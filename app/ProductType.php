<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    protected $fillable = [
      'clabe',
      'name',
      'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }
}
