<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CampaignPolicy
{
    use HandlesAuthorization;

    private function isAdmin(User $user): bool
    {
        return $user->id === 1 
            || $user->email === 'support@orbitakenya.com' 
            || strtolower($user->role ?? '') === 'admin';
    }

    public function viewAny(User $user): bool 
    { 
        return true; 
    }

    public function view(User $user, Campaign $campaign): bool 
    { 
        return true; // Everyone can VIEW the details
    }

    public function create(User $user): bool 
    { 
        return true; 
    }

    public function update(User $user, Campaign $campaign): bool 
    { 
        if ($this->isAdmin($user)) {
            return true;
        }
        
        // 🌟 FIX: Force lowercase and trim spaces to ensure it always matches!
        $status = strtolower(trim($campaign->approval_status ?? 'draft'));
        
        return in_array($status, ['draft', 'pending', '']);
    }

    public function delete(User $user, Campaign $campaign): bool 
    { 
        return $this->isAdmin($user); 
    }

    public function deleteAny(User $user): bool 
    { 
        return $this->isAdmin($user); 
    }

    public function restore(User $user, Campaign $campaign): bool 
    { 
        return $this->isAdmin($user); 
    }

    public function forceDelete(User $user, Campaign $campaign): bool 
    { 
        return $this->isAdmin($user); 
    }
}