<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Revenue';

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