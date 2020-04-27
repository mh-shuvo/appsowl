<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class notification extends Model
{
    protected $table = 'as_notification';
    protected $primaryKey = 'notification_id';

    protected $fillable = [
    	'title','message','link','status','user_id','notification_type'
    ];
}
