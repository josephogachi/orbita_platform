<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_name',
        'shop_phone',
        'shop_address',
        'phone_contact',  // Make sure this is added
        'email_contact',  // Make sure this is added
        'vat_percentage',
        'logo_path',
        'show_countdown',
        'promo_banner_text',
        'countdown_end',
    ];

    protected $casts = [
        'show_countdown' => 'boolean', // Essential for the toggle to work
        'countdown_end' => 'datetime', // Essential for the countdown timer
        'vat_percentage' => 'integer',
    ];
}