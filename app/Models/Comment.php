<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'parentId',
        'userId',
        'description',
        'likes',
        'attachment',
        'toPost'
    ];
}
