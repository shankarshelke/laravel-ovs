<?php

namespace App\Http\Controllers\Front\user;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\AircraftOwnerModel;
use App\Models\SiteSettingModel;
use App\Models\ReviewsModel;
use App\Models\UsersModel;
use App\Models\ReservationModel;
use App\Models\FeedbacksModel;
use App\Models\AircraftModel;
use App\Models\QuotationModel;
use App\Models\BankDetailsModel;
use App\Models\FeedbackQuestionsModel;
use App\Models\NotificationModel;
use App\Models\TransactionModel;

use App\Common\Services\MailService;

use Auth;
use Validator;
use Session;
use Cookie;
use Hash;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->arr_view_data            = [];
        $this->module_title             = "User";
        $this->module_view_folder       = "front.user";
        $this->AircraftOwnerModel       = new AircraftOwnerModel();
        $this->SiteSettingModel         = new SiteSettingModel();
        $this->UsersModel               = new UsersModel();
        $this->AircraftModel               = new AircraftModel();
        $this->TransactionModel         = new TransactionModel();
        $this->BankDetailsModel         = new BankDetailsModel();
        $this->ReservationModel         = new ReservationModel();
        $this->NotificationModel        = new NotificationModel();
        $this->ReviewsModel             = new ReviewsModel();
        $this->FeedbackQuestionsModel     = new FeedbackQuestionsModel();
        $this->FeedbacksModel             = new FeedbacksModel();
        $this->QuotationModel             = new QuotationModel();
        $this->user_profile_base_img_path   = base_path().config('app.project.user_profile_image');
        $this->user_profile_public_img_path = url('/').config('app.project.user_profile_image');
        $this->operator_profile_base_img_path   = base_path().config('app.project.operator_profile_image');
        $this->operator_profile_public_img_path = url('/').config('app.project.operator_profile_image');
        $this->aircraft_image_base_img_path   = base_path().config('app.project.img_path.aircraft_image');
        $this->aircraft_image_public_img_path = url('/').config('app.project.img_path.aircraft_image');
        $this->admin_base_img_path   = base_path().config('app.project.img_path.admin_profile_image');
        $this->admin_public_img_path = url('/').config('app.project.img_path.admin_profile_image');

        $this->MailService     = new MailService();
        $this->module_url_path = "dashboard";
        $this->user_auth       = auth()->guard('users');
    }

    public function dashboard()
    {
        $user       = $this->user_auth->user();
        $user_id    = $user->user_id;
        $id         = $user->id;
        $obj_data   = $this->UsersModel->where('id',$id)->first();

        if($obj_data){
            $arr_data = $obj_data->toArray();
        }

        $obj_settings_data  = $this->SiteSettingModel->where('id','1')->first();

        if($obj_settings_data)
        {
            $arr_settings_data = $obj_settings_data->toArray();
        }

        $obj_review_data  = $this->ReviewsModel->where('review_to','RESERVATION')
                                               ->where('review_to_id',$id)
                                               ->get();
        if($obj_review_data)
        {
            $arr_review_data = $obj_review_data->count();
        }

        $obj_completed_booking  = $this->ReservationModel->where('user_id',$id)
                                                         ->where('status','COMPLETED')
                                                         ->get();
        if($obj_completed_booking)
        {
            $arr_completed_booking = $obj_completed_booking->count();
        }

        $obj_pending_booking  = $this->ReservationModel->where('user_id',$id)
                                                       ->where('status','PENDING')
                                                       ->get();
        if($obj_pending_booking)
        {
            $arr_pending_booking = $obj_pending_booking->count();
        }

        $obj_notification  = $this->NotificationModel->with(['get_owner_details','get_admin_details'])
                                                     ->where('receiver_type','user')
                                                     ->where('receiver_id',$id)
                                                     ->where('status','0')
                                                     ->orderBy('created_at','DESC')
                                                     ->get();
        if($obj_notification)
        {
            $arr_notification = $obj_notification->toArray();
            $arr_notification_count = $obj_notification->count();
        }
/*
        $tot_paid = $this->ReservationModel->where('user_id', $id)
                                           ->where('is_signed', '1')
                                           ->where('cancellation_status','!=','APPROVED')
                                           ->where('status','!=','REFUNDED')
                                           ->sum('final_amount');
*/

        $obj_transaction = $this->TransactionModel->where('pay_from','user')
                                                  ->where('pay_from_id',$id)
                                                  ->count();


        $this->arr_view_data['arr_review_data']         = $arr_review_data;
        $this->arr_view_data['arr_completed_booking']   = $arr_completed_booking;
        $this->arr_view_data['arr_pending_booking']     = $arr_pending_booking;
        //$this->arr_view_data['tot_paid']                = $tot_paid;
        $this->arr_view_data['arr_data']                = $arr_data;
        $this->arr_view_data['obj_transaction']         = $obj_transaction;
        $this->arr_view_data['arr_notification']        = $arr_notification;
        $this->arr_view_data['arr_notification_count']  = $arr_notification_count;
        $this->arr_view_data['arr_settings_data']       = $arr_settings_data;
        $this->arr_view_data['module_title']            = $this->module_title." Dashboard";
        $this->arr_view_data['page_title']              = $this->module_title." Dashboard";
        $this->arr_view_data['user_profile_base_img_path']   = $this->user_profile_base_img_path;
        $this->arr_view_data['user_profile_public_img_path'] = $this->user_profile_public_img_path;
        $this->arr_view_data['operator_profile_base_img_path']   = $this->operator_profile_base_img_path;
        $this->arr_view_data['operator_profile_public_img_path'] = $this->operator_profile_public_img_path;
        $this->arr_view_data['admin_base_img_path']   = $this->admin_base_img_path;
        $this->arr_view_data['admin_public_img_path'] = $this->admin_public_img_path;
        return view($this->module_view_folder.'.dashboard',$this->arr_view_data);
    }

    public function pending_bookings(Request $request)
    {

        /* Search  */
        $search = '';
        if(\Request::has('search')&& \Request::get('search')!='')
        {
            $search = \Request::get('search');
        }
        /* Search  */

        $user = $this->user_auth->user();
        $user_id = $user->user_id;
        $obj_data  = $this->UsersModel->where('user_id',$user_id)->first();
        if($obj_data)
        {
            $arr_data = $obj_data->toArray();
            $id = (isset($arr_data['id']) ? $arr_data['id'] : '');
        }
        $obj_booking  = $this->ReservationModel
                                                ->with(['get_aircraft_details'=>function($q){
                                                    $q->with(['get_aircraft_owner','get_aircraft_type']);
                                                }])
                                                ->with(['get_image'])
                                                ->where('user_id',$id)
                                                ->where('status','PENDING');

        if($search!='' && isset($search))
        {
            $obj_booking= $obj_booking->whereHas('get_aircraft_details', function($q)use($search){
                                                $q->whereHas('get_aircraft_type',function($q2) use ($search){
                                                    $q2->where('model_name', 'LIKE',"%".$search."%");
                                                });
                                        })
                                       ->orwhere('reservation_id','LIKE', "%".$search."%");
        }

        $obj_booking = $obj_booking->where('user_id',$id)
                                    ->where('status','PENDING')
                                    ->orderBy('created_at','DESC')
                                    ->paginate(6)->appends(request()->input());
        if($obj_booking)
        {
            $arr_pagination = clone $obj_booking;
            $arr_booking = $obj_booking->toArray();
            /*$page_link    = $obj_booking->links();*/
        }
        $this->arr_view_data['page_link']               = $arr_pagination;
        $this->arr_view_data['arr_booking']             = $arr_booking;
        $this->arr_view_data['module_title']            = " Pending Bookings";
        $this->arr_view_data['page_title']              = $this->module_title." My Bookings";
        $this->arr_view_data['aircraft_image_base_img_path']   = $this->aircraft_image_base_img_path;
        $this->arr_view_data['aircraft_image_public_img_path'] = $this->aircraft_image_public_img_path;
        $this->arr_view_data['operator_profile_base_img_path']   = $this->operator_profile_base_img_path;
        $this->arr_view_data['operator_profile_public_img_path'] = $this->operator_profile_public_img_path;
        return view($this->module_view_folder.'.pending_bookings',$this->arr_view_data);
    }
    public function completed_bookings(Request $request)
    {
        /* Search  */
        $search = '';
        if(\Request::has('search')&& \Request::get('search')!='')
        {
            $search = \Request::get('search');
        }
        /* Search  */
        $user = $this->user_auth->user();
        $user_id = $user->user_id;
        $obj_data  = $this->UsersModel->where('user_id',$user_id)->first();
        if($obj_data)
        {
            $arr_data = $obj_data->toArray();
            $id = (isset($arr_data['id']) ? $arr_data['id'] : '');
        }
        $obj_booking  = $this->ReservationModel->with(['get_aircraft_details'=>function($q){$q->with(['get_aircraft_owner','get_aircraft_type']);}])
                                               ->with(['get_owner_details'])
                                               ->with(['get_image'])
                                               ->where('user_id',$id)
                                               ->where('status','COMPLETED');
        if($search!='' && isset($search))
        {
            $obj_booking= $obj_booking->whereHas('get_aircraft_details', function($q)use($search){
                                                $q->whereHas('get_aircraft_type',function($q2)use($search){
                                                    $q2->where('model_name', 'LIKE',"%".$search."%");
                                                });
                                        })
                                      ->orwhere('reservation_id','LIKE', "%".$search."%");
     }
     $obj_booking  = $obj_booking->where('user_id',$id)
                                 ->where('status','COMPLETED')
                                 ->orderBy('created_at','DESC')
                                 ->paginate(6);
     if($obj_booking)
     {
        $arr_booking = $obj_booking->toArray();
        $page_link    = $obj_booking->links(); 
    }
    $obj_feedback  = $this->FeedbackQuestionsModel->where('feedback_for','USER')->where('status',1)->get();
    if($obj_feedback)
    {
        $arr_feedback = $obj_feedback->toArray();
    }

    /*$obj_check_review = $this->ReviewsModel->where('review_to','AIRCRAFT')
                                           ->where('review_from_id',$id)
                                            ->count();*/

    $this->arr_view_data['arr_feedback']            = $arr_feedback;
    $this->arr_view_data['page_link']               = $page_link;
    $this->arr_view_data['arr_booking']             = $arr_booking;
    $this->arr_view_data['module_title']            = " Completed Bookings";
    $this->arr_view_data['page_title']              = $this->module_title." My Bookings";
    $this->arr_view_data['aircraft_image_base_img_path']     = $this->aircraft_image_base_img_path;
    $this->arr_view_data['aircraft_image_public_img_path']   = $this->aircraft_image_public_img_path;
    $this->arr_view_data['operator_profile_base_img_path']   = $this->operator_profile_base_img_path;
    $this->arr_view_data['operator_profile_public_img_path'] = $this->operator_profile_public_img_path;
    return view($this->module_view_folder.'.completed_bookings',$this->arr_view_data);
}

