<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeatherReading extends Model
{
    protected $fillable = ['site_id', 'reading_date', 'temp', 'pressure', 'humidity', 'wind_speed', 'cloud', 'sunrise', 'sunset', 'icon'];

}
