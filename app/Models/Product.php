<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $table = 'product';
    protected $datas = ['deleted_at'];
    protected $fillable =
    [
      'product_id',
      'barcode_id',
      'product_name',
      'product_cat_id',
      'uom_id',
      'curry_id',
      'payment_id',
      'percentage',
      'percentage_value',
      'purchase_price',
      'sales_price',
      'disc_percentage',
      'disc_value',
      'tax_percentage',
      'tax_value',
      'sales_total_price',
      'created_by',
      'updated_by'
    ];

    public function user()
    {
        return $this->belongsTo('App\User','created_by','id')->select(array('id','name'));
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category','product_cat_id','id')->select(array('id','description'));
    }

    public function uom()
    {
        return $this->belongsTo('App\Models\UOM','uom_id','id')->select(array('id','uom_name'));
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency','curry_id','id')->select(array('id','currency_name'));
    }

    public function payment()
    {
        return $this->belongsTo('App\Model\Payment','payment_id','payment_id')->select(array('payment_id','payment_method'));
    }
}
