// Recommends.js //
//
// Recommender 1 article //

  export function recommendarticle(idarticle, authorarticle)
{

    const data = {
                    "idarticle":idarticle, // identifiant de l'article
                    "authorarticle": authorarticle // identifiant de l'utilisateur concerné
                  }

    fetch('/larablog/public/recommends', {

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
    .then(function(JsonRecommendsResult) {

        switch(JsonRecommendsResult.addrecommendstate)
      {
        case 'authorarticle': // l'auteur de l'article ne peut recommender son propre article

                              // notification
                          
                              document.querySelector('#notifcomm').innerHTML =  '<div class="uk-alert-warning" uk-alert>'+
                                                                                '<a class="uk-alert-close" uk-close></a>'+
                                                                                '<p>Vous ne pouvez pas recommender votre propre article.</p>'+
                                                                                '</div>';

                              

        break;

        case 'newrecommendok': // recommendation crée

                                  // notification
        
                                    document.querySelector('#notifcomm').innerHTML = '<div class="uk-alert-success" uk-alert>'+
                                                                                  '<a class="uk-alert-close" uk-close></a>'+
                                                                                  '<p>Article recommendé !</p>'+
                                                                                  '</div>';

                                  // incrémentation du nombre de recommendation pour cet article

                                    document.querySelector('#nbrecommends').textContent ++;

      break;

      case 'newrecommendnotok': // échec création de recommendation

                                  // notification

                                  document.querySelector('#notifcomm').innerHTML = '<div class="uk-alert-danger" uk-alert>'+
                                                                                    '<a class="uk-alert-close" uk-close></a>'+
                                                                                    '<p>Impossible de recommendé cet article !</p>'+
                                                                                    '</div>';

      break;

      case 'alreadyrecommend': // article déjà recommendé

                                // notification

                                document.querySelector('#notifcomm').innerHTML = '<div class="uk-alert-warning" uk-alert>'+
                                                                                  '<a class="uk-alert-close" uk-close></a>'+
                                                                                  '<p>Vous avez déjà recommender cet article !</p>'+
                                                                                  '</div>';

break;

      }


    }).catch(function(err) {

// notification d'échec : problème technique, serveur,...

        console.log(err);

    });
}