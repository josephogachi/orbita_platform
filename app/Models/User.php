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
        'role', // Supports: 'admin', 'seller', 'customer'
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

    public function isSeller(): bool
    {
        return $this->role === 'seller';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Relationships
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Authorize access to Filament panels.
     * Ensures only staff can access the backend via the "hidden" link.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Check access by Panel ID
        if ($panel->getId() === 'admin') {
            // Only 'admin' and 'seller' roles can enter the Filament backend
            return in_array($this->role, ['admin', 'seller']);
        }

        // Add other panels here if you create separate ones (e.g., 'app' or 'portal')
        return true;
    }
}