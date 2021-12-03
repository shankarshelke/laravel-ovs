<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class BoothModel extends Model 
{
	
    protected $table = "booth";
    protected $fillable = [
                            'district',
                            'city',
                            'village',
    	                    'booth_no',
    	                    'ward_id',
    	                    'booth_name',
                            'booth_address',
                            'status'

    ];
    public function get_ward_details()
    {
        return $this->belongsTo('App\Models\WardsModel','ward_id','id');
    }
}
	