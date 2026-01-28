<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Sales Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Group::make()->schema([
                // 1. Order Status & Summary
                Forms\Components\Section::make('Order Summary')->schema([
                    Forms\Components\TextInput::make('order_number')
                        ->default('ORD-' . strtoupper(Str::random(6)))
                        ->disabled()
                        ->dehydrated(),
                    
                    Forms\Components\Select::make('status')
                        ->options([
                            'new' => 'New Order',
                            'processing' => 'Processing',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'cancelled' => 'Cancelled',
                        ])
                        ->required()
                        ->native(false),

                    Forms\Components\Select::make('payment_status')
                        ->options([
                            'paid' => 'Paid',
                            'unpaid' => 'Unpaid',
                            'partial' => 'Partial Payment',
                        ])
                        ->required()
                        ->native(false),
                ])->columns(3),

                // 2. Line Items (The most important part)
                Forms\Components\Section::make('Order Items')->schema([
                    Forms\Components\Repeater::make('items')
                        ->relationship() // Assumes Order hasMany OrderItem
                        ->schema([
                            Forms\Components\Select::make('product_id')
                                ->relationship('product', 'name')
                                ->label('Product')
                                ->disabled() // Usually orders are created from frontend
                                ->columnSpan(3),
                            Forms\Components\TextInput::make('quantity')
                                ->numeric()
                                ->disabled()
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('unit_price')
                                ->label('Price (KES)')
                                ->numeric()
                                ->disabled()
                                ->columnSpan(1),
                        ])
                        ->columns(5)
                        ->addable(false) // Disable manual adding for existing orders
                        ->deletable(false)
                ]),
            ])->columnSpan(2),

            Forms\Components\Group::make()->schema([
                // 3. Customer Info
                Forms\Components\Section::make('Customer & Shipping')->schema([
                    Forms\Components\Textarea::make('shipping_address')
                        ->label('Delivery Address')
                        ->rows(4)
                        ->required(),
                    
                    Forms\Components\Placeholder::make('grand_total_display')
                        ->label('Grand Total')
                        ->content(fn (Order $record): string => 'KES ' . number_format($record->grand_total)),
                    
                    Forms\Components\Hidden::make('grand_total'),
                ]),

                // 4. Admin Notes
                Forms\Components\Section::make('Internal Notes')->schema([
                    Forms\Components\Textarea::make('notes')
                        ->placeholder('Internal delivery instructions or customer requests...')
                        ->rows(3),
                ]),
            ])->columnSpan(1),
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order ID')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(), // Allows admin to copy Order ID instantly
                
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'gray',
                        'processing' => 'info',
                        'shipped' => 'warning',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Payment')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'danger',
                        'partial' => 'warning',
                    }),

                Tables\Columns\TextColumn::make('grand_total')
                    ->money('KES')
                    ->sortable()
                    ->weight('black')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                    ]),
            ])
            ->actions([
                // QUICK ACTION: Mark as Shipped
                Action::make('markAsShipped')
                    ->label('Ship')
                    ->icon('heroicon-o-truck')
                    ->color('warning')
                    ->action(fn (Order $record) => $record->update(['status' => 'shipped']))
                    ->requiresConfirmation()
                    ->visible(fn (Order $record) => $record->status === 'processing'),
                
                Tables\Actions\Action::make('downloadInvoice')
                    ->label('Invoice')
                    ->icon('heroicon-m-document-text')
                    ->color('success')
                    ->action(function (Order $record) {
                        return response()->streamDownload(function () use ($record) {
                            $record->load('items.product');
                            echo Pdf::loadView('pdf.invoice', ['order' => $record])->stream();
                        }, "Orbita_Invoice_{$record->order_number}.pdf");
                    }),
                
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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