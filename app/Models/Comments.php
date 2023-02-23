<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_comment';

    public function user()
    {
        return $this->belongsTo(User::class,'author_comment','name');
    }

}
