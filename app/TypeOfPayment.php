<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeOfPayment extends Model
{
    protected $table = 'type_of_payments';

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
