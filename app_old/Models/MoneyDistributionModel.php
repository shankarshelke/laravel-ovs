<?php

namespace App\Models;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class MoneyDistributionModel extends Model
{
    protected $table = "money";
    protected $fillable = [
                            'subadmin_id',
                            'amount',
                            'city_id',
                            'village_id',
                            'd_date',
                            ];

    public function get_admin_details()
    {
        return $this->belongsTo('App\Models\WebAdmin','subadmin_id','id');
    }
    public function get_village_details()
    {
        return $this->belongsTo('App\Models\VillageModel','village_id','id');
    }
    public function get_city_details()
    {
        return $this->belongsTo('App\Models\CityModel','city_id','id');
    }
  public function get_user_details()
    {
        return $this->belongsTo('App\Models\UsersModel','user_id','id');
    }
}
