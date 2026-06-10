<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class OrderStats extends BaseWidget
{
    // Make the cards stretch across the dashboard
    protected int | string | array $columnSpan = 'full';
    
    // Refresh the stats automatically every 15 seconds for live M-Pesa updates
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        // --- 1. DYNAMIC REVENUE (Last 30 Days + 7-Day Chart) ---
        $revenue30Days = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(30))
            ->sum('grand_total') ?? 0;

        $revenuePrevious30Days = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])
            ->sum('grand_total') ?? 0;

        // Determine if revenue is trending up or down
        $isRevenueUp = $revenue30Days >= $revenuePrevious30Days;
        $revenueTrendIcon = $isRevenueUp ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $revenueColor = $isRevenueUp ? 'success' : 'danger';

        // Generate the 7-day live sparkline chart
        $revenueChartData = collect(range(0, 6))->map(function ($daysAgo) {
            return Order::where('payment_status', 'paid')
                ->whereDate('created_at', now()->subDays($daysAgo))
                ->sum('grand_total');
        })->reverse()->values()->toArray();

        // --- 2. DYNAMIC NEW ORDERS (With 7-Day Volume Chart) ---
        $newOrdersCount = Order::where('status', 'new')->count();

        // Generate a 7-day live sparkline showing order volume
        $ordersChartData = collect(range(0, 6))->map(function ($daysAgo) {
            return Order::where('created_at', '>=', now()->subDays($daysAgo)->startOfDay())
                ->where('created_at', '<=', now()->subDays($daysAgo)->endOfDay())
                ->count();
        })->reverse()->values()->toArray();

        // --- 3. AVERAGE ORDER VALUE ---
        $aov = Order::where('payment_status', 'paid')->avg('grand_total') ?? 0;

        return [
            // Card 1: Revenue & Trends
            Stat::make('Revenue (Last 30 Days)', 'KES ' . number_format($revenue30Days, 2))
                ->description($isRevenueUp ? 'Revenue is growing' : 'Revenue is down vs last month')
                ->descriptionIcon($revenueTrendIcon)
                ->color($revenueColor)
                ->chart($revenueChartData), // The dynamic line graph!

            // Card 2: New Orders Queue
            Stat::make('New Orders (Action Required)', $newOrdersCount)
                ->description('Orders awaiting processing')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info')
                ->chart($ordersChartData), // Maps your daily order volume

            // Card 3: Average Spend
            Stat::make('Average Order Value', 'KES ' . number_format($aov, 2))
                ->description('Average spend per customer')
                ->descriptionIcon('heroicon-m-presentation-chart-line')
                ->color('warning'),
        ];
    }
}