<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';
    protected $fillable = [
        'media',
        'email',
        'phone',
        'linkedin',
        'twitter',
        'facebook',
        'instagram',
        'youtube',
    ];
}
