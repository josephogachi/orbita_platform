<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockAlert extends BaseWidget
{
    // Position this after the sales stats
    protected static ?int $sort = 3;
    
    // Set a heading for the widget
    protected static ?string $heading = 'Low Stock Alerts (Restock Needed)';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Only show active products with stock below 20
                Product::query()
                    ->where('is_active', true)
                    ->where('stock_quantity', '<', 20)
                    ->orderBy('stock_quantity', 'asc')
            )
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('')
                    ->circular()
                    ->limit(1),

                Tables\Columns\TextColumn::make('name')
                    ->label('Product')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Quantity Left')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state <= 5 => 'danger',  // Critical
                        $state <= 15 => 'warning', // Low
                        default => 'gray',
                    })
                    ->suffix(' Units'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->badge(),
            ])
            ->actions([
                // Quick link to edit the product and update stock
                Tables\Actions\Action::make('update_stock')
                    ->label('Restock')
                    ->url(fn (Product $record): string => "/admin/products/{$record->id}/edit")
                    ->icon('heroicon-m-plus-circle')
                    ->color('success'),
            ]);
    }
}
