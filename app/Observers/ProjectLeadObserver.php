<?php

namespace App\Observers;

use App\Models\ProjectLead;
use App\Models\User;
use App\Notifications\ProjectCompletedNotification;

class ProjectLeadObserver
{
    public function updated(ProjectLead $projectLead): void
    {
        // Check if the status was changed to 'completed'
        if ($projectLead->isDirty('status') && $projectLead->status === 'completed') {
            
            // Find the Admin(s)
            $admins = User::where('role', 'admin')->get();
            
            foreach ($admins as $admin) {
                $admin->notify(new ProjectCompletedNotification($projectLead));
            }
        }
    }
}