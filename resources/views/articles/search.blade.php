{{-- Page d'accueuil --}}

@extends('layout.base') {{-- hérite de base.blade.php--}}

@section('title', 'Larablog |'.request('search')) {{-- titre de page --}}

@section('content') {{-- Remplit la section content de base.blade.php--}}

<p class="uk-text-lead uk-text-center">{{ $articles->total()}} résultat(s) pour {{ request('search') }}.</p>

{{-- affichage des articles --}}

<div id="Container">

@foreach ($articles as $article)

<article class="uk-article">

    <h1 class="uk-article-title"><a class="uk-link-reset" href="">{{ $article->titre_article }}</a></h1>

        <p class="uk-article-meta"><img class="uk-comment-avatar" src="{{ asset('storage/img/avatar/'.$article->author.'') }}" width="50" height="50" alt="" style="border-radius: 50px"> <a href="{{ route('showbyauthor', ['author' => $article->author]) }}">{{ $article->author }}</a> <span class="uk-badge" style="background:{{ $article->user->role == 'admin' ? 'red' : 'blue' }}">{{ $article->user->role }}</span> {{ \Carbon\Carbon::parse($article->created_at)->diffForHumans() }} dans la catégorie <a href="{{ route('showbytag', ['tag' => $article->tag]) }}">{{ $article->tag }}</a>. 
            
            {{$article->created_at != $article->updated_at ? 'Modifié '.\Carbon\Carbon::parse($article->updated_at)->diffForHumans().'' : '' }}</p>

            <p>{!! $article->corps_article !!}</p>

                <div class="uk-grid-small uk-child-width-auto" uk-grid>

                    <div>

                        <a href="{{ route('readarticle', ['id' => $article->id_article]) }}">Lire</a>

                    </div>

                    <div>

                        <span uk-icon="comments"></span> {{ $article->comment_count }} commentaire(s) - <span uk-icon="star"></span> 
                        {{ $article->recommends_count }} recommendation(s)

                    </div>

                </div>

</article>

@endforeach

<div uk-spinner hidden></div>

</div>

<div class="pagination">

{{-- lien de pagination, utilisant la vue ulkit du dossier vendor/pagination --}}

{{ $articles->links('vendor.pagination.ulkit') }} 

</div>

<div class="uk-alert uk-alert-primary no-more" hidden>
    
    <p class="uk-text-center">Fin des résultats</p>

</div>
    
@endsection