<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use SoftDeletes;
    protected $table    = 'division';
    protected $dates    = ['deleted_at'];
    protected $fillable = ['division_code','division_name','created_by'];


    public function user()
    {
        return $this->belongsTo('App\User','created_by','id')->select(array('id','name'));
    }
}
