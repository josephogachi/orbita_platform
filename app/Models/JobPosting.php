<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class JobPosting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'department', 'location', 
        'employment_type', 'description', 'requirements', 
        'is_published', 'closing_date'
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'closing_date' => 'date',
        ];
    }

    // Automatically generate a slug from the title if missing
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($job) {
            if (empty($job->slug)) {
                $job->slug = Str::slug($job->title) . '-' . rand(1000, 9999);
            }
        });
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }
}