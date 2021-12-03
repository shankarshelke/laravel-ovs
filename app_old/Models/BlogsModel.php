<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class BlogsModel extends Model 
{
	
    protected $table = "blogs";
    protected $fillable = [
    	                   'title',
    	                   'description',
                           'short_description',
    	                   'image',
    	                   'status'
    ];
}
	