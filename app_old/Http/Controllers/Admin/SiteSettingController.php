<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SiteSettingModel;
use App\Models\CountryModel;
use App\Common\Traits\MultiActionTrait;
use Validator;
use Session;

use App\Common\Services\CommonDataService;


class SiteSettingController extends Controller
{
    public function __construct()
    {
    	$this->arr_view_data        = [];
		$this->admin_url_path       = url(config('app.project.admin_panel_slug'));
		$this->admin_panel_slug     = config('app.project.admin_panel_slug');
		$this->module_url_path      = $this->admin_url_path."/site_setting";
		$this->module_view_folder   = "admin.site_setting";
		$this->module_title         = "Site Setting";
		$this->module_icon          = 'fa fa-cog';
        $this->SiteSettingModel     = new SiteSettingModel;
        $this->CountryModel         = new CountryModel;
        $this->ip_address           = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:false;  
        $this->auth                 = auth()->guard('admin'); 
    }

    public function index()
    {
        $arr_site_settings = $arr_country_code = [];
        $obj_site_settings = $this->SiteSettingModel->first();
        if($obj_site_settings) 
        {
            $arr_site_settings = $obj_site_settings->toArray();
        }

        $obj_site_settings_country_code = $this->CountryModel->get();
        if($obj_site_settings_country_code) 
        {
            $arr_site_settings_country_code = $obj_site_settings_country_code->toArray();
        }

        $this->arr_view_data['page_title']           = $this->module_title;
        $this->arr_view_data['parent_module_icon']   = "fa fa-home";
        $this->arr_view_data['parent_module_title']  = "Dashboard";
        $this->arr_view_data['parent_module_url']    = url('/').'/admin/dashboard';
        $this->arr_view_data['module_icon']          = $this->module_icon;
        $this->arr_view_data['module_title']         = $this->module_title;
        $this->arr_view_data['module_url_path']      = $this->module_url_path;
        $this->arr_view_data['admin_panel_slug']     = $this->admin_panel_slug;
        $this->arr_view_data['arr_site_settings']    = $arr_site_settings;  
        $this->arr_view_data['arr_site_settings_country_code']    = $arr_site_settings_country_code;
        return view($this->module_view_folder.'.index',$this->arr_view_data);
    }

    public function update(Request $request)
    {   
        $status_update                    = $status_create = '';
        $arr_rules                        = $arr_data = array();
        $arr_rules['site_name']           = "required";
        $arr_rules['site_address']        = "required"; 
        $arr_rules['country_code']        = "required"; 
        $arr_rules['site_email']          = "required|email"; 
        $arr_rules['site_contact_number'] = "required|min:7|max:16"; 
        $arr_rules['meta_title']          = "required";
        $arr_rules['meta_keyword']        = "required";
        $arr_rules['meta_description']    = "required";
        $arr_rules['commission_rate']     = "required";

        $validator = Validator::make($request->all(),$arr_rules);
        
        if($validator->fails())
        {       
            return redirect()->back()->withErrors($validator)->withInput();  
        }
        $arr_data['site_name']                  = trim($request->input('site_name',''));
        $arr_data['site_email_address']         = trim($request->input('site_email',''));
        $arr_data['site_contact_number']        = trim($request->input('site_contact_number',''));
        $arr_data['site_address']               = $request->input('site_address',1);
        $arr_data['country_code']               = $request->input('country_code','');
        $arr_data['site_status']                = $request->input('site_status','');
        $arr_data['meta_title']                 = $request->input('meta_title','');
        $arr_data['meta_desc']                  = $request->input('meta_description','');
        $arr_data['meta_keyword']               = $request->input('meta_keyword','');
        $arr_data['lat']                        = $request->input('latitude','');
        $arr_data['lon']                        = $request->input('longitude','');
        $arr_data['commission_rate']            = $request->input('commission_rate','');
        
        $obj_data = $this->SiteSettingModel->first();
        if($obj_data)
        {   
            $status_update = $obj_data->where('id',1)->update($arr_data);
        }
        else
        {
            $status_create = $this->SiteSettingModel->create($arr_data);
        }
        if($status_update) 
        {
            Session::flash('success',str_singular($this->module_title).' details updated successfully.');
        }
        elseif($status_create)
        {
            Session::flash('success',str_singular($this->module_title).' details added successfully.');
        }
        else
        {
            Session::flash('error','Problem Occurred, While Updating '.str_singular($this->module_title));
        }
        return redirect()->back();
    }

    public function update_social_links(Request $request)
    {
        $status_update                  = $status_create = '';
        $arr_rules                      = $arr_data = array();

        $arr_rules['facebook_url']      = "required|url";
        $arr_rules['twitter_url']       = "required|url";
        $arr_rules['gmail_url']         = "required|url";
        $arr_rules['youtube_url']       = "required|url";
        
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput()->withTab('tab_social');
        }

        $arr_data['fb_url']             = $request->input('facebook_url','');
        $arr_data['twitter_url']        = $request->input('twitter_url','');
        $arr_data['gmail_url']          = $request->input('gmail_url','');
        $arr_data['youtube_url']        = $request->input('youtube_url','');
        
        $obj_data = $this->SiteSettingModel->first();
        if($obj_data)
        {
            $status_update = $obj_data->update($arr_data);
        }
        else
        {
            $status_create = $this->SiteSettingModel->create($arr_data);
        }


        if($status_update) 
        {
            Session::flash('success', 'Social links updated successfully.');
        }
        elseif($status_create)
        {
            Session::flash('success', 'Social links added successfully.');
        }
        else
        {
            Session::flash('error','Problem Occurred, While Updating Social links');
        }
        return redirect()->back();
    }

    public function update_bank_details(Request $request)
    {
        $status_update                  = $status_create = '';
        $arr_rules                      = $arr_data = array();

        $arr_rules['branch_name']       = "required";
        $arr_rules['bank_name']         = "required";
        $arr_rules['swift_code']        = "required";
        $arr_rules['account_number']    = "required";
        $arr_rules['bank_address']      = "required";

        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput()->withTab('tab_bank');
        }

        $arr_data['branch_name']        = $request->input('branch_name','');
        $arr_data['bank_name']          = $request->input('bank_name','');
        $arr_data['swift_code']         = $request->input('swift_code','');
        $arr_data['account_number']     = $request->input('account_number','');
        $arr_data['bank_address']       = $request->input('bank_address','');
       
        $obj_data = $this->SiteSettingModel->first();
        if($obj_data)
        {
            $status_update = $obj_data->update($arr_data);
        }
        else
        {
            $status_create = $this->SiteSettingModel->create($arr_data);
        }
        if($status_update) 
        {
            Session::flash('success', 'Bank details updated successfully.');
        }
        elseif($status_create)
        {
            Session::flash('success', 'Bank details added successfully.');
        }
        else
        {
            Session::flash('error','Problem Occurred, While Updating Bank details');
        }
        return redirect()->back();
    }

}