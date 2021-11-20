<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ContactEnquiryModel;
use App\Models\FrontPagesModel;
use App\Common\Services\MailService;

use Validator;
use Session;

class FrontPagesController extends Controller
{
	function __construct()
	{
		$this->arr_view_data       = [];
		$this->module_title        = "Info";
		$this->module_view_folder  = "front.pages";
		$this->module_url_path     = url('info');
		$this->MailService         = new MailService();
		/*$this->FrontPagesModel     = new FrontPagesModel();*/
		$this->ContactEnquiryModel = new ContactEnquiryModel();
		$this->FrontPagesModel 	   = new FrontPagesModel();
	}

	/*public function terms_condition()
	{
		$arr_terms = [];

		$obj_terms = $this->FrontPagesModel->where('id','1')->first();
        
        if ($obj_terms)
        {
            $arr_terms = $obj_terms->toArray();
        }
      	//dd($arr_terms);
        $this->arr_view_data['arr_terms']     = $arr_terms;
		$this->arr_view_data['page_title']       = 'Terms Condition';
		$this->arr_view_data['module_title']     = 'Terms Condition';
		$this->arr_view_data['module_url_path']  = $this->module_url_path;

		return view($this->module_view_folder.'.terms_condition',$this->arr_view_data);
	}*/
	
	public function about_us()
	{
		$arr_about_us = [];

	/*	$obj_about_us = $this->FrontPagesModel->where('id','4')->first();
        
        if ($obj_about_us)
        {
            $arr_about_us = $obj_about_us->toArray();
        }

		$this->arr_view_data['arr_about_us']     = $arr_about_us;*/
		$this->arr_view_data['page_title']       = 'About Us';
		$this->arr_view_data['module_title']     = 'About Us';
		
		return view($this->module_view_folder.'.about_us',$this->arr_view_data);
	}

	public function contact_us()
	{
		$this->arr_view_data['page_title']      = 'Contact Us';
		$this->arr_view_data['module_title']    = 'Contact Us';

		return view($this->module_view_folder.'.contact_us',$this->arr_view_data);
	}

	public function process_contact_us(Request $request)
	{	
		$arr_rules['first_name']  = "required";
		$arr_rules['last_name']   = "required";
		$arr_rules['email']       = "required";
		$arr_rules['contact_no']  = "required";
		$arr_rules['description'] = "required";

		$validator = Validator::make($request->all(),$arr_rules);

		if($validator->fails()) 
		{		
			Session::flash('error','Please fill up the all mandatory fields.');
			return redirect()->back();
		}

		$first_name  = $request->input('first_name',null);
		$last_name   = $request->input('last_name',null);
		$email       = $request->input('email',null);
		$contact_no  = $request->input('contact_no',null);
		$description = $request->input('description',null);
		
		$arr_data['first_name']  = $first_name;
		$arr_data['last_name']   = $last_name;
		$arr_data['email']       = $email;
		$arr_data['contact_no']  = $contact_no;
		$arr_data['message'] = $description;

		$status = $this->ContactEnquiryModel->create($arr_data);

		if($status)
		{
			$arr_notification['first_name'] = $first_name;
			$arr_notification['last_name']  = $last_name;
			$arr_notification['message']    = 'New Contact Enquiry By'.$first_name;
			$arr_notification['to_type']    = '1';
			
			 /*
                |
                |Send notification
                |
             */

                 $username = $first_name.' '.$last_name;
                
                $ARR_NOTIFICATIOn = [];
                $ARR_NOTIFICATION_DATA['receiver_id']            = config('app.project.admin_id');
                $ARR_NOTIFICATION_DATA['receiver_type']          = 'admin';
                $ARR_NOTIFICATION_DATA['sender_id']              = '';
                $ARR_NOTIFICATION_DATA['sender_type']            = 'user';
                $ARR_NOTIFICATION_DATA['title']                  = 'Contact Enquiry';
                $ARR_NOTIFICATION_DATA['description']            = 'New Contact enquiry received from '.$username;
                $ARR_NOTIFICATION_DATA['redirect_url']           = '/admin/contact_enquiry';
                $ARR_NOTIFICATION_DATA['status']                 = 0;

                $this->save_notification($ARR_NOTIFICATION_DATA);

			Session::flash('success', ' Thank you for contacting us, We will get back you soon!');
			return redirect()->back();
		}
		
		Session::flash('error', 'Sorry! Error occurred while send the message.');
		return redirect()->back();
	}

	public function terms_conditions()
	{
		$obj_data = $this->FrontPagesModel->where('id','7')->first();
		if($obj_data)
		{
			$arr_data = $obj_data->toArray();
		}


		$this->arr_view_data['arr_data']	    = $arr_data;
		$this->arr_view_data['page_title']      = 'Terms & Conditions';
		$this->arr_view_data['module_title']    = 'Terms & Conditions';

		return view($this->module_view_folder.'.cms',$this->arr_view_data);
	}

	public function privacy_policy()
	{
		$obj_data = $this->FrontPagesModel->where('id','9')->first();
		if($obj_data)
		{
			$arr_data = $obj_data->toArray();
		}


		$this->arr_view_data['arr_data']	    = $arr_data;
		$this->arr_view_data['page_title']      = 'Privacy Policy';
		$this->arr_view_data['module_title']    = 'Privacy Policy';

		return view($this->module_view_folder.'.cms',$this->arr_view_data);
	}

	public function guidelines()
	{
		$obj_data = $this->FrontPagesModel->where('id','11')->first();
		if($obj_data)
		{
			$arr_data = $obj_data->toArray();
		}


		$this->arr_view_data['arr_data']	    = $arr_data;
		$this->arr_view_data['page_title']      = 'Guidelines';
		$this->arr_view_data['module_title']    = 'Guidelines';

		return view($this->module_view_folder.'.cms',$this->arr_view_data);
	}
	

	public function investor_information()
	{
		$obj_data = $this->FrontPagesModel->where('id','12')->first();
		if($obj_data)
		{
			$arr_data = $obj_data->toArray();
		}


		$this->arr_view_data['arr_data']	    = $arr_data;
		$this->arr_view_data['page_title']      = 'Investor Information';
		$this->arr_view_data['module_title']    = 'Investor Information';

		return view($this->module_view_folder.'.cms',$this->arr_view_data);
	}

	public function legal_terms()
	{
		$obj_data = $this->FrontPagesModel->where('id','13')->first();
		if($obj_data)
		{
			$arr_data = $obj_data->toArray();
		}


		$this->arr_view_data['arr_data']	    = $arr_data;
		$this->arr_view_data['page_title']      = 'Legal Terms';
		$this->arr_view_data['module_title']    = 'Legal Terms';

		return view($this->module_view_folder.'.cms',$this->arr_view_data);
	}

	public function site_map()
	{
		$obj_data = $this->FrontPagesModel->where('id','14')->first();
		if($obj_data)
		{
			$arr_data = $obj_data->toArray();
		}


		$this->arr_view_data['arr_data']	    = $arr_data;
		$this->arr_view_data['page_title']      = 'Site Map';
		$this->arr_view_data['module_title']    = 'Site Maps';

		return view($this->module_view_folder.'.cms',$this->arr_view_data);
	}	

}
