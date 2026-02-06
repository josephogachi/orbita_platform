<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'shop_name',
        'shop_phone',
        'shop_address',     // Showroom Address
        'office_address',   // Corporate Office Address
        'phone_contact',
        'email_contact',
        'vat_percentage',
        'logo_path',        // Stores the filename string (e.g., "settings/logo.png")
        'show_countdown',
        'promo_banner_text',
        'countdown_end',
        'bank_name',
        'account_name',
        'account_number',
        'about_image_path',
        'catalog_path',
    ];

    /**
     * The attributes that should be cast.
     * Note: We intentionally DO NOT cast 'logo_path' to array.
     */
    protected $casts = [
        'show_countdown' => 'boolean',  // Handles the toggle switch
        'countdown_end' => 'datetime',  // Handles the date picker
        'vat_percentage' => 'integer',  // Stores 16% as 16
    ];
}