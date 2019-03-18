<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $appends = ['full_name'];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'type_of_person',
        'status',
        'comment',
        'phone',
        'cellphone',
        'birthdate',
        'level',
        'customer_id',
        'rfc',
        'user_id',
        'office_id',
        'group_id',
        'subgroup_id',
    ];

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id')->with('profile');
    }

    public function office()
    {
        return $this->hasOne(Office::class,'id','office_id');
    }

    public function group()
    {
        return $this->hasOne(Group::class,'id','group_id');
    }

    public function subgroup()
    {
        return $this->hasOne(Subgroup::class,'id','subgroup_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status',1);
    }

    public function getFullNameAttribute() {
       return "{$this->first_name} {$this->last_name}";
    }
}
