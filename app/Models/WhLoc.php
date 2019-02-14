<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WhLoc extends Model
{
    use SoftDeletes;
    protected $table = 'warehouse_location';
    protected $dates = ['deleted_at'];
    protected $fillable = ['wh_loc_id','wh_id','location','created_by'];

    public function user()
    {
        return $this->belongsTo('App\User','created_by','id')->select(array('id','name'));
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse','wh_id','wh_code')->select(array('wh_code','wh_description'));
    }
}
