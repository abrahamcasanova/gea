<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{

    protected $fillable = [
      'name',
      'address',
      'status',
      'group_id'
    ];

    public function getNameAttribute($value) {
        return ucfirst(strtolower($value));
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst(strtolower($value));
    }

    protected static $logAttributes = [
        'name',
        'status',
        'group_id'
    ];

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

}
