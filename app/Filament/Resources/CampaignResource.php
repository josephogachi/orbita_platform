<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CampaignResource\Pages;
use App\Models\Campaign;
use App\Models\Subscriber;
use App\Mail\PromotionMail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';

    protected static ?string $navigationGroup = 'Marketing';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Compose Promotion')
                    ->description('Write the email that will be sent to your subscribers.')
                    ->schema([
                        Forms\Components\TextInput::make('subject')
                            ->required()
                            ->placeholder('e.g. Special Offer: 15% Off All Hotel Locks')
                            ->maxLength(255),
                        
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->label('Email Body')
                            ->toolbarButtons([
                                'bold', 'italic', 'link', 'bulletList', 'orderedList', 'redo', 'undo',
                            ])
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                
                Tables\Columns\TextColumn::make('sent_at')
                    ->label('Dispatched On')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Not sent yet'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),

                // 🧪 TEST SEND (PREVIEW) ACTION
                Tables\Actions\Action::make('preview_campaign')
                    ->label('Test Send')
                    ->icon('heroicon-o-beaker')
                    ->color('info')
                    ->form([
                        Forms\Components\TextInput::make('test_email')
                            ->label('Recipient Email')
                            ->email()
                            ->default(auth()->user()->email)
                            ->required(),
                    ])
                    ->action(function (Campaign $record, array $data) {
                        try {
                            // Find the subscriber in the DB or create a "dummy" for the test
                            $subscriber = Subscriber::where('email', $data['test_email'])->first() 
                                          ?? new Subscriber(['email' => $data['test_email'], 'id' => 0]);

                            // 🟢 Fixed: Pass both the campaign and the subscriber
                            Mail::to($data['test_email'])->send(new PromotionMail($record, $subscriber));

                            Notification::make()
                                ->title('Test Email Sent')
                                ->body("A preview has been sent to {$data['test_email']}")
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            \Log::error("Preview failed: " . $e->getMessage());
                            Notification::make()
                                ->title('Preview Failed')
                                ->body('Check your SMTP settings in .env')
                                ->danger()
                                ->send();
                        }
                    }),

                // 🚀 THE FINAL SEND ACTION
                Tables\Actions\Action::make('send_campaign')
                    ->label('Send to All')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Dispatch')
                    ->modalDescription('Are you sure you want to send this promotion to all active subscribers? This action cannot be undone.')
                    ->action(function (Campaign $record) {
                        $subscribers = Subscriber::where('is_active', true)->get();

                        if ($subscribers->isEmpty()) {
                            Notification::make()
                                ->title('No Subscribers Found')
                                ->danger()
                                ->send();
                            return;
                        }

                        foreach ($subscribers as $subscriber) {
                            try {
                                // 🟢 Fixed: Pass both the campaign and the specific subscriber for the unsubscribe link
                                Mail::to($subscriber->email)->send(new PromotionMail($record, $subscriber));
                            } catch (\Exception $e) {
                                \Log::error("Failed to send email to {$subscriber->email}: " . $e->getMessage());
                            }
                        }

                        $record->update(['sent_at' => now()]);

                        Notification::make()
                            ->title('Campaign Dispatched')
                            ->body("Successfully sent to " . $subscribers->count() . " subscribers.")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCampaigns::route('/'),
            'create' => Pages\CreateCampaign::route('/create'),
            'edit' => Pages\EditCampaign::route('/{record}/edit'),
        ];
    }
}