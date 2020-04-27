<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class voucher extends Model
{
    protected $table = 'as_voucher';
    protected $primaryKey = 'voucher_id';

    public function user()
    {
        return $this->hasone('App\Model\user', 'user_id', 'user_id');
    }

    public function generate()
    {
        return $this->hasone('App\Model\userDetails', 'user_id', 'generated_by');
    }
}
