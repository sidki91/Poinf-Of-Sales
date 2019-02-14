<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;
    protected $fillable = ['wh_code','wh_description','created_by'];
    protected $dates    = ['deleted_at'];
    protected $table ='warehouse';

    public function user()
    {
        return $this->belongsTo('App\User','created_by','id')->select(array('id','name'));
    }

  

}
