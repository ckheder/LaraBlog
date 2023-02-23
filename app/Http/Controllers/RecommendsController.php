<?php

namespace App\Http\Controllers;

use App\Models\Recommends;
use Illuminate\Http\Request;

class RecommendsController extends Controller
{
    /**
     * Création d'une recommendation
     *
     * @param Request $request
     * @return void
     */
        public function store(Request $request)
    {
            if($request->ajax()) // requête AJAX
        {       
            // un auteur ne peut pas recommender son propre article

                if($request->input('authorarticle') == $request->user()->name)
            {

            return response()->json([
                                        'addrecommendstate' => 'authorarticle'
                                    ]);

        
            }
                else
            {

                // on vérifie si l'utilisateur n'a pas déjà recommendé cet article

                $checkrecommends = Recommends::where(['user_recommends' => $request->user()->name, 'article_recommends' => $request->input('idarticle')])
                                ->count();

        
                    if($checkrecommends == 0) // pas de recommendations, on en crée
                {

                    $recommends = new Recommends();

                    $recommends->user_recommends = $request->user()->name;
                        
                    $recommends->article_recommends = $request->input('idarticle');

                    if($recommends->save()) // enregistrement réussi
    
                    return response()->json([
                                                'addrecommendstate' => 'newrecommendok'
                                            ]);

                    else // échec enregistrement

                    return response()->json([
                                                'addrecommendstate' => 'newrecommendnotok'
                                            ]);
                }
                    else // recommendation existante

                {
                    return response()->json([
                                                'addrecommendstate' => 'alreadyrecommend'
                                            ]);

                }
            }
        }
            else
        {
            abort(404); // non AJAX -> 404
        }
                     
    }
}
