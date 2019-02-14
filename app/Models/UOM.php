<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UOM extends Model
{
    use SoftDeletes;
    protected $table    ='uom';
    protected $dates    = ['deleted_at'];
    protected $fillable = ['uom_name','created_by'];

    public function user()
    {
        return $this->belongsTo('App\User','created_by','id')->select(array('id', 'name'));
    }
}
