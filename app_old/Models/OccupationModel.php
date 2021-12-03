<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class OccupationModel extends Model 
{
	
    protected $table = "occupation";
    protected $fillable = [
    	                   'occupation_name'
    ];

    
}
	