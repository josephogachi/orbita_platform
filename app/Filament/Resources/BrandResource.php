<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-badge';
    protected static ?string $navigationGroup = 'Shop Management';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Brand Identity')
                    ->description('Manage manufacturer details and brand presence.')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Brand Name')
                                ->placeholder('e.g., Orbita')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, $state) => $set('slug', Str::slug($state))),

                            Forms\Components\TextInput::make('slug')
                                ->required()
                                ->disabled()
                                ->dehydrated()
                                ->unique(Brand::class, 'slug', ignoreRecord: true),
                        ]),

                        Forms\Components\TextInput::make('website')
                            ->label('Official Website')
                            ->placeholder('www.orbitatech.com')
                            ->url()
                            ->prefix('https://')
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('logo')
                            ->label('Brand Logo')
                            ->image()
                            ->imageEditor()
                            ->directory('brands')
                            ->columnSpanFull(),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active Partner')
                            ->helperText('Visible on the frontend brand filters and product pages.')
                            ->default(true)
                            ->onColor('success'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->label('Logo')
                    ->height('40px')
                    ->disk('public'), // Ensure this matches your storage config
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Brand')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('website')
                    ->label('URL')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->iconColor('primary')
                    ->color('primary')
                    ->url(fn ($state) => $state, true) // Makes the text clickable to open in a new tab
                    ->toggleable(),

                // Shows how many products are currently linked to this brand
                Tables\Columns\TextColumn::make('products_count')
                    ->label('Catalog Size')
                    ->counts('products')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Status'),
            ])
            ->defaultSort('name')
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
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}