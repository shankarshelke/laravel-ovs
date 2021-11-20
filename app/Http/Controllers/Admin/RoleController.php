<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Common\Traits\MultiActionTrait;

use App\Models\RolesModel;

use Validator;
use Session;
use DataTables;
use Response;
use DB; 

class RoleController extends Controller
{
	use MultiActionTrait;
    function __construct()
    {
		$this->arr_view_data                = [];
		$this->admin_panel_slug             = config('app.project.admin_panel_slug');
		$this->admin_url_path               = url(config('app.project.admin_panel_slug'));
		$this->module_url_path              = $this->admin_url_path."/user_role";
		$this->module_title                 = trans('user_role.user role');
		$this->module_view_folder           = "admin.user_role";
		$this->module_icon                  = "fa fa-user";
		$this->auth                         = auth()->guard('admin');
		$this->BaseModel					= new RolesModel();
		$this->user_profile_base_img_path   = base_path().config('app.project.img_path.user_profile_image');
		$this->user_profile_public_img_path = url('/').config('app.project.img_path.user_profile_image');
		$this->user_image_base_path         = base_path().config('app.project.img_path.user_image');
		$this->user_image_public_path 		= url('/').config('app.project.img_path.user_image');
    }

    public function index()
    {     //dd('user role');
		$this->arr_view_data['page_title']          = trans('user_role.manage'). ' ' .trans('user_role.user role');
        $this->arr_view_data['parent_module_icon']  = "fa-home";
        $this->arr_view_data['parent_module_title'] = trans('user_role.dashboard');
        $this->arr_view_data['parent_module_url']   = url('/').'/admin/dashboard';
        $this->arr_view_data['module_icon']         = $this->module_icon;
        $this->arr_view_data['module_title']        = trans('user_role.manage').' ' .trans('user_role.user role');
		$this->arr_view_data['module_url_path']     = $this->module_url_path;
		$this->arr_view_data['admin_url_path']      = $this->admin_url_path;
		$this->arr_view_data['admin_panel_slug']    = $this->admin_panel_slug;
		//dd($this->arr_view_data);
		return view($this->module_view_folder.'.index',$this->arr_view_data);

    }

