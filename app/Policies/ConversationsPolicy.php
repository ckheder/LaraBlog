<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Conversations;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConversationsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Comments  $comments
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function read(User $user, Conversations $conversation)
    {

        if($conversation->user_one == $user->name || $conversation->user_two == $user->name)
        {
            return Response::allow();
        }
            else
        {
            return Response::deny();
        }
    }
}
