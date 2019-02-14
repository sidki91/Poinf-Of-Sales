<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TermsPayment extends Model
{
     use SoftDeletes;
     protected $dates    = ['deleted_at'];
     protected $table    = 'terms_payment';
     protected $fillable = ['terms_id','terms_description','created_by','updated_by'];

     public function user()
     {
          return $this->belongsto('App\User','created_by','id')->select(array('id','name'));
     }
}
