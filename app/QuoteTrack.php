<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuoteTrack extends Model
{
    protected $table = 'quote_tracks';

    protected $fillable = [
      'quote_id',
      'user_id',
      'track_status',
      'contact_date',
      'comments',
      'status',
    ];

    public function quote()
    {
        return $this->hasOne(Quote::class,'id','quote_id')->with('customerOrder');
    }

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function scopeWhereQuoteId($query,$id)
    {
        return $query->where('quote_id',$id);
    }
}
