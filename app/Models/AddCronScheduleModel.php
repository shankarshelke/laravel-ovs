<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddCronScheduleModel extends Model
{
    protected $table = "add_cron_schedule";
    protected $fillable = [
    	                   'event_date',
    	                   'template_id',
                           'sent_to',
                           'group_id',
    	                   'status',
    ];

     public function get_template_name()
    {
        return $this->belongsTo('App\Models\SmsTemplateModel','template_id','id');
    }

     public function get_group_name()
    {
        return $this->belongsTo('App\Models\GroupModel','group_id','parent_id');
    }


}
