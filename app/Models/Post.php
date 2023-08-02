<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $fillable = [
        'category_id',
        'postClassification_id',
        'user_id',
        'title',
        'content',
        'sources',
        'evaluation',
        'proof',
        'media',
        'views',
        'trending'
    ];

    public function categories(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function PostClassifications(){
        return $this->belongsTo(PostClassification::class, 'PostClassification_id');
    }

    public function users(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }
}
