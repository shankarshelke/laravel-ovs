<?php

namespace App\Http\Controllers\Front\operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AircraftOwnerModel;
use App\Models\ReservationModel;
use App\Models\UsersModel;
use App\Models\TransactionModel;
use App\Models\ExtendRequestModel;

use App\Common\Services\MailService;

use Auth;
use Validator;
use Session;
use Image;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->arr_view_data                    = [];
        $this->module_title                     = "Bookings";
        $this->module_view_folder               = "front.operator";

        $this->AircraftOwnerModel               = new AircraftOwnerModel();
        $this->ReservationModel                 = new ReservationModel();
        $this->UsersModel                       = new UsersModel();
        $this->TransactionModel                 = new TransactionModel();

        $this->ExtendRequestModel              = new ExtendRequestModel();

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
        $this->user_auth                        = auth()->guard('operator');

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

        $owner_id       = isset($obj_booking['owner_id']) ?  $obj_booking['owner_id'] : 'NA';
        $reservation_id = isset($obj_booking['reservation_id']) ?  $obj_booking['reservation_id'] : 'NA';

        if(!$obj_booking){
            Session::flash('error','Something went wrong!');
            return redirect()->back();
        }

        $obj_transaction = $this->TransactionModel->where('pay_to','operator')
                                                  ->where('pay_to_id',$owner_id)
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