<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class CityModel extends Model 
{
	
    protected $table = "cities";
    protected $fillable = [
    	                   'id',
    	                   'district_id',
    	                   'city_name'
    ];
}
	