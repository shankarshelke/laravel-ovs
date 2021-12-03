<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class ReligionModel extends Model 
{
	
    protected $table = "religion";
    protected $fillable = [
    	                   'religion_name'
    ];
}
	