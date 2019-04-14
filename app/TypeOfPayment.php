<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeOfPayment extends Model
{
    protected $table = 'type_of_payments';

    protected $fillable = [
      'name',
      'with_authorization',
      'with_percentage'
    ];

    public function getNameAttribute($value) {
        return ucfirst(strtolower($value));
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst(strtolower($value));
    }

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }
}