public function reviews(Request $request,$res_id,$enc_id)
{
    $user    = $this->user_auth->user();
    $user_id = $user->user_id;
    $aircraft_id    = base64_decode($enc_id);
    $reservation_id = base64_decode($res_id);
    $arr_rules      = $arr_credentials =  array();
    $status         = false;
    $arr_rules['rating']        = "required";
    $arr_rules['reviews']       = "required";
    /*$arr_rules['experience']    = "required";*/
    $validator = Validator::make($request->all(),$arr_rules);
    if($validator->fails()) 
    {
        return back()->withErrors($validator)->withInput();
    }
    $rating         = $request->input('rating');
    $reviews        = $request->input('reviews');
    $experience     = $request->input('experience');
    $obj_data  = $this->UsersModel->where('user_id',$user_id)->first();

    if($obj_data) 
    {
        $arr_data = $obj_data->toArray();
        $id = (isset($arr_data['id']) ? $arr_data['id'] : '');

    }

    $obj_owner  = $this->AircraftModel->where('id',$aircraft_id)->first();
    if($obj_owner)
    {
        $owner_id  = isset($obj_owner['aircraft_owner_id']) ? $obj_owner['aircraft_owner_id'] : '';
    }

    $arr_data['review_from_id']   = $id;
    $arr_data['review_to_id']     = $owner_id;
    $arr_data['aircraft_id']      = $aircraft_id;
    $arr_data['reviews']          = $reviews;
    $arr_data['review_to']        = 'AIRCRAFT';
    $arr_data['reservation_id']   = $reservation_id;
    $arr_data['ratings']          = $rating;


    $obj_create     = $this->ReviewsModel->create($arr_data);
    if(!empty($experience))
    {

        foreach($experience as $key => $val )
        {
            $arr_feedback = [];
            $arr_feedback['question_id']  = $key;
            $arr_feedback['answer']       = $val;
            $arr_feedback['booking_id']   = $reservation_id;
            $arr_feedback['from_id']      = $id;
            $arr_feedback['from_role']    = 'USER';
            
            $obj_feedback   = $this->FeedbacksModel->create($arr_feedback);
        }
    }

    if($obj_create && $obj_feedback)
    { 
        Session::flash('success','Reviews saved Successfully.');
        return redirect()->back();    
    }

}

