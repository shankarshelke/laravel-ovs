<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AircraftOwnerModel;
use App\Models\UsersModel;
use App\Common\Services\MailService;
use App\Notifications\VerifyAccount;
use Carbon;
use Auth;
use Validator;
use Session;
use Cookie;
use Hash;

class AuthController extends Controller
{
    public function __construct()
    {
          $this->AircraftOwnerModel = new AircraftOwnerModel();
          $this->UsersModel         = new UsersModel();  
          $this->arr_view_data      = [];
          $this->module_title       = "User";
          $this->module_view_folder = "front.auth";
          $this->MailService        = new MailService();
          $this->user_auth          = auth()->guard('users');
          $this->operator_auth      = auth()->guard('operator');
    }

    public function process_login()
    {   
        if(Auth::guard('operator')->check()){
            $profile_url = url('/').'/operator/profile';
            return redirect()->to($profile_url);
        }elseif(Auth::guard('users')->check()){
            $profile_url = url('/').'/user/profile';
            return redirect()->to($profile_url);
        }

        $this->arr_view_data['module_title']     = $this->module_title." Login";
        $this->arr_view_data['page_title']       = $this->module_title." Login";

        return view($this->module_view_folder.'.login',$this->arr_view_data);	
    }

    public function validate_login(Request $request)
    {
        $arr_rules      = $arr_credentials =  array();
        $status         = false;
        $user_id = $password  = '';
        $arr_rules['user_id']        = "required";
        $arr_rules['password']       = "required";

        $validator = Validator::make($request->all(),$arr_rules);
        
        if($validator->fails()) 
        {
            return back()->withErrors($validator)->withInput();
        }
        $time    = Carbon::now();
        
        $arr_data       = [];
        $user_id        = $request->input('user_id');
        $password       = $request->input('password');
        $remember_me    = $request->input('remember_me', null);

        $arr_data['user_id']    = $user_id;
        $arr_data['password']   = $password;

        $arr_credentials['owner_id'] = $owner_id = $request->input('user_id',null);
        $arr_credentials['password'] = $request->input('password',null);

        $remember = isset($remember_me) ? TRUE : FALSE ;

        if(auth()->guard('users')->attempt($arr_data))
        {
            $obj_user = $this->UsersModel->where('user_id',$arr_data['user_id'])->first();

            if(!$obj_user->is_email_verified){
                $this->user_auth->logout();
                Session::flash('error','Your email is not verified yet, please verify first.');
                return redirect()->back();
            }elseif(!$obj_user->is_verified){
              $this->user_auth->logout();
              Session::flash('error','Your account is not verified by admin yet. Please contact to admin.');
              return redirect()->back();
            }elseif(!$obj_user->status){
                $this->user_auth->logout();
                Session::flash('error','Your account blocked by admin.');
                return redirect()->back();
            }

            if($remember_me!= 'on' || $remember_me == null)
            {
                setcookie("remember_me_id","");                       
            }else{
                setcookie('remember_me_id',$request->input('user_id'), time()+60*60*24*100);
            }
            $this->UsersModel->where('id', $user_id )->update(['last_logged_at'=> $time]);

            if($request->has('redirect_to') && $request->input('redirect_to') != ''){
                return redirect($request->input('redirect_to'));
            }else{
                return redirect(url('/').'/operator/profile');
            }
        }
        elseif(auth()->guard('operator')->attempt($arr_credentials))
        {
            $obj_operator = $this->AircraftOwnerModel->where('owner_id',$arr_credentials['owner_id'])->first();
            if(!$obj_operator->is_email_verified){
                $this->operator_auth->logout();
                Session::flash('error','Your email is not verified yet, please verify first.');
                return redirect()->back();
            }elseif(!$obj_operator->is_verified){
                $this->operator_auth->logout();
                Session::flash('error','Your account is not verified by admin yet. Please contact to admin.');
                return redirect()->back();
            }elseif(!$obj_operator->status){
                $this->operator_auth->logout();
                Session::flash('error','Your account blocked by admin.');
                return redirect()->back();
            }

            if($remember_me!= 'on' || $remember_me == null)
            {
                setcookie("remember_me_id","");                       
            }else{
                setcookie('remember_me_id', $owner_id, time()+60*60*24*100);
            }

            if($request->has('redirect_to') && $request->input('redirect_to') != ''){
                return redirect($request->input('redirect_to'));
            }else{
                return redirect(url('/').'/operator/profile');
            }

        }
        else
        {
            setcookie("remember_me_email","");

            Session::flash('error','Your login attempt was not successful. Please try again.');

            return redirect()->back();
        }

        $this->arr_view_data['module_title']     = $this->module_title." Login";
        $this->arr_view_data['page_title']       = $this->module_title." Login";

        Session::flash('error','The User Id or password you entered is incorrect.');

        return redirect()->back();
        
    }
    public function reset_password(Request $request)
    {
        $arr_rules      = array();
        $status         = false;

        $arr_rules['email']        = "required";

        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails()) 
        {
            return back()->withErrors($validator)->withInput();
        }
        $input    = $request->input('email');

