<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;

class LeadPolicy
{
    // Helper to identify Admins
    private function isAdmin(User $user): bool
    {
        return strtolower($user->role ?? '') === 'admin' || $user->id === 1;
    }

    // Everyone can see the CRM
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, Lead $model): bool { return true; }

    // Everyone can create new leads
    public function create(User $user): bool { return true; }

    // Everyone can update leads (for team collaboration)
    public function update(User $user, Lead $model): bool { return true; }

    // ONLY Admin can delete leads (to protect your database)
    public function delete(User $user, Lead $model): bool { return $this->isAdmin($user); }
    public function deleteAny(User $user): bool { return $this->isAdmin($user); }
}