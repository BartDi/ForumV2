<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Post extends Model
{
    use HasFactory, Sortable;

    protected $fillable = [
        'title',
        'description',
        'userId',
        'likes',
        'attachment'
    ];

    public $sortable = [
        'id', 
        'title', 
        'description',
        'likes', 
        'created_at'
    ];
}
