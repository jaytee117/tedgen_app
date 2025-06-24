<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Site;

class Installation extends Model
{
    protected $fillable = [
        'account_id', 
        'site_id', 
        'asset_id',
        'elec_day_rate', 
        'elec_night_rate', 
        'elec_ccl_rate',
        'gas_rate', 
        'gas_ccl_rate', 
        'gas_ccl_discount',
        'elec_ccl_discount',
        'boiler_efficiency', 
        'tedgen_discount', 
        'tedgen_elec_day',
        'tedgen_elec_night',
        'tedgen_gas_heating',
        'calorific_value',
        'conversion_factor',
        'elec_carbon_rate',
        'gas_carbon_rate',
        'xero_id',
        'machine_type',
        'machine_status',
        'machine_model',
        'team_id',
        'logger_type',
        'ip_address'
    ];

    public function site(){
        return $this->belongsTo(Site::class, 'site_id', 'id');
    }

}
