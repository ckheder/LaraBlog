{{-- Page d'accueuil --}}

@extends('layout.base') {{-- hérite de base.blade.php--}}

@section('title', 'Accès interdit') {{-- titre de page --}}

@section('content') {{-- Remplit la section content de base.blade.php--}}

<div class="uk-alert-danger" uk-alert>Vous n'êtes pas autorisé à faire cette action.

    <p class="uk-text-center">

        <button class="uk-button uk-button-primary" href="{{ URL::previous() }}">Retour </button>

    </p>

</div>

@endsection