        $obj_data_owner = $this->AircraftOwnerModel->where('owner_id',$input)
                                                   ->first();
        if(isset($obj_data_owner) && $obj_data_owner != 'null')
        {
            $first_name         = isset($obj_data_owner['first_name']) ? $obj_data_owner['first_name'] : '';
            $last_name          = isset($obj_data_owner['last_name']) ? $obj_data_owner['last_name'] : '';
            $password           = $obj_data_owner['password'];
            $id                 = $obj_data_owner['owner_id'];
            $email              = $obj_data_owner['email'];  
            if(isset($email) && $email != 'null')
            {
                $arr_email['first_name']       = isset($first_name) ? $first_name : '';
                $arr_email['last_name']        = isset($last_name) ? $last_name : '';
                $arr_email['to_user_name']     = $first_name.' '.$last_name;
                $arr_email['to_email_id']      = $email;
                $arr_email['verification_url'] = url('/').'/set_password/'.base64_encode($id);

                $date     = Carbon\Carbon::tomorrow()->format('Y-m-d');
                $obj_data_owner1  = $this->AircraftOwnerModel->where('owner_id',$input)
                                                            ->update(['is_set_password'=>'0','set_password_link_expiry'=>$date]);
                $email_status = $this->MailService->send_forget_password_email($arr_email);
                Session::flash('success','reset password has been sent successfully.');
                return redirect()->back();
            }
        }

