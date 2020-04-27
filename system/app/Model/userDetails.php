<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class userDetails extends Model
{
    protected $table = 'as_user_details';
    
    protected $primaryKey = 'user_id';

    public function user()
    {
        return $this->belongsTo('App\Model\user', 'user_id');
    }
}
