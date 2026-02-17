<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Determine whether the user can view the list of models.
     * 🔒 Hides "Products" from the sidebar for Sales Agents.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    // 🔒 Lock down details
    public function view(User $user, Product $product): bool
    {
        return $user->role === 'admin';
    }

    // 🔒 Lock down creation
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    // 🔒 Lock down editing
    public function update(User $user, Product $product): bool
    {
        return $user->role === 'admin';
    }

    // 🔒 Lock down deleting
    public function delete(User $user, Product $product): bool
    {
        return $user->role === 'admin';
    }
}