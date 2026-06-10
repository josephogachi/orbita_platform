<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue History (Last 12 Months)';
    protected static ?int $sort = 2; // Places it right below your Stats Overview
    protected int | string | array $columnSpan = 'full'; // Makes it stretch beautifully across the screen
    protected static ?string $maxHeight = '300px';

    // Restrict visibility to Admins (optional, remove if sales agents should see company revenue)
    public static function canView(): bool
    {
        return auth()->user()->role === 'admin';
    }

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        // Loop backward through the last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            
            // Format label (e.g., "Mar 2025")
            $labels[] = $month->format('M Y');
            
            // Calculate total completed revenue for that specific month
            $monthlyRevenue = Order::where('status', 'completed')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total');

            $data[] = $monthlyRevenue;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Revenue (KES)',
                    'data' => $data,
                    'fill' => 'start', // Fills the area under the line for a modern look
                    'backgroundColor' => 'rgba(197, 160, 89, 0.15)', // Orbita Gold with transparency
                    'borderColor' => '#C5A059', // Solid Orbita Gold line
                    'tension' => 0.4, // Gives the line a smooth, elegant curve instead of sharp angles
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line'; // You can change this to 'bar' if you prefer bar charts!
    }
}