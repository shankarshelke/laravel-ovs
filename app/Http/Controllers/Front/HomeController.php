<?php

namespace App\Http\Controllers\Front;

use App\Models\NewsletterModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use Session;
use DB;

class HomeController extends Controller
{
    function __construct(NewsletterModel $email)
    {
        $this->arr_view_data      = [];
        $this->module_title       = "Home";
        $this->module_view_folder = "front.";
        $this->common_url         = url('/');
        $this->NewsletterModel    = new NewsletterModel();
        $this->BaseModel          = $this->NewsletterModel;
        $this->ip_address         = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:false;  
        }

    public function index()
    {
        $this->arr_view_data['page_title']                        = 'Home';
      	return view($this->module_view_folder.'index',$this->arr_view_data);
    }
    
    
     public function store_email(Request $request)
    {
        $email = trim($request->input('subscription_email'));
        
        $arr_rules['subscription_email']="required";

        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails())
        {
            $arr_json['status'] = 'error';
            $arr_json['message'] = 'Please enter email!';
            return response()->json($arr_json);
            //return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        $does_exists = $this->BaseModel->where('email',$email)
                                        ->count();
        if($does_exists > 0)
        {
            $arr_json['status'] = 'error';
            $arr_json['message'] = 'Email already exists!!';
            return response()->json($arr_json);
        }
        $arr_data=array();

        $arr_data['email']=$email;

        $email=$this->BaseModel->create($arr_data);

        if($email)
        {

                $ARR_NOTIFICATIOn = [];
                $ARR_NOTIFICATION_DATA['receiver_id']            = config('app.project.admin_id');
                $ARR_NOTIFICATION_DATA['receiver_type']          = 'admin';
                $ARR_NOTIFICATION_DATA['sender_id']              = 0;
                $ARR_NOTIFICATION_DATA['sender_type']            = 'newsletter';
                $ARR_NOTIFICATION_DATA['title']                  = 'Newsletter';
                $ARR_NOTIFICATION_DATA['description']            = $arr_data['email'].' subscribed for Newsletter';
                $ARR_NOTIFICATION_DATA['redirect_url']           = '/admin/newsletters';
                $ARR_NOTIFICATION_DATA['status']                 = 0;
                $ARR_NOTIFICATION_DATA['notification_type']      = 'general';

                $this->save_notification($ARR_NOTIFICATION_DATA);
            $arr_json['status'] = 'success';
            $arr_json['message'] = 'SUCCESS! You have subscribed for Newsletter Successfully!!';
            return response()->json($arr_json);
        }
        else
        {
            $arr_json['status'] = 'error';
            $arr_json['message'] = 'ERROR!! something went wrong!!';
            return response()->json($arr_json);
        }
        return response()->json($arr_json);
    }
}
