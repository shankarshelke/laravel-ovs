<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class StatesModel extends Model 
{
	
    protected $table = "state_table";
    protected $fillable = [
    	                   'st_id',
    	                   'st_name'
    ];
}
	