<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Priveleges extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'user_group';
    protected $fillable = ['gname','role_view','role_create','role_alter','role_drop'];
}
