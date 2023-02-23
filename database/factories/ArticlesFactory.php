<?php

namespace Database\Factories;

use App\Models\Tags;
use App\Models\User;
use App\Models\Articles;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Articles>
 */
class ArticlesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

      return [
              'titre_article' => fake()->word(),
              'corps_article' => fake()->text(),
              'author' => $this->faker->randomElement(User::all())['name']
              ];

    }

}
