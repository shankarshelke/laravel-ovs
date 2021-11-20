<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class GroupModel extends Model 
{
	
    protected $table = "contact_group";
    protected $fillable = [
    	                    'group_name',
                            'contact_no',
                            'contact_person_name',
                            'parent_id'
    	               
    ];
    public function group_name()
    {
        return $this->hasOne('App\Models\GroupModel','parent_id','id');
    }    
  }