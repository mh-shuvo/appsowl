<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class invoices extends Model
{
    protected $table = 'as_invoices';
    protected $primaryKey = 'invoice_id';

    public function user()
    {
        return $this->belongsTo('App\Model\user', 'user_id', 'user_id');
    }

    public function agent()
    {
        return $this->belongsTo('App\Model\user', 'agent_id', 'user_id');
    }
}