public function requested_quotations(Request $request)
{
    /* Search  */
    $search = '';
    if(\Request::has('search')&& \Request::get('search')!='')
    {
        $search = \Request::get('search');
    }
    /* Search  */
    $user = $this->user_auth->user();
    $user_id = $user->user_id;
    $obj_data  = $this->UsersModel->where('user_id',$user_id)->first();
    if($obj_data)
    {
        $arr_data = $obj_data->toArray();
        $id = (isset($arr_data['id']) ? $arr_data['id'] : '');
    }

    $obj_quotations  = $this->QuotationModel
                                            ->with(['aircraft','aircraft_owner','get_image','aircraft.get_aircraft_type'])
                                            ->where('user_id',$id);

    if($search!='' && isset($search))
    {
        $obj_quotations = $obj_quotations->whereHas('aircraft', function($q) use($search){
                                                $q->whereHas('get_aircraft_type', function($q2) use($search){
                                                    $q2->where('model_name','LIKE',"%".$search."%");
                                                });
                                            })
                                         ->orwhere('rfq_id','LIKE', "%".$search."%");
    }

    $obj_quotations = $obj_quotations->where('user_id',$id)
                                     ->orderBy('created_at','DESC')
                                     ->paginate(6);
    if($obj_quotations)
    {
        $arr_quotations = $obj_quotations->toArray();
        //dd($arr_quotations);
        $page_link    = $obj_quotations->links(); 
    }

    $this->arr_view_data['page_link']               = $page_link;
    $this->arr_view_data['arr_quotations']          = $arr_quotations;
    $this->arr_view_data['module_title']            = " Quotes under review";
    $this->arr_view_data['page_title']              = $this->module_title." My Bookings";
    $this->arr_view_data['aircraft_image_base_img_path']   = $this->aircraft_image_base_img_path;
    $this->arr_view_data['aircraft_image_public_img_path'] = $this->aircraft_image_public_img_path;
    $this->arr_view_data['operator_profile_base_img_path']   = $this->operator_profile_base_img_path;
    $this->arr_view_data['operator_profile_public_img_path'] = $this->operator_profile_public_img_path;
    return view($this->module_view_folder.'.requested_quotations',$this->arr_view_data);
}

