<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class subscribe extends Model
{
    protected $table = 'as_subscribe';
    protected $primaryKey = 'subscribe_id';

    public function agentDetails()
    {
        return $this->hasOne('App\Model\user', 'user_id', 'agent_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Model\user', 'user_id', 'user_id');
    }

    public function softwareDetails()
    {
        return $this->hasOne('App\Model\software', 'software_id');
    }

    public function softwareVariationDetails()
    {
        return $this->hasOne('App\Model\software_variation', 'software_variation_id');
    }

    public function invoices()
    {
        return $this->belongsTo('App\Model\invoices', 'subscribe_id', 'subscribe_id');
    }

    public function software()
    {
        return $this->hasOne('App\Model\software', 'software_id', 'software_id');
    }

    public function plugin()
    {
        return $this->belongsTo('App\Model\plugin', 'plugins_id', 'plugin_id');
    }
}
