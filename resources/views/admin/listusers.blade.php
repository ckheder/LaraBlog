{{-- Affichage de tous les utilisateurs --}}

@extends('layout.base') {{-- hérite de base.blade.php--}}

@section('title',  'Gestion des utilisateurs' )

@section('content') {{-- Remplit la section content de base.blade.php--}}

<meta name="csrf-token" content="{{ csrf_token() }}">

<div id="notif">

</div>

<a href="{{ route('adminindex') }}"><button class="uk-button uk-button-primary">Retour à l'administation</button></a> {{-- bouton de gestion des catégories--}}

    <table class="uk-table uk-table-middle uk-table-divider">
        <thead>
            <tr>
                <th>Nom </th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Nombres d'articles</th>
                <th>Date d'inscription</th>
                <th>Supprimer</th>
            </tr>

        </thead>

        <tbody id="container">

            @foreach ($users as $user)
            <tr id="user{{ $user->id }}" class="tabinfos">
                <td> <img class="uk-comment-avatar" src="{{ asset('storage/img/avatar/'.$user->name.'') }}" width="50" height="50" alt="" style="border-radius: 50px"> <a href="{{ route('showbyauthor', ['author' => $user->name]) }}">{{ $user->name }}</a></td>
                <td>{{ $user->email }}</td>
                <td>
                    <select class="uk-select" id="updaterole{{ $user->id }}" onchange="updateuserrole(this, {{ $user->id }})">
                        <option value="">--Modifier le rôle--</option>
                        <option value="admin">Admin</option>
                        <option value="author">Auteur</option>
                        <option value="user">Utilisateur</option>
                    </select>
                    Rôle actuel : <span id="role{{ $user->id }}">{{ $user->role }}</span>   
                </td>
                <td>{{ $user->article_count }}</td>
                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</td>
                <td><a class="uk-button uk-button uk-button-danger" onclick="OpenModalDeleteUser({{ $user->id }}, '{{ $user->name }}')">Supprimer</a></td>
            </tr>

            @endforeach

        </tbody>

    </table>

    <div uk-spinner hidden></div>

    {{-- Modal de suppression d'un compte utilisateur avec demande si il faut supprimer les articles rédigés (remplie dynamiquement par Javascript)--}}

    <div id="modal-deleteaccount" uk-modal>

        <div class="uk-modal-dialog">

            <button class="uk-modal-close-default" type="button" uk-close></button>

                <div class="uk-modal-header">

                    <h2 class="uk-modal-title">Confirmer la suppression du compte de <span class="ModalUsername"></span> ?</h2>

                </div>

                <div class="uk-modal-body">

                </div>
                
                <div class="uk-modal-footer uk-text-right">

                    <button class="uk-button uk-button-default uk-modal-close" type="button">Annuler</button>

                    <button id="BtnDeleteUser" class="uk-button uk-button-primary" type="button">Oui, supprimer le compte de <span class="ModalUsername"></span></button>

                </div>

        </div>

    </div>

    {{-- lien de pagination, utilisant la vue ulkit du dossier vendor/pagination --}}

    <div class="pagination">

        {{ $users->links('vendor.pagination.ulkit') }} 

    </div>

    <div class="uk-alert uk-alert-primary no-more" hidden>
    
        <p class="uk-text-center">Fin des utilisateurs</p>

    </div>

@endsection
