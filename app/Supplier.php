<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    //
    protected $fillable = [
        'name',
        'address',
        'commission',
        'email',
        'phone',
        'cellphone',
        'note',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }
}
