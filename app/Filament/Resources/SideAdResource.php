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
    
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';
    protected static ?string $navigationGroup = 'Website Content';
    protected static ?string $navigationLabel = 'Hero Side Ads';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                                    ->placeholder('https://orbitakenya.com/category/deals'),
                            ]),
                        ]),
                ])->columnSpan(2),

                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Visuals & Logic')->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Product Image')
                            ->image()
                            ->imageEditor()
                            ->directory('ads')
                            ->required()
                            ->helperText('Use transparent PNGs or portrait-oriented product shots.'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Status')
                            ->helperText('Visible on site')
                            ->default(true)
                            ->onColor('success'),
                            
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Sort Position')
                            ->numeric()
                            ->default(0),
                    ]),
                ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Thumbnail')
                    ->height(60)
                    ->backgroundStyle('gray') // High contrast for product cutouts
                    ->square(),

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
            ->reorderable('sort_order') // Enables manual drag-drop
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