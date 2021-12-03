<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class MeetingsModel extends Model 
{
	
    protected $table = "meetings";
    protected $fillable = [

      	                   'meeting_name',
      	                   'meeting_subject',
                           'meeting_address',
                           'meeting_date',
                           'meeting_time',
                           'status'

    ];
}