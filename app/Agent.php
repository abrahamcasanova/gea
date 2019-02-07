<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Agent extends Model
{

    use LogsActivity;

    protected $fillable = [
      'first_name',
      'last_name',
      'email',
      'clabe',
      'status',
      'office_id',
      'user_id',
    ];

    protected static $logAttributes = [
        'first_name',
        'last_name',
        'email',
        'clabe',
        'office_id',
        'status',
        'user_id'
    ];

    public function getFirstNameAttribute($value) {
        return ucwords(strtolower($value));
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucwords(strtolower($value));
    }

    public function getLastNameAttribute($value) {
        return ucwords(strtolower($value));
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucwords(strtolower($value));
    }

    public function getEmailAttribute($value) {
        return ucfirst(strtolower($value));
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = ucfirst(strtolower($value));
    }

    public function getClabeAttribute($value) {
        return strtoupper($value);
    }

    public function setClabeAttribute($value)
    {
        $this->attributes['clabe'] = strtoupper($value);
    }

    public function office()
    {
        return $this->hasOne(Office::class,'id','office_id');
    }

    public function getDescriptionForEvent(string $eventName): string
    {
        if($eventName == 'updated'){
            return "Agente actualizado";
        }else if($eventName == 'created'){
            return "Agente creado";
        }else{
            return "Agente eliminado";
        }

    }
}
