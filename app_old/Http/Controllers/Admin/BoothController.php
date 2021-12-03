<?
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Common\Traits\MultiActionTrait;

use App\Models\UsersModel;
use App\Models\DistrictModel;
use App\Models\CityModel;
use App\Models\ListModel;

use App\Models\VillageModel;
use App\Models\ReligionModel;
use App\Models\BoothModel;
use App\Models\WardsModel;
use Validator;
use Session;
use DataTables;
use Response;
use DB; 

class BoothController extends Controller
{
use MultiActionTrait;
    function __construct()
    {
		$this->arr_view_data                = [];
		$this->admin_panel_slug             = config('app.project.admin_panel_slug');
		$this->admin_url_path               = url(config('app.project.admin_panel_slug'));
		$this->module_url_path              = $this->admin_url_path."/voting_booth";
		$this->module_title                 = "Voting Booth and List";
		$this->module_view_folder           = "admin.booth";
		$this->module_icon                  = "fa fa-user";
		$this->auth                         = auth()->guard('admin');

		$this->BaseModel					= new BoothModel();
		$this->ListModel					= new ListModel();
		$this->DistrictModel				= new DistrictModel();
		$this->CityModel					= new CityModel();
		$this->VillageModel					= new VillageModel();
		$this->WardsModel					= new WardsModel();
		

		$this->user_profile_base_img_path   = base_path().config('app.project.img_path.user_profile_image');
		$this->user_profile_public_img_path = url('/').config('app.project.img_path.user_profile_image');
		$this->user_image_base_path         = base_path().config('app.project.img_path.user_image');
		$this->user_image_public_path 		= url('/').config('app.project.img_path.user_image');
    }

    public function index()
    {
    	$arr_ward = [];
    	$arr_data = [];

		$obj_ward = $this->WardsModel->get();
		if($obj_ward)
		{
		$arr_ward = $obj_ward->toArray();
		}
		$obj_data = $this->BaseModel->with('get_ward_details')->first();
		$obj_wards = $this->WardsModel->get();
		if($obj_data)
		{
		$arr_data = $obj_data->toArray();
		$arr_ward = $obj_wards->toArray();
		$this->arr_view_data['page_title']          = "Manage ".$this->module_title;
        $this->arr_view_data['parent_module_icon']  = "fa-home";
        $this->arr_view_data['parent_module_title'] = "Dashboard";
        $this->arr_view_data['parent_module_url']   = url('/').'/admin/dashboard';
        $this->arr_view_data['module_icon']         = $this->module_icon;
        $this->arr_view_data['module_title']        = "Manage ".$this->module_title;
		$this->arr_view_data['module_url_path']     = $this->module_url_path;
		$this->arr_view_data['admin_url_path']      = $this->admin_url_path;
		$this->arr_view_data['admin_panel_slug']    = $this->admin_panel_slug;
		$this->arr_view_data['arr_ward']    	   = $arr_ward;
		$this->arr_view_data['arr_data']    	   = $arr_data;
	     

		return view($this->module_view_folder.'.index',$this->arr_view_data);
    }
}

