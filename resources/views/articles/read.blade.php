{{-- Affichage d'un article et chargement des commentaires associés via AJAX --}}

@extends('layout.base') {{-- hérite de base.blade.php--}}

@section('title',  $article->titre_article )

@section('content') {{-- Remplit la section content de base.blade.php--}}

@if(Auth::check()) {{-- utilisateur authentifié, création d'une méta csrf et d'une zone de notification --}}

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div id="notifcomm">

    </div>

@endif

        <article class="uk-article">

            <h1 class="uk-article-title"><a class="uk-link-reset" href="">{{ $article->titre_article }}</a></h1>

            <p class="uk-article-meta"><img class="uk-comment-avatar" src="{{ asset('storage/img/avatar/'.$article->author.'') }}" width="50" height="50" alt="" style="border-radius: 50px"> <a href="{{ route('showbyauthor', ['author' => $article->author]) }}">{{ $article->author }}</a> <span class="uk-badge" style="background:{{ $article->user->role == 'admin' ? 'red' : 'blue' }}">{{ $article->user->role }}</span> {{ \Carbon\Carbon::parse($article->created_at)->diffForHumans() }} dans la catégorie <a href="{{ route('showbytag', ['tag' => $article->tag]) }}">{{ $article->tag }}</a>.
            
                {{$article->created_at != $article->updated_at ? 'Modifié '.\Carbon\Carbon::parse($article->updated_at)->diffForHumans().'' : '' }}</p>

                    <p>{!! $article->corps_article !!}</p>

        </article>

        {{-- Affichage du nombre de recommendations --}}

        <p>
            <span uk-icon="star"></span> 
            
                <u><span id="nbrecommends">{{ $article->recommends_count }}</span> recommendation(s)</u>
        </p>

                    @if(Auth::check())

                    <br />

                        {{-- Formulaire ajout de commentaire--}}

                        <form method="POST" action="{{ route('newcomment') }}" id="form_comm" onsubmit="UsernewComment(); return false;">

                            {{-- Input titre article --}}
                         
                            <div class="uk-margin">
                        
                                <div class="uk-form-controls">
                        
                                    <input class="uk-input" type="text" placeholder="Votre commentaire..." id="comment" name="comment" value="{{ old('comment') }}">
                        
                                </div>
                        
                            </div>

                            <input type="hidden" value="{{ $article->id_article }}" id="idarticle" name="idarticle">

                            <div class="uk-inline">
                                <button class="uk-button uk-button-default" type="button"><img src="{{ asset('storage/img/emoji/grinning.png') }}">Emoji</button>
                                <div uk-dropdown="mode: click">
                                    @foreach(Storage::files('public/img/emoji/') as $file)
                                    <img src="{{ asset('storage/img/emoji/'.File::basename($file).'') }}" onclick="insertemoji('{{ File::basename($file) }}','comment');return false;">
                                @endforeach
                            </div>
                            </div>

                            {{-- Bouton d'envoi --}}
                        
                            <div class="uk-text-center uk-margin">
                        
                                <button class="uk-button uk-button-secondary" type="submit">Commenter</button>
                        
                            </div>
                            
                        </form>

                        {{-- Afiichage d'un bouton de recommendation si l'utilisateur courant peut le faire --}}

                        @can('create', [App\Recommends::class, $article])

                            <p class="uk-margin"><a onclick="recommendarticle({{ $article->id_article }}, '{{ $article->author }}')"><span uk-icon="star"></span> Recommender cet article</a></p>
            
                        @endcan

                        @else

                        <div class="uk-alert-primary" uk-alert>

                                <p>Vous devez vous <a href="{{ route('login') }}"> connecter</a> ou vous <a href="{{ route('registration') }}">enregistrer</a> pour commenter cet article ou le recommender.</p>

                        </div>

                        @endif

                        {{-- Affichage des commentaires (AJAX REQUEST) --}}

                        

                        <div id="zone_comm">

                            <div uk-spinner hidden></div>

                            <div id="list_comm"></div>

                        </div>

                       
@endsection

@push('scripts')

    <script>

        // récupération de l'url d'ajout de commentaire

        const urlcomments = "{{ route('commentsbyarticle', ['idarticle' => $article->id_article,'author' => $article->author]) }}";

    </script>

@endpush
