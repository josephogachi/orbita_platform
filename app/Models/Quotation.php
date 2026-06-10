<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_number',
        'client_name',
        'client_email',
        'client_phone',
        'hotel_name',
        'items',
        'installation_fee',
        'shipping_fee',
        'maintenance_fee',
        'has_maintenance',
        'subtotal',
        'total',
        'status',
        'notes',
        'is_vat_inclusive', 
        'vat_amount',       
    ];

    /**
     * Cast the items JSON from the database into an array automatically,
     * and handle our boolean toggles.
     */
    protected $casts = [
        'items' => 'array',
        'has_maintenance' => 'boolean',
        'is_vat_inclusive' => 'boolean', 
    ];

    // 🌟 THE FIX: Intercept empty emails before they crash SQLite
    protected static function booted()
    {
        static::saving(function ($quotation) {
            if (empty($quotation->client_email)) {
                $quotation->client_email = 'N/A'; // Satisfies the database NOT NULL rule
            }
        });
    }
}