<?php

namespace App\Http\Controllers;

use App\Models\Articles;
use App\Models\Tags;

class HomeController extends Controller
{
    /**
     * Récupération et pagination des articles, et de la liste des catégories avec le nombre d'articles associés
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        $articles = Articles::withCount(['comment','recommends'])->with('user:role,name')->latest()->paginate(10);

        //dd($articles->user->role);

        //tags avec leur nombre d'articles associés

        $tags = Tags::withCount('article')->get();

        return view('homepage.index', ['articles' => $articles, 'tags' => $tags]);
    }

    /**
     * Controlleur avec une unique méthode : Route::get('/', HomeController::class);
     */

    //public function __invoke()
    //{
        //return view('welcome');
    //}
}
