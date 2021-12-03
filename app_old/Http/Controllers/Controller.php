<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Events\NotificationEvent;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

     /*---------------------------------------------------------
    |Notification Log
    ---------------------------------------------------------*/
    public function save_notification($ARR_DATA = [], $EXTRA_DATA=[])
    {
        if(isset($ARR_DATA) && sizeof($ARR_DATA)>0)
        {
            $ARR_NOTIFICATION_DATA['receiver_id']			= $ARR_DATA['receiver_id'];
            $ARR_NOTIFICATION_DATA['receiver_type']         = $ARR_DATA['receiver_type'];
            $ARR_NOTIFICATION_DATA['sender_id']             = $ARR_DATA['sender_id'];
            $ARR_NOTIFICATION_DATA['sender_type']           = $ARR_DATA['sender_type'];
            $ARR_NOTIFICATION_DATA['title']                 = $ARR_DATA['title'];
            $ARR_NOTIFICATION_DATA['description']           = $ARR_DATA['description'];
            $ARR_NOTIFICATION_DATA['redirect_url']          = $ARR_DATA['redirect_url'];
            $ARR_NOTIFICATION_DATA['status']                = $ARR_DATA['status'];
            $ARR_NOTIFICATION_DATA['notification_type']     = $ARR_DATA['notification_type'];

            event(new NotificationEvent($ARR_NOTIFICATION_DATA));

            return true;
        }
        return false;
    }
    /*-------------------------------------------------------*/
    public function build_response( $status = 'success',
                                    $message = "",
                                    $arr_data = [],
                                    $response_format = 'json',
                                    $response_code = 200)
    {
        $arr_result=[];

        if($response_format == 'json')
        {
            $arr_result = [
                'status' => $status,
                'msg' => $message
            ];
            
            if(count($arr_data)>0)
            {
                $arr_result['response_data'] = $arr_data;
            }
            return response()->json($arr_result,$response_code,[],JSON_UNESCAPED_UNICODE);    
        }   
    }
}
