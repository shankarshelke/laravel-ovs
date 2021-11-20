<?php

namespace App\Http\Controllers\Front\operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AircraftOwnerModel;
use App\Models\ReservationModel;
use App\Models\UsersModel;
use App\Models\AvailabilityModel;
use App\Models\ExtendRequestModel;
use App\Models\TransactionModel;
use App\Common\Traits\MultiActionTrait;
use App\Common\Services\MailService;

use Auth;
use Validator;
use Session;
use Image;
use PDF;

class TransactionController extends Controller
{
    use MultiActionTrait;
    public function __construct()
    {
        $this->arr_view_data                    = [];
        $this->module_title                     = "Bookings";
        $this->module_view_folder               = "front.operator";

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
        $this->operator_auth            = auth()->guard('operator');

    }

    public function transactions()
    {  
        /* Search  */
        $search = '';
        if(\Request::has('search')&& \Request::get('search')!='')
        {
            $search = \Request::get('search');
        }
        /* Search  */
        $user       = $this->operator_auth->user();
        $owner_id   =  $user->owner_id;
        $id         = $user->id;
        $obj_data   = $this->AircraftOwnerModel->where('id',$id)->first();

        if($obj_data){
            $arr_data = $obj_data->toArray();
        }


        $obj_transaction = $this->TransactionModel->where('pay_to','operator')
                                                  ->where('pay_to_id',$id);

        if($search!='' && isset($search))
        {
              /*$obj_transaction->whereRaw(" transaction_id  like '%".$search."%'");*/
              $obj_transaction = $obj_transaction->where('transaction_id', 'LIKE',"%".$search."%");
          } 

        $obj_transaction = $obj_transaction->orderBy('created_at','desc')
                                           ->paginate(15);

        if($obj_transaction)
        {
            $arr_transaction = $obj_transaction->toArray();
            $page_link       = $obj_transaction->links(); 
        }

        $obj_reservations = $this->ReservationModel->where('owner_id',$id)->get();
        if($obj_reservations)
        {
            $arr_reservations = $obj_reservations->toArray();
        }   

        $obj_temp_amount = $this->ReservationModel->where('owner_id',$id)
                                             ->whereIn('status',['PENDING','COMPLETED','CANCELLED'])      
                                             ->sum('final_amount');

        $obj_extend_amount=  $this->ExtendRequestModel->whereHas('reservation_details', function($q)use($id){
                                                    $q->where('owner_id',$id);
                                                })
                                                ->with(['reservation_details'])
                                                ->where('status','APPROVED')           
                                                ->sum('amount');
        $obj_amount = ($obj_extend_amount + $obj_temp_amount);                                     

        $obj_paid_amount  = $this->TransactionModel->where('pay_to','operator')
                                                   ->where('pay_to_id',$id)
                                                   ->where('status','APPROVED')
                                                   ->sum('paid_amount');

        $obj_commission  = $this->ReservationModel->where('owner_id',$id)
                                                  ->sum('commission_amount');

        $obj_final_amount = ($obj_amount - $obj_commission);

        $pending_amount   = ($obj_final_amount - $obj_paid_amount );


        $this->arr_view_data['module_title']                    = 'Transactions';
        $this->arr_view_data['page_title']                      = 'Transactions';
        $this->arr_view_data['arr_transaction']                 = $arr_transaction;
        $this->arr_view_data['arr_reservations']                = $arr_reservations;
        $this->arr_view_data['obj_final_amount']                = $obj_final_amount;
        $this->arr_view_data['obj_paid_amount']                 = $obj_paid_amount;
        $this->arr_view_data['obj_commission']                  = $obj_commission;
        $this->arr_view_data['pending_amount']                  = $pending_amount;
        $this->arr_view_data['page_link']                       = $page_link;
        $this->arr_view_data['user_profile_base_img_path']      = $this->user_profile_base_img_path;
        $this->arr_view_data['user_profile_public_img_path']    = $this->user_profile_public_img_path;
        $this->arr_view_data['operator_profile_base_img_path']  = $this->operator_profile_base_img_path;
        $this->arr_view_data['operator_profile_public_img_path']= $this->operator_profile_public_img_path;
        $this->arr_view_data['contract_signature_base_path']    = $this->contract_signature_base_path;
        $this->arr_view_data['contract_signature_public_path']  = $this->contract_signature_public_path;
        $this->arr_view_data['payment_receipt_to_admin_base_path']    = $this->payment_receipt_to_admin_base_path;
        $this->arr_view_data['payment_receipt_to_admin_public_path']  = $this->payment_receipt_to_admin_public_path;

        return view($this->module_view_folder.'.transactions',$this->arr_view_data);

    }
    public function request_payment(Request $request)
    {
        $user       = $this->operator_auth->user();
        $owner_id   =  $user->owner_id;
        $id         = $user->id;
        $arr_rules['amount']      = "required";
        $arr_rules['reservation'] = "required";

        $validator = validator::make($request->all(),$arr_rules);

        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $amount         =  $request->input('amount', null); 
        $reservation_id =  $request->input('reservation', null);    

        $arr_data = [];
        $arr_data['pay_to']             = 'operator';
        $arr_data['pay_to_id']          = $id;
        $arr_data['requested_amount']   = $amount;
        $arr_data['paid_amount']        = '';
        $arr_data['pay_from']           = 'admin';
        $arr_data['pay_from_id']        = config('app.project.admin_id');
        $arr_data['pay_slip']           = '';
        $arr_data['transaction_id']     = '';
        $arr_data['status']             = 'REQUESTED';
        $arr_data['received_status']    = 'pending';
        $arr_data['reservation_id']     = $reservation_id;

        $transaction = $this->TransactionModel->create($arr_data);
        

        $ARR_NOTIFICATIOn = [];
        $ARR_NOTIFICATION_DATA['receiver_id']            = config('app.project.admin_id');
        $ARR_NOTIFICATION_DATA['receiver_type']          = 'admin';
        $ARR_NOTIFICATION_DATA['sender_id']              = $id;
        $ARR_NOTIFICATION_DATA['sender_type']            = 'aircraft_owner';
        $ARR_NOTIFICATION_DATA['title']                  = 'Payment request';
        $ARR_NOTIFICATION_DATA['description']            = 'Operator has Requested for Payment of amount $'.$amount.' against Reservation id '.$reservation_id;
        $ARR_NOTIFICATION_DATA['redirect_url']           = url('/').'/admin/aircraft_owner/transaction/'.base64_encode($id);
        $ARR_NOTIFICATION_DATA['status']                 = 0;
        $ARR_NOTIFICATION_DATA['notification_type']      = 'transaction';
           $status = $this->save_notification($ARR_NOTIFICATION_DATA);
           if($status)
           {
                Session::flash('success','Request sent Successfully.');
                return redirect()->back();
            }
        Session::flash('error','Something went wrong');
        return redirect()->back();
    }
    public function approve(Request $request,$enc_id)
    {
        $id = base64_decode($enc_id);
        $response = $this->TransactionModel->where('id',$id)->update(['status'=>'APPROVED']);

        $user = $this->TransactionModel->where('id',$id)->first();
        if($user)
        {
            $owner_id = isset($user['pay_to_id']) ? $user['pay_to_id'] : '';
            $transaction_id = isset($user['transaction_id']) ? $user['transaction_id'] : '';
        }

        if($response!='')
        {
            $ARR_NOTIFICATIOn = [];
            $ARR_NOTIFICATION_DATA['receiver_id']            = config('app.project.admin_id');
            $ARR_NOTIFICATION_DATA['receiver_type']          = 'admin';
            $ARR_NOTIFICATION_DATA['sender_id']              = $owner_id;
            $ARR_NOTIFICATION_DATA['sender_type']            = 'aircraft_owner';
            $ARR_NOTIFICATION_DATA['title']                  = 'Operator has approved your Payment';
            $ARR_NOTIFICATION_DATA['description']            = 'Operator has Approved your Payment successfully for transaction id '.$transaction_id;
            $ARR_NOTIFICATION_DATA['redirect_url']           = url('/').'/admin/aircraft_owner/transaction/'.base64_encode($owner_id);
            $ARR_NOTIFICATION_DATA['status']                 = 0;
            $ARR_NOTIFICATION_DATA['notification_type']      = 'transaction';

           $status = $this->save_notification($ARR_NOTIFICATION_DATA);
            Session::flash('success',' Payment Approved Successfully.');
            return redirect()->back();
        }
         Session::flash('error','Something went wrong');
        return redirect()->back();

    }

