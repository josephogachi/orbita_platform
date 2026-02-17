<?php

namespace App\Policies;

use App\Models\Promotion;
use App\Models\User;

class PromotionPolicy
{
    // 🔒 HIDE "Campaigns/Promotions" from Sales Team
    public function viewAny(User $user): bool { return $user->role === 'admin'; }
    public function view(User $user, Promotion $promotion): bool { return $user->role === 'admin'; }
    public function create(User $user): bool { return $user->role === 'admin'; }
    public function update(User $user, Promotion $promotion): bool { return $user->role === 'admin'; }
    public function delete(User $user, Promotion $promotion): bool { return $user->role === 'admin'; }
}