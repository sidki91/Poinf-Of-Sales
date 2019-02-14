<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Category extends Model
{
    use SoftDeletes;
    protected $table = 'product_category';
    protected $dates = ['deleted_at'];
    protected $fillable = ['description','created_by','status'];

    public function user()
    {
        return $this->belongsTo('App\User','created_by','id')->select(array('id', 'name'));
    }

}
