<?php
   
namespace App\Console\Commands;
   
use Illuminate\Console\Command;
use App\Models\AddCronScheduleModel;
use App\Models\UsersModel;
use App\Common\Traits\MultiActionTrait;
use App\Models\GroupModel;
use App\Models\SmsTemplateModel;
use App\Models\SentSmsModel;
use App\Models\CronScheduleListModel;
use Validator;
use Session;
use DataTables;
use DB;


class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';
    
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
    DB::beginTransaction();
        try
        {  

            $arr_data = AddCronScheduleModel::
                            where('event_date', [date('Y-d-m')])
                            ->where('flag_id', '0')
                            ->first();


        if(isset($arr_data)){

        
            
                $template_id = $arr_data->template_id;
                $sent_to  = $arr_data->sent_to;

                if($arr_data->group_id !=null){
                    $group_id = $arr_data->group_id;
                }
                /* get Contact List */
                $get_contact_list   = CronScheduleListModel::
                                                            where('cron_schedule_id',$arr_data->id)
                                                            ->where('flag_id','0')
                                                            ->where('is_invalid_contact','0')
                                                            ->limit(10)
                                                            ->get();
                                                          //  dd($get_contact_list); 
    
                /* get Template */
                $arr_template    = SmsTemplateModel::
                                        where('id',$template_id)
                                        ->first();

                if(isset($get_contact_list) && count($get_contact_list) ==0){
                    $get_cron = AddCronScheduleModel::where('id',$arr_data->id)->first();
                    $get_cron['flag_id'] = '1';
                    $get_cron->save();
                  \Log::info(json_encode("all Sms Sent"));
                }
                else{

                foreach ($get_contact_list as $key => $value) {
                            $contant = $arr_template->template_html;
                            $username="vpawar";
                            $password="Vpawar123";
                            $route  = "trans1%20";
                            $senderid = "PAWARM";

                            $message=$contant;
                            // dd($message);
                            $sender="Voter"; //ex:INVITE GOT THIS ID FROM DASHBORAD
                            $numbers=$value->contact_no;
                            
                            $url="http://173.45.76.227/sendunicode.aspx?username=$username&pass=$password&route=$route&senderid=$senderid&numbers=$numbers&message=".urlencode($message);
                            $ch = curl_init();
                            $headers = array(
                                    //'Accept: application/json',
                                    'Content-type: text/html; charset=UTF-8',
                                );
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            curl_setopt($ch, CURLOPT_HEADER, 0);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);                        
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

                            
                            if($output){
                                $get_status = explode("|",$output);
                                
                                     if($get_status[0] =='1'){
                                        $get_cron_schedule = CronScheduleListModel::
                                                                where('id',$value->id)
                                                                ->first();
                                        $get_cron_schedule->flag_id ='1';
                                        $get_cron_schedule->save();
                                        
                                        // $save_sms_record = 
                                      }  
                                else if($get_status[0] =='4'){
                                      $update_invalid_no = CronScheduleListModel::where('id',$value->id)
                                      ->first();
                                      $update_invalid_no->is_invalid_contact = '1';
                                      $update_invalid_no->save();
                                      }                                    
                            }
                        \Log::info(json_encode($output));  
                    }
                }   
                
                        // if(count($output)){
                        //     Session::flash('success', $this->module_title.' SMS Sent successfully.');
                        //     return redirect()->back();  
                        // }

                        // Session::flash('error', 'Error while updating '.$this->module_title.'.');
                        // return redirect()->back();    
            
            }                        
        }
        
        catch(\Exception $e)
        {
            DB::rollBack();
            \Log::emergency($e);
        }
         \Log::info("Cron Run");  
      
        $this->info('Demo:Cron Cummand Run successfully!');
    }
}