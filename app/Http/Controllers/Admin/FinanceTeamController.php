<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Common\Traits\MultiActionTrait;
use App\Models\WardsModel;
use App\Models\UsersModel;
use App\Models\DistrictModel;
use App\Models\CityModel;
use App\Models\VillageModel;
use App\Models\WebAdmin;
use App\Models\FinanceTeamModel;

use App\Models\BoothModel;
use Validator;
use Session;
use DataTables;
use Response;
use DB; 


class FinanceTeamController extends Controller
{
	
	use MultiActionTrait;
    public function __construct()
    {
		$this->arr_view_data                = [];
		$this->admin_panel_slug             = config('app.project.admin_panel_slug');
		$this->admin_url_path               = url(config('app.project.admin_panel_slug'));
		$this->module_url_path              = $this->admin_url_path."/finance_team";
		$this->module_title                 = "Finance Team";
		$this->module_view_folder           = "admin.finance_team";
		$this->module_icon                  = "fa fa-user";
		$this->auth                         = auth()->guard('admin');
		$this->BaseModel					= new FinanceTeamModel();
		$this->DistrictModel				= new DistrictModel();
		$this->CityModel					= new CityModel();
		$this->VillageModel					= new VillageModel();
		$this->WardsModel			        = new WardsModel();
		$this->WebAdmin						= new WebAdmin();
    }

