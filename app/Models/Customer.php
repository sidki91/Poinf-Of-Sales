<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Softdeletes;

class Customer extends Model
{
    use Softdeletes;
    protected $table    = 'customer';
    protected $dates    = ['deleted_at'];
    protected $fillable =
    [
      'customer_id',
      'cust_class_id',
      'customer_name',
      'place_of_birth',
      'religion',
      'gender',
      'date_of_birth',
      'date_join',
      'email',
      'phone',
      'address',
      'city',
      'postal_code',
      'created_by',
      'updated_by'
    ];

    public function custclass()
    {
        return $this->belongsto('App\Models\CustClass','cust_class_id','customer_class_id')->select(array('customer_class_id','customer_class'));
    }

    public function user()
    {
        return $this->belongsto('App\User','created_by','id')->select(array('id','name'));
    }


}
