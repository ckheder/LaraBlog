<?php

namespace App\Http\Controllers;

use App\Models\Tags;
use App\Models\Articles;
use Illuminate\Http\Request;
use App\Http\Requests\ArticlesRequest;


class ArticlesController extends Controller
{
    /**
     * Affichage d'un article par son id
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function read($id)
    {

        //$article = Articles::withCount('recommends')->with(['user' => function ($query){
           // $query->select('role'); // i take id because in table users = id 
       // }])->findOrFail($id);

       $article = Articles::withCount('recommends')->with('user:role,name')->findOrFail($id);

        //dd($article);

        return view('articles.read', [
                                            'article' => $article
                                    ]);
    }


    /**
    * Affichage de tous les articles par leur tag
    *
    * @param int $idtag
    * @return \Illuminate\View\View
    */
        public function showbytag($tag)
    {

    $articlesbytag = Articles::withCount(['comment','recommends'])
                                ->with('user:role,name')
                                ->where('tag', $tag)
                                ->latest()
                                ->paginate(10);

        return view('articles.viewbytag', ['articles' => $articlesbytag]);

    }
    /**
     * Affichage de tous les articles par leur auteur
     *
     * @param [type] $author
     * @return void
     */
    public function showbyauthor($author)
    {

        $articlesbyauthor = Articles::withCount(['comment','recommends'])
                                    ->with('user:role,name')
                                    ->where('author', $author)
                                    ->latest()
                                    ->paginate(10);

        return view('articles.viewbyauthor', ['articles' => $articlesbyauthor]);

    }


    /**
     * Affichage de mes articles pour modification/suppression
     *
     * @param Request $request
     * @return void
     */
        public function myarticle(Request $request)
    {
        $articles = Articles::withCount(['comment','recommends'])
                    ->where('author', $request->user()->name)
                    ->latest()
                    ->paginate(5);

        return view('articles.myarticle', ['articles' => $articles]);
    }

    /**
    * Création d'un nouvel article
    *
    * @return void
    */
        public function new()
    {

        //récupération des tags pour le formulaire et affichage de la vue formulaire

        $tags = Tags::all();

        return view('articles.new', ['tags' => $tags]);
        
    }


    /**
    * Modification d'un article (vue)
    *
    * @param [type] $idarticle
    * @param Articles $article
    * @return void
    */
        public function edit($idarticle, Articles $article)
    {
        // récupération de l'article passé en paramètre  :  si il n'existe pas , retourne une 404

        $article = Articles::findOrFail($idarticle);

        $article->corps_article = $this->unparsing_content($article->corps_article);

        //récupération des tags pour le formulaire et affichage de la vue formulaire

        $tags = Tags::all();
        
        return view('articles.edit', ['article' => $article,'tags' => $tags]);
           
    }

    /**
    * Traitement de la création d'un nouvel article
    *
    * @param ArticlesRequest $request
    * @param [type] $idarticle
    * @return void
    */
    public function store(ArticlesRequest $request)
    {

        $this->authorize('create', Articles::class);

        $article = new Articles();

        $article->fill([
                        'titre_article' => $request->titre_article,
                        'corps_article' => Controller::parsing_content(strip_tags($request->corps_article)),
                        'author' => $request->user()->name,
                        'tag' => $request->tag
                    ])->save();
        

        return redirect()->route('myarticle')->with('status', 'success') // status : success, danger ou warning
                    ->with('message', 'Article crée !'); // message : message à afficher
        
    }
        /**
         * Mise à jour d'un article (traitement)
         *
         * @param ArticlesRequest $request
         * @param [type] $idarticle
         * @return void
         */
        public function update(ArticlesRequest $request, $idarticle)
    {

        // récupération de l'article

        $article = Articles::findOrFail($idarticle);

        // on vérifie si l'utilisateur connecté est l'auteur de cet article

        $this->authorize('update', $article);

        // si oui , traitement de la mise à jour

        $article->fill([
                        'titre_article' => $request->titre_article,
                        'corps_article' => Controller::parsing_content(strip_tags($request->corps_article)),
                        'author' => $request->user()->name,
                        'tag' => $request->tag
                        ])->save();

            if(!$article->wasChanged()) // si aucun changement n'a était fait
        {
            $message = 'Article non modifié !';

            $statut = 'warning';
        }
            else
        {
            $message = 'Article modifié !';

            $statut = 'success';
        }

        return redirect()->route('myarticle')->with('status', $statut) // status : success, danger ou warning
                        ->with('message', $message); // message : message à afficher

    }

    /**
    * Suppression d'un article
    *
    * @param [type] $idarticle
    * @return void
    */
    public function delete($idarticle, Request $request)
    {

        if($request->ajax()) // requête AJAX
        {

        // on vérifie si l'utilisateur connecté est l'auteur de cet article

        $this->authorize('delete', Articles::find($idarticle));

        // si oui il peut supprimer l'article

        $deletearticle = Articles::destroy($idarticle);

        if($deletearticle)
        {
            return response()->json([
                                        'deletearticlestate' => 'deletearticleok'
                                    ]);
        }
        else
        {
            return response()->json([
                                    'deletearticlestate' => 'deletearticlenotok'
            ]);
        }
    }
    else
    {
        abort(404); // non AJAX -> 404
    }
}
    
    /**
     * Faire une recherche parmi les titres d'articles et leur contenu
     *
     * @param [type] $search
     * @return void
     */
    public function search($search = null)
    {
                    $posts = Articles::withCount(['comment','recommends'])
                                        ->with('user:role,name')
                                        ->whereFullText(['titre_article','corps_article'], $search)
                                        ->latest()
                                        ->paginate(10);

                    return view('articles.search',['articles' => $posts]);

    }

        /**
    * Parse le contenu passé en paramètre : URL, code, ou emoji pour modifier le contenu d'un article (inverse les balises vers le balisage d'origine à la création d'un article)
    *
    * @param [type] $article
    * @return void
    */
    public function unparsing_content($article)
    {
        // Url

        $article = preg_replace('~<a href=\"(.*?)\">(.*?)<\/a>~i', '{Url}$2{/Url}', $article);

        // Code source
 
        $article = preg_replace('~<pre><code>(.*?)<\/code><\/pre>~i', '{Code}$1{/Code}', $article);

        // Emoji

        $article =  preg_replace('~<img[^>]+alt=\"([^>]*)\"[^>]*>~i', ':$1:', $article); 
        
        return($article);
    }



}
