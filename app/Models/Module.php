<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use SoftDeletes;
    protected $table    = 'module';
    protected $dates    = ['deleted_at'];
    protected $fillable = ['modid','modules','alias','parent','enable','r_create','r_alter','r_drop','parent','created_by'];
}
