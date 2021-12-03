<?php

namespace App\Http\Controllers\Front\operator;

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
use Image;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->arr_view_data            = [];
        $this->module_title             = "Operator";
        $this->module_view_folder       = "front.operator";
        $this->AircraftOwnerModel       = new AircraftOwnerModel();
        $this->NewsletterModel          = new NewsletterModel();
        $this->UsersModel               = new UsersModel();
        $this->user_profile_base_img_path   = base_path().config('app.project.operator_profile_image');
        $this->user_profile_public_img_path = url('/').config('app.project.operator_profile_image');
        $this->aircraft_experience_base_img_path   = base_path().config('app.project.user_experience_certificates');
        $this->aircraft_experience_public_img_path = url('/').config('app.project.user_experience_certificates');
        $this->MailService              = new MailService();
        $this->module_url_path          = "signup_user";
        $this->operator_auth            = auth()->guard('operator');
    }

    public function profile()
    {   
        $user =$this->operator_auth->user();
        $owner_id =  $user->owner_id;
        $obj_data  = $this->AircraftOwnerModel->where('owner_id',$owner_id)->first();

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
        return view($this->module_view_folder.'.operator_profile',$this->arr_view_data);
    }

    public function update_operator(Request $request)
    {
        
        $user =$this->operator_auth->user();
        $owner_id =  $user->owner_id;
       //dd($request->all());
        if($owner_id)
        {
            $operator_data = $this->AircraftOwnerModel->where('owner_id',$owner_id)->first();
            if($operator_data) 
            {
                $arr_operator = $operator_data->toArray();
                $id = (isset($arr_operator['id']) ? $arr_operator['id'] : '');
                $first_name =  (isset($arr_operator['first_name']) ? ucfirst($arr_operator['first_name']) : '');
                $last_name  =  (isset($arr_operator['last_name']) ? ucfirst($arr_operator['last_name']) : '');
                $full_name  =  $first_name.' '.$last_name;
            }

        }
        $arr_rules = [];
        
        $arr_rules['first_name']            = "required";
        $arr_rules['last_name']             = "required";
        $arr_rules['company_name']          = "required";
        $arr_rules['contact']               = "required";
        $arr_rules['address']               = "required";
        $arr_rules['latitude']              = "required";
        $arr_rules['longitude']             = "required";
        $arr_rules['user_name']             = "required";
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
        $user_name  = $request->user_name;
        //$email      = $request->email;
        $company_name = $request->company_name;
        $old_image  = $request->input('oldimage');
        if($request->hasFile('profile_image'))
        {
            $file_name = $request->input('profile_image');
            $file      = $request->file('profile_image');
            $file_extension = strtolower($request->file('profile_image')->getClientOriginalExtension());
            if(in_array($file_extension,['jpg','jpeg','png']))
            {
                $file_name = sha1(uniqid().$file_name.uniqid()).'.'.$file_extension;
                $image1    = Image::make($file)->resize(40,40);
                $isUpload = $request->file('profile_image')->move($this->user_profile_base_img_path , $file_name);
                $destination_path1  = 'uploads/aircraft_owner/profile_image/thumb_40x40/';
                $image1->save($destination_path1.$file_name);
                if($isUpload)
                {
                    if ($old_image!="" && $old_image!=null) 
                    {
                        if (file_exists($this->user_profile_base_img_path.$old_image))
                        {
                            @unlink($this->user_profile_base_img_path.$old_image);
                        }

                        if (file_exists($this->user_profile_base_img_path.'/thumb_50X50_'.$old_image)) 
                        {
                            @unlink($this->user_profile_base_img_path.'/thumb_50X50_'.$old_image);
                        }
                        
                    }
                }
            }
            else
            {
                Session::flash('error','Invalid File type, While creating '.str_singular($this->module_title));
                return redirect()->back();
            }
        }
        else
        {
           $file_name=$old_image;
       }

       $old_file  = $request->input('oldfile');
       if($request->hasFile('file'))
        {
            $certificate_name = $request->input('file');
            $file_extension = strtolower($request->file('file')->getClientOriginalExtension());
            if(in_array($file_extension,['pdf','docx']))
            {
                $certificate_name = sha1(uniqid().$certificate_name.uniqid()).'.'.$file_extension;
                $isUpload = $request->file('file')->move($this->aircraft_experience_base_img_path , $certificate_name);
            }
            else
            {
                $status     = 'fail';
                $customMsg = 'Invalid File type, While creating '.str_singular($this->module_title);

                $resp = array('status' => $status,'errors'=>$errors,'customMsg'=> $customMsg);
                return response()->json($resp);

                Session::flash('error','Invalid File type, While creating '.str_singular($this->module_title));
                return redirect()->back();
            }
        }
        else
        {
            $certificate_name = $old_file;
        }

       $obj_data  = $this->AircraftOwnerModel->where('owner_id',$owner_id)->first();
       if(isset($obj_data) && $obj_data != 'null') 
       {
        $arr_data['first_name']            = $first_name;
        $arr_data['last_name']             = $last_name; 
        $arr_data['company_name']          = $company_name; 
        $arr_data['contact']               = $contact;
        $arr_data['address']               = $address;
        $arr_data['latitude']              = $latitude;
        $arr_data['longitude']             = $longitude;
        $arr_data['profile_image']         = $file_name; 
        $arr_data['user_name']             = $user_name;  
        $arr_data['experience']            = $certificate_name;  


        $obj_update = $this->AircraftOwnerModel->where('owner_id',$owner_id)->update($arr_data);
        if($obj_update)
        {
            $ARR_NOTIFICATIOn = [];
            $ARR_NOTIFICATION_DATA['receiver_id']            = $id;
            $ARR_NOTIFICATION_DATA['receiver_type']          = 'aircraft_owner';
            $ARR_NOTIFICATION_DATA['sender_id']              = config('app.project.admin_id');
            $ARR_NOTIFICATION_DATA['sender_type']            = 'admin';
            $ARR_NOTIFICATION_DATA['title']                  = 'Operator Profile Update';
            $ARR_NOTIFICATION_DATA['description']            = 'Hello '.$full_name.' Your Profile has been updated successfully.';
            $ARR_NOTIFICATION_DATA['redirect_url']           = '';
            $ARR_NOTIFICATION_DATA['notification_type']      = 'general';
            $ARR_NOTIFICATION_DATA['status']                 = 0;
            $this->save_notification($ARR_NOTIFICATION_DATA);

            Session::flash('success','Operators updated successfully.');
            return redirect()->back();    
        }
    }
    Session::flash('error','Something went wrong');
    return redirect()->back();
    
}

