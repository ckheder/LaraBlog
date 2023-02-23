<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Articles;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RecommendsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Comments  $comments
     * @param author $author : auteur de l'article
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Articles $article)
    {

        if($user->role == 'admin' || $user->name != $article->author)
        {
            return Response::allow();
        }
            else
        {
            return Response::deny();
        }
    }
}
