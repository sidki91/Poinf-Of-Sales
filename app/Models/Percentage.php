<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Percentage extends Model
{
    use SoftDeletes;
    protected $table    = 'percentage';
    protected $dates    = ['deleted_at'];
    protected $fillable = ['percentage_id','percentage','created_by','updated_by'];


    public function user()
    {
        return $this->belongsTo('App\User','created_by','id')->select(array('id','name'));
    }
}
