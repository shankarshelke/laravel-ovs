<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\AircraftOwnerModel;
use App\Models\NewsletterModel;
use App\Models\SiteSettingModel;
use App\Models\UsersModel;
use App\Common\Services\MailService;

use Carbon;
use Validator;
use Session;
use Cookie;
use Hash;

class SignupController extends Controller
{
    public function __construct(AircraftOwnerModel $aircraft_owner)
    {    
        $this->arr_view_data      = [];
        $this->module_title       = "User";
        $this->module_view_folder = "front.signup";
        $this->AircraftOwnerModel = $aircraft_owner;

        $this->aircraft_experience_base_img_path   = base_path().config('app.project.user_experience_certificates');
        $this->aircraft_experience_public_img_path = url('/').config('app.project.user_experience_certificates');
        
        $this->NewsletterModel    = new NewsletterModel();
        $this->UsersModel         = new UsersModel();
        $this->SiteSettingModel         = new SiteSettingModel();
        $this->MailService        = new MailService();
        $this->module_url_path    = "signup_user";
    }

    public function signup_user()
    {
        $this->arr_view_data['module_title']     = $this->module_title." Sign-up";
        $this->arr_view_data['page_title']       = $this->module_title." Sign-up";

        return view($this->module_view_folder.'.signup_user',$this->arr_view_data);
    }

    public function signup_operator()
    {
        $this->arr_view_data['module_title']     = $this->module_title." Sign-up";
        $this->arr_view_data['page_title']       = $this->module_title." Sign-up";

        return view($this->module_view_folder.'.signup_operator',$this->arr_view_data);
    }

    public function process_signup_user(Request $request)
    {
        $arr_rules      = array();
        //$status         = false;
        $status = 'fail';
        $errors = $customMsg = '';

        $customMsg = 'Oops,Something went wrong,Please try again later';
        $checkLevel = 0;

        $arr_rules['first_name']      = "required";
        $arr_rules['last_name']       = "required";
        $arr_rules['email']           = "required|email|unique:users,email";
        $arr_rules['filled-in-box']   = "required";

        $validator = Validator::make($request->all(),$arr_rules);
        
        if($validator->fails()) 
        {
            //return back()->withErrors($validator)->withInput();
            return json_encode(['errors'=> $validator->errors()->getMessages(),'code'=>422,'status'=>'fail']);
        }

        $is_exist ='';
        $first_name = trim($request->input('first_name'));
        $last_name  = trim($request->input('last_name'));
        $email      = trim($request->input('email'));
        $arr_data  = array();
        $user_ID = '';
        $user_ID = "ZX".mt_rand();
        $verification_url ='<a href="'.url('/').'/password/'.base64_encode($user_ID).'">Click here</a>';

        $arr_data['user_id']          = isset($user_ID) ? $user_ID :'';            
        $arr_data['email']            = isset($email) ? $email : '';
        $arr_data['first_name']       = isset($first_name) ? $first_name : '';
        $arr_data['last_name']        = isset($last_name) ? $last_name : '';
        $arr_data['set_password_link_expiry']        = Carbon\Carbon::tomorrow()->format('Y-m-d');
        $arr_email['verification_url']      = isset($verification_url) ? $verification_url : ''; 
        $arr_email['first_name']       = $first_name = isset($first_name) ? $first_name : '';
        $arr_email['last_name']        = $last_name  =isset($last_name) ? $last_name : '';
        $arr_email['to_user_name']     = $first_name.' '.$last_name;
        $arr_email['user_id']          = isset($user_ID) ? $user_ID :'';   

        $arr_email['to_email_id'] = $email;

        $status = $this->UsersModel->create($arr_data);
        $email_status = $this->MailService->send_user_registration_email($arr_email);

        if($status)
        {
            if($email_status)
            {   
                $recently_reg_user_id = $status->id;
                \Session::put('last_registered_user', $status->user_id);
                $status     = 'success';
                $customMsg = 'Registration Successfull We will send you verification mail soon.';

                 /*
                |
                |Send notification
                |
                */
                 $username = $first_name.' '.$last_name;
                
                $ARR_NOTIFICATIOn = [];
                $ARR_NOTIFICATION_DATA['receiver_id']            = config('app.project.admin_id');
                $ARR_NOTIFICATION_DATA['receiver_type']          = 'admin';
                $ARR_NOTIFICATION_DATA['sender_id']              = $recently_reg_user_id;
                $ARR_NOTIFICATION_DATA['sender_type']            = 'user';
                $ARR_NOTIFICATION_DATA['title']                  = 'User Registered';
                $ARR_NOTIFICATION_DATA['description']            = 'New User Registered '.$username;
                $ARR_NOTIFICATION_DATA['redirect_url']           = '/admin/users/view/'.base64_encode($recently_reg_user_id);
                $ARR_NOTIFICATION_DATA['status']                 = 0;
                $ARR_NOTIFICATION_DATA['notification_type']      = 'general';
                $this->save_notification($ARR_NOTIFICATION_DATA);


                $resp = array('status' => 'success','errors'=>$errors,'customMsg'=> $customMsg);
                return response()->json($resp);
            }
            else
            {
                $customMsg = 'Email Sending failed!';
                $resp = array('status' => 'fail','errors'=>'','customMsg'=> $customMsg);
                return response()->json($resp);
            }
        }
        else
        {
        	$customMsg = 'Oops,Something went wrong,Please try again later';
            $resp = array('status' => 'fail','errors'=>'','customMsg'=> $customMsg);
            return response()->json($resp);
        }
    }

