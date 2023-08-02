<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostClassification extends Model
{
    use HasFactory;

    protected $fillable = ['post_type'];

    public function posts(){
        return $this->hasMany(post::class);
    }
}
