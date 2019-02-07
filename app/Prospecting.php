<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prospecting extends Model
{
    protected $table = 'prospectings';

    protected $fillable = [
      'first_name',
      'last_name',
      'email',
      'phone',
      'cellphone',
      'branch_id',
      'customer_id',
      'status',
    ];

    public function branches()
    {
        return $this->hasOne(Branch::class,'id','branch_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }
}
