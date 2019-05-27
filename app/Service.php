<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'date_payment',
        'frequency',
        'note',
        'status',
    ];

    protected $dates = ['deleted_at'];


    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

    public function scopeCurrentPayment($query)
    {
        return $query->with(['payments' => function ($query) {
    		      $query->whereYear('date',  date('Y'))
                  ->whereMonth('date', date('m'));
		    }]);
    }

    public function payments()
    {
        return $this->hasMany(ServicePayment::class,'service_id','id');
    }
}
