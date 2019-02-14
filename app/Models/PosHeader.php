<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PosHeader extends Model
{
    use SoftDeletes;
    protected $table    = 'pos_header';
    protected $dates    = ['deleted_at'];
    protected $fillable =
    [
      'trans_id',
      'trans_date',
      'cust_id',
      'cust_name',
      'payment_id',
      'grand_qty',
      'disc',
      'ppn',
      'grand_disc',
      'grand_ppn',
      'grand_sales_price',
      'grand_purchase_price',
      'grand_laba',
      'note',
      'stat_pay',
      'total_bayar',
      'kembali',
      'created_by',
      'updated_by'
    ];

    public function payment()
    {
        return $this->belongsTo('App\Models\Payment','payment_id','payment_id')->select(array('payment_id','payment_method'));
    }
}
