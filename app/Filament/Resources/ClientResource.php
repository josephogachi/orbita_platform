<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Website Content';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Partners & Clients';

    /**
     * 🔒 SECURITY: HARD-CODE "ADMIN ONLY" ACCESS
     * This ensures Sales Agents (or anyone else) CANNOT see this menu item.
     */
    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Client / Partner Details')
                    ->description('These logos appear in the "Trusted By" marquee on the homepage.')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Company Name')
                                ->placeholder('e.g., Sarova Hotels')
                                ->required()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('website')
                                ->label('Client Website (Optional)')
                                ->url()
                                ->prefix('https://')
                                ->placeholder('www.client-site.com'),
                        ]),

                        Forms\Components\FileUpload::make('logo_path')
                            ->label('Client Logo')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('clients')
                            ->visibility('public')
                            ->required()
                            ->helperText('Preferred format: Transparent PNG.')
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Toggle::make('is_visible')
                                ->label('Visible on Home Page')
                                ->default(true)
                                ->onColor('success'),

                            Forms\Components\TextInput::make('sort_order')
                                ->numeric()
                                ->default(0)
                                ->label('Priority Order'),
                        ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->height(50)
                    ->disk('public')
                    ->extraAttributes(['style' => 'background-color: #f3f4f6; border-radius: 8px; padding: 4px; border: 1px solid #e5e7eb;']),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Company')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('website')
                    ->label('Link')
                    ->icon('heroicon-m-link')
                    ->color('gray')
                    ->url(fn ($state) => $state, true)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\ToggleColumn::make('is_visible')
                    ->label('Live Status'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Sort')
                    ->sortable()
                    ->badge(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}