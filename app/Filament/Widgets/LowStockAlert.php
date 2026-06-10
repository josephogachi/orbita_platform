<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockAlert extends BaseWidget
{
    protected static ?int $sort = 4; // Places it right below your Top Products
    protected int | string | array $columnSpan = 'full'; // Stretches beautifully across the screen
    
    // We can use a custom heading with an icon for urgency
    protected static ?string $heading = '⚠️ Low Stock Alerts';

    // Optional: Hide this widget entirely if the user is just a sales agent
    public static function canView(): bool
    {
        return auth()->user()->role === 'admin';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // ONLY pull products where stock is 5 or less, and order by the lowest stock first
                Product::query()
                    ->where('is_active', true)
                    ->where('stock_quantity', '<=', 5)
                    ->orderBy('stock_quantity', 'asc')
            )
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('Image')
                    ->getStateUsing(function (Product $record) {
                        return is_array($record->images) ? ($record->images[0] ?? null) : $record->images;
                    })
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Product Name')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->color('gray')
                    ->copyable() // Lets you click the SKU to copy it instantly for reordering!
                    ->copyMessage('SKU copied to clipboard'),

                // The critical column: The actual stock number
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Remaining Stock')
                    ->size('lg')
                    ->weight('black')
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        $state == 0 => 'danger',   // Red: OUT OF STOCK
                        $state <= 2 => 'warning',  // Orange: Critical
                        default => 'primary',      // Gold/Primary: Getting low
                    }),
            ])
            // This is the magic "Empty State" design when all stock is healthy
            ->emptyStateHeading('Inventory is Healthy')
            ->emptyStateDescription('You have no active products running low on stock at the moment.')
            ->emptyStateIcon('heroicon-o-shield-check')
            ->paginated(false); // Keeps it as a clean, simple list
    }
}