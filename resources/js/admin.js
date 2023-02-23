// Admin.js //
//
// Gestion des actions administrateur sur l'interface admin //


// supprimer un article

  export function deletearticle(idarticle)
{

    fetch('/larablog/public/articles/delete/'+idarticle+'', {

            headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                      },
                      method: "POST",

    })
      .then(function(response) 
    {
      return response.json(); // récupération des données au format JSON
    })
    .then(function(JsonArticleCommResult) {

        switch(JsonArticleCommResult.deletearticlestate)
      {
        case 'deletearticleok': // suppression d'article réussi

                              // notification
                          
                              document.querySelector('#notif').innerHTML =  '<div class="uk-alert-success" uk-alert>'+
                                                                            '<a class="uk-alert-close" uk-close></a>'+
                                                                            '<p>Article supprimé !</p>'+
                                                                            '</div>';

                              // suppression de l'article du DOM

                              document.querySelector('#article'+idarticle).remove();

        break;

        case 'deletearticlenotok': // échec suppression d'article

                                  // notification
        
                                    document.querySelector('#notif').innerHTML = '<div class="uk-alert-danger" uk-alert>'+
                                                                                  '<a class="uk-alert-close" uk-close></a>'+
                                                                                  '<p>Article non supprimé !</p>'+
                                                                                  '</div>';
      }


    }).catch(function(err) {

// notification d'échec : problème technique, serveur,...

        console.log(err);

    });
}

// suppression d'un tag d'article

export function admindeletetag(nametag)
{

    fetch('/larablog/public/admin/tags/delete/'+nametag+'', { 

    headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              },
              method: "POST",

  })
    .then(function(response) 
  {
    return response.json(); // récupération des données au format JSON
  })
  .then(function(JsonDeleteTagResult) {

    switch(JsonDeleteTagResult.deletetagstate)
    {
      case 'deletetagok': // suppression de catégorie réussie

                          // notification
                        
                        document.querySelector('#notif').innerHTML = '<div class="uk-alert-success" uk-alert>'+
                                                                      '<a class="uk-alert-close" uk-close></a>'+
                                                                      '<p>Catégorie supprimée !</p>'+
                                                                      '</div>';
                        // suppression du tag du DOM
                        
                        document.querySelector('#tag'+nametag+'').remove();

      break;

      case 'deletetagnotok': // échec suppression du tag

                            // notification

                              document.querySelector('#notif').innerHTML = '<div class="uk-alert-danger" uk-alert>'+
                                                                            '<a class="uk-alert-close" uk-close></a>'+
                                                                            '<p>Catégorie non supprimée !</p>'+
                                                                            '</div>';

        break;
    }



  }).catch(function(err) {

// notification d'échec : problème technique, serveur,...

      console.log(err);

  });
}

// mise à jour du rôle d'un utilisateur

export function updateuserrole(role, userid)
{

    const data = {
                    "role":role.value, // récupération du nouveau rôle
                    "userid": userid // identifiant de l'utilisateur concerné
                  }

    fetch('/larablog/public/admin/user/updateuserrole', { 
      headers: {
                  'X-Requested-With': 'XMLHttpRequest',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                  'Content-type': 'application/json' // requête JSON
                },
                
                method: "POST",
  
                body: JSON.stringify(data)
    })
      .then(function(response) 
    {
      return response.json(); // récupération des données au format JSON
    })
    .then(function(JsonUpdateUserResult) {

      switch(JsonUpdateUserResult.updateroleuserstate)
      {
        case 'updateuserrolenochange': // pas de modification car rôle identique au précédent

                                      // notification
                          
                          document.querySelector('#notif').innerHTML = '<div class="uk-alert-warning" uk-alert>'+
                                                                        '<a class="uk-alert-close" uk-close></a>'+
                                                                        '<p>Rôle non modifié car identique au précédent !</p>'+
                                                                        '</div>';
  
                          
  
        break;

        case 'nomoreadmin': // pas de modification car rôle identique au précédent

                                      // notification
                          
                          document.querySelector('#notif').innerHTML = '<div class="uk-alert-warning" uk-alert>'+
                                                                        '<a class="uk-alert-close" uk-close></a>'+
                                                                        '<p>Il doit y avoir au minimum 1 compte Administrateur ! Assignez le rôle \'Admin\' à un autre utilisateur si vous voulez enlever les droits admin sur ce compte.</p>'+
                                                                        '</div>';
  
                          
  
        break;
  
        case 'updateuserroleok': // rôle mis à jour

                                // notification
        
                                document.querySelector('#notif').innerHTML = '<div class="uk-alert-success" uk-alert>'+
                                                                              '<a class="uk-alert-close" uk-close></a>'+
                                                                              '<p>Rôle mis à jour !</p>'+
                                                                              '</div>';
                                
                                // MAJ text span du role                                           

                                document.querySelector('#role'+userid).textContent = role.value; 

                                // reset du select

                                document.querySelector('#updaterole'+userid).selectedIndex = null; 
  
          break;

          case 'updateuserrolenotok': // échec mis à jour du rôle

                                      // notification
          
                                      document.querySelector('#notif').innerHTML = '<div class="uk-alert-danger" uk-alert>'+
                                                                                    '<a class="uk-alert-close" uk-close></a>'+
                                                                                    '<p>Rôle non modifié !</p>'+
                                                                                    '</div>';

          break;
      }
  
  
  
    }).catch(function(err) {
  
  // notification d'échec : problème technique, serveur,...
  
        console.log(err);
  
    });
}

