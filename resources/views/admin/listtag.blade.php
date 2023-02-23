{{-- Affichage de toutes les catégories --}}

@extends('layout.base') {{-- hérite de base.blade.php--}}

@section('title',  'Gestion des catégories' )

@section('content') {{-- Remplit la section content de base.blade.php--}}

<meta name="csrf-token" content="{{ csrf_token() }}">

<div id="notif">

</div>

<a href="{{ route('newtags') }}"><button class="uk-button uk-button-default">Crée une nouvelle catégorie</button></a> {{-- bouton de gestion des catégories--}}

    <a href="{{ route('adminindex') }}"><button class="uk-button uk-button-primary">Retour à l'administation</button></a> {{-- bouton de gestion des catégories--}}

    <table class="uk-table uk-table-middle uk-table-divider">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Date de création</th>
                <th>Date de modification</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>

        </thead>

        <tbody id="container">

            @foreach ($tags as $tag)
            <tr id="tag{{ $tag ->nametags }}" class="tabinfos">
                <td><a href="{{ route('showbytag', ['tag' => $tag->nametags]) }}" title="Voir les articles pour cette catégorie">{{ $tag->nametags }}</a></td>
                <td>{{ \Carbon\Carbon::parse($tag->created_at)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($tag->updated_at)->format('d/m/Y') }}</td>
                <td><a href="{{ route('edittag', ['tag' => $tag->nametags]) }}"><button class="uk-button uk-button-primary" type="button">Modifier</button></a></td>
                <td><button onclick="admindeletetag('{{ $tag->nametags }}')" class="uk-button uk-button-danger" type="button">Supprimer</button></td>
            </tr>

            @endforeach
            
        </tbody>

    </table>

    <div uk-spinner hidden></div>

    {{-- lien de pagination, utilisant la vue ulkit du dossier vendor/pagination --}}

    <div class="pagination">

        {{ $tags->links('vendor.pagination.ulkit') }} 

    </div>

    <div class="uk-alert uk-alert-primary no-more" hidden>
        
        <p class="uk-text-center">Fin des catégories</p>
    
    </div>

@endsection