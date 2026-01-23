<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStats extends BaseWidget
{
    // Optional: Refresh the stats every 30 seconds automatically
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Revenue', 'KES ' . number_format(Order::where('payment_status', 'paid')->sum('grand_total'), 2))
                ->description('Total collected revenue')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([7, 3, 10, 3, 15, 4, 17]), // Visual trend line

            Stat::make('New Orders', Order::where('status', 'new')->count())
                ->description('Orders awaiting processing')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info'),

            Stat::make('Average Order Value', 'KES ' . number_format(Order::where('payment_status', 'paid')->avg('grand_total') ?? 0, 2))
                ->description('Average spend per customer')
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('warning'),
        ];
    }
}