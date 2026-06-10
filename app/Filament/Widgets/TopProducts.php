<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopProducts extends BaseWidget
{
    protected static ?int $sort = 3; // Places it neatly under your charts
    protected int | string | array $columnSpan = 'full'; // Stretches across the dashboard
    protected static ?string $heading = 'Premium Catalog Overview';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Pulls the top 5 most recently updated active products
                Product::query()
                    ->where('is_active', true)
                    ->orderBy('updated_at', 'desc')
                    ->limit(5)
            )
            ->columns([
                // 1. Product Thumbnail Image
                Tables\Columns\ImageColumn::make('images')
                    ->label('Image')
                    ->getStateUsing(function (Product $record) {
                        // Safely grabs the first image from your JSON array
                        return is_array($record->images) ? ($record->images[0] ?? null) : $record->images;
                    })
                    ->circular()
                    ->stacked(),

                // 2. Product Name
                Tables\Columns\TextColumn::make('name')
                    ->label('Product Name')
                    ->weight('bold')
                    ->searchable(),

                // 3. SKU Badge
                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->badge()
                    ->color('gray'),

                // 4. Formatted Price
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('KES') // Automatically formats as KES 15,000.00
                    ->weight('bold')
                    ->color('primary'),

                // 5. Dynamic Color-Coded Stock Indicator
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stock Level')
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        $state <= 0 => 'danger',   // Red if out of stock
                        $state <= 5 => 'warning',  // Yellow/Orange if running low
                        default => 'success',      // Green if healthy
                    }),
            ])
            ->paginated(false); // Removes the next/previous buttons for a cleaner dashboard look
    }
}