<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class termsandcondition extends Model
{
    protected $table = 'as_terms_and_condition';
    protected $primaryKey = 't_c_id';

     public function userDetails()
    {
        return $this->hasone('App\Model\userDetails', 'user_id', 'added_by');
    }
}
