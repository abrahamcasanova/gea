<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeneralConfig extends Model
{
    protected $table = 'general_config';

    protected $fillable = [
      'maximum_expenses',
    ];
}
