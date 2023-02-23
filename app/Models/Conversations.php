<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversations extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_conv';

    protected $fillable = ['user_one', 'user_two', 'is_visible_user_one', 'is_visible_user_two'];

            /**
     * Get the comments for the blog post.
     */
    public function message()
    {
        return $this->hasMany(Messages::class, 'conversation');
    }

}