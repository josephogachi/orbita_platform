<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectLead extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * 🟢 Add all your form fields here.
     */
    protected $fillable = [
        'hotel_name',
        'facility_type',
        'client_name',
        'client_phone',
        'client_email',
        'number_of_rooms',
        'interested_products', // Ensure this is cast to array if using multiple select
        'status',
        'remarks',
        'user_id',
    ];

    /**
     * Relationship: The Sales Agent who owns this project.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}