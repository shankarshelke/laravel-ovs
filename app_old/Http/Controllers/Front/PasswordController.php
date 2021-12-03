<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use App\Models\UserModel;
use Session;
use Config;
use Hash;
use DB;
use Validator;

class PasswordController extends Controller
{
    public function __construct()
	{
		Config::set("auth.defaults.passwords","user");
        Config::set("auth.password_hasher","off"); //turn off password hashing
        Config::set("auth.use_custom_template","on"); //turn on custom templates
        Config::set("auth.user_mode","user");
        $this->UserModel = new UserModel();
        $this->module_view_folder   = "front.auth";
    }

    public function forgot_password()
    {
        $this->arr_view_data['module_title'] = 'Forgot Password';
        $this->arr_view_data['page_title']   = 'Forgot Password';

        return view($this->module_view_folder.'.forgot_password',$this->arr_view_data);        
    }

    public function postEmail(Request $request)
    {
        $arr_email     = $arr_content = [];

        $arr_rules['email']    = "required|email";

        $validator = Validator::make($request->all(),$arr_rules);
        
        if($validator->fails()) 
        {
            Session::flash('error','Please fill up the all mandatory fields.');
            return redirect()->back();      
        }

        $arr_email                         = $request->only('email');
        $arr_email['is_email_verified'] = '1';

        $response = Password::sendResetLink($arr_email,function($m){
            $m->subject(config('app.project.name').' : Your Password Reset Link');
        });
        switch ($response)
        {
            case Password::RESET_LINK_SENT:
                Session::flash('success','We have sent you an email containing your reset password link, Please check the inbox of registered email.');
                return redirect('/login');
            case Password::INVALID_USER:
                Session::flash('error',trans($response));
                return redirect('/login');
        }
    }

    public function getReset($enc_token = null)
    {
        if (is_null($enc_token)) 
        {
            Session::flash('error','Your reset password link is expired or invalid.');
            return redirect('/login');
        }
        $token          = $enc_token;
        $password_reset = DB::table('user_password_reset')->where('token',$token)->first();
        if($password_reset == NULL)
        {
            Session::flash('error','Your reset password link is expired or invalid.');
            return redirect('/login');
        }

        $this->arr_view_data['token'] = $token;
        $this->arr_view_data['email'] = isset($password_reset->email) ? $password_reset->email : '';
        $this->arr_view_data['module_title'] = 'Reset Password';
        $this->arr_view_data['page_title']   = 'Reset Password';
        return view($this->module_view_folder.'.reset_password',$this->arr_view_data);
    }

    public function postReset(Request $request)
    {

        $status = false;
        
        $arr_rules['token']    = "required";
        $arr_rules['email']    = "required|email";
        $arr_rules['password'] = "required|confirmed|min:6";

        $validator = Validator::make($request->all(),$arr_rules);
        
        if($validator->fails()) 
        {
            Session::flash('error','Please fill up the all mandatory fields.');
            return redirect()->back();      
        }

        $credentials   = $request->only('email', 'password', 'password_confirmation', 'token');
        $obj_user = $this->UserModel->where('email','=',$credentials['email'])->first();
        
        if($obj_user)
        {
          $password  = isset($credentials['password'])?Hash::make($credentials['password']):'';
          $status    = $obj_user->update(['password'=>$password]);
        }
        if($status)
        {
          $is_deleted_token    = DB::table('user_password_reset')->where('token','=',$credentials['token'])->delete();

          Session::flash('success','Your '.config('app.project.name').' password has been updated, You can log-in to Your account.');
          return redirect('/login');
        }
        else
        {
          Session::flash('error','Error occure while updating your '.config('app.project.name').' password.');
          return redirect('/login');
        }
    }

    public function validate_email(Request $request)
    {
        $email = $request->input('email', null);

        if($email!=null)
        {
            $count = $this->UserModel->where('email', $email)->count();

            if($count>0)
            {
                return response()->json(TRUE);             
            }
            else
            {
                return response()->json(FALSE);          
            }
            return response()->json(FALSE);             
        }
        return response()->json(FALSE);             
    }
}