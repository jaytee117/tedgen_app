<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credentials extends Model
{
    protected $fillable = [
        'provider', 
        'access_token', 
        'expires'
    ];

    public static $_provider = [
        0 => 'NONE',
        1 => '2G',
    ];
}
