<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSettingModel extends Model
{
    protected $table = 'site_settings';

    protected $fillable =   [
                                'site_name',
                                'site_address',
                                'country_code',
                                'country_name',
                                'site_contact_number',
                                'site_status',
                                'meta_title',
                                'meta_desc',
                                'meta_keyword',
                                'site_email_address',
                                'fb_url',
                                'twitter_url',
                                'gmail_url',
                                'linkedin_url',
                                'youtube_url',
                                'instagram_url',
                                'lat',
                                'lon',
                                'charity_points',
                                'carrer_video_link',
                                'bank_name',
                                'branch_name',
                                'swift_code',
                                'account_number',
                                'bank_address',
                                'commission_rate',
                                'referral_code_value_user',
                                'referral_code_value_operator'
                            ];
}
