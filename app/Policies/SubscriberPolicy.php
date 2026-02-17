<?php

namespace App\Policies;

use App\Models\Subscriber;
use App\Models\User;

class SubscriberPolicy
{
    // 🔒 HIDE "Clients/Subscribers" from Sales Team
    public function viewAny(User $user): bool { return $user->role === 'admin'; }
    public function view(User $user, Subscriber $subscriber): bool { return $user->role === 'admin'; }
    public function create(User $user): bool { return $user->role === 'admin'; }
    public function update(User $user, Subscriber $subscriber): bool { return $user->role === 'admin'; }
    public function delete(User $user, Subscriber $subscriber): bool { return $user->role === 'admin'; }
}