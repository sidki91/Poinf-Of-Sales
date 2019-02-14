<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;
    protected $table    = 'payment_method';
    protected $dates    = ['deleted_at'];
    protected $fillable = ['payment_id','payment_method','created_by','updated_by'];


    public function user()
    {
        return $this->belongsTo('App\User','created_by','id')->select(array('id','name'));

    }
}
