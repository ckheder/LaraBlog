<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Conversations;
use App\Http\Requests\MessagesRequest;
use Illuminate\Support\Facades\Validator;


class MessagesController extends Controller
{
        /**
         * Page d'accueuil de la messagerie : affichage des conversations actives
         *
         * @param Request $request
         * @return void
         */

            public function index()
        {
            
            return view('message.index');
        }


        /**
         * Affichage des conversations actives d'un utilisateur
         * 
         * Chargé en AJAX
         *
         * @param Request $request
         * @return void
         */
        public function activeconv(Request $request)
    {
        $message = $this->getconversation($request->user()->name, 1);

        return view('message.activeconv', ['messages' => $message]);
    }
        /**
         * Affichage des conversations masquées d'un utilisateur
         * 
         * Chargé en AJAX
         *
         * @param Request $request
         * @return void
         */

        public function hideconv(Request $request)
    {
        $hidemessage = $this->getconversation($request->user()->name, 0);
   
        return view('message.hideconv', ['hidemessages' => $hidemessage]);
    }

    /**
     * Affichage d'une conversation
     *
     * @param [type] $idconv
     * @param Request $request
     * @return void
     */

        public function read($idconv, Request $request) 
    {

        // vérification de l'existence de cette conversation

        $conv = Conversations::findOrFail($idconv);

        // test si je peux lire cette conversation

        $this->authorize('read', $conv);

        // récupération de l'autre utilisateur et du statut de la conversation

            if($conv->user_one == $request->user()->name)
        {
            $other = $conv->user_two;

            $statut = $conv->is_visible_user_one;
        }
            else
        {
            $other = $conv->user_one;

            $statut = $conv->is_visible_user_two;
        }

        // pagination par 5 et classement du plus récent au plus ancien des messages de cette conversation

        $messages = $conv->message()->with('user:role,name')->latest()->paginate(5);
 
        return view('message.read', ['messages' => $messages, 'other' => $other, 'statutconv' => $statut]);
                                                                         
    }
        /**
         * Ajout d'un message
         *
         * @param MessagesRequest $request
         * @return void
         */
        public function store(Request $request)
    {

            // si le champ de message n'est pas remplie

                if(!$request->filled('corps_message'))
            {
                    if($request->ajax()) // renvoi AJAX si envoi depuis la modale messagerie
                {
                    return response()->json([
                                                'addmessagestate' => 'nomsg'
                                            ]);
                }
                    else // renvoi d'une erreur vers la page des conversations
                {
                    return back()->with('status', 'danger') // status : success, danger ou warning
                                ->with('message', 'Le message ne peut être vide'); // message : message à afficher
                }

        }
            $newmessage = new Messages();

            if($request->has('idconv')) // message depuis une conversation
        {
            $newmessage->conversation = $request->idconv;
        }
            elseif($request->ajax()) // message depuis l'accueuil de la messagerie
        {
            // test d'un destinataire existant

                if(!$request->filled('destinataire'))
            {
                return response()->json([
                                            'addmessagestate' => 'nodest'
                                        ]);
            }

                elseif($request->destinataire == $request->user()->name)
            {
                return response()->json([
                                            'addmessagestate' => 'messagetomyself'
                                        ]);
            }
                else
            {
                $checkdestinataire = User::where('name', $request->destinataire)->first();
            
                // destinataire inexistant
    
                    if(is_null($checkdestinataire))
                {
                    return response()->json([
                                                'addmessagestate' => 'unknownuser'
                                            ]);
                }
            }

            // recherche d'une conversation existante et récupération de son id

            $checkconv = Conversations::where(function($q1) use ($request) {
                $q1->where('user_one',$request->user()->name)
                ->where('user_two', $request->destinataire);
            })
 
            ->orWhere(function($q2) use ($request) {
                $q2->where('user_one',$request->destinataire)
                ->where('user_two', $request->user()->name);
            })
          
          ->first();

                if(is_null($checkconv)) // pas de résultat : création d'une nouvelle conversation
            {
                $newconv = new Conversations();

                $newconv->fill([
                                'user_one' => $request->user()->name,
                                'user_two' => $request->destinataire,
                                'is_visible_user_one' => 1,
                                'is_visible_user_two' => 1
                    ])->save();

                    $newmessage->conversation = $newconv->id_conv;
            }
                else // récupération de l'id de conversation + test conv bloquée (AJAX uniquement)
            {
                    if(($checkconv->user_one = $request->user()->name && $checkconv->is_visible_user_one = 0) || ($checkconv->user_two = $request->user()->name && $checkconv->is_visible_user_two = 0) || $request->user()->role != 'admin')
                {
                    
                                return response()->json([
                                                            'addmessagestate' => 'disableconv'
                                                        ]);
                }

                    $newmessage->conversation = $checkconv->id_conv;
            }
        }

        $newmessage->author_message = $request->user()->name;

        $newmessage->corps_message = Controller::parsing_content(strip_tags($request->corps_message));

                if($newmessage->save())// message envoyé
            {
                    if($request->ajax()) // si requête AJAX donc envoi via modale messagerie
                {
                    return response()->json([
                                                'addmessagestate' => 'msgsend'
                                            ]);
                }
                    else // revnoi message vers la page d'une conversation
                {
                    return back()->with('status', 'success') // status : success, danger ou warning
                                ->with('message', 'Message envoyé'); // message : message à afficher
                }
        }

    }

