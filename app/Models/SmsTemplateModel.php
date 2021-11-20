<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class SmsTemplateModel extends Model 
{
	
    protected $table = "sms_template";
    protected $fillable = [
    	                 'template_name',
    	                 'template_html',
                         'template_variables',
                         'flag_id',
    ];


}
	