<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'type_of_currency';

    protected $fillable = [
      'name',
    ];

    public function getNameAttribute($value) {
        return ucfirst(strtolower($value));
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = mb_convert_encoding(ucfirst(strtolower(utf8_decode($value))), 'UTF-8', 'UTF-8');
    }
}
