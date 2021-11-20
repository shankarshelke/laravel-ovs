<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GroupModel;
use App\Models\UsersModel;
use App\Common\Traits\MultiActionTrait;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use Session;
use DataTables;
use DB;


class GroupController extends Controller
{
	use MultiActionTrait;
	public function __construct(GroupModel $group_model)
	{
		$this->arr_view_data           = [];
		$this->GroupModel = $group_model;
		$this->admin_panel_slug   = config('app.project.admin_panel_slug');
		$this->admin_url_path     = url(config('app.project.admin_panel_slug'));
		$this->module_url_path    = $this->admin_url_path."/group";
		$this->module_title       = "Group";
		$this->module_view_folder = "admin.group";
		$this->module_icon        = "fa fa-book";
		$this->BaseModel          = $group_model;
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


	public function view($enc_id)
	{
		$id = base64_decode($enc_id);
		$arr_data = $this->GroupModel
						  ->where('parent_id',$id)
						  ->get();

		$this->arr_view_data['parent_module_icon']   = "fa-home";
		$this->arr_view_data['parent_module_title']  = "Dashboard";
		$this->arr_view_data['parent_module_url']    = $this->admin_url_path.'/dashboard';
		$this->arr_view_data['page_title']           = "Manage ".str_plural($this->module_title);
		$this->arr_view_data['module_title']         = "Manage ".str_plural($this->module_title);
		$this->arr_view_data['module_icon']          = $this->module_icon;
		$this->arr_view_data['module_url_path']      = $this->module_url_path;
		$this->arr_view_data['admin_panel_slug']     = $this->admin_panel_slug;
		$this->arr_view_data['arr_data']     = $arr_data;
		
		return view($this->module_view_folder.'.view',$this->arr_view_data);

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
		$arr_sms['flag_id']        = '1';

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
		$arr_rules['template_from']       = "required";
		$arr_rules['template_from_mail']  = "required";
		$arr_rules['template_subject']    = "required";
		$arr_rules['template_html']       = "required";

		$validator = validator::make($request->all(),$arr_rules);

		if ($validator->fails()) 
		{
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$template_name = $request->input('template_name', null);
		$id         = base64_decode($enc_id);


		$arr_template['template_name']      = $template_name;
		$arr_template['template_from']      = $request->input('template_from', null);
		$arr_template['template_from_mail'] = $request->input('template_from_mail', null);
		$arr_template['template_subject']   = $request->input('template_subject', null);
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
		if(isset($arr_search_column['group_name']) && $arr_search_column['group_name']!="")
		{
			$obj_email_templates = $obj_email_templates->where('group_name', 'LIKE', "%".$arr_search_column['group_name']."%");	
		}

		$obj_email_templates = $obj_email_templates->select(['id', 'group_name','created_at','parent_id']);

		$obj_email_templates           = $obj_email_templates
												->orderBy('created_at','desc')
												->groupBy('parent_id');

		if($obj_email_templates)
		{
			$json_result  = DataTables::of($obj_email_templates)->make(true);
			$build_result = $json_result->getData();

			foreach ($build_result->data as $key => $data) 
			{
				$built_view_href   = $this->module_url_path.'/view/'.base64_encode($data->parent_id);

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

					$build_result->data[$key]->group_name       = isset($data->group_name)? $data->group_name :'';				


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

	public function import(){

		$this->arr_view_data['parent_module_icon']  = "fa-home";
        $this->arr_view_data['parent_module_title'] = "Dashboard";
        $this->arr_view_data['parent_module_url']   = url('/').'/admin/dashboard';
		$this->arr_view_data['page_title']       	= 'Create '.str_singular($this->module_title);
		$this->arr_view_data['page_icon']        	= $this->module_icon;
		$this->arr_view_data['module_title']     	= 'Manage '.$this->module_title;
		$this->arr_view_data['sub_module_title'] 	= 'Import File '.$this->module_title;
		$this->arr_view_data['sub_module_icon']  	= 'fa fa-plus';
		$this->arr_view_data['module_icon']      	= $this->module_icon;
		$this->arr_view_data['admin_panel_slug'] 	= $this->admin_panel_slug;
		$this->arr_view_data['module_url_path']  	= $this->module_url_path;
		$this->arr_view_data['module_url']       	= $this->module_url_path;
		// dd($arr_districts);
		// dd(session('subadmin_id'));
		return view($this->module_view_folder.'.import',$this->arr_view_data);
	}

	public function import_file(Request $request){

    DB::beginTransaction();
        try
        {         
        if ($request->hasFile('file')) {
            $path = $request->file('file')->getRealPath();
            $data = \Excel::load($path)->get();
           
             set_time_limit(0);
           
             ini_set("memory_limit", -1);
            
            if ($data->count() !=0) {
            	// $unique_id = 0;
            	/* get parent name */
				$get_group_id = GroupModel::orderBy('id','desc')->first();
					if(isset($get_group_id)){
						$get_group_id = $get_group_id->parent_id;
							if(isset($get_group_id) && $get_group_id!=''){
									$get_group_id = $get_group_id +1;
									$parent_id = $get_group_id;
								}								
					}

					else{
							$parent_id = '1';
								// $parent_id = $parent_id[0];								
					}
                foreach ($data as $key => $value) {

                    foreach ($value as $key1 => $val) {
                    	
						$arr = null;
						
							// dd("Sdf");


						$arr['parent_id'] 			= 	$parent_id;
						$arr['group_name'] 			= 	$request->input('title');	
						$arr['contact_no'] 			= 	$val['contact'];
						$arr['contact_person_name'] = 	$val['name'];											
                        /* insert data */

                       	$check_phone_contact = $this->GroupModel
                       								->where('contact_no',$arr['contact_no'])
                       								->first();
	                       	if(empty($check_phone_contact)){
		                        $store = GroupModel::create($arr);
		                       		// $unique_id = $parent_id;                       		
	                       	}							 
						}
						   
                    }    
                }
            }
         
        DB::commit();
                if($store){
                  return redirect($this->module_url_path)->with('message', 'Added Successfully!');
                }
                else{
                    return  redirect($this->module_url_path)->with('message', 'SomeThing Went Wrong..!!!');
                }
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            \Log::emergency($e);
        }
        return redirect($this->module_url_path)->with('message', 'SomeThing Went Wrong..!!!');                       
		//return view($this->module_view_folder.'.import',$this->arr_view_data);
	}	    
 
}
