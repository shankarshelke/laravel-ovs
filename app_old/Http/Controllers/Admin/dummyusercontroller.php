<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Common\Traits\MultiActionTrait;
use App\Common\Services\SmsService;
use App\Models\UsersModel;
use App\Models\ListModel;

use App\Models\BoothModel;
use App\Models\OccupationModel;
use App\Models\StatesModel;
use App\Models\DistrictModel;
use App\Models\CityModel;
use App\Models\VillageModel;
use App\Models\WardsModel;
use App\Models\ReligionModel;
use App\Models\CasteModel;
use App\Models\WebAdmin;

use DB;
use Validator;
use Session;
use DataTables;
use Response;
// use Carbon;
use Carbon\Carbon;

class DummyUsersController extends Controller
{
	use MultiActionTrait;
    function __construct()
    {
		$this->arr_view_data                = [];
		$this->admin_panel_slug             = config('app.project.admin_panel_slug');
		$this->admin_url_path               = url(config('app.project.admin_panel_slug'));
		$this->module_url_path              = $this->admin_url_path."/voters";
		$this->module_title                 = "Voter";
		$this->module_view_folder           = "admin.users";
		$this->module_icon                  = "fa fa-user";
		$this->auth                         = auth()->guard('admin');
		$this->BaseModel					= new UsersModel();
		$this->ListModel					= new ListModel();
		$this->StatesModel					= new StatesModel();
		$this->DistrictModel				= new DistrictModel();
		$this->CityModel					= new CityModel();
		$this->VillageModel					= new VillageModel();
		$this->WardsModel					= new WardsModel();
		$this->CityModel					= new CityModel();
		$this->ReligionModel			    = new ReligionModel();
		$this->CasteModel			        = new CasteModel();
		$this->BoothModel			        = new BoothModel();
		$this->WebAdmin			        	= new WebAdmin();
		$this->OccupationModel              = new OccupationModel();
		$this->SmsService 	                = new SmsService();
		

		$this->user_profile_base_img_path   = base_path().config('app.project.img_path.user_profile_image');
		$this->user_profile_public_img_path = url('/').config('app.project.img_path.user_profile_image');
		$this->user_image_base_path         = base_path().config('app.project.img_path.user_image');
		$this->user_image_public_path 		= url('/').config('app.project.img_path.user_image');
    }

