<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuoteDetail extends Model
{

    protected $fillable = [
      'category',
      'price',
      'description',
      'supplier_id',
      'product_id',
      'quote_id',
      'status',
    ];

    public function product()
    {
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class,'id','supplier_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

    public function scopeWhereStatus($query,$id)
    {
        return $query->where('status',$id);
    }

    public function scopeWhereQuoteId($query,$id)
    {
        return $query->where('quote_id',$id);
    }
}
