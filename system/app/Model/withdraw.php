<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class withdraw extends Model
{
    protected $table = 'as_withdrawal';
    protected $primaryKey = 'withdrawal_id';

    public function userDetails()
    {
        return $this->hasOne('App\Model\userDetails', 'user_id', 'user_id');
    }

    public function approve_by()
    {
        return $this->hasone('App\Model\userDetails', 'user_id', 'withdrawal_approve_by');
    }
}
