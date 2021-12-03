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

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->arr_view_data                    = [];
        $this->module_title                     = "Transactions";
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

    public function transactions()
    {  
        /* Search  */
        $search = '';
        if(\Request::has('search')&& \Request::get('search')!='')
        {
            $search = \Request::get('search');
        }
        /* Search  */
        $user       = $this->user_auth->user();
        $user_id    = $user->user_id;
        $id         = $user->id;
        $obj_data   = $this->UsersModel->where('id',$id)->first();

        if($obj_data){
            $arr_data = $obj_data->toArray();
        }


        $obj_transaction = $this->TransactionModel->where('pay_from','user')
                                                  ->where('pay_from_id',$id);

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

        $obj_reservations = $this->ReservationModel->where('user_id',$id)->get();
        if($obj_reservations)
        {
            $arr_reservations = $obj_reservations->toArray();
        }

        $obj_amount = $this->ReservationModel->where('user_id',$id)
                                                   ->whereIn('status',['PENDING','COMPLETED','CANCELLED']) 
                                                   ->sum('final_amount');

        $obj_extend_amount = $this->ExtendRequestModel->whereHas('reservation_details', function($q)use($id){
                                                    $q->where('user_id',$id);
                                                })
                                                ->with(['reservation_details'])
                                                ->where('status','APPROVED')  
                                                ->sum('amount');
        $obj_final_amount = ($obj_extend_amount + $obj_amount);

        $obj_paid_amount  = $this->TransactionModel->where('pay_from','user')
                                                   ->where('pay_from_id',$id)
                                                   ->where('status','APPROVED')
                                                   ->sum('paid_amount');

        $obj_commission  = $this->ReservationModel->where('user_id',$id)->sum('commission_amount');

        $pending_amount  = ($obj_final_amount - $obj_paid_amount);

        $this->arr_view_data['module_title']                    = 'Transactions';
        $this->arr_view_data['page_title']                      = 'Transactions';
        $this->arr_view_data['arr_transaction']                 = $arr_transaction;
        $this->arr_view_data['page_link']                       = $page_link;
        $this->arr_view_data['obj_final_amount']                = $obj_final_amount;
        $this->arr_view_data['obj_paid_amount']                 = $obj_paid_amount;
        $this->arr_view_data['pending_amount']                  = $pending_amount;
        $this->arr_view_data['arr_reservations']                = $obj_reservations;
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

    public function send_payment(Request $request)
    { 
        $user       = $this->user_auth->user();
        $user_id    = $user->user_id;
        $id         = $user->id;
        $obj_data   = $this->UsersModel->where('id',$id)->first();

        if($obj_data){
            $arr_data = $obj_data->toArray();
        }
        $arr_rules = [];
        $arr_rules['amount']            = "required";
        $arr_rules['reservation']       = "required"; 
        $arr_rules['transaction']       = "required"; 
        
        $validator = Validator::make($request->all(),$arr_rules);

        if($validator->fails())
        {   
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        
        if($request->hasFile('receipt'))
        {
            $file_name = $request->input('receipt');
            $file_extension = strtolower($request->file('receipt')->getClientOriginalExtension());
            if(in_array($file_extension,['jpg','png','jpeg']))
            {
                $file_name = sha1(uniqid().$file_name.uniqid()).'.'.$file_extension;
                $isUpload = $request->file('receipt')->move($this->payment_receipt_to_admin_base_path , $file_name);
            }
            else
            {
                $status     = 'fail';
                $customMsg = 'Invalid File type, While creating '.str_singular($this->module_title);

                $resp = array('status' => $status,'errors'=>$errors,'customMsg'=> $customMsg);
                return response()->json($resp);

                /*Session::flash('error','Invalid File type, While creating '.str_singular($this->module_title));
                return redirect()->back();*/
            }
        }
        else
        {
            $file_name = $old_image;
        }
        
        $note           = $request->input('note');
        $reservation_id = $request->input('reservation');
        $amount         = $request->input('amount');
        $transaction_id = $request->input('transaction');
        $hidden         = $request->input('hidden');

        $user_info = $this->UsersModel->where('id',$id)->first();
        if($user_info)
        {
            $arr_info = $user_info->toArray();
            $first_name = isset($arr_info['first_name']) ? $arr_info['first_name'] : '';
            $last_name = isset($arr_info['last_name']) ? $arr_info['last_name'] : '';
            $user_fname = $first_name.' '.$last_name;
        }


        $arr_data_pay['pay_to']                = 'admin';
        $arr_data_pay['pay_to_id']             = config('app.project.admin_id');
        $arr_data_pay['requested_amount']      = isset($amount) ? $amount : ''; 
        $arr_data_pay['paid_amount']           = isset($amount) ? $amount : '';
        $arr_data_pay['pay_from']              = 'user';
        $arr_data_pay['pay_from_id']           = $id;
        $arr_data_pay['pay_slip']              = isset($file_name) ? $file_name : '';
        $arr_data_pay['transaction_id']        = $transaction_id;
        $arr_data_pay['note']                  = isset($note) ? $note : '';
        $arr_data_pay['status']                = 'PENDING';
        $arr_data_pay['reservation_id']        = isset($reservation_id) ? $reservation_id : '';


        if(isset($hidden) && $hidden != null)
        {
            $status = $this->TransactionModel->where('id',$hidden)->update($arr_data_pay);
        }
        else
        {
            $status = $this->TransactionModel->create($arr_data_pay);
        }

        if($status)
        {
            $ARR_NOTIFICATIOn = [];
            $ARR_NOTIFICATION_DATA['receiver_id']            = config('app.project.admin_id');
            $ARR_NOTIFICATION_DATA['receiver_type']          = 'admin';
            $ARR_NOTIFICATION_DATA['sender_id']              = $id;
            $ARR_NOTIFICATION_DATA['sender_type']            = 'user';
            $ARR_NOTIFICATION_DATA['title']                  = 'Payment received from User';
            $ARR_NOTIFICATION_DATA['description']            = 'User '.$user_fname.' has processed the payment. Please check my transactions.';
            $ARR_NOTIFICATION_DATA['redirect_url']           = url('/').'/admin/users/transaction/'.base64_encode($id);
            $ARR_NOTIFICATION_DATA['status']                 = 0;
            $ARR_NOTIFICATION_DATA['notification_type']      = 'transaction';
            $notification = $this->save_notification($ARR_NOTIFICATION_DATA);
                    
            Session::flash('success','Payment Slip uploaded successfully.');
            return redirect()->back();
        }
        Session::flash('error','Something went wrong');
        return redirect()->back();
    }


}