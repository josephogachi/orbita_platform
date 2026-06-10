<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketingList extends Model
{
    protected $fillable = [
        'name',
        'emails',
    ];

    /**
     * The attributes that should be cast.
     * We store emails as a JSON array in SQLite.
     */
    protected $casts = [
        'emails' => 'array',
    ];

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }
}