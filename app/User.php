<?php

namespace App;

use App\Models\SocialAccount;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','username','access_id','avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // public function category()
    // {
    //     return $this->hasMany(Category::class, 'created_by', 'id');
    // }

    // public function warehouse()
    // {
    //     return $this->hasMany(WhLoc::class, 'created_by', 'id');
    // }

    public function socialAccount()
    {
       return $this->hasMany(SocialAccount::class);
    }







}