public function accept_quotation(Request $request,$enc_id)
{
    /* Search  */
    $rfq_id = base64_decode($enc_id);
    $user = $this->user_auth->user();
    $id = $user->id;
    $obj_data  = $this->UsersModel->where('id',$id)->first();
    if($obj_data)
    {
        $arr_data       = $obj_data->toArray();
        $first_name     = isset($arr_data['first_name']) ? $arr_data['first_name'] : 'N/A';
        $last_name      = isset($arr_data['last_name']) ? $arr_data['last_name'] : 'N/A';
        $user_full_name = $first_name.' '.$last_name;
    }
    $obj_quotations  = $this->QuotationModel->where('rfq_id',$rfq_id)->first();
    if($obj_quotations)
    {
        $arr_quotations = $obj_quotations->toArray();
    }


    $obj_update         = $this->QuotationModel->where('rfq_id',$rfq_id)->update(['status' => 'ACCEPTED']);
    if($obj_update)
    {
        $quotation_id  = $this->QuotationModel->where('rfq_id',$rfq_id)->first();
        if($quotation_id)
        {
            $arr_quote_id = $quotation_id->toArray();
            $quote_id     = isset($arr_quote_id['id']) ? $arr_quote_id['id'] : 'N/A';
        }
        $ARR_NOTIFICATIOn = [];
        $ARR_NOTIFICATION_DATA['receiver_id']            = config('app.project.admin_id');
        $ARR_NOTIFICATION_DATA['receiver_type']          = 'admin';
        $ARR_NOTIFICATION_DATA['sender_id']              = $id;
        $ARR_NOTIFICATION_DATA['sender_type']            = 'user';
        $ARR_NOTIFICATION_DATA['title']                  = 'Quotation Accepted';
        $ARR_NOTIFICATION_DATA['description']            = $user_full_name.' has accepted the Quotation';
        $ARR_NOTIFICATION_DATA['redirect_url']           = '/admin/quotation/view/'.base64_encode($quote_id);
        $ARR_NOTIFICATION_DATA['status']                 = 0;
        $ARR_NOTIFICATION_DATA['notification_type']      = 'reservation';
        $this->save_notification($ARR_NOTIFICATION_DATA);
        Session::flash('success','You have successfully Accepted the Quotation.');
        return redirect()->back();    
    }
    Session::flash('error','Something went wrong.');
    return redirect()->back();    
}
public function reject_quotation(Request $request,$enc_id)

