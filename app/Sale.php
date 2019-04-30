<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = [
      'price',
      'travel_destination',
      'date_payment_limit',
      'date_payment_supplier',
      'date_advance',
      'date_reminder',
      'schedule',
      'amount_receivable',
      'simple_room',
      'double_room',
      'triple_room',
      'path',
      'note',
      'quadruple_room',
      'rate_price',
      'confirmation',
      'product_id',
      'supplier_id',
      'user_id',
      'quote_id',
      'quote_detail_id',
      'status',
    ];

    protected $dates = ['deleted_at'];

    public function product()
    {
        return $this->hasOne(Product::class,'id','product_id')->with('product_types');
    }

    public function quote()
    {
        return $this->hasOne(Quote::class,'id','quote_id')->with('customerOrder','quoteDetails')->withTrashed();
    }

    public function quoteDetail()
    {
        return $this->hasOne(QuoteDetail::class,'id','quote_detail_id')->with('product');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class,'sale_id','id');
    }

    public function saleDetail()
    {
        return $this->hasMany(ProductDetailSale::class,'sale_id','id')->with('product','quote','supplier');
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
