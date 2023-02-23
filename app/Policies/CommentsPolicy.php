<?php

namespace App\Policies;

use App\Models\Comments;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CommentsPolicy
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
    public function delete(User $user, Comments $comments, $author)
    {

        if($user->role == 'admin' || $user->name === $comments->author_comment || $user->name === $author)
        {
            return Response::allow();
        }
            else
        {
            return Response::deny();
        }
    }

}
