{{-- Affichage de tous les articles --}}

@extends('layout.base') {{-- hérite de base.blade.php--}}

@section('title',  'Administration' )

@section('content') {{-- Remplit la section content de base.blade.php--}}

<meta name="csrf-token" content="{{ csrf_token() }}">

<div id="notif">

</div>

<a href="{{ route('adminlisttag') }}"><button class="uk-button uk-button-primary">Gérer les catégories</button></a> {{-- bouton de gestion des catégories--}}

<a href="{{ route('adminlistusers') }}"><button class="uk-button uk-button-primary">Gérer les utilisateurs</button></a> {{-- bouton de gestion des catégories--}} 

    <table class="uk-table uk-table-middle uk-table-divider">
        <thead>
            <tr>
                <th>Titre </th>
                <th>Auteur</th>
                <th>Catégorie</th>
                <th>Nombre de commentaire</th>
                <th>Nombre de recommendations</th>
                <th>Date de création</th>
                <th>Date de modification</th>
                <th>Supprimer</th>
            </tr>

        </thead>

        <tbody id="container">
            
            @foreach ($articles as $article)
            <tr id="article{{ $article->id_article }}" class="tabinfos">
                <td><a title="Voir l'article" href="{{ route('readarticle', ['id' => $article->id_article]) }}">{{ $article->titre_article }}</a></td>
                <td><img class="uk-comment-avatar" src="{{ asset('storage/img/avatar/'.$article->author.'') }}" width="50" height="50" alt="" style="border-radius: 50px"> <a href="{{ route('showbyauthor', ['author' => $article->author]) }}">{{ $article->author }}</a></td>
                <td>{{ $article->tag }}</td>
                <td>{{ $article->comment_count }}</td>
                <td>{{ $article->recommends_count }}</td>
                <td>{{ \Carbon\Carbon::parse($article->created_at)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($article->updated_at)->format('d/m/Y') }}</td>
                <td><button onclick="deletearticle({{ $article->id_article }})" class="uk-button uk-button-danger" type="button">Supprimer</button></td>
            </tr>

            @endforeach

        </tbody>

    </table>

    <div uk-spinner hidden></div>

    {{-- lien de pagination, utilisant la vue ulkit du dossier vendor/pagination --}}

    <div class="pagination">

        {{ $articles->links('vendor.pagination.ulkit') }} 
        
    </div>
        
    <div class="uk-alert uk-alert-primary no-more" hidden>
        
        <p class="uk-text-center">Fin des articles</p>
    
    </div>

@endsection
