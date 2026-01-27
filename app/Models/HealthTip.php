<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthTip extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'source',
    ];
}


