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
                // LEFT COLUMN (Main Info)
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

                // RIGHT COLUMN (Settings & Marketing)
                Forms\Components\Group::make()->schema([
                    
                    // 1. PRICING
                    Forms\Components\Section::make('Pricing')->schema([
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->required()
                            ->prefix('KES')
                            ->helperText('This is the current selling price.'),
                        
                        Forms\Components\TextInput::make('old_price')
                            ->numeric()
                            ->label('Original (Old) Price')
                            ->prefix('KES')
                            ->helperText('If set higher than current price, a discount badge will appear.'),

                        Forms\Components\TextInput::make('wholesale_price')
                            ->numeric()
                            ->prefix('KES'),

                        Forms\Components\TextInput::make('cost_price')
                            ->numeric()
                            ->helperText('Visible only to admins')
                            ->prefix('KES'),
                    ]),

                    // 2. MARKETING
                    Forms\Components\Section::make('Marketing & Visibility')->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\Toggle::make('is_hot')
                                ->label('Flash Sale / Hot')
                                ->onColor('danger'),
                            
                            Forms\Components\Toggle::make('is_new')
                                ->label('New Arrival')
                                ->default(true)
                                ->onColor('success'),

                            Forms\Components\Toggle::make('is_sponsored')
                                ->label('Sponsored Item')
                                ->onColor('warning'),
                                
                            Forms\Components\Toggle::make('is_featured')
                                ->label('Pin to Homepage')
                                ->default(false),
                        ]),

                        Forms\Components\TextInput::make('affiliate_link')
                            ->label('External Link')
                            ->placeholder('https://partner-site.com')
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
                            ->searchable(),
                    ]),

                    // 4. INVENTORY
                    Forms\Components\Section::make('Inventory')->schema([
                        Forms\Components\TextInput::make('sku')
                            ->label('SKU')
                            ->default(fn () => 'ORB-' . random_int(10000, 99999))
                            ->required(),

                        Forms\Components\TextInput::make('stock_quantity')
                            ->numeric()
                            ->required()
                            ->default(0),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Visible on Website')
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
                    ->circular()
                    ->stacked()
                    ->limit(1), 
                
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Product $record) => Str::limit($record->description, 30)),
                
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('price')
                    ->money('KES')
                    ->sortable(),

                Tables\Columns\TextColumn::make('old_price')
    ->label('Was')
    ->money('KES')
    ->color('gray')
    // This replaces the strikethrough/lineThrough method with direct CSS classes
    ->extraAttributes([
        'class' => 'line-through',
    ])
    ->toggleable(),
                // Marketing Badges
                Tables\Columns\IconColumn::make('is_hot')
                    ->label('Hot')
                    ->boolean()
                    ->trueColor('danger')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('discount_percent')
                    ->label('Discount')
                    ->getStateUsing(fn (Product $record) => $record->discount_percent > 0 ? "-{$record->discount_percent}%" : null)
                    ->badge()
                    ->color('warning')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->numeric()
                    ->sortable()
                    ->color(fn ($state) => $state < 10 ? 'danger' : 'success'),

                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
                
                Tables\Filters\TernaryFilter::make('is_hot')->label('Flash Sale / Hot'),
                Tables\Filters\TernaryFilter::make('is_new')->label('New Arrivals'),
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