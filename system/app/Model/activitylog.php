<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class activitylog extends Model
{
    protected $table = 'activity_log';
    protected $primaryKey = 'log_id';

    protected $fillable = [
    	'subject', 'url', 'method', 'ip', 'agent', 'user_id','note','document'
    ];

    public function userDetails()
    {
        return $this->hasOne('App\Model\userDetails', 'user_id', 'user_id');
    }
}
