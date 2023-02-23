{{-- Page d'accueuil --}}

@extends('layout.base') {{-- hérite de base.blade.php--}}

@section('title', 'Modifier un article') {{-- titre de page --}}

@section('content') {{-- Remplit la section content de base.blade.php--}}

<p class="uk-text-lead uk-text-center">Modifier un article.</p>

{{-- On vérifie si l'utilisateur connecté peut modifier un article (si il lui appartient) --}}

@can('update', $article)

{{-- Affichage des erreurs --}}

@if ($errors->any())

<div class="uk-alert-danger" uk-alert>

    <a class="uk-alert-close" uk-close></a>

        <ul>
            @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif

{{-- Affichage du formulaire --}}

<form method="POST" action="{{ route('updatearticle', ['idarticle' => $article->id_article]) }}">

    @csrf {{-- token csrf --}}

    {{-- Input titre article --}}
 
    <div class="uk-margin">

        <div class="uk-form-controls">

            <input class="uk-input" type="text" placeholder="Titre de l'article" id="titre_article" name="titre_article" value="{{ $article->titre_article }}">

        </div>

    </div>

    {{-- Textarea corps des articles --}}
    
    <div class="uk-margin">

        <div class="uk-form-controls">

            <textarea class="uk-textarea" id="corps_article" name="corps_article" rows="5" placeholder="Corps de l'article">{{ $article->corps_article }}</textarea>

        </div>

    </div>

    {{-- Select affichage des catégories (obtenu en BDD) --}}

    <div class="uk-margin">

        <div class="uk-form-controls">

        <select class="uk-select" id="tag" name="tag">

            @foreach ($tags as $tag)

                <option value="{{ $tag->nametags}}" {{ $article->tag == $tag->nametags ? 'selected=selected' : '' }}>{{ $tag->nametags}}</option>

            @endforeach

        </select>

        </div>

    </div>

    <div>

        {{-- Bouton ajouter une URL --}}

                <button class="uk-button uk-button-default" onclick="insertAtCaret('corps_article', '{Url}{/Url}');return false;"><span class="uk-margin-small-right" uk-icon="world"></span>
                    Ajouter une url</button>

        {{-- Bouton Ajouter du code source --}}

                <button class="uk-button uk-button-primary" onclick="insertAtCaret('corps_article', '{Code}{/Code}');return false;"><span class="uk-margin-small-right" uk-icon="code"></span> Ajouter du code </button>

        {{-- Supprimer les sautes de ligne --}}

        <button class="uk-button uk-button-secondary" onclick="removebr();return false;"><span class="uk-margin-small-right" uk-icon="file-text"></span> Supprimer les sauts de ligne </button>     

        {{-- Emoji --}}
        
                <div class="uk-inline">

                    <button class="uk-button uk-button-default" type="button"><img src="{{ asset('storage/img/emoji/grinning.png') }}">Emoji</button>

                        <div uk-dropdown="mode: click">

                            @foreach(Storage::files('public/img/emoji/') as $file)

                                <img src="{{ asset('storage/img/emoji/'.File::basename($file).'') }}" onclick="insertemoji('{{ File::basename($file) }}','corps_article');return false;">

                            @endforeach

                        </div>

                </div>

            </div>

    {{-- Bouton d'envoi --}}

    <div class="uk-text-center uk-margin">

        <button class="uk-button uk-button-secondary" type="submit">Mise à jour</button>

    </div>
    
</form>

{{-- Affichage d'un message comme quoi l'utilisateur connecté ne peut pas modifier cet article : il ne lui appartient pas --}}

@else

<div class="uk-alert-danger" uk-alert>

    <p>Vous ne pouvez pas modifier cet article, vous n'en êtes pas l'auteur.</p>

</div>

@endcan

@endsection