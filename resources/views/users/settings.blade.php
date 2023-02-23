{{-- Vue Settings --}}

@extends('layout.base') {{-- hérite de base.blade.php--}}

@section('title', 'Larablog | Paramètres') {{-- titre de page --}}

@section('content') {{-- Remplit la section content de base.blade.php--}}

<span id="notifsettings">

</span>

<p class="uk-text-lead uk-text-center">Modifier mes informations</p>

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

{{-- affichage role --}}

<p class="uk-text-lead"><span class="uk-margin-small-right" uk-icon="users"></span>Votre statut : {{Auth::user()->role}}</p>

{{-- changer role --}}

@if(Auth::user()->role == 'user')

    <div class="uk-alert-primary" uk-alert>

        <p>En tant qu'utilisateur enregistré, vous pouvez commenter et recommender les articles de LaraBlog.
            
            <br>Si vous souhaitez rédiger et partager vos astuces sur LaraBlog, envoyer un message à christophe_kheder en précisant quel type d'articles vous souhaitez rédiger.</p>

    </div>

@elseif(Auth::user()->role == 'author')

    <div class="uk-alert-primary" uk-alert>

        <p>En tant qu'auteur, vous pouvez rédiger des articles sur LaraBlog.
            
            <br>Si vous ne souhaitez plus rédiger d'articles, envoyer un message à christophe_kheder en précisant que vous ne souhaitez  plus rédiger.</p>

    </div>

@else
    
    <div class="uk-alert-primary" uk-alert>

        <p>En tant qu'administrateur, vous pouvez gérer les articles sur LaraBlog, modérer les articles et commentaires, bannir un utilisateur ou en modifier son rôle.</p>

    </div>
    
@endif

{{-- Affichage du formulaire --}}

<form method="POST" id="form_settings" action="{{ route('processsettings') }}" autocomplete="off" enctype="multipart/form-data" onsubmit="UserupdateSettings(); return false;">

    @csrf {{-- token csrf --}}

    {{-- Modification avatar --}}

    <p class="uk-text-lead"><span class="uk-margin-small-right" uk-icon="image"></span>Modifier mon avatar</p>

    <div class="uk-margin">

        <p class="uk-text-meta">

        <label for="myfile">Choisissez un fichier:</label>

        <input type="file" id="avataruser" name="avataruser" onchange="PreviewAvatar(this)"> 

        <br><br>

    Image (jpg/jpeg/png) 3mo maximum.
    
    </p>

    Prévisualisation

    <div class="uk-width-auto">

        <img class="uk-comment-avatar" id="previewHolder" src="{{ asset('storage/img/avatar/default.png') }}" width="80" height="80" alt="">

    </div>

    {{-- Input adresse mail --}}

</div>

<p class="uk-text-lead"><span class="uk-margin-small-right" uk-icon="mail"></span>Modifier mon adresse mail</p>
 
    <div class="uk-margin">

            <input class="uk-input" id="email" name="email" type="email"  placeholder="Adresse e-mail"  value="{{ old('email') }}">

    </div>

        <div class="uk-margin">

            <input class="uk-input" id="email_confirmation" name="email_confirmation" type="email" placeholder="Confirmer mon adresse mail">

        </div>

<p class="uk-text-lead"><span class="uk-margin-small-right" uk-icon="lock"></span>Modifier mon mot de passe</p>

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

        <button class="uk-button uk-button-secondary" type="submit"><span class="uk-margin-small-right" uk-icon="cog"></span> Enregistrer mes modifications</button>

    </div>
    
</form>

<hr>

<p class="uk-text-lead uk-text-center ">Supprimer mon compte</p>

Si vous souhaitez supprimer votre compte LaraBlog, cliquez sur le bouton ci-dessous : 

    <p class="uk-text-center uk-margin-bottom">

        {{-- Modale de suppression du compte --}}

        <a class="uk-button uk-button uk-button-danger" href="#modal-deleteaccount" uk-toggle><span class="uk-margin-small-right" uk-icon="trash"></span> Supprimer mon compte</a>

        <div id="modal-deleteaccount" uk-modal>

            <div class="uk-modal-dialog">

                <button class="uk-modal-close-default" type="button" uk-close></button>

                    <div class="uk-modal-header">

                        <h2 class="uk-modal-title">Confirmer la suppression de votre compte ?</h2>

                    </div>

                <div class="uk-modal-body">

                    <p class="uk-margin">

                        Effacer votre compte supprimera définitivement tous vos commentaires d'article et toutes vos données de profil sans possibilités de les récupérer par la suite.
                    </p>

                    <p class="uk-margin">

                        Rien ne sera conservée et vous pourrez toujours profiter des articles de LaraBlog par la suite.
                        
                    </p>

                    {{-- si l'utilisateur est un auteur on propose de garder ou non les articles rédigés --}}
                        
                    @if(Auth::user()->role != 'user')

                        <p class="uk-margin">

                            En tant qu'auteur d'articles sur LaraBlog, souhaitez vous que vos articles soit toujours sur le site ou souhaitez vous les supprimer ?

                        </p>

                        <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">

                            <label><input class="uk-radio" type="radio" value="keeparticle" name="articledecision" checked> Conserver mes articles</label>

                            <label><input class="uk-radio" type="radio" value="deletearticle" name="articledecision"> Supprimer mes articles</label>

                        </div>

                    @endif()

                    {{--  Utilisateur admin--}}

                    @if(Auth::user()->role == 'admin')

                    <div class="uk-alert-danger" uk-alert>

                            <p>Vous ne pourrez pas supprimer votre compte si il est le seul compte Administrateur actif.</p>

                    </div>

                    @endif

                </div>

                <div class="uk-modal-footer uk-text-right">

                    <button class="uk-button uk-button-default uk-modal-close" type="button">Annuler</button>

                    <button onclick="DeleteUserAccount()" class="uk-button uk-button-primary" type="button">Supprimer mon compte LaraBlog</button>

                </div>

            </div>
            
        </div>

    </p>

@endsection

