<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LeadStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Active Leads', Lead::whereNotIn('status', ['won', 'lost'])->count())
                ->description('Current opportunities')
                ->color('info'),

            Stat::make('Pipeline Value', 'KES ' . number_format(Lead::where('status', '!=', 'lost')->sum('estimated_value')))
                ->description('Potential revenue')
                ->chart([7, 3, 4, 5, 6, 3, 5, 8]) // Sparkline trend
                ->color('success'),

            Stat::make('Overdue Follow-ups', Lead::where('next_follow_up_date', '<', now())->whereNotIn('status', ['won', 'lost'])->count())
                ->description('Needs urgent attention')
                ->color('danger'),
        ];
    }
}