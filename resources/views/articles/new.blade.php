{{-- Page d'accueuil --}}

@extends('layout.base') {{-- hérite de base.blade.php--}}

@section('title', 'Nouvel article') {{-- titre de page --}}

@section('content') {{-- Remplit la section content de base.blade.php--}}

<div uk-grid>

    <div class="uk-width-expand@m">

        <p class="uk-text-lead uk-text-center">Rédiger efficacement un article</p>

        <ul class="uk-list uk-list-large uk-list-disc">

            <li>Donner un titre explicite pour permettre au lecteur de tout de suite voir si c'est un article qui les intéresse.</li>

            <li>Donner autant de détails possibles dans votre article, vous n'êtes pas limité en nombre de caractère.</li>

            <li>Si vous ne trouvez pas la catégorie correspondante à votre article, vous pouvez contacter un administrateur pour qu'il l'ajoute.</li>

            <li>
                <button class="uk-button uk-button-default" ><span class="uk-margin-small-right" uk-icon="world"></span>

                Ajouter une url</button> : Ajouter n'importe quel URL dans votre article et crée un lien Web vers celle-ci, pour cela, coller votre lien entre les balises {Url}{/Url}.

            </li>

            <li>
                <button class="uk-button uk-button-primary"><span class="uk-margin-small-right" uk-icon="code"></span> Ajouter du code </button>

             : Ajouter du code source de n'importe quel langage informatique afin de le mettre en valeur dans votre article , pour cela, coller votre code source entre les balises {Code}{/Code}.</li>

             <li>

                <button class="uk-button uk-button-default" type="button"><img src="{{ asset('storage/img/emoji/grinning.png') }}">Emoji</button> : Permet d'ajouter un ou plusieurs emojis à votre article, pour cela, cliquez sur celui que vous voulez dans la liste déroulante.

            </li>
        </ul>
</div>

<div class="uk-width-expand@m">

    <p class="uk-text-lead uk-text-center">Nouvel article.</p>

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

{{--  Information catégorie --}}

@if(Auth::user()->role != 'admin')

<div class="uk-alert-primary" uk-alert>

    <a class="uk-alert-close" uk-close></a>

        <p>Si lors de la rédaction de votre article, vous ne trouvez pas la catégorie adaptée, n'hésitez pas à envoyer un message à christophe_kheder pour qu'il l'ajoute.</p>

</div>

@endif

{{-- Affichage du formulaire --}}

<form method="POST" action="{{ route('processarticle') }}">

    @csrf {{-- token csrf --}}

    {{-- Input titre article --}}
 
    <div class="uk-margin">

        <div class="uk-form-controls">

            <input class="uk-input" type="text" placeholder="Titre de l'article" id="titre_article" name="titre_article" value="{{ old('titre_article') }}">

        </div>

    </div>

    {{-- Textarea corps des articles --}}
    
    <div class="uk-margin">

        <div class="uk-form-controls">

            <textarea class="uk-textarea" id="corps_article" name="corps_article" rows="5" placeholder="Corps de l'article">{{ old('corps_article') }}</textarea>

        </div>

    </div>

    {{-- Select affichage des catégories (obtenu en BDD) --}}

    <div class="uk-margin">

        <div class="uk-form-controls">

        <select class="uk-select" id="tag" name="tag">

            @foreach ($tags as $tag)

                <option value="{{ $tag->nametags}}">{{ $tag->nametags}}</option>

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

                <div class="uk-inline">

                    <button class="uk-button uk-button-default" type="button"><img src="{{ asset('storage/img/emoji/grinning.png') }}">Emoji</button>

                    <div uk-dropdown="mode: click">

                        @foreach(Storage::files('public/img/emoji/') as $file)

                            <img src="{{ asset('storage/img/emoji/'.File::basename($file).'') }}" onclick="insertemoji('{{ File::basename($file) }}', 'corps_article');return false;">

                        @endforeach

                    </div>
                    
                </div>

            </div>

    {{-- Bouton d'envoi --}}

    <div class="uk-text-center uk-margin">

        <button class="uk-button uk-button-secondary" type="submit">Poster</button>

    </div>
    
</form>

</div>

</div>

@endsection