    public function load_data(Request $request,$type=null)
    {
    	$obj_user = $build_status_btn = $built_download_button = $search_country = "";
		$arr_search_column = $request->input('column_filter');
		$obj_user = $this->BaseModel;


		if(isset($arr_search_column['booth_no']) && $arr_search_column['booth_no']!=""){
			$obj_user = $obj_user->where('booth_no', 'LIKE', "%".$arr_search_column['booth_no']."%");	
		}

		if(isset($arr_search_column['booth_name']) && $arr_search_column['booth_name']!=""){
			$obj_user = $obj_user->where('booth_name', 'LIKE', "%".$arr_search_column['booth_name']."%");	
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

					$built_view_href   = $this->module_url_path.'/view/'.base64_encode($data->id);

					$built_edit_href   = $this->module_url_path.'/edit/'.base64_encode($data->id);

					$built_delete_href 	= $this->module_url_path.'/delete/'.base64_encode($data->id);

					if($data->status != null && $data->status == "0")
					{
						if(get_admin_access('voting_booth','approve'))
						{
							$build_status_btn = '<a class="label label-danger label-mini" title="Inactive" href="'.$this->module_url_path.'/unblock/'.base64_encode($data->id).'"onclick="return confirm_action(this,event,\'Do you really want to activate this record ?\')" >Inactive</a>';	
						}
						else
						{
							$build_status_btn = '<span class="label label-danger label-mini">Inactive</span>';
						}						
					}
					elseif($data->status != null && $data->status == "1")
					{
						if(get_admin_access('voting_booth','approve'))
						{
							$build_status_btn = '<a class="label label-success label-mini" title="Active" href="'.$this->module_url_path.'/block/'.base64_encode($data->id).'" onclick="return confirm_action(this,event,\'Do you really want to inactivate this record ?\')" >Active</a>';
						}
						else
						{
							$build_status_btn = '<span class="label label-success label-mini">active</span>';
						}
					}					
					if(get_admin_access('voting_booth','view'))
					{
						$built_view_button = "<a class='btn btn-default btn-rounded btn-sm show-tooltip' href='".$built_view_href."' title='View' data-original-title='View'><i class='fa fa-eye' ></i> View</a>";
					}
					else
					{
						$built_view_button = '';
					}
					/*if(get_admin_access('voting_booth','edit'))
					{
						$built_edit_button 	  = "<a class='btn btn-default btn-sm edit_button' href='javascript:void(0)' title='Edit' ><i class='fa fa-pencil-square-o' ></i> Edit</a>";
					}
					else
					{
						$built_edit_button = '';
					}	*/	

					 if(get_admin_access('voting_booth','edit'))
                    {
                        $built_edit_button    = "<a class='btn btn-default btn-sm edit_button' href='javascript:void(0);' title='Edit' data-id=".base64_encode($data->id)."><i class='fa fa-pencil-square-o' ></i> Edit</a>";
                    }
                    else
                    {
                        $built_edit_button = '';
                    }		
					if(get_admin_access('voting_booth','delete'))
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
					$booth_no      = isset($data->booth_no) && $data->booth_no!="" ? $data->booth_no:'';
					$booth_name    = isset($data->booth_name) && $data->booth_name!="" ? $data->booth_name:'';
					
					$created_at         = isset($data->created_at)?$data->created_at:'';
					$built_action_button = $built_view_button.$built_edit_button.$built_delete_button;

				    $build_result->data[$key]->id         		   = $id;
					$build_result->data[$key]->booth_no            = $booth_no;
					$build_result->data[$key]->booth_name          = $booth_name;
					$build_result->data[$key]->build_status_btn    = $build_status_btn;
					$build_result->data[$key]->built_action_button = $action_button_html;
			
				}
			}
			return response()->json($build_result);
		}
	
    } 
