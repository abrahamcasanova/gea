<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $table = 'receipts';

    protected $fillable = [
      'serie',
      'initial_validity',
      'final_validity',
      'net_premium',
      'policy_right',
      'policy_surcharge',
      'vat',
      'total_premium',
      'payment',
      'payment_date',
      'note',
      'status',
      'policy_id',
    ];

    public function policy()
    {
        return $this->hasOne(Policy::class,'id','policy_id')->with('user');
    }

}
