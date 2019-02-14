<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;
    protected $table    = 'vendor';
    protected $dates    = ['deleted_at'];
    protected $fillable =
    [
        'vendor_id',
        'vendor_class_id',
        'vendor_name',
        'bussiness_name',
        'attention',
        'email',
        'web',
        'phone',
        'address',
        'city',
        'postal_code',
        'payment_method',
        'terms_payment',
        'curry_id',
        'tax_reg_id',
        'tax_type',
        'created_by',
        'updated_by'
    ];


    public function user()
    {
        return $this->belongsto('App\User','created_by','id')->select(array('id','name'));
    }

    public function classVendor()
    {
        return $this->belongsto('App\Models\VendorClass','vendor_class_id','vendor_class_id')->select(array('vendor_class_id','vendor_class'));
    }
}
