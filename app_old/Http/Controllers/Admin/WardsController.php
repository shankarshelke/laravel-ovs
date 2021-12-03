<?
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Common\Traits\MultiActionTrait;
use App\Models\WardsModel;
use App\Models\UsersModel;
use App\Models\DistrictModel;
use App\Models\CityModel;
use App\Models\VillageModel;

use App\Models\BoothModel;
use Validator;
use Session;
use DataTables;
use Response;
use DB; 

class WardsController extends Controller
{
use MultiActionTrait;
    function __construct()
    {
		$this->arr_view_data                = [];
		$this->admin_panel_slug             = config('app.project.admin_panel_slug');
		$this->admin_url_path               = url(config('app.project.admin_panel_slug'));
		$this->module_url_path              = $this->admin_url_path."/wards";
		$this->module_title                 = " Wards";
		$this->module_view_folder           = "admin.wards";
		$this->module_icon                  = "fa fa-user";
		$this->auth                         = auth()->guard('admin');
		$this->BaseModel					= new WardsModel();
		$this->BoothModel					= new BoothModel();
		$this->DistrictModel				= new DistrictModel();
		$this->CityModel					= new CityModel();
		$this->VillageModel					= new VillageModel();
		$this->UsersModel					= new UsersModel();
	
		$this->user_profile_base_img_path   = base_path().config('app.project.img_path.user_profile_image');
		$this->user_profile_public_img_path = url('/').config('app.project.img_path.user_profile_image');
		$this->user_image_base_path         = base_path().config('app.project.img_path.user_image');
		$this->user_image_public_path 		= url('/').config('app.project.img_path.user_image');
    }

    public function index(Request $request,$type=null)
    {   
       
                                                                                        
		$this->arr_view_data['page_title']          = "Manage ".$this->module_title;
        $this->arr_view_data['parent_module_icon']  = "fa-home";
        $this->arr_view_data['parent_module_title'] = "Dashboard";
        $this->arr_view_data['parent_module_url']   = url('/').'/admin/dashboard';
        $this->arr_view_data['module_icon']         = $this->module_icon;
        $this->arr_view_data['module_title']        = "Manage ".$this->module_title;
		$this->arr_view_data['module_url_path']     = $this->module_url_path;
		$this->arr_view_data['admin_url_path']      = $this->admin_url_path;
		$this->arr_view_data['admin_panel_slug']    = $this->admin_panel_slug;

		return view($this->module_view_folder.'.index',$this->arr_view_data);
    }



