<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{

    /**
     * Vue login
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {

        // stockage en session de l'url demandée avant login

            if(!session()->has('url.intended'))
        {
            session(['url.intended' => url()->previous()]);

        }

        return view('auth.login');
    } 
    
    
    /**
     * Connexion d'un utilisateur
     *
     * @param Request $request
     * @return void
     */  
    public function login(Request $request)
    {

        // vérification de la présence de l'adresse mail et du mot de passe

        $credentials = $request->validateWithBag('login',[
                                            'email' => ['required', 'email'],
                                            'password' => ['required'],
                                        ]);
 
        // on vérifie si la case 'se souvenir de moi' est coché ou non

        $remember = $request->has('remember') ? true : false;

        // on authentifie l'utilisateur

            if (Auth::attempt($credentials, $remember)) 
        {
            $request->session()->regenerate();
 
            // redirection vers la page précédente

            return redirect($request->session()->get('url.intended'))->with('status', 'success')
                                                ->with('message', 'Bonjour '.$request->user()->name.'');
        }

        // sinon on retourne à la vue login avec juste l'email de réécrit
 
            return back()->withErrors([
                                        'email' => 'The provided credentials do not match our records.',
            ], 'login')->onlyInput('email');
    }
    
    /**
     * Vue enregistrement utilisateur
     *
     * @return void
     */
    public function registration()
    {
        return view('auth.registration');
    }
      

    /**
     * Fonction de déconnexion d'un utilisateur
     *
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        Auth::logout();
     
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect()->route('index')->with('status', 'success') // status : success, danger ou warning
                        ->with('message', 'Déconnexion réussie');
    }
}