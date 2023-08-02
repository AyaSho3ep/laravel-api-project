<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddingPostRequests extends Model
{
    use HasFactory;

    protected $table = 'adding_post_requests';
    protected $fillable = [
        'name',
        'email',
        'url',
        'description',
        'media',
    ];

}
