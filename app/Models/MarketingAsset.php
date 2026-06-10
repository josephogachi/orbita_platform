<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketingAsset extends Model
{
    protected $fillable = [
        'name',
        'type', // 'header' or 'footer'
        'image_path',
    ];

    /**
     * Relationship: Campaigns using this asset as a header.
     */
    public function headerCampaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'header_id');
    }

    /**
     * Relationship: Campaigns using this asset as a footer.
     */
    public function footerCampaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'footer_id');
    }
}