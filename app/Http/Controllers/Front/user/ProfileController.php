<?php

namespace App\Http\Controllers\Front\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AircraftOwnerModel;
use App\Models\NewsletterModel;
use App\Models\UsersModel;
use App\Common\Services\MailService;

use Auth;
use Validator;
use Session;
use Cookie;
use Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->arr_view_data            = [];
        $this->module_title             = "User";
        $this->module_view_folder       = "front.user";
        $this->AircraftOwnerModel       = new AircraftOwnerModel();
        $this->NewsletterModel          = new NewsletterModel();
        $this->UsersModel               = new UsersModel();
        $this->user_profile_base_img_path   = base_path().config('app.project.user_profile_image');
        $this->user_profile_public_img_path = url('/').config('app.project.user_profile_image');
 
        $this->MailService     = new MailService();
        $this->module_url_path = "signup_user";
        $this->user_auth       = auth()->guard('users');
    }

    public function profile()
    {
        $user = $this->user_auth->user();
        $user_id = $user->user_id;

        $obj_data  = $this->UsersModel->where('user_id',$user_id)->first();

        if($obj_data)
        {
            $email = isset($obj_data['email']) ? $obj_data['email'] :'NA';
        }

        $obj_newsletter = $this->NewsletterModel->where('email',$email)->count();

        $this->arr_view_data['obj_data']         = $obj_data;
        $this->arr_view_data['obj_newsletter']   = $obj_newsletter;
        $this->arr_view_data['module_title']     = $this->module_title." Profile";
        $this->arr_view_data['page_title']       = $this->module_title." Profile";
        $this->arr_view_data['user_profile_base_img_path']   = $this->user_profile_base_img_path;
        $this->arr_view_data['user_profile_public_img_path'] = $this->user_profile_public_img_path;
        return view($this->module_view_folder.'.user_profile',$this->arr_view_data);
    }

    public function update_user(Request $request)
    { 
        $user = $this->user_auth->user();
        $user_id = $user->user_id;

        if($user_id)
        {
            $user_data = $this->UsersModel->where('user_id',$user_id)->first();
            if($user_data) 
            {
                $arr_user = $user_data->toArray();
                $id = (isset($arr_user['id']) ? $arr_user['id'] : '');
                $first_name =  (isset($arr_user['first_name']) ? ucfirst($arr_user['first_name']) : '');
                $last_name  =  (isset($arr_user['last_name']) ? ucfirst($arr_user['last_name']) : '');
                $user_name  =  $first_name.' '.$last_name;
            }

        }
        $arr_rules = [];
        $arr_rules['first_name']            = "required";
        $arr_rules['last_name']             = "required"; 
        $arr_rules['contact']               = "required";
        $arr_rules['address']               = "required";
        $arr_rules['latitude']              = "required";
        $arr_rules['longitude']             = "required";

        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails())
        {   
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $first_name = $request->first_name;
        $last_name  = $request->last_name;
        $address    = $request->address;
        $contact    = $request->contact;
        $latitude   = $request->latitude;
        $longitude  = $request->longitude;
       /* $email      = $request->email;*/
        $old_image  = $request->oldimage;
        $company_details  = $request->company_details;

        if($request->hasFile('profile_image'))
        {
            $file_name = $request->input('profile_image');
            $file_extension = strtolower($request->file('profile_image')->getClientOriginalExtension());
            if(in_array($file_extension,['jpg','jpeg','png']))
            {
                $file_name = sha1(uniqid().$file_name.uniqid()).'.'.$file_extension;
                $isUpload  = $request->file('profile_image')->move($this->user_profile_base_img_path, $file_name);
            }
            else
            {
                Session::flash('error','Invalid File type, While creating '.str_singular($this->module_title));
                return redirect()->back();
            }
        }
        else
        {
             $file_name = $old_image;
        }

            $arr_data['first_name']            = isset($first_name) ? $first_name : '' ;
            $arr_data['last_name']             = isset($last_name) ? $last_name : ''; 
            $arr_data['mobile_number']         = isset($contact) ? $contact : '';
            $arr_data['address']               = isset($address) ? $address : '';
            $arr_data['latitude']              = isset($latitude) ? $latitude :'';
            $arr_data['longitude']             = isset($longitude) ? $longitude : '';
            $arr_data['profile_image']         = isset($file_name) ? $file_name : '' ; 
            $arr_data['company_details']       = isset($company_details) ? $company_details : '' ; 
            $obj_update = $this->UsersModel->where('user_id',$user_id)->update($arr_data);
            
            if($obj_update)
            { 
                $ARR_NOTIFICATIOn = [];
                $ARR_NOTIFICATION_DATA['receiver_id']            = $id;
                $ARR_NOTIFICATION_DATA['receiver_type']          = 'user';
                $ARR_NOTIFICATION_DATA['sender_id']              = config('app.project.admin_id');
                $ARR_NOTIFICATION_DATA['sender_type']            = 'admin';
                $ARR_NOTIFICATION_DATA['title']                  = 'User Profile Update';
                $ARR_NOTIFICATION_DATA['description']            = 'Hello '.$user_name.' Your Profile has been updated successfully.';
                $ARR_NOTIFICATION_DATA['redirect_url']           = '';
                $ARR_NOTIFICATION_DATA['status']                 = 0;
                $ARR_NOTIFICATION_DATA['notification_type']      = 'general';
                $this->save_notification($ARR_NOTIFICATION_DATA);
                Session::flash('success',' Profile updated Successfully.');
                return redirect()->back();    
            }
       
    }

    public function update_password(Request $request)
    {
        $arr_rules = array();
        $status = FALSE;
        $arr_rules['old_password']     = "required";
        $arr_rules['new_password']     = "required";
        $arr_rules['confirm_password'] = "required|same:new_password";        
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
           return redirect()->back()->withErrors($validator);
        }

        $old_password     = $request->input('old_password');
        $new_password     = $request->input('new_password');
        $confirm_password = $request->input('confirm_password');

        $user = $this->user_auth->user();
        $user_id =  $user->user_id;
        //dd($user_id);
        if($user_id)
        {
            $user_data = $this->UsersModel->where('user_id',$user_id)->first();
            if($user_data) 
            {
                $arr_user = $user_data->toArray();
                $id = (isset($arr_user['id']) ? $arr_user['id'] : '');
                $first_name =  (isset($arr_user['first_name']) ? ucfirst($arr_user['first_name']) : '');
                $last_name  =  (isset($arr_user['last_name']) ? ucfirst($arr_user['last_name']) : '');
                $user_name  =  $first_name.' '.$last_name;
            }

        }
        if(Hash::check($old_password,$this->user_auth->user()->password))
        {
            if($old_password!=$new_password)
            {
                if($new_password == $confirm_password)
                {
                    //$user_password = bcrypt($new_password);
                    $status = $this->UsersModel->where('user_id',$user_id)->update(['password' => bcrypt($new_password)]);

                    if($status)
                    {

                       Session::flash('success','Your password changed successfully.');

                        //\Auth::guard('users')->logout();

                         $login_user_type = 'user';
                        \Session::put('login_user_type',$login_user_type);

                        $ARR_NOTIFICATIOn = [];
                        $ARR_NOTIFICATION_DATA['receiver_id']            = $id;
                        $ARR_NOTIFICATION_DATA['receiver_type']          = 'user';
                        $ARR_NOTIFICATION_DATA['sender_id']              = config('app.project.admin_id');
                        $ARR_NOTIFICATION_DATA['sender_type']            = 'admin';
                        $ARR_NOTIFICATION_DATA['title']                  = 'User Password Update';
                        $ARR_NOTIFICATION_DATA['description']            = 'Hello '.$user_name.' Your Password has been Changed successfully.';
                        $ARR_NOTIFICATION_DATA['redirect_url']           = '';
                        $ARR_NOTIFICATION_DATA['status']                 = 0;
                        $ARR_NOTIFICATION_DATA['notification_type']      = 'general';
                        $this->save_notification($ARR_NOTIFICATION_DATA);
                        return redirect()->back();
                    }
                    else
                    {
                        Session::flash('error','Problem occured, while changing password.');
                    }
                    return redirect()->back();
                }
                else
                {
                    Session::flash('error','Please enter the same value again.');
                    return redirect()->back();
                }
            }
            else
            {
                Session::flash('error','Sorry you can not use current password as a new password, Please enter another new password.');
                return redirect()->back();
            }
        }
        else
        {
            Session::flash('error','Incorrect old password.');
            return redirect()->back();          
        }

        Session::flash('error','Problem occured, while changing password.');
        return redirect()->back();   
    }

    public function send_request(Request $request)
    {
        $user = $this->user_auth->user();
        $id = $user->id;


        $arr_rules['req_first_name']     = "required";
        $arr_rules['req_last_name']      = "required";
        $arr_rules['req_email']          = "required";
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
           return redirect()->back()->withErrors($validator);
        }


        $obj_data  = $this->UsersModel->where('id',$id)->first();
        if($obj_data) 
        {
            $arr_user = $obj_data->toArray();
            $id = (isset($arr_user['id']) ? $arr_user['id'] : '');
            $first_name =  (isset($arr_user['first_name']) ? ucfirst($arr_user['first_name']) : '');
            $last_name  =  (isset($arr_user['last_name']) ? ucfirst($arr_user['last_name']) : '');
            $user_name  =  $first_name.' '.$last_name;
        }
        $req_first_name  =  $request->input('req_first_name');
        $req_last_name   =  $request->input('req_last_name');
        $req_email       =  $request->input('req_email');

            $ARR_NOTIFICATIOn = [];
            $ARR_NOTIFICATION_DATA['receiver_id']            = config('app.project.admin_id');;
            $ARR_NOTIFICATION_DATA['receiver_type']          = 'admin';
            $ARR_NOTIFICATION_DATA['sender_id']              = $id;
            $ARR_NOTIFICATION_DATA['sender_type']            = 'user';
            $ARR_NOTIFICATION_DATA['title']                  = 'Request for update profile fields';
            $ARR_NOTIFICATION_DATA['description']            = 'User '.$user_name.' has requested for Change of information as follows - First Name :'.$req_first_name.' , Last Name :'.$req_last_name.' , Email :'.$req_email;
            $ARR_NOTIFICATION_DATA['redirect_url']           = url('/').'/admin/users/edit/'.base64_encode($id);
            $ARR_NOTIFICATION_DATA['status']                 = 0;
            $ARR_NOTIFICATION_DATA['notification_type']      = 'general';
           $status = $this->save_notification($ARR_NOTIFICATION_DATA);
        if($status)
        {
            Session::flash('success',' Request sent');
            return redirect()->back();
        }
        else
        {
            Session::flash('error',' Problem occured, while sending Request.');
        }
    }
    public function newsletter(Request $request)
    {
        $user = $this->user_auth->user();
        $id = $user->id;
        $obj_data  = $this->UsersModel->where('id',$id)->first();
        if($obj_data)
        {
            $email = isset($obj_data['email']) ? $obj_data['email'] : 'NA';
        }
        $value = $request->input('checkbox');
        if($value != null)
        {
            $status =$this->NewsletterModel->where('email',$email)->delete();
             $resp = array('status' => 'success','msg'=> 'Unsubcribed from Newsletter');
            return response()->json($resp);
        }
        else
        {

            $arr_data = [];
            $arr_data['email'] = isset($email) ? $email :'NA';
            $status =$this->NewsletterModel->create($arr_data);
            $resp = array('status' => 'success','msg'=> 'Subscribed for Newsletter');
            return response()->json($resp);
        }
        $resp = array('status' => 'fail','msg'=> 'Something went wrong');
        return response()->json($resp);
    }
}
