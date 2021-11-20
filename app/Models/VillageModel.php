<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class VillageModel extends Model 
{
	
    protected $table = "villages";
    protected $fillable = [
    	                   // 'district_id',
    	                   'city_id',
    	                   'village_name'
    ];
}
	