    public function load_data(Request $request,$type=null)
    {
    	$build_status_btn = $built_download_button = $search_country = "";
		$arr_search_column = $request->input('column_filter');
		$ward_details = $this->BaseModel->getTable();

    	// $district     = $this->DistrictModel->getTable();
     //    $city    	  = $this->CityModel->getTable();
    	// $village      = $this->VillageModel->getTable();
    	$obj_ward     = DB::table($ward_details)
    				    	->select(DB::raw(
    							$ward_details.'.id,'.
    							'ward_no,'.
    							'ward_name,'.
    							'ward_address,'.
                                
                                
    							// $district.'.district_name,'.
    							// $city.'.city_name,'.
    							// $village.'.village_name,'.
    							
                                'status'
                                
    					));
         //                ->join($district,$ward_details.'.district','=',$district.'.id')
    					// ->join($city,$ward_details.'.city','=',$city.'.id')
    					// ->join($village,$ward_details.'.village_id','=',$village.'.id')
         //                ->orderBy($village.'.village_name','ASC');

    	$obj_ward = $this->BaseModel->orderBy('created_at','asc')->get();

		

		if(isset($arr_search_column['ward_no']) && $arr_search_column['ward_no']!=""){
			$obj_ward = $obj_ward->where('ward_no', 'LIKE', "%".$arr_search_column['ward_no']."%");	
		}
		// if(isset($arr_search_column['village_name']) && $arr_search_column['village_name']!=""){
		// 	$obj_ward = $obj_ward->where('village_name', 'LIKE', "%".$arr_search_column['village_name']."%");	
		// }


		if(isset($arr_search_column['ward_name']) && $arr_search_column['ward_name']!=""){
			$obj_ward = $obj_ward->where('ward_name', 'LIKE', "%".$arr_search_column['ward_name']."%");	
		}

		if(isset($arr_search_column['ward_address']) && $arr_search_column['ward_address']!=""){
			$obj_ward = $obj_ward->where('ward_address', 'LIKE', "%".$arr_search_column['ward_address']."%");	
		}
		
		if(isset($arr_search_column['status']) && $arr_search_column['status']!=""){
			$obj_ward = $obj_ward->where('status', 'LIKE', "%".$arr_search_column['status']."%");	
		}
		

		// $obj_ward = $obj_ward->orderBy('created_at','asc');

		if($obj_ward)
		{
			$json_result  = DataTables::of($obj_ward)->make(true);
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
						if(get_admin_access('wards','approve'))
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
						if(get_admin_access('wards','approve'))
						{
							$build_status_btn = '<a class="label label-success label-mini" title="Active" href="'.$this->module_url_path.'/block/'.base64_encode($data->id).'" onclick="return confirm_action(this,event,\'Do you really want to inactivate this record ?\')" >Active</a>';
						}
						else
						{
							$build_status_btn = '<span class="label label-success label-mini">active</span>';
						}

					}

					
					if(get_admin_access('wards','view'))
					{
						$built_view_button = "<a class='btn btn-default btn-rounded btn-sm show-tooltip' href='".$built_view_href."' title='View' data-original-title='View'><i class='fa fa-eye' ></i> View</a>";
					}
					else
					{
						$built_view_button = '';
					}
/*
					if(get_admin_access('wards','edit'))
					{
						$built_edit_button 	  = "<a class='btn btn-default btn-sm edit_button' href='javascript:void(0);' title='Edit' ><i class='fa fa-pencil-square-o' ></i> Edit</a>";
					}
					else
					{
						$built_edit_button = '';
					}*/

                    if(get_admin_access('wards','edit'))
					{
						$built_edit_button 	  = "<a class='btn btn-default btn-sm edit_button' href='javascript:void(0);' title='Edit' data-id=".base64_encode($data->id)."><i class='fa fa-pencil-square-o' ></i> Edit</a>";
					}
					else
					{
						$built_edit_button = '';
					}


				
					if(get_admin_access('wards','delete'))
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
					

					
					$ward_no               = isset($data->ward_no) && $data->ward_no!="" ? $data->ward_no:'';
					$ward_name             = isset($data->ward_name) && $data->ward_name!="" ? $data->ward_name:'';

					//$village_id            = isset($data->village_id) && $data->village_id!="" ? $data->village_id:'';
					$ward_address		   = isset($data->ward_address) && $data->ward_address!="" ? $data->ward_address:'';
					// $city   	  		   = isset($data->city)?$data->city:'';
					// $district     	       = isset($data->district)?$data->district.'.':'';
					//
					
					$created_at           = isset($data->created_at)?$data->created_at:'';

					$ward_address		   = isset($data->ward_address) && $data->ward_address!="" ? $data->ward_address:'';
										

					$built_action_button  = $built_view_button.$built_edit_button.$built_delete_button;

					$build_result->data[$key]->id         		   = $id;
					$build_result->data[$key]->ward_no             = $ward_no;
					$build_result->data[$key]->ward_name           = $ward_name;

					// $build_result->data[$key]->village_id          = $village_id;
					// $build_result->data[$key]->city                = $city;
					// $build_result->data[$key]->district            = $district;

					$build_result->data[$key]->ward_address        = $ward_address;
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
        $this->arr_view_data['parent_module_title']  = "Dashboard";
        $this->arr_view_data['parent_module_url']    = url('/').'/admin/dashboard';
		$this->arr_view_data['page_title']           = 'Create '.str_singular($this->module_title);
		$this->arr_view_data['page_icon']            = $this->module_icon;
		$this->arr_view_data['module_title']         = 'Manage '.$this->module_title;
		$this->arr_view_data['sub_module_title']     = 'Create Voting Wards ';
		$this->arr_view_data['sub_module_icon']      = 'fa fa-plus';
		$this->arr_view_data['module_icon']          = $this->module_icon;
		$this->arr_view_data['admin_panel_slug']     = $this->admin_panel_slug;
		$this->arr_view_data['module_url_path']      = $this->module_url_path;
		$this->arr_view_data['module_url']           = $this->module_url_path;
		return view($this->module_view_folder.'.create',$this->arr_view_data);
	}

