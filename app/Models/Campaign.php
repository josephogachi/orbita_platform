<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Campaign extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'subject',
        'content',
        'sent_at',
        'scheduled_at',      // ⏱️ ADDED: For the scheduling feature
        'status',            // 🚦 ADDED: To track draft/scheduled/sending states
        'marketing_list_id', // 📱 Added for Audience targeting
        'header_id',         // 🎨 Added for Brand Letterhead
        'footer_id',         // 🎨 Added for Brand Footer
        'status_log',        // 📊 Added for tracking
        'action_button',     // 🔘 Added for the CTA Button
        'attachments',       // 📎 Added for File Attachments
        'approval_status'
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'scheduled_at' => 'datetime', // ⏱️ ADDED: Tells Laravel this is a Date/Time object
            'status_log' => 'array',
            'action_button' => 'array',
            'attachments' => 'array',
        ];
    }

    /**
     * Relationship: The target email list for this campaign.
     */
    public function marketingList(): BelongsTo
    {
        return $this->belongsTo(MarketingList::class);
    }

    /**
     * Relationship: The brand header image used.
     */
    public function header(): BelongsTo
    {
        return $this->belongsTo(MarketingAsset::class, 'header_id');
    }

    /**
     * Relationship: The brand footer image used.
     */
    public function footer(): BelongsTo
    {
        return $this->belongsTo(MarketingAsset::class, 'footer_id');
    }
}