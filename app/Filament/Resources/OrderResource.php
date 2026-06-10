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
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Filament\Forms\Components\Grid;
use Filament\Forms\Get;
use Filament\Forms\Set;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Sales Management';

    public static function form(Form $form): Form
    {
        // 🚀 THE FINAL RECTIFIED CALCULATION ENGINE
        $calculateTotal = function (Get $get, Set $set) {
            $currency = $get('currency') ?? 'KES';
            $rate = (float) ($get('exchange_rate') ?? 130);
            if ($rate <= 0) $rate = 1;

            $items = $get('items') ?? [];
            $itemsSubtotal = 0;

            foreach ($items as $index => $item) {
                if ($currency === 'USD') {
                    $kesPrice = (float) ($item['unit_price_kes'] ?? 0);
                    $unitPrice = round($kesPrice / $rate, 2);
                    $set("items.{$index}.unit_price", $unitPrice);
                } else {
                    $unitPrice = (float) ($item['unit_price'] ?? 0);
                }
                $qty = (int) ($item['quantity'] ?? 1);
                $totalPrice = round($unitPrice * $qty, 2);
                $set("items.{$index}.total_price", $totalPrice);
                $itemsSubtotal += $totalPrice;
            }

            // 1. Determine Fees
            if ($currency === 'USD') {
                $shipping = round((float) ($get('shipping_cost_kes') ?? 0) / $rate, 2);
                $install = round((float) ($get('installation_fee_kes') ?? 0) / $rate, 2);
                $set('shipping_cost', $shipping);
                $set('installation_fee', $install);
            } else {
                $shipping = (float) ($get('shipping_cost') ?? 0);
                $install = (float) ($get('installation_fee') ?? 0);
            }

            // 2. STRICT TAX EXCLUSIVE MATH (Matches A4 Invoice)
            // Taxable: Items + Installation. Shipping is added after tax.
            $taxableBase = $itemsSubtotal + $install;
            $vatAmount = round($taxableBase * 0.16, 2);
            $finalGrandTotal = $taxableBase + $vatAmount + $shipping;

            // 3. Save to Database
            $set('grand_total', round($finalGrandTotal, 2));
        };

        return $form->schema([
            Forms\Components\Group::make()->schema([
                Forms\Components\Section::make('Order Summary')->schema([
                    Forms\Components\TextInput::make('order_number')
                        ->default(fn () => 'ORD-' . strtoupper(Str::random(6)))
                        ->disabled()->dehydrated(),
                    
                    Forms\Components\Select::make('status')
                        ->options(['new' => 'New Order', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'])
                        ->required()->native(false),

                    Forms\Components\Select::make('payment_status')
                        ->options(['paid' => 'Paid', 'unpaid' => 'Unpaid', 'partial' => 'Partial Payment'])
                        ->required()->native(false),
                        
                    Forms\Components\Select::make('currency')
                        ->options(['KES' => 'KES', 'USD' => 'USD'])
                        ->default('KES')->required()->live()->native(false)->afterStateUpdated($calculateTotal),

                    Forms\Components\TextInput::make('exchange_rate')
                        ->label('Rate (1 USD = ? KES)')
                        ->numeric()->default(130)->live(onBlur: true)->visible(fn(Get $get) => $get('currency') === 'USD')->afterStateUpdated($calculateTotal),
                ])->columns(4),

                Forms\Components\Section::make('Order Items')->schema([
                    Forms\Components\Repeater::make('items')
                        ->relationship()->live()->afterStateUpdated($calculateTotal)
                        ->schema([
                            Forms\Components\Toggle::make('is_custom_item')->label('Custom Item')->live()->columnSpanFull(),

                            Forms\Components\Select::make('product_id')
                                ->relationship('product', 'name')->searchable()->preload()
                                ->required(fn (Get $get) => !$get('is_custom_item'))
                                ->hidden(fn (Get $get) => $get('is_custom_item'))
                                ->columnSpan(3)->live(onBlur: true)
                                ->afterStateUpdated(function ($state, Set $set, Get $get) use ($calculateTotal) {
                                    $product = Product::find($state);
                                    $priceKes = $product ? (float) $product->price : 0;
                                    if ($get('../../currency') === 'USD') { $set('unit_price_kes', $priceKes); } 
                                    else { $set('unit_price', $priceKes); }
                                    $calculateTotal($get, $set);
                                }),

                            Forms\Components\TextInput::make('custom_name')->hidden(fn (Get $get) => !$get('is_custom_item'))->columnSpan(2),

                            Forms\Components\TextInput::make('quantity')->numeric()->default(1)->live(onBlur: true)->afterStateUpdated($calculateTotal)->columnSpan(1),

                            Forms\Components\TextInput::make('unit_price_kes')
                                ->label('Price (KES)')->numeric()->placeholder('Enter KES')
                                ->visible(fn(Get $get) => $get('../../currency') === 'USD')->live(onBlur: true)->afterStateUpdated($calculateTotal)->dehydrated(false),

                            Forms\Components\TextInput::make('unit_price')
                                ->label(fn(Get $get) => $get('../../currency') === 'USD' ? 'Price (USD)' : 'Price (KES)')
                                ->numeric()->readOnly(fn(Get $get) => $get('../../currency') === 'USD')->required()->columnSpan(1)->live(onBlur: true)->afterStateUpdated($calculateTotal),

                            Forms\Components\Hidden::make('total_price')->default(0),
                        ])->columns(5)
                ]),
            ])->columnSpan(2),

            Forms\Components\Group::make()->schema([
                Forms\Components\Section::make('Client Info')->schema([
                    Forms\Components\TextInput::make('client_name'),
                    Forms\Components\TextInput::make('client_company'),
                ]),

                Forms\Components\Section::make('Fees & Totals')->schema([
                    Forms\Components\Textarea::make('shipping_address')->rows(2)->required(),

                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('installation_fee_kes')
                            ->label('Installation (KES)')->numeric()
                            ->visible(fn(Get $get) => $get('currency') === 'USD')->live(onBlur: true)->afterStateUpdated($calculateTotal)->dehydrated(false),

                        Forms\Components\TextInput::make('installation_fee')
                            ->label(fn(Get $get) => $get('currency') === 'USD' ? 'Install (USD)' : 'Installation')
                            ->numeric()->readOnly(fn(Get $get) => $get('currency') === 'USD')->prefix(fn (Get $get) => $get('currency') ?? 'KES')->live(onBlur: true)->afterStateUpdated($calculateTotal),
                    ]),

                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('shipping_cost_kes')
                            ->label('Shipping (KES)')->numeric()
                            ->visible(fn(Get $get) => $get('currency') === 'USD')->live(onBlur: true)->afterStateUpdated($calculateTotal)->dehydrated(false),

                        Forms\Components\TextInput::make('shipping_cost')
                            ->label(fn(Get $get) => $get('currency') === 'USD' ? 'Ship (USD)' : 'Shipping')
                            ->numeric()->readOnly(fn(Get $get) => $get('currency') === 'USD')->prefix(fn (Get $get) => $get('currency') ?? 'KES')->live(onBlur: true)->afterStateUpdated($calculateTotal),
                    ]),

                    Forms\Components\TextInput::make('grand_total')
                        ->readOnly()->prefix(fn (Get $get) => $get('currency') ?? 'KES')
                        ->extraInputAttributes(['style' => 'font-weight: bold; font-size: 1.2rem; color: #d48d56;']),
                ]),
            ])->columnSpan(1),
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')->searchable()->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('grand_total')->money(fn ($record) => $record->currency ?? 'KES')->weight('black')->color('primary'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('M d, Y H:i')->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('downloadInvoice')
                    ->label('Invoice')->icon('heroicon-m-document-text')->color('success')
                    ->action(function (Order $record) {
                        return response()->streamDownload(function () use ($record) {
                            $record->load('items.product');
                            echo Pdf::loadView('pdf.invoice', ['order' => $record])->stream();
                        }, "Orbita_Invoice_{$record->order_number}.pdf");
                    }),
                Tables\Actions\Action::make('posReceipt')
                    ->label('POS')->icon('heroicon-m-printer')->color('info')
                    ->action(function (Order $record) {
                        return response()->streamDownload(function () use ($record) {
                            $record->load('items.product');
                            echo Pdf::loadView('pdf.thermal_receipt', ['order' => $record])
                                    ->setPaper([0, 0, 164, 600])->stream();
                        }, "POS_{$record->order_number}.pdf");
                    }),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array { return [ 'index' => Pages\ListOrders::route('/'), 'create' => Pages\CreateOrder::route('/create'), 'edit' => Pages\EditOrder::route('/{record}/edit'), ]; }
}