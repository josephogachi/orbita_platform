<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Notifications\Notification;
use App\Models\User; 

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'contact_person',
        'contact_position',
        'phone',
        'email',
        'region',
        'status',
        'estimated_value',
        'next_follow_up_date',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'next_follow_up_date' => 'datetime',
        'estimated_value' => 'integer',
    ];

    /**
     * 🚀 AUTOMATED NOTIFICATIONS
     * This runs automatically every time a Lead is created or updated.
     */
    protected static function booted()
    {
        // 🌟 FIXED: Notify the ENTIRE team when a new lead is added
        static::created(function ($lead) {
            // This grabs every single user in your CRM (Admins + Sales Team)
            $allUsers = User::all();
            
            if ($allUsers->count() > 0) {
                Notification::make()
                    ->title('New Lead Generated! 🚀')
                    ->icon('heroicon-o-funnel')
                    ->body("{$lead->agent?->name} added a potential deal with {$lead->company_name}")
                    ->success()
                    ->sendToDatabase($allUsers);
            }
        });

        // Notify Agent if Admin reassigns a lead to them
        static::updated(function ($lead) {
            // Check if the user_id actually changed and if the new agent exists
            if ($lead->isDirty('user_id') && $lead->agent) {
                Notification::make()
                    ->title('New Lead Assigned to You 👤')
                    ->body("Admin has assigned {$lead->company_name} to your pipeline.")
                    ->info()
                    ->sendToDatabase($lead->agent);
            }
        });
    }

    /**
     * Helper to check if follow-up is overdue
     */
    public function isOverdue(): bool
    {
        return $this->next_follow_up_date && 
               $this->next_follow_up_date->isPast() && 
               !in_array($this->status, ['won', 'lost']);
    }

    /**
     * Get the sales agent assigned to this lead.
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}