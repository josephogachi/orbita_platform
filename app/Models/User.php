<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
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
        'role', // Added to support Admin, Seller, and Client roles
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
     * Authorize access to Filament panels.
     * This prevents regular clients from logging into the Admin Dashboard.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Restrict the 'admin' panel to users with the 'admin' role
        if ($panel->getId() === 'admin') {
            return $this->role === 'admin';
        }

        // Future-proofing for your Seller Dashboard
        if ($panel->getId() === 'seller') {
            return $this->role === 'seller';
        }

        // Allow all authenticated users to access the general app/user panel
        return true;
    }
}