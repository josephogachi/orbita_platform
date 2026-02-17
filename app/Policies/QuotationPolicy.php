<?php

namespace App\Policies;

use App\Models\Quotation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuotationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Both Admins and Sales Agents can view the list of quotes
        return in_array($user->role, ['admin', 'sales_agent']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Quotation $quotation): bool
    {
        // Both can view specific quote details
        return in_array($user->role, ['admin', 'sales_agent']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Both can create new quotes
        return in_array($user->role, ['admin', 'sales_agent']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Quotation $quotation): bool
    {
        // Both can edit/update quotes (e.g., changing prices or quantities)
        return in_array($user->role, ['admin', 'sales_agent']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Quotation $quotation): bool
    {
        // 🔒 SECURITY CRITICAL: Only Admin can delete quotes.
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Quotation $quotation): bool
    {
        // Only Admin can restore
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Quotation $quotation): bool
    {
        // Only Admin can permanently delete
        return $user->role === 'admin';
    }
}