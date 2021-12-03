<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class SentSmsModel extends Model 
{
	
    protected $table = "sent_sms";
    protected $fillable = [
    	                 'template_id',
    	                 'user_id',
                         'contact_no',
                         'flag_id',
    ];


}
	