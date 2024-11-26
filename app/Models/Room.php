<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'board',
        'host_move'
    ];

    protected $casts = [
        'board' => 'array',
    ];
}
