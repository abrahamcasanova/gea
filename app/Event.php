<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
      'date',
      'details',
      'firebase_id',
      'open',
      'title',
      'type',
      'schedule',
      'date_advance',
      'date_payment_limit',
      'date_payment_supplier',
      'quote_id',
      'sale_id',
      'status',
    ];

    public function sale()
    {
        return $this->hasOne(Sale::class,'id','sale_id')->with('product','saleDetail','quote','quoteDetail');
    }
}
