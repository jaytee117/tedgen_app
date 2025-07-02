<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LastCount extends Model
{
    protected $fillable = [
        'installation_id', 
        'type',
        'last_reading', 
    ];
}
