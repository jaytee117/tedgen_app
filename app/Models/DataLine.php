<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Installation;

class DataLine extends Model
{
    protected $fillable = [
        'installation_id', 
        'data_line_type', 
        'line_reference',
        'x420_line_assignment', 
        'xero_account_code', 
    ];

    public function installation(){
        return $this->belongsTo(Installation::class, 'installation_id', 'id');
    }

    public static $_data_line_type = [
        1 => 'Thermal - Out',
        2 => 'Electric - Out',
        3 => 'Gas - In'
    ];

    public static $_x420_line_assignment = [
        1 => 'Count 1',
        3 => 'Count 2',
        5 => 'Count 3'
    ];
}
