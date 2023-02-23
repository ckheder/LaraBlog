{{-- Vue création de compte --}}

@extends('layout.base') {{-- hérite de base.blade.php--}}

@section('title', 'Larablog | Crée un compte') {{-- titre de page --}}

@section('content') {{-- Remplit la section content de base.blade.php--}}

<p class="uk-text-lead uk-text-center">Crée un compte</p>

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

<form method="POST" action="{{ route('register') }}" autocomplete="off">

    @csrf {{-- token csrf --}}

    {{-- Input nom d'utilisateur' --}}

    <p class="uk-text-lead"><span class="uk-margin-small-right" uk-icon="user"></span>Nom d'utilisateur</p>
 
    <div class="uk-margin">

        <div class="uk-form-controls">

            <input class="uk-input" type="text" placeholder="Nom d'utilisateur" id="name" name="name" value="{{ old('name') }}">

        </div>

    </div>

    {{-- Input auteur de l'article --}}

    <p class="uk-text-lead"><span class="uk-margin-small-right" uk-icon="mail"></span>Adresse mail</p>
 
    <div class="uk-margin">

            <input class="uk-input" id="email" name="email" type="email"  placeholder="Adresse e-mail"  value="{{ old('email') }}">

    </div>

        <div class="uk-margin">

            <input class="uk-input" id="email_confirmation" name="email_confirmation" type="email" placeholder="Confirmer mon adresse mail">

        </div>

        {{-- Input mot de passe --}}

        <p class="uk-text-lead"><span class="uk-margin-small-right" uk-icon="lock"></span>Mot de passe</p>

        <p>Conseils : 8 caractères minimum, utilisez des chiffres, des lettres majuscules et minuscules et des caractères spéciaux (#,@,$,...)</p>

        {{-- Input mot de passe --}}

        <div class="uk-margin">

                <input class="uk-input" id="password" name="password" type="password" placeholder="Mot de passe"  value="{{ old('password') }}">

        </div>

        <label><input class="uk-checkbox" onclick="displayPassword('password')" type="checkbox"> Voir le mot de passe</label>

            <div class="uk-margin">

                <input class="uk-input" id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirmer mon mot de passe">
    
            </div>

            <label><input class="uk-checkbox" onclick="displayPassword('password_confirmation')" type="checkbox"> Voir le mot de passe</label>


    {{-- Bouton d'envoi --}}

    <div class="uk-text-center uk-margin">

        <button class="uk-button uk-button-secondary" type="submit">Crée mon compte</button>

    </div>
    
</form>

@endsection