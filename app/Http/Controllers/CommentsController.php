<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use Illuminate\Http\Request;
use App\Http\Requests\CommentsRequest;

class CommentsController extends Controller
{
        /**
         * Récupération des commentaires d'un article (chargement en AJAX)
         *
         * @param [type] $idarticle
         * @return void
         */
        public function index($idarticle,$author)
    {

        $comments = Comments::where('article_comment', $idarticle)
                                                                    ->with('user:role,name')
                                                                    ->latest()
                                                                    ->paginate(10);

        return view('comments.index', [
                                        'comments' => $comments,
                                        'author' => $author
            
                                        ]);
    }
    /**
     * Ajout d'un commentaire
     *
     * @param CommentsRequest $request
     * @return void
     */
        public function store(CommentsRequest $request)
    {

            if($request->ajax()) // requête AJAX
        {

            $comment = new Comments();

            $comment->comment = Controller::parsing_content(strip_tags($request->comment));
    
            $comment->author_comment = $request->user()->name;
    
            $comment->article_comment = $request->idarticle;

            if($comment->save()) // enregistrement réussi
    
                return response()->json([
                                            'addcommstate' => 'newcommok'
                                        ]);

            else // échec enregistrement

                return response()->json(['addcommstate' => 'newcommnotok'
             
                                        ]);

        }
            else
        {
            abort(404); // non AJAX -> 404
        }
 
    }

        /**
         * Suppression d'un commentaire
         *
         * @param Request $request
         * @return void
         */
        public function delete(Request $request)
    {
            if($request->ajax()) // requête AJAX
        {

            $this->authorize('delete', [Comments::find($request->idcomment), $request->header('author')]);

            $deletecomment = Comments::destroy($request->idcomment);

                if($deletecomment)
            {
                return response()->json([
                                            'deletecommstate' => 'deletecommok'
                                        ]);
            }

        }
            else
        {
            abort(404); // non AJAX -> 404
        }
    }
}
