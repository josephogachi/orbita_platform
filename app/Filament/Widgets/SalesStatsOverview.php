<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\ProjectLead; 
use Carbon\Carbon;

class SalesStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s'; 
    protected static ?int $sort = 1; // Pushes it to the top of the dashboard

    public static function canView(): bool
    {
        return in_array(auth()->user()->role, ['admin', 'sales_agent']);
    }

    protected function getStats(): array
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin';
        $stats = [];

        // 💰 1. REVENUE STAT (Admin Only - With Live 7-Day Chart & 30-Day Trend)
        if ($isAdmin) {
            // Calculate 30-day revenue trend
            $revenue30Days = Order::where('status', 'completed')
                ->where('created_at', '>=', now()->subDays(30))->sum('total') ?? 0;
                
            $revenuePrevious30 = Order::where('status', 'completed')
                ->whereBetween('created_at', [now()->subDays(60), now()->subDays(30)])->sum('total') ?? 0;

            // Determine Trend UI
            $isRevenueUp = $revenue30Days >= $revenuePrevious30;
            $revenueTrendIcon = $isRevenueUp ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
            $revenueTrendColor = $isRevenueUp ? 'success' : 'danger';

            // Generate a real 7-day dynamic chart from the database
            $chartData = collect(range(0, 6))->map(function ($daysAgo) {
                return Order::where('status', 'completed')
                    ->whereDate('created_at', now()->subDays($daysAgo))
                    ->sum('total');
            })->reverse()->values()->toArray();

            $stats[] = Stat::make('Total Revenue (Last 30 Days)', 'KES ' . number_format($revenue30Days))
                ->description('vs Previous 30 Days')
                ->descriptionIcon($revenueTrendIcon)
                ->chart($chartData)
                ->color($revenueTrendColor);
        }

        // 🏗️ 2. ACTIVE PROJECTS (With weekly momentum tracking)
        $projectsQuery = ProjectLead::query()->where('status', '!=', 'completed');
        if (!$isAdmin) {
            $projectsQuery->where('user_id', $user->id);
        }
        $activeProjects = $projectsQuery->count();
        
        // Calculate new leads added just this week
        $newLeadsThisWeek = (clone $projectsQuery)->where('created_at', '>=', now()->startOfWeek())->count();

        $stats[] = Stat::make('Active Projects', $activeProjects)
            ->description($isAdmin ? $newLeadsThisWeek . ' new system leads this week' : $newLeadsThisWeek . ' new leads assigned to you this week')
            ->descriptionIcon('heroicon-m-briefcase')
            ->color('primary');

        // ✅ 3. CLOSED DEALS (With monthly conversion tracking)
        $closedDealsQuery = Order::query()->where('status', 'completed');
        if (!$isAdmin) {
            $closedDealsQuery->where('user_id', $user->id);
        }
        $closedDeals = $closedDealsQuery->count();
        
        // Calculate deals closed this month
        $dealsThisMonth = (clone $closedDealsQuery)->where('created_at', '>=', now()->startOfMonth())->count();

        $stats[] = Stat::make('Closed Deals', $closedDeals)
            ->description($dealsThisMonth . ' deals closed this month')
            ->descriptionIcon('heroicon-m-check-badge')
            ->color('warning');

        return $stats;
    }
}