<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_message';

    public function conversation()
    {
        return $this->belongsTo(Conversations::class, 'conversation');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'author_message','name');
    }

}
