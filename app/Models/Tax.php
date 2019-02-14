<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\database\Eloquent\SoftDeletes;

class Tax extends Model
{
     use SoftDeletes;
     protected $table    = 'tax';
     protected $dates    = ['deleted_at'];
     protected $fillable = ['tax_id','tax_name','tax_value','tax_type','created_by'];

     public function user()
     {
          return $this->belongsTo('App\User','created_by','id')->select(array('id','name'));
     }
}
