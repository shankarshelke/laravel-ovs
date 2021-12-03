<?php
namespace App\Common\Services;
use \Shipu\MuthoFun\Facades\MuthoFun;

use Twilio\Rest\Client;

require base_path().'/app/Common/Services/twilio-php-master/Twilio/autoload.php';

class SmsService
{
    function __construct()
    {
        $this->sms_enabled   = true;
    }

    public function send_sms($message,$to_number)
    { //dd($to_number);
        // Find your Account Sid and Auth Token at twilio.com/console
        // DANGER! This is insecure. See http://twil.io/secure
        try{
            $sid    = "AC1f5a9f5ac19722ed95e6fd2d0c7ed9a1";
            $token  = "3e80c988a19c353b9a5bbf56fd357f0d";

            $twilio = new Client($sid, $token);
            // dd($twilio);
            $message = $twilio->messages
                                ->create($to_number, // to
                                //->create("+918888910323", // to
                                    array(
                                       "body" => $message,

                                       // "from" => "+13475719238"
                                       "from" => "+14243295144"
                                       // "from" => "+15005550000"
                                    )
                                );

            //print($message->sid);
            
            return true;
        }
        catch(\Exception $e){
          dd($e)  ;
            return false;
        }
    }
}

?>