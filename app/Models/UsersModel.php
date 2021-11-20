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

class UsersModel extends Model implements AuthenticatableContract,
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

/*class UsersModel extends Model
{*/
    protected $table = "users";
    protected $fillable = [
                            //'aadhar_id',
                            'voter_id',
                            'family_id',
                            'father_full_name',
                            'first_name',
                            'last_name',
                            'address',
                            'gender',
                            'admin_id',
                            'is_invalid_contact',
                            //'face_color',
                            'voting_surety',
                            'date_of_birth',
                            'house_no',
                            'street',
                            'village',
                            'city',
                            'district',
                            'pincode',
                            'state',
                            'religion',
                            'caste',
                            'address',
                            'longitude',
                            'latitude',
                            'ward',
                            'booth',
                            'list',
                            'occupation',
                            'country_id',
                            'mobile_number',
                            'email',
                            'password',
                            'admin_id',
                            'is_set_password',
                            'set_password_link_expiry',
                            'remember_token',
                            'profile_image',
                            'is_email_verified',
                            'is_verified',
                            'verification_token',
                            'status',
                            'recieved_count',
                            'last_logged_at'

    ];
    
    public function get_district()
    {
        return $this->hasOne('App\Models\DistrictModel','id','district');
    }

    public function get_state_details()
    {
        return $this->belongsTo('App\Models\StatesModel','state','st_id');
    }

    public function get_district_details()
    {
        return $this->belongsTo('App\Models\DistrictModel','district','id');
    }

    public function get_cities_details()
    {
        return $this->belongsTo('App\Models\CityModel','city','id');
    }

    public function get_village_details()
    {
        return $this->belongsTo('App\Models\VillageModel','village','id');
    }
    public function get_religion_details()
    {
        return $this->belongsTo('App\Models\ReligionModel','religion','id');
    }

    public function get_caste_details()
    {
        return $this->belongsTo('App\Models\CasteModel','caste','id');
    }
    public function get_ward_details()
    {
        return $this->belongsTo('App\Models\WardsModel','ward','id');
    }
    public function get_booth_details()
    {
        return $this->belongsTo('App\Models\BoothModel','booth','id');
    }

     public function get_list_details()
    {
        return $this->belongsTo('App\Models\ListModel','list','id');
    }

     public function get_occupation_details()
    {
        return $this->belongsTo('App\Models\OccupationModel','occupation','id');
    }
}
