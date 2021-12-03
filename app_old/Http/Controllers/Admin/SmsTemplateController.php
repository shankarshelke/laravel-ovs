<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SmsTemplateModel;
use App\Models\UsersModel;
use App\Common\Traits\MultiActionTrait;
use App\Models\GroupModel;
use Validator;
use Session;
use DataTables;
use DB;


class SmsTemplateController extends Controller
{
	use MultiActionTrait;
	public function __construct(SmsTemplateModel $sms_template_model)
	{
		$this->arr_view_data           = [];
		$this->SmsTemplateModel = $sms_template_model;
		$this->admin_panel_slug   = config('app.project.admin_panel_slug');
		$this->admin_url_path     = url(config('app.project.admin_panel_slug'));
		$this->module_url_path    = $this->admin_url_path."/sms_template";
		$this->module_title       = "SMS Template";
		$this->module_view_folder = "admin.sms_template";
		$this->module_icon        = "fa fa-book";
		$this->BaseModel          = $sms_template_model;
		$this->UserModel          = new UsersModel();
		$this->ip_address         = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:false;  
		$this->auth               = auth()->guard('admin');
	}

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

	public function create()
	{
		$this->arr_view_data['parent_module_icon']  = "fa-home";
		$this->arr_view_data['parent_module_title'] = "Dashboard";
		$this->arr_view_data['parent_module_url']   = $this->admin_url_path.'/dashboard';
		$this->arr_view_data['page_title']          = "Manage ".str_plural($this->module_title);
		$this->arr_view_data['module_title']        = "Manage ".str_plural($this->module_title);
		$this->arr_view_data['module_icon']         = $this->module_icon;
		$this->arr_view_data['module_url']     	    = $this->module_url_path;
		$this->arr_view_data['module_url_path']     = $this->module_url_path;
		$this->arr_view_data['admin_panel_slug']    = $this->admin_panel_slug;
		$this->arr_view_data['sub_module_title']    = 'Add '.str_singular($this->module_title);
		$this->arr_view_data['sub_module_icon']     = 'fa fa-plus';

		return view($this->module_view_folder.'.create',$this->arr_view_data);
	}

	public function store(Request $request)
	{

		$arr_rules      = $arr_sms = array();
		$status         = false;

		$arr_rules['template_name']  =  "required";
		$arr_rules['template_html']  =  "required";

		$validator = validator::make($request->all(),$arr_rules);

		if ($validator->fails()) 
		{
			return redirect()->back()->withErrors($validator)->withInput();
		}


		$template_name = $request->input('template_name', null);
		$template_html = $request->input('template_html', null);

		$arr_sms['template_name'] = $template_name;
		$arr_sms['template_html'] = $template_html;
		$arr_sms['status']        = '1';
		$arr_sms['flag_id']       = '1';
		// dd($arr_sms);
		$dose_exist = $this->BaseModel->where('template_name', '=', $template_name)->count();

		if($dose_exist> 0 )
		{
			Session::flash('error', $this->module_title.' with this name already exist.');
			return redirect()->back();
		}
		
		$status = $this->BaseModel->create($arr_sms);		

		if($status)
		{
			/*-------------------------------------------------------
	        |   Activity log Event
	        --------------------------------------------------------*/
	        // $arr_event                     = [];
	        // $arr_event['ACTIVITY_MESSAGE'] = str_singular($this->module_title).' Created By '.login_name($this->admin_panel_slug)."";
	        // $arr_event['IP_ADDRESS']       = $this->ip_address;
	        // $arr_event['ACTION']           = 'Create';
	        // $arr_event['MODULE_TITLE']     = $this->module_title;
	        // $this->save_activity($arr_event);
	        /*----------------------------------------------------------------------*/
			Session::flash('success', $this->module_title.' added successfully.');
			return redirect($this->module_url_path);
		}

		Session::flash('error', 'Error while adding '.$this->module_title.'.');
		return redirect()->back();
	}

