<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',  // <--- This was missing
        'role',         // e.g. "Manager at Hilton"
        'content',      // The actual review text
        'image_path',   // Client photo
        'rating',       // 1-5 stars
        'is_active',    // Show/Hide toggle
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rating' => 'integer',
    ];
}