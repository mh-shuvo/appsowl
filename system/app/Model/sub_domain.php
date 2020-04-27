<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class sub_domain extends Model
{
    protected $table = 'as_sub_domain';
    protected $primaryKey = 'domain_id';

    public function user()
    {
        return $this->belongsTo('App\Model\user', 'user_id', 'user_id');
    }
}
