<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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


class AddCronScheduleController extends Controller
{

    use MultiActionTrait;
    public function __construct(AddCronScheduleModel $cron_schedule)
    {
        $this->arr_view_data           = [];
        $this->cron_schedule = $cron_schedule;
        $this->admin_panel_slug   = config('app.project.admin_panel_slug');
        $this->admin_url_path     = url(config('app.project.admin_panel_slug'));
        $this->module_url_path    = $this->admin_url_path."/cron_schedule";
        $this->module_title       = "Cron Schedule";
        $this->module_view_folder = "admin.cron_schedule";
        $this->module_icon        = "fa fa-book";
        $this->BaseModel          = $cron_schedule;
        $this->UserModel          = new UsersModel();
        $this->SmsTemplateModel  = new SmsTemplateModel();
        $this->ip_address         = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:false;  
        $this->auth               = auth()->guard('admin');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $this->arr_view_data['parent_module_icon']   = "fa-home";
        $this->arr_view_data['parent_module_title']  = "Dashboard";
        $this->arr_view_data['parent_module_url']    = $this->admin_url_path.'/dashboard';
        $this->arr_view_data['page_title']           = "Manage ".str_plural($this->module_title);
        $this->arr_view_data['module_title']         = "Manage ".str_plural($this->module_title);
        $this->arr_view_data['module_icon']          = $this->module_icon;
        $this->arr_view_data['module_url_path']      = $this->module_url_path;
        $this->arr_view_data['admin_panel_slug']     = $this->admin_panel_slug;
        
        return view($this->module_view_folder.'.index',$this->arr_view_data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $template =$this->SmsTemplateModel->where('flag_id','1')
                                    ->pluck('template_name','id');

        // $sql = 'select * from `contact_group` group by `parent_id`';  
        // $results = DB::select($sql);         
        // dd($results);                       //
         $group = GroupModel::
                            select('parent_id')
                            //->with('group_name')
                            ->groupBy('parent_id')
                            ->get();
        $group_arr = null;                  
        foreach ($group as $key => $value) {
                $query = GroupModel::where('parent_id',$value->parent_id)->first();
                
                $group_arr[] = array('id' =>$query->parent_id ,'group_name'=>$query->group_name);
         } 
         
        
        $this->arr_view_data['parent_module_icon']  = "fa-home";
        $this->arr_view_data['parent_module_title'] = "Dashboard";
        $this->arr_view_data['parent_module_url']   = $this->admin_url_path.'/dashboard';
        $this->arr_view_data['page_title']          = "Manage ".str_plural($this->module_title);
        $this->arr_view_data['module_title']        = "Manage ".str_plural($this->module_title);
        $this->arr_view_data['template']            = $template;
        $this->arr_view_data['group']            = $group_arr;
        $this->arr_view_data['module_icon']         = $this->module_icon;
        $this->arr_view_data['module_url']          = $this->module_url_path;
        $this->arr_view_data['module_url_path']     = $this->module_url_path;
        $this->arr_view_data['admin_panel_slug']    = $this->admin_panel_slug;
        $this->arr_view_data['sub_module_title']    = 'Add '.str_singular($this->module_title);
        $this->arr_view_data['sub_module_icon']     = 'fa fa-plus';

        return view($this->module_view_folder.'.create',$this->arr_view_data);
    }

    public function store(Request $request)
    {
// dd($request);

        $arr_rules      = $arr_sms = array();
        $status         = false;

        $arr_rules['template_id']  = "required";
        $arr_rules['event_date']  =  "required";
        // $arr_rules['date']    = "required";

        $validator = validator::make($request->all(),$arr_rules);

        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $template_id = $request->input('template_id', null);
        $event_date = date("Y-m-d", strtotime($request->input('event_date')));
        // $event_date = $request->input('event_date', null);
        $send_to = $request->input('send_to');
        
        // if($send_to =='group'){
        //     $group_id = $request->input('group_id', null);
        // }
        $arr_data['template_id'] = $template_id;
        $arr_data['event_date'] = $event_date;
        $arr_data['sent_to'] = $send_to;
        $arr_data['group_id'] = $request->input('group_id', null);
        $arr_data['status']        = '1';

        // $dose_exist = $this->BaseModel->where('template_name', '=', $template_name)->count();

        // if($dose_exist> 0 )
        // {
        //     Session::flash('error', $this->module_title.' with this name already exist.');
        //     return redirect()->back();
        // }
        
        $status = $this->BaseModel->create($arr_data);       

        if($status)
        {
            $contact_list = null;
            if($send_to =='all'){
                $get_user_details = UsersModel::whereNotNull('mobile_number')
                                                ->get();
                if(isset($get_user_details) && count($get_user_details) !=0){
                    foreach ($get_user_details as $key => $value) {
                    $contact_list['cron_schedule_id'] = $status->id;
                    if(strlen((string)$value->mobile_number) >= 10){
                        $contact_list['contact_no'] = $value->mobile_number;
                    }
                   
                    $contact_list['user_id']   = $value->id;
                    $save_user_list_cron = CronScheduleListModel::create($contact_list);
                    }

                }                                
            }
            else if($send_to =='group') {
                $group_id = $request->input('group_id');
                $get_group_detail = GroupModel::where('parent_id',$group_id)
                                        ->get();
                 foreach ($get_group_detail as $key => $value_1) {
                    $contact_list['cron_schedule_id'] = $status->id;
                    if(strlen((string)$value_1->contact_no) >= 10){
                    $contact_list['contact_no'] = $value_1->contact_no;
                    }
                    $contact_list['user_id']   = $value_1->id;
                    $save_user_list_cron = CronScheduleListModel::create($contact_list);
                }                       
                                        

            }    
            Session::flash('success', $this->module_title.' added successfully.');
            return redirect($this->module_url_path);
        }

        Session::flash('error', 'Error while adding '.$this->module_title.'.');
        return redirect()->back();
    }



    public function load_data(Request $request)
    {
        $arr_search_column      = $request->input('column_filter');


        $obj_data = $this->BaseModel;
        // if(isset($arr_search_column['group_name']) && $arr_search_column['group_name']!="")
        // {
        //     $obj_email_templates = $obj_email_templates->where('group_name', 'LIKE', "%".$arr_search_column['group_name']."%"); 
        // }

        $obj_data = $obj_data->select(['id', 'template_id','event_date','sent_to','group_id','created_at','status'])->with('get_template_name','get_group_name');

        $obj_data           = $obj_data
                                                ->orderBy('created_at','desc');
                                                // ->groupBy('parent_id');

        if($obj_data)
        {
            $json_result  = DataTables::of($obj_data)->make(true);
            $build_result = $json_result->getData();

            foreach ($build_result->data as $key => $data) 
            {
                $built_view_href   = $this->module_url_path.'/view/'.base64_encode($data->id);

                $built_bank_details_href   = $this->module_url_path.'/delete/'.base64_encode($data->id);

                if(isset($build_result->data) && sizeof($build_result->data)>0)
                {
                    if(get_admin_access('email_template','edit')){
                    $built_view_button = "<a class='btn btn-default btn-rounded show-tooltip' href='".$built_view_href."' title='Edit' data-original-title='Edit'><i class='fa fa-eye' ></i> View</a>";
                    }
                    else
                    {
                        $built_view_button = '';
                    }
                    
                    $action_button_html = '<ul class="action-list-main">';
                    $action_button_html .= '<li class="dropdown">';
                    $action_button_html .= '<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"> <i class="ti-menu"></i><span><i class="fa fa-caret-down"></i></span></a>';
                    $action_button_html .= '<ul class="action-drop-section dropdown-menu dropdown-menu-right">';
                    $action_button_html .= '<li>'.$built_view_button.'</li>';                    
                    $action_button_html .= '</ul>';
                    $action_button_html .= '</li>';
                    $action_button_html .= '</ul>';

                    $action_button = $built_view_button;

                    $id = isset($data->id)? base64_encode($data->id) :'';

                    $build_result->data[$key]->id                  = $id;               

                    $build_result->data[$key]->event_date       = isset($data->event_date)? $data->event_date :'';              

                    $build_result->data[$key]->template_id       = isset($data->get_template_name->template_name)? $data->get_template_name->template_name :'';  


                    $build_result->data[$key]->sent_to       = isset($data->sent_to)? $data->sent_to :'';   

                    $build_result->data[$key]->group_id       = isset($data->get_group_name->group_name)? $data->get_group_name->group_name :'';   


                    $build_result->data[$key]->created_at          = isset($data->created_at)? get_formated_date($data->created_at) :'';

                    $build_result->data[$key]->built_action_button = $action_button_html;

                }
            }
            return response()->json($build_result);
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function send_sms_to_user(Request $request){

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
                                                            ->limit(10)
                                                            ->get();
                                                          //  dd($get_contact_list); 
    
                /* get Template */
                $arr_template    = $this->SmsTemplateModel
                                        ->where('id',$template_id)
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
                            }
                          
                    }
                }   
                \Log::info(json_encode($output));
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
        return redirect()->back();              
        
    }


}