    public function load_data(Request $request,$type=null)
    {
    	$obj_user = $build_status_btn = $built_download_button = $search_country = "";

		$arr_search_column = $request->input('column_filter');
		$obj_user = $this->BaseModel;


		if(isset($arr_search_column['role']) && $arr_search_column['role']!=""){
			$obj_user = $obj_user->where('role', 'LIKE', "%".$arr_search_column['role']."%");	
		}

		if(isset($arr_search_column['description']) && $arr_search_column['description']!=""){
			$obj_user = $obj_user->where('description', 'LIKE', "%".$arr_search_column['description']."%");	
		}

		
		if(isset($arr_search_column['status']) && $arr_search_column['status']!=""){
			$obj_user = $obj_user->where('status', 'LIKE', "%".$arr_search_column['status']."%");	
		}
		

		$obj_user = $obj_user->orderBy('created_at','asc');

		if($obj_user)
		{
			$json_result  = DataTables::of($obj_user)->make(true);
			$build_result = $json_result->getData();

			foreach ($build_result->data as $key => $data) 
			{
				if(isset($build_result->data) && sizeof($build_result->data)>0)
				{

					$built_view_href          = $this->module_url_path.'/view/'.base64_encode($data->id);
                    $built_transaction_href   = $this->module_url_path.'/transaction/'.base64_encode($data->id);
					$built_edit_href          = $this->module_url_path.'/edit/'.base64_encode($data->id);
					$built_delete_href        = $this->module_url_path.'/delete/'.base64_encode($data->id);
				    $built_download_href      = $this->module_url_path.'/download/'.base64_encode($data->id);


					if($data->status != null && $data->status == "0")
					{
						if(get_admin_access('user_role','approve'))
						{
							$build_status_btn = '<a class="label label-danger label-mini" title="Inactive" href="'.$this->module_url_path.'/unblock/'.base64_encode($data->id).'" 
								onclick="return confirm_action(this,event,\'Do you really want to activate this record ?\')" >Inactive</a>';	
						}
						else
						{
							$build_status_btn = '<span class="label label-danger label-mini">Inactive</span>';
						}
						

						
					}
					elseif($data->status != null && $data->status == "1")
					{
						if(get_admin_access('user_role','approve'))
						{
							$build_status_btn = '<a class="label label-success label-mini" title="Active" href="'.$this->module_url_path.'/block/'.base64_encode($data->id).'" onclick="return confirm_action(this,event,\'Do you really want to inactivate this record ?\')" >Active</a>';
						}
						else
						{
							$build_status_btn = '<span class="label label-success label-mini">active</span>';
						}

					}

					
					if(get_admin_access('user_role','view'))
					{
						$built_view_button = "<a class='btn btn-default btn-rounded btn-sm show-tooltip' href='".$built_view_href."' title='View' data-original-title='View'><i class='fa fa-eye' ></i> View</a>";
					}
					else
					{
						$built_view_button = '';
					}

					if(get_admin_access('user_role','edit'))
					{
						$built_edit_button 	  = "<a class='btn btn-default btn-sm edit_button' href='javascript:void(0);' title='Edit' data-id=".base64_encode($data->id)."><i class='fa fa-pencil-square-o' ></i> Edit</a>";
					}
					else
					{
						$built_edit_button = '';
					}

				
					if(get_admin_access('user_role','delete'))
					{
						$built_delete_button  = "<a class='btn btn-default btn-sm' href='".$built_delete_href."' title='Delete' onclick='return confirm_action(this,event,\"Do you really want to delete this record ?\")'><i class='fa fa-trash-o' ></i> Delete</a>";
					}
					else
					{
						$built_delete_button = '';
					}

                    $action_button_html = '<ul class="action-list-main">';
                    $action_button_html .= '<li class="dropdown">';
                    $action_button_html .= '<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"> <i class="ti-menu"></i><span><i class="fa fa-caret-down"></i></span></a>';
                    $action_button_html .= '<ul class="action-drop-section dropdown-menu dropdown-menu-right">';
                    $action_button_html .= '<li>'.$built_view_button.'</li>';
                    $action_button_html .= '<li>'.$built_edit_button.'</li>';
                    $action_button_html .= '<li>'.$built_delete_button.'</li>';
                    $action_button_html .= '</ul>';
                    $action_button_html .= '</li>';
                    $action_button_html .= '</ul>';
					
					$id = isset($data->id)? base64_encode($data->id):'';
					
					$role           = isset($data->role) && $data->role!="" ? $data->role:'';
					$description    = isset($data->description) && $data->description!="" ? $data->description:'';
					
					$created_at          = isset($data->created_at)?$data->created_at:'';
					$built_action_button = $built_view_button.$built_edit_button.$built_delete_button;
					
					$build_result->data[$key]->id         		   = $id;			
					$build_result->data[$key]->role                = $role;
					$build_result->data[$key]->description         = $description;
					$build_result->data[$key]->build_status_btn    = $build_status_btn;
					$build_result->data[$key]->built_action_button = $action_button_html;
			
				}
			}
			return response()->json($build_result);
		}
	
    } 


 

 public function create()
	{
		

		$this->arr_view_data['parent_module_icon']   = "fa-home";
        $this->arr_view_data['parent_module_title']  = trans('user_role.dashboard');
        $this->arr_view_data['parent_module_url']    = url('/').'/admin/dashboard';
		$this->arr_view_data['page_title']           = trans('user_role.create').' '.str_singular(trans('user_role.user role'));
		$this->arr_view_data['page_icon']            = $this->module_icon;
		$this->arr_view_data['module_title']         = trans('user_role.manage') .' '. trans('user_role.user role');
		$this->arr_view_data['sub_module_title']     = trans('user_role.create user role');
		$this->arr_view_data['sub_module_icon']      = 'fa fa-plus';
		$this->arr_view_data['module_icon']          = $this->module_icon;
		$this->arr_view_data['admin_panel_slug']     = $this->admin_panel_slug;
		$this->arr_view_data['module_url_path']      = $this->module_url_path;
		$this->arr_view_data['module_url']           = $this->module_url_path;
	   // dd($this->arr_view_data);
		return view($this->module_view_folder.'.create',$this->arr_view_data);
	}