	public function store(Request $request)
	{ 
		$arr_rules      = $arr_data = array();
		$status         = false;

		$arr_rules['ward_no']  	             ="required";
		$arr_rules['ward_name']  	         = "required";
		$arr_rules['ward_address']  	     = "required";
	
		$validator = validator::make($request->all(),$arr_rules);

		if($validator->fails()) 
		{
			return redirect()->back()->withErrors($validator)->withInput();	
		}
		$arr_data['ward_no']		= $request->input('ward_no', null);
		$arr_data['ward_name']		= $request->input('ward_name', null);
		$arr_data['ward_address']	= $request->input('ward_address', null);	

		$status = $this->BaseModel->create($arr_data);
		if($status)
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
	

		$ward_id  = base64_decode($enc_id);
		if(isset($ward_id) && $ward_id!="")
		{
			$obj_ward = $this->BaseModel->where('id',$ward_id)->first();
			if($obj_ward)
			{				
			  $arr_ward = $obj_ward->toArray();
		    }			
		}	
		$this->arr_view_data['arr_ward']                     = $arr_ward;
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
		
		if($enc_id=='')
		{
			return redirect()->back();
		}

		$obj_data = $this->BaseModel->where('id', base64_decode($enc_id))->first();
		if($obj_data)
		{
			$arr_data = $obj_data->toArray() ;
		}
		else
		{
			Session::flash('error', 'No Record Found.');
			return redirect($this->module_url_path);
		}
		$id = base64_decode($enc_id);

	
		//dd($arr_data);
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
		$this->arr_view_data['enc_id']                  = $enc_id;
        //dd($this->arr_view_data);
		return view($this->module_view_folder.'.edit',$this->arr_view_data);
 	}*/

 	public function edit($enc_id='')
	{
		//dd('hhii');
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
	//dd($arr_data);
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


	public function update(Request $request, $id='')
	{

		$arr_rules      = $arr_data = array();
		$status         = false;

	
		$arr_rules['ward_no']      	   	    = "required"; 
		$arr_rules['ward_name']  	        = "required";
		$arr_rules['ward_address']  	    = "required";
		
		$validator = validator::make($request->all(),$arr_rules);

		if($validator->fails()) 
		{	
			return redirect()->back()->withErrors($validator)->withInput();	
		}
        $id 		                 =$request->input('enc_id');
		$arr_data['ward_no']		 = $request->input('ward_no', null);
		$arr_data['ward_name']       = $request->input('ward_name', null);	
		$arr_data['ward_address']	 = $request->input('ward_address', null);

		$status = $this->BaseModel->where('id',$id)->update($arr_data);

		if($status)
		{
			Session::flash('success', str_singular($this->module_title).' updated successfully.');
			return redirect($this->module_url_path);
		}
		Session::flash('error', 'Error while updating '.str_singular($this->module_title).'.');
		return redirect($this->module_url_path);
	}

	public function delete($enc_id = FALSE)
	{
		$id = base64_decode($enc_id);	
		$success = $this->BaseModel->where('id',$id)->delete();
		if($success)
		{
  			Session::flash('success','Ward deleted successfully');
  			return redirect()->back();
		}
		else
		{
			Session::flash('error','Something went wrong');
  			return redirect()->back();
		}
	}

	public function get_village(Request $request)
	{
		$arr_data = [];
		$village_id = $request->input('village_id');
		$obj_data = $this->BaseModel->where('village_id',$village_id)->get();	   
		if($obj_data)
        {
            $arr_data = $obj_data->toArray();
        }
	}
}
