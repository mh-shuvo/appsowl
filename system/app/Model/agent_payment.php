<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class agent_payment extends Model
{
   protected $table = 'as_agent_payment';
   protected $primaryKey = 'agent_payment_id';

   public function agent()
   {
       return $this->belongsTo('App\Model\user', 'agent_id','user_id');
   }
}
