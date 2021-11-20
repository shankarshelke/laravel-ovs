<?php

namespace App\Common\Services;
use Tymon\JWTAuth\Contracts\JWTSubject;

use App\Models\WebAdmin;
use App\Models\DistrictModel;
use App\Models\CityModel;
use App\Models\VillageModel;
use App\Models\CountryModel;
use App\Models\UsersModel;
use Validator;
use Auth;
use JWTAuth;
use Hash;
use Session;

class Voter
{
	function __construct()
	{
		$this->WebAdmin          = new WebAdmin();
		$this->CountryModel      = new CountryModel();
		$this->UsersModel    	 = new UsersModel();
		$this->SmsService	     = new SmsService();
		$this->MailService	     = new MailService();
		$this->auth              = auth()->guard('admin');
		$this->sid               = config('');

		$this->user_profile_image_path = base_path().config('app.project.img_path.user_profile_image');
		$this->user_profile_image_url  = url('/').config('app.project.img_path.user_profile_image');
		$this->user_image_base_path         = base_path().config('app.project.img_path.user_image');
		// dd('here');
	}	

	public function create_voter($request)
	{
		$arr_rules      = $arr_data = array();
		$status         = false;

		$arr_rules['aadhar_id']      	   	 = "required|unique:users,aadhar_id";
		$arr_rules['voter_id']      		 = "required|unique:users,voter_id";
		$validator = Validator::make($request->all(), $arr_rules);
		if($validator->fails())
		{
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'Voter ID Or Aadhar ID Allready Used.';
			$arr_responce['data']	= [];
			return $arr_responce;
		}

		$arr_rules['profile_image']   	   	 = "required";
		$arr_rules['first_name']  	         = "required";
		$arr_rules['last_name']  	         = "required";
		$arr_rules['father_full_name']       = "required";
		$arr_rules['mobile_number']  	     = "required";
		$arr_rules['gender']  	        	 = "required";
		$arr_rules['date_of_birth']  	     = "required";
		$arr_rules['house_no']  	         = "required";
		$arr_rules['street']  	             = "required";
		$arr_rules['village']  	             = "required";
		$arr_rules['city']  	       		 = "required";
		$arr_rules['district']  	         = "required";
		$arr_rules['pincode']  	             = "required";
		$arr_rules['face_color']  	         = "required";
		$arr_rules['voting_surety']  	     = "required";
		$arr_rules['religion']  	         = "required";
		$arr_rules['caste']  	             = "required";
		$arr_rules['booth'] 				 = "required";
 		$arr_rules['ward']					 = "required";
		$arr_rules['list']					 = "required";
		$arr_rules['occupation']  	         = "required";
		$arr_rules['latitude']  	         = "required";
		$arr_rules['longitude']  	         = "required";

		$validator = Validator::make($request->all(), $arr_rules);

		
		if($validator->fails())
		{
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'Please fill all the required field.';
			$arr_responce['data']	= [];
			return $arr_responce;
		}

		$arr_data['aadhar_id']			= $request->input('aadhar_id', null);
		$arr_data['first_name']			= $request->input('first_name', null);
		$arr_data['last_name']			= $request->input('last_name', null);
		$arr_data['father_full_name']	= $request->input('father_full_name', null);	
		$arr_data['voter_id']			= $request->input('voter_id', null);
		$arr_data['gender']	   	 		= $request->input('gender', null);
		$arr_data['date_of_birth']		= $request->input('date_of_birth', null);
		$arr_data['house_no']			= $request->input('house_no', null);
		$arr_data['street']	      	  	= $request->input('street', null);
		$arr_data['village']	 	   	= $request->input('village',null);
		$arr_data['city']	     	   	= $request->input('city', null);
		$arr_data['pincode']		    = $request->input('pincode', null);	
		$arr_data['booth']	   	 	    = $request->input('booth', null);
		$arr_data['ward']	    	    = $request->input('ward', null);
		$arr_data['list']	    	    = $request->input('list', null);	
		$arr_data['state']	     	   	= $request->input('state', null);	
		$arr_data['district']	  	  	= $request->input('district', null);
		$arr_data['email']	 	  	  	= $request->input('email', null);
		$arr_data['mobile_number']		= /*'+91'.*/$request->input('mobile_number', null);
		$arr_data['face_color']	  	  	= $request->input('face_color', null);
		$arr_data['voting_surety']		= $request->input('voting_surety', null);	
		$arr_data['religion']	  	  	= $request->input('religion', null);
		$arr_data['caste']	      	  	= $request->input('caste', null);
		$arr_data['occupation']	  	  	= $request->input('occupation', null);
		$arr_data['address']	  	  	= $arr_data['house_no'].','.$arr_data['street'].','.$arr_data['pincode']	;
		$arr_data['latitude']	 	   	= $request->input('latitude', null);
		$arr_data['longitude']	 	   	= $request->input('longitude', null);
		$arr_data['subadmin_id']	 	   	= session('subadmin_id');

		if($request->hasFile('profile_image'))
		{         
			// dd($this->user_image_base_path);	
			$file_extension = strtolower($request->file('profile_image')->getClientOriginalExtension());

			if(in_array($file_extension,['png','jpg','jpeg']))
			{
				$file     = $request->file('profile_image');
				$filename = sha1(uniqid().uniqid()) . '.' . $file->getClientOriginalExtension();
				$path     = $this->user_image_base_path . $filename;
				// dd($filename,$path);
				$isUpload = $file->move($this->user_image_base_path , $filename);
				if($isUpload)
				{
					$arr_data['profile_image'] = $filename;
				}
			}    
			else
			{
				$arr_responce['status'] = 'error';
				$arr_responce['msg']	= 'Error While Uploading Profile Picture';
				$arr_responce['data']	= [];
				return $arr_responce;
			}
		}

	 // dd($arr_data);

		$status = $this->UsersModel->create($arr_data);
		if($status)
		{ 
			$arr_create_data['email']            = isset($status->email)? $status->email : "";
			$arr_create_data['first_name']       = isset($status->first_name)?$status->first_name : "";
			$arr_create_data['last_name']        = isset($status->last_name)?$status->last_name : "";

			$arr_responce['status'] = 'success';
			$arr_responce['msg']	= 'Voter Added Successfully.';
			$arr_responce['data']	= $arr_create_data;
			return $arr_responce;
		}
		else
		{
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'User not found';
			$arr_responce['data']	= [];
			return $arr_responce;
		}
		
	}



