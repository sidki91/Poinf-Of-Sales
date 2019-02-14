<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Softdeletes;

class Counter extends Model
{
    use Softdeletes;
    protected $table    = 'counter';
    protected $dates    = ['deleted_at'];
    protected $fillable = ['counter_id','counter_name','created_by','updated_by'];


    public function user()
    {
        return $this->belongsto('App\User','created_by','id')->select(array('id','name'));
    }

}
