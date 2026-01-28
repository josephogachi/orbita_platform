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
                // LEFT & MIDDLE COLUMNS (Main Content)
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Product Information')
                        ->description('Basic details and technical specifications.')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, $state) => $set('slug', Str::slug($state))),

                            Forms\Components\TextInput::make('slug')
                                ->required()
                                ->maxLength(255)
                                ->disabled()
                                ->dehydrated()
                                ->unique(Product::class, 'slug', ignoreRecord: true),

                            Forms\Components\MarkdownEditor::make('description')
                                ->label('Marketing Description')
                                ->columnSpanFull(),

                            Forms\Components\Textarea::make('technical_specs')
                                ->label('Technical Specifications')
                                ->helperText('Enter each specification on a new line.')
                                ->placeholder("Bluetooth 5.0\nBattery: 4x AA\nMaterial: Zinc Alloy")
                                ->rows(5)
                                ->columnSpanFull(),
                        ])->columns(2),

                    Forms\Components\Section::make('Product Images')
                        ->schema([
                            Forms\Components\FileUpload::make('images')
                                ->multiple()
                                ->directory('products')
                                ->maxFiles(5)
                                ->reorderable()
                                ->image()
                                ->imageEditor()
                                ->columnSpanFull(),
                        ])
                ])->columnSpan(2),

                // RIGHT COLUMN (Price, Inventory & Marketing)
                Forms\Components\Group::make()->schema([
                    
                    // 1. PRICING
                    Forms\Components\Section::make('Pricing')->schema([
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->required()
                            ->prefix('KES')
                            ->helperText('Active selling price.'),
                        
                        Forms\Components\TextInput::make('old_price')
                            ->numeric()
                            ->label('Original (Old) Price')
                            ->prefix('KES')
                            ->helperText('Setting this triggers a discount badge.'),

                        Forms\Components\TextInput::make('wholesale_price')
                            ->numeric()
                            ->prefix('KES'),

                        Forms\Components\TextInput::make('cost_price')
                            ->numeric()
                            ->label('Cost (Internal)')
                            ->helperText('Visible only to authorized admins')
                            ->prefix('KES'),
                    ]),

                    // 2. MARKETING
                    Forms\Components\Section::make('Marketing & Visibility')->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Toggle::make('is_hot')
                                ->label('Flash Sale')
                                ->onColor('danger'),
                            
                            Forms\Components\Toggle::make('is_new')
                                ->label('New Arrival')
                                ->default(true)
                                ->onColor('success'),

                            Forms\Components\Toggle::make('is_sponsored')
                                ->label('Sponsored')
                                ->onColor('warning'),
                                
                            Forms\Components\Toggle::make('is_featured')
                                ->label('Pin Home')
                                ->default(false),
                        ]),

                        Forms\Components\TextInput::make('affiliate_link')
                            ->label('Partner Link')
                            ->placeholder('https://external-store.com')
                            ->url()
                            ->visible(fn (Forms\Get $get) => $get('is_sponsored')),
                    ]),

                    // 3. ASSOCIATIONS
                    Forms\Components\Section::make('Associations')->schema([
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('brand_id')
                            ->relationship('brand', 'name')
                            ->searchable()
                            ->preload(),
                    ]),

                    // 4. INVENTORY
                    Forms\Components\Section::make('Inventory Control')->schema([
                        Forms\Components\TextInput::make('sku')
                            ->label('Product SKU')
                            ->default(fn () => 'ORB-' . strtoupper(Str::random(6)))
                            ->required(),

                        Forms\Components\TextInput::make('stock_quantity')
                            ->numeric()
                            ->required()
                            ->default(0),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Published')
                            ->default(true),
                    ])
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
                    ->weight('bold')
                    ->description(fn (Product $record) => Str::limit($record->description, 40)),
                
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('price')
                    ->money('KES')
                    ->sortable()
                    ->color('primary')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('old_price')
                    ->label('Was')
                    ->money('KES')
                    ->color('gray')
                    ->lineThrough() // Native Filament v3 method
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_hot')
                    ->label('Hot')
                    ->boolean()
                    ->trueColor('danger')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('discount_percent')
                    ->label('Disc.')
                    ->getStateUsing(fn (Product $record) => $record->discount_percent > 0 ? "-{$record->discount_percent}%" : null)
                    ->badge()
                    ->color('warning')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state < 10 ? 'danger' : ($state < 50 ? 'warning' : 'success')),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Live'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
                
                Tables\Filters\TernaryFilter::make('is_hot')->label('Hot Sale'),
                Tables\Filters\TernaryFilter::make('is_active')->label('Published'),
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