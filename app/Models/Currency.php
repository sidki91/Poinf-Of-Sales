<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use SoftDeletes;
    protected $table ='currency';
    protected $dates = ['deleted_at'];
    protected $fillable = ['currency_name','created_by'];

    public function user()
    {
        return $this->belongsTo('App\User','created_by','id')->select(array('id','name'));
    }

}
