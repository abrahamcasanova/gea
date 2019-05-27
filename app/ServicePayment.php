<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServicePayment extends Model
{
  protected $table = 'services_payments';

    protected $fillable = [
        'amount',
        'date',
        'note',
        'service_id',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

    public function service()
    {
        return $this->hasOne(Service::class,'id','service_id');
    }
}
