<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articles extends Model
{
    use HasFactory;
        /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_article';

    protected $fillable = ['titre_article', 'corps_article', 'author', 'tag'];

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'tag' ,'nametags');
    }

    public function comment()
    {
        return $this->hasMany(Comments::class, 'article_comment');
    }

    public function recommends()
    {
        return $this->hasMany(Recommends::class, 'article_recommends');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'author' ,'name');
    }

}
