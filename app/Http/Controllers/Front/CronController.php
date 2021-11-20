<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Models\ReservationModel;

class CronController extends Controller
{
    function __construct()
    {
        $this->ReservationModel     = new ReservationModel();
    }

    public function complete_bookings(Request $request)
    {
        $obj_bookings = $this->ReservationModel->where('status',"PENDING")
                                                ->whereDate('pickup_date','<',date('Y-m-d'))
                                                ->get();

        if($obj_bookings->count() > 0){
            foreach($obj_bookings as $booking)
            {
                $notify_msg = $notify_title = '';
                if($booking->is_signed == 0)
                {
                    $this->ReservationModel->where('id',$booking->id)->update(['status'=>'CANCELLED']);
                    $notify_msg = "Your Booking #".$booking->reservation_id." has been cancelled due to contract has not signed.";
                    $notify_title = "Booking cancelled";
                }
                elseif($booking->is_signed == 1 && $booking->payment_status != "APPROVED")
                {
                    $notify_title = "Booking cancelled";
                    $is_update=$this->ReservationModel->where('id',$booking->id)->update(['status'=>'CANCELLED']);

                    if($is_update){
                        if($booking->payment_status == 'SUBMITTED'){
                            $notify_msg = "Your Booking #".$booking->reservation_id." has been cancelled due to your booking payment has not approved by system.";
                        }elseif($booking->payment_status == 'REJECTED'){
                            $notify_msg = "Your Booking #".$booking->reservation_id." has been cancelled due to your booking payment has rejected by system.";
                        }
                    }
                }
                elseif($booking->is_signed == 1 && $booking->payment_status == "APPROVED" && strtotime($booking->is_signed) == strtotime(date('Y-m-d')) )
                {
                    $notify_title = "Booking completed";
                    $is_update=$this->ReservationModel->where('id',$booking->id)->update(['status'=>'COMPLETED']);
                    if($is_update){
                        $notify_msg = "Your Booking #".$booking->reservation_id." has been completed. Please give reviews to your booking.";
                    }
                }
                /*
                |
                |Send notification
                |
                */

                $ARR_NOTIFICATIOn = [];
                $ARR_NOTIFICATION_DATA['receiver_id']            = $booking->user_id;
                $ARR_NOTIFICATION_DATA['receiver_type']          = 'user';
                $ARR_NOTIFICATION_DATA['sender_id']              = config('app.project.admin_id');
                $ARR_NOTIFICATION_DATA['sender_type']            = 'admin';
                $ARR_NOTIFICATION_DATA['title']                  = 'Contract Extend Request Received';
                $ARR_NOTIFICATION_DATA['description']            = $notify_msg;
                $ARR_NOTIFICATION_DATA['redirect_url']           = '';
                $ARR_NOTIFICATION_DATA['status']                 = 0;
                $this->save_notification($ARR_NOTIFICATION_DATA);
            }
        }
    }
}