    public function user_verify_account(Request$request, $user_ID, $password )
    {
        $user_id = $arr_user ='';
        $user_id = base64_decode($user_ID);
        $obj_user = $this->UsersModel->select('id','user_id')->where('user_id',$user_id)->first();
        $arr_user = $obj_user->toArray();
        $arr_data =[];
        $arr_data['password'] = base64_decode($password);
        $arr_data['is_email_verified'] = '1';

        if($user_ID!=null)
        {
            $obj_user = $this->UsersModel->where('id',$arr_user['id'])->update($arr_data);
            
            if($obj_user)
            {
                Session::flash('success','Your account has been successfully verified, You can login now.');
                return redirect('/sign_in');  
            }
            Session::flash('error','Your verification link has expired.');
            return redirect('/sign_in');
        }
    }

    public function process_signup_operator(Request $request)
    {
        $status = 'fail';
        $errors = $customMsg = '';
        $arr_rules = [];
        $arr_rules['first_name']            = "required";
        $arr_rules['last_name']             = "required"; 
        $arr_rules['email']                 = "required|email|unique:aircraft_owner,email";
        $arr_rules['company_name']          = "required";

        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            return json_encode(['errors'=> $validator->errors()->getMessages(),'code'=>422,'status'=>'fail']);
        }        

        $first_name         = trim($request->first_name);
        $last_name          = trim($request->last_name);
        $contact            = trim($request->contact);
        $email              = trim($request->email);
        $company_name       = trim($request->company_name);
        $owner_id           = "AO".mt_rand();

        $admin_data = $this->SiteSettingModel->where('id','1')->first();
        $commission_rate = isset($admin_data['commission_rate']) ?  $admin_data['commission_rate'] : '0';
        
        $verification_url ='<a href="'.url('/').'/password/'.base64_encode($owner_id).'">Click here</a>';
        $arr_data['owner_id']              = isset($owner_id) ? $owner_id : '';
        $arr_data['company_name']          = isset($company_name) ? $company_name : ''; 
        $arr_data['first_name']            = $first_name = isset($first_name) ? $first_name : '';
        $arr_data['last_name']             = $last_name = isset($last_name) ? $last_name : '';
        $arr_data['verification_url']      = isset($verification_url) ? $verification_url : ''; 
        $arr_data['email']                 = $email;
        $arr_data['set_password_link_expiry']        = Carbon\Carbon::tomorrow()->format('Y-m-d');
        $arr_data['commission_rate']       = $commission_rate;

        $status = $this->AircraftOwnerModel->create($arr_data);
        
        $email_status = $this->MailService->send_operator_registration_email($arr_data);

