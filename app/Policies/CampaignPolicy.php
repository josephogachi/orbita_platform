<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;

class CampaignPolicy
{
    // 🔒 HIDE FROM SALES TEAM (Admin Only)
    public function viewAny(User $user): bool { return $user->role === 'admin'; }
    public function view(User $user, Campaign $model): bool { return $user->role === 'admin'; }
    public function create(User $user): bool { return $user->role === 'admin'; }
    public function update(User $user, Campaign $model): bool { return $user->role === 'admin'; }
    public function delete(User $user, Campaign $model): bool { return $user->role === 'admin'; }
}