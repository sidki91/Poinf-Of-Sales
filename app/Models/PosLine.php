<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PosLine extends Model
{
    use SoftDeletes;
    protected $table = 'pos_line';
    protected $dates = ['deleted_at'];
    protected $fillable =
    [
      'trans_line_id',
      'trans_id',
      'product_id',
      'product_name',
      'qty',
      'uom',
      'currency',
      'sales_price',
      'purchase_price',
      'disc',
      'disc_value',
      'ppn',
      'ppn_value',
      'sub_total_sales',
      'sub_total_purchase',
      'laba_item',
      'created_by'
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id','product_id')->select(array('product_id','barcode_id'));
    }

}
