<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'message',
        'target_audience',
        'marketing_list_id',
        'approval_status',
        'sent_at',
        'delivery_report',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivery_report' => 'array',
    ];

    public function marketingList()
    {
        return $this->belongsTo(MarketingList::class);
    }
}