{{-- Affichage des commentaires (via AJAX) --}}

<div class="uk-grid-small uk-child-width-auto uk-margin" uk-grid>

    <u><span uk-icon="comments"></span> {{ $comments->total() }} commentaires</u> {{-- nombre total de commentaires --}}

</div>

<div id="ListComments">

@foreach ($comments as $comment)

<article class="uk-comment uk-margin-medium">

    <header class="uk-comment-header">

        <div class="uk-grid-medium uk-flex-middle" uk-grid>

            {{-- Avatar --}}

            <div class="uk-width-auto">

                <img class="uk-comment-avatar" src="{{ asset('storage/img/avatar/'.$comment->author_comment.'') }}" width="50" height="50" alt="" style="border-radius: 50px"> 

            </div>

            <div class="uk-width-expand">

                {{-- Auteur + Date --}}

                <h4 class="uk-comment-title uk-margin-remove"><a class="uk-link-reset" href="{{ route('showbyauthor', ['author' => $comment->author_comment]) }}">{{ $comment->author_comment }}</a> <span class="uk-badge" style="background:{{ $comment->user->role == 'admin' ? 'red' : ($comment->user->role == 'author' ? 'blue' : 'green') }}">{{ $comment->user->role }}</span></h4>

                <ul class="uk-comment-meta uk-subnav uk-subnav-divider uk-margin-remove-top">

                    <li>{{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y') }}</li>

                </ul>

            </div>

        </div>

    </header>

    {{-- Commentaire --}}

    <div class="uk-comment-body uk-margin">

        <p>{!! $comment->comment !!}</p>

    </div>

    {{-- test de l'autorisation de supprimer --}}

    @if(Auth::check())

        @can('delete', [$comment, $author])

            <a class="uk-text-danger" onclick="deletecomment({{ $comment->id_comment }},'{{ $author }}')"><span uk-icon="close"></span> Supprimer ce commentaire</a>
                
        @endcan

    @endif

    <hr>

</article>

@endforeach

<div uk-spinner class="uk-spinner-comment" hidden></div>

</div>

<div class="pagination">

    {{ $comments->links('vendor.pagination.ulkit') }} {{-- Lien de pagination --}}

</div>

<div class="uk-alert uk-alert-primary no-more" hidden>
    
    <p class="uk-text-center">Fin des commentaires</p>

</div>