{
    /* Search  */
    $rfq_id = base64_decode($enc_id);
    $user = $this->user_auth->user();
    $user_id = $user->user_id;
    $obj_data  = $this->UsersModel->where('user_id',$user_id)->first();
    if($obj_data)
    {
        $arr_data = $obj_data->toArray();
        $id = (isset($arr_data['id']) ? $arr_data['id'] : '');
    }
    $obj_quotations  = $this->QuotationModel->where('rfq_id',$rfq_id)->first();
    if($obj_quotations)
    {
        $arr_quotations = $obj_quotations->toArray();
    }

    
    $obj_update         = $this->QuotationModel->where('rfq_id',$rfq_id)->update(['status' => 'REJECTED']);
    if($obj_update)
    {
        Session::flash('success','You have successfully Rejected the Quotation.');
        return redirect()->back();    
    }
    Session::flash('error','Something went wrong.');
    return redirect()->back();    
}

public function cancelled_bookings(Request $request)
{ 
    /* Search  */
    $search = '';
    if(\Request::has('search')&& \Request::get('search')!='')
    {
        $search = \Request::get('search');
    }
    /* Search  */
    $user = $this->user_auth->user();
    $user_id = $user->user_id;
    $obj_data  = $this->UsersModel->where('user_id',$user_id)->first();
    if($obj_data)
    {
        $arr_data = $obj_data->toArray();
        $id = (isset($arr_data['id']) ? $arr_data['id'] : '');

    }
    $obj_cancel_booking  = $this->ReservationModel->with(['get_aircraft_details'=>function($q){$q->with(['get_aircraft_owner','get_aircraft_type']);}])
                                                    ->with(['get_image'])
                                                    ->where('user_id',$id)
                                                    ->where('status','CANCELLED');
    if($search!='' && isset($search))
    {
      $obj_cancel_booking= $obj_cancel_booking->whereHas('get_aircraft_details', function($q)use($search){
                                                $q->whereHas('get_aircraft_type',function($q2)use($search){
                                                        $q2->where('model_name', 'LIKE',"%".$search."%");
                                                    });
                                                })
                                                ->orwhere('reservation_id','LIKE', "%".$search."%"); 
  }


  $obj_cancel_booking = $obj_cancel_booking->where('user_id',$id)
                                          ->where('status','CANCELLED')
                                          ->orderBy('created_at','DESC')  
                                          ->paginate(6);
  if($obj_cancel_booking)
  {
    $arr_booking = $obj_cancel_booking->toArray();
    $page_link    = $obj_cancel_booking->links(); 
   }

    $this->arr_view_data['page_link']               = $page_link;
    $this->arr_view_data['arr_booking']             = $arr_booking;
    $this->arr_view_data['module_title']            = " Cancelled Bookings";
    $this->arr_view_data['page_title']              = $this->module_title." My Bookings";
    $this->arr_view_data['aircraft_image_base_img_path']   = $this->aircraft_image_base_img_path;
    $this->arr_view_data['aircraft_image_public_img_path'] = $this->aircraft_image_public_img_path;
    $this->arr_view_data['operator_profile_base_img_path']   = $this->operator_profile_base_img_path;
    $this->arr_view_data['operator_profile_public_img_path'] = $this->operator_profile_public_img_path;
    return view($this->module_view_folder.'.cancelled_bookings',$this->arr_view_data);
}


