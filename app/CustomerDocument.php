<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerDocument extends Model
{
    //
    protected $fillable = [
        'name',
        'url',
        'customer_id',
    ];

    public function scopeWhereCustomerId($query,$id)
    {
        return $query->where('customer_id',$id);
    }
}
