<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_name',
        'contact_person',
        'region',
        'area', 
        'email',
        'phone',
        'status',
    ];
}