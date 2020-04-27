<?php

namespace App\model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class user extends Model
{
    use Notifiable;

    protected $table = 'as_users';

    protected $primaryKey = 'user_id';

    protected $hidden = ['password','sms_verify_key','confirmation_key'];

    public function userDetails()
    {
        return $this->hasOne('App\Model\userDetails', 'user_id', 'user_id');
    }

    public function payment()
    {
        return $this->hasmany('App\Model\payment', 'user_id', 'user_id');
    }

    public function subscribtion()
    {
        return $this->hasmany('App\Model\subscribe', 'user_id', 'user_id');
    }

    public function agent_commission()
    {
        return $this->hasmany('App\Model\agent_commission', 'agent_id','user_id');
    }

    public function agent_payment()
    {
        return $this->hasmany('App\Model\agent_payment', 'agent_id','user_id');
    }
}