    /**
     * Mise à jour du statut d'une conversation
     *
     * @param [type] $idconv
     * @param Request $request
     * @return void
     */
        public function update($idconv, Request $request)
    {

        // vérification de l'existence de cette conversation

        $conv = Conversations::findOrFail($idconv);

        // vérification du statut actuel de la conversation

            if($conv->user_one == $request->user()->name)
        {
            $visible = 'is_visible_user_one'; 
        }
            else
        {
            $visible = 'is_visible_user_two';
        }

        // inversmement du statut de la conversation

        $conv->$visible = !$conv->$visible;

        // renvoi d'un message selon le cas

            if($conv->$visible == 1)
        {
            $statut = 'Cette conversation est désormais visible.';
        }
            else
        {
            $statut = 'Cette conversation est désormais masquée.';
        }

        $conv->save();

        return back()->with('status', 'success') // status : success, danger ou warning
                        ->with('message', $statut); // message : message à afficher
    }
    
    /**
     * Récupération de mes conversations
     *
     * @param [type] $username -> utilisateur courant
     * @param [type] $typeconv -> type de conversation à rechercher
     * @return void
     */
        private function getconversation($username, $typeconv) // typeconv : 1 -> conversation visible | 0 ->conversation masquée
    {

        // récupération de mes conversations

        $conversations = Conversations::select('id_conv','user_one','user_two','is_visible_user_one','is_visible_user_two')
        ->where('user_one',$username)
        ->orWhere('user_two', $username)
        ->get();

        $message = array(); // tableau qui contiendra l'id de conv, mon destinataire et un sous tableau qui contiendra les informations sur le dernier message

        foreach ($conversations as $conversation) // pour chaque conversation
    {
        // détermine qui est l'autre utilisateur

            if($conversation->user_one == $username AND $conversation->is_visible_user_one === $typeconv)
        {
            $otheruser = $conversation->user_two;
        }
            elseif($conversation->user_two == $username AND $conversation->is_visible_user_two === $typeconv)
        {
            $otheruser = $conversation->user_one;
        }

        // récupère le dernier message de chaque conversation 

        if(isset($otheruser))
    {
        $lastmessage = $conversation->message()
                                    ->take(1)
                                    ->latest()
                                    ->get()
                                    ->toArray();

        $message[$conversation->id_conv] = array(
                                                    'id_conv' => $conversation->id_conv,
                                                    'otheruser' => $otheruser,
                                                    'lastmessage' => $lastmessage, // tableau contenant les informations du dernier message
                                                    'datelastmessage' => $lastmessage[0]['created_at'] // va servir plus bas à réordonné du plus récent au plus ancien

                                                );
                unset($otheruser);
    }

}

 // réorganisation par colonne pour toujours avoir le message le plus récent

        $columns = array_column($message, 'datelastmessage');

        array_multisort($columns, SORT_DESC, $message);

        return $message;
    }
    
}
