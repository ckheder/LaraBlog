{{-- Affichage de tous les articles via leur catégorie --}}

@extends('layout.base') {{-- hérite de base.blade.php--}}

@section('title',  'Mes articles' )

@section('content') {{-- Remplit la section content de base.blade.php--}}

<meta name="csrf-token" content="{{ csrf_token() }}">

<div id="notif">

</div>

<a href="{{ route('newarticle') }}"><button class="uk-button uk-button-primary">Nouvel article</button></a> {{-- bouton de retour accueil--}}

    <table class="uk-table uk-table-middle uk-table-divider">
        <thead>
            <tr>
                <th>Titre </th>
                <th>Nombre de commentaire</th>
                <th>Nombre de recommendations</th>
                <th>Catégorie</th>
                <th>Date de création</th>
                <th>Date de modification</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>

        </thead>

        <tbody id="container">

            @foreach ($articles as $article)
            <tr id="article{{ $article->id_article }}" class="tabinfos">
                <td><a title="Voir l'article" href="{{ route('readarticle', ['id' => $article->id_article]) }}">{{ $article->titre_article }}</a></td>
                <td>{{ $article->comment_count }}</td>
                <td>{{ $article->recommends_count }}</td>
                <td>{{ $article->tag }}</td>
                <td>{{ \Carbon\Carbon::parse($article->created_at)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($article->updated_at)->format('d/m/Y') }}</td>
                <td><a href="{{ route('editarticle', ['idarticle' => $article->id_article]) }}"><button class="uk-button uk-button-primary" type="button">Modifier</button></a></td>
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