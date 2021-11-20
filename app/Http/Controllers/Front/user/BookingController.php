<?php

namespace App\Http\Controllers\Front\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AircraftOwnerModel;
use App\Models\ReservationModel;
use App\Models\UsersModel;
use App\Models\AvailabilityModel;
use App\Models\ExtendRequestModel;
use App\Models\TransactionModel;
use App\Common\Services\MailService;

use Auth;
use Validator;
use Session;
use Image;
use PDF;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->arr_view_data                    = [];
        $this->module_title                     = "Bookings";
        $this->module_view_folder               = "front.user";

        $this->AircraftOwnerModel               = new AircraftOwnerModel();
        $this->AvailabilityModel                = new AvailabilityModel();
        $this->ReservationModel                 = new ReservationModel();
        $this->TransactionModel                 = new TransactionModel();
        $this->UsersModel                       = new UsersModel();

        $this->ExtendRequestModel               = new ExtendRequestModel();

        $this->user_profile_base_img_path       = base_path().config('app.project.user_profile_image');
        $this->user_profile_public_img_path     = url('/').config('app.project.user_profile_image');

        $this->operator_profile_base_img_path   = base_path().config('app.project.operator_profile_image');
        $this->operator_profile_public_img_path = url('/').config('app.project.operator_profile_image');

        $this->aircraft_image_base_img_path     = base_path().config('app.project.img_path.aircraft_image');
        $this->aircraft_image_public_img_path   = url('/').config('app.project.img_path.aircraft_image');
        
        $this->payment_receipt_to_admin_base_path   = base_path().config('app.project.payment_receipt_to_admin');
        $this->payment_receipt_to_admin_public_path = url('/').config('app.project.payment_receipt_to_admin');
        
        $this->contract_signature_base_path     = base_path().config('app.project.contract_signature');
        $this->contract_signature_public_path   = url('/').config('app.project.contract_signature');

        $this->MailService                      = new MailService();
        $this->user_auth                        = auth()->guard('users');

    }

    public function view_contract($enc_id)
    {  
        $id = base64_decode($enc_id);
        if(!is_numeric($id))
        {
            Session::flash('error','Invalid Request.');
            return redirect()->back();
        }

        $obj_booking = $this->ReservationModel->where('id', $id)
                                                ->whereHas('get_contract', function($q){})
                                                ->whereHas('get_aircraft_details', function($q){
                                                       $q->with(['get_aircraft_type']); 
                                                })
                                                ->whereHas('get_user_details', function($q){})
                                                ->whereHas('get_owner_details', function($q){})
                                                ->with('get_aircraft_details')
                                                ->with('get_contract')
                                                ->with('get_user_details')
                                                ->with('get_owner_details')
                                                ->first();

        $user_id        = isset($obj_booking['user_id']) ?  $obj_booking['user_id'] : 'NA';
        $reservation_id = isset($obj_booking['reservation_id']) ?  $obj_booking['reservation_id'] : 'NA';

        if(!$obj_booking){
            Session::flash('error','Something went wrong!');
            return redirect()->back();
        }

        $obj_transaction = $this->TransactionModel->where('pay_from','user')
                                                  ->where('pay_from_id',$user_id)
                                                  ->where('reservation_id',$reservation_id)
                                                  ->get();

        if($obj_transaction)
        {
            $arr_transaction = $obj_transaction->toArray();
        }

        $this->arr_view_data['module_title']                    = $this->module_title." Contract";
        $this->arr_view_data['page_title']                      = $this->module_title." Contract";
        $this->arr_view_data['enc_id']                          = $enc_id;
        $this->arr_view_data['obj_booking']                     = $obj_booking;
        $this->arr_view_data['arr_transaction']                 = $arr_transaction;
        $this->arr_view_data['user_profile_base_img_path']      = $this->user_profile_base_img_path;
        $this->arr_view_data['user_profile_public_img_path']    = $this->user_profile_public_img_path;
        $this->arr_view_data['operator_profile_base_img_path']  = $this->operator_profile_base_img_path;
        $this->arr_view_data['operator_profile_public_img_path']= $this->operator_profile_public_img_path;
        $this->arr_view_data['contract_signature_base_path']    = $this->contract_signature_base_path;
        $this->arr_view_data['contract_signature_public_path']  = $this->contract_signature_public_path;
        $this->arr_view_data['payment_receipt_to_admin_base_path']    = $this->payment_receipt_to_admin_base_path;
        $this->arr_view_data['payment_receipt_to_admin_public_path']  = $this->payment_receipt_to_admin_public_path;

        return view($this->module_view_folder.'.contract',$this->arr_view_data);
    }

    public function extend_contract()
    {  
        $arr_bookings = [];
        $user          = $this->user_auth->user();
        $user_id       = $user->id;

         /* Search  */
        $search = '';
        if(\Request::has('search')&& \Request::get('search')!='')
        {
            $search = \Request::get('search');
        }
        /* Search  */

        //dd(date('Y-m-d'));

        $obj_bookings  = $this->ReservationModel
                            ->where('pickup_date', '>=', date('Y-m-d'))
                            ->where('return_date', '>=', date('Y-m-d'))
                            ->with(['get_aircraft_details'=>function($q){
                                $q->with(['get_aircraft_owner']);
                                $q->with(['get_availablity1']);
                            }])
                            ->with(['get_user_details'])
                            ->with(['extend_requests'=>function($q) use ($user_id)
                            {
                                // $q->with(['reservation_details'=>function($subquery) use($user_id)
                                // {
                                //     $subquery->where('user_id',$user_id);
                                // }]);
                                // $q->where('status','PENDING');

                            }])
                            ->where('user_id',$user_id)
                            ->orderBy('created_at','DESC')
                            ->where('status','PENDING');

                            if($search!='' && isset($search))
                            {
                                $obj_bookings = $obj_bookings->whereHas('get_aircraft_details', function($q)use($search){
                                    $q->where('name', 'LIKE',"%".$search."%");
                                })
                                ->orwhere('reservation_id','LIKE', "%".$search."%");
                            }


        $obj_bookings =   $obj_bookings->paginate(5);
        if($obj_bookings)
        {
            $arr_bookings = $obj_bookings->toArray();
        }





        // dd($arr_bookings);
        $this->arr_view_data['module_title']   = " Extend Contracts";
        $this->arr_view_data['arr_bookings']   = $arr_bookings;
        $this->arr_view_data['obj_bookings']   = $obj_bookings;

       return view($this->module_view_folder.'.extend_contract',$this->arr_view_data);
    }

    public function request_extend_contract(Request $request, $enc_id)
    {
        $id = base64_decode($enc_id);
        $user          = $this->user_auth->user();
        $user_id       = $user->id;

        $first_name = isset($user->first_name) ? $user->first_name:'';
        $last_name  = isset($user->last_name) ? $user->last_name:'';

        $arr_rules                      = [];
        $arr_rules['extended_date']     = "required";

        $validator = validator::make($request->all(),$arr_rules);
        if($validator->fails())
        {   
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $reservation_id     = $id;
        $date_to_extent     = $request->input('extended_date', null);

        $obj_booking    = $this->ReservationModel->where('id', $reservation_id)->first();

        if(!$obj_booking){
            Session::flash('error',' Something went wrong, please try again.');
            return redirect()->back();
        }

        if( strtotime($date_to_extent) <= strtotime($obj_booking->return_date) ){
            Session::flash('error',' Requested date should be greater than return date.');
            return redirect()->back();
        }

        $pickup_date        = $obj_booking->pickup_date;
        $return_date        = $date_to_extent;
        $aircraft_id        = $obj_booking->aircraft_id;

        $is_valid_availability = $this->AvailabilityModel->where('aircraft_id', $aircraft_id)
                                                        ->where(function($q) use($pickup_date){
                                                            $q->whereRaw("'$pickup_date' >= Date(from_date)");
                                                            $q->whereRaw("'$pickup_date' <= Date(to_date)");
                                                        })
                                                        ->where(function($q) use($return_date){
                                                            $q->whereRaw("'$return_date' >= Date(from_date)");
                                                            $q->whereRaw("'$return_date' <= Date(to_date)");
                                                        })
                                                        ->count();
        if(!$is_valid_availability > 0){
            Session::flash('error',' Invalid Date Selection. Please select the dates within available dates.');
            return redirect()->back();
        }

        $arr_data['extended_date']  = $date_to_extent;
        $arr_data['reservation_id'] = $reservation_id;
        $reservation_enc_id         = $request->input('reservation_enc_id');

        $success = $this->ExtendRequestModel->create($arr_data);

        if($success)
        {
            /*
            |
            |Send notification
            |
            */
            $username = $first_name.' '.$last_name;

            $ARR_NOTIFICATIOn = [];
            $ARR_NOTIFICATION_DATA['receiver_id']            = config('app.project.admin_id');
            $ARR_NOTIFICATION_DATA['receiver_type']          = 'admin';
            $ARR_NOTIFICATION_DATA['sender_id']              = $user_id;
            $ARR_NOTIFICATION_DATA['sender_type']            = 'user';
            $ARR_NOTIFICATION_DATA['title']                  = 'Contract Extend Request Received';
            $ARR_NOTIFICATION_DATA['description']            = 'Contract Extend Request Received for reservation : #'.$reservation_enc_id.' from '.$username;
            $ARR_NOTIFICATION_DATA['redirect_url']           = '/admin/extend_request';
            $ARR_NOTIFICATION_DATA['status']                 = 0;
            $ARR_NOTIFICATION_DATA['notification_type']      = 'reservation';
            $this->save_notification($ARR_NOTIFICATION_DATA);

            Session::flash('success',' Extend request sent successfully.');
            return redirect()->back();
        }
        else
        {
            Session::flash('error',' Something went wrong please try again.');
            return redirect()->back();       
        }
    }

    public function extend_contract_payment(Request $request)
    {
        $user          = $this->user_auth->user();
        $user_id       = $user->id;

        $id     = $request->input('reservation_id_for_extend_payment');
        $amount = $request->input('amount');
        $transaction_id = $request->input('transaction');

        $arr_rules                          = [];
        $arr_rules['payment_sleep']         = "required";
        $arr_rules['amount']                = "required";
        $arr_rules['transaction']           = "required";


        
        $payment_sleep_name =  '';

        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        if($request->hasFile('payment_sleep'))
        {
            $file_name = $request->input('payment_sleep');
            $file_extension = strtolower($request->file('payment_sleep')->getClientOriginalExtension());
            if(in_array($file_extension,['pdf','doc','docx','png','jpg','jpeg']))
            {
                $file_name = sha1(uniqid().$file_name.uniqid()).'.'.$file_extension;
                $isUpload = $request->file('payment_sleep')->move($this->payment_receipt_to_admin_base_path , $file_name);
                if($isUpload)
                {
                    $payment_sleep_name = $file_name;
                }else{
                    Session::flash('error',' Error while uploading payment sleep.');
                    return redirect()->back();
                }
            }
            else
            {
                Session::flash('error',' Invalid File type of payment sleep.');
                return redirect()->back();
            }
        }
        else
        {
            Session::flash('error', 'Please upload payment sleep to proceed.');
            return redirect()->back();
        }




        $arr_data = [];
        $arr_data['payment_sleep']  = $payment_sleep_name;
        $arr_data['amount']         = $amount;
        $arr_data['payment_status'] = 'SUBMITTED';
       
        $isUpdated = $this->ExtendRequestModel->where('reservation_id', $id)->update($arr_data);

        if($isUpdated)
        {
            $obj_booking = $this->ExtendRequestModel->where('reservation_id', $id)->first();

            $user_name = '';
            $user_name = isset($obj_booking->reservation_details->get_user_details->first_name) ? $obj_booking->reservation_details->get_user_details->first_name.'&nbsp;' : '';
            $user_name .= isset($obj_booking->reservation_details->get_user_details->last_name) ? $obj_booking->reservation_details->get_user_details->last_name : '';

            $reservation_id = isset($obj_booking->reservation_details->reservation_id) ? $obj_booking->reservation_details->reservation_id : '';

        $arr_data_pay['pay_to']                = 'admin';
        $arr_data_pay['pay_to_id']             = config('app.project.admin_id');
        $arr_data_pay['requested_amount']      = isset($amount) ? $amount : ''; 
        $arr_data_pay['paid_amount']           = isset($amount) ? $amount : '';
        $arr_data_pay['pay_from']              = 'user';
        $arr_data_pay['pay_from_id']           = $user_id;
        $arr_data_pay['pay_slip']              = isset($payment_sleep_name) ? $payment_sleep_name : '';
        $arr_data_pay['transaction_id']        = $transaction_id;
        $arr_data_pay['note']                  = isset($note) ? $note : '';
        $arr_data_pay['status']                = 'APPROVED';
        $arr_data_pay['reservation_id']        = isset($reservation_id) ? $reservation_id : '';


        $status = $this->TransactionModel->create($arr_data_pay);
        

            
            /*
            |
            |Send notification to Amdin
            |
            */

            $ARR_NOTIFICATIOn = [];
            $ARR_NOTIFICATION_DATA['receiver_id']            = config('app.project.admin_id');
            $ARR_NOTIFICATION_DATA['receiver_type']          = 'admin';
            $ARR_NOTIFICATION_DATA['sender_id']              = $user_id;
            $ARR_NOTIFICATION_DATA['sender_type']            = 'user';
            $ARR_NOTIFICATION_DATA['title']                  = 'Extend Request payment received by '.$user_name;
            $ARR_NOTIFICATION_DATA['description']            = 'Extend request payment received for reservation : #'.$reservation_id.', Please confirm it.';
            $ARR_NOTIFICATION_DATA['redirect_url']           = config('app.project.admin_panel_slug').'/extend_request/view/'.base64_encode($obj_booking->id);
            $ARR_NOTIFICATION_DATA['status']                 = 0;
            $ARR_NOTIFICATION_DATA['notification_type']      = 'transaction';
            $this->save_notification($ARR_NOTIFICATION_DATA);

            Session::flash('success',' Payment receipt uploaded successfully.');
            return redirect()->back();
        }
        else
        {
            Session::flash('error',' Error while updating data.');
            return redirect()->back();
        }
    }

    public function submit_contract(Request $request, $enc_id)
    {
        $id = base64_decode($enc_id);
        $user          = $this->user_auth->user();
        $user_id       = $user->id;

        if(!is_numeric($id)){
            Session::flash('error','Invalid Request!');
            return redirect()->back();
        }

        $arr_rules                          = [];
        //$arr_rules['payment_sleep']         = "required";
        $arr_rules['signature_file']        = "required";

        $payment_sleep_name = $signature_file_name = '';

        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails())
        {
            \Session::put('contract_modal','show');
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $obj_booking = $this->ReservationModel->where('id', $id)->first();
        if($obj_booking)
        {
            $arr_booking        = $obj_booking->toArray();
            $final_amount       = isset($arr_booking['final_amount']) ? $arr_booking['final_amount'] : 'NA';
            $booking_owner_id   = isset($arr_booking['owner_id']) ? $arr_booking['owner_id'] : 'NA';
            $booking_user_id    = isset($arr_booking['user_id']) ? $arr_booking['user_id'] : 'NA';
            $reservation_id     = isset($arr_booking['reservation_id']) ? $arr_booking['reservation_id'] : 'NA';
        }

        if(!$obj_booking){
            Session::flash('error','Something went wrong!');
            return redirect()->back();
        }

        $today = date('Y-m-d');

        if(strtotime($today) >= strtotime($obj_booking->pickup_date)){
            Session::flash('error','Sorry, The date has been passed to sign contract!');
            return redirect()->back();
        }

        /*if($request->hasFile('payment_sleep'))
        {
            $file_name = $request->input('payment_sleep');
            $file_extension = strtolower($request->file('payment_sleep')->getClientOriginalExtension());
            if(in_array($file_extension,['pdf','png','jpg','jpeg']))
            {
                $file_name = sha1(uniqid().$file_name.uniqid()).'.'.$file_extension;
                $isUpload = $request->file('payment_sleep')->move($this->payment_receipt_to_admin_base_path , $file_name);
                if($isUpload)
                {
                    $payment_sleep_name = $file_name;
                }else{
                    Session::flash('error',' Error while uploading payment sleep.');
                    return redirect()->back();
                }
            }
            else
            {
                Session::flash('error',' Invalid File type of payment sleep.');
                return redirect()->back();
            }
        }else{
            Session::flash('error', 'Please upload payment sleep to proceed.');
            return redirect()->back();
        }*/

        if($request->has('signature_file'))
        {
            $input_img      = $request->input('signature_file');
            $file_extension = explode('/',explode(';', $input_img)[0])[1];
            $filename       = sha1(uniqid().uniqid()) . '.' . $file_extension;

            if(in_array($file_extension,['pdf','png','jpg','jpeg']))
            {
                $image_file = $input_img;
                $image1 = Image::make($image_file);
                $isUpload = $image1->save($this->contract_signature_base_path.$filename);

                if($isUpload)
                {
                    $signature_file_name = $filename;
                }else{
                    Session::flash('error','Error while uploading Signature file.');
                    return redirect()->back();
                }
            }
            else
            {
                Session::flash('error','Invalid File type of Signature file.');
                return redirect()->back();
            }
        }else{
            Session::flash('error',' Please draw Signature to proceed.');
            return redirect()->back();
        }

        $arr_data = $arr_transaction = [];

        $arr_data['signature']      = $signature_file_name;
        $arr_data['payment_sleep']  = $payment_sleep_name;
        $arr_data['is_signed']      = '1';
        $arr_data['payment_status'] = 'PENDING';

        $arr_transaction['pay_to']          =  'admin';
        $arr_transaction['pay_to_id']       =  config('app.project.admin_id');
        $arr_transaction['requested_amount']=  $final_amount;
        $arr_transaction['paid_amount']     =  0;
        $arr_transaction['pay_from']        =  'user';
        $arr_transaction['pay_from_id']     =  $booking_user_id;
        //$arr_transaction['pay_slip']        =  $payment_sleep_name;
        //$arr_transaction['transaction_id']  =  "TR".mt_rand();
        $arr_transaction['received_status'] =  'pending';
        $arr_transaction['reservation_id']  =  $reservation_id;
        $arr_transaction['status']          = 'REQUESTED';

        $transaction = $this->TransactionModel->create($arr_transaction);

        $isUpdated = $this->ReservationModel->where('id', $id)->update($arr_data);

        if($isUpdated)
        {
            $user_name = '';
            $user_name = isset($obj_booking->get_user_details->first_name) ? $obj_booking->get_user_details->first_name.'&nbsp;' : '';
            $user_name .= isset($obj_booking->get_user_details->last_name) ? $obj_booking->get_user_details->last_name : 
            /*
            |
            |Send notification to Amdin
            |
            */

            $ARR_NOTIFICATIOn = [];
            $ARR_NOTIFICATION_DATA['receiver_id']            = config('app.project.admin_id');
            $ARR_NOTIFICATION_DATA['receiver_type']          = 'admin';
            $ARR_NOTIFICATION_DATA['sender_id']              = $user_id;
            $ARR_NOTIFICATION_DATA['sender_type']            = 'user';
            $ARR_NOTIFICATION_DATA['title']                  = 'Contract signed by '.$user_name;
            $ARR_NOTIFICATION_DATA['description']            = 'Charter has signed contract : '.$obj_booking->reservation_id.', Please confirm it.';
            $ARR_NOTIFICATION_DATA['redirect_url']           = config('app.project.admin_panel_slug').'/reservation/view/'.base64_encode($obj_booking->id);
            $ARR_NOTIFICATION_DATA['status']                 = 0;
            $ARR_NOTIFICATION_DATA['notification_type']      = 'reservation';
            $this->save_notification($ARR_NOTIFICATION_DATA);

            /*
            |
            |Send notification to Operator
            |
            */

            $ARR_NOTIFICATIOn = [];
            $ARR_NOTIFICATION_DATA['receiver_id']            = $obj_booking->owner_id;
            $ARR_NOTIFICATION_DATA['receiver_type']          = 'aircraft_owner';
            $ARR_NOTIFICATION_DATA['sender_id']              = config('app.project.admin_id');
            $ARR_NOTIFICATION_DATA['sender_type']            = 'admin';
            $ARR_NOTIFICATION_DATA['title']                  = 'Contract signed by User';
            $ARR_NOTIFICATION_DATA['description']            = 'Charter has signed contract : '.$obj_booking->reservation_id.', Please confirm it.';
            $ARR_NOTIFICATION_DATA['redirect_url']           = '/operator/view_contract/'.base64_encode($obj_booking->id);
            $ARR_NOTIFICATION_DATA['status']                 = 0;
            $ARR_NOTIFICATION_DATA['notification_type']      = 'reservation';
            $this->save_notification($ARR_NOTIFICATION_DATA);

            /*
            |
            |Send notification to User / Charter
            |
            */

            $ARR_NOTIFICATIOn = [];
            $ARR_NOTIFICATION_DATA['receiver_id']            = $user_id;
            $ARR_NOTIFICATION_DATA['receiver_type']          = 'user';
            $ARR_NOTIFICATION_DATA['sender_id']              = config('app.project.admin_id');
            $ARR_NOTIFICATION_DATA['sender_type']            = 'admin';
            $ARR_NOTIFICATION_DATA['title']                  = 'Contract signed successfully';
            $ARR_NOTIFICATION_DATA['description']            = 'You have successfully signed contract : '.$obj_booking->reservation_id;
            $ARR_NOTIFICATION_DATA['redirect_url']           = '/user/view_contract/'.base64_encode($obj_booking->id);
            $ARR_NOTIFICATION_DATA['status']                 = 0;
            $ARR_NOTIFICATION_DATA['notification_type']      = 'reservation';
            $this->save_notification($ARR_NOTIFICATION_DATA);

            Session::flash('success',' You have successfully signed contract.');
            return redirect()->back();
        }else{
            Session::flash('error',' Error while updating data.');
            return redirect()->back();
        }

    }

    public function submit_new_paysleep(Request $request, $enc_id)
    {
        $id = base64_decode($enc_id);
        $user          = $this->user_auth->user();
        $user_id       = $user->id;

        if(!is_numeric($id)){
            Session::flash('error','Invalid Request!');
            return redirect()->back();
        }

        $arr_rules                  = [];
        $arr_rules['pay_sleep']     = "required";

        $payment_sleep_name = '';

        $obj_booking = $this->ReservationModel->where('id', $id)->first();

        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        if($request->hasFile('pay_sleep'))
        {
            $file_name = $request->input('pay_sleep');
            $file_extension = strtolower($request->file('pay_sleep')->getClientOriginalExtension());
            
            if(in_array($file_extension,['pdf','jpg','png','jpeg']))
            {
                $file_name = sha1(uniqid().$file_name.uniqid()).'.'.$file_extension;
                $isUpload = $request->file('pay_sleep')->move($this->payment_receipt_to_admin_base_path , $file_name);
                if($isUpload)
                {
                    $old_file = $this->payment_receipt_to_admin_base_path.$obj_booking->payment_sleep;
                    if(file_exists($old_file)){
                        unlink($old_file);
                    }
                    $payment_sleep_name = $file_name;
                }else{
                    Session::flash('error',' Error while uploading payment sleep.');
                    return redirect()->back();
                }
            }
            else
            {
                Session::flash('error',' Invalid File type of payment sleep.');
                return redirect()->back();
            }
        }else{
            Session::flash('error', 'Please upload payment sleep to proceed.');
            return redirect()->back();
        }

        $arr_data = [];
        $arr_data['payment_sleep']  = $payment_sleep_name;
        $arr_data['payment_status'] = 'SUBMITTED';

        $isUpdated = $this->ReservationModel->where('id', $id)->update($arr_data);

        if($isUpdated)
        {
            $user_name = '';
            $user_name = isset($obj_booking->get_user_details->first_name) ? $obj_booking->get_user_details->first_name.'&nbsp;' : '';
            $user_name .= isset($obj_booking->get_user_details->last_name) ? $obj_booking->get_user_details->last_name : 
            /*
            |
            |Send notification to Amdin
            |
            */

            $ARR_NOTIFICATIOn = [];
            $ARR_NOTIFICATION_DATA['receiver_id']            = config('app.project.admin_id');
            $ARR_NOTIFICATION_DATA['receiver_type']          = 'admin';
            $ARR_NOTIFICATION_DATA['sender_id']              = $user_id;
            $ARR_NOTIFICATION_DATA['sender_type']            = 'user';
            $ARR_NOTIFICATION_DATA['title']                  = 'Payment sleep uploaded by '.$user_name;
            $ARR_NOTIFICATION_DATA['description']            = 'Payment sleep re-uploaded for contract : #'.$obj_booking->reservation_id.', Please confirm it.';
            $ARR_NOTIFICATION_DATA['redirect_url']           = '/'.config('app.project.admin_panel_slug').'/reservation/view/'.base64_encode($obj_booking->id);
            $ARR_NOTIFICATION_DATA['status']                 = 0;
            $ARR_NOTIFICATION_DATA['notification_type']      = 'transaction';
            $this->save_notification($ARR_NOTIFICATION_DATA);

            /*
            |
            |Send notification to User / Charter
            |
            */

            $ARR_NOTIFICATIOn = [];
            $ARR_NOTIFICATION_DATA['receiver_id']            = $user_id;
            $ARR_NOTIFICATION_DATA['receiver_type']          = 'user';
            $ARR_NOTIFICATION_DATA['sender_id']              = config('app.project.admin_id');
            $ARR_NOTIFICATION_DATA['sender_type']            = 'admin';
            $ARR_NOTIFICATION_DATA['title']                  = 'Payment sleep uploaded successfully';
            $ARR_NOTIFICATION_DATA['description']            = 'You have successfully uploaded payment sleep for contract : #'.$obj_booking->reservation_id;
            $ARR_NOTIFICATION_DATA['redirect_url']           = '/user/view_contract/'.base64_encode($obj_booking->id);
            $ARR_NOTIFICATION_DATA['status']                 = 0;
            $ARR_NOTIFICATION_DATA['notification_type']      = 'transaction';
            $this->save_notification($ARR_NOTIFICATION_DATA);

            Session::flash('success',' You have successfully uploaded payment sleep.');
            return redirect()->back();
        }else{
            Session::flash('error',' Error while updating data.');
            return redirect()->back();
        }
    }

    public function cancel_book_req($enc_id)
    {  
        $id = base64_decode($enc_id);
        $user          = $this->user_auth->user();
        $user_id       = $user->id;

        if(!is_numeric($id)){
            Session::flash('error','Invalid Request!');
            return redirect()->back();
        }
        $obj_booking = $this->ReservationModel->where('id', $id)->first();

        $isUpdated = $this->ReservationModel->where('id', $id)->update(['cancellation_status'=>'REQUESTED']);

        if($isUpdated)
        {
            $user_name = '';
            $user_name = isset($obj_booking->get_user_details->first_name) ? $obj_booking->get_user_details->first_name.'&nbsp;' : '';
            $user_name .= isset($obj_booking->get_user_details->last_name) ? $obj_booking->get_user_details->last_name : '' ;
            /*
            |
            |Send notification to Amdin
            |
            */

            $ARR_NOTIFICATIOn = [];
            $ARR_NOTIFICATION_DATA['receiver_id']            = config('app.project.admin_id');
            $ARR_NOTIFICATION_DATA['receiver_type']          = 'admin';
            $ARR_NOTIFICATION_DATA['sender_id']              = $user_id;
            $ARR_NOTIFICATION_DATA['sender_type']            = 'user';
            $ARR_NOTIFICATION_DATA['title']                  = 'Reservation cancellation request by '.$user_name;
            $ARR_NOTIFICATION_DATA['description']            = 'Reservation cancellation request for contract : #'.$obj_booking->reservation_id.', By User : '.$user_name.', Please respond to it.';
            $ARR_NOTIFICATION_DATA['redirect_url']           = '/'.config('app.project.admin_panel_slug').'/reservation/view/'.base64_encode($obj_booking->id);
            $ARR_NOTIFICATION_DATA['status']                 = 0;
            $ARR_NOTIFICATION_DATA['notification_type']      = 'reservation';
            $this->save_notification($ARR_NOTIFICATION_DATA);
            Session::flash('success',' Request to cancel booking has been sent successfully!');
            return redirect()->back();
        }else{
            Session::flash('error','Error while updating!');
            return redirect()->back();
        }
    }

    public function download_contract($enc_id){
        $id = base64_decode($enc_id);

        if(!is_numeric($id))
        {
            Session::flash('error','Invalid Request.');
            return redirect()->back();
        }

        $obj_booking = $this->ReservationModel->where('id', $id)
                                                ->whereHas('get_contract', function($q){})
                                                ->whereHas('get_aircraft_details', function($q){
                                                    $q->with(['get_aircraft_type']);
                                                })
                                                ->whereHas('get_user_details', function($q){})
                                                ->whereHas('get_owner_details', function($q){})
                                                ->with('get_aircraft_details')
                                                ->with('get_contract')
                                                ->with('get_user_details')
                                                ->with('get_owner_details')
                                                ->first();

        if(!$obj_booking){
            Session::flash('error','Something went wrong!');
            return redirect()->back();
        }

        $this->arr_view_data['obj_booking'] = $obj_booking;
        $this->arr_view_data['user_profile_base_img_path']      = $this->user_profile_base_img_path;
        $this->arr_view_data['user_profile_public_img_path']    = $this->user_profile_public_img_path;
        $this->arr_view_data['operator_profile_base_img_path']  = $this->operator_profile_base_img_path;
        $this->arr_view_data['operator_profile_public_img_path']= $this->operator_profile_public_img_path;
        $this->arr_view_data['contract_signature_base_path']    = $this->contract_signature_base_path;
        $this->arr_view_data['contract_signature_public_path']  = $this->contract_signature_public_path;
        $this->arr_view_data['payment_receipt_to_admin_base_path']    = $this->payment_receipt_to_admin_base_path;
        $this->arr_view_data['payment_receipt_to_admin_public_path']  = $this->payment_receipt_to_admin_public_path;

        return view($this->module_view_folder.'.download_contract',$this->arr_view_data);

        $pdf = PDF::loadView($this->module_view_folder.'.download_contract', $this->arr_view_data);
        return $pdf->download($this->module_view_folder.'.pdf', $this->arr_view_data);
    }

}