<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarketingListResource\Pages;
use App\Models\MarketingList;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MarketingListResource extends Resource
{
    protected static ?string $model = MarketingList::class;
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?string $navigationLabel = 'Email Lists';

   public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make('Audience List')
                ->description('Paste your CSV data or enter emails manually.')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->placeholder('e.g., Hotel Managers Expo 2026')
                        ->maxLength(255),

                    // 💡 THE FIX: Use a Textarea to bypass server file validation
                    Forms\Components\Textarea::make('csv_paste')
                        ->label('Paste CSV / Excel Data')
                        ->placeholder("Open your CSV in Notepad or Excel, Copy everything, and Paste it here.")
                        ->rows(5)
                        ->helperText('We will automatically find and extract the emails from whatever you paste.')
                        ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                            if (!$state) return;

                            // 🔍 Search for emails in the pasted text
                            preg_match_all('/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}/i', $state, $matches);
                            
                            if (!empty($matches[0])) {
                                $existingEmails = $get('emails') ?? [];
                                $newEmails = array_unique(array_merge($existingEmails, $matches[0]));
                                $set('emails', $newEmails);
                                
                                // Clear the paste box after successful extraction
                                $set('csv_paste', '');

                                \Filament\Notifications\Notification::make()
                                    ->title(count($matches[0]) . ' Emails Added')
                                    ->success()
                                    ->send();
                            }
                        })
                        ->live(onBlur: true), // Triggers when you click outside the box

                    Forms\Components\TagsInput::make('emails')
                        ->label('Target Email Addresses')
                        ->placeholder('Emails will appear here...')
                        ->required()
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('emails')
                    ->label('Total Contacts')
                    ->getStateUsing(fn ($record) => count($record->emails ?? []) . ' Emails')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->date()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarketingLists::route('/'),
            'create' => Pages\CreateMarketingList::route('/create'),
            'edit' => Pages\EditMarketingList::route('/{record}/edit'),
        ];
    }
}