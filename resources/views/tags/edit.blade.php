{{-- Page d'accueuil --}}

@extends('layout.base') {{-- hérite de base.blade.php--}}

@section('title', 'Modifier une catégorie') {{-- titre de page --}}

@section('content') {{-- Remplit la section content de base.blade.php--}}

<p class="uk-text-lead uk-text-center">Modifier une catégorie.</p>

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

<form method="POST" action="{{ route('updatetag', ['tag' => $tag->nametags]) }}">

    @csrf {{-- token csrf --}}

    {{-- Input nom de la catégorie --}}
 
    <div class="uk-margin">

        <div class="uk-form-controls">

            <input class="uk-input" type="text" placeholder="Nouveau nom de catégorie" id="nametags" name="nametags" value="{{ $tag->nametags }}">

        </div>

    </div>

    <div class="uk-text-center uk-margin">

        <button class="uk-button uk-button-secondary" type="submit">Mise à jour</button>

    </div>
    
</form>

@endsection