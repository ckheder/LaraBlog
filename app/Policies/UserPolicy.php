<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Comments  $comments
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function updateuserrole(User $user)
    {

        if($user->role == 'admin')
        {
            return Response::allow();
        }
            else
        {
            return Response::deny();
        }
    }
}
