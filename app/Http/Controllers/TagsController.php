<?php

namespace App\Http\Controllers;


use App\Models\Tags;
use Illuminate\Http\Request;
use App\Http\Requests\TagsRequest;


class TagsController extends Controller
{
    /**
    * Création d'un nouveau tag (vue)
    *
    * @return void
    */
        public function new()
    {
        return view('tags.new'); 
    }
    /**
     * Modification d'un tag (vue)
     *
     * @param [type] $idtag
     * @return void
     */
    public function edit($tag)
    {
        // récupération du tag passé en paramètre  :  si il n'existe pas , retourne une 404

        $tag = Tags::findOrFail($tag);

        return view('tags.edit', ['tag' => $tag]);
    }
    /**
     * Traitement création d'un tag
     *
     * @param TagsRequest $request
     * @param [type] $idtag
     * @return void
     */
    public function store(TagsRequest $request)
    {
        // on vérifie si je peux crée un tag (admin uniquement)

        $this->authorize('create',Tags::class);

        // si oui on crée le tag et on redirige

        $tag = new Tags();

        $tag->nametags = $request->nametags;
            
        $tag->save();

        return redirect()->route('adminlisttag')->with('status', 'success') // status : success, danger ou warning
                        ->with('message', 'Catégorie crée !'); // message : message à afficher
        
    }

        /**
         * Traitement de la mise à jour d'un tag
         *
         * @param TagsRequest $request
         * @param [type] $tag
         * @return void
         */
        public function update(TagsRequest $request, $tag)
    {
        // on vérifie si je peux modifier un tag (admin uniquement)

        $this->authorize('update',Tags::class);

        // on récupère le tag

        $tag = Tags::findOrFail($tag);

        // on le met à jour et on enregistre

        $tag->nametags = $request->nametags;
            
        $tag->save();

        // Vérifie si le nom à été modifié sinon renvoi d'un message (par exemple si on ne modifie pa le nom et que l'on clique sur modifier)

            if(!$tag->wasChanged())
        {
            $message = 'Catégorie non modifiée !';

            $statut = 'warning';
        }
            else
        {
            $message = 'Catégorie modifiée !';

            $statut = 'success';
        }

        return redirect()->route('adminlisttag')->with('status', $statut) // status : success, danger ou warning
                        ->with('message', $message); // message : message à afficher
    }

    /**
     * Suppression d'un tag
     *
     * @param [type] $idtag
     * @return void
     */
    public function destroy($tag, Request $request)
    {
        if($request->ajax()) // requête AJAX
        {



        // on vérifie si je peux supprimer un tag (admin uniquement)

        $this->authorize('delete',Tags::class);

        // on supprime le tag

        $deleteTags = Tags::destroy($tag);

        if($deleteTags)
        {
            return response()->json([
                'deletetagstate' => 'deletetagok'
            ]);
        }
        else
        {
            return response()->json([
                'deletetagstate' => 'deletetagnotok'
            ]);
        }
    }
    else
    {
        abort(404);
    }
}

}
