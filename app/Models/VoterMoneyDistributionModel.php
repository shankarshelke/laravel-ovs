<?php

namespace App\Models;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Tymon\JWTAuth\Contracts\JWTSubject;

class VoterMoneyDistributionModel extends Model  implements AuthenticatableContract,
                                          AuthorizableContract,
                                          CanResetPasswordContract,
                                          JWTSubject

{
    use Authenticatable, Authorizable, CanResetPassword;
    
     /**
    * @return mixed
    */
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
// {
    protected $table = "voter_money";
    protected $fillable = [ 
                            'subadmin_id',
                            'user_id',
                            'amount',
                            'd_date',
                            'role_status',
                            'village_id',
                            //'city_id',
                            ];

    public function get_admin_details()
    {
        return $this->belongsTo('App\Models\WebAdmin','subadmin_id','id');
    }
    public function get_village_details()
    {
        return $this->belongsTo('App\Models\VillageModel','village_id','id');
    }
    public function get_user_details()
    {
        return $this->belongsTo('App\Models\UsersModel','user_id','id');
    }
     public function get_country() 
    {
       return $this->hasOne('App\Models\CountryModel', 'id', 'country_id');
    }
    public function get_distribution_amount()
    {
        return $this->hasMany('App\Models\MoneyDistributionModel','subadmin_id','subadmin_id');
    }
}