	public function store(Request $request)
	{ //dd('store user role');
		
		$arr_rules      = $arr_data = array();
		$status         = false;

		$arr_rules['role']  	             = "required";
		$arr_rules['description']  	         = "required";
		
	
		
		$validator = validator::make($request->all(),$arr_rules);

		if($validator->fails()) 
		{
			/*dd($validator->messages());	*/
			return redirect()->back()->withErrors($validator)->withInput();
			
		}
		
		$arr_data['role']		    = $request->input('role', null);
		$arr_data['description']    = $request->input('description', null);
			
    
		$status = $this->BaseModel->create($arr_data);
		//dd($status);

		if($status)
		{
			Session::flash('success',str_singular(trans('user_role.user role')).' '.trans('user_role.created successfully'));
			return redirect($this->module_url_path);
		}
		Session::flash('error', trans('user_role.error while creating').' '.str_singular(trans('user_role.user role')).'.');
		return redirect($this->module_url_path.'/create');
	}

 
	public function view($enc_id)
	{
		$arr_user = []; 
		$arr_list = [];

		$role_id  = base64_decode($enc_id);
		// dd($role_id);
		if(isset($role_id) && $role_id!="")
		{
			$obj_user = $this->BaseModel->where('id','=',$role_id)->first();
			if($obj_user)
			{//dd($obj_user);
			  $arr_user = $obj_user->toArray();
		    }
			
		}
        $this->arr_view_data['arr_user']                     = $arr_user;
		$this->arr_view_data['parent_module_icon']           = "fa-home";
		$this->arr_view_data['parent_module_title']          = trans('user_role.dashboard');
		$this->arr_view_data['parent_module_url']            = $this->admin_url_path.'/dashboard';
		$this->arr_view_data['module_title']                 = trans('user_role.user role');
		$this->arr_view_data['module_icon']                  = $this->module_icon;
		$this->arr_view_data['module_url']                   = $this->module_url_path;
		$this->arr_view_data['admin_panel_slug']             = $this->admin_panel_slug;
		$this->arr_view_data['sub_module_title']             = trans('user_role.view').' '.trans('user_role.user role');
		$this->arr_view_data['sub_module_icon']              = 'fa fa-eye';
		$this->arr_view_data['module_url_path']              = $this->module_url_path;
		
		
		return view($this->module_view_folder.'.view',$this->arr_view_data);
	}

	public function edit($enc_id='')
	{
		$arr_data = $arr_resp = [];
		$id = base64_decode($enc_id);
     // dd($id);
		if(is_numeric($id))
		{ //dd(is_numeric($id));

			$obj_data = $this->BaseModel->where('id',$id)->first();
          //dd($obj_data);
			if(isset($obj_data))
			{
				$arr_data = $obj_data->toArray();
				$arr_resp['status'] = "success";
				$arr_resp['msg'] 	= "Data displayed successfully";
				$arr_resp['data']   = $arr_data;
				return $arr_resp;
	//	dd($arr_data);
			 }else{
			 	$arr_resp['status'] = "error";
				$arr_resp['msg']    = "Something went wrong";
				$arr_resp['data']   = $arr_data;
				return $arr_resp;
			 }
		}
		$arr_resp['status']  = "error";
		$arr_resp['msg']  	 = "Something went wrong";
		$arr_resp['data']    = $arr_data;
		return $arr_resp;
	}
	



	public function update(Request $request,$id='')
	{
		//dd($request->all());

		$arr_rules      = $arr_data = array();
		$status         = false;

	
		$arr_rules['role']      	   	    = "required"; 
		$arr_rules['description']  	        = "required";
		
		//dd($arr_rules);


		$validator = validator::make($request->all(),$arr_rules);

		if($validator->fails()) 
		{
			
			return redirect()->back()->withErrors($validator)->withInput();
			
		}

		$id 		                 =$request->input('enc_id');
		$arr_data['role']		     = $request->input('role', null);
		$arr_data['description']     = $request->input('description', null);	
		//dd($id );
		$status = $this->BaseModel->where('id',$id)->update($arr_data);
//dd($status);
		if($status)
		{
			Session::flash('success',trans('user_role.user role').' '.trans('user_role.updated successfully'));
			return redirect($this->module_url_path);
		}
		Session::flash('error',trans('user_role.error while updating').' '.trans('user_role.user role').'.');
		return redirect($this->module_url_path);
	}
    
}