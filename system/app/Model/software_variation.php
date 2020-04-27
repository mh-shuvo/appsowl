<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class software_variation extends Model
{
    protected $table = 'as_software_variation';
    protected $primaryKey = 'software_variation_id';

    public function software()
    {
        return $this->belongsTo('App\Model\software', 'software_id');
    }
}
