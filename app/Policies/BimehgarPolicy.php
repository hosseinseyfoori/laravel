<?php

namespace App\Policies;

use App\Models\Bimehgar;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BimehgarPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() ;

    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Bimehgar $bimehgar): bool
    {
        return $user->isAdmin();

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Bimehgar $bimehgar): bool
    {
        return $user->isAdmin();

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Bimehgar $bimehgar): bool
    {
        return $user->isAdmin();

    }
    public function deleteAny(User $user): bool
    {
        return $user->isAdmin();

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Bimehgar $bimehgar): bool
    {
        return $user->isAdmin();

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Bimehgar $bimehgar): bool
    {
        return $user->isAdmin();

    }
}
