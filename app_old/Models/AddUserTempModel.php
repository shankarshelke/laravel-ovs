<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class AddUserTempModel extends Model 
{
	
    protected $table = "add_user_temp";
    protected $fillable = [
    	                     'first_name',
    	                     'voter_id',
                           'last_name',
                           'father_full_name',
    	                     'address',
    	                     'gender',
                           'occupation',
                           'date_of_birth',
                           'contact',
                           'email',
                           'status',
                           'temp_id'
    ];

     public function get_occupation_details()
    {
        return $this->belongsTo('App\Models\OccupationModel','occupation','id');
    }

}
	