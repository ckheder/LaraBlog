<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Articles;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlesPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if($user->role == 'admin' || $user->role == 'author')
        {
            return Response::allow();
        }
            else
        {
            return Response::deny();
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Articles  $articles
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Articles $articles)
    {
        return $user->name === $articles->author
                ? Response::allow()
                : Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Articles  $articles
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Articles $articles)
    {

            if($user->role == 'admin' || $user->name === $articles->author)
        {
            return Response::allow();
        }
            else
        {
            return Response::deny();
        }
    }
}
