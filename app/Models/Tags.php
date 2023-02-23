<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tags extends Model
{
    use HasFactory;

    protected $primaryKey = 'nametags';

    protected $keyType = 'string';

    public $incrementing = false;

        /**
     * Get the comments for the blog post.
     */
    public function article()
    {
        return $this->hasMany(Articles::class, 'tag');
    }

}
