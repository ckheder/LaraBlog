{{-- Page d'accueuil --}}

@extends('layout.base') {{-- hérite de base.blade.php--}}

@section('title', 'Erreur serveur') {{-- titre de page --}}

@section('content') {{-- Remplit la section content de base.blade.php--}}

<div class="uk-alert-warning" uk-alert>Un problème serveur empêche l'accès à cette page.Veuillez réessayer plus tard

    <p class="uk-text-center">

        <button class="uk-button uk-button-primary" href="{{ URL::previous() }}">Retour </button>

    </p>

</div>

@endsection