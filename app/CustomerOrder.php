<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerOrder extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'travel_month',
        'travel_date',
        'travel_end_date',
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
