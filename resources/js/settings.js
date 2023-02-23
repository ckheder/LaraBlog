// Settings.js //
//
// Gestion des paramètres utilisateur //

// Mise à jour des informations utilisateur

export function UserupdateSettings() {

    let data = new FormData(document.querySelector('#form_settings'));

      fetch(document.querySelector('#form_settings').getAttribute('action'), { // on récupère l'URL d'envoi des données
        method: 'POST',
        headers: {
                    'X-Requested-With': 'XMLHttpRequest', // envoi d'un header pour tester dans le controlleur si la requête est bien une requête ajax
                    'X-CSRF-TOKEN': document.querySelector("[name='_token']").value
                  },
        body: data
      })
  .then(function(response) 
    {
      return response.json(); // récupération des données en JSON
    })
      .then(function(JsonAddCommResult) {

          if(JsonAddCommResult.error) // si il y'a des erreurs (confirmation mot de passe, format mail ou erreur sur l'avatar), on les affiche
        {

          var errors = "";

          errors +=  '<div class="uk-alert-danger" uk-alert>';

          errors += '<a class="uk-alert-close" uk-close></a>';

            for(let i = 0; i < JsonAddCommResult.error.length; i++)
          {
            errors += '<p>'+JsonAddCommResult.error[i]+'</p>';
          }                      
            errors += '</div>';

            document.querySelector('#notifsettings').innerHTML += errors;

            window.scrollTo(0, 0);
        }
          else // mise à jour des informations réussies
        {

          document.querySelector('#form_settings').reset();

          window.scrollTo(0, 0); // on remonte en haut pour l'affichage des notifications

          switch(JsonAddCommResult.updatesettingstate)
        {
          case 'passwordchanged': // mot de passe changé

                                  // notification

                            document.querySelector('#notifsettings').innerHTML = '<div class="uk-alert-success" uk-alert>'+
                                                                                  '<a class="uk-alert-close" uk-close></a>'+
                                                                                  '<p>Mise à jour de vos informations réussies. Cependant votre mot de passe ayant était changé, vous allez être redirigé vers la page de connexion afin de vous connecter avec votre nouveau mot de passe.</p>'+
                                                                                  '</div>';
          
                            // redirection après 5 secondes vers la page de login

                            setTimeout(function(){
                                                    window.location.href = '/larablog/public/login';
                                                  }, 5000);

          break;

          case 'updatesettingsok': // mise à jour mail ou avatar réussie

                              // notification

                              document.querySelector('#notifsettings').innerHTML = '<div class="uk-alert-success" uk-alert>'+
                                                                                    '<a class="uk-alert-close" uk-close></a>'+
                                                                                    '<p>Informations mise à jour avec succès !</p>'+
                                                                                    '</div>';
       
          break;
        }
      }

      }).catch(function(err) {
  
  // notification d'échec
  
            console.log(err);
  
      });

  }

  // preview avatar

//preview de l'Avatar

