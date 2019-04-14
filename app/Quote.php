<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = [
      'proposal_date',
      'travel_date',
      'number_adults',
      'number_childs',
      'include',
      'policy',
      'payment',
      'path',
      'currency',
      'customer_order_id',
      'status',
    ];

    public function customerOrder()
    {
        return $this->hasOne(CustomerOrder::class,'id','customer_order_id')->with('customer');
    }

    public function quoteDetails()
    {
        return $this->hasMany(QuoteDetail::class,'quote_id','id')->with('product');
    }

    public function scopeWhereStatus($query,$status)
    {
        return $query->where('status',$status);
    }

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }
}