        $obj_data_user  = $this->UsersModel->where('user_id',$input)
                                           ->first();
        if(isset($obj_data_user) && $obj_data_user != 'null')
        {
            $first_name         = isset($obj_data_user['first_name']) ? $obj_data_user['first_name'] : '';
            $last_name          = isset($obj_data_user['last_name']) ? $obj_data_user['last_name'] : '';
            $password           = $obj_data_user['password'];
            $id                 = $obj_data_user['user_id'];
            $email              = $obj_data_user['email'];  
            
            if(isset($email) && $email != 'null')
            {
                $arr_email['first_name']       = isset($first_name) ? $first_name : '';
                $arr_email['last_name']        = isset($last_name) ? $last_name : '';
                $arr_email['to_user_name']     = $first_name.' '.$last_name;
                $arr_email['to_email_id']      = $email;
                $arr_email['verification_url'] = url('/').'/set_password/'.base64_encode($id);

                $date     = Carbon\Carbon::tomorrow()->format('Y-m-d');
                $obj_data_user1  = $this->UsersModel->where('user_id',$input)
                                                    ->update(['is_set_password'=>'0','set_password_link_expiry'=>$date]);
                
                $email_status = $this->MailService->send_forget_password_email($arr_email);
                Session::flash('success',' Reset password link send successfully, Please check your email.');
                return redirect()->back();
            }
        }
        $this->arr_view_data['module_title']     = $this->module_title." Login";
        $this->arr_view_data['page_title']       = $this->module_title." Login";
        Session::flash('error','The User Id you entered is incorrect.');
        return redirect()->back();
    }
    
    public function set_password(Request $request,$enc_id)
    {
        $new_date = Carbon\Carbon::now()->format('Y-m-d');
        $id = base64_decode($enc_id);

        $obj_data_owner  = $this->AircraftOwnerModel->where('owner_id',$id)->first();
        if(isset($obj_data_owner) && $obj_data_owner != 'null')
        {
            $this->arr_view_data['new_date']         = $new_date;        
            $this->arr_view_data['module_title']     = $this->module_title." Login";
            $this->arr_view_data['page_title']       = $this->module_title." Login";
            $this->arr_view_data['obj_data']         = $obj_data_owner;
            return view($this->module_view_folder.'.set_password',$this->arr_view_data);   
        }
        $obj_data_user  = $this->UsersModel->where('user_id',$id)->first();
        if(isset($obj_data_user) && $obj_data_user != 'null')
        {
            $this->arr_view_data['new_date']         = $new_date;    
            $this->arr_view_data['module_title']     = $this->module_title." Login";
            $this->arr_view_data['page_title']       = $this->module_title." Login";
            $this->arr_view_data['obj_data']         = $obj_data_user;
            return view($this->module_view_folder.'.set_password',$this->arr_view_data);   
        }
    }

    public function save_password(Request $request,$enc_id)
    {
        $arr_rules = array();
        $status = FALSE;

        $arr_rules['new_password']     = "required";
        $arr_rules['confirm_password'] = "required|same:new_password";        
        
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
            Session::flash('error',' Error while updating.');
            return redirect()->back()->withErrors($validator);
        }

        $new_password     = $request->input('new_password');
        $confirm_password = $request->input('confirm_password');

        $user_id = base64_decode($enc_id);
        $arr_user = []; 
        $status = false;
    
       $credentials   = $request->only('new_password',  'confirm_password', 'token');

        $check_user = $this->UsersModel->where('user_id',$user_id)->first();
        
        if($check_user){

            $obj_user = $this->UsersModel->where('user_id',$user_id)->first();
            if($obj_user)
            {
                $password  = isset($credentials['new_password'])?Hash::make($credentials['new_password']):'';
                $status    = $obj_user->update(['password'=>$password,'is_set_password'=>'1']);
                if($status)
                {
                    Session::flash('success',' Your password has been updated, You can log-in to your account.');
                    return redirect('/sign_in');
                }
                else
                {
                    Session::flash('error','Error occure while updating your '.config('app.project.name').' password.');
                    return redirect('/sign_in');
                }
                Session::flash('error','Invalid user details.');
                return redirect('/sign_in');
            }

        } else {

            $obj_operator = $this->AircraftOwnerModel->where('owner_id',$user_id)->first();
            if($obj_operator)
            {
                $password  = isset($credentials['new_password'])?Hash::make($credentials['new_password']):'';
                $status    = $obj_operator->update(['password'=>$password,'is_set_password'=>'1']);
            }
            if($status)
            {
                Session::flash('success',' Your password has been updated, You can log-in to your account.');
                return redirect('/sign_in');
            }
            else
            {
                Session::flash('error','Error occure while updating your '.config('app.project.name').' password.');
                return redirect('/sign_in');
            }
            Session::flash('error','Invalid User Details.');
            return redirect('/sign_in');
        }
        Session::flash('error','Invalid User Details.');
        return redirect('/sign_in');
    }


    public function logout()
    {
        if(Auth::guard('users')->check())
        {
            $user_id = $this->user_auth->user()->id;
            Cookie::forget('remember_me_id');
            $this->user_auth->logout();
        }
        elseif(Auth::guard('operator')->check())
        {
            $owner_id = $this->operator_auth->user()->id;
            Cookie::forget('remember_me_id');
            $this->operator_auth->logout();
        }
        Session::flush();
        return redirect('/');
    }

}
