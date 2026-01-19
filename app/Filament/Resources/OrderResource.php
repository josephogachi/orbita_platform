<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Hash; // <--- IMPORTANT IMPORT

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Shop Management';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // LEFT COLUMN: Order Details
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Order Information')->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->default('ORB-' . random_int(100000, 999999))
                            ->required()
                            ->readOnly(),
                            
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Customer')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('email')
                                    ->label('Email Address')
                                    ->email()
                                    ->required()
                                    ->unique('users', 'email'),

                                // THIS IS THE MISSING FIELD FIX
                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->required()
                                    ->minLength(8)
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->visible(fn ($operation) => $operation === 'create'),
                            ]),
                        
                        Forms\Components\Select::make('status')
                            ->options([
                                'new' => 'New',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('new'),

                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'paid' => 'Paid',
                                'unpaid' => 'Unpaid',
                                'partial' => 'Partial',
                            ])
                            ->default('unpaid'),
                            
                        Forms\Components\Select::make('payment_method')
                            ->options([
                                'stripe' => 'Stripe',
                                'mpesa' => 'M-Pesa',
                                'cash' => 'Cash (POS)',
                                'bank_transfer' => 'Bank Transfer',
                            ]),
                    ])->columns(2),

                    // ORDER ITEMS (Repeater)
                    Forms\Components\Section::make('Order Items')->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->distinct()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, Set $set) => 
                                        $set('unit_price', Product::find($state)?->price ?? 0)
                                    ),
                                
                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, Set $set, Get $get) => 
                                        $set('total_price', $state * $get('unit_price'))
                                    ),
                                
                                Forms\Components\TextInput::make('unit_price')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                                
                                Forms\Components\TextInput::make('total_price')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                            ])
                            ->columns(4)
                    ])
                ])->columnSpan(2),

                // RIGHT COLUMN: Totals
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Financials')->schema([
                        Forms\Components\TextInput::make('sub_total')
                            ->numeric()
                            ->default(0),
                        
                        Forms\Components\TextInput::make('vat')
                            ->label('VAT (16%)')
                            ->numeric()
                            ->default(0),
                        
                        Forms\Components\TextInput::make('grand_total')
                            ->numeric()
                            ->default(0),
                    ]),
                    
                    Forms\Components\Textarea::make('notes')
                        ->rows(3),
                ])->columnSpan(1)
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('grand_total')
                    ->money('KES')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'info',
                        'processing' => 'warning',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                    }),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->date(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}