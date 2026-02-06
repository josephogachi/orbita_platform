<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;
    
    // Use an Inbox Icon
    protected static ?string $navigationIcon = 'heroicon-o-inbox';
    protected static ?string $navigationGroup = 'Admin Management';
    protected static ?string $label = 'Inbox Message';
    protected static ?int $navigationSort = 1;

    // Show a badge count of unread messages in the sidebar
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_read', false)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger'; // Red badge for attention
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Message Details')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Sender Name')
                                ->disabled(), // Read-only
                            
                            Forms\Components\TextInput::make('email')
                                ->label('Email Address')
                                ->email()
                                ->suffixIcon('heroicon-m-envelope')
                                ->url(fn ($state) => "mailto:{$state}") // Click to email
                                ->disabled(),
                                
                            Forms\Components\TextInput::make('phone')
                                ->label('Phone Number')
                                ->prefixIcon('heroicon-m-phone')
                                ->disabled(),
                                
                            Forms\Components\DatePicker::make('created_at')
                                ->label('Received On')
                                ->disabled(),
                        ]),
                        
                        Forms\Components\TextInput::make('subject')
                            ->columnSpanFull()
                            ->disabled(),

                        Forms\Components\Textarea::make('message')
                            ->columnSpanFull()
                            ->rows(6)
                            ->disabled(),
                    ])->columnSpan(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_read')
                            ->label('Mark as Read')
                            ->onColor('success')
                            ->offColor('danger')
                            ->helperText('Toggle this when you have responded to the client.'),
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (ContactMessage $record) => $record->email),

                Tables\Columns\TextColumn::make('subject')
                    ->limit(30)
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_read')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-envelope')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc') // Newest first
            ->filters([
                Tables\Filters\Filter::make('unread')
                    ->label('Show Unread Only')
                    ->query(fn ($query) => $query->where('is_read', false)),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('View Message'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('mark_read')
                    ->label('Mark Selected as Read')
                    ->icon('heroicon-o-check')
                    ->action(fn ($records) => $records->each->update(['is_read' => true]))
                    ->color('success'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'create' => Pages\CreateContactMessage::route('/create'),
            'edit' => Pages\EditContactMessage::route('/{record}/edit'),
        ];
    }
}