<html>
    <head>
        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- UIkit CSS -->
<link rel="stylesheet" href="{{ asset('css/uikit.min.css') }}" />
<link rel="icon" type="image/png" href="{{ asset('ico.png') }}">
<title>@yield('title')</title>

<!-- UIkit JS -->
<script src="{{ asset('js/uikit.min.js') }}"></script>

<script src="{{ asset('js/uikit-icons.min.js') }}"></script>

    </head>

    <body>

        <div class="uk-container">

            <nav class="uk-navbar-container uk-margin" uk-navbar>

                <div class="uk-navbar-left">
            
                    <a class="uk-navbar-item uk-logo" href="{{ route('index') }}">Accueil</a>
            
                    <div class="uk-margin">

                        <form class="uk-search uk-search-default">
                           
                            <span class="uk-search-icon-flip" uk-search-icon></span>

                            <input class="uk-search-input" type="search" placeholder="Recherche..." id="search" name="search">

                        </form>

                    </div>
            
                </div>

                <div class="uk-navbar-right">

                    @if(Auth::check()) {{-- si l'utilisateur est connecté et non user--}}

                        @if(Auth::user()->role != 'user')

                            <a href="{{ route('myarticle') }}"><button class="uk-button uk-button-primary"><span uk-icon="pencil"></span> Mes articles</button></a> {{-- lien vers mes articles--}}

                        @endif

                    {{-- Avatar utilisateur + badge de rôle--}}

                    <p>
                        
                        <img class="uk-comment-avatar" src="{{ asset('storage/img/avatar/'.Auth::user()->name.'') }}" width="40" height="40" alt="" style="border-radius: 50px">

                            {{ Auth::user()->name }} <span class="uk-badge" style="background:{{ Auth::user()->role == 'admin' ? 'red' : (Auth::user()->role == 'author' ? 'blue' : 'green') }}">{{ Auth::user()->role }}</span>

                    </p>

                    <div class="uk-navbar-dropdown">

                        <ul class="uk-nav uk-navbar-dropdown-nav">

                            <li>
                                <a href="{{ route('usersettings') }}"> <span class="uk-margin-small-right" uk-icon="cog"></span>Paramètres</a>
                            </li>

                            <li>
                                <a href="{{ route('indexmessagerie') }}"><span class="uk-margin-small-right" uk-icon="mail"></span> Messagerie</a>
                            </li>

                            @if(Auth::user()->role == 'admin')

                            <li>
                                <a href="{{ route('adminindex' )}}"><span class="uk-margin-small-right" uk-icon="server"></span> Administration</a>
                            </li>

                            @endif

                            <li class="uk-nav-divider"></li>

                            <li><a href="{{ route('logout') }}"><span class="uk-margin-small-right" uk-icon="sign-out"></span> Déconnexion</a> {{-- bouton de déconnexion--}}</li>

                        </ul>

                    </div>
                
                @else
               
                    <a href="{{ route('registration') }}"><button class="uk-button uk-button-secondary">Crée un compte</button></a> {{-- lien de création de compte--}}

                    <a href="{{ route('login') }}"><button class="uk-button uk-button-primary">Connexion</button></a> {{-- lien de connexion--}}
   
                @endif

                </div>

            </nav>

            {{-- Affichage d'un message et d'un statut suivant une action --}}

@if (session('status') && session ('message'))

<div class="uk-alert-{{ session('status') }}" uk-alert>

    <a class="uk-alert-close" uk-close></a>

        {{ session('message') }}

    </div>

@endif
            @yield('content') {{-- Affiche du contenu, équivalent au block twig --}}

        </div>

        @vite('resources/js/app.js')

        @stack('scripts')

    </body>

</html>