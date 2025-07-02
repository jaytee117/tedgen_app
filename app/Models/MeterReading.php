<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeterReading extends Model
{
    protected $fillable = [
        'reading_date',
        'meter_number',
        'hh_data',
        'total',
        'reading_type',
        'meter_reference',
        'unit',
        'contract_id',
        'site_id',
        'op_count',
        'online',
        'online_status',
        'reading_source' 
    ];

    public static $_reading_type = [
        0 => 'Please Select',
        1 => 'MOP',
        2 => 'CHP',
        3 => 'HH'
    ];
    
    public static $_online_status = [
        0 => 'Running',
        1 => 'No Gas Recorded',
        2 => 'Low Gas Consumption'
    ];
}
