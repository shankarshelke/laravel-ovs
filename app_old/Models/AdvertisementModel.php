<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class AdvertisementModel extends Model 
{
	
    protected $table = "advertisement";
    protected $fillable = [
    	                   'media_name',
    	                   'description',
                           'user_id',
                           'webadmin_id',
                           'site_setting_id',
                           'links_id'

    ];
    
    public function get_team_details()
    {
        return $this->belongsTo('App\Models\WebAdmin','webadmin_id','id');
    }
     public function get_sitesetting_details()
    {
        return $this->belongsTo('App\Models\SiteSettingModel','site_setting_id','id');
    }
  }