    public function index()
    {
    	$arr_districts = [];

		$obj_districts = $this->DistrictModel->get();
		if($obj_districts)
		{
			$arr_districts = $obj_districts->toArray();
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
		$this->arr_view_data['arr_districts']       = $arr_districts;
// dd($this->arr_view_data)
		// dd(session('subadmin_id'));
		return view($this->module_view_folder.'.index',$this->arr_view_data);
    }

    public function load_data(Request $request,$type=null)
    {
    	
    	$obj_user = $build_status_btn = $built_download_button = $search_country = "";
		$arr_search_column = $request->input('column_filter');
		$user_details = $this->BaseModel->getTable();
    	//$district     = $this->DistrictModel->getTable();
      //  $city    	  = $this->CityModel->getTable();
    	//$village      = $this->VillageModel->getTable();
    	$obj_user     = DB::table($user_details)
    				    	->select(DB::raw(
    							$user_details.'.id,'.
    							'first_name,'.
                                'last_name,'.
                                'voter_id,'.
                                'address,'.
                                //'street,'.
                                
    							//$district.'.district_name,'.
    							//$city.'.city_name,'.
    							//$village.'.village_name,'.
    							'pincode,'.
    							'gender,'.
    							//'face_color,'.
    							'date_of_birth,'.
    							'admin_id,'.
                                'mobile_number,'.
                                'email,'.
                                'voting_surety,'.
                                'caste,'.
                                'status'
                              
                                
    					))
    				    ->where($user_details.'.voter_id','!=','')
                       // ->join($district,$user_details.'.district','=',$district.'.id')
    					//->join($city,$user_details.'.city','=',$city.'.id')
    					//->join($village,$user_details.'.village','=',$village.'.id')
                        ->orderBy($user_details.'.id','DESC');

		if(session('subadmin_id')!='1')
		{
			$obj_user = $obj_user->where('admin_id',session('subadmin_id'));
		}

		// if(isset($arr_search_column['q_first_name']) && $arr_search_column['q_first_name']!=""){
			
		// 	$obj_user = $obj_user->where('first_name', 'LIKE', "%".$arr_search_column['q_first_name']."%");	
		// }

		// if(isset($arr_search_column['q_last_name']) && $arr_search_column['q_last_name']!=""){
			
		// 	$obj_user = $obj_user->where('last_name', 'LIKE', "%".$arr_search_column['q_last_name']."%");	
		// }

		if(isset($arr_search_column['q_full_name']) && $arr_search_column['q_full_name']!="")
        {
            $obj_user = $obj_user->where('first_name', 'LIKE', "%".$arr_search_column['q_full_name']."%")
                                 ->orWhere('last_name', 'LIKE', "%".$arr_search_column['q_full_name']."%");
        }
		
		if(isset($arr_search_column['q_voter_id']) && $arr_search_column['q_voter_id']!="")
		{	
			$obj_user = $obj_user->where('voter_id', 'LIKE', "%".$arr_search_column['q_voter_id']."%");	
		}

		if(isset($arr_search_column['q_address']) && $arr_search_column['q_address']!="")
		{	
			$obj_user = $obj_user->where('address', 'LIKE', "%".$arr_search_column['q_address']."%");
			/*$obj_user = $obj_user->where('house_no', 'LIKE', "%".$arr_search_column['q_address']."%")
								 ->orWhere('street', 'LIKE', "%".$arr_search_column['q_address']."%")
								 ->orWhere('village_name', 'LIKE', "%".$arr_search_column['q_address']."%")
								 ->orWhere('city_name', 'LIKE', "%".$arr_search_column['q_address']."%")
								 ->orWhere('district_name', 'LIKE', "%".$arr_search_column['q_address']."%")
								 ->orWhere('pincode', 'LIKE', "%".$arr_search_column['q_address']."%");*/
		}						 
								 
		/*if(isset($arr_search_column['q_city']) && $arr_search_column['q_city']!="")
		{	
			$obj_user = $obj_user->where('city_name', 'LIKE', "%".$arr_search_column['q_city']."%");
		}*/

		if(isset($arr_search_column['q_status']) && $arr_search_column['q_status']!=""){
			$obj_user = $obj_user->where('status', 'LIKE', "%".$arr_search_column['q_status']."%");
	
		}
				
		if(isset($arr_search_column['q_voting_surety']) && $arr_search_column['q_voting_surety']!="")
		{
			$obj_user = $obj_user->where('voting_surety', 'LIKE', "%".$arr_search_column['q_voting_surety']."%");	
		}						
		
		

		// $obj_user = $obj_user->orderBy('created_at','desc');

		if($obj_user)
		{
			$json_result  = DataTables::of($obj_user)->make(true);
			$build_result = $json_result->getData();
			// dd($build_result->data);
			foreach ($build_result->data as $key => $data) 
			{
				if(isset($build_result->data) && sizeof($build_result->data)>0)
				{

					$built_view_href   = $this->module_url_path.'/view/'.base64_encode($data->id);

					$built_transaction_href   = $this->module_url_path.'/transaction/'.base64_encode($data->id);

					$built_edit_href   = $this->module_url_path.'/edit/'.base64_encode($data->id);

					$built_delete_href 	= $this->module_url_path.'/delete/'.base64_encode($data->id);

					$built_download_href   = $this->module_url_path.'/download/'.base64_encode($data->id);

					if($data->status != null && $data->status == "0")
					{
						if(get_admin_access('voters','approve'))
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
						if(get_admin_access('voters','approve'))
						{
							$build_status_btn = '<a class="label label-success label-mini" title="Active" href="'.$this->module_url_path.'/block/'.base64_encode($data->id).'" onclick="return confirm_action(this,event,\'Do you really want to inactivate this record ?\')" >Active</a>';
						}
						else
						{
							$build_status_btn = '<span class="label label-success label-mini">active</span>';
						}

					}

					
					if($data->voting_surety != null && $data->voting_surety == 0)
    				{
    					$build_voting_surety_btn='<span class="label label-success label-mini">Full surety</span>';
    				}
    				elseif($data->voting_surety != null && $data->voting_surety == 1)
    				{
    					$build_voting_surety_btn ='<span class="label label-warning label-mini">Half surety</span>';
    				}
    				elseif($data->voting_surety != null && $data->voting_surety == 2)
    				{					
    					$build_voting_surety_btn ='<span class="label label-danger label-mini">Not sured</span>';	
    				}



						if(get_admin_access('voters','view'))
						{
							$built_view_button = " 
							<a class='btn btn-default btn-rounded btn-sm show-tooltip' href='".$built_view_href."' title='View' data-original-title='View'><i class='fa fa-eye' ></i> View</a>
							";
						}
						else
						{
							$built_view_button = '';
						}

						if(get_admin_access('voters','edit'))
						{
							$built_edit_button = "
							<a class='btn btn-default btn-rounded btn-sm show-tooltip edit_button' href='".$built_edit_href."'  title='Edit' data-original-title='Edit'><i class='fa fa-pencil-square-o' ></i> Edit</a>
							";
						}
						else
						{
							$built_edit_button = '';
						}

					
						if(get_admin_access('voters','delete'))
						{
							$built_delete_button = 	"
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
                    $action_button_html .= '<li>'.$built_edit_button.'</li>';                    
                    $action_button_html .= '<li>'.$built_delete_button.'</li>';
                    $action_button_html .= '</ul>';
                    $action_button_html .= '</li>';
                    $action_button_html .= '</ul>';
                    
                    
					$id 		        = isset($data->id)? base64_encode($data->id):'';
					$user_ID	        = isset($data->user_id)?$data->user_id:'';
					$voter_id	        = isset($data->voter_id)?$data->voter_id:'';
					//$face_color	        = isset($data->face_color)?$data->face_color:'';
					$first_name         = isset($data->first_name) && $data->first_name!="" ? $data->first_name:'';
					$last_name          = isset($data->last_name) && $data->last_name!="" ? $data->last_name:'';
					$email    			= isset($data->email)?$data->email:'';
					$religion     		= isset($data->religion)?$data->religion.', ':'';
					$caste     		    = isset($data->caste)?$data->caste.', ':'';
					$address     		= isset($data->address)?$data->address.', ':'';
					//$street 	    	= isset($data->street)?$data->street.', ':'';
					//$village_name     	= isset($data->village_name)?$data->village_name.', ':'';
					//$temp_city_name   	= isset($data->city_name)?$data->city_name.', ':'';
					//$city_name   	  	= isset($data->city_name)?$data->city_name:'';
					//$pincode 	    	= isset($data->pincode)?$data->pincode.', ':'';
					//$district_name     	= isset($data->district_name)?$data->district_name.'.':'';
					// $state 	    		= isset($data->state)?$data->state.', ':'';
					//$address 			= $house_no.$street.$village_name.$temp_city_name.$pincode.$district_name;
					$ward   	  		= isset($data->ward)?$data->ward.', ':'';
					$booth   	  		= isset($data->booth)?$data->booth.', ':'';
					$list 	    	    = isset($data->list)?$data->list.', ':'';
					$occupation 	    = isset($data->occupation)?$data->occupation.', ':'';
					$created_at         = isset($data->created_at)?$data->created_at:'';
					$built_action_button= $built_view_button.$built_edit_button.$built_delete_button;

					$build_result->data[$key]->id         		   = $id;
					$build_result->data[$key]->user_ID         	   = $user_ID;					
					$build_result->data[$key]->full_name          = $first_name.' '.$last_name;
					$build_result->data[$key]->last_name           = $last_name;
					$build_result->data[$key]->voter_id            = $voter_id;
					$build_result->data[$key]->address             = $address;
					//$build_result->data[$key]->city_name 	       = $city_name;
					//$build_result->data[$key]->face_color          = $face_color;
					$build_result->data[$key]->religion            = $religion;
					$build_result->data[$key]->ward                = $ward;
					$build_result->data[$key]->booth               = $booth;
					$build_result->data[$key]->list                = $list;
					$build_result->data[$key]->occupation          = $occupation;
					$build_result->data[$key]->caste               = $caste;
					// $build_result->data[$key]->full_name           = $full_name;
					// $build_result->data[$key]->email               = $email;
					// $build_result->data[$key]->created_at          = get_formated_date($created_at);
					$build_result->data[$key]->build_status_btn    = $build_status_btn;
					$build_result->data[$key]->build_voting_surety_btn= $build_voting_surety_btn;
					$build_result->data[$key]->built_action_button = $action_button_html;
			
				}
			}
			return response()->json($build_result);
		}
	
    } 

	 public function create()
	{
		
		$obj_wards = $this->WardsModel->get();
		if($obj_wards)
		{
			$arr_wards = $obj_wards->toArray();
		}

		$arr_religion = [];

		$obj_religion = $this->ReligionModel->get();
		if($obj_religion)
		{
			$arr_religion = $obj_religion->toArray();
		}

		$arr_caste = [];

		$obj_caste = $this->CasteModel->get();
		if($obj_caste)
		{
			$arr_caste = $obj_caste->toArray();
		}
		$arr_occupation = [];
		$obj_occupation = $this->OccupationModel->get();
		if($obj_occupation)
		{
			$arr_occupation = $obj_occupation->toArray();

		}


		$this->arr_view_data['parent_module_icon']  = "fa-home";
        $this->arr_view_data['parent_module_title'] = "Dashboard";
        $this->arr_view_data['parent_module_url']   = url('/').'/admin/dashboard';
		$this->arr_view_data['page_title']       	= 'Create '.str_singular($this->module_title);
		$this->arr_view_data['page_icon']        	= $this->module_icon;
		$this->arr_view_data['module_title']     	= 'Manage '.$this->module_title;
		$this->arr_view_data['sub_module_title'] 	= 'Create '.$this->module_title;
		$this->arr_view_data['sub_module_icon']  	= 'fa fa-plus';
		$this->arr_view_data['module_icon']      	= $this->module_icon;
		$this->arr_view_data['admin_panel_slug'] 	= $this->admin_panel_slug;
		$this->arr_view_data['module_url_path']  	= $this->module_url_path;
		$this->arr_view_data['module_url']       	= $this->module_url_path;
		$this->arr_view_data['arr_wards'] 	     	= $arr_wards;
		$this->arr_view_data['arr_religion']     	= $arr_religion;
		$this->arr_view_data['arr_caste']        	= $arr_caste;
		$this->arr_view_data['arr_occupation']   	= $arr_occupation;
		// dd($arr_districts);
		// dd(session('subadmin_id'));
		return view($this->module_view_folder.'.create',$this->arr_view_data);
	}

	public function store(Request $request)
	{ 
		// dd($request->all());
		$arr_rules      = $arr_data = array();
		$status         = false;

		$arr_rules['_token']  	             = "required";
		$arr_rules['voter_id']      		 = "unique:users,voter_id";
		$arr_rules['gender']  	        	 = "required";
		$arr_rules['date_of_birth']  	     = "required";
		$arr_rules['first_name']  	         = "required";
		$arr_rules['last_name']  	         = "required";
		$arr_rules['father_full_name']       = "required";
		$arr_rules['address']  	         	 = "required";
		$arr_rules['mobile_number']  	     = "required";
		$arr_rules['voting_surety']  	     = "required";
		$arr_rules['religion']  	         = "required";
		$arr_rules['caste']  	             = "required";
		$arr_rules['ward'] 					 = "required";
		$arr_rules['booth'] 				 = "required";
 		$arr_rules['list']					 = "required";
		$arr_rules['occupation']  	         = "required";
		// $arr_rules['latitude']  	         = "required";
		// $arr_rules['longitude']  	         = "required";
		
		$validator = validator::make($request->all(),$arr_rules);

		if($validator->fails()) 
		{
			/*dd($validator->messages());	*/
			return redirect()->back()->withErrors($validator)->withInput();
			
		}
		$arr_data['voter_id']			= $request->input('voter_id', null);
		$arr_data['first_name']			= $request->input('first_name', null);
		$arr_data['last_name']			= $request->input('last_name', null);
		$arr_data['father_full_name']	= $request->input('father_full_name', null);	
		$arr_data['gender']	   	 		= $request->input('gender', null);
		$arr_data['date_of_birth']		= $request->input('date_of_birth', null);
		$arr_data['address']			= $request->input('address', null);	
		$arr_data['booth']	   	 	    = $request->input('booth', null);
		$arr_data['ward']	    	    = $request->input('ward', null);
		$arr_data['list']	    	    = $request->input('list', null);
		$arr_data['email']	 	  	  	= $request->input('email', null);
		$arr_data['mobile_number']		= /*'+91'.*/$request->input('mobile_number', null);
		//$arr_data['face_color']	  	  	= $request->input('face_color', null);
		$arr_data['voting_surety']		= $request->input('voting_surety', null);	
		$arr_data['religion']	  	  	= $request->input('religion', null);
		$arr_data['caste']	      	  	= $request->input('caste', null);
		$arr_data['occupation']	  	  	= $request->input('occupation', null);
		$arr_data['latitude']	 	   	= $request->input('latitude', null);
		$arr_data['longitude']	 	   	= $request->input('longitude', null);
		$arr_data['admin_id'] 			= session('subadmin_id');
	//dd($arr_data);
		$status = $this->BaseModel->create($arr_data);
		
		          /*$message    =  "Hello Welcome to Voter Management ";
                    $to_number  = "+918888910323";
				   $email_status = $this->SmsService->send_sms($message,$to_number);*/

				 
		if($status)
		{
			Session::flash('success', str_singular($this->module_title).' created successfully.');
			return redirect($this->module_url_path);
		}
		Session::flash('error', 'Error while creating '.str_singular($this->module_title).'.');
		return redirect($this->module_url_path);

	}



	public function voter_create()
	{
		$arr_districts = [];
		$obj_districts = $this->DistrictModel->get();
		if($obj_districts)
		{
			$arr_districts = $obj_districts->toArray();
		}
		$arr_religion = [];
		$obj_religion = $this->ReligionModel->get();
		if($obj_religion)
		{
			$arr_religion = $obj_religion->toArray();
		}
		$arr_caste = [];
		$obj_caste = $this->CasteModel->get();
		if($obj_caste)
		{
			$arr_caste = $obj_caste->toArray();
		}
		$arr_occupation = [];
		$obj_occupation = $this->OccupationModel->get();
		if($obj_occupation)
		{
			$arr_occupation = $obj_occupation->toArray();
		}
		$this->arr_view_data['parent_module_icon']  	= "fa-home";
        $this->arr_view_data['parent_module_title']  	= "Dashboard";
        $this->arr_view_data['parent_module_url']    	= url('/').'/admin/dashboard';
		$this->arr_view_data['page_title']       		= 'Register '.str_singular($this->module_title);
		$this->arr_view_data['page_icon']        		= $this->module_icon;
		$this->arr_view_data['module_title']     		= 'Manage '.$this->module_title;
		$this->arr_view_data['sub_module_title'] 		= 'Register '.$this->module_title.' '.'without Voter Card';
		$this->arr_view_data['sub_module_icon']  		= 'fa fa-plus';
		$this->arr_view_data['module_icon']      		= $this->module_icon;
		$this->arr_view_data['admin_panel_slug'] 		= $this->admin_panel_slug;
		$this->arr_view_data['module_url_path']  		= $this->module_url_path;
		$this->arr_view_data['module_url']       		= $this->module_url_path;
		$this->arr_view_data['arr_districts']    		= $arr_districts;
		$this->arr_view_data['arr_religion']     		= $arr_religion;
		$this->arr_view_data['arr_caste']        		= $arr_caste;
		$this->arr_view_data['arr_occupation']   		= $arr_occupation;
		// dd($arr_districts);
		return view($this->module_view_folder.'.create_without_voter_card',$this->arr_view_data);
	}

	public function aadhar_voter()
	{
		$arr_districts = [];
		$obj_districts = $this->DistrictModel->get();
		if($obj_districts)
		{
			$arr_districts = $obj_districts->toArray();
		}
		$arr_religion = [];
		$obj_religion = $this->ReligionModel->get();
		if($obj_religion)
		{
			$arr_religion = $obj_religion->toArray();
		}
		$arr_caste = [];
		$obj_caste = $this->CasteModel->get();
		if($obj_caste)
		{
			$arr_caste = $obj_caste->toArray();
		}
		$arr_occupation = [];
		$obj_occupation = $this->OccupationModel->get();
		if($obj_occupation)
		{
			$arr_occupation = $obj_occupation->toArray();
		}
		$this->arr_view_data['parent_module_icon']  	= "fa-home";
        $this->arr_view_data['parent_module_title']  	= "Dashboard";
        $this->arr_view_data['parent_module_url']    	= url('/').'/admin/dashboard';
		$this->arr_view_data['page_title']       		= 'Register '.str_singular($this->module_title);
		$this->arr_view_data['page_icon']        		= $this->module_icon;
		$this->arr_view_data['module_title']     		= 'Manage '.$this->module_title;
		$this->arr_view_data['sub_module_title'] 		= 'Register '.$this->module_title.' '.'without Aadhar Card & Voter Card';
		$this->arr_view_data['sub_module_icon']  		= 'fa fa-plus';
		$this->arr_view_data['module_icon']      		= $this->module_icon;
		$this->arr_view_data['admin_panel_slug'] 		= $this->admin_panel_slug;
		$this->arr_view_data['module_url_path']  		= $this->module_url_path;
		$this->arr_view_data['module_url']       		= $this->module_url_path;
		$this->arr_view_data['arr_districts']    		= $arr_districts;
		$this->arr_view_data['arr_religion']     		= $arr_religion;
		$this->arr_view_data['arr_caste']        		= $arr_caste;
		$this->arr_view_data['arr_occupation']   		= $arr_occupation;
		// dd($arr_districts);
		return view($this->module_view_folder.'.create_without_aadhar_voter',$this->arr_view_data);
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
		// dd($request->all());
		$arr_data = [];

		$ward_id = $request->input('ward_id');
		//dd($ward_id);
		$obj_data = $this->BoothModel->where('ward_id',$ward_id)
									   ->get();
		// dd($obj_data->toArray());									   
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

  public function get_list(Request $request)
	{
		
		$arr_data = [];

		$id = $request->input('booth_id');
		
		$obj_data = $this->ListModel->where('booth_id',$id)->get();
		if($obj_data)
        {
            $arr_data = $obj_data->toArray();
        }
		$html = "<option value=''>Select List </option>";        
        foreach ($arr_data as $key => $value) {
        $html .=  " <option value=".$value['id'].">"."(". $value['list_no'].")".$value['list_name']."</option>";
    	}

    	return response()->json($html);
	}

   

	public function view($enc_id)
	{
		$arr_user = [];
		

		if($enc_id==false)
		{
			return redirect()->back()->with('error','Something went wrong');
		}

		$id = base64_decode($enc_id);

	   /*$arr_data =  $arr_districts = $arr_city= $arr_village= [];
 
		$obj_districts = $this->DistrictModel->get();
		if($obj_districts)
		{
			$arr_districts = $obj_districts->toArray();
		}

		$obj_city = $this->CityModel->get();
		if($obj_city)
		{
			$arr_city = $obj_city ->toArray();
			
		}*/

		$obj_data = $this->BaseModel->where('id',$id)->with('get_religion_details','get_caste_details','get_ward_details','get_booth_details','get_list_details','get_occupation_details')->first();
		if($obj_data)
		{
			$arr_user = $obj_data->toArray();
		//dd($arr_user);
			
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
		//$this->arr_view_data['arr_districts'] 	             = $arr_districts;
		//$this->arr_view_data['arr_city'] 	                 = $arr_city;
		$this->arr_view_data['arr_user'] 		             = $arr_user;

		return view($this->module_view_folder.'.view',$this->arr_view_data);
	}

	 public function edit($enc_id='')
	{
		/*dd($get_district_details);*/
		
		if($enc_id=='')
		{
			return redirect()->back();
		}

		/*$obj_data = $this->BaseModel->with('get_state_details','get_district_details','get_cities_details','get_village_details','get_booth_details','get_list_details')->where('id', base64_decode($enc_id))->first();
		 // dd($obj_data->toArray());
		$arr_data =  $arr_districts = $arr_city= $arr_village = $arr_booth = $arr_occupation= $arr_list = [];
		*/
		$obj_data = $this->BaseModel->with('get_booth_details','get_list_details')->where('id', base64_decode($enc_id))->first();
		 // dd($obj_data->toArray());
		$arr_data =  $arr_booth = $arr_occupation= $arr_list = [];
	
		if($obj_data)
		{
			$arr_data = $obj_data->toArray();

			/*$obj_districts = $this->DistrictModel->get();
			if($obj_districts)
			{
				$arr_districts = $obj_districts->toArray();
			}
			$obj_city = $this->CityModel->where('district_id',$arr_data['district'])
									    ->get();
			if($obj_city)
			{
				$arr_city = $obj_city->toArray();
			}
			$obj_village = $this->VillageModel->where('city_id',$arr_data['city'])
											  ->get();
			if($obj_village)
			{
				$arr_village = $obj_village->toArray();
			}*/
			$obj_wards = $this->WardsModel->get();
		    if($obj_wards)
		    {
		 	   $arr_ward = $obj_wards->toArray();
		    }

			$obj_booth = $this->BoothModel->where('ward_id',$arr_data['ward'])
									    ->get();
			if($obj_booth)
			{
				$arr_booth = $obj_booth->toArray();
			}
			$obj_religion = $this->ReligionModel->get();
			if($obj_religion)
			{
				$arr_religion = $obj_religion->toArray();
			}
			$obj_caste = $this->CasteModel->get();
			if($obj_caste)
			{
				$arr_caste = $obj_caste->toArray();
			}
			$obj_occupation = $this->OccupationModel->get();
			if($obj_occupation)
			{
				$arr_occupation = $obj_occupation->toArray();
			}

			$obj_list = $this->ListModel->where('booth_id',$arr_data['booth'])
									    ->get();

			if($obj_list)
			{
				$arr_list = $obj_list->toArray();


			}
		}
		
		$this->arr_view_data['parent_module_icon']   	= "fa-home";
        $this->arr_view_data['parent_module_title']  	= "Dashboard";
        $this->arr_view_data['parent_module_url']    	= url('/').'/admin/aadhar';
		$this->arr_view_data['module_url']              = $this->module_url_path;
		$this->arr_view_data['page_title']              = 'Edit '.str_singular($this->module_title);
		$this->arr_view_data['page_icon']               = $this->module_icon;
		$this->arr_view_data['module_title']            = 'Manage '.$this->module_title;
		$this->arr_view_data['sub_module_title']        = 'Edit '.$this->module_title;
		$this->arr_view_data['sub_module_icon']         = 'fa fa-pencil-square-o';
		$this->arr_view_data['module_icon']             = $this->module_icon;
		$this->arr_view_data['user_image_base_path']    = $this->user_image_base_path;
		$this->arr_view_data['user_image_public_path']  = $this->user_image_public_path;
		$this->arr_view_data['admin_panel_slug']        = $this->admin_panel_slug;
		$this->arr_view_data['module_url_path']         = $this->module_url_path;
		$this->arr_view_data['arr_data']                = $arr_data;
		$this->arr_view_data['enc_id']                  = $enc_id;
		//$this->arr_view_data['arr_districts'] 	        = $arr_districts;
		//$this->arr_view_data['arr_city'] 	            = $arr_city;
		//$this->arr_view_data['arr_village'] 	        = $arr_village;
		$this->arr_view_data['arr_religion']            = $arr_religion;
		$this->arr_view_data['arr_caste']               = $arr_caste;
		$this->arr_view_data['arr_ward']                = $arr_ward;
		$this->arr_view_data['arr_booth']               = $arr_booth;
		$this->arr_view_data['arr_occupation']          = $arr_occupation;
		$this->arr_view_data['arr_list']                = $arr_list;
	 //dd($this->arr_view_data);

		return view($this->module_view_folder.'.edit',$this->arr_view_data);
	}
	

	public function update(Request $request, $enc_id='')
	{
        $arr_rules      = $arr_data = array();
		$status         = false;

		$arr_rules['_token']  	              = "required";
		//$arr_rules['aadhar_id']      		 = "required";
		$arr_rules['gender']  	        	 = "required";
		$arr_rules['date_of_birth']  	     = "required";
		$arr_rules['address']  	             = "required";
		//$arr_rules['city']  	       		 = "required";
		//$arr_rules['pincode']  	        	 = "required";
		//$arr_rules['district']  	         = "required";
		$arr_rules['first_name']  	         = "required";
		$arr_rules['last_name']  	         = "required";
		$arr_rules['father_full_name']       = "required";
		//$arr_rules['street']  	             = "required";
		//$arr_rules['village']  	             = "required";
		//$arr_rules['pincode']  	             = "required";
		$arr_rules['mobile_number']  	     = "required";
		//$arr_rules['face_color']  	         = "required";
		$arr_rules['voting_surety']  	     = "required";
		$arr_rules['religion']  	         = "required";
		$arr_rules['caste']  	             = "required";
		$arr_rules['occupation']  	         = "required";
		$arr_rules['latitude']  	         = "required";
		$arr_rules['longitude']  	         = "required";

		$validator = validator::make($request->all(),$arr_rules);

		if($validator->fails()) 
		{
			/*dd($validator->messages());	*/
			return redirect()->back()->withErrors($validator)->withInput();
			
		}


			
		//$arr_data['aadhar_id']		 = $request->input('aadhar_id', null);
		$arr_data['father_full_name']= $request->input('father_full_name', null);		
		$arr_data['gender']	   	 	 = $request->input('gender', null);
		$arr_data['date_of_birth']	 = $request->input('date_of_birth', null);
		$arr_data['address']		 = $request->input('address', null);
		//$arr_data['street']	         = $request->input('street', null);
		//$arr_data['village']	     = $request->input('village',null);
		//$arr_data['city']	         = $request->input('city', null);
		//$arr_data['pincode']	     = $request->input('pincode', null);	
		//$arr_data['district']	     = $request->input('district', null);
		$arr_data['email']	 	     = $request->input('email', null);
		$arr_data['mobile_number']	 = $request->input('mobile_number', null);
		$arr_data['occupation']	     = $request->input('occupation', null);
		$arr_data['first_name']	     = $request->input('first_name', null);		
		$arr_data['last_name']	     = $request->input('last_name', null);
		$arr_data['caste']	         = $request->input('caste', null);
		//$arr_data['face_color']      = $request->input('face_color', null);
		$arr_data['latitude']        = $request->input('latitude', null);		
		$arr_data['longitude']	     = $request->input('longitude', null);
		$arr_data['voting_surety']	 = $request->input('voting_surety', null);
		$arr_data['religion']	 	     = $request->input('religion', null);
		$arr_data['booth']	         = $request->input('booth', null);
		$arr_data['list']	         = $request->input('list', null);
		$arr_data['ward']	         = $request->input('ward', null);
	//dd($arr_data);
		$status = $this->BaseModel->where('id', base64_decode($enc_id))->update($arr_data);

		if($status)
		{
			Session::flash('success', str_singular($this->module_title).' updated successfully.');
			return redirect($this->module_url_path);
		}
		Session::flash('error', 'Error while updating '.str_singular($this->module_title).'.');
		return redirect($this->module_url_path);
	}
	public function get_location($enc_id)
	{
		$this->arr_view_data['parent_module_icon']   = "fa-home";
        $this->arr_view_data['parent_module_title']  = "Dashboard";
        $this->arr_view_data['parent_module_url']    = url('/').'/admin/dashboard';
		$this->arr_view_data['page_title']           = 'Voter location';
		$this->arr_view_data['page_icon']            = $this->module_icon;
		$this->arr_view_data['module_title']         = 'Manage '.$this->module_title;
		$this->arr_view_data['sub_module_title']     = 'Voter location';
		$this->arr_view_data['sub_module_icon']      = 'fa fa-plus';
		$this->arr_view_data['module_icon']          = $this->module_icon;
		$this->arr_view_data['admin_panel_slug']     = $this->admin_panel_slug;
		$this->arr_view_data['module_url_path']      = $this->module_url_path;
		$this->arr_view_data['module_url']           = $this->module_url_path;


		$obj_admin = $this->WebAdmin->where('id',session('subadmin_id'))->first();
		// dd($obj_admin->address);
		$address = $obj_admin->address;
		$url = "https://maps.google.com/maps/api/geocode/json?key=AIzaSyB9s91K1zHQ4zz0v9oCVPnNingRJt2SGGc&address=".urlencode($address);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
		$responseJson = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($responseJson);
		if ($response->status == 'OK') {
		    $admin_latitude = $response->results[0]->geometry->location->lat;
		    $admin_longitude = $response->results[0]->geometry->location->lng;
		    // dd('Latitude: ' . $admin_latitude,'Longitude: ' . $admin_longitude);
		}


		$location = $this->BaseModel->where('id', base64_decode($enc_id))->first();
		if($location)
		{
			$location_arr=$location->toArray();
			$lng =$location_arr['longitude'];
			$lat =$location_arr['latitude'];
		}

	    $url = 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyB9s91K1zHQ4zz0v9oCVPnNingRJt2SGGc&latlng='.trim($lat).','.trim($lng).'&sensor=false';
	    $json = @file_get_contents($url);
	    $data=json_decode($json);
	    $status = $data->status;
	    if($status=="OK")
	    {
	    	// dd($data);
	      // return $data->results[3]->lat;
	    	$data_arr[0]=$lat;
	    	$data_arr[1]=$lng;
	    	// dd($data_arr);
	    	$this->arr_view_data['data_arr']       = $data_arr;
	    	$this->arr_view_data['admin_latitude'] = $admin_latitude;
	    	$this->arr_view_data['admin_longitude'] = $admin_longitude;
	    	// dd($this->arr_view_data);
			return view($this->module_view_folder.'.view_map',$this->arr_view_data);
	    }
	    else
	    {
	    	return false;
	    }
	}


	public function get_address()
	{
		// dd('here');
		$latitude =19.9974533;
		$longitude =73.7898023;
				
		$geolocation = $latitude.','.$longitude;
		$request = 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyB9s91K1zHQ4zz0v9oCVPnNingRJt2SGGc&latlng='.$geolocation.'&sensor=false'; 
		$file_contents = file_get_contents($request);
		$json_decode = json_decode($file_contents);
		if(isset($json_decode->results[0])) 
		{
		    $response = array();
		    foreach($json_decode->results[0]->address_components as $addressComponet) 
		    {
		        if(in_array('political', $addressComponet->types)) 
		        {
		            $response[] = $addressComponet->long_name; 
		        }
		    }
		    // dd($response);
		    if(isset($response[0])){ $first  =  $response[0];  } else { $first  = 'null'; }
		    if(isset($response[1])){ $second =  $response[1];  } else { $second = 'null'; } 
		    if(isset($response[2])){ $third  =  $response[2];  } else { $third  = 'null'; }
		    if(isset($response[3])){ $fourth =  $response[3];  } else { $fourth = 'null'; }
		    if(isset($response[4])){ $fifth  =  $response[4];  } else { $fifth  = 'null'; }
		     dd($first,$second,$third,$fourth,$fifth);
		    if( $first!='null'&& $second!='null'&&$third !='null'&&$fourth!='null'&&$fifth!='null') 
		    { 
		        echo "<br/>Address:: ".$first;
		        echo "<br/>City:: ".$second;
		        echo "<br/>State:: ".$fourth;
		        echo "<br/>Country:: ".$fifth;
	    	}
		    elseif($first!='null'&&$second!='null'&& $third!='null' && $fourth != 'null' && $fifth == 'null')
		    {
		        echo "<br/>Address:: ".$first;
		        echo "<br/>City:: ".$second;
		        echo "<br/>State:: ".$third;
		        echo "<br/>Country:: ".$fourth;
	    	}
		    elseif($first!='null'&&$second!='null'&&$third != 'null' && $fourth == 'null' && $fifth == 'null' ) 
		    {
		        echo "<br/>City:: ".$first;
		        echo "<br/>State:: ".$second;
		        echo "<br/>Country:: ".$third;
		    }
		    elseif($first!='null'&&$second!='null'&& $third== 'null' && $fourth == 'null' && $fifth == 'null'  ) 
		    {
		        echo "<br/>State:: ".$first;
		        echo "<br/>Country:: ".$second;
		    }
		    elseif($first!='null'&&$second=='null' && $third == 'null' && $fourth == 'null' && $fifth == 'null') 
		    {
		        echo "<br/>Country:: ".$first;
		    }
		}
	}


	public function send_newsletter(Request $request ,$enc_id='')
	{ //dd('send wishes');
		//$message = " Hello message from Dande Hanuman";
	    // $number = "+918830272373";/*$this->BaseModel->select('mobile_number')->get();*/
		   // $send_message = $this->SmsService->send_sms($message,$number);

            //  dd('send_message');
		     //dump($request->all());
		    $checked_record = $request->input('checked_record');
		    $message 		= $request->input('message');
        
		    $arr_checked_records =[];
		  //  dd($checked_record);
		     if(isset($checked_record) && sizeof($checked_record)>0)

			{ 
		       foreach($checked_record as $row){
		    	$arr_checked_records[] = base64_decode($row);
		    	
		     }

		    	$obj_data = $this->BaseModel->select('mobile_number')->whereIn('id',$arr_checked_records)->get();
		    	//dd($obj_data);

		     	$obj_number = array_column($obj_data->toArray(),'mobile_number') ;
                 //dd($obj_number);
					/*dd(base64_decode($value));*/
					/*get mobile number*/
					/*$id = base64_decode($value);*/ 
					try
					{   
						//dd($arr_number);
						 $arr_number  = $obj_number;
                        foreach ($arr_number as $key => $to_number)
                         {  //dump($to_number);
	                         $mobile_status = $this->SmsService->send_sms($message,$to_number);
	                        //dd($to_number);

                         } 
							Session::flash('success','Messagess sent successfully.');
							return redirect()->back();
					}
					catch(\Exception $e)
					{
						//dd($e);
						Session::flash('error','message not sent please try again.'); 
						return redirect()->back();                   	                       
					}

				}
				else
				{
					Session::flash('error','Please select Contact to send Message.');    
			      return redirect()->back();
				}
 
			     
		}

	public function send_birthday_msg(Request $request ,$enc_id='')
	{    

       // $message = " Hello messagesssss from Dande Hanuman";
		//$number = "+918830272373";/*$this->BaseModel->select('mobile_number')->get();*/
	   //$send_message = $this->SmsService->send_sms($message,$number);
	  // if($send_message)
	  // { //dd($send_message);
	  // 	Session::flash('success','Message sent successfully.');
							//return redirect()->back();
	  // }
	  // else{
	   	//Session::flash('error','message not sent please try again.'); 
					//	return redirect()->back(); 
	   //}
	  
	  $today = Carbon::now()->format('d/m/Y');
	// dd($today);
	  $message = "Happy birthday! May your day be filled with lots of love and happiness.";
     //dd($message);

      $users = $this->BaseModel->where('date_of_birth', '=', $today)->get();
      //dd($users);
      if($users)
      {
      	$arr_data = array_column($users->toArray(),'mobile_number') ;
      	//dd($arr_data);
	  }
 
      $send_message = $this->SmsService->send_sms($message,$arr_data);
      if($send_message)
      {//dd($send_message);
      	Session::flash('success','Messagess sent successfully.');
							return redirect()->back();
      }
      else{
      	Session::flash('error','message not sent please try again.'); 
						return redirect()->back();
      }
      // dd($send_message);
      
    }	   

	public function select_form()
	{
		$this->arr_view_data['parent_module_icon']   = "fa-home";
        $this->arr_view_data['parent_module_title']  = "Dashboard";
        $this->arr_view_data['parent_module_url']    = url('/').'/admin/dashboard';
		$this->arr_view_data['page_title']       = 'Create '.str_singular($this->module_title);
		$this->arr_view_data['page_icon']        = $this->module_icon;
		$this->arr_view_data['module_title']     = $this->module_title;
		$this->arr_view_data['sub_module_title'] = 'Select Form';
		$this->arr_view_data['sub_module_icon']  = 'fa fa-plus';
		$this->arr_view_data['module_icon']      = $this->module_icon;
		$this->arr_view_data['admin_panel_slug'] = $this->admin_panel_slug;
		$this->arr_view_data['module_url_path']  = $this->module_url_path;
		$this->arr_view_data['module_url']       = $this->module_url_path;
		return view($this->module_view_folder.'.select_form',$this->arr_view_data);
	}

	public function test(){
		echo "test";
	}
}