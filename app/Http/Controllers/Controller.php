<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

    class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

        /**
    * Parse le contenu passé en paramètre : URL, code ou emoji
    *
    * @param [type] $article
    * @return void
    */
        public function parsing_content($article)
    {
    // Url
            $article = preg_replace('~\{Url}([^{]*)\{/Url}~i', '<a href="$1" class="link_article" target="_blank">$1</a>', $article);
        
    // code HTML,Javascript,PHP ou Console/Bash

        $article = preg_replace('~\{Code}([^{]*)\{/Code}~i', '<pre><code>$1</code></pre>', $article);

    // emoji
        
        $article =  preg_replace('/:([^\s]+):/', '<img src="/larablog/public/storage/img/emoji/$1.png" alt="$1"/>', $article); // emoji

    // paragraphe

        $article = nl2br($article); 

        return($article);
    }
        /**
         * On compte le nombre d'utilisateur 'admin' 
         *
         * @return void
         */
        public function CheckNbAdminAccount()
    {
        $nbadminaccount = User::where('role', 'admin')->count();

        return $nbadminaccount;
    }
}
