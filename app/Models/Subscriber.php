<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    // 🟢 ADD THIS LINE to allow saving the email
    protected $fillable = ['email', 'is_active'];
}