<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\ProjectLead; 
use App\Models\User;

class SalesStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s'; 

    public static function canView(): bool
    {
        return in_array(auth()->user()->role, ['admin', 'sales_agent']);
    }

    protected function getStats(): array
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';

        // --- 1. Active Projects ---
        $projectsQuery = ProjectLead::query()->where('status', '!=', 'completed');
        if (!$isAdmin) {
            $projectsQuery->where('user_id', $user->id);
        }
        $activeProjects = $projectsQuery->count();

        // --- 2. Closed Deals (Count) ---
        $closedDealsQuery = Order::query()->where('status', 'completed');
        if (!$isAdmin) {
            $closedDealsQuery->where('user_id', $user->id);
        }
        $closedDeals = $closedDealsQuery->count();

        // --- Start Building the Stats Array ---
        $stats = [];

        // 💰 REVENUE STAT: ONLY added to the array if the user is an ADMIN
        if ($isAdmin) {
            $revenue = Order::where('status', 'completed')->sum('total') ?? 0;
            
            $stats[] = Stat::make('Total Revenue', 'KES ' . number_format($revenue))
                ->description('Company-wide collected revenue')
                ->descriptionIcon('heroicon-m-banknotes')
                ->chart([7, 2, 10, 3, 15, 4, 17]) 
                ->color('success');
        }

        // 🏗️ ACTIVE PROJECTS STAT (Visible to both)
        $stats[] = Stat::make('Active Projects', $activeProjects)
            ->description($isAdmin ? 'Total System Leads' : 'Leads Assigned to Me')
            ->descriptionIcon('heroicon-m-briefcase')
            ->color('primary');

        // ✅ CLOSED DEALS STAT (Visible to both)
        $stats[] = Stat::make('Closed Deals', $closedDeals)
            ->description($isAdmin ? 'Total Orders Completed' : 'Your Successful Conversions')
            ->descriptionIcon('heroicon-m-check-badge')
            ->color('warning');

        return $stats;
    }
}