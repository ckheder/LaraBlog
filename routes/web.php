<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticlesController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\RecommendsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Route Index Blog */

Route::get('/', [HomeController::class, 'index'])->name('index'); // [Nomducontrolleur::class ,'methode']

/* Route Articles */

Route::controller(ArticlesController::class)->group(function () {

    Route::get('/articles/read/{id}', 'read')->whereNumber('id')->name('readarticle'); /* Afficher un article */

    Route::get('/articles/tag/{tag}', 'showbytag')->name('showbytag');/* Afficher les articles par tags */

    Route::get('/articles/author/{author}', 'showbyauthor')->name('showbyauthor');/* Afficher les articles par auteurs */

    Route::get('/articles/new', 'new')->middleware(['auth', 'isadminorauthor'])->name('newarticle');/* Crée un article : vue */

    Route::get('/articles/myarticle', 'myarticle')->middleware(['auth', 'isadminorauthor'])->name('myarticle');/* Afficher mes articles : vue */

    Route::post('/articles/add', 'store')->middleware(['auth', 'isadminorauthor'])->name('processarticle');/* Crée un article : controller*/

    Route::post('/articles/update/{idarticle}', 'update')->middleware(['auth', 'isadminorauthor'])->whereNumber('idarticle')->name('updatearticle');/* Modifier un article : controller*/

    Route::get('/articles/edit/{idarticle}', 'edit')->middleware(['auth', 'isadminorauthor'])->whereNumber('idarticle')->name('editarticle'); /* Modifier un article : vue */

    Route::post('/articles/delete/{idarticle}', 'delete')->middleware(['auth', 'isadminorauthor'])->whereNumber('idarticle')->name('deletearticle'); /* Supprimer un article : controller */

    Route::get('/search/{search}','search')->name('search'); /* Faire une recherche parmi les articles */
        
});


/* Route admin */

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'isadmin']], function() {

    Route::get('/listarticles', [AdminController::class, 'listarticle'])->name('adminindex'); /* affichage des articles */

    Route::get('/listtag',[AdminController::class, 'listtag'])->name('adminlisttag'); /* affichage des tags */

    Route::get('/listusers',[AdminController::class, 'listusers'])->name('adminlistusers'); /* affichage des tags */

    Route::get('/tags/new', [TagsController::class, 'new'])->name('newtags'); /* Crée un tag : vue */

    Route::post('/tags/add', [TagsController::class, 'store'])->name('addtag');/* Crée un tag : controller*/

    Route::get('/tags/edit/{tag}', [TagsController::class, 'edit'])->name('edittag'); /* Modifier un tag : vue */

    Route::post('/tags/update/{tag}', [TagsController::class, 'update'])->name('updatetag');/* Modifier un tag : controller*/

    Route::post('/tags/delete/{tag}', [TagsController::class, 'destroy'])->name('deletetag'); /* Supprimer un tag : controller */

    Route::post('/user/updateuserrole', [AdminController::class, 'updateuserrole'])->name('updateuserrole'); /* Mettre à jour le rôle d'un utilisateur */
  
});


/* Route comments */

Route::controller(CommentsController::class)->group(function () {

    Route::get('/comments/{idarticle}/{author?}', 'index')->whereNumber('idarticle')->name('commentsbyarticle'); /* Récupération des commentaires pour un article donné */

    Route::post('/comments/new', 'store')->middleware('auth')->name('newcomment'); /* Crée un commentaire : comentaire */

    Route::get('/comments/delete/{idcomment}', 'delete')->middleware('auth')->whereNumber('idcomment')->name('deletecomment'); /* Supprimer un commentaire : controller */  

});


/* Route Auth */

Route::controller(AuthController::class)->group(function () {

    Route::get('/registration', 'registration')->name('registration'); /* Création d'un utilisateur : vue */

    Route::get('/login', 'index')->name('login'); /** Login d'un utilisateur : vue */

    Route::post('/processlogin', 'login')->name('processlogin'); /* Login d'un utilisateur : controller */

    Route::get('/logout', 'logout')->name('logout'); /* Déconnexion d'un utilisateur : controller */
        
});

/* Route Users */

Route::controller(UsersController::class)->group(function () {

    Route::post('/register', 'store')->name('register'); /* Création d'un utilisateur : controller */

    Route::get('/settings', 'settings')->middleware('auth')->name('usersettings'); /* Modifier utilisateur : vue */

    Route::post('/processsettings', 'processsettings')->middleware('auth')->name('processsettings'); /* Modifier utilisateur : controller */

    Route::get('/deleteaccount/{userid?}', 'deleteaccount')->middleware('auth')->name('deleteaccount'); /* Déconnexion d'un utilisateur : controller */
   
});

/* Route message */

Route::controller(MessagesController::class)->group(function () {

    Route::get('/messagerie', 'index')->middleware('auth')->name('indexmessagerie'); /* Accueuil messagerie*/

    Route::get('/messagerie/activeconv', 'activeconv')->middleware('auth')->name('activeconv'); /* Afficher mes conversations masquées*/

    Route::get('/messagerie/hideconv', 'hideconv')->middleware('auth')->name('hideconv'); /* Afficher mes conversations masquées*/

    Route::get('/messagerie/read/{idconv}', 'read')->middleware('auth')->whereNumber('idconv')->name('readconv'); /* Afficher une conversation */

    Route::post('/messagerie/new', 'store')->middleware('auth')->name('newmessage'); /* Crée un message : controller */

    Route::get('/messagerie/desaconv/{idconv}', 'update')->middleware('auth')->whereNumber('idconv')->name('desaconv'); /* Désactiver/Activer une conversation */
     
});

/*Route recommends */

Route::post('/recommends', [RecommendsController::class, 'store'])->name('newrecommends'); // [Nomducontrolleur::class ,'methode']

