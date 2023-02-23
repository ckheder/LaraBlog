// traitement de la messagerie

  // appel et affichage des différentes pages : conversations actives/désactivées

  export function loadConvItem(itemconv)
{

    let url_conv;

    if(itemconv == 'activeconv') // URL d'affichage des conversations actives
  {

    url_conv = '/larablog/public/messagerie/activeconv';

  }
    else if (itemconv === 'hideconv') // URL d'affichage des conversations désactivées
  {

    url_conv = '/larablog/public/messagerie/hideconv';

  }
    else
  {
      return;
  }

  	document.querySelector("#list_conv").innerHTML = ""; // on vide la div d'affichage des conversations

    document.querySelector('.uk-spinner').removeAttribute('hidden');

    fetch(url_conv, { // URL à charger dans la div précédente

                headers: {
                            'X-Requested-With': 'XMLHttpRequest' // envoi d'un header pour tester dans le controlleur si la requête est bien une requête ajax
                          }
              })

    .then(function (data) {
                            return data.text();
                          })
    .then(function (html) {

    
      document.querySelector("#list_conv").innerHTML = html; // chargement de la réponse dans la div précédente

      document.querySelector('.uk-spinner').setAttribute('hidden', ''); // on masque le spinner de chargement

    })

    // affichage d'erreur si besoin

    .catch(function(err) {
  	                       console.log(err);
  	});

}

// envoi d'un message depuis la fenêtre modale

export function UsernewMessageFromModal() {

  const data = {
                "destinataire": document.querySelector('#destinataire').value, // destinataire du message
                "corps_message": document.querySelector('#corps_message').value // message
              }

    fetch(document.querySelector('#FormMmessageFromModal').getAttribute('action'), { // on récupère l'URL d'envoi des données
      method: 'POST',
      headers: {
                  'X-Requested-With': 'XMLHttpRequest', // envoi d'un header pour tester dans le controlleur si la requête est bien une requête ajax
                  'X-CSRF-TOKEN': document.querySelector("[name='_token']").value, // récupération du token CSRF
                  'Content-type': 'application/json' // données envoyées en JSON
                },
      body:  JSON.stringify(data)
    })
.then(function(response) 
  {
    return response.json(); // récupération des données en JSON
  })
    .then(function(JsonUserNewMessageFromModalResult) {

      UIkit.modal('#ModalNewMessage').hide(); // fermeture de la fenêtre modale

        if(JsonUserNewMessageFromModalResult.error) // affichage d'éventuelles erreurs
      {
        document.querySelector('#notifmessage').innerHTML = '<div class="uk-alert-danger" uk-alert>'+
                                                                              '<a class="uk-alert-close" uk-close></a>'+
                                                                              '<p>'+JsonUserNewMessageFromModalResult.error[0]+'</p>'+
                                                                              '</div>';
      }
        else
      {

        switch(JsonUserNewMessageFromModalResult.addmessagestate)
      {
        case 'msgsend': // message envoyé avec succès

                        // rechargement des conversations
        
                          loadConvItem('activeconv');

                          // reset du formulaire

                          document.querySelector('#FormMmessageFromModal').reset();

                          // notification

                          document.querySelector('#notifmessage').innerHTML = '<div class="uk-alert-success" uk-alert>'+
                                                                           '<a class="uk-alert-close" uk-close></a>'+
                                                                            '<p>Message envoyé !</p>'+
                                                                           '</div>';

        break;

        case 'nomsg': // message vide

                      // notification

                      document.querySelector('#notifmessage').innerHTML = '<div class="uk-alert-danger" uk-alert>'+
                                                                          '<a class="uk-alert-close" uk-close></a>'+
                                                                          '<p>Vous devez compléter votre message !</p>'+
                                                                          '</div>';

        break;

        case 'nodest': // destinataire vide

                      // notification
        
                      document.querySelector('#notifmessage').innerHTML = '<div class="uk-alert-danger" uk-alert>'+
                                                                          '<a class="uk-alert-close" uk-close></a>'+
                                                                          '<p>Vous devez renseigner un destinataire !</p>'+
                                                                          '</div>';
     
        break;

        case 'messagetomyself': // envoi d'un message à moi même

                                // notification

                                document.querySelector('#notifmessage').innerHTML = '<div class="uk-alert-danger" uk-alert>'+
                                                                                    '<a class="uk-alert-close" uk-close></a>'+
                                                                                    '<p>Vous ne pouvez pas vous envoyer de message !</p>'+
                                                                                    '</div>';

        break;

        case 'unknownuser': // destinataire inexistant

                            // notification

                            document.querySelector('#notifmessage').innerHTML = '<div class="uk-alert-danger" uk-alert>'+
                                                                                '<a class="uk-alert-close" uk-close></a>'+
                                                                                '<p>Ce destinataire n\'existe pas !</p>'+
                                                                                '</div>';

        break;

        case 'disableconv': // envoi d'un message vers une conversation désactivée

                            // notification

                            document.querySelector('#notifmessage').innerHTML = '<div class="uk-alert-danger" uk-alert>'+
                                                                                '<a class="uk-alert-close" uk-close></a>'+
                                                                                '<p>Vous avez déjà une conversation avec '+document.querySelector('#destinataire').value+'. Celle-ci étant désactivée, vous ne pouvez pas lui envoyer de message.Activez d\'abord cette conversation puis réessayez.</p>'+
                                                                                '</div>';

        break;
      }
    }

    }).catch(function(err) {

// notification d'échec

          console.log(err);

    });

}