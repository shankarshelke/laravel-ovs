<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Common\CommonController;

use App\Models\WebAdmin;
//use App\Models\MerchantModel;

use Validator;
use Session;
use Cookie;

class AuthController extends CommonController
{
    public function __construct(WebAdmin $web_admin_model)
    {
        $this->auth               = auth()->guard('admin');
        $this->arr_view_data      = [];
	    $this->module_title       = "Admin";
	    $this->module_view_folder = "admin.auth";
	    $this->admin_panel_slug   = config('app.project.admin_panel_slug');
	    $this->module_url_path    = url($this->admin_panel_slug);
        $this->WebAdmin           = $web_admin_model;
    }

    public function login()
    {   
        $this->arr_view_data['module_title']     = $this->module_title." Login";
        $this->arr_view_data['page_title']       = $this->module_title." Login";
        $this->arr_view_data['admin_panel_slug'] = $this->admin_panel_slug;
        $this->module_view_folder = "admin.auth";
        return view($this->module_view_folder.'.login',$this->arr_view_data);
    }

    public function validate_login(Request $request)
    {   //dd($request->toArray());
        $arr_rules      = array();
        $status         = false;
        $remember_me = "";

        $arr_rules['email']          = "required|email";
        $arr_rules['password']       = "required";

        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails()) 
        {
            return back()->withErrors($validator)->withInput();
        }

        $remember_me = $request->input('remember_me');

        $obj_group_admin  = $this->WebAdmin->where('email',$request->only('email'))->first();
        if($obj_group_admin) 
        {
            if(\Auth::guard('admin')->attempt($request->only('email', 'password')))
            {
                $arr_admin =$obj_group_admin->toArray();
                if($remember_me!= 'on' || $remember_me == null)
                {
                    setcookie("remember_me_email","");
                    setcookie("remember_me_password","");
                    session(['subadmin_id' => $arr_admin['id']]);
                    // dd($setcookie);
                }
                else
                {
                    setcookie('remember_me_email',$request->input('email'), time()+60*60*24*100);
                    setcookie('remember_me_password',$request->input('password'), time()+60*60*24*100);
                    session(['subadmin_id' => $arr_admin['id']]);
                }
                // dd($arr_admin);
                if($arr_admin['permissions']=="")
                {
                    return redirect(url('/admin/dashboard'));
                }
                else
                {
                    return redirect(url('/admin/account_setting'));
                }
            }
            else
            {
                setcookie("remember_me_email","");
                setcookie("remember_me_password","");
                
                Session::flash('error','Your login attempt was not successful. Please try again.');

                return redirect()->back();
            }
        }
        else
        { 
            setcookie("remember_me_email","");
            setcookie("remember_me_password","");

            Session::flash('error','Your login attempt was not successful. Please try again.');
            return redirect()->back();
        }

        return redirect()->back();
    }

    public function logout()
    {
        $this->auth->logout();
        Session::flush();
        return redirect($this->module_url_path.'/');
    }

}
