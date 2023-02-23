{{-- Page d'accueuil --}}

@extends('layout.base') {{-- hérite de base.blade.php--}}

@section('title', 'Larablog | Connexion') {{-- titre de page --}}

@section('content') {{-- Remplit la section content de base.blade.php--}}

<div uk-grid>

    <div class="uk-width-expand@m">

<p class="uk-text-lead uk-text-center">Connexion</p>

{{-- Affichage des erreurs --}}

@if ($errors->login->any())

<div class="uk-alert-danger" uk-alert>

    <a class="uk-alert-close" uk-close></a>

        <ul>
            @foreach ($errors->login->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


{{-- Affichage du formulaire --}}

<form method="POST" action="{{ route('processlogin') }}">

    @csrf {{-- token csrf --}}


    {{-- Input auteur de l'article --}}

    <div class="uk-margin">

        <div class="uk-form-controls">

            <input class="uk-input" id="email" name="email" type="email" placeholder="Adresse e-mail"  value="{{ old('mail') }}">

        </div>

    </div>

        {{-- Input mot de passe --}}

        <div class="uk-margin">

            <div class="uk-form-controls">
    
                <input class="uk-input" id="password" name="password" type="password" placeholder="Mot de passe"  value="{{ old('password') }}">
    
            </div>
    
        </div>

        {{-- Case à cocher se souvenir de moi  --}}

        <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">

            <label>
                
                <input class="uk-checkbox" name="remember" type="checkbox"> Se souvenir de moi ?
            
            </label>

        </div>
        
    {{-- Bouton d'envoi --}}

    <div class="uk-text-center uk-margin">

        <button class="uk-button uk-button-secondary" type="submit">Connexion</button>

    </div>
    
</form>

    </div>

    <div class="uk-width-expand@m">
        
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
        
        <form method="POST" action="{{ route('register') }}">
        
            @csrf {{-- token csrf --}}
        
            {{-- Input nom d'utilisateur' --}}
         
            <div class="uk-margin">
        
                <div class="uk-form-controls">
        
                    <input class="uk-input" type="text" placeholder="Nom d'utilisateur" id="name" name="name" value="{{ old('name') }}">
        
                </div>
        
            </div>
        
            {{-- Input auteur de l'article --}}
        
            <div class="uk-margin">
        
                <div class="uk-form-controls">
        
                    <input class="uk-input" id="email" name="email" type="email" placeholder="Adresse e-mail"  value="{{ old('mail') }}">
        
                </div>
        
            </div>
        
                {{-- Input mot de passe --}}
        
                <div class="uk-margin">
        
                    <div class="uk-form-controls">
            
                        <input class="uk-input" id="password" name="password" type="password" placeholder="Mot de passe"  value="{{ old('password') }}">
            
                    </div>
            
                </div>
        
        
            <div>
        
        
            {{-- Bouton d'envoi --}}
        
            <div class="uk-text-center uk-margin">
        
                <button class="uk-button uk-button-secondary" type="submit">Crée mon compte</button>
        
            </div>
            
        </form></div>
</div>

@endsection