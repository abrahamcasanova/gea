<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
      'clabe',
      'name',
      'category',
      'type',
      'product_type_id',
      'url_image',
      'description',
      'extra_comments',
      'location',
      'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

    public function product_types()
    {
        return $this->hasOne(ProductType::class,'id','product_type_id');
    }


}
