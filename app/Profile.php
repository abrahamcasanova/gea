<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{

    protected $table = 'profiles';

    protected $fillable = [
      'name',
      'first_name',
      'last_name',
      'user_profile',
      'phone',
      'cellphone',
      'address',
      'location',
      'status',
    ];


    public function getNameAttribute($value) {
        return ucwords(strtolower($value));
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucwords(strtolower($value));
    }

    public function getFirstNameAttribute($value) {
        return ucwords(strtolower($value));
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucwords(strtolower($value));
    }

    public function getLastNameAttribute($value) {
        return ucwords(strtolower($value));
    }

    public function setLocationAttribute($value)
    {
        $this->attributes['location'] = ucfirst(strtolower($value));
    }

    public function getLocationAttribute($value) {
        return ucfirst(strtolower($value));
    }

    public function getFullNameAttribute() {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    public function scopeWhereUserId($query,$id)
    {
        return $query->where('user_id',$id);
    }

}
