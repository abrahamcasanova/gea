<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

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
      'user_id'
    ];

    public function customerOrder()
    {
        return $this->hasOne(CustomerOrder::class,'id','customer_order_id')->with('customer');
    }

    public function quoteDetails()
    {
        return $this->hasMany(QuoteDetail::class,'quote_id','id')->with('product');
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
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
