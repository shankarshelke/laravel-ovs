<?php

namespace App\Models;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class FinanceTeamModel extends Model
{
    protected $table = "finance_team";
    protected $fillable = [
                            'subadmin_id',
                            'country_id',
                            'state_id',
                            'district_id',
                            'city_id',
                            'village_id',
                            'ward',
                            'status',
                            ];
    

    public function get_district_details()
    {
        return $this->belongsTo('App\Models\DistrictModel','district_id','id');
    }

    public function get_cities_details()
    {
        return $this->belongsTo('App\Models\CityModel','city_id','id');
    }

    public function get_village_details()
    {
        return $this->belongsTo('App\Models\VillageModel','village_id','id');
    }
     public function get_admin_details()
    {
        return $this->belongsTo('App\Models\WebAdmin','subadmin_id','id');
    }
    public function get_ward_details()
    {
        return $this->belongsTo('App\Models\WardsModel','ward','id');
    }
}
