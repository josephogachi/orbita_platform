<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class TestimonialPolicy // Change class name to match the file (e.g., HeroSlidePolicy)
{
    // 🔒 ADMIN ONLY: Sales team doesn't need to see website configuration
    public function viewAny(User $user): bool { return $user->role === 'admin'; }
    public function view(User $user, $model): bool { return $user->role === 'admin'; }
    public function create(User $user): bool { return $user->role === 'admin'; }
    public function update(User $user, $model): bool { return $user->role === 'admin'; }
    public function delete(User $user, $model): bool { return $user->role === 'admin'; }
}