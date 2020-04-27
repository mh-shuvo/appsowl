<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class smslog extends Model
{
    protected $table = 'sms_log';
    protected $primaryKey = 'sms_log_id';
    
    protected $fillable = [
    	'messageid','number','body','send_to','sender'
    ];

    public function sended_to()
    {
        return $this->hasOne('App\Model\userDetails', 'user_id', 'send_to');
    }

    public function send_by()
    {
        return $this->hasOne('App\Model\userDetails', 'user_id', 'sender');
    }
}