public function create()
	{
		$arr_ward = [];

		$obj_ward = $this->WardsModel->get();
		if($obj_ward)
		{
			$arr_ward = $obj_ward->toArray();
		}

		$this->arr_view_data['parent_module_icon']   = "fa-home";
        $this->arr_view_data['parent_module_title']  = "Dashboard";
        $this->arr_view_data['parent_module_url']    = url('/').'/admin/dashboard';
		$this->arr_view_data['page_title']       = 'Create '.str_singular($this->module_title);
		$this->arr_view_data['page_icon']        = $this->module_icon;
		$this->arr_view_data['module_title']     = 'Manage '.$this->module_title;
		$this->arr_view_data['sub_module_title'] = 'Create Voting Booth ';
		$this->arr_view_data['sub_module_icon']  = 'fa fa-plus';
		$this->arr_view_data['module_icon']      = $this->module_icon;
		$this->arr_view_data['admin_panel_slug'] = $this->admin_panel_slug;
		$this->arr_view_data['module_url_path']  = $this->module_url_path;
		$this->arr_view_data['module_url']       = $this->module_url_path;
		$this->arr_view_data['arr_ward']    	 = $arr_ward;
	     
		return view($this->module_view_folder.'.create',$this->arr_view_data);
	}

	public function store(Request $request)
	{ 
		// dd($request->toArray());
		$arr_rules      = $arr_data = array();
		$status         = false;

		$arr_rules['booth_no']  	         = "required";//|unique:booth,booth_no
		$arr_rules['booth_name']  	         = "required";	
		
		$validator = validator::make($request->all(),$arr_rules);

		if($validator->fails()) 
		{
			return redirect()->back()->withErrors($validator)->withInput();	
		}
		$ward_data  = $this->WardsModel->select()->where('id',$request->input('ward'))->get();
		if ($ward_data)
		{
			$ward_data=$ward_data->toArray();
		}
		$arr_data['ward_id']		= $request->input('ward', null);
		$arr_data['booth_no']		= $request->input('booth_no', null);
		$arr_data['booth_name']		= $request->input('booth_name', null);
		// dd($arr_data);	
		$status = $this->BaseModel->create($arr_data);if($status)
		{
			Session::flash('success', str_singular($this->module_title).' created successfully.');
			return redirect($this->module_url_path);
		}
		Session::flash('error', 'Error while creating '.str_singular($this->module_title).'.');
		return redirect($this->module_url_path.'/create');
	}
 
	public function view($enc_id)
	{
		$arr_user = []; 
		$arr_list = [];

		$user_id  = base64_decode($enc_id);
		if(isset($user_id) && $user_id!="")
		{
			$obj_user = $this->BaseModel->where('id','=',$user_id)->first();
			if($obj_user)
			{
			  $arr_user = $obj_user->toArray();
		    }
			$obj_list = $this->ListModel->where('booth_id','=',$user_id)->orderBy('id','asc')->get();
			if($obj_list)
			{
				$arr_list = $obj_list->toArray();
		    }
		}
	/*	$user_id  = base64_decode($enc_id);*/
		
		$this->arr_view_data['arr_user']                     = $arr_user;
		$this->arr_view_data['arr_list']                     = $arr_list;
		$this->arr_view_data['obj_list']                     = $obj_list;
		$this->arr_view_data['parent_module_icon']           = "fa-home";
		$this->arr_view_data['parent_module_title']          = "Dashboard";
		$this->arr_view_data['parent_module_url']            = $this->admin_url_path.'/dashboard';
		$this->arr_view_data['module_title']                 = str_plural($this->module_title);
		$this->arr_view_data['module_icon']                  = $this->module_icon;
		$this->arr_view_data['module_url']                   = $this->module_url_path;
		$this->arr_view_data['admin_panel_slug']             = $this->admin_panel_slug;
		$this->arr_view_data['sub_module_title']             = 'View '.str_singular($this->module_title);
		$this->arr_view_data['sub_module_icon']              = 'fa fa-eye';
		$this->arr_view_data['module_url_path']              = $this->module_url_path;
		$this->arr_view_data['user_profile_base_img_path']   = $this->user_profile_base_img_path;
		$this->arr_view_data['user_profile_public_img_path'] = $this->user_profile_public_img_path;
		$this->arr_view_data['user_image_base_path']         = $this->user_image_base_path;
		$this->arr_view_data['user_image_public_path']       = $this->user_image_public_path;
		
		return view($this->module_view_folder.'.view',$this->arr_view_data);
	}

	/*public function edit($enc_id='')
	{
		
		$arr_data = [];
		$id = base64_decode($enc_id);

		$obj_data = $this->BaseModel->with('get_ward_details')->where('id',$id)->first();
		$obj_wards = $this->WardsModel->get();
		if($obj_data)
		{
			$arr_data = $obj_data->toArray();
			$arr_ward = $obj_wards->toArray();
			// dd($arr_data,$arr_ward);

	
			$this->arr_view_data['parent_module_icon']   	= "fa-home";
	        $this->arr_view_data['parent_module_title']  	= "Dashboard";
	        $this->arr_view_data['parent_module_url']    	= url('/').'/admin/dashboard';
			$this->arr_view_data['module_url']              = $this->module_url_path;
			$this->arr_view_data['page_title']              = 'Edit '.str_singular($this->module_title);
			$this->arr_view_data['page_icon']               = $this->module_icon;
			$this->arr_view_data['module_title']            = 'Manage '.$this->module_title;
			$this->arr_view_data['sub_module_title']        = 'Edit '.$this->module_title;
			$this->arr_view_data['sub_module_icon']         = 'fa fa-pencil-square-o';
			$this->arr_view_data['module_icon']             = $this->module_icon;

			$this->arr_view_data['admin_panel_slug']        = $this->admin_panel_slug;
			$this->arr_view_data['module_url_path']         = $this->module_url_path;
			$this->arr_view_data['arr_data']                = $arr_data;
			$this->arr_view_data['arr_ward'] 		  	    = $arr_ward;
			$this->arr_view_data['enc_id']                  = $enc_id;


			return view($this->module_view_folder.'.edit',$this->arr_view_data);
	 	}
	
		Session::flash('Something went wrong');
		return redirect()->back();
	}
*/

    public function edit($enc_id='')
    {
        $arr_data = $arr_resp = [];
        $id = base64_decode($enc_id);
        if(is_numeric($id))
        { 
            $obj_data = $this->BaseModel->with('get_ward_details')->where('id',$id)->first();
        	if(isset($obj_data))
            {
                $arr_data = $obj_data->toArray();
                // dd($arr_data);
                $arr_resp['status'] = "success";
                $arr_resp['msg']    = "Data displayed successfully";
                $arr_resp['data']   = $arr_data;
                return $arr_resp;
    
             }else{
                $arr_resp['status'] = "error";
                $arr_resp['msg']    = "Something went wrong";
                $arr_resp['data']   = $arr_data;
                return $arr_resp;
             }
        }
        $arr_resp['status']  = "error";
        $arr_resp['msg']     = "Something went wrong";
        $arr_resp['data']    = $arr_data;
        return $arr_resp;
    }


	public function update(Request $request, $id='')
	{
		//dd($request->all());
		$arr_rules      = $arr_data = array();
		$status         = false;

		$arr_rules['booth_no']  	         = "required";//|unique:booth,booth_no
		$arr_rules['booth_name']  	         = "required";

		$validator = validator::make($request->all(),$arr_rules);

		if($validator->fails()) 
		{			
			return redirect()->back()->withErrors($validator)->withInput();			
		}
         $id                        =$request->input('enc_id');
		$arr_data['booth_no']		= $request->input('booth_no', null);
		$arr_data['booth_name']     = $request->input('booth_name', null);	

		$status = $this->BaseModel->where('id',$id)->update($arr_data);

		if($status)
		{
			Session::flash('success', str_singular($this->module_title).' updated successfully.');
			return redirect($this->module_url_path);
		}
		Session::flash('error', 'Error while updating '.str_singular($this->module_title).'.');
		return redirect($this->module_url_path);
	}
	public function manage_list()
	{
		$arr_ward = [];
		$arr_data = [];
		$obj_ward = $this->WardsModel->get();
		if($obj_ward)
		{
			$arr_ward = $obj_ward->toArray();
		}
		$obj_booth = $this->BaseModel->get();
		if($obj_booth)
		{
			$arr_booth = $obj_booth->toArray();
		}
		$obj_data = $this->ListModel->with('get_booth_details')->first();
		if(isset($obj_data->id))
		{ 
			$arr_data = $obj_data->toArray();
		}
		$this->arr_view_data['page_title']          = "Manage ".$this->module_title;
		$this->arr_view_data['parent_module_icon']  = "fa-home";
		$this->arr_view_data['parent_module_title'] = "Dashboard";
		$this->arr_view_data['parent_module_url']   = url('/').'/admin/dashboard';
		$this->arr_view_data['module_icon']         = $this->module_icon;
		$this->arr_view_data['module_title']        = "Manage ".$this->module_title;
		$this->arr_view_data['module_url_path']     = $this->module_url_path;
		$this->arr_view_data['admin_url_path']      = $this->admin_url_path;
		$this->arr_view_data['admin_panel_slug']    = $this->admin_panel_slug;
		$this->arr_view_data['arr_ward'] 	     	= $arr_ward;
		$this->arr_view_data['arr_data'] 	     	= $arr_data;
		$this->arr_view_data['arr_booth'] 	     	= $arr_booth;

		return view($this->module_view_folder.'.manage_list',$this->arr_view_data);
	}




   public function load_listdata(Request $request,$type=null)
    {
    	$obj_lists = $build_status_btn = $built_download_button = $search_country = "";
		$arr_search_column = $request->input('column_filter');
		
		$booth_details = $this->BaseModel->getTable();
		$list_details  = $this->ListModel->getTable();

    	$obj_lists     = DB::table($list_details)
    				    	->select(DB::raw(
    							$list_details.'.id,'.
    							'booth_id,'.
                                'list_no,'.
                                'list_name,'.
                                $list_details.'.status as list_status,'.
                                $list_details.'.created_at,'.
                                $list_details.'.updated_at,'.
    							$booth_details.'.booth_no,'.
    							$booth_details.'.booth_name,'.
    							$booth_details.'.booth_address'

    					))
                        ->join($booth_details,$list_details.'.booth_id','=',$booth_details.'.id')
                        ->orderBy($list_details.'.id','asc');

		$obj_lists = $obj_lists->orderBy('created_at','asc');
		if($obj_lists)
		{
			$json_result  = DataTables::of($obj_lists)->make(true);
			$build_result = $json_result->getData();

			foreach ($build_result->data as $key => $data) 
			{
				if(isset($build_result->data) && sizeof($build_result->data)>0)
				{
					$built_view_href   = $this->module_url_path.'/view_list/'.base64_encode($data->id);
					$built_edit_href   = $this->module_url_path.'/edit_list/'.base64_encode($data->id);
					$built_delete_href 	= $this->module_url_path.'/delete_list/'.base64_encode($data->id);
					if($data->list_status != null && $data->list_status == "0")
					{
						if(get_admin_access('voting_booth','approve'))
						{
							$build_status_btn = '<a class="label label-danger label-mini" title="Inactive" href="'.$this->module_url_path.'/unblock_list/'.base64_encode($data->id).'" 
							onclick="return confirm_action(this,event,\'Do you really want to activate this record ?\')" >Inactive</a>';	
						}
						else
						{
							$build_status_btn = '<span class="label label-danger label-mini">Inactive</span>';
						}	
					}
					elseif($data->list_status != null && $data->list_status == "1")
					{
						if(get_admin_access('voting_booth','approve'))
						{
							$build_status_btn = '<a class="label label-success label-mini" title="Active" href="'.$this->module_url_path.'/block_list/'.base64_encode($data->id).'" onclick="return confirm_action(this,event,\'Do you really want to inactivate this record ?\')" >Active</a>';
						}
						else
						{
							$build_status_btn = '<span class="label label-success label-mini">active</span>';
						}
					}
					if(get_admin_access('voting_booth','view'))
					{
						$built_view_button = " 
							<a class='btn btn-default btn-rounded btn-sm show-tooltip' href='".$built_view_href."' title='View' data-original-title='View'><i class='fa fa-eye' ></i> View</a>";
					}
					else
					{
						$built_view_button = '';
					}

					 if(get_admin_access('voting_booth','edit'))
                    {
                        $built_edit_button    = "<a class='btn btn-default btn-sm edit_button' href='javascript:void(0);' title='Edit' data-id=".base64_encode($data->id)."><i class='fa fa-pencil-square-o' ></i> Edit</a>";
                    }
                    else
                    {
                        $built_edit_button = '';
                    }	
					if(get_admin_access('voting_booth','delete'))
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

					$id 		   = isset($data->id)? base64_encode($data->id):'';
				    $booth_no      = isset($data->booth_no) && $data->booth_no!="" ? $data->booth_no:'';
					$booth_name    = isset($data->booth_name) && $data->booth_name!="" ? $data->booth_name:'';
					$booth_address = isset($data->booth_address) && $data->booth_address!="" ? $data->booth_address:'';
					$booth_detail  = '('.$booth_no.')'.'('.$booth_name.')'.'('.$booth_address.')';
					$list_no       = isset($data->list_no) && $data->list_no!="" ? $data->list_no:'';
					$list_name     = isset($data->list_name) && $data->list_name!="" ? $data->list_name:'';
					
					$created_at            = isset($data->created_at)?$data->created_at:'';
					$built_action_button   = $built_view_button.$built_edit_button.$built_delete_button;
			
					$build_result->data[$key]->booth               = $booth_detail;
					$build_result->data[$key]->list_no             = $list_no;
					$build_result->data[$key]->list_name           = $list_name;
					$build_result->data[$key]->build_status_btn    = $build_status_btn;
					$build_result->data[$key]->built_action_button = $action_button_html;
				}
			}
			return response()->json($build_result);
		}
    }
 	public function create_list()
	{	
		$arr_ward = [];
		$obj_ward = $this->WardsModel->get();
		if($obj_ward)
		{
			$arr_ward = $obj_ward->toArray();
		}

		$this->arr_view_data['parent_module_icon']   = "fa-home";
        $this->arr_view_data['parent_module_title']  = "Dashboard";
        $this->arr_view_data['parent_module_url']    = url('/').'/admin/dashboard';
		$this->arr_view_data['page_title']       	 = 'Create '.str_singular($this->module_title);
		$this->arr_view_data['page_icon']            = $this->module_icon;
		$this->arr_view_data['module_title']     	 = 'Manage '.$this->module_title;
		$this->arr_view_data['sub_module_title'] 	 = 'Create List';
		$this->arr_view_data['sub_module_icon']  	 = 'fa fa-plus';
		$this->arr_view_data['module_icon']      	 = $this->module_icon;
		$this->arr_view_data['admin_panel_slug'] 	 = $this->admin_panel_slug;
		$this->arr_view_data['module_url_path']  	 = $this->module_url_path;
		$this->arr_view_data['module_url']       	 = $this->module_url_path;
		$this->arr_view_data['arr_ward'] 	     	 = $arr_ward;
		return view($this->module_view_folder.'.create_list',$this->arr_view_data);
	}


   	public function store_list(Request $request)
	{ 	
		$arr_rules      = $arr_data = array();
		$status         = false;

		$arr_rules['booth']  	         = "required";
		$arr_rules['list_no']  	         = "required";
		$arr_rules['list_name']  	     = "required";
		$validator = validator::make($request->all(),$arr_rules);
		if($validator->fails()) 
		{
			return redirect()->back()->withErrors($validator)->withInput();	
		}
		$arr_data['booth_id']		= $request->input('booth', null);
		$arr_data['list_no']		= $request->input('list_no', null);
		$arr_data['list_name']	    = $request->input('list_name', null);	
    
		$status = $this->ListModel->create($arr_data);
		if($status)
		{
			Session::flash('success','List created successfully.');
			return redirect($this->module_url_path.'/manage_list');
		}
		Session::flash('error', 'Error while creating '.str_singular($this->module_title).'.');
		return redirect($this->module_url_path.'/create_list');
	}


	public function view_list($enc_id)
	{	
		$arr_user = []; 
		$user_id  = base64_decode($enc_id);

		if(isset($user_id) && $user_id!="")
		{
			$obj_user = $this->ListModel->with('get_booth_details')->where('id','=',$user_id)->first();
			if($obj_user)
			{
			  $arr_user = $obj_user->toArray();
		    }
		}
		
		$this->arr_view_data['arr_user']                     = $arr_user;
		$this->arr_view_data['parent_module_icon']           = "fa-home";
		$this->arr_view_data['parent_module_title']          = "Dashboard";
		$this->arr_view_data['parent_module_url']            = $this->admin_url_path.'/dashboard';
		$this->arr_view_data['module_title']                 = str_plural($this->module_title);
		$this->arr_view_data['module_icon']                  = $this->module_icon;
		$this->arr_view_data['module_url']                   = $this->module_url_path;
		$this->arr_view_data['admin_panel_slug']             = $this->admin_panel_slug;
		$this->arr_view_data['sub_module_title']             = 'View '.str_singular($this->module_title);
		$this->arr_view_data['sub_module_icon']              = 'fa fa-eye';
		$this->arr_view_data['module_url_path']              = $this->module_url_path;
		$this->arr_view_data['user_profile_base_img_path']   = $this->user_profile_base_img_path;
		$this->arr_view_data['user_profile_public_img_path'] = $this->user_profile_public_img_path;
		$this->arr_view_data['user_image_base_path']         = $this->user_image_base_path;
		$this->arr_view_data['user_image_public_path']       = $this->user_image_public_path;
		
		return view($this->module_view_folder.'.view_list',$this->arr_view_data);
	}
	public function edit_list($enc_id='')
    {
        $arr_data = $arr_resp = [];
        $id = base64_decode($enc_id);
        if(is_numeric($id))
        { 
            $obj_data = $this->ListModel->with('get_booth_details')->where('id',$id)->first();
            if(isset($obj_data))
            {
                $arr_data = $obj_data->toArray();
                $arr_resp['status'] = "success";
                $arr_resp['msg']    = "Data displayed successfully";
                $arr_resp['data']   = $arr_data;
                return $arr_resp;
             }else{
                $arr_resp['status'] = "error";
                $arr_resp['msg']    = "Something went wrong";
                $arr_resp['data']   = $arr_data;
                return $arr_resp;
             }
        }
        $arr_resp['status']  = "error";
        $arr_resp['msg']     = "Something went wrong";
        $arr_resp['data']    = $arr_data;
        return $arr_resp;
    }




	public function update_list(Request $request, $id='')
	{
		$arr_rules      = $arr_data = array();
		$status         = false;

		$arr_rules['list_no']  	            = "required";
		$arr_rules['list_name']  	        = "required";
		
		$validator = validator::make($request->all(),$arr_rules);

		if($validator->fails()) 
		{
			return redirect()->back()->withErrors($validator)->withInput();	
		}
		$id                         =$request->input('enc_id');
		$arr_data['list_no']        = $request->input('list_no', null);	
		$arr_data['list_name']	    = $request->input('list_name', null);

		$status = $this->ListModel->where('id',$id)->update($arr_data);

		if($status)
		{
			Session::flash('success', str_singular($this->module_title).' updated successfully.');
			return redirect($this->module_url_path.'/manage_list');
		}
		Session::flash('error', 'Error while updating '.str_singular($this->module_title).'.');
		return redirect($this->module_url_path);
	}

	public function unblock_list($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            return redirect()->back();
        }

        if($this->perform_unblock_list(base64_decode($enc_id)))
        {
            Session::flash('success', str_singular($this->module_title). ' Activated Successfully');
            return redirect()->back();
        }
        else
        {
            Session::flash('error', 'Problem Occured While '.str_singular($this->module_title).' Activation ');
        }
        return redirect()->back();
    }

    public function block_list($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            return redirect()->back();
        }

        if($this->perform_block_list(base64_decode($enc_id)))
        {
            Session::flash('success', str_singular($this->module_title). ' Inactivated Successfully');
        }
        else
        {
            Session::flash('error', 'Problem Occured While '.str_singular($this->module_title).' Deactivation ');
        }

        return redirect()->back();
    }

    public function perform_unblock_list($id)
    {
        if($id!=null)
        {
            $responce = $this->ListModel->where('id',$id)->update(['status'=>'1']);
            if($responce)
            {
                return TRUE;
            }
            return FALSE;            
        }
        return FALSE;
    }


    public function perform_block_list($id)
    {   
        if($id!=null)
        {
            $responce = $this->ListModel->where('id',$id)->update(['status'=>'0']);
            if($responce)
            {
                return TRUE;
            }  
            return FALSE;          
        }
        return FALSE;
    }

    public function delete_list($enc_id = FALSE)
    {
        if(!$enc_id)
        {
            return redirect()->back();
        }

        if($this->perform_delete_list(base64_decode($enc_id)))
        {
            Session::flash('success', str_singular($this->module_title). ' Deleted Successfully');
        }
        else
        {
            Session::flash('error', 'Problem Occured While '.str_singular($this->module_title).' Deletion ');
        }

        return redirect()->back();
    }

    public function perform_delete_list($id)
    {
        $delete= $this->ListModel->where('id',$id)->delete();
        
        if($delete)
        {
            return TRUE;
        }
        return FALSE;
    }



    public function get_cities(Request $request)
	{
		$arr_data = [];

		$id = $request->input('district_id');
	
		$obj_data = $this->CityModel->where('district_id',$id)->get();
		if($obj_data)
        {
            $arr_data = $obj_data->toArray();
        }
		$html = "<option value=''>Select City </option>";        
        foreach ($arr_data as $key => $value) {
        	$html .=  " <option value=".$value['id'].">".$value['city_name']."</option>";
    	}
    	return response()->json($html);
	}
    
	public function get_villages(Request $request)
	{
		$arr_data = [];

		$id = $request->input('district_id');
		$city_id = $request->input('city_id');

		$obj_data = $this->VillageModel->where('city_id',$city_id)
									   ->get();
		if($obj_data)
        {
            $arr_data = $obj_data->toArray();
        }
		$html = "<option value=''>Select village </option>";        
        foreach ($arr_data as $key => $value) {
        	$html .=  " <option value=".$value['id'].">".$value['village_name']."</option>";
    	}

    	return response()->json($html);
	}

	public function get_wards(Request $request)
	{
		// dd($request->all());
		$arr_data = [];

		$village_id = $request->input('village_id');
		//dd($village_id);
		$obj_data = $this->WardsModel->where('village_id',$village_id)
									   ->get();
		// dd($obj_data->toArray());									   
		if($obj_data)
        {
            $arr_data = $obj_data->toArray();
        }
		$html = "<option value=''>Select Ward </option>";        
        foreach ($arr_data as $key => $value) {
        	$html .=  " <option value=".$value['id'].">".$value['ward_name']."</option>";
    	}

    	return response()->json($html);
	}

	public function get_booths(Request $request)
	{
		$arr_data = [];
		$ward_id = $request->input('ward_id');
		$obj_data = $this->BaseModel->where('ward_id',$ward_id)
									   ->get();
		if($obj_data)
        {
            $arr_data = $obj_data->toArray();
        }
		$html = "<option value=''>Select Booth </option>";        
        foreach ($arr_data as $key => $value) {
        	$html .=  " <option value=".$value['id'].">".$value['booth_name']."</option>";
    	}

    	return response()->json($html);
	}

}