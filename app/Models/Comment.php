<?php

namespace App\Models;

use Illuminate\Support\Facades\Redis;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';
    protected $fillable = [
        'comment',
        'user_id',
        'post_id'
    ];

    public function users(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function posts(){
        return $this->belongsTo(Post::class, 'post_id');
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($comment) {
            Redis::publish('comment.added', json_encode($comment));
        });

        static::updated(function ($comment) {
            Redis::publish('comment.updated', json_encode($comment));
        });

        static::deleted(function ($comment) {
            Redis::publish('comment.deleted', json_encode($comment));
        });
    }
}
