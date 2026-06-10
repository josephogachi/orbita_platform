<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'phone', // 📱 Added: Required for M-Pesa reconciliation
        'password',
        'google_id',
        'email_verified_at',
        'role', // Supports: 'admin', 'sales_agent', 'user'
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Role Helper Methods
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSalesAgent(): bool
    {
        return $this->role === 'sales_agent';
    }

    public function isClient(): bool
    {
        return $this->role === 'user';
    }

    /**
     * 🔗 RELATIONSHIPS
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // New CRM Relationship
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'user_id');
    }
    
    public function projects(): HasMany
    {
        return $this->hasMany(ProjectLead::class);
    }

    /**
     * Authorize access to Filament panels.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return in_array($this->role, ['admin', 'sales_agent']);
        }

        return false;
    }
}