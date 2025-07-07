<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Site;
use App\Models\DataLine;
use App\Models\MeterReading;

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
    
    public function datalines()
    {
        return $this->hasMany(DataLine::class, 'installation_id', 'id');
    }

    public function readings()
    {
        return $this->hasMany(MeterReading::class, 'installation_id', 'id');
    }

    public static $_machine_type = [
        0 => 'CHP',
        1 => 'GENSET',
    ];

    public static $_machine_status = [
        0 => 'Running',
        1 => 'No Gas Recorded',
        2 => 'Low Gas Consumption',
        3 => 'Fault State',
        4 => 'Offline'
    ];

    public static $_logger_type = [
        0 => 'None',
        1 => 'X420',
        2 => 'Crucible Meter Logger 100',
        3 => 'FTP',
        4 => '2G API'
    ];



}
