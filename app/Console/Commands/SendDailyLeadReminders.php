<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Lead;
use Illuminate\Console\Command;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class SendDailyLeadReminders extends Command
{
    // The name you will use to run this (e.g., php artisan leads:remind)
    protected $signature = 'leads:remind';
    protected $description = 'Sends daily dashboard notifications to agents for their scheduled follow-ups.';

    public function handle()
    {
        $today = now()->startOfDay();

        // Find all agents who have leads due today or overdue
        $agents = User::whereHas('leads', function ($query) use ($today) {
            $query->whereDate('next_follow_up_date', '<=', $today)
                  ->whereNotIn('status', ['won', 'lost']);
        })->get();

        foreach ($agents as $agent) {
            $count = Lead::where('user_id', $agent->id)
                ->whereDate('next_follow_up_date', '<=', $today)
                ->whereNotIn('status', ['won', 'lost'])
                ->count();

            Notification::make()
                ->title('Morning Briefing: Tasks Due Today 📅')
                ->body("Good morning, {$agent->name}! You have {$count} lead(s) requiring follow-up today.")
                ->icon('heroicon-o-calendar-days')
                ->iconColor('warning')
                ->actions([
                    Action::make('view')
                        ->label('View My Pipeline')
                        ->url('/admin/leads')
                        ->button(),
                ])
                ->sendToDatabase($agent);
        }

        $this->info("Daily reminders sent to {$agents->count()} agents.");
    }
}