	public function edit($enc_id)
	{
		$id = base64_decode($enc_id);
		$arr_email_template = $arr_variables = [];

		$obj_email_template = $this->BaseModel->where('id',$id)->select('*')->first();
		
		if($obj_email_template)
		{
			$arr_email_template = $obj_email_template->toArray();	
		}

		$arr_variables = isset($arr_email_template['template_variables']) && !empty($arr_email_template['template_variables']) ? explode("~",$arr_email_template['template_variables']):array();
		
		$this->arr_view_data['arr_variables']       = $arr_variables;
		$this->arr_view_data['arr_email_template']  = $arr_email_template;
		$this->arr_view_data['id']                  = $enc_id;
		$this->arr_view_data['page_title']          = "Edit ".str_singular($this->module_title);
		$this->arr_view_data['parent_module_icon']  = "fa-home";
		$this->arr_view_data['parent_module_title'] = "Dashboard";
		$this->arr_view_data['parent_module_url']   = $this->admin_url_path.'/dashboard';
		$this->arr_view_data['module_title']        = str_plural($this->module_title);
		$this->arr_view_data['module_icon']         = $this->module_icon;
		$this->arr_view_data['module_icon']         = $this->module_icon;
		$this->arr_view_data['module_url']          = $this->module_url_path;
		$this->arr_view_data['admin_panel_slug']    = $this->admin_panel_slug;
		$this->arr_view_data['sub_module_title']    = 'Edit '.str_singular($this->module_title);
		$this->arr_view_data['sub_module_icon']     = 'fa fa-pencil-square-o';

		$this->arr_view_data['module_url_path']     = $this->module_url_path;

		return view($this->module_view_folder.'.edit',$this->arr_view_data);
	}