public function save_password(Request $request)
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

    $user = $this->operator_auth->user();
    $owner_id =  $user->owner_id;
       //dd($request->all());
        if($owner_id)
        {
            $operator_data = $this->AircraftOwnerModel->where('owner_id',$owner_id)->first();
            if($operator_data) 
            {
                $arr_operator = $operator_data->toArray();
                $id = (isset($arr_operator['id']) ? $arr_operator['id'] : '');
                $first_name =  (isset($arr_operator['first_name']) ? ucfirst($arr_operator['first_name']) : '');
                $last_name  =  (isset($arr_operator['last_name']) ? ucfirst($arr_operator['last_name']) : '');
                $full_name  =  $first_name.' '.$last_name;
            }

        }
    if(Hash::check($old_password,$this->operator_auth->user()->password))
    {
        if($old_password!=$new_password)
        {
            if($new_password == $confirm_password)
            {
                $status = $this->AircraftOwnerModel->where('owner_id',$owner_id)->update(['password' => bcrypt($new_password)]);

                if($status)
                {
                    $ARR_NOTIFICATIOn = [];
                    $ARR_NOTIFICATION_DATA['receiver_id']            = $id;
                    $ARR_NOTIFICATION_DATA['receiver_type']          = 'aircraft_owner';
                    $ARR_NOTIFICATION_DATA['sender_id']              = config('app.project.admin_id');
                    $ARR_NOTIFICATION_DATA['sender_type']            = 'admin';
                    $ARR_NOTIFICATION_DATA['title']                  = 'Operator Password Update';
                    $ARR_NOTIFICATION_DATA['description']            = 'Hello '.$full_name.' Your Password has been updated successfully.';
                    $ARR_NOTIFICATION_DATA['redirect_url']           = '';
                    $ARR_NOTIFICATION_DATA['notification_type']      = 'general';

                    $ARR_NOTIFICATION_DATA['status']                 = 0;
                    $this->save_notification($ARR_NOTIFICATION_DATA);
                    Session::flash('success',' Your Password Changed Successfully.');

                    $login_user_type = 'operator';
                    \Session::put('login_user_type',$login_user_type);

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
        $user = $this->operator_auth->user();
        $id =  $user->id;

        $arr_rules['req_first_name']     = "required";
        $arr_rules['req_last_name']      = "required";
        $arr_rules['req_email']          = "required";
        $arr_rules['req_company_name']   = "required";
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {
           return redirect()->back()->withErrors($validator);
        }


        $obj_data  = $this->AircraftOwnerModel->where('id',$id)->first();
        if($obj_data) 
        {
            $arr_user = $obj_data->toArray();
            $id = (isset($arr_user['id']) ? $arr_user['id'] : '');
            $first_name =  (isset($arr_user['first_name']) ? ucfirst($arr_user['first_name']) : '');
            $last_name  =  (isset($arr_user['last_name']) ? ucfirst($arr_user['last_name']) : '');
            $user_name  =  $first_name.' '.$last_name;
        }
        $req_first_name   =  $request->input('req_first_name');
        $req_last_name    =  $request->input('req_last_name');
        $req_email        =  $request->input('req_email');
        $req_company_name =  $request->input('req_company_name');

            $ARR_NOTIFICATIOn = [];
            $ARR_NOTIFICATION_DATA['receiver_id']            = config('app.project.admin_id');;
            $ARR_NOTIFICATION_DATA['receiver_type']          = 'admin';
            $ARR_NOTIFICATION_DATA['sender_id']              = $id;
            $ARR_NOTIFICATION_DATA['sender_type']            = 'aircraft_owner';
            $ARR_NOTIFICATION_DATA['title']                  = 'Request for update profile fields';
            $ARR_NOTIFICATION_DATA['description']            = 'Operator '.$user_name.' has requested for Change of information as follows - First Name :'.$req_first_name.' , Last Name :'.$req_last_name.' , Email :'.$req_email.' , Comany\'s Name :'.$req_company_name;
            $ARR_NOTIFICATION_DATA['redirect_url']           = url('/').'/admin/aircraft_owner/edit/'.base64_encode($id);
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
        $user = $this->operator_auth->user();
        $id =  $user->id;
        $obj_data  = $this->AircraftOwnerModel->where('id',$id)->first();
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
