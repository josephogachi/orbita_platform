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
use Illuminate\Support\Facades\Blade;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Sales Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Order Summary')->schema([
                Forms\Components\TextInput::make('order_number')->disabled(),
                Forms\Components\Select::make('status')
                    ->options([
                        'new' => 'New',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])->required(),
                Forms\Components\Select::make('payment_status')
                    ->options([
                        'paid' => 'Paid',
                        'unpaid' => 'Unpaid',
                        'partial' => 'Partial',
                    ])->required(),
            ])->columns(3),

            Forms\Components\Section::make('Customer & Delivery')->schema([
                Forms\Components\Textarea::make('shipping_address')->rows(3),
                Forms\Components\TextInput::make('grand_total')->numeric()->prefix('KES')->disabled(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')->searchable()->sortable()->weight('bold'),
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
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'danger',
                        'partial' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('grand_total')->money('KES')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->actions([
                // QUICK ACTION: Mark as Shipped
                Action::make('markAsShipped')
                    ->icon('heroicon-o-truck')
                    ->color('warning')
                    ->action(fn (Order $record) => $record->update(['status' => 'shipped']))
                    ->requiresConfirmation()
                    ->visible(fn (Order $record) => $record->status === 'processing'),
                
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('downloadInvoice')
    ->label('Invoice')
    ->icon('heroicon-o-document-arrow-down')
    ->color('success')
    ->action(function (Order $record) {
        return response()->streamDownload(function () use ($record) {
            // Eager load items and products for the PDF
            $record->load('items.product');
            echo Pdf::loadView('pdf.invoice', ['order' => $record])->stream();
        }, "Orbita_Invoice_{$record->order_number}.pdf");
    })
            ]);
    }

    public static function getRelations(): array { return []; }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}