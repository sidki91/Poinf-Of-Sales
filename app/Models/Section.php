<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use SoftDeletes;
    protected $table    = 'section';
    protected $dates    = ['deleted_at'];
    protected $fillable =  ['section_code','section_name','created_by','updated_by'];

    public function user()
    {
        return $this->belongsTo('App\User','created_by','id')->select(array('id','name'));
    }
}
