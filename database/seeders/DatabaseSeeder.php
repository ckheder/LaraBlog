<?php

namespace Database\Seeders;


use App\Models\Tags;
use App\Models\User;
use App\Models\Articles;
use App\Models\Comments;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->call([
            //TagsSeeder::class,
            //ArticlesSeeder::class
        //]);

        User::create([
            'name' => 'christophe_kheder',
            'email' => 'christophekheder@gmail.com',
            'email_verified_at' => null,
            'password' => '$2y$10$Md74w7FPnEhROAtDtv5d1e9levmOYH1eX2aIfLhzCyd.rnMj8NTKu',
            'role' => 'admin',
            'remember_token' => '6GIAdGeOlzXWCMU3QMiTtrxwza7gdF1r8Afhh7rsWPjPMhSFJ1VtBbwQtfbM',
        ]);

        User::factory()
            ->count(5)
            ->create();

        // création de 5 tags avec pour chacun d'eux 5 articles associés et pour chaque article 10 commentaires

            Tags::factory(5)
                            ->has(Articles::factory()
                                ->has(Comments::factory()->count(10),'comment')
                            ->count(20),'article')
            ->create();


    }
}
