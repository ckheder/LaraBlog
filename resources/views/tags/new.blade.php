{{-- Page d'accueuil --}}

@extends('layout.base') {{-- hérite de base.blade.php--}}

@section('title', 'Nouvelle catégorie') {{-- titre de page --}}

@section('content') {{-- Remplit la section content de base.blade.php--}}

<p class="uk-text-lead uk-text-center">Nouvelle catégorie d'article.</p>

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

<form method="POST" action="{{ route('addtag') }}">

    @csrf {{-- token csrf --}}

    {{-- Input nom de la catégorie --}}
 
    <div class="uk-margin">

        <div class="uk-form-controls">

            <input class="uk-input" type="text" placeholder="Nom de la catégorie" id="nametags" name="nametags" value="{{ old('nametags') }}">

        </div>

    </div>

    {{-- Bouton d'envoi --}}

    <div class="uk-text-center uk-margin">

        <button class="uk-button uk-button-secondary" type="submit">Crée une nouvelle catégorie</button>

    </div>
    
</form>

@endsection