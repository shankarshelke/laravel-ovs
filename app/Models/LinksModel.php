<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class LinksModel extends Model 
{
	
    protected $table = "links";
    protected $fillable = [

    	                   'links',
                         'banners'
    	                  

    ];
    
  }