<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostsDocuments extends Model
{
    use HasFactory;

    protected $table = 'posts_documents';
    protected $fillable = [
        'name',
        'email',
        'national_id',
        'media',
    ];
}
