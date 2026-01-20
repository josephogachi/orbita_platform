<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SideAd extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'badge_text',
        'button_text',
        'image_path',
        'link_url',
        'is_active',
        'sort_order',
    ];
}