    public function index(Request $request,$type=null)
    {
		$obj_wards = $this->WardsModel->get();
		if($obj_wards)
		{
			$arr_wards = $obj_wards->toArray();
		}
		$arr_teams = [];
        $obj_teams = $this->WebAdmin->where('role','Accounting')->where('role_status','1')->get();
        if($obj_teams)
        {
            $arr_teams = $obj_teams->toArray();  
  
        }
		$obj_admin = $this->WebAdmin->get();
			
		if($obj_admin)
		{
			$arr_accountant =$obj_admin->where('role','Accounting')->toArray();
		}
        $this->arr_view_data['arr_wards']            = $arr_wards;
		$this->arr_view_data['arr_teams']            = $arr_teams; 
		$this->arr_view_data['arr_accountant']      = $arr_accountant;                               
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
		
        $obj_finance_team     = $this->BaseModel->with(['get_admin_details','get_ward_details']);		

		/*if(isset($arr_search_column['distributor_no']) && $arr_search_column['distributor_no']!="")
		{
			$obj_finance_team = $obj_finance_team->whereHas('get_admin_details',function($q)use($arr_search_column){	
										$q->where('subadmin_id', 'LIKE', "%".$arr_search_column['distributor_no']."%");
										});	
		}*/
		if(isset($arr_search_column['ward']) && $arr_search_column['ward']!="")
		{
			
				$obj_finance_team = $obj_finance_team->whereHas('get_ward_details',function($q)use($arr_search_column){	
										$q->where('ward', 'LIKE', "%".$arr_search_column['ward']."%");
									});
		}
		/*if(isset($arr_search_column['village']) && $arr_search_column['village']!="")
		{

			$obj_finance_team = $obj_finance_team->whereHas('get_village_details',function($q)use($arr_search_column){	
										$q->where('village_name', 'LIKE', "%".$arr_search_column['village']."%");
									});
		}
		if(isset($arr_search_column['city']) && $arr_search_column['city']!="")
		{
			$obj_finance_team = $obj_finance_team->whereHas('get_cities_details',function($q)use($arr_search_column){	
										$q->where('city_name', 'LIKE', "%".$arr_search_column['city']."%");
									});	
		}*/
		if(isset($arr_search_column['full_name']) && $arr_search_column['full_name']!="")
        {
            $obj_finance_team = $obj_finance_team->whereHas('get_admin_details',function($q)use($arr_search_column){   
                                        $q->where('first_name', 'LIKE', "%".$arr_search_column['full_name']."%");
                                        $q->orwhere('last_name', 'LIKE', "%".$arr_search_column['full_name']."%");
                                    });
        }

        if(isset($arr_search_column['status']) && $arr_search_column['status']!="")
        {
            $obj_finance_team = $obj_finance_team->where('status', 'LIKE', "%".$arr_search_column['status']."%");
    
        } 
		


		

		$obj_finance_team = $obj_finance_team->orderBy('created_at','asc');

		if($obj_finance_team)
		{
			$json_result  = DataTables::of($obj_finance_team)->make(true);
			$build_result = $json_result->getData();

			foreach ($build_result->data as $key => $data) 
			{
				if(isset($build_result->data) && sizeof($build_result->data)>0)
				{

					$built_view_href          = $this->module_url_path.'/view/'.base64_encode($data->id);

                    $built_transaction_href   = $this->module_url_path.'/transaction/'.base64_encode($data->id);
                    $built_view_history_href   ='./money_distribution/history/'.base64_encode($data->subadmin_id);


                    $built_edit_href          = $this->module_url_path.'/edit/'.base64_encode($data->id);

                    $built_delete_href        = $this->module_url_path.'/delete/'.base64_encode($data->id);

                    $built_download_href      = $this->module_url_path.'/download/'.base64_encode($data->id);

					if($data->status != null && $data->status == "0")
                    {
                        if(get_admin_access('finance_team','approve'))
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
                        if(get_admin_access('finance_team','approve'))
                        {
                            $build_status_btn = '<a class="label label-success label-mini" title="Active" href="'.$this->module_url_path.'/block/'.base64_encode($data->id).'" onclick="return confirm_action(this,event,\'Do you really want to inactivate this record ?\')" >Active</a>';
                        }
                        else
                        {
                            $build_status_btn = '<span class="label label-success label-mini">active</span>';
                        }

                    }

                        if(get_admin_access('finance_team','view'))
                        {
                            $built_view_button = " 
                            <a class='btn btn-default btn-rounded btn-sm show-tooltip' href='".$built_view_href."' title='View' data-original-title='View'><i class='fa fa-eye' ></i> View</a>
                            ";
                        }
                        else
                        {
                            $built_view_button = '';
                        }


                         if(get_admin_access('money_distribution','view_history'))
					{
						$built_view_history_button = "<a class='btn btn-default btn-rounded btn-sm show-tooltip' href='".$built_view_history_href."' title='Transaction' data-original-title='Transaction'><i class='fa fa-money'></i> Transaction</a>";
					}
					else
					{
						$built_view_history_button = '';
					}

                    /*if(get_admin_access('finance_team','edit'))
                    {
                        $built_edit_button = "
                        <a class='btn btn-default btn-rounded btn-sm show-tooltip edit_button' href='javascript:void(0)'  title='Edit' data-original-title='Edit'><i class='fa fa-pencil-square-o' ></i> Edit</a>
                        ";
                    }
                    else
                    {
                        $built_edit_button = '';
                    }*/

                    if(get_admin_access('finance_team','edit'))
					{
						$built_edit_button 	  = "<a class='btn btn-default btn-sm edit_button' href='javascript:void(0);' title='Edit' data-id=".base64_encode($data->id)."><i class='fa fa-pencil-square-o' ></i> Edit</a>";
					}
					else
					{
						$built_edit_button = '';
					}


                    if(get_admin_access('finance_team','delete'))
                    {
                        $built_delete_button =  "
                        <a class='btn btn-default btn-sm' href='".$built_delete_href."' title='Delete' onclick='return confirm_action(this,event,\"Do you really want to delete this record ?\")'><i class='fa fa-trash-o' ></i> Delete</a>
                        ";
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
                    $action_button_html .= '<li>'.$built_view_history_button.'</li>';
                    $action_button_html .= '<li>'.$built_edit_button.'</li>';
                    $action_button_html .= '<li>'.$built_delete_button.'</li>';
                    $action_button_html .= '</ul>';
                    $action_button_html .= '</li>';
                    $action_button_html .= '</ul>';


					
					$id              = isset($data->id)? base64_encode($data->id):'';
					//$distributor_no  = isset($data->id) && $data->id!="" ? $data->id:'';
					
					$first_name      = isset($data->get_admin_details->first_name) && $data->get_admin_details->first_name!="" ? $data->get_admin_details->first_name:'';
					$last_name       = isset($data->get_admin_details->last_name) && $data->get_admin_details->last_name!="" ? $data->get_admin_details->last_name:'';
					$full_name       = $first_name.'  '.$last_name;
					$ward    	     = isset($data->get_ward_details->ward_name) && $data->get_ward_details->ward_name!="" ? $data->get_ward_details->ward_name:'';
					//$city    	     = isset($data->get_cities_details->city_name) && $data->get_cities_details->city_name!="" ? $data->get_cities_details->city_name:'';

					//$district    	 = isset($data->get_district_details->district_name) && $data->get_district_details->district_name!="" ? $data->get_district_details->district_name:'';
					
					$created_at          = isset($data->created_at)?$data->created_at:'';
					$built_action_button = $built_view_button. $built_view_history_button.$built_edit_button.$built_delete_button;

					$build_result->data[$key]->id         		   = $id;	
					//$build_result->data[$key]->distributor_no      = $distributor_no;	
					$build_result->data[$key]->full_name 		   = $full_name;
					$build_result->data[$key]->ward                = $ward;
					$build_result->data[$key]->build_status_btn    = $build_status_btn;
					$build_result->data[$key]->built_action_button = $action_button_html;
			
				}
			}
			return response()->json($build_result);
		}
	
    }

	public function store(Request $request)
	{ 
		//dd($request->all());
		$arr_rules      = $arr_data = array();
		$status         = false;
		
		$arr_rules['ward']  	         	="required";
		$arr_rules['sub_admin_id']  	    ="required";
	
		
		$validator = validator::make($request->all(),$arr_rules);

		if($validator->fails()) 
		{
			return redirect()->back()->withErrors($validator)->withInput();	
		}

		//$arr_data['district_id']    = $request->input('district', null);
		//$arr_data['city_id']		= $request->input('city', null);
		$arr_data['ward']		    = $request->input('ward', null);
		$arr_data['subadmin_id']    = $request->input('sub_admin_id', null);

		 //dd($arr_data);
			$status = $this->BaseModel->create($arr_data);
			if($status)
			{	//dd($status);
				$arr_status['role_status']	= '0';
				$status = $this->WebAdmin->where('id',$status['subadmin_id'])->update($arr_status);

				Session::flash('success', str_singular($this->module_title).' created successfully.');
				return redirect($this->module_url_path);
			}
			Session::flash('error', 'Error while creating '.str_singular($this->module_title).'.');
			return redirect($this->module_url_path.'/create');
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
 




 
	public function view($enc_id)
	{
		$arr_user = []; 
	

		$obj_finance_team = $this->BaseModel->where('id',base64_decode($enc_id))->with('get_admin_details','get_ward_details')->first();
		// dd($obj_finance_team);
			if($obj_finance_team)
			{
			  $arr_finance_team = $obj_finance_team->toArray();
		    }
		    // dd($arr_finance_team);						
		    
			
		/*	$user_id  = base64_decode($enc_id);*/
		
		$this->arr_view_data['arr_finance_team']             = $arr_finance_team;
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
		/*$this->arr_view_data['user_profile_base_img_path']   = $this->user_profile_base_img_path;
		$this->arr_view_data['user_profile_public_img_path'] = $this->user_profile_public_img_path;
		$this->arr_view_data['user_image_base_path']         = $this->user_image_base_path;
		$this->arr_view_data['user_image_public_path']       = $this->user_image_public_path;*/
		// dd($this->arr_view_data);
		return view($this->module_view_folder.'.view',$this->arr_view_data);
	}

	/*public function edit($enc_id='')
	{
		//dd($request->toArray());
		$arr_data=[];
		if($enc_id=='')
		{
			return redirect()->back();
		}
		$id = base64_decode($enc_id);

		if(is_numeric($id))
		{
			$obj_data = $this->BaseModel->with('get_admin_details','get_ward_details')->where('id', base64_decode($enc_id))->first();
			$arr_ward =[];
			$arr_data = $obj_data->toArray();

	
		 // dd($arr_village);
			$obj_admin = $this->WebAdmin->get();
			
			if($obj_admin)
			{//dd($obj_admin);
				$arr_accountant =$obj_admin->where('role','Accounting')->toArray();
			}
			$obj_wards = $this->WardsModel->get();
		    if($obj_wards)
		    {//dd($obj_wards);
		 	   $arr_ward = $obj_wards->toArray();
		    }
			
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
		//	$this->arr_view_data['arr_districts'] 	        = $arr_districts;
			//$this->arr_view_data['arr_city'] 	       		= $arr_city;
			$this->arr_view_data['arr_ward'] 			    = $arr_ward;
			$this->arr_view_data['arr_accountant'] 			= $arr_accountant;
			//dd($this->arr_view_data);

			return view($this->module_view_folder.'.edit',$this->arr_view_data);
 
		}

		Session::flash('Something went wrong');
		return redirect()->back();
	}

	*/
	public function edit($enc_id='')
	{
		// dd('hhii');
		$arr_data = $arr_resp = [];
		$id = base64_decode($enc_id);
	// dd($id);
		if(is_numeric($id))
		{ //dd(is_numeric($id));

			$obj_data = $this->BaseModel->with('get_admin_details','get_ward_details')->where('id',$id)->first();
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
	//dd($request->toArray());

		$arr_rules      = $arr_data = array();
		$status         = false;

		$arr_rules['ward']  	            	="required";
		$arr_rules['sub_admin_id']  	        ="required";

		
		$validator = validator::make($request->all(),$arr_rules);

		if($validator->fails()) 
		{
			return redirect()->back()->withErrors($validator)->withInput();	
		}
		$id 		                 =$request->input('enc_id');
		$arr_data['ward']		    = $request->input('ward', null);
		$arr_data['subadmin_id']    = $request->input('sub_admin_id', null);

	//dd($arr_data);

		$status = $this->BaseModel->where('id',$id)->update($arr_data);

		if($status)
		{
			Session::flash('success', str_singular($this->module_title).' Updated Successfully.');
			return redirect($this->module_url_path);
		}
		Session::flash('error', 'Error while updating '.str_singular($this->module_title).'.');
		return redirect($this->module_url_path);
	}



	public function delete($enc_id = FALSE)
	{
		$id = base64_decode($enc_id);
		$obj_data =$this->BaseModel->where('id',$id)->with('get_admin_details')->first();
		// dd($obj_data);
		// dd($obj_data['subadmin_id']);
		$success = $this->BaseModel->where('id',$id)->delete();

		if($success)
		{
			$arr_status['role_status']	= '1';
			$status = $this->WebAdmin->where('id',$obj_data['subadmin_id'])->update($arr_status);
			
			Session::flash('success','Accountant deleted successfully');
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
		// dd($request->all());
		$arr_data = [];

		$village_id = $request->input('village_id');
		//dd($village_id);
		$obj_data = $this->BaseModel->where('village_id',$village_id)
									->get();
		// dd($obj_data->toArray());									   
		if($obj_data)
		{
			$arr_data = $obj_data->toArray();
		}

	}


}