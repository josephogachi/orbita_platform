<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view the list of projects.
     * 🔒 Restricted to Admin only.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can view a specific project.
     */
    public function view(User $user, Project $project): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can create new projects.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update a project.
     */
    public function update(User $user, Project $project): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete a project.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore a deleted project.
     */
    public function restore(User $user, Project $project): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete a project.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return $user->role === 'admin';
    }
}