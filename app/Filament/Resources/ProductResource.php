<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
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
                // LEFT & MIDDLE COLUMNS
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Product Information')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, $state) => $set('slug', Str::slug($state))),

                            Forms\Components\TextInput::make('slug')
                                ->required()
                                ->disabled()
                                ->dehydrated()
                                ->unique(Product::class, 'slug', ignoreRecord: true),

                            Forms\Components\MarkdownEditor::make('description')
                                ->label('Marketing Description (Markdown Supported)')
                                ->helperText('Use **bold** or - lists for the masterpiece view.')
                                ->columnSpanFull(),

                            Forms\Components\Textarea::make('technical_specs')
                                ->label('Technical Specifications')
                                ->helperText('Format as "Key: Value" on each line (e.g., Material: Zinc Alloy)')
                                ->placeholder("Bluetooth: 5.0\nBattery: 4x AA\nFinish: Matte Black")
                                ->rows(5)
                                ->columnSpanFull(),
                        ])->columns(2),

                    Forms\Components\Section::make('Assets & Media')
                        ->schema([
                            Forms\Components\FileUpload::make('images')
                                ->multiple()
                                ->directory('products')
                                ->maxFiles(5)
                                ->reorderable()
                                ->image()
                                ->imageEditor()
                                ->columnSpanFull(),

                            Forms\Components\FileUpload::make('pdf_datasheet')
                                ->label('Technical Datasheet (PDF)')
                                ->directory('product-docs')
                                ->acceptedFileTypes(['application/pdf'])
                                ->helperText('Only logged-in users can download this on the frontend.')
                                ->maxSize(5120), 
                        ])
                ])->columnSpan(2),

                // RIGHT COLUMN
                Forms\Components\Group::make()->schema([
                    
                    // 1. INVENTORY & LOGISTICS (Restored Stock Quantity)
                    Forms\Components\Section::make('Inventory & Logistics')->schema([
                        Forms\Components\TextInput::make('sku')
                            ->label('Product SKU')
                            ->default(fn () => 'ORB-' . strtoupper(Str::random(6)))
                            ->required(),

                        // ✅ RESTORED: Stock Input
                        Forms\Components\TextInput::make('stock_quantity')
                            ->label('Current Stock')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->prefix('Qty'),

                        Forms\Components\TextInput::make('weight')
                            ->label('Unit Weight')
                            ->numeric()
                            ->default(1.0)
                            ->suffix('kg')
                            ->helperText('Used for shipping fees.')
                            ->required(),
                    ]),

                    // 2. PRICING
                    Forms\Components\Section::make('Pricing')->schema([
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->required()
                            ->prefix('KES'),
                        
                        Forms\Components\TextInput::make('old_price')
                            ->numeric()
                            ->label('Strike-through Price')
                            ->prefix('KES'),
                    ]),

                    // 3. ASSOCIATIONS
                    Forms\Components\Section::make('Associations')->schema([
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->preload(),

                        Forms\Components\Select::make('brand_id')
                            ->relationship('brand', 'name')
                            ->preload(),
                    ]),

                    // 4. STATUS
                    Forms\Components\Section::make('Visibility')->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Published')
                            ->default(true),
                        
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured on Home'),

                        Forms\Components\Toggle::make('is_hot')
                            ->label('Flash Sale / Hot'),
                    ]),
                ])->columnSpan(1)
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('Thumbnail')
                    ->circular()
                    ->stacked()
                    ->limit(1), 
                
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('category.name')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('price')
                    ->money('KES')
                    ->sortable()
                    ->color('success')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('weight')
                    ->suffix(' kg')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->badge()
                    ->color(fn ($state) => $state < 10 ? 'danger' : 'success'),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Live'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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