<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class ListModel extends Model 
{
	
    protected $table = "list";
    protected $fillable = [
    	                   'booth_id',
    	                   'list_no',
    	                   'list_name'
    ];

     public function get_booth_details()
    {
        return $this->belongsTo('App\Models\BoothModel','booth_id','id');
    }
}
	