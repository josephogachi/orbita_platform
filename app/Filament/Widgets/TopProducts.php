<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class TopProducts extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                OrderItem::query()
                    ->select(
                        'product_id', 
                        DB::raw('SUM(quantity) as total_qty'), 
                        // Updated to use unit_price from your migration
                        DB::raw('SUM(quantity * unit_price) as revenue') 
                    )
                    ->groupBy('product_id')
                    ->orderBy('total_qty', 'desc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('product.images')
                    ->label('Image')
                    ->circular()
                    ->stacked()
                    ->limit(1),

                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product Name')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_qty')
                    ->label('Units Sold')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('revenue')
                    ->label('Total Revenue')
                    ->money('KES')
                    ->sortable(),
            ]);
    }
}