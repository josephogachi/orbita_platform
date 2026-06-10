<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CampaignResource\Pages;
use App\Models\Campaign;
use App\Models\MarketingList;
use App\Models\MarketingAsset;
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
                Forms\Components\Grid::make(3)
                    ->schema([
                        // LEFT COLUMN: Promotion Content
                        Forms\Components\Section::make('Compose Promotion')
                            ->columnSpan(2)
                            ->schema([
                                Forms\Components\TextInput::make('subject')
                                    ->required()
                                    ->placeholder('e.g. Special Offer: 15% Off Smart Locks!')
                                    ->maxLength(255),
                                
                                Forms\Components\RichEditor::make('content')
                                    ->required()
                                    ->label('Email Body')
                                    ->helperText('Use [email] to automatically insert the recipient\'s email address.')
                                    ->fileAttachmentsDisk('public') 
                                    ->fileAttachmentsDirectory('marketing/campaigns')
                                    ->fileAttachmentsVisibility('public') 
                                    ->toolbarButtons([
                                        'attachFiles', 'bold', 'italic', 'link', 'bulletList', 'orderedList', 'h2', 'h3', 'redo', 'undo',
                                    ]),

                                Forms\Components\Fieldset::make('Add Extras')
                                    ->schema([
                                        Forms\Components\FileUpload::make('attachments')
                                            ->label('Attach Documents (PDFs, Word, Excel, CSV, Zip, Images)')
                                            ->multiple()
                                            ->directory('marketing/attachments')
                                            ->preserveFilenames()
                                            ->maxSize(65536) 
                                            ->acceptedFileTypes([
                                                'application/pdf', 'text/plain', 'text/csv', 'application/csv',
                                                'application/zip', 'application/x-zip-compressed', 'application/msword', 
                                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                                                'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
                                                'application/excel', 'application/vnd.msexcel', 'image/jpeg', 'image/jpg',
                                                'image/png', 'image/gif', 'image/webp',
                                            ])
                                            ->columnSpanFull(),

                                        Forms\Components\TextInput::make('action_button.text')
                                            ->label('Button Text (e.g., Shop Now)'),

                                        Forms\Components\TextInput::make('action_button.url')
                                            ->label('Button Link (URL)')
                                            ->url(),
                                    ]),
                            ]),

                        // RIGHT COLUMN: Settings & Scheduling
                        Forms\Components\Group::make([
                            Forms\Components\Section::make('Settings')
                                ->schema([
                                    Forms\Components\Select::make('marketing_list_id')
                                        ->label('Target Email List')
                                        ->options(MarketingList::pluck('name', 'id'))
                                        ->required(),

                                    // REMOVED required() and added a placeholder
                                    Forms\Components\Select::make('header_id')
                                        ->label('Brand Header')
                                        ->options(MarketingAsset::where('type', 'header')->pluck('name', 'id'))
                                        ->placeholder('None (Optional)'),

                                    // REMOVED required() and added a placeholder
                                    Forms\Components\Select::make('footer_id')
                                        ->label('Brand Footer')
                                        ->options(MarketingAsset::where('type', 'footer')->pluck('name', 'id'))
                                        ->placeholder('None (Optional)'),
                                ]),

                            // ⏱️ NEW: Delivery Schedule Section
                            Forms\Components\Section::make('Delivery Schedule')
                                ->schema([
                                    Forms\Components\Select::make('status')
                                        ->label('Send Status')
                                        ->options([
                                            'draft' => 'Draft (Do not send yet)',
                                            'scheduled' => 'Scheduled (Wait for date/time)',
                                            'completed' => 'Completed',
                                        ])
                                        ->default('draft')
                                        ->required()
                                        ->live(),

                                    Forms\Components\DateTimePicker::make('scheduled_at')
                                        ->label('Schedule Delivery Time')
                                        ->helperText('Select the exact date and time this email should be dispatched.')
                                        ->minDate(now())
                                        ->native(false)
                                        ->prefixIcon('heroicon-o-clock')
                                        ->visible(fn (Forms\Get $get) => $get('status') === 'scheduled')
                                        ->requiredIf('status', 'scheduled'),
                                ]),
                        ])->columnSpan(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject')->searchable()->weight('bold')->wrap(),
                
                Tables\Columns\TextColumn::make('approval_status')
                    ->label('Approval')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'draft' => 'gray',
                        'pending' => 'warning',
                        'approved' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => ucfirst($state)),

                // 🚦 NEW: Delivery Status Badge
                Tables\Columns\TextColumn::make('status')
                    ->label('Delivery')
                    ->badge()
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'draft' => 'gray',
                        'scheduled' => 'warning',
                        'sending' => 'info',
                        'completed' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => ucfirst($state)),

                // ⏱️ NEW: Scheduled Time Column
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label('Scheduled For')
                    ->dateTime('M d, Y - h:i A')
                    ->sortable()
                    ->icon('heroicon-o-clock')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('marketingList.name')->label('Audience List')->icon('heroicon-o-users')->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('status_log')
                    ->label('Live Report')
                    ->getStateUsing(function (Campaign $record) {
                        if (!$record->sent_at) return 'Not Sent';
                        $log = $record->status_log ?? [];
                        $opened = collect($log)->filter(fn($d) => (is_array($d) && ($d['status'] ?? '') === 'opened') || (!is_array($d)))->count();
                        $failed = collect($log)->filter(fn($d) => is_array($d) && ($d['status'] ?? '') === 'failed')->count();
                        return "{$opened} Opened • {$failed} Failed";
                    })
                    ->badge()
                    ->color(fn (string $state): string => $state === 'Not Sent' ? 'gray' : 'info')
                    ->icon('heroicon-o-chart-pie')
                    ->action(
                        Tables\Actions\Action::make('view_report_column')
                            ->modalHeading(fn(Campaign $record) => 'Analytics: ' . $record->subject)
                            ->modalWidth('4xl')
                            ->modalContent(fn (Campaign $record) => view('filament.campaign-report', [
                                'log' => $record->status_log ?? [],
                                'total_list' => $record->marketingList->emails ?? []
                            ]))
                    ),

                Tables\Columns\TextColumn::make('sent_at')->label('Dispatched')->dateTime('M d, Y - H:i')->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                
                Tables\Actions\EditAction::make()
                    ->visible(function (Campaign $record) {
                        $user = auth()->user();
                        $isAdmin = $user->id === 1 || $user->email === 'support@orbitakenya.com' || strtolower($user->role ?? '') === 'admin';
                        $status = strtolower(trim($record->approval_status ?? 'draft'));
                        
                        return $isAdmin || in_array($status, ['draft', 'pending', '']);
                    }),
                
                Tables\Actions\Action::make('request_approval')
                    ->label('Submit for Approval')
                    ->icon('heroicon-o-clock')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (Campaign $record) {
                        $record->update(['approval_status' => 'pending']);
                        Notification::make()->title('Submitted to Admin!')->success()->send();
                    })
                    ->visible(fn (Campaign $record) => in_array(strtolower(trim($record->approval_status ?? 'draft')), ['draft', '']) && $record->sent_at === null),

                Tables\Actions\Action::make('approve_campaign')
                    ->label('Approve')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Campaign $record) {
                        $record->update(['approval_status' => 'approved']);
                        Notification::make()->title('Campaign Approved!')->success()->send();
                    })
                    ->visible(fn (Campaign $record) => strtolower(trim($record->approval_status ?? '')) === 'pending' && (auth()->user()->id === 1 || auth()->user()->email === 'support@orbitakenya.com' || strtolower(auth()->user()->role ?? '') === 'admin')),

                Tables\Actions\Action::make('reject_campaign')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Campaign $record) {
                        $record->update(['approval_status' => 'draft']);
                        Notification::make()->title('Campaign Rejected. Sent back to Draft.')->danger()->send();
                    })
                    ->visible(fn (Campaign $record) => strtolower(trim($record->approval_status ?? '')) === 'pending' && (auth()->user()->id === 1 || auth()->user()->email === 'support@orbitakenya.com' || strtolower(auth()->user()->role ?? '') === 'admin')),

                // 🚀 BLAST ACTION (Manual Send)
                Tables\Actions\Action::make('send_to_list')
                    ->label('Force Send Now')
                    ->icon('heroicon-s-paper-airplane')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->modalDescription('This will ignore any schedule and blast the email out immediately. Are you sure?')
                    ->action(function (Campaign $record) {
                        $emails = $record->marketingList->emails ?? [];
                        $log = $record->status_log ?? [];

                        if (empty($emails)) {
                            Notification::make()->title('List is empty')->danger()->send();
                            return;
                        }

                        $successCount = 0; $failCount = 0;

                        // Mark as sending
                        $record->update(['status' => 'sending']);

                        foreach ($emails as $email) {
                            try {
                                $tempSub = Subscriber::where('email', $email)->first() ?? new Subscriber(['email' => $email, 'id' => 0]);
                                
                                $personalizedCampaign = clone $record;
                                $personalizedCampaign->content = str_replace('[email]', $email, $record->content);

                                Mail::to($email)->send(
                                    (new PromotionMail($personalizedCampaign, $tempSub))
                                        ->from('info@orbitakenya.com', 'Orbita Kenya')
                                );
                                
                                $log[$email] = ['status' => 'delivered', 'at' => now()->toDateTimeString()];
                                $successCount++;
                            } catch (\Exception $e) {
                                $log[$email] = ['status' => 'failed', 'error' => $e->getMessage()];
                                $failCount++;
                            }
                        }

                        $record->update(['sent_at' => now(), 'status_log' => $log, 'status' => 'completed']);
                        Notification::make()->title('Liftoff! 🚀')->body("Sent: $successCount | Failed: $failCount")->success()->send();
                    })
                    // Hidden if already sent, OR if not approved
                    ->hidden(fn (Campaign $record) => $record->sent_at !== null || strtolower(trim($record->approval_status ?? '')) !== 'approved'),

                Tables\Actions\Action::make('resend_campaign')
                    ->label('Resend')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn (Campaign $record) => $record->sent_at !== null)
                    ->form([
                        Forms\Components\Radio::make('resend_target')
                            ->label('Who should receive this?')
                            ->options(['failed' => 'Only Failed/Bounced emails', 'all' => 'Entire list again'])
                            ->default('failed')->required(),
                    ])
                    ->action(function (Campaign $record, array $data) {
                        $allEmails = $record->marketingList->emails ?? [];
                        $log = $record->status_log ?? [];
                        
                        $targetEmails = ($data['resend_target'] === 'failed') 
                            ? collect($log)->filter(fn($d) => is_array($d) && strtolower(trim($d['status'] ?? '')) === 'failed')->keys()->toArray()
                            : $allEmails;

                        if (empty($targetEmails)) {
                            Notification::make()->title('No targets found')->warning()->send();
                            return;
                        }

                        $successCount = 0; $failCount = 0;
                        foreach ($targetEmails as $email) {
                            try {
                                $tempSub = Subscriber::where('email', $email)->first() ?? new Subscriber(['email' => $email, 'id' => 0]);
                                
                                $personalizedCampaign = clone $record;
                                $personalizedCampaign->content = str_replace('[email]', $email, $record->content);

                                Mail::to($email)->send(
                                    (new PromotionMail($personalizedCampaign, $tempSub))
                                        ->from('info@orbitakenya.com', 'Orbita Kenya')
                                );
                                $log[$email] = ['status' => 'delivered', 'at' => now()->toDateTimeString()];
                                $successCount++;
                            } catch (\Exception $e) {
                                $log[$email] = ['status' => 'failed', 'error' => $e->getMessage()];
                                $failCount++;
                            }
                        }

                        $record->update(['status_log' => $log, 'updated_at' => now()]);
                        Notification::make()->title('Resend Complete')->body("Sent: $successCount | Failed: $failCount")->success()->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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