     public function reject(Request $request,$enc_id)
    {
        $id = base64_decode($enc_id);
        $response = $this->TransactionModel->where('id',$id)->update(['status'=>'REJECTED']);

        $user = $this->TransactionModel->where('id',$id)->first();
        if($user)
        {
            $owner_id = isset($user['pay_to_id']) ? $user['pay_to_id'] : '';
            $transaction_id = isset($user['transaction_id']) ? $user['transaction_id'] : '';
        }

        if($response!='')
        {
            $ARR_NOTIFICATIOn = [];
            $ARR_NOTIFICATION_DATA['receiver_id']            = config('app.project.admin_id');
            $ARR_NOTIFICATION_DATA['receiver_type']          = 'admin';
            $ARR_NOTIFICATION_DATA['sender_id']              = $owner_id;
            $ARR_NOTIFICATION_DATA['sender_type']            = 'aircraft_owner';
            $ARR_NOTIFICATION_DATA['title']                  = 'Operator has approved your Payment';
            $ARR_NOTIFICATION_DATA['description']            = 'Operator has Approved your Payment successfully for transaction id '.$transaction_id;
            $ARR_NOTIFICATION_DATA['redirect_url']           = url('/').'/admin/aircraft_owner/transaction/'.base64_encode($owner_id);
            $ARR_NOTIFICATION_DATA['status']                 = 0;
            $ARR_NOTIFICATION_DATA['notification_type']      = 'transaction';
           $status = $this->save_notification($ARR_NOTIFICATION_DATA);
            Session::flash('success',' Payment Approved Successfully.');
            return redirect()->back();
        }
         Session::flash('error','Something went wrong');
        return redirect()->back();

    }
}