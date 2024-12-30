<?php

namespace App\Policies;

use App\Models\Udecrement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UdecrementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view') ? true : false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Udecrement $udecrement): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create') ? true : false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Udecrement $udecrement): bool
    {
        return $user->hasPermissionTo('update') ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Udecrement $udecrement): bool
    {
        return $user->hasPermissionTo('delete') ? true : false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Udecrement $udecrement): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Udecrement $udecrement): bool
    {
        return false;
    }
}
