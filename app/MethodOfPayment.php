<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MethodOfPayment extends Model
{
    protected $table = 'method_of_payments';

    protected $fillable = [
      'name',
    ];

    public function getNameAttribute($value) {
        return ucfirst(strtolower($value));
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst(strtolower($value));
    }
}
