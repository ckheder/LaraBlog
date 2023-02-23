{{-- Page d'affichage d'une conversation'--}}

@extends('layout.base') {{-- hérite de base.blade.php--}}

@section('title', 'Conversation avec '.$other.'') {{-- titre de page --}}

@section('content') {{-- Remplit la section content de base.blade.php--}}

<button class="uk-button uk-button-default uk-margin-small-right" type="button" uk-toggle="target: #modal-confirm">

{{-- Affichage d'un bouton d'activation/désactivation (selon le cas) de la conversation courante --}}

 @php 

    echo ($statutconv == 1) ? '<span class="uk-margin-small-right" uk-icon="lock"></span> Désactiver cette conversation' : '<span class="uk-margin-small-right" uk-icon="unlock"></span>Activer cette conversation';

@endphp

</button>

{{-- lien de retour vers la messagerie --}}

<a href="{{ route('indexmessagerie') }}"><button class="uk-button uk-button-default uk-margin-small-right" type="button"><span uk-icon="mail"></span> Retourner à la messagerie</button></a>

<div id="modal-confirm" uk-modal>

    <div class="uk-modal-dialog uk-modal-body">

        <h2 class="uk-modal-title">{{ $statutconv == 1 ? 'Désactiver cette conversation ?' : 'Activer cette conversation ?' }}</h2>

            <p>

                {{ $statutconv == 1 ? 'Vous ne verrez plus les messages de '.$other.' dans votre messagerie. Vous pourrez annuler cette action ultérieurement.' : 
            
                    'En réactivant cette conversation, vous pourrez de nouveau voir les messages de  '.$other.' dans votre messagerie.'}}

                <p class="uk-text-right">

                    <button class="uk-button uk-button-default uk-modal-close" type="button"><span class="uk-margin-small-right" uk-icon="close"></span> Annuler</button>

                    <a href="{{ route('desaconv', ['idconv' => request('idconv')]) }}"><button class="uk-button uk-button-primary"><span class="uk-margin-small-right" uk-icon="check"></span> {{ $statutconv == 1 ? 'Désactiver cette conversation' : 'Activer cette conversation' }}</button></a>

                </p> 

            </p>

    </div>

</div>

{{-- si admin ou conv non masquée --}}

@if ($statutconv != 0 || Auth::user()->role == 'admin' )

<form method="POST" action="{{ route('newmessage') }}">

    @csrf {{-- token csrf --}}

    {{-- Input titre article --}}
 
    <div class="uk-margin">

        <div class="uk-form-controls">

            <textarea class="uk-textarea" id="corps_message" name="corps_message" rows="5" placeholder="Nouveau message..." value="{{ old('corps_message') }}"></textarea>

        </div>

    </div>

    <input type="hidden" value="{{ request('idconv') }}" name="idconv">

    {{-- emoji --}}

    <div class="uk-inline">

            <button class="uk-button uk-button-default" type="button"><img src="{{ asset('storage/img/emoji/grinning.png') }}">Emoji</button>

                <div uk-dropdown="mode: click">

                    @foreach(Storage::files('public/img/emoji/') as $file)

                        <img src="{{ asset('storage/img/emoji/'.File::basename($file).'') }}" onclick="insertemoji('{{ File::basename($file) }}','corps_message');return false;">

                    @endforeach

                </div>
    </div>

    {{-- Bouton d'envoi --}}

    <div class="uk-text-center uk-margin">

        <button class="uk-button uk-button-primary" type="submit"><span class="uk-margin-small-right" uk-icon="mail"></span> Envoyer</button>

    </div>
    
</form>

@else

<div class="uk-alert-danger" uk-alert>

    <a class="uk-alert-close" uk-close></a>

        <p>Vous ne pouvez pas envoyer de message dans une conversation désactivée, activez cette conversation d'abord.</p>
</div>

    
@endif

{{-- affichage des messages --}}

<div id="Container">

@foreach ($messages as $message)

<article class="uk-comment uk-margin-medium">

    <header class="uk-comment-header">

        <div class="uk-grid-medium uk-flex-middle" uk-grid>

            {{-- Avatar --}}

            <div class="uk-width-auto">

                <img class="uk-comment-avatar" src="{{ asset('storage/img/avatar/'.$message->author_message.'') }}" width="50" height="50" alt="" style="border-radius: 50px">

            </div>

            <div class="uk-comment-meta">

                {{-- Auteur + Date --}}

                <h4 class="uk-comment-title uk-margin-remove"><a class="uk-link-reset" href="#">{{ $message->author_message}}</a> <span class="uk-badge" style="background:{{ $message->user->role == 'admin' ? 'red' : ($message->user->role == 'author' ? 'blue' : 'green') }}">{{ $message->user->role }}</span></h4>

                {{ \Carbon\Carbon::parse($message->created_at)->diffForHumans() }}
                
            </div>

        </div>

    </header>

    <div class="uk-comment-body">

        <p> {!! $message->corps_message !!} </p>

     </div>

</article>

@endforeach

<div uk-spinner hidden></div>

</div>

<div class="pagination">

{{ $messages->links('vendor.pagination.ulkit') }} {{-- Lien de pagination --}}

</div>

<div class="uk-alert uk-alert-primary no-more"><p class="uk-text-center">Fin des messages</p></div>

@endsection