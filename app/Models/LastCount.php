<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LastCount extends Model
{
    protected $fillable = [
        'site_id', 
        'type',
        'last_reading', 
    ];
}
