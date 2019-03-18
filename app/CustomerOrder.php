<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerOrder extends Model
{
    protected $fillable = [
        'travel_month',
        'travel_date',
        'phone',
        'cellphone',
        'call_time',
        'with_us',
        'travel_destination',
        'number_adults',
        'number_childs',
        'services',
        'comments',
        'customer_id',
        'status'
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class,'id','customer_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

}
