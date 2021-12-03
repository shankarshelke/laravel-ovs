<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CronScheduleListModel extends Model
{
    protected $table = "cron_schedule_contact_list";
    protected $fillable = [
    	                   'cron_schedule_id',
    	                   'is_invalid_contact',
    	                   'contact_no',
                           'flag_id',
                           'user_id',
                        //    'group_id',
    	                   // 'status',
    ];
}
