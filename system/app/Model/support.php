<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class support extends Model
{
    protected $table = 'as_ticket';
    protected $primaryKey = 'ticket_no';

    public function user()
    {
        return $this->belongsTo('App\Model\user', 'user_id', 'user_id');
    }
}
