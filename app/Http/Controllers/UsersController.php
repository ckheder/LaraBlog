<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Articles;
use Illuminate\Http\Request;
use App\Http\Requests\UsersRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    /**
     * Enregistrement d'un nouvel utilisateur
     *
     * @param UsersRequest $request
     * @return void
     */
    public function store(UsersRequest $request)
    {

        $user = new User;

        $user->fill([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'role' => 'user'

                    ])->save();
        
        // Création de l'avatar par défaut

        Storage::copy('public/img/avatar/default.png', 'public/img/avatar/'.$request->name.'');

        // Connexion du nouvel utilisateur

        Auth::login($user);

        // Redirection vers l'accueuil

        return redirect()->route('index')->with('status', 'success') // status : success, danger ou warning
                        ->with('message', 'Enregistrement réussi ! Bienvenue '.$request->name.' sur LaraBlog'); // message : message à afficher
        
    }


    /**
     * Affichage de la vue de modification des informations utilisateurs
     *
     * @return void
     */
    public function settings()
    {
        return view('users.settings');
    }

    /**
     * Traitement des modifications des informations utilisateurs
     *
     * @param Request $request
     * @return void
     */
    public function processsettings(Request $request)
    {

            if($request->ajax()) // requête AJAX
        {

        // Récupération de l'utilisateur courant

        $currentuser = User::find($request->user()->id);

        //traitement avatar

            if($request->has('avataruser'))
        {
            
               $validfile = array('avataruser' => $request->file('avataruser'));

               // on valide l'upload de l'avatar

               $validator = Validator::make($validfile, [
                                                            'avataruser' => 'image|mimes:jpg,jpeg,png|max:3000',
                                                        ],
            [
                'avataruser.image'=> 'Votre fichier doit être une image.',
                'avataruser.mimes'=> 'Votre fichier doit être une image de type jpeg, jpg ou png.',
                'avataruser.max'=> 'Image trop lourde'
            ]
        );

                if ($validator->fails()) // échec validation : renvoi d'un message
            {
    
                return response()->json(['error'=>$validator->errors()->all()]);
    
            }
                else // Déplacement et renommage de l'image uploadé
            {
                Storage::putFileAs('public/img/avatar', $request->file('avataruser'), $request->user()->name );
            }
                
        }

        // traitement mail

        // si on envoi un mail et une confirmation

            if($request->filled('email') && $request->filled('email_confirmation'))
        {

         $validemail = array('email' => $request->email,'email_confirmation'=> $request->email_confirmation);

         // on valide l'adresse mail et sa confirmation

         $validator = Validator::make($validemail,[
                                                    'email' => 'email|confirmed|unique:users',
                                                    ],
        [
            'email.confirmed'=> 'Les deux adresses mail ne correspondent pas.',
            'email.unique' => 'Cette adresse mail existe déjà.',
        ]
    
        );

                if ($validator->fails()) //échec validation
            {

                return response()->json(['error'=>$validator->errors()->all()]);

            }
                else // mise à jour de l'adresse mail
            {
                $currentuser->email = $request->email;

                $currentuser->save();
            }

        }

        // traitement password

        // si on envoi un mot de passe et une confirmation

            if($request->filled('password') && $request->filled('password_confirmation'))
        {

          $validpassword = array('password' => $request->password,'password_confirmation' => $request->password_confirmation);

          // on valide le mot de passe et sa confirmation

          $validator = Validator::make($validpassword, [
 
                                                        'password' => 'confirmed|min:8',
 
                                                        ],
         [
            'password.confirmed'=> 'Les deux mot de passe ne correspondent pas.', // custom message
            'password.min' => 'Le mot de passe doit faire au minimum 8 caractères.'
         ]);
 
             if ($validator->fails()) //échec validation
         {
 
             return response()->json(['error'=>$validator->errors()->all()]);
 
         }
             else // validation du mot de passe
         {
            $currentuser->password = Hash::make($request->password);

            $currentuser->save();

            if($currentuser->wasChanged()) // si le mot de passe a était changé, on déconnecte pour une reconnexion avec le nouveau
          {

            Auth::logout();
     
            $request->session()->invalidate();
         
            $request->session()->regenerateToken();

            return response()->json([
                                   'updatesettingstate' => 'passwordchanged'
                                   ]);
          }

         }

        }
         
        return response()->json([
                                    'updatesettingstate' => 'updatesettingsok'
                                ]);
    }
        else // accès non AJAX
    {
        abort(404);
    }

    }

    /**
     * Suppression d'un compte utilisateur (par défaut un utilisateur supprime son compte donc $userid est null)
     *
     * @param Request $request
     * @return void
     */
    public function deleteaccount(Request $request, $userid = null)
    {

        // si $userid existe il s'agit d'une suppression fait par l'admin (on vérifie quand même qu'il est admin)

            if($request->header('DeleteOrigin') == 'admin' && $request->user()->role == 'admin')
        {
            $user = User::findOrFail($userid); // on récupère l'utilisateur que l'admin veut delete

            $userid = $user->id; // identifiant

            $username = $user->name; // username

            $role = $user->role; // role

        }
            else // utilisateur courant qui veut supprimer son compte
        {
            $userid = $request->user()->id; // identifiant

            $username = $request->user()->name; // username

            $role = $request->user()->role; // role

        }

        // si l'utilisateur est un admin, on vérifie si il est le seul : si oui il ne peut pas supprimer son compte

            if ($role == 'admin')
        {
            $checknbadmincount = Controller::CheckNbAdminAccount();

                if($checknbadmincount === 1 && $request->header('DeleteOrigin') == 'admin') // répoonse au delete user par admin
            {
                return response()->json([
                                            'deleteuserstate' => 'nomoreadmin'
                                        ]);
            }
                elseif($checknbadmincount === 1 && $request->header('DeleteOrigin') == 'user') // réponse au delete par l'user courant
            {
                return response()->json([
                                            'deleteaccountstate' => 'deleteaccountadmin'
                                        ]);
            }
        }

        // gestion article

            if($request->header('UserChoice') == 'deletearticle') // on supprime les articles associés
        {
            Articles::where('author', $username)->delete();
        }

            if(User::destroy($userid) && Storage::delete('public/img/avatar/'.$username))
        {

                if($request->header('DeleteOrigin') == 'admin') // suppression fait par l'admin : redirection vers la liste des utilisateurs
            {
                return response()->json([
                                            'deleteuserstate' => 'deleteuserok'
                                        ]);
            }
                elseif($request->header('DeleteOrigin') == 'user') // suppression fait par un utilisateur : redirection vers l'index
            {

                Auth::logout();
     
                $request->session()->invalidate();
    
                return response()->json([
                                            'deleteaccountstate' => 'deleteaccountok'
                                        ]);
            }

        }
            else // échec suppression compte
        {
                if($request->header('DeleteOrigin') == 'admin') // suppression fait par l'admin : redirection vers la liste des utilisateurs
            {
                return response()->json([
                                            'deleteuserstate' => 'deleteusernotok'
                                        ]);
            }
            elseif($request->header('DeleteOrigin') == 'user')
            {
                return response()->json([
                                            'deleteaccountstate' => 'deleteaccountnotok'
                                        ]);
            }
        }

    }
}
