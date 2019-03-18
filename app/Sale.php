<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
      'price',
      'date_payment_limit',
      'date_payment_supplier',
      'date_advance',
      'date_reminder',
      'schedule',
      'amount_receivable',
      'simple_room',
      'double_room',
      'triple_room',
      'quadruple_room',
      'rate_price',
      'confirmation',
      'product_id',
      'supplier_id',
      'user_id',
      'status',
    ];

    public function product()
    {
        return $this->hasOne(Product::class,'id','product_id')->with('product_types');
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class,'id','supplier_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }
}
