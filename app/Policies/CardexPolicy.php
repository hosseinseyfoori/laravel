<?php

namespace App\Policies;

use App\Models\Cardex;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CardexPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isStaff();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Cardex $cardex): bool
    {
        return $user->isAdmin() || $user->isStaff();

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isStaff();

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Cardex $cardex): bool
    {
        return $user->isAdmin() || $cardex->user_id === $user->id;

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Cardex $cardex): bool
    {
        return $user->isAdmin() ;
    }
    public function deleteAny(User $user): bool
    {
        return $user->isAdmin() ;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Cardex $cardex): bool
    {
        return $user->isAdmin() ;

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Cardex $cardex): bool
    {
        return $user->isAdmin() ;

    }
}
