<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\database\Eloquent\softDeletes;

class VendorClass extends Model
{
    use softDeletes;
    protected $table    = 'vendor_class';
    protected $dates    = ['deleted_at'];
    protected $fillable = ['vendor_class_id','vendor_class','created_by','updated_by'];

    public function user()
    {
        return $this->belongsto('App\User','created_by','id')->select(array('id','name'));
    }
}
