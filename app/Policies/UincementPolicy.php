<?php

namespace App\Policies;

use App\Models\Uincement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UincementPolicy
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
    public function view(User $user, Uincement $uincement): bool
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
    public function update(User $user, Uincement $uincement): bool
    {
        return $user->hasPermissionTo('update') ? true : false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Uincement $uincement): bool
    {
        return $user->hasPermissionTo('delete') ? true : false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Uincement $uincement): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Uincement $uincement): bool
    {
        return false;
    }
}