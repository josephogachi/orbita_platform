<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Revenue';

    /**
     * 🔒 SECURITY: Only Admins can see the total Revenue Chart.
     * This will hide the widget from the Sales Team dashboard.
     */
    public static function canView(): bool
    {
        return auth()->user()->role === 'admin';
    }

    protected function getData(): array
    {
        // This calculates totals for the last 6 months
        return [
            'datasets' => [
                [
                    'label' => 'Revenue (KES)',
                    'data' => [150000, 240000, 190000, 400000, 350000, 520000], // Example data
                    'backgroundColor' => '#002D62', // Orbita Blue
                    'borderColor' => '#B8860B',     // Orbita Gold
                    'fill' => 'start',              // Adds a nice shaded area under the line
                    'tension' => 0.3,               // Makes the line curvy
                ],
            ],
            'labels' => ['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}