	public function update(Request $request, $enc_id)
	{
		$arr_rules      = $arr_template = array();
		$status         = false;
		
		$arr_rules['template_name']       = "required";
		$arr_rules['template_html']       = "required";

		$validator = validator::make($request->all(),$arr_rules);

		if ($validator->fails()) 
		{
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$template_name = $request->input('template_name', null);
		$id         = base64_decode($enc_id);


		$arr_template['template_name']      = $template_name;
		$arr_template['template_html']      = $request->input('template_html', null);

		$dose_exist = $this->BaseModel->where('template_name', '=', $template_name)->where('id', '!=', $id)->count();

		if($dose_exist> 0 )
		{
			Session::flash('error', $this->module_title.' with this name already exist.');
			return redirect()->back();
		}
		$status = $this->BaseModel->where('id', $id)->update($arr_template);		

		if($status)
		{
			Session::flash('success', $this->module_title.' updated successfully.');
			return redirect($this->module_url_path);
		}

		Session::flash('error', 'Error while updating '.$this->module_title.'.');
		return redirect()->back();

	}

	public function load_data(Request $request)
	{
		$arr_search_column      = $request->input('column_filter');


		$obj_email_templates = $this->BaseModel;
		if(isset($arr_search_column['q_template_name']) && $arr_search_column['q_template_name']!="")
		{
			$obj_email_templates = $obj_email_templates->where('template_name', 'LIKE', "%".$arr_search_column['q_template_name']."%");	
		}

		$obj_email_templates = $obj_email_templates->select(['id', 'template_name','created_at']);

		$obj_email_templates           = $obj_email_templates
												->where('flag_id','1')
												->orderBy('created_at','desc');
		if($obj_email_templates)
		{
			$json_result  = DataTables::of($obj_email_templates)->make(true);
			$build_result = $json_result->getData();

			foreach ($build_result->data as $key => $data) 
			{
				$built_view_href   = $this->module_url_path.'/edit/'.base64_encode($data->id);

				$built_bank_details_href   = $this->module_url_path.'/delete/'.base64_encode($data->id);

				if(isset($build_result->data) && sizeof($build_result->data)>0)
				{
					if(get_admin_access('email_template','edit')){
					$built_view_button = "<a class='btn btn-default btn-rounded show-tooltip' href='".$built_view_href."' title='Edit' data-original-title='Edit'><i class='fa fa-pencil-square-o' ></i> Edit</a>";
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

					$build_result->data[$key]->template_name       = isset($data->template_name)? $data->template_name :'';				


					$build_result->data[$key]->created_at          = isset($data->created_at)? get_formated_date($data->created_at) :'';

					$build_result->data[$key]->built_action_button = $action_button_html;

				}
			}
			return response()->json($build_result);
		}
	}

	 public function preview(Request $request)
    {
        $form_data = [];

        $content ="";
        
        $form_data = $request->all();

        if(isset($form_data['preview_html']) && !empty($form_data['preview_html']))
        {
            if(isset($form_data['preview_html']) && !empty($form_data['preview_html']))
            {
                $content = html_entity_decode($form_data['preview_html']);
                return view('admin.email.general',compact('content'))->render();    
            }
            else
            {
                Session::flash('error','Please enter '.str_singular($this->module_title).' content');
                return redirect()->back();       
            }
        }
        else
        {
            Session::flash('error','Problem occured while showing '.str_singular($this->module_title).' preview');
            return redirect()->back();
        }
    }
    public function send_sms(){

    	$template =$this->BaseModel->where('flag_id','1')
    								->pluck('template_name','id');

		// $sql = 'select * from `contact_group` group by `parent_id`';  
		// $results = DB::select($sql);  		
		// dd($results);					   //
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
		$this->arr_view_data['module_url']     	    = $this->module_url_path;
		$this->arr_view_data['module_url_path']     = $this->module_url_path;
		$this->arr_view_data['admin_panel_slug']    = $this->admin_panel_slug;
		$this->arr_view_data['sub_module_title']    = 'Add '.str_singular($this->module_title);
		$this->arr_view_data['sub_module_icon']     = 'fa fa-plus';

		return view($this->module_view_folder.'.sendsms',$this->arr_view_data);
    }     
 
    public function send_sms_to_user(Request $request){
		$arr_rules['template_id']       = "required";
		$arr_rules['send_to']       = "required";
		$validator = validator::make($request->all(),$arr_rules);

		if ($validator->fails()) 
		{
			return redirect()->back()->withErrors($validator)->withInput();
		}
    DB::beginTransaction();
        try
        {  		
			$template_id = $request->input('template_id');
			$sent_to  = $request->input('send_to');
			if($request->has('group_id')){
				$group_id = $request->group_id;
			}

			/* get Template */
			$arr_template    = $this->SmsTemplateModel
									->where('id',$template_id)
									->first();
								// dd($sent_to);	
			/* send Sms To all User */
			if($sent_to =='all'){
				$get_user_detail = UsersModel::select('mobile_number')
												->get();
												// dd($get_user_detail);
				if(isset($get_user_detail) && count($get_user_detail)!=0 && isset($arr_template)){
					// dd($get_user_detail);
					foreach ($get_user_detail as $key => $value) {
						$contact = $value['mobile_number'];

						$contant = $arr_template->template_html;					
				    	$username="vpawar";
						$password="Vpawar123";
						$route  = "trans1%20";
						$senderid = "PRINFO";

						$message=$contant;
						// dd($message);
						$sender="Voter"; //ex:INVITE GOT THIS ID FROM DASHBORAD
				        $numbers=$contact;
				        
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
				        $output[] = curl_exec($ch); 

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
					// dd($output);
					if(count($output)){
							Session::flash('success', $this->module_title.' SMS Sent successfully.');
							return redirect()->back();	
					}

						Session::flash('error', 'Error while updating '.$this->module_title.'.');
						return redirect()->back();							
							
				}									
			}
			if($sent_to =='group'){
				/*get group Detail */

				$group_detail = GroupModel::where('parent_id',$group_id)
											->get();
				if(isset($group_detail) && count($group_detail)!=0 && isset($arr_template)){
					foreach ($group_detail as $key => $valgp) {
						$contact = $valgp['contact_no'];

						$contant = $arr_template->template_html;					
				    	$username="vpawar";
						$password="Vpawar123";
						$route  = "trans1%20";
						$senderid = "PRINFO";

						$message=$contant;
						// dd($message);
						$sender="Voter"; //ex:INVITE GOT THIS ID FROM DASHBORAD
				        $numbers=$contact;
				        
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
				        $output[] = curl_exec($ch); 
				       	
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
						 dd($output);
						if(count($output)){
							Session::flash('success', $this->module_title.' SMS Sent successfully.');
							return redirect()->back();	
						}

						Session::flash('error', 'Error while updating '.$this->module_title.'.');
						return redirect()->back();										
					}
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
