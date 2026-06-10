<?php

namespace App\Policies;

use App\Models\User;
use App\Models\JobPosting; 

class JobPostingPolicy
{
    // 🔒 HIDE from everyone except Admin
    public function viewAny(User $user): bool 
    { 
        return $user->role === 'admin'; 
    }
    
    public function view(User $user, JobPosting $jobPosting): bool 
    { 
        return $user->role === 'admin'; 
    }
    
    public function create(User $user): bool 
    { 
        return $user->role === 'admin'; 
    }
    
    public function update(User $user, JobPosting $jobPosting): bool 
    { 
        return $user->role === 'admin'; 
    }
    
    public function delete(User $user, JobPosting $jobPosting): bool 
    { 
        return $user->role === 'admin'; 
    }
}