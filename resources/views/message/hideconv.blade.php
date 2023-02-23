{{-- Affichage des conversations désativées (appelé par AJAX) --}}

<p class="uk-text-lead uk-text-center uk-margin-top"><span class="uk-margin-small-right" uk-icon="mail"></span>Mes conversations masquées <span uk-tooltip="title: Ces conversations n'apparaîssent pas dans votre messagerie mais vous pouvez les y intégrer de nouveau si vous le désirez.; pos: right" uk-icon="question"></span>

</p>

@foreach ($hidemessages as $hidemessage)

<article class="uk-comment uk-margin-medium">

    <header class="uk-comment-header">

        <div class="uk-grid-medium uk-flex-middle" uk-grid>

            {{-- Avatar --}}

            <div class="uk-width-auto">

                <img class="uk-comment-avatar" src="{{ asset('storage/img/avatar/'.$hidemessage['otheruser'].'') }}" width="80" height="80" alt="" style="border-radius: 50px">

            </div>

            <div class="uk-width-expand">

                {{-- Auteur + Date --}}

                <h4 class="uk-comment-title uk-margin-remove"><a class="uk-link-reset" href="#">{{ $hidemessage['otheruser']}}</a></h4>

                @foreach ($hidemessage['lastmessage'] as $lastmsg)

                    <p class="uk-article-meta">{!! $lastmsg['corps_message'] !!} </p>

                        <span class="uk-margin-small-right" uk-icon="clock"></span>{{ \Carbon\Carbon::parse($lastmsg['created_at'])->diffForHumans() }}

                            <a href="{{ route('readconv', ['idconv' => $lastmsg['conversation']]) }}">Voir la conversation</a>

                @endforeach

            </div>

        </div>

    </header>

    <hr>

</article>

@endforeach

