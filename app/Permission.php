<?php namespace App;

use Zizaco\Entrust\EntrustPermission;
use Illuminate\Database\Eloquent\Model;

class Permission extends EntrustPermission
{
    //
    protected $fillable = [
        'name',
        'display_name',
        'guard_name',
        'module_id',
    ];

}
