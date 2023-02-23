<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommends extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_recommends';

    public $timestamps = false;

    protected $fillable = ['user_recommends', 'article_recommends'];

}
