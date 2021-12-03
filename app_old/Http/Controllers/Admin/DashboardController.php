<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon;
use App\Models\SiteSettingModel;
use App\Models\UsersModel;
use App\Models\RolesModel;
use App\Models\FinanceTeamModel;
use App\Models\WebAdmin;
use App\Models\VoterMoneyDistributionModel;
use App\Models\MoneyDistributionModel;


class DashboardController extends Controller
{
	public function __construct()
	{
		$this->arr_view_data                    = [];
		$this->module_title                     = "Dashboard";
		$this->module_view_folder               = "admin.dashboard";		
		$this->admin_url_path                   = url(config('app.project.admin_panel_slug'));
		$this->admin_panel_slug                 = config('app.project.admin_panel_slug');
		$this->ip_address                       = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:false;
		$this->auth                             = auth()->guard('admin');
		$this->UsersModel					    = new UsersModel();
		$this->RolesModel					    = new RolesModel();
		$this->FinanceTeamModel					= new FinanceTeamModel();
		$this->WebAdmin		                    = new WebAdmin();
		$this->VoterMoneyDistributionModel      = new VoterMoneyDistributionModel();
		$this->MoneyDistributionModel           = new MoneyDistributionModel();
	}


	public function index()
	{
		$arr_site_setting = [];
		$obj_site_setting = SiteSettingModel::first();
		if($obj_site_setting)
		{
			$arr_site_setting = $obj_site_setting->toArray();
		}
		$obj_users = $this->UsersModel->get();
		if($obj_users)
		{
			$arr_users = $obj_users->toArray();
		}
		$obj_admin = $this->WebAdmin->where('admin_type','SUBADMIN')->get();
        if($obj_admin)
        {
            $arr_admin = $obj_admin->toArray();  
        }
        $obj_roles = $this->RolesModel->get();
        if($obj_roles)
        {
            $arr_roles = $obj_roles->toArray(); 
        }
        $obj_financeteam = $this->FinanceTeamModel->get();
        if($obj_financeteam)
        {
            $arr_financeteam = $obj_financeteam->toArray(); 
        }

        $obj_admin  = $this->WebAdmin->where('id','=',session('subadmin_id'))->first();
        //dd($obj_admin->admin_type);
        if($obj_admin->admin_type=='SUBADMIN')
        {
        	$obj_voter_money = $this->VoterMoneyDistributionModel->where('subadmin_id','=',$obj_admin->id)->get();
        	// dd($obj_voter_money->toArray());
        }
        else
        {
        	$obj_voter_money = $this->VoterMoneyDistributionModel->get();
        	// dd($obj_voter_money->toArray());
        }
        
        if($obj_voter_money)
        {
            $arr_voter_money = $obj_voter_money->toArray();
            $voter_money=0;
            foreach ($arr_voter_money as $key => $value) 
            {
            	$voter_money=$voter_money+$value['amount'];
            }
        }
        $obj_admin  = $this->WebAdmin->where('id','=',session('subadmin_id'))->first();
        //dd($obj_admin->admin_type);
        if($obj_admin->admin_type=='SUBADMIN')
        {
        	$obj_admin_money = $this->MoneyDistributionModel->where('subadmin_id','=',$obj_admin->id)->get();
        }
        else
        {
        	$obj_admin_money = $this->MoneyDistributionModel->get();
        }
        
        //dd($obj_admin_money->toArray());
        if($obj_admin_money)
        {
            $arr_admin_money = $obj_admin_money->toArray();
            $admin_money=0;
            foreach ($arr_admin_money as $key => $value) 
            {
            	$admin_money=$admin_money+$value['amount'];
            }
        }

        $obj_today_birthday = $this->UsersModel
           ->whereRaw('DATE_FORMAT(date_of_birth, "%m-%d") = ?', [date('m-d')])
           ->count();
         /* pie data */
         $full_surity = $this->UsersModel
                             ->where('voting_surety','0')
                             ->count();

         if($full_surity ==0){
            $full_surity = 0;

         } 
         else{
            $full_surity = $full_surity;
         }                   
         $half_surity = $this->UsersModel
                            ->where('voting_surety','1')
                            ->count();

         if($half_surity ==0){
            $half_surity = 0;
         }  
         else{
            $half_surity = $half_surity;
         }                 
         $not_surity = $this->UsersModel
                            ->where('voting_surety','2')
                            ->count();

         if($not_surity ==0){

            $not_surity = 0;
         } 
         else{
            $not_surity = $not_surity;
         }                  

         $none_surity = $this->UsersModel
                            ->whereNull('voting_surety')
                            ->count();

         if($none_surity ==0){
            
            $none_surity = 0;
         } 
         else{
            $none_surity = $none_surity;
         }   

        /* 2nd Pie Data */
        $total_user = $this->UsersModel->count();

        $not_available = $this->UsersModel->whereNull('mobile_number') 
                               ->count(); 
		$this->arr_view_data['page_title']                       = $this->module_title;
		$this->arr_view_data['module_title']                     = $this->module_title;
		$this->arr_view_data['parent_module_icon']               = "icon-home2";
		$this->arr_view_data['parent_module_title']              = "Dashboard";
		$this->arr_view_data['parent_module_url']                = url('/').'/admin/dashboard';
		$this->arr_view_data['admin_panel_slug']                 = $this->admin_panel_slug;
		$this->arr_view_data['arr_site_setting']                 = $arr_site_setting;
		$this->arr_view_data['arr_users']                        = $arr_users;
		$this->arr_view_data['arr_roles']                        = $arr_roles;
		$this->arr_view_data['arr_admin']                        = $arr_admin;
		$this->arr_view_data['arr_financeteam']                  = $arr_financeteam;
		$this->arr_view_data['voter_money']                  	 = $voter_money;
		$this->arr_view_data['admin_money']                  	 = $admin_money;
        $this->arr_view_data['today_birthday']                   = $obj_today_birthday;
		$this->arr_view_data['ad_type']		                  	 = $obj_admin->admin_type;
        $this->arr_view_data['full_surity']                          = $full_surity;
        $this->arr_view_data['half_surity']                          = $half_surity;
        $this->arr_view_data['not_surity']                          = $not_surity;
        $this->arr_view_data['none_surity']                        = $none_surity;
        $this->arr_view_data['total_user']                          = $total_user;
        $this->arr_view_data['not_available']                        = $not_available;        
		//dd($this->arr_view_data);
		return view($this->module_view_folder.'.index',$this->arr_view_data);
	}

	public function sms_trial(Request $request)
	{
		$mobile_no  = $request->input('mobile_no',Null);
        $arr_msg['mobile_no']  = $mobile_no;
		$this->ApiOtpService->sms_trial($arr_msg);
		
	}	
}
