<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostEditRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'evaluation',
        'type',
        'content'
    ];

    public function posts(){
        return $this->belongsTo(Post::class, 'post_id');
    }
    
    public function users(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
