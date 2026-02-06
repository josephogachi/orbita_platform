<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectQuote extends Model
{
    /**
     * The attributes that aren't mass-assignable.
     * Setting this to empty allows mass assignment for all fields defined in your migration.
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     * This ensures your totals and percentages stay as numbers, not strings.
     */
    protected $casts = [
        'requires_installation' => 'boolean',
        'installation_fee_per_unit' => 'decimal:2',
        'deposit_percentage' => 'decimal:2',
        'estimated_total' => 'decimal:2',
        'distance_km' => 'decimal:2',
        'product_quantity' => 'integer',
    ];

    /**
     * RELATIONSHIP: The user who requested the quote.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * RELATIONSHIP: The main product being quoted (e.g., E3041 Lock).
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * HELPER: Calculate the remaining balance after the deposit.
     */
    public function getRemainingBalanceAttribute(): float
    {
        $depositAmount = ($this->estimated_total * ($this->deposit_percentage / 100));
        return $this->estimated_total - $depositAmount;
    }

    /**
     * HELPER: Get the full path for the door image.
     */
    public function getDoorImageUrlAttribute(): ?string
    {
        return $this->door_image ? asset('storage/' . $this->door_image) : null;
    }
}