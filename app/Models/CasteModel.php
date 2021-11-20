<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class CasteModel extends Model 
{
	
    protected $table = "caste_category";
    protected $fillable = [
    	                   'caste_name'
    ];
}