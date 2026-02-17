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
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'email_verified_at',
        'role', // Supports: 'admin', 'sales_agent', 'user'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
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
     * These allow you to use if(auth()->user()->isAdmin()) in your controllers/views.
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
     * Relationships
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    
    public function projects(): HasMany
    {
        return $this->hasMany(ProjectLead::class);
    }

    /**
     * Authorize access to Filament panels.
     * Ensures only Admins and Sales Agents can access the backend.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Check access by Panel ID (default is 'admin')
        if ($panel->getId() === 'admin') {
            // Only 'admin' and 'sales_agent' roles can enter the Filament backend
            return in_array($this->role, ['admin', 'sales_agent']);
        }

        // Default deny for safety, though usually unreachable if only one panel exists
        return false;
    }
}