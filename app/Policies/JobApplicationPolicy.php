<?php

namespace App\Policies;

use App\Models\User;
use App\Models\JobApplication; 

class JobApplicationPolicy
{
    // 🔒 HIDE from everyone except Admin
    public function viewAny(User $user): bool 
    { 
        return $user->role === 'admin'; 
    }
    
    public function view(User $user, JobApplication $jobApplication): bool 
    { 
        return $user->role === 'admin'; 
    }
    
    public function create(User $user): bool 
    { 
        return $user->role === 'admin'; 
    }
    
    public function update(User $user, JobApplication $jobApplication): bool 
    { 
        return $user->role === 'admin'; 
    }
    
    public function delete(User $user, JobApplication $jobApplication): bool 
    { 
        return $user->role === 'admin'; 
    }
}