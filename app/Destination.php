<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $fillable = [
      'name',
      'status',
    ];

    public function getNameAttribute($value) {
        return strtoupper($value);
    }

    public function setNameAttribute($value) {
        $this->attributes['name'] = mb_convert_encoding(mb_strtoupper($value), 'UTF-8', 'UTF-8');
    }

     public function scopeActive($query)
    {
        return $query->where('status',1);
    }
}
