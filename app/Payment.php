<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
      'price',
      'type_of_payment',
      'percentage',
      'exchange_rate',
      'real_price',
      'authorization_number	',
      'real_price_without_percentage',
      'deposit_date',
      'payment_date',
      'comments',
      'confirm',
      'break',
      'note',
      'date_received',
      'path',
      'status',
      'customer_id',
      'sale_id',
      'user_id',
    ];

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class,'id','customer_id');
    }

    public function sale()
    {
        return $this->hasOne(Sale::class,'id','sale_id')
          ->with('product','saleDetail','quote','quoteDetail')->withTrashed();
    }
}
