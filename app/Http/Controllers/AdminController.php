<?php

namespace App\Http\Controllers;

use App\Models\Tags;
use App\Models\User;
use App\Models\Articles;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Retourne la liste des articles
     *
     * @return void
     */
        public function listarticle()
    {
        // articles

        $articles = Articles::withCount(['comment','recommends'])->latest()->paginate(10);

        return view('admin.listarticle', ['articles' => $articles]);

    }

    /**
    * Retourne la liste des tags en vue d'une suppression ou d'une modification
    *
    * @return void
    */
        public function listtag()
    {
        $tags = Tags::paginate(5);

        return view('admin.listtag', ['tags' => $tags]);
    }

    /**
     * Affichage de la liste des utilisateurs
     *
     * @return void
     */
        public function listusers()
    {   

        $users = User::select('id','name','email','role','created_at','updated_at')->withCount('article')->paginate(10);

        return view('admin.listusers', ['users' => $users]);
    }

    public function updateuserrole(Request $request)
    {
            if($request->ajax()) // requête AJAX
        {

        $this->authorize('updateuserrole', User::class);

        $UserRoleToUpdate = User::findOrFail($request->input('userid'));

            if($UserRoleToUpdate->role == 'admin')
        {
            $checknbadmincount = Controller::CheckNbAdminAccount();

                if($checknbadmincount === 1)
            {
                return response()->json([
                                            'updateroleuserstate' => 'nomoreadmin'
                                        ]);
            }
        }

        $UserRoleToUpdate->role = $request->input('role');
        

            if($UserRoleToUpdate->save())
        {

                if(!$UserRoleToUpdate->wasChanged()) // si aucun changement n'a était fait
            {
                return response()->json([
                                            'updateroleuserstate' => 'updateuserrolenochange'
                                        ]);
            }
                else
            {
                return response()->json([
                                            'updateroleuserstate' => 'updateuserroleok'
                                        ]);
            }
        }
            else
        {
            return response()->json([
                                        'updateroleuserstate' => 'updateuserrolenotok'
                                    ]);
        }
    }
            else
        {
            abort(404);
        }
    }
}
