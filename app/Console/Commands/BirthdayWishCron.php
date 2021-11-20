<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SentSmsModel;
use App\Models\UsersModel;
use App\Models\SmsTemplateModel;
use App\Models\WebAdmin;
class BirthdayWishCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BirthdayWish:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $arr_template    = SmsTemplateModel::
                                where('id','9')
                                ->first();
        $day = now()->day;
        $month = now()->month;
        //dd($day);
        $user_info =  UsersModel::whereRaw('DATE_FORMAT(date_of_birth, "%m-%d") = ?', [date('m-d')])
           ->where('is_invalid_contact','0')
           ->get();
        /* get Birthday Details */
            $sender_contact =null;
            foreach($user_info as $key => $value) {
                        $get_family_mobile_no = UsersModel::where('family_id',$value->family_id)
                                    ->whereNotNull('mobile_number')
                                    ->whereRaw('LENGTH(mobile_number) = 10')
                                    ->where('is_invalid_contact','0')
                                    ->first();
                    /* get number */                
                     $sender_contact[] = (empty($value['mobile_number']) || strlen($value['mobile_number']) < 10) ? ((empty($get_family_mobile_no['mobile_number']) || strlen($get_family_mobile_no['mobile_number']) < 10) ? null : $get_family_mobile_no['mobile_number']) :$value['mobile_number'];
                     
                     /* get name */   
 	                        $first_name = $value['first_name'] ? $value['first_name'] :'';
	                        $last_name  = $value['last_name']  ? $value['last_name'] : '';
	                        $full =$first_name.$last_name;   

                     $full_name[] = (empty($value['mobile_number']) || strlen($value['mobile_number']) < 10) ? ((empty($get_family_mobile_no['mobile_number']) || strlen($get_family_mobile_no['mobile_number']) < 10) ? null : $full) :$full;
                     
                /* get user_id */   
                
                     $user_id[] = (empty($value['mobile_number']) || strlen($value['mobile_number']) < 10) ? ((empty($get_family_mobile_no['mobile_number']) || strlen($get_family_mobile_no['mobile_number']) < 10) ? null : $value['id']) :$value['id'];                                    

                }
    if(isset($sender_contact) && count($sender_contact)!=0){
        foreach ($sender_contact as $key => $value) {
            if($value !=null){
            /* check sent flag */
    	date_default_timezone_set('Asia/Kolkata');
    	$time = date('H:i:s',strtotime("9:00 AM"));
			if($time < date('H:i:s')){            
            $today = date('Y-m-d');
           
            $check_flag = SentSmsModel::where('template_id',$arr_template->id)
                                        ->where('contact_no',$value)
                                        ->where('user_id',$user_id[$key])
                                        ->where('created_at',$today)
                                        ->where('flag_id','1')
                                        ->first();
                // dd($user_id[$key]);                      
            if($check_flag==null){

                $contant = $arr_template->template_html;
                $contant = str_replace("##USERNAME##",$full_name[$key], $contant);      
                $username="vpawar";
                $password="Vpawar123";
                $route  = "trans1%20";
                $senderid = "PAWARM";

                $message=$contant;
                // dd($message);
                $sender="Voter"; //ex:INVITE GOT THIS ID FROM DASHBORAD
                $numbers=$value;
                
                $url="http://173.45.76.227/sendunicode.aspx?username=$username&pass=$password&route=$route&senderid=$senderid&numbers=$numbers&message=".urlencode($message);
                $ch = curl_init();
                $headers = array(
                        //'Accept: application/json',
                        'Content-type: text/html; charset=UTF-8',
                    );
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_URL, $url);                
                $output = curl_exec($ch); 
               
                if(curl_errno($ch))
                {
                    echo curl_error($ch);
                }
                else
                {
                    //echo 'done';
                }
                /*print_r($output);*/
                 $data = curl_close($ch); 
                 /* save sms record */ 
                 if($output){
                   $get_status = explode("|",$output);
                if($get_status[0] =='1'){                     
                    $sms['template_id'] = $arr_template->id;
                    $sms['contact_no'] = $value;
                    $sms['user_id'] = $user_id[$key];
                    $sms['created_at'] =$today;
                    $sms['flag'] ='1';
                    $save_sms = SentSmsModel::create($sms);
                    
                        /* send sms to admin */
                                $obj_admin_details  = WebAdmin::where('id','1')->first();
                                // \Log::info(json_encode($obj_admin_details->contact)); 
                                // exit;
                                $admin_no =null;
                                $admin_no = array($obj_admin_details->contact,'9423040408');   
                                foreach ($admin_no as $key_1 => $admin_con) {
                                        $contant = 'Following Todays Birthday '.$full_name[$key].' '.$value;                    
                                        $username="vpawar";
                                        $password="Vpawar123";
                                        $route  = "trans1%20";
                                        $senderid = "PAWARM";
                    
                                        $message=$contant;
                                        // dd($message);
                                        $sender="Voter"; //ex:INVITE GOT THIS ID FROM DASHBORAD
                                        $numbers=$admin_con;
                                        
                                        $url="http://173.45.76.227/sendunicode.aspx?username=$username&pass=$password&route=$route&senderid=$senderid&numbers=$numbers&message=".urlencode($message);
                                        $ch = curl_init();
                                        $headers = array(
                                                //'Accept: application/json',
                                                'Content-type: text/html; charset=UTF-8',
                                            );
                    
                                                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                    curl_setopt($ch, CURLOPT_URL, $url); 
                    
                                        $output = curl_exec($ch); 
                    
                                        if(curl_errno($ch))
                                        {
                                            echo curl_error($ch);
                                        }
                                        else
                                        {
                                            //echo 'done';
                                        }
                                        /*print_r($output);*/
                                         $data = curl_close($ch);
                                }                    
                }
                else if($get_status[0] =='4'){
                            $update_invalid_no = UsersModel::where('id',$user_id[$key])->first();
                            $update_invalid_no->is_invalid_contact = '1';
                            $update_invalid_no->save();
                }                 
                    // $save_sms_record = 
            }              
                \Log::info(json_encode($output));  
                
            // }
                       
                            
             }
             else{
                //echo "SMS Not sent";
                 \Log::info("SMS Not sent..!");             
             }                      
         }
         else{
             \Log::info("Not Correct Time..!"); 
         }
         
        }
            
        }

    }
         // dd($output);
    else{
        //echo "record found";
         \Log::info("No Record Found..!");
    }
        $this->info('BirthdayWish:Cron Cummand Run successfully!');
    }
}
