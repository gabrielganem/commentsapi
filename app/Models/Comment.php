<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'message',
        'parent_id',
    ];

    public function subComments()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}