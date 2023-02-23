//# Gestion des commentaires #//

//afficher commentaire : chargement en AJAX des commentaires d'un article

export function loadcomments() {

  document.querySelector('.uk-spinner').removeAttribute('hidden'); // affichage du spinner de chargement

  fetch(urlcomments, { // URL à charger dans la div précédente

              headers: {
                          'X-Requested-With': 'XMLHttpRequest' // envoi d'un header pour tester dans le controlleur si la requête est bien une requête ajax
                        }
            })

  .then(function (data) {
                          return data.text();
                        })
  .then(function (html) 
  {

    document.querySelector('#list_comm').innerHTML = html; // chargement de la réponse dans la div précédente

    document.querySelector('.uk-spinner').setAttribute('hidden', ''); // disparition du spinner

    // Chargement des commentaires suivants via Infinite Ajax Scroll

    let ias = new InfiniteAjaxScroll('#ListComments', {
      item: '.uk-comment',
      logger: false,
      next: '.next',
      pagination: '.pagination',
      spinner: {

                  // Definition du spinner

                  element: document.querySelector('.uk-spinner-comment'),

                  // Affichage du spinner

                  show: function(element) {
                                            element.removeAttribute('hidden'); 
                                          },

                  // Masquage du spinner

                  hide: function(element) {
                                            element.setAttribute('hidden', '');
                                          }
        
        }
      })

      // affichage de la div de fin de chargement des données
    
    ias.on('last', function() {

                                let el = document.querySelector('.no-more');
    
                                el.removeAttribute("hidden");
                                
                              })

  })

  // affichage d'erreur si besoin

  .catch(function(err) {
                         console.log(err);
  });

}

  // ajouter commentaire

  export function UsernewComment() {

    const data = {
                  "idarticle": document.querySelector('#idarticle').value, // identifiant de l'article
                  "comment": document.querySelector('#comment').value // commentaire
                }

    let button_submit_comm = document.querySelector('button[type=submit]') // récupération du bouton d'envoi
  
    let buttonTextSubmitComm = button_submit_comm.textContent // récupération du texte du bouton
  
    button_submit_comm.disabled = true // désactivation du bouton
  
    button_submit_comm.textContent = 'Publication en cours....' // mise à jour du texte du bouton

      fetch(document.querySelector('#form_comm').getAttribute('action'), { // on récupère l'URL d'envoi des données
        method: 'POST',
        headers: {
                    'X-Requested-With': 'XMLHttpRequest', // envoi d'un header pour tester dans le controlleur si la requête est bien une requête ajax
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-type': 'application/json'
                  },
        body:  JSON.stringify(data)
      })
  .then(function(response) 
    {
      return response.json(); // récupération des données en JSON
    })
      .then(function(JsonAddCommResult) {

          if(JsonAddCommResult.error) // si l'input est vide (Renvoi par FormRequest), affichage d'une notification
        {
          document.querySelector('#notifcomm').innerHTML = '<div class="uk-alert-danger" uk-alert>'+
                                                                                '<a class="uk-alert-close" uk-close></a>'+
                                                                                '<p>'+JsonAddCommResult.error[0]+'</p>'+
                                                                                '</div>';
        }
          else
        {

          switch(JsonAddCommResult.addcommstate)
        {
          case 'newcommok': // commentaire ajouté

                            // rechargement des commentaires
          
                            loadcomments();

                            // reset du formulaire

                            document.querySelector('#form_comm').reset();

                            // notification

                            document.querySelector('#notifcomm').innerHTML = '<div class="uk-alert-success" uk-alert>'+
                                                                             '<a class="uk-alert-close" uk-close></a>'+
                                                                              '<p>Commentaire posté !</p>'+
                                                                             '</div>';

          break;

          case 'newcommnotok': // commentaire non ajouté

                              // notification
          
                              document.querySelector('#notifcomm').innerHTML = '<div class="uk-alert-danger" uk-alert>'+
                                                                                '<a class="uk-alert-close" uk-close></a>'+
                                                                                '<p>Commentaire non posté !</p>'+
                                                                                '</div>';
       
          break;
        }
      }

      }).catch(function(err) {
  
  // notification d'échec
  
            console.log(err);
  
      });

    button_submit_comm.disabled = false // on réactive le bouton

    button_submit_comm.textContent = buttonTextSubmitComm // on remet le texte initial du bouton
  }

  // supprimer commentaire

  export function deletecomment(idcomment,author)

    {     

        fetch('/larablog/public/comments/delete/'+idcomment+'', { 
        headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Author': author // auteur de l'article pour le test Policy
                  }
      })
        .then(function(response) 
      {
        return response.json(); // récupération des données au format JSON
      })
      .then(function(JsonDeleteCommResult) {

        switch(JsonDeleteCommResult.deletecommstate)
        {
          case 'deletecommok': // commentaire supprimé

                            // rechargement des commentaires
          
                            loadcomments();

                            // notification
                            
                            document.querySelector('#notifcomm').innerHTML = '<div class="uk-alert-success" uk-alert>'+
                                                                              '<a class="uk-alert-close" uk-close></a>'+
                                                                              '<p>Commentaire supprimé !</p>'+
                                                                              '</div>';

          break;

          default: document.querySelector('#notifcomm').innerHTML = '<div class="uk-alert-danger" uk-alert>'+
                                                                    '<a class="uk-alert-close" uk-close></a>'+
                                                                    '<p>Commentaire non supprimé !</p>'+
                                                                    '</div>';
        }

  
 
      }).catch(function(err) {
  
  // notification d'échec : problème technique, serveur,...
  
          console.log(err);
  
      });
  
  };
  