export function PreviewAvatar(UserFile) {

     let size = UserFile.files[0].size; // taille fichier

     let extn = UserFile.files[0].type; // extension fichier

      if (extn == "image/jpg" || extn == "image/jpeg" || extn == "image/png")
     { // fichier jpg/jpeg/png

        if(size <= 3047171) // taille inférieur ou égale à 3mo
       {
          if (typeof (FileReader) != "undefined") // si vieux navigateur
         {

             var reader = new FileReader();

              reader.onload = function()
             {
                document.querySelector('#previewHolder').src = reader.result;
             }

             reader.readAsDataURL(UserFile.files[0]);

         } 
          else 
         {

            //notification vieux navigateur

            document.querySelector('#notifsettings').innerHTML = '<div class="uk-alert-danger" uk-alert>'+
                                                                  '<a class="uk-alert-close" uk-close></a>'+
                                                                  '<p>Votre navigateur ne permet pas de lire ce fichier.</p>'+
                                                                  '</div>';
         }
       }
        else 
       {

            //notification fichier trop gros

            document.querySelector('#notifsettings').innerHTML = '<div class="uk-alert-danger" uk-alert>'+
                                                                  '<a class="uk-alert-close" uk-close></a>'+
                                                                  '<p>Ce fichier est trop gros.</p>'+
                                                                  '</div>';

                  UserFile.value = ""; // on vide l'input

                  // on remet la preview par défaut

                  document.querySelector('#previewHolder').src = '/larablog/public/storage/img/avatar/default.png';

                  // on scroll en haut

                  window.scrollTo(0, 0);
       }
     } 
      else 
     {

          //notification extension fichier

          document.querySelector('#notifsettings').innerHTML = '<div class="uk-alert-danger" uk-alert>'+
                                                              '<a class="uk-alert-close" uk-close></a>'+
                                                              '<p>Seuls les fichier jpeg, jpg ou png sont acceptés.</p>'+
                                                              '</div>';

                  UserFile.value = ""; // on vide l'input

                  // on remet la preview par défaut

                  document.querySelector('#previewHolder').src = '/larablog/public/storage/img/avatar/default.png';

                  // on scroll en haut

                  window.scrollTo(0, 0);
           
     }
 };

 // suppression du compte utilisateur

export function DeleteUserAccount()
{

  let header; // header conditionel suivant le role de l'utilisateur : si author on demande si on conserve les articles sinon le header est nul

    if(document.querySelector('input[name="articledecision"]'))
  {
    header = document.querySelector('input[name="articledecision"]:checked').value;
  }
    else
  {
    header = null;
  }

  fetch('/larablog/public/deleteaccount', {
    headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector("[name='_token']").value,
                'UserChoice': header,
                'DeleteOrigin': 'user'
              }
  })
    .then(function(response) 
  {
    return response.json(); // récupération des données au format JSON
  })
  .then(function(JsonDeleteAccountResult) {

    switch(JsonDeleteAccountResult.deleteaccountstate)
    {
      case 'deleteaccountok': // utilisateur supprimé

                          // fermeture modale

                          UIkit.modal('#modal-deleteaccount').hide();

                          // on scroll en haut

                          window.scrollTo(0, 0);

                          // notification

                          document.querySelector('#notifsettings').innerHTML = '<div class="uk-alert-success" uk-alert>'+
                                                                                  '<a class="uk-alert-close" uk-close></a>'+
                                                                                  '<p>Compte supprimé avec succès. Vous allez être redirigé vers la page d\'accueuil.</p>'+
                                                                                  '</div>';
          
                            // redirection après 3 secondes vers la page d'accueuil

                          setTimeout(function(){
                                                  window.location.href = '/larablog/public/';
                                                }, 3000);


      break;

      case 'deleteaccountadmin': // suppression d'un compte admin alors qu'il est le seul

                          // fermeture modale

                          UIkit.modal('#modal-deleteaccount').hide();

                          // on scroll en haut

                          window.scrollTo(0, 0);

                          // notification

                          document.querySelector('#notifsettings').innerHTML = '<div class="uk-alert-warning" uk-alert>'+
                                                                                  '<a class="uk-alert-close" uk-close></a>'+
                                                                                  '<p>Vous ne pouvez pas supprimer votre compte Administrateur avant d\'avoir donné ces droits à au moins un autre compte LaraBlog.</p>'+
                                                                                  '</div>';
          

      break;

      case 'deleteaccountnotok': // utilisateur non supprimé

                              // notification
      
                              document.querySelector('#notifsettings').innerHTML = '<div class="uk-alert-danger" uk-alert>'+
                                                                                    '<a class="uk-alert-close" uk-close></a>'+
                                                                                    '<p>Compte non supprimé !</p>'+
                                                                                    '</div>';

        break;
    }

  }).catch(function(err) {

// notification d'échec : problème technique, serveur,...

      console.log(err);

  });
}
  