public function reviews_and_ratings(Request $request)
{
    $user = $this->user_auth->user();
    $user_id = $user->user_id;
    $obj_data  = $this->UsersModel->where('user_id',$user_id)->first();
    if($obj_data)
    {
        $arr_data = $obj_data->toArray();
        $id = (isset($arr_data['id']) ? $arr_data['id'] : '');

    }
    $obj_review     = $this->ReviewsModel->with(['aircraft','owner','get_image','get_reservation'])
                                         ->with(['aircraft'=>function($q){
                                            $q->with(['get_aircraft_type']);
                                         }])
                                         ->where('review_to','RESERVATION')
                                         ->where('review_to_id',$id)
                                         ->orderBy('created_at','DESC')
                                         ->paginate(3);
    if($obj_review)
    {
        $arr_data = $obj_review->toArray();
        $page_link    = $obj_review->links(); 
    }
    
    
    $this->arr_view_data['page_link']               = $page_link;
    $this->arr_view_data['arr_data']                = $arr_data;
    $this->arr_view_data['module_title']            = " Reviews & Ratings";
    $this->arr_view_data['page_title']              = $this->module_title." Reviews & Ratings";
    $this->arr_view_data['aircraft_image_base_img_path']   = $this->aircraft_image_base_img_path;
    $this->arr_view_data['aircraft_image_public_img_path'] = $this->aircraft_image_public_img_path;
    $this->arr_view_data['operator_profile_base_img_path']   = $this->operator_profile_base_img_path;
    $this->arr_view_data['operator_profile_public_img_path'] = $this->operator_profile_public_img_path;
    return view($this->module_view_folder.'.reviews_and_ratings',$this->arr_view_data);
}        
public function bank_details(Request $request)
{ 
    $user = $this->user_auth->user();
    $id = $user->id;
    $obj_data  = $this->UsersModel->where('id',$id)->first();
    if($obj_data)
    {
        $arr_data = $obj_data->toArray();
    }
    $obj_data  = $this->BankDetailsModel->where('customer_id',$id)
                                        ->where('type','USER')
                                        ->first();
    if($obj_data)
    {
        $arr_data = $obj_data->toArray();
    }
    $obj_admin  = $this->SiteSettingModel->where('id',1)->first();
    if($obj_admin)
    {
        $arr_admin = $obj_admin->toArray();
    }
    
    $this->arr_view_data['arr_data']                = $arr_data;
    $this->arr_view_data['arr_admin']               = $arr_admin;
    $this->arr_view_data['module_title']            = " Bank Details";
    $this->arr_view_data['page_title']              = $this->module_title." Bank Details";
    $this->arr_view_data['aircraft_image_base_img_path']   = $this->aircraft_image_base_img_path;
    $this->arr_view_data['aircraft_image_public_img_path'] = $this->aircraft_image_public_img_path;
    $this->arr_view_data['user_profile_base_img_path']   = $this->user_profile_base_img_path;
    $this->arr_view_data['user_profile_public_img_path'] = $this->user_profile_public_img_path;
    return view($this->module_view_folder.'.bank_details',$this->arr_view_data);
} 
public function update_bank_details(Request $request)
{
    $user = $this->user_auth->user();
    $id = $user->id;
    $obj_data  = $this->UsersModel->where('id',$id)->first();
    if($obj_data)
    {
        $arr_data = $obj_data->toArray();
    }
    $arr_rules      = array();
    $status         = false;
    $arr_rules['bank_name']         = "required";
    $arr_rules['branch_name']       = "required";
    $arr_rules['swift_code']        = "required";
    $arr_rules['account_number']    = "required";
    $arr_rules['address']           = "required";
    /*$arr_rules['experience']    = "required";*/
    $validator = Validator::make($request->all(),$arr_rules);
    
    if($validator->fails()) 
    {
        return back()->withErrors($validator)->withInput();
    }
    $bank_name         = $request->input('bank_name');
    $branch_name       = $request->input('branch_name');
    $swift_code        = $request->input('swift_code');
    $account_number    = $request->input('account_number');
    $address           = $request->input('address');


    $arr_bank_data['customer_id']    = $id;
    $arr_bank_data['bank_name']      = $bank_name;
    $arr_bank_data['branch_name']    = $branch_name;
    $arr_bank_data['swift_code']     = $swift_code;
    $arr_bank_data['account_number'] = $account_number;
    $arr_bank_data['bank_address']   = $address;
    $arr_bank_data['type']           = 'USER';

    $obj_search                 = $this->BankDetailsModel->where('customer_id',$id)
                                                         ->where('type','USER')   
                                                         ->first();
    if($obj_search && $obj_search !='')
    {
        $obj_update             = $this->BankDetailsModel->where('customer_id',$id)
                                       ->where('type','USER')
                                       ->update($arr_bank_data);
        if($obj_update)
        {
            Session::flash('success','Your Bank Details have been changed successfully.');
            return redirect()->back();    
        }
        Session::flash('error','Something went wrong while updating.');
        return redirect()->back();    
    }
    else
    {
        $obj_create                 = $this->BankDetailsModel->create($arr_bank_data);
        if($obj_create)        
        {
            Session::flash('success','Your Bank Details have been saved successfully.');
            return redirect()->back();    
        }
        Session::flash('error','Something went wrong.');
        return redirect()->back();    
    } 
}
public function notifications(Request $request)
{
     /* Search  */
    $notification = '';
    if(\Request::has('type')&& \Request::get('type')=='transaction')
    {
        //$x = Request::has('transaction');
        $notification = \Request::get('type');
    }
    else if(\Request::has('type')&& \Request::get('type')=='reservation')
    {
        $notification = \Request::get('type');
    }
    else if(\Request::has('type')&& \Request::get('type')=='general')
    {
        $notification = \Request::get('type');
    }
    /* Search  */
    $user = $this->user_auth->user();
    $user_id = $user->id;
    $obj_data  = $this->UsersModel->where('id',$user_id)->first();
    if($obj_data)
    {
        $arr_data = $obj_data->toArray();
    }
    $obj_notification  = $this->NotificationModel->with(['get_owner_details','get_admin_details'])
                                                 ->where('receiver_type','user')
                                                 ->where('receiver_id',$user_id);
                                                 
    if($notification!='' && isset($notification) && $notification=='general')
    {
        $obj_notification = $obj_notification->where('notification_type',$notification);

    }else if($notification!='' && isset($notification) && $notification=='transaction')
    {
        $obj_notification = $obj_notification->where('notification_type',$notification);
    }else if($notification!='' && isset($notification) && $notification == 'reservation')
    {
        $obj_notification = $obj_notification->where('notification_type',$notification);
    }

    $obj_notification = $obj_notification->orderBy('created_at','DESC')
                                                 ->paginate(10);

    if($obj_notification)
    {
        $arr_notification = $obj_notification->toArray();
        $page_link    = $obj_notification->links(); 
        foreach($obj_notification as $row){
        $obj_update_notification  = $this->NotificationModel->where('id',$row->id)->update(['status'=>'1']);
        }
    }
    
    $this->arr_view_data['page_link']               = $page_link;
    $this->arr_view_data['arr_notification']        = $arr_notification;
    $this->arr_view_data['arr_data']                = $arr_data;
    $this->arr_view_data['module_title']            = " Notifications";
    $this->arr_view_data['page_title']              = $this->module_title." My Bookings";
    $this->arr_view_data['sub_module_title']        = ucfirst($notification);
    $this->arr_view_data['aircraft_image_base_img_path']   = $this->aircraft_image_base_img_path;
    $this->arr_view_data['aircraft_image_public_img_path'] = $this->aircraft_image_public_img_path;
    $this->arr_view_data['user_profile_base_img_path']   = $this->user_profile_base_img_path;
    $this->arr_view_data['user_profile_public_img_path'] = $this->user_profile_public_img_path;
    $this->arr_view_data['operator_profile_base_img_path']   = $this->operator_profile_base_img_path;
    $this->arr_view_data['operator_profile_public_img_path'] = $this->operator_profile_public_img_path;
    $this->arr_view_data['admin_base_img_path']   = $this->admin_base_img_path;
    $this->arr_view_data['admin_public_img_path'] = $this->admin_public_img_path;
    return view($this->module_view_folder.'.notifications',$this->arr_view_data);
}

}