// suppression d'un utilisateur

// affichage modale

  export function OpenModalDeleteUser(userid,username)
{

  // ouverture modale

  UIkit.modal('#modal-deleteaccount').show();

  // Mise à jour du titre de la modale + bouton de suppression en utilisant dynamiquement le nom de l'utilisateur concerné 

  let UserNameModal = document.querySelectorAll('.ModalUsername');

  UserNameModal.forEach(element => {

                                      element.textContent = username;
    
                                    });

  // traitement affichage de texte suivant le rôle

  let UserRole = document.querySelector('#role'+userid).textContent; // on récupère le rôle actuelle

    switch (UserRole)
  {
    case 'admin': document.querySelector('.uk-modal-body').innerHTML = '<p class="uk-margin">'+
    
                                                                        'Supprimer également les articles rédigés par '+username+ '?'+
                                  
                                                                          '<div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">'+
                          
                                                                            '<label><input class="uk-radio" type="radio" value="deletearticle" name="articledecision" checked> Oui</label>'+
                          
                                                                            '<label><input class="uk-radio" type="radio" value="keeparticle" name="articledecision"> Non</label>'+
                          
                                                                          '</div>'+
                                      
                                                                      '</p>'+

                                                                      '<div class="uk-alert-danger" uk-alert>'+

                                                                        '<p>Attention : vous ne pourrez pas supprimer ce compte si il est le seul compte Administrateur actif.</p>'+

                                                                      '</div>';

    break;

    case 'author' :  document.querySelector('.uk-modal-body').innerHTML = 'Supprimer également les articles rédigés par '+username+ '?'+
                                                                            
                                                                            '<div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">'+

                                                                              '<label><input class="uk-radio" type="radio" value="deletearticle" name="articledecision" checked> Oui</label>'+

                                                                              '<label><input class="uk-radio" type="radio" value="keeparticle" name="articledecision"> Non</label>'+

                                                                            '</div>';

    break;

    case 'user' :  document.querySelector('.uk-modal-body').innerHTML = '';

  break;

} 

// traitement id user sur le bouton 

document.querySelector('#BtnDeleteUser').setAttribute('onclick', 'admindeleteuser('+userid+')');

}

// traitement

export function admindeleteuser(userid)
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
  fetch('/larablog/public/deleteaccount/'+userid, {
    headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'UserChoice': header,
                'DeleteOrigin': 'admin'
              }

  })
    .then(function(response) 
  {
    return response.json(); // récupération des données au format JSON
  })
  .then(function(JsonDeleteUserResult) {

    switch(JsonDeleteUserResult.deleteuserstate)
    {
      case 'deleteuserok': // utilisateur supprimé

                          // notification
                        
                          document.querySelector('#notif').innerHTML = '<div class="uk-alert-success" uk-alert>'+
                                                                        '<a class="uk-alert-close" uk-close></a>'+
                                                                        '<p>Utilisateur supprimé !</p>'+
                                                                        '</div>';

                           // fermeture modale

                          UIkit.modal('#modal-deleteaccount').hide();

                          // suppression de l'utilisateur du DOM

                          document.querySelector('#user'+userid).remove();

      break;

      case 'nomoreadmin': // // suppression d'un compte admin alors qu'il est le seul
      
                                      // notification
                          
                          document.querySelector('#notif').innerHTML = '<div class="uk-alert-warning" uk-alert>'+
                                                                        '<a class="uk-alert-close" uk-close></a>'+
                                                                        '<p>Il doit y avoir au minimum 1 compte Administrateur ! Assignez le rôle \'Admin\' à un autre utilisateur si vous voulez supprimer ce compte.</p>'+
                                                                        '</div>';

                            // fermeture modale

                          UIkit.modal('#modal-deleteaccount').hide();
                          
      break;

      case 'deleteusernotok': // utilisateur non supprimé

                              // notification
      
                              document.querySelector('#notif').innerHTML = '<div class="uk-alert-danger" uk-alert>'+
                                                                            '<a class="uk-alert-close" uk-close></a>'+
                                                                            '<p>Impossible de supprimer cet utilisateur !</p>'+
                                                                            '</div>';

        break;
    }



  }).catch(function(err) {

// notification d'échec : problème technique, serveur,...

      console.log(err);

  });
}