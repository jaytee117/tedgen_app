<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Site;

class Customer extends Model
{
    protected $fillable = ['customer_name', 'company_number', 'vat_number', 'address_1', 'address_2', 'address_3', 'city', 'region', 'postcode', 'country', 'telephone_1', 'telephone_2'];

    public function site()
    {
        return $this->hasMany(Site::class, 'account_id', 'id');
    }
}
