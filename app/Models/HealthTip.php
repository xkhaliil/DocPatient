<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthTip extends Model
{
    protected $fillable = [
        'title',
        'description',
        'content',
        'category',
        'source',
        'author',
        'published_at',
        'read_more_url',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}


