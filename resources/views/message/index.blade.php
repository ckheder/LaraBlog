{{-- Page d'accueuil de la mesagerie (conversations actives)--}}

@extends('layout.base') {{-- hérite de base.blade.php--}}

@section('title', 'Messagerie') {{-- titre de page --}}

@section('content') {{-- Remplit la section content de base.blade.php--}}

{{-- modale d'affichage d'envoi d'un message--}}

<button class="uk-button uk-button-default" type="button" uk-toggle="target: #ModalNewMessage"> <span class="uk-margin-small-right" uk-icon="mail"></span> Nouveau message</button>

{{-- bouton de choix d'affichage  : conversations actives ou conversations masquées --}}

<button onclick="loadConvItem('activeconv')" class="uk-button uk-button-primary" type="button"> <span class="uk-margin-small-right" uk-icon="unlock"></span>Mes conversations</button>

<button onclick="loadConvItem('hideconv')" class="uk-button uk-button-danger" type="button"><span class="uk-margin-small-right" uk-icon="lock"></span> Mes conversations masquées</button>

<span id="notifmessage"> {{-- zone affichage des notifications--}}

</span>

<div id="ModalNewMessage" uk-modal>

    <div class="uk-modal-dialog uk-modal-body">

        <h2 class="uk-modal-title">Nouveau message</h2>

        <p><form method="POST" action="{{ route('newmessage') }}" id="FormMmessageFromModal" onsubmit="UsernewMessageFromModal(); return false;">

            @csrf {{-- token csrf --}}
        
            {{-- Input destinataire --}}

            <div class="uk-form-controls">
                        
                <input class="uk-input" type="text" placeholder="Destinataire" id="destinataire" name="destinataire">
    
            </div>

            {{-- Input textarea message --}}
         
            <div class="uk-margin">
        
                <div class="uk-form-controls">
        
                    <textarea class="uk-textarea" id="corps_message" name="corps_message" rows="5" placeholder="Votre message" value="{{ old('corps_message') }}"></textarea>
        
                </div>
        
            </div>

            {{--emoji --}}

            <div class="uk-inline">

                <button class="uk-button uk-button-default" type="button"><img src="{{ asset('storage/img/emoji/grinning.png') }}">Emoji</button>

                <div uk-dropdown="mode: click">

                    @foreach(Storage::files('public/img/emoji/') as $file)

                        <img src="{{ asset('storage/img/emoji/'.File::basename($file).'') }}" onclick="insertemoji('{{ File::basename($file) }}','corps_message');return false;">

                    @endforeach

                </div>

            </div>

            {{-- Bouton d'envoi --}}
        
            <p class="uk-text-right">

                <button class="uk-button uk-button-default uk-modal-close" type="button">Fermer</button>

                <button class="uk-button uk-button-primary" type="submit">Envoyer</button>

            </p> 

        </form>

    </p>

    </div>

</div>


{{-- affichage des conversations (AJAX REQUEST)--}}

                        <div id="zone_conv">

                            <div class="uk-spinner hidden"></div>

                            <div id="list_conv"></div>

                        </div>

@endsection