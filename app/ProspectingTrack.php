<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProspectingTrack extends Model
{
    protected $table = 'prospecting_tracks';

    protected $fillable = [
      'prospecting_id',
      'user_assistant_id',
      'status_prospecting',
      'note',
      'status',
    ];

    public function prospecting()
    {
        return $this->hasOne(Prospecting::class,'id','prospecting_id');
    }

    public function assistant()
    {
        return $this->hasOne(User::class,'id','user_assistant_id');
    }

    public function scopeWhereProspectingId($query,$id)
    {
        return $query->where('prospecting_id',$id);
    }
}
