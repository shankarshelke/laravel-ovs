<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class WardsModel extends Model 
{
	
    protected $table = "wards";
    protected $fillable = [
    	                   'ward_no',
    	                   'ward_name',
                           'ward_address',
                           'status',
                           'user_id', 
                           'village_id',
                            'city',
                            'district'

    ];
    
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
}
