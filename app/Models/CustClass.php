<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Softdeletes;

class CustClass extends Model
{
    use Softdeletes;
    protected $table    = 'customer_class';
    protected $dates    = ['deleted_at'];
    protected $fillable = ['customer_class_id','customer_class','created_by','updated_by'];

    public function user()
    {
        return $this->belongsto('App\User','created_by','id')->select(array('id','name'));
    }
}
