<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Session;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Tymon\JWTAuth\Contracts\JWTSubject;


use Auth;
//use Session;

class WebAdmin extends Model implements Authenticatable, CanResetPasswordContract, JWTSubject
{
    use AuthenticableTrait, CanResetPassword, Notifiable;
    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
    * @return array
    */
    public function getJWTCustomClaims()
    {
        return [];
     //return ['user' => ['id' => $this->id]];
    }
    
    protected $hidden = array('password', 'remember_token');
    protected $table = 'web_admin';

    protected $fillable = [ 'first_name',
                            'last_name',
                            'email',
                            'password',
                            'contact',
                            'address',
                            'status',
                            'profile_image',
                            'admin_type',
                            'permissions',
                            'role',
                            'role_status',
                            'is_verified',
                            'password_reset_code'
                         ];  

    public function admin_role()
    {
        return $this->belongsTo('App\Models\UserHasRolesModel', 'id', 'web_admin_id');
    }
       public function get_country() 
    {
       return $this->hasOne('App\Models\CountryModel', 'id', 'country_id');
    }
}