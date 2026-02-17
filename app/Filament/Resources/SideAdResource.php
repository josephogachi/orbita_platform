<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SideAdResource\Pages;
use App\Models\SideAd;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SideAdResource extends Resource
{
    protected static ?string $model = SideAd::class;
    
    // Navigation Configuration
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';
    protected static ?string $navigationGroup = 'Website Content';
    protected static ?string $navigationLabel = 'Hero Side Ads';
    protected static ?int $navigationSort = 2;

    /**
     * 🔒 SECURITY: HARD-CODE "ADMIN ONLY" ACCESS
     * This ensures Sales Agents CANNOT see or access Hero Side Ads.
     */
    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // LEFT COLUMN: Text Content
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Ad Content')
                        ->description('These ads appear in the vertical slider next to the main hero banner.')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Main Title')
                                ->required()
                                ->placeholder('e.g., Fingerprint Access')
                                ->maxLength(255),
                            
                            Forms\Components\TextInput::make('subtitle')
                                ->label('Supporting Text')
                                ->placeholder('e.g., 30% Off Bulk Orders')
                                ->maxLength(255),
                                
                            Forms\Components\TextInput::make('badge_text')
                                ->label('Promo Badge')
                                ->placeholder('e.g., TRENDING')
                                ->default('FEATURED DEAL'),

                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('button_text')
                                    ->label('Button Label')
                                    ->default('View Deal')
                                    ->required(),
                                    
                                Forms\Components\TextInput::make('link_url')
                                    ->label('Redirect URL')
                                    ->url()
                                    ->prefix('https://')
                                    ->placeholder('orbitakenya.com/deals'),
                            ]),
                        ]),
                ])->columnSpan(2),

                // RIGHT COLUMN: Visuals & Status
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Visuals & Logic')->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Product Image')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('ads')
                            ->visibility('public') // Critical for public viewing
                            ->required()
                            ->helperText('Use transparent PNGs or portrait-oriented product shots.'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Live Status')
                            ->helperText('Switch off to hide this ad temporarily.')
                            ->default(true)
                            ->onColor('success'),
                            
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Position')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first.'),
                    ]),
                ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // IMAGE: Added grey background to make transparent PNGs visible
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Thumbnail')
                    ->height(60)
                    ->disk('public')
                    ->extraAttributes(['style' => 'background-color: #f3f4f6; border-radius: 8px; padding: 4px; border: 1px solid #e5e7eb;']),

                Tables\Columns\TextColumn::make('title')
                    ->label('Ad Heading')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (SideAd $record) => $record->subtitle),

                Tables\Columns\TextColumn::make('badge_text')
                    ->label('Badge')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Live'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->badge(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order') // Enable Drag & Drop sorting
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
            'index' => Pages\ListSideAds::route('/'),
            'create' => Pages\CreateSideAd::route('/create'),
            'edit' => Pages\EditSideAd::route('/{record}/edit'),
        ];
    }
}