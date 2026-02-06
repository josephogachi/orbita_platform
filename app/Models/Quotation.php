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
    ];

    /**
     * Cast the items JSON from the database into an array automatically
     */
    protected $casts = [
        'items' => 'array',
        'has_maintenance' => 'boolean',
    ];
}