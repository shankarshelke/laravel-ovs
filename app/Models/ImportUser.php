<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class ImportUser extends Model 
{
	
    protected $table = "users_import";
    protected $fillable = [

    	                  // 'family_id',
                       //   'first_name',
                       //   'last_name'

    	                     'voter_id',
                            'family_id',
                            'father_full_name',
                            'first_name',
                            'middle_name',
                            'last_name',
                            'address',
                            'gender',
                            'admin_id',
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
    
  }