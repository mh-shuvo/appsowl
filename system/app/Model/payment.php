<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    protected $table = 'as_payment';
    protected $primaryKey = 'payment_id';
    
    public function user()
    {
        return $this->belongsTo('App\Model\user', 'user_id', 'user_id');
    }
}