        if($status)
        {
            if($email_status)
            {
                $recently_reg_user_id = $status->id;
                \Session::put('last_registered_operator', $owner_id);
                $status     = 'success';
                $customMsg = 'Registeration Successfully We will send you verification mail soon.';


                /*
                |
                |Send notification
                |
                */
                 $username = $first_name.' '.$last_name;

                $ARR_NOTIFICATIOn = [];
                $ARR_NOTIFICATION_DATA['receiver_id']            = config('app.project.admin_id');
                $ARR_NOTIFICATION_DATA['receiver_type']          = 'admin';
                $ARR_NOTIFICATION_DATA['sender_id']              = $recently_reg_user_id;
                $ARR_NOTIFICATION_DATA['sender_type']            = 'aircraft_owner';
                $ARR_NOTIFICATION_DATA['title']                  = 'New Aircraft Owner Registered';
                $ARR_NOTIFICATION_DATA['description']            = 'New Aircraft Owner Registered '.$username;
                $ARR_NOTIFICATION_DATA['redirect_url']           = '/admin/aircraft_owner/view/'.base64_encode($recently_reg_user_id);
                $ARR_NOTIFICATION_DATA['status']                 = 0;
                $ARR_NOTIFICATION_DATA['notification_type']      = 'general';
                $this->save_notification($ARR_NOTIFICATION_DATA);


                $resp = array('status' => 'success','errors'=>$errors,'customMsg'=> $customMsg);
                return response()->json($resp);

            }
            else
            {
                $customMsg = 'Email Sending failed!';
                $resp = array('status' => 'fail','errors'=>$errors,'customMsg'=> $customMsg);
                return response()->json($resp);

                /*Session::flash('error','Error while replying to '.str_singular($this->module_title));*/
            }
        }
        else
        {
            $resp = array('status' => 'fail','errors'=>$errors,'customMsg'=> $customMsg);
            return response()->json($resp);

        }
    }

    public function check_user_email(Request $request) 
    {
        $email = $request->input('email');

        $response = true;
        $is_exist = $this->UsersModel->where('email',$email)->get();

        if(count($is_exist) > 0)
        {
            $response = false;
        }
        else
        {
            $response = true;
        }
        echo json_encode($response);
    }

    public function check_email(Request $request) 
    {
        $email = $request->input('email');

        $response = true;
        $is_exist = $this->AircraftOwnerModel->where('email',$email)->get();

        if(count($is_exist) > 0)
        {
            $response = false;
        }
        else
        {
            $response = true;
        }
        echo json_encode($response);
    }

    public function user_resend_resgistraion_mail(Request $request)
    {
        $last_registered_user = '';

        $msg = 'Oops,Something went wrong,Please try again later';

        if(!\Session::has('last_registered_user') || \Session::get('last_registered_user') == '' )
        {
            $resp = array('status' => 'fail','msg'=> 'Something Wents Wrong');
            return response()->json($resp);
        }

        $last_registered_user = \Session::get('last_registered_user');

        $obj_user = $this->UsersModel->where('user_id',$last_registered_user)->first();

        if(!$obj_user)
        {
            $resp = array('status' => 'fail','msg'=> 'Something Wents Wrong');
            return response()->json($resp);
        }
        $verification_url ='<a href="'.url('/').'/password/'.base64_encode($last_registered_user).'">Click here</a>';
        $first_name = isset($obj_user->first_name) ? $obj_user->first_name : '';
        $last_name = isset($obj_user->last_name) ? $obj_user->last_name : '';
        $user_id    = isset($obj_user->user_id) ? $obj_user->user_id : '';
        $arr_email['user_id']           = isset($user_id) ? $user_id :'';            
        $arr_email['first_name']        = isset($first_name) ? $first_name : '';
        $arr_email['last_name']         = isset($last_name) ? $last_name : '';
        $arr_email['to_user_name']      = $first_name.' '.$last_name;
        $arr_email['verification_url']  = isset($verification_url) ? $verification_url : ''; 
        $arr_email['to_email_id']       = isset($obj_user->email) ? $obj_user->email : '' ;

        //$status = $this->UsersModel->create($arr_data);
        $email_status = $this->MailService->send_user_registration_email($arr_email);
        if($email_status)
        {
            $resp = array('status' => 'success','msg'=> 'Email has been sent to your email');
            return response()->json($resp);
        }
    }

    public function operator_resend_resgistraion_mail(Request $request)
    {
        $last_registered_user = '';

        $msg = 'Oops,Something went wrong,Please try again later';

        if(!\Session::has('last_registered_operator') || \Session::get('last_registered_operator') == '' )
        {
            $resp = array('status' => 'fail','msg'=> 'Something Wents Wrong');
            return response()->json($resp);
        }

        $last_registered_operator = \Session::get('last_registered_operator');

        $obj_user = $this->AircraftOwnerModel->where('owner_id',$last_registered_operator)->first();

        if(!$obj_user)
        {
            $resp = array('status' => 'fail','msg'=> 'Something Wents Wrong');
            return response()->json($resp);
        }

        $verification_url ='<a href="'.url('/').'/password/'.base64_encode($last_registered_operator).'">Click here</a>';
        $first_name = isset($obj_user->first_name) ? $obj_user->first_name : '';
        $last_name  = isset($obj_user->last_name) ? $obj_user->last_name : '';
        $owner_id   = isset($obj_user->owner_id) ? $obj_user->owner_id : '';
        $arr_email['owner_id']          = isset($owner_id) ? $owner_id : '';
        $arr_email['first_name']        = isset($first_name) ? $first_name : '';
        $arr_email['last_name']         = isset($last_name) ? $last_name : '';
        $arr_email['to_user_name']      = $first_name.' '.$last_name;
        $arr_email['verification_url']  = isset($verification_url) ? $verification_url : ''; 
        $arr_email['email']             = isset($obj_user->email) ? $obj_user->email : '' ;

        //$status = $this->UsersModel->create($arr_data);
        $email_status = $this->MailService->send_operator_registration_email($arr_email);
        if($email_status)
        {
            $resp = array('status' => 'success','msg'=> 'Email has been sent to your email.');
            return response()->json($resp);
        }else{
            $resp = array('status' => 'fail','msg'=> 'Error occured while sending email.');
            return response()->json($resp);
        }
    }
    public function password(Request $request,$enc_id)
    {
        $new_date = Carbon\Carbon::now()->format('Y-m-d');
        $id = base64_decode($enc_id);
        
        $obj_data_owner = $this->AircraftOwnerModel->where('owner_id',$id)->first();

        $obj_data_user  = $this->UsersModel->where('user_id',$id)->first();

        if($obj_data_owner)
        {
            $condition = $obj_data_owner['set_password_link_expiry'] > $new_date;
          
            $this->AircraftOwnerModel->where('owner_id',$id)->update(['is_email_verified'=> '1']);

            if(($obj_data_owner['is_set_password'] == 0) && ($condition == 'true' ))
            {
        	   Session::flash('success',' Your email is verified, please set password now.');
            }
            $arr_data_owner = $obj_data_owner->toArray();

            $this->arr_view_data['new_date']         = $new_date;    
            $this->arr_view_data['arr_data']         = $arr_data_owner;
            $this->arr_view_data['module_title']     = $this->module_title." Login";
            $this->arr_view_data['page_title']       = $this->module_title." Login";
            $this->arr_view_data['obj_data']         = $obj_data_owner;
            
            return view($this->module_view_folder.'.password',$this->arr_view_data);   
        }
        elseif(isset($obj_data_user) && $obj_data_user != 'null')
        {
            $condition = $obj_data_user['set_password_link_expiry'] > $new_date;

        	$this->UsersModel->where('user_id',$id)->update(['is_email_verified'=> '1']);
        	
            if(($obj_data_user['is_set_password'] == 0) && ($condition == 'true' ))
            {
        	   Session::flash('success',' Your email is verified, please set password now.');
            }
            $arr_data_user = $obj_data_user->toArray();
            
            $this->arr_view_data['new_date']         = $new_date;    
            $this->arr_view_data['arr_data']         = $arr_data_user;
            $this->arr_view_data['module_title']     = $this->module_title." Login";
            $this->arr_view_data['page_title']       = $this->module_title." Login";
            $this->arr_view_data['obj_data']         = $obj_data_user;
            
            return view($this->module_view_folder.'.password',$this->arr_view_data);   
        }
        else{
            Session::flash('error',' Oops, Something went wrong!');
            return redirect('/sign_in');
        }
    }

    public function email_save_password(Request $request,$enc_id)
    {
        $id = base64_decode($enc_id);

        $arr_rules = array();
        $status = FALSE;
        $arr_rules['password']         = "required";
        $arr_rules['confirm_password'] = "required";        
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
           return redirect()->back()->withErrors($validator);
       }
       $password         = $request->input('password');
       $confirm_password = $request->input('confirm_password');

       $id = base64_decode($enc_id);
       $arr_user = []; 
       $status = false;

       $credentials   = $request->only('password',  'confirm_password', 'token');
       /*  dd($credentials ,$user_id);*/
       $obj_user = $this->UsersModel->where('user_id',$id)->first();

       if(isset($obj_user) && $obj_user !='null')
       {
            $password   = isset($credentials['password'])?Hash::make($credentials['password']):'';
            $status     = $obj_user->update(['password'=>$password,'is_set_password'=>'1','is_verified'=>'1']);
            $first_name = isset($obj_user->first_name) ? $obj_user->first_name : '';
            $last_name  = isset($obj_user->last_name) ? $obj_user->last_name : '';
            $user_id    = isset($obj_user->user_id) ? $obj_user->user_id : '';
            $arr_email['user_id']           = isset($user_id) ? $user_id : '';
            $arr_email['first_name']        = isset($first_name) ? $first_name : '';
            $arr_email['last_name']         = isset($last_name) ? $last_name : '';
            $arr_email['to_user_name']      = $first_name.' '.$last_name;
            $arr_email['password']          = $request->input('password');
            $arr_email['email']             = isset($obj_user->email) ? $obj_user->email : '' ;

            $arr_newsletter['email']        = isset($obj_user->email) ? $obj_user->email : '' ;
            $newsletter = $this->NewsletterModel->create($arr_newsletter);

            $email_status = $this->MailService->send_user_login_email($arr_email);
             if($email_status)
            {
               
                Session::flash('success',' Your password has been set successfully.');
                return redirect('/sign_in');
            }
        }
        $obj_operator = $this->AircraftOwnerModel->where('owner_id',$id)->first();
        if(isset($obj_operator) && $obj_operator !='null')
        {
            $password  = isset($credentials['password'])?Hash::make($credentials['password']):'';
            $status    = $obj_operator->update(['password'=>$password,'is_set_password'=>'1','is_verified'=>'1']);
            $first_name = isset($obj_operator->first_name) ? $obj_operator->first_name : '';
            $last_name  = isset($obj_operator->last_name) ? $obj_operator->last_name : '';
            $owner_id    = isset($obj_operator->owner_id) ? $obj_operator->owner_id : '';
            $arr_email['owner_id']          = isset($owner_id) ? $owner_id : '';
            $arr_email['first_name']        = isset($first_name) ? $first_name : '';
            $arr_email['last_name']         = isset($last_name) ? $last_name : '';
            $arr_email['to_user_name']      = $first_name.' '.$last_name;
            $arr_email['password']          = $request->input('password');
            $arr_email['email']             = isset($obj_operator->email) ? $obj_operator->email : '' ;

            $arr_newsletter['email']        = isset($obj_operator->email) ? $obj_operator->email : '' ;
            $newsletter = $this->NewsletterModel->create($arr_newsletter);
            
            $email_status = $this->MailService->send_operator_login_email($arr_email);
            if($email_status)
            {
                Session::flash('success','  Your password has been set successfully.');
                return redirect('/sign_in');
            }
        }
        if($status)
        {
          /*  $is_deleted_token    = DB::table('user_password_reset')->where('token','=',$credentials['token'])->delete();*/

          Session::flash('success',' Your password has been updated, You can log-in to your account.');
          return redirect('/sign_in');
      }
      else
      {
        Session::flash('error','Error occure while updating your '.config('app.project.name').' password.');
        return redirect('/sign_in');
       }
    }

}