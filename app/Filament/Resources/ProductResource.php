<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    protected static ?string $navigationGroup = 'Shop Management';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Product Information')->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, $set) => 
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->disabled()
                            ->dehydrated()
                            ->unique(Product::class, 'slug', ignoreRecord: true),

                        Forms\Components\MarkdownEditor::make('description')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('technical_specs')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                    Forms\Components\Section::make('Product Images')->schema([
                        Forms\Components\FileUpload::make('images')
                            ->multiple()
                            ->directory('products')
                            ->maxFiles(5)
                            ->reorderable()
                            ->columnSpanFull(),
                    ])
                ])->columnSpan(2),

                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Pricing')->schema([
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->required()
                            ->prefix('KES'),
                        
                        Forms\Components\TextInput::make('wholesale_price')
                            ->numeric()
                            ->prefix('KES'),

                        Forms\Components\TextInput::make('cost_price')
                            ->numeric()
                            ->helperText('Only visible to admins')
                            ->prefix('KES'),
                    ]),

                    Forms\Components\Section::make('Associations')->schema([
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required(),

                        Forms\Components\Select::make('brand_id')
                            ->relationship('brand', 'name'),
                    ]),

                    Forms\Components\Section::make('Inventory')->schema([
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU (Stock Keeping Unit)')
                            ->default('ORB-' . random_int(10000, 99999))
                            ->required(),

                        Forms\Components\TextInput::make('stock_quantity')
                            ->numeric()
                            ->required()
                            ->default(0),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                        
                        Forms\Components\Toggle::make('is_featured')
                            ->default(false),
                    ])
                ])->columnSpan(1)
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->circular()
                    ->stacked()
                    ->limit(1), // Show only 1 image in table preview
                
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable()
                    ->searchable()
                    ->badge(),

                Tables\Columns\TextColumn::make('price')
                    ->money('KES')
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
            ])
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}