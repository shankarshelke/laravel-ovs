<?php

namespace App\Common\Services;
use Tymon\JWTAuth\Contracts\JWTSubject;

use App\Models\WebAdmin;
use App\Models\DistrictModel;
use App\Models\CityModel;
use App\Models\VillageModel;
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

class Authservice
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

		$this->user_profile_base_img_path=base_path().config('app.project.img_path.admin_profile_image');
	}	
	
	public function processlogin($request)
	{ 		
		$arr_rule = [];
		$arr_responce = [];

		$arr_rule['email']    = 'required';
		$arr_rule['password'] = 'required';
		$validator = Validator::make($request->all(), $arr_rule);

		if($validator->fails())
		{
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'Please fill all the required field.';
			$arr_responce['data']	= [];
			return $arr_responce;
		}
		$arr_data['email'] = $request->input('email');
		$arr_data['password'] = $request->input('password');
         //dd($arr_data);
		if(auth()->guard('admin')->attempt($arr_data))
		{
			//dd('correct');
			$obj_user = $this->WebAdmin->where('email',$arr_data['email'])->first();
			$token = JWTAuth::fromUser($obj_user);

			$arr_login_data = []; 
			$arr_login_data['email']            = isset($obj_user->permissions)? $obj_user->email : "";
			$arr_login_data['first_name']       = isset($obj_user->first_name)?$obj_user->first_name : "";
			$arr_login_data['last_name']        = isset($obj_user->last_name)?$obj_user->last_name : "";
			if($obj_user->admin_type == 'SUPERADMIN')
			{
				$arr_login_data['permission']   = "SUPERADMIN has All Permissions";
			}
			if ($obj_user->admin_type == 'SUBADMIN') 
			{
				$arr_login_data['permission']   =$obj_user->permissions;
				$arr_login_data['role']   		=$obj_user->role;
			} 
			
			$arr_login_data['tokan']	        = $token;

			$arr_responce['status'] = 'success';
			$arr_responce['msg']	= 'Congratulations! '.$arr_login_data['first_name'].' '.$arr_login_data['last_name'] .' You have logged in successfully.';
			$arr_responce['data']	= $arr_login_data;
			return $arr_responce;
		}
		else
		{
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'Error while login to your account.';
			$arr_responce['data']	= [];
			return $arr_responce;		
		}	
	}

	public function register($request)
	{   
		//dd(session('subadmin_id'));
		/*$obj_admin = $this->WebAdmin->where('id',session('subadmin_id'))->first();
		if($obj_admin->admin_type=='SUBADMIN')
		{
			$arr_responce['status'] = 'Error';
			$arr_responce['msg']	= 'You Don\'t have permission to add team Member';
			$arr_responce['data']	= [];
			return $arr_responce;
		}
*/
		$arr_rule = [];
		$arr_responce = [];
        $arr_rule['email']   	= "required|email|unique:web_admin,email";
		//$arr_rule['username']   = "required";
		$arr_rule['password']   = "required";
		$arr_rule['address']    = "required";
		$arr_rule['first_name'] = "required";
		$arr_rule['last_name']  = "required";
		$arr_rule['contact']    = "required";
		$arr_rule['admin_type']  = "required";
			
		$validator = Validator::make($request->all(), $arr_rule);

		if($validator->fails())
		{
			$arr_responce['status'] = 'error';
			$arr_responce['msg'] =$validator->errors()->first();
			$arr_responce['data']	= [];
			return $arr_responce;				
		}
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
	    $token = substr( str_shuffle( $chars ), 0,40);
		$email  		= $request->input('email');
		//$username		= $request->input('username');
		$password		=bcrypt($request->input('password'));
		$first_name		= $request->input('first_name');
		$last_name		= $request->input('last_name');
		$contact    	= $request->input('contact');
		$address   		= $request->input('address');
		$role	    	= $request->input('role');
		$admin_type	    = $request->input('admin_type');
		//$profile_image	= $request->input('profile_image');
		

		$arr_data = [];

		$arr_data['email'] 		= isset($email)? $email : "";
		$arr_data['password'] 	= isset($password)? $password : "";
		$arr_data['first_name'] = isset($first_name)? $first_name : "";
		$arr_data['last_name'] 	= isset($last_name)? $last_name : "";
		$arr_data['contact'] 	= isset($contact)? $contact : "";
		$arr_data['address'] 	= isset($address)? $address : "";
		$arr_data['role'] 		= isset($role)? $role : "";
		$arr_data['admin_type'] = isset($admin_type)? $admin_type : "";


		/*if($request->hasFile('profile_image'))
        {     
            $file_extension = strtolower($request->file('profile_image')->getClientOriginalExtension());

            if(in_array($file_extension,['png','jpg','jpeg']))
            {
                $file     = $request->file('profile_image');
                $filename = sha1(uniqid().uniqid()) . '.' . $file->getClientOriginalExtension();
                $path     = $this->user_profile_base_img_path . $filename;
                $isUpload = $file->move($this->user_profile_base_img_path , $filename);
                if($isUpload)
                {
                    $arr_data['profile_image'] = $filename;
                }
            }    
            else
            {
	            $arr_responce['status'] = 'Error';
				$arr_responce['msg']	= 'Oops, Please Select Proper File Format(.png, .jpg, .jpeg)';
				$arr_responce['data']	= [];
				return $arr_responce;
            }
        }*/
        // dd($arr_data);
		$creatuser = $this->WebAdmin->create($arr_data);
		if($creatuser)
		{	
			// $activation_link     = url('/').'/api/verify_user/id='.$creatuser->id;
			$activation_link     = url('/').'/api/verify_user/'.base64_encode($creatuser->id);
			$data['to_email_id'] = $creatuser->email;
			$data['username']    = $arr_data['email'];
			$data['verification_url']= $activation_link;
			$data['webadmin_id'] = $creatuser->id;
			$data['first_name']	 = $creatuser->first_name;
			$data['last_name']	 = $creatuser->last_name;
			$data['role']		 = $creatuser->role;

            $res_email = $this->MailService->send_user_registration_email($data);

			$arr_responce['status'] = 'success';
			$arr_responce['msg']	='Congratulations! '.$arr_data['first_name'].' '.$arr_data['last_name'].' You have Registered successfully. Check Your Email For Further Detail';
			$arr_responce['data']	= [];
			return $arr_responce;
		}
		else
		{
			$arr_responce['status'] = 'Error';
			$arr_responce['msg']	= 'Oops,Something went wrong,please try again later';
			$arr_responce['data']	= [];
			return $arr_responce;
		}	
	}

	public function verify_user($request,$id)
	{
		// dd($id,base64_decode($email));

		$user_id 	= base64_decode($id);
		// $user_email	= base64_decode($email);
		 // dd($user_id,$user_email);
		$user_detail	= $this->WebAdmin->where('id',$user_id)->first();
		 //dd($user_detail);
		if($user_detail == "")
		{
			// dd($user_detail);
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'User not exists';
			$arr_responce['data']	= [];
			return $arr_responce;
		}

		if(isset($user_id) && $user_detail->is_verified==1)
		{   
			$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
	    	$cred = substr( str_shuffle( $chars ), 0,8);
	    	// dd($cred);
			$arr_data['is_verified']	='1';
			$arr_data['password'] 		=  bcrypt($cred);
			$obj_user =	$this->WebAdmin->where('id',$user_id)->update($arr_data);
			// dd($obj_user->toArray());
			if($obj_user)
			{	//dd($obj_user->is_verified);
				$obj_user = $this->WebAdmin->where('id',$user_id)->first();

                $login_link     = url('/').'/api/login';
                $arr_data['to_email_id']=$obj_user->email;
                $arr_data['to_user_name']=$obj_user->email;
                $arr_data['first_name']   =$obj_user->first_name;
                $arr_data['password']=$cred;
                $arr_data['role']   =$obj_user->role;
                $arr_data['login_url']=$login_link;
                // dd($arr_data);
				$res_email = $this->MailService->send_user_registration_detail($arr_data);
				$arr_responce['status'] = 'success';
				$arr_responce['msg']	= 'Email Verified successfully. Please Check Email For Login Details';
				$arr_responce['data']	= [];
				return $arr_responce;
			}
			else
			{
				$arr_responce['status'] = 'error';
				$arr_responce['msg']	= 'Oops,Err';
				$arr_responce['data']	= [];
				return $arr_responce;
			}
		}
		elseif(isset($user_id) && $user_detail->is_verified==1)
		{
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'Your email Allready Verified';
			$arr_responce['data']	= [];
			return $arr_responce;
		}
		else
		{
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'No Record Found';
			$arr_responce['data']	= [];
			return $arr_responce;
		}
	}

	public function forgot_password($request)
	{
		$arr_data = [];
		$arr_data['email'] = $request->input('email');
		
		if($arr_data['email'] !="")
		{
			$user_exist = $this->WebAdmin->where('email',$arr_data['email'])->first();
			//dd($user_exist);
			if($user_exist != null)
			{ 
				$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
            	$password = substr( str_shuffle( $chars ), 0, 8 );
            	$password_db	=bcrypt($password);
            	$reset_code= rand(100000,999999);
            	// dd($reset_code);
            	$arr_data['username'] = isset($user_exist->username)? $user_exist->username : "";

				$res_update = $this->WebAdmin->where('email',$arr_data['email'])->update(array('password'=>$password_db,'password_reset_code'=>$reset_code));
				// dd($res_update);
				if($res_update)
				{	
					$res_update = $this->WebAdmin->where('email',$arr_data['email'])->first();
					$id=base64_encode($res_update->id);
					// $decrypt= decrypt($arr_data['email']); 
					$encode_email	=base64_encode($arr_data['email']);
					// $dcrypt_email  = base64_decode($bcrypt_email);
					$token = JWTAuth::fromUser($res_update);

					$login_link     = url('/').'/api/new_password/'.base64_encode($id).'/'.$encode_email.'/'.base64_encode($reset_code);
					$data['to_email_id']        = $res_update->email;
					$data['to_user_name']       = $res_update->email;
					$data['verification_url']   = $login_link;
					$data['webadmin_id']        = $res_update->id;
					$data['first_name']	        = $res_update->first_name;
					$data['last_name']	        = $res_update->last_name;
					//dd($data);
		            $res_email = $this->MailService->send_forget_password_email($data);
		           // dd($res_email);
					$arr_responce['status'] = 'success';
					$arr_responce['msg']	= 'Please check your email for new password.';
					$arr_responce['data']	= [];
					return $arr_responce;	
				}
				$arr_responce['status'] = 'error';
				$arr_responce['msg']	= 'Error while updating password.';
				$arr_responce['data']	= [];
				return $arr_responce;
			

			}
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'Sorry, email id not exists';
			$arr_responce['data']	= [];
			return $arr_responce;
		}
		$arr_responce['status'] = 'error';
		$arr_responce['msg']	= 'Oops,Something went wrong,please try again later';
		$arr_responce['data']	= [];
		return $arr_responce;
	}
	


	public function new_password($request,$id,$email,$reset_code)
	{
		$arr_rule = [];
		$arr_rule['password']         = "required";
		$arr_rule['confirm_password'] = "required|same:password";
		$validator = Validator::make($request->all(), $arr_rule);
		if($validator->fails())
		{
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'Please fill all the required field.';
			$arr_responce['data']	= [];

			if($validator->errors())
			{
				$arr_responce['msg'] =$validator->errors()->first();
			}
			
			return $arr_responce;
		}

		$encypted_id    = base64_decode($id);
		$admin_id       = base64_decode($encypted_id);
		$admin_email  	= base64_decode($email);
		$reset_code 	= base64_decode($reset_code);
		// dd($admin_id,$admin_email,$reset_code);

		$password 	        = $request->input('password');

		if($id!="" && $email!="")
		{
			$res_admin = $this->WebAdmin->where('id',$admin_id)
										->where('email',$admin_email)
										->where('password_reset_code',$reset_code)
										->first();
			if($res_admin)
			{
				$arr_data['password'] 			=bcrypt($password);
				$arr_data['password_reset_code']="";

				$res_update = $this->WebAdmin->where('email',$admin_email)->update($arr_data);
				if($res_update)
				{	
					$res_update 			= $this->WebAdmin->where('email',$admin_email)->first();
					$first_name	 			= $res_update->first_name;
					$last_name	 			= $res_update->last_name;
					$arr_responce['status'] = 'success';
					$arr_responce['msg']	= 'Great! '.$first_name.' '.$last_name.' Password Forgeted Successfully';
					$arr_responce['data']	= [];
					return $arr_responce;
				}
				$arr_responce['status'] 	= 'error';
				$arr_responce['msg']		= 'Error While Forgeting Password';
				$arr_responce['data']		= [];
				return $arr_responce;
			}
			$arr_responce['status'] 		= 'error';
			$arr_responce['msg']			= 'No Record Found';
			$arr_responce['data']			= [];
			return $arr_responce;

			
		}
		else
		{
			$arr_responce['status'] 		= 'error';
			$arr_responce['msg']			= 'No Record Found';
			$arr_responce['data']			= [];
			return $arr_responce;
		}
	}



	public function change_password($request)
	{
	   // dd('change_password');
		$arr_rule = [];

		$arr_rule['old_password']  	= "required";
		$arr_rule['new_password']  	= "required";
		$arr_rule['conf_password'] 	= "required|same:new_password";

		$validator = Validator::make($request->all(), $arr_rule);

		if($validator->fails())
		{
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'Please fill all the required field.';
			$arr_responce['data']	= [];

			if($validator->errors())
			{
				$arr_responce['msg'] =$validator->errors()->first();
			}
			
			return $arr_responce;
		}

		$webadmin_id 	= $request->input('webadmin_id');
		$old_password 	= $request->input('old_password');
		$new_password 	= $request->input('new_password');
		$conf_password 	= $request->input('conf_password');
        // dd($conf_password  );
		$user_obj = $this->WebAdmin->where('id',$webadmin_id)->first();
		//dd($user_obj);
		if($user_obj)
		{
				if($old_password != $new_password)
				{  
					$resupdate = $this->WebAdmin->where('id',$webadmin_id)->update(['password' => bcrypt($new_password)]);
					if($resupdate)
					{
						$arr_responce['status'] = 'success';
						$arr_responce['msg']	= 'Your password changed successfully';
						$arr_responce['data']	= [];
						return $arr_responce;	
					}
					$arr_responce['status'] = 'error';
					$arr_responce['msg']	= 'Error while updating password.';
					$arr_responce['data']	= [];
					return $arr_responce;

				}
				$arr_responce['status'] = 'error';
				$arr_responce['msg']	= 'Sorry you can not use current password as a new password, Please enter another new password';
				$arr_responce['data']	= [];
				return $arr_responce;
			
		}
		$arr_responce['status'] = 'error';
		$arr_responce['msg']	= 'Oops,Something went wrong,please try again later.';
		$arr_responce['data']	= [];
		return $arr_responce;
	}

	


    public function get_country($request)
	{
		$country_arr  = [];
		$obj_coutry = $this->CountryModel->get();
		if($obj_coutry)
		{
			$countryList = $obj_coutry->toArray();
			foreach ($countryList as $key => $value) {
				$country_arr[$key]['id']	= $value['id'];
				$country_arr[$key]['name']	= $value['name'];
				$country_arr[$key]['country_code']	= $value['country_code'];
			}
			$arr_responce['status'] = 'success';
			$arr_responce['msg']	= 'Country list';
			$arr_responce['data']	= $country_arr;
			return $arr_responce;

		}

		$arr_responce['status'] = 'error';
		$arr_responce['msg']	= 'Oops,Something went wrong,please try again later.';
		$arr_responce['data']	= [];
		return $arr_responce;
	}


	public function profile_details($request)
	{   
		$user = $request->input('webadmin_id');
		
		if(isset($user) && $user != null)
		{
			$userdata = $this->WebAdmin->with('get_country')->where('id',$user)->first();
			//dd($userdata->toArray());

			$userinfo['email']         = isset($userdata->email)?$userdata->email :"";
			$userinfo['first_name']    = isset($userdata->first_name)?$userdata->first_name :"";
			$userinfo['last_name']     = isset($userdata->last_name)?$userdata->last_name :"";
			$userinfo['role']          = isset($userdata->role)?$userdata->role :"";
			$userinfo['profile_image'] = isset($userdata->profile_image) && $userdata->profile_image !="" ?$this->user_profile_image_url.$userdata->profile_image :"";
			$userinfo['webadmin_id']  = $user;

			$arr_responce['status'] = 'success';
			$arr_responce['msg']	= 'User profile details';
			$arr_responce['data']	= $userinfo;
			return $arr_responce;			
		}
		$arr_responce['status'] = 'error';
		$arr_responce['msg']	= 'Oops,Something went wrong,please try again later';
		$arr_responce['data']	= [];
		return $arr_responce;
	}

	public function update_profile($request)
	{	
		$arr_rule = [];
		$profile_image ='';
		$webadmin_id = isset($request->webadmin_id)? $request->webadmin_id :"";

		if(isset($webadmin_id) && $webadmin_id != null)
		{
			$userdata = $this->WebAdmin->where('id',$webadmin_id)->first();

			$email             = isset($userdata->email)? $userdata->email :"";
			$role              = isset($userdata->role)? $userdata->role :"";
			$userName          = isset($userdata->username)?$userdata->username :"";
			$oldprofileImage   = isset($userdata->profile_image)?$userdata->profile_image :"";

		}

		$arr_rule['first_name']    = "required";
		$arr_rule['last_name']     = "required";
		$arr_rule['role']          = "required";
		//$arr_rule['date_of_birth'] = "required";
		$arr_rule['admin_type']    = "required";
		
		

		$validator = Validator::make($request->all(), $arr_rule);

		if($validator->fails())
		{
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'Please fill all the required field.';
			$arr_responce['data']	= [];

			if($validator->errors())
			{
				$arr_responce['msg'] =$validator->errors()->first();
			}
			
			return $arr_responce;
		}

		$firstName   = $request->input('first_name');
		$lastName    = $request->input('last_name');
		$role        = $request->input('role');
		$admin_type  = $request->input('admin_type');


		$arr_data['first_name'] 	= isset($firstName)?$firstName : "";
		$arr_data['last_name'] 		= isset($lastName)?$lastName : "";
		$arr_data['role'] 	        = isset($role)?$role : "";
		$arr_data['admin_type'] 	= isset($admin_type)?$admin_type : "";
	


		if($request->hasFile('profile_image'))
		{
			$file_name = $request->input('profile_image');
			$file_extension = strtolower($request->file('profile_image')->getClientOriginalExtension());

			if(in_array($file_extension,['jpg','jpeg','png']))
			{
				$file_name = sha1(uniqid().$file_name.uniqid()).'.'.$file_extension;
				$isUpload = $request->file('profile_image')->move($this->user_profile_image_path,$file_name);
				if($isUpload)
				{
					if($oldprofileImage !="" && $oldprofileImage != null)
					{
						if(file_exists($this->user_profile_image_path.$oldprofileImage))
						{
							unlink($this->user_profile_image_path.$oldprofileImage);
						}
					}

					$arr_data['profile_image']  = isset($file_name)?$file_name : "";
				}
			}

		}
		
		$resUser = $this->WebAdmin->where('id',$webadmin_id)->update($arr_data);
		if($resUser)
		{
			$arr_responce['status'] = 'success';
			$arr_responce['msg']	= 'User Profile updated successfully.';
			$arr_responce['data']	= [];
			return $arr_responce;
		}

		$arr_responce['status'] = 'error';
		$arr_responce['msg']	= 'Oops,Something went wrong,please try again later.';
		$arr_responce['data']	= [];
		return $arr_responce;
	}
	public function transfer_money_voter($request)
	{   
	   $arr_rules      = $arr_data = array();
	   $status         = false;
	  


        $arr_rule['amount']   	= "required";
        $arr_rule['d_date']   	= "required";
        $arr_rule['user_id']   	= "required";

			
		$validator = Validator::make($request->all(), $arr_rule);

		if($validator->fails())
		{
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'Please fill all the required field.';
			$arr_responce['data']	= [];
			return $arr_responce;
		}
		$obj_accountant =$this->MoneyDistributionModel->where('subadmin_id',session('subadmin_id'))->first();
        // dd($obj_accountant);
        if ($obj_accountant)
        { 
            $arr_accountant = $obj_accountant->toArray();
        }

       // $arr_data['village_id']          = $arr_accountant['village_id'];
		$arr_data['amount']			     = $request->input('amount', null);
		$arr_data['d_date']              =$request->input('d_date',null);
		$arr_data['user_id']             =$request->input('user_id',null);
		$arr_data['subadmin_id']         =session('subadmin_id');


        //dd($arr_data);
		$status = $this->VoterMoneyDistributionModel->create($arr_data);
		if($status)
		{
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
			
			// dd($userdata->toArray());
			$userinfo['voter_money_id']  = isset($userdata->id)?$userdata->id :"";
			$first_name= isset($userdata->get_user_details->first_name)?$userdata->get_user_details->first_name :"";
			$last_name=isset($userdata->get_user_details->last_name)?$userdata->get_user_details->last_name :"";
			$admin_f_name= isset($userdata->get_admin_details->first_name)?$userdata->get_admin_details->first_name :"";
			$admin_l_name= isset($userdata->get_admin_details->last_name)?$userdata->get_admin_details->last_name :"";
			$userinfo['disributor_name'] = $admin_f_name.' '.$admin_l_name;
			$userinfo['voter_name'] 	 = $first_name.' '.$last_name;
			$userinfo['amount']          = isset($userdata->amount)?$userdata->amount :"";
			$userinfo['d_date']          = isset($userdata->d_date)?$userdata->d_date :"";
			

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


	public function votermoney_listing($request)
	{
		 $arr_data = [];
		 $data=[];
		 $obj_accountant =$this->MoneyDistributionModel->where('subadmin_id',session('subadmin_id'))->get();
        // dd($obj_accountant);
        if ($obj_accountant)
        { 
            $arr_accountant = $obj_accountant->toArray();
       
		   $arr_responce['status'] = 'success';
			$arr_responce['msg']	= 'Voter money Listing.';
			$arr_responce['data']	=  $arr_accountant;
			return $arr_responce;
			
		}
		else
		{
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'No Record Found.';
			$arr_responce['data']	= $data;
			return $arr_responce;
			
		}


}
}
?>
