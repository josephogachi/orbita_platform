<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Redirect; 

class RedirectPolicy
{
    // ðŸ”’ HIDE from everyone except Admin
    public function viewAny(User $user): bool { return $user->role === 'admin'; }
    public function view(User $user, Category $model): bool { return $user->role === 'admin'; }
    public function create(User $user): bool { return $user->role === 'admin'; }
    public function update(User $user, Category $model): bool { return $user->role === 'admin'; }
    public function delete(User $user, Category $model): bool { return $user->role === 'admin'; }
}