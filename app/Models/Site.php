<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Installation;


class Site extends Model
{
    protected $fillable = [
        'account_id', 
        'site_name', 
        'address_1', 
        'address_2', 
        'city', 
        'region', 
        'postcode', 
        'lat', 
        'lng', 
        'site_telephone', 
        'site_img', 
        'current_temp', 
        'weather_icon'
    ];

    public function account()
    {
        return $this->belongsTo(Customer::class, 'account_id', 'id');
    }

    public function installation()
    {
        return $this->hasMany(Installation::class, 'site_id', 'id');
    }
    
    public function getImageURL()
    {
        if ($this->site_img) {
            return url('storage/' . $this->site_img);
        }
        return "https://placehold.co/600x400";
    }
}
