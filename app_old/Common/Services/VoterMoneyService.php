<?php

namespace App\Common\Services;
use Tymon\JWTAuth\Contracts\JWTSubject;

use App\Models\WebAdmin;
use App\Models\CountryModel;
use App\Models\UsersModel;
use App\Models\VoterMoneyDistributionModel;
use App\Models\MoneyDistributionModel;
use App\Models\FinanceTeamModel;


use Validator;
use Auth;
use JWTAuth;
use Hash;
use Session;
use Crypt;

class VoterMoneyService
{
	function __construct()
	{
		$this->WebAdmin          				 = new WebAdmin();;
		$this->CountryModel                      = new CountryModel();
		$this->SmsService	                     = new SmsService();
		$this->MoneyDistributionModel	         = new MoneyDistributionModel();
		$this->FinanceTeamModel	                 = new FinanceTeamModel();
		$this->VoterMoneyDistributionModel	     = new VoterMoneyDistributionModel();
		$this->UsersModel	                     = new UsersModel();
		$this->MailService	                     = new MailService();
		$this->auth                              = auth()->guard('admin');
		$this->sid                               = config('');

		$this->user_profile_image_path = base_path().config('app.project.img_path.user_profile_image');
		$this->user_profile_image_url  = url('/').config('app.project.img_path.user_profile_image');
		$this->user_image_base_path         = base_path().config('app.project.img_path.user_image');
		// dd('here');
	}		
	public function transfer_money_voter($request)
	{   
		$arr_rules      		= $arr_data = array();
		$status         		= false;
    	$arr_rule['amount']   	= "required";
    	//$arr_rule['d_date']   	= "required";
        $arr_rule['user_id']   	= "required";

			
		$validator = Validator::make($request->all(), $arr_rule);

		if($validator->fails())
		{
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'Please fill all the required field.';
			$arr_responce['data']	= [];
			return $arr_responce;
		}
		// dd(session('subadmin_id'));
		if(session('subadmin_id')==1)
		{
			// dd("Superadmin Does Not have permisson To Distribute money to Voter Direcr");
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'Superadmin Does Not have permisson To Distribute money to Voter Direcr.';
			$arr_responce['data']	= [];
			return $arr_responce;
		}
		$obj_accountant =$this->FinanceTeamModel->where('subadmin_id',session('subadmin_id'))->get();
      //dd($obj_accountant);
        if ($obj_accountant)
        { 
            $arr_accountant = $obj_accountant->toArray();
            // dd($arr_accountant[0]['village_id']);
        }

     //   $arr_data['village_id']          = $arr_accountant[0]['village_id'];
		$arr_data['amount']			     = $request->input('amount', null);
		$arr_data['d_date']              = date('Y-m-d');
		$arr_data['user_id']             = $request->input('user_id',null);
		$arr_data['subadmin_id']         = session('subadmin_id');

		//dd($arr_data);
		$status = $this->VoterMoneyDistributionModel->create($arr_data);
		if($status)
		{	
			$user_id    			= $status->user_id;
			$arr_responce['status'] = 'success';
			$arr_responce['msg']	= 'Money Transfer successfully.';
			$arr_responce['data']	= [];
			return $arr_responce;
		}

		$arr_responce['status'] = 'error';
		$arr_responce['msg']	= 'Oops,Something went wrong,please try again later.';
		$arr_responce['data']	= [];
		return $arr_responce;
	}

	public function view_voter_money_detail($request,$id)
	{
		$userdata = $this->VoterMoneyDistributionModel->where('id',$id)->with(['get_admin_details','get_user_details'])->first();
		//dd($userdata->get_admin_details->toArray());
		if(isset($userdata) && $userdata != null)
		{
			// dd('here');
			// dd($userdata->toArray());
			$userinfo['voter_money_id']  = isset($userdata->id)?$userdata->id :"";
			$first_name    = isset($userdata->get_user_details->first_name)?$userdata->get_user_details->first_name :"";
			$last_name     =isset($userdata->get_user_details->last_name)?$userdata->get_user_details->last_name :"";
			$admin_f_name  = isset($userdata->get_admin_details->first_name)?$userdata->get_admin_details->first_name :"";
			$admin_l_name  = isset($userdata->get_admin_details->last_name)?$userdata->get_admin_details->last_name :"";

			
			$userinfo['disributor_name']  = $admin_f_name.' '.$admin_l_name;
			$userinfo['voter_name'] 	  = $first_name.' '.$last_name;
			$userinfo['amount']           = isset($userdata->amount)?$userdata->amount :"";
			$userinfo['transaction_date'] = isset($userdata->d_date)?$userdata->d_date :"";
			

			$arr_responce['status'] = 'success';
			$arr_responce['msg']	= 'Money Distribution Detail of Voter';
			$arr_responce['data']	= $userinfo;
			return $arr_responce;			
		}
		$arr_responce['status'] = 'error';
		$arr_responce['msg']	= 'No Record Found';
		$arr_responce['data']	= [];
		return $arr_responce;
	}
}
?>