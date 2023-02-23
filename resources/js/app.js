import './bootstrap';

// importation des deux fonctions venant de editor.js

import { insertAtCaret , removebr } from './editor';

// mise à disposition dans le scope globale

window.insertAtCaret = insertAtCaret;

window.removebr = removebr;

// importation de highlight js et du style atom-one-dark.css

import hljs from 'highlight.js';

import 'highlight.js/styles/atom-one-dark.css';

// fonction qui va appliquer le highlight js aux balises <pre> aux chargement de la page

setTimeout(function () {
    document
        .querySelectorAll('pre')
        .forEach((block) => hljs.highlightElement(block));
}, 1000);


// faire une recherche a l'appui sur la touche 'Entrée'

document.querySelector('#search').addEventListener('keydown', function (e) 
{

        if (e.keyCode === 13) 
    {
        e.preventDefault();

         // on redirige vers la route de recherche
 
        window.location.href = '/larablog/public/search/'+document.querySelector('#search').value+'';
    }

})

// affichage du mot de passe dans l'input

  function displayPassword(param) 
{
 var x = document.querySelector("#"+param);

    if (x.type === "password") 
  {
    x.type = "text";
  } 
    else 
  {
    x.type = "password";
  }
}

window.displayPassword = displayPassword;

// désactivation du coller sur les champs de confirmation de l'email et du mot de passe

  document.querySelectorAll("#email_confirmation, #password_confirmation").forEach(item => 
{
  item.addEventListener('paste', event => {

    event.preventDefault();

    return false;

  })
})

// comment

import { UsernewComment, loadcomments, deletecomment } from './comment';

window.UsernewComment = UsernewComment; // ajouter commentaire

window.loadcomments = loadcomments; // chargement des commentaires

window.deletecomment = deletecomment; // suppression des commentaires

  if(document.querySelector('#list_comm')) // si on est sur la page 'read' d'un article, chargement des commentaires
{

  document.querySelector('#list_comm').addEventListener("load", loadcomments()); // chargement des commentaires au chargement de la page

}

// admin

import  { deletearticle , admindeletetag , updateuserrole , admindeleteuser, OpenModalDeleteUser} from './admin';

window.deletearticle = deletearticle; // suppression d'un article (valable pour les auteurs)

window.admindeletetag = admindeletetag; // suppression d'un tag

window.updateuserrole = updateuserrole; // mise à jour du rôle d'un utilisateur

window.admindeleteuser = admindeleteuser; // suppression d'un utilisateur

window.OpenModalDeleteUser = OpenModalDeleteUser; // affichage modale delete user

// mise à jour settings

import { UserupdateSettings, PreviewAvatar , DeleteUserAccount} from './settings';


window.UserupdateSettings = UserupdateSettings;

window.PreviewAvatar = PreviewAvatar;

window.DeleteUserAccount = DeleteUserAccount;

// messagerie

  if(document.querySelector('#list_conv')) // si on est sur la page d'accueuil de la messagerie, chargement des conversations actives
{

  document.querySelector('#list_conv').addEventListener("load", loadConvItem("activeconv"));

}

import { loadConvItem, UsernewMessageFromModal } from './message';

window.loadConvItem = loadConvItem; // chargement suivant le click de la page des conversations actives/masquées

window.UsernewMessageFromModal = UsernewMessageFromModal; // traitement de l'envoi d'un message de la fenêtre modal de la page d'accueuil de la messagerie

// Recommendations

import { recommendarticle } from './recommends';

window.recommendarticle = recommendarticle;

//emoji

 // traitement des emoji dans le textarea des articles,commentaires et message
 
  function insertemoji(emoji,textarea)
 {

    //suppression de l'extension du fichier

    emoji  = emoji.replace(/\.[^/.]+$/, "");

    // mise en forme :emoji:

    emoji = ' :'+emoji+': ';

    //ajout au textarea/input

    document.querySelector('#'+textarea).value += emoji;

    // focus sur la textarea/input

    document.querySelector('#'+textarea).focus();
     
 };

 window.insertemoji = insertemoji;

 // infinite ajax scroll

 import InfiniteAjaxScroll from '@webcreate/infinite-ajax-scroll';

 window.InfiniteAjaxScroll = InfiniteAjaxScroll;

 // Test si la page à l'id specifique à Infinite Ajax Scroll

  if(document.querySelector('#Container'))
 {

    let item; // class utiliser par Infinite Ajax Scroll pour charger les données

    if(document.querySelector('article') == null) // pages d'administration
  {
    item = '.tabinfos';
  }

    else if(document.querySelector('article').classList.contains('uk-article')) // page d'accueuil du LaraBlog
  {
    item = '.uk-article';
  }

    else if(document.querySelector('article').classList.contains('uk-comment')) // page des conversations
  {
    item = '.uk-comment';
  }

  // Traitement Infinite Ajax Scroll

 let ias = new InfiniteAjaxScroll('#Container', {
  item: item,
  logger: false, // on n'affiche pas les erreurs et action de traiteemnt dans la console
  next: '.next',
  pagination: '.pagination',
  spinner: {
              // Definition du spinner

              element: document.querySelector('.uk-spinner'),

              //delay: 10600,

              // Affichage du spinner

              show: function(element) {
                                        element.removeAttribute('hidden'); // default behaviour
                                      },
  
              // Masquage du spinner

              hide: function(element) {

                                        element.setAttribute('hidden', ''); // default behaviour
                                      }
            }
  })

  // affichage de la div de fin de chargement des données

ias.on('last', function() {

                            let el = document.querySelector('.no-more');

                            el.removeAttribute("hidden");
                          })
 }
