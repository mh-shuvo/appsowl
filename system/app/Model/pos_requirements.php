<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class pos_requirements extends Model
{
    protected $table = 'as_pos_requirements';
    protected $primaryKey = 'pos_requirement_id';

    public function userDetails()
    {
        return $this->hasOne('App\Model\userDetails', 'user_id', 'user_id');
    }

    public function user()
    {
        return $this->hasOne('App\Model\user', 'user_id', 'user_id');
    }
}