	public function edit($request ,$enc_id)
	{
		$arr_rules      = $arr_data = array();
		$status         = false;

		// $arr_rules['profile_image']   	   	 = "required";
		// $arr_rules['aadhar_id']      	   	 = "required";
		// $arr_rules['voter_id']      		 = "required";
		$arr_rules['first_name']  	         = "required";
		$arr_rules['last_name']  	         = "required";
		$arr_rules['father_full_name']       = "required";
		$arr_rules['mobile_number']  	     = "required";
		$arr_rules['gender']  	        	 = "required";
		$arr_rules['date_of_birth']  	     = "required";
		$arr_rules['house_no']  	         = "required";
		$arr_rules['street']  	             = "required";
		// $arr_rules['village']  	             = "required";
		// $arr_rules['city']  	       		 = "required";
		// $arr_rules['district']  	         = "required";
		// $arr_rules['pincode']  	             = "required";
		$arr_rules['face_color']  	         = "required";
		$arr_rules['voting_surety']  	     = "required";
		$arr_rules['religion']  	         = "required";
		$arr_rules['caste']  	             = "required";
		// $arr_rules['booth'] 				 = "required";
 	// 	$arr_rules['ward']					 = "required";
		// $arr_rules['list']					 = "required";
		$arr_rules['occupation']  	         = "required";
		// $arr_rules['latitude']  	         = "required";
		// $arr_rules['longitude']  	         = "required";

		$validator = Validator::make($request->all(), $arr_rules);

		
		if($validator->fails())
		{
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'Please fill all the required field.';
			$arr_responce['data']	= [];
			return $arr_responce;
		}

		// $arr_data['aadhar_id']			= $request->input('aadhar_id', null);
		$arr_data['first_name']			= $request->input('first_name', null);
		$arr_data['last_name']			= $request->input('last_name', null);
		$arr_data['father_full_name']	= $request->input('father_full_name', null);
		$arr_data['gender']	   	 		= $request->input('gender', null);
		$arr_data['date_of_birth']		= $request->input('date_of_birth', null);
		$arr_data['house_no']			= $request->input('house_no', null);
		$arr_data['street']	      	  	= $request->input('street', null);
		// $arr_data['village']	 	   	= $request->input('village',null);
		// $arr_data['city']	     	   	= $request->input('city', null);
		$arr_data['pincode']		    = $request->input('pincode', null);	
		// $arr_data['booth']	   	 	    = $request->input('booth', null);
		// $arr_data['ward']	    	    = $request->input('ward', null);
		// $arr_data['list']	    	    = $request->input('list', null);	
		// $arr_data['state']	     	   	= $request->input('state', null);	
		// $arr_data['district']	  	  	= $request->input('district', null);
		// $arr_data['email']	 	  	  	= $request->input('email', null);
		$arr_data['mobile_number']		= /*'+91'.*/$request->input('mobile_number', null);
		$arr_data['face_color']	  	  	= $request->input('face_color', null);
		$arr_data['voting_surety']		= $request->input('voting_surety', null);	
		$arr_data['religion']	  	  	= $request->input('religion', null);
		$arr_data['caste']	      	  	= $request->input('caste', null);
		$arr_data['occupation']	  	  	= $request->input('occupation', null);
		$arr_data['address']	  	  	= $arr_data['house_no'].','.$arr_data['street'].','.$arr_data['pincode'];
		// $arr_data['latitude']	 	   	= $request->input('latitude', null);
		// $arr_data['longitude']	 	   	= $request->input('longitude', null);
		$arr_data['admin_id']	 	   	= session('subadmin_id');
		//dd($arr_data);
		if($request->hasFile('profile_image'))
		{         
			$file_extension = strtolower($request->file('profile_image')->getClientOriginalExtension());

			if(in_array($file_extension,['png','jpg','jpeg']))
			{
				$file     = $request->file('profile_image');
				$filename = sha1(uniqid().uniqid()) . '.' . $file->getClientOriginalExtension();
				$path     = $this->user_image_base_path . $filename;
				// dd($filename,$path);
				$isUpload = $file->move($this->user_image_base_path , $filename);
				if($isUpload)
				{
					$arr_data['profile_image'] = $filename;
				}
				else
				{
					$arr_responce['status'] = 'error';
					$arr_responce['msg']	= 'Error While Uploading Profile Picture';
					$arr_responce['data']	= [];
					return $arr_responce;
				}
			}    
			else
			{
				$arr_responce['status'] = 'error';
				$arr_responce['msg']	= 'Invalid Type Of Image';
				$arr_responce['data']	= [];
				return $arr_responce;
			}
		}
		$status = $this->UsersModel->where('id','=',$enc_id)->update($arr_data);
		if($status)
		{ 
			// $arr_create_data['email']            = isset($status->email)? $status->email : "";
			$arr_create_data['first_name']       = $arr_data['first_name'];
			$arr_create_data['last_name']        = $arr_data['last_name'];

			$arr_responce['status'] = 'success';
			$arr_responce['msg']	= 'Voter Updated Successfully.';
			$arr_responce['data']	= $arr_create_data;
			return $arr_responce;
		}
		else
		{
			$arr_responce['status'] = 'error';
			$arr_responce['msg']	= 'User not found';
			$arr_responce['data']	= [];
			return $arr_responce;
		}
		
	
	}
}

?>