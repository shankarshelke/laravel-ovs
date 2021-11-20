<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Common\Traits\MultiActionTrait;
use App\Common\Services\SmsService;
use App\Models\UsersModel;
use App\Models\ListModel;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ImportUser;
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
use App\Models\SmsTemplateModel;
use App\Models\AddUserTempModel;
use App\Models\SentSmsModel;
use DB;
use Validator;
use Session;
use DataTables;
use Response;
use Auth;
// use Carbon;
use Carbon\Carbon;

use Stichoza\GoogleTranslate\TranslateClient;
use TranslateText;

class UsersController extends Controller
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
		$this->AddUserTempModel 	        = new AddUserTempModel();
		$this->SmsTemplateModel 	        = new SmsTemplateModel();
		

		$this->user_profile_base_img_path   = base_path().config('app.project.img_path.user_profile_image');
		$this->user_profile_public_img_path = url('/').config('app.project.img_path.user_profile_image');
		$this->user_image_base_path         = base_path().config('app.project.img_path.user_image');
		$this->user_image_public_path 		= url('/').config('app.project.img_path.user_image');
    }

    public function index()
    {

		// $translation= TranslateText::translate('en', 'mr', "shankar jagtap");
      	// return translateText("shankar jagtap");
		$google_api_key = config('app.project.google_map_api_key');
		// $json = json_decode(file_get_contents('https://translation.googleapis.com/language/translate/v2?key='.$google_api_key.'&q=' . urlencode('Shankar Shelke') . '&target=mr&format=en'));
    	// $translated_text = $json->data->translations[0]->translatedText;
		// echo $translated_text;

		// $curl_handle=curl_init();
		// curl_setopt($curl_handle,CURLOPT_URL,'https://translation.googleapis.com/language/translate/v2?key='.$google_api_key.'&q=' . urlencode('Shankar Shelke') . '&target=mr&format=en');
		// curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
		// curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
		// $buffer = curl_exec($curl_handle);
		// curl_close($curl_handle);
		// if (empty($buffer)){
		// 	print "Nothing returned from url.<p>";
		// }
		// else{
		// 	print_r($buffer);
		// }

		// $tr = new TranslateClient('en', 'mr');
		// echo $tr->translate("shankar shelake");die;

		//     	date_default_timezone_set('Asia/Kolkata');
		//     	dd(date('H:i:s'));
		//     	$time = date('H:i:s',strtotime("9:00 AM"));
		// 			if($time < date('H:i:s')){
		// 	     		dd(date('H:i:s'));
		// 			}
		// 			else{
		// 				dd("not match");
		// 			}
    	/*$arr_districts = [];

		$obj_districts = $this->DistrictModel->get();
		if($obj_districts)
		{
			$arr_districts = $obj_districts->toArray();
		}*/

		$this->arr_view_data['page_title']          = "Manage ".$this->module_title;
        $this->arr_view_data['parent_module_icon']  = "fa-home";
        $this->arr_view_data['parent_module_title'] = "Dashboard";
        $this->arr_view_data['parent_module_url']   = url('/').'/admin/dashboard';
        $this->arr_view_data['module_icon']         = $this->module_icon;
        $this->arr_view_data['module_title']        = "Manage ".$this->module_title;
		$this->arr_view_data['module_url_path']     = $this->module_url_path;
		$this->arr_view_data['admin_url_path']      = $this->admin_url_path;
		$this->arr_view_data['admin_panel_slug']    = $this->admin_panel_slug;
		//$this->arr_view_data['arr_districts']       = $arr_districts;
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
    							'full_name,'.
    							'first_name,'.
                                'last_name,'.
                                'father_full_name,'.
                                'voter_id,'.
                                'address,'.
                                'family_id,'.
                                
                                
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
    				    // ->where($user_details.'.voter_id','!=','')
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
    				else{
    					$build_voting_surety_btn ='';
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
					$full_name         = isset($data->full_name) && $data->full_name!="" ? $data->full_name:'';
					$first_name         = isset($data->first_name) && $data->first_name!="" ? $data->first_name:'';
					$last_name          = isset($data->last_name) && $data->last_name!="" ? $data->last_name:'';					
					$father_full_name          = isset($data->father_full_name) && $data->father_full_name!="" ? $data->father_full_name:'';
					$mobile_number   = isset($data->mobile_number) && $data->mobile_number!="" ? $data->mobile_number:'';
					$email    			= isset($data->email)?$data->email:'';
					$religion     		= isset($data->religion)?$data->religion.', ':'';
					$caste     		    = isset($data->caste)?$data->caste.', ':'';
					$address     		= isset($data->address)?$data->address.', ':'';
					$ward   	  		= isset($data->ward)?$data->ward.', ':'';
					$booth   	  		= isset($data->booth)?$data->booth.', ':'';
					$list 	    	    = isset($data->list)?$data->list.', ':'';
					$occupation 	    = isset($data->occupation)?$data->occupation.', ':'';
					$family_id 	    = isset($data->family_id)?$data->family_id:'';
					$created_at         = isset($data->created_at)?$data->created_at:'';
					$built_action_button= $built_view_button.$built_edit_button.$built_delete_button;

					$build_result->data[$key]->id         		   = $id;
					$build_result->data[$key]->user_ID         	   = $user_ID;					
					$build_result->data[$key]->full_name           = ($full_name) ? $full_name : ($first_name.' '.$last_name);
					$build_result->data[$key]->last_name           = $last_name;
					$build_result->data[$key]->voter_id            = $voter_id;
					$build_result->data[$key]->address             = $address;
					$build_result->data[$key]->religion            = $religion;
					$build_result->data[$key]->ward                = $ward;
					$build_result->data[$key]->booth               = $booth;
					$build_result->data[$key]->list                = $list;
					$build_result->data[$key]->occupation          = $occupation;
					$build_result->data[$key]->mobile_number          = $mobile_number;
					$build_result->data[$key]->family_id           =  $family_id;
					$build_result->data[$key]->caste               = $caste;
					$build_result->data[$key]->build_status_btn    = $build_status_btn;
					$build_result->data[$key]->build_voting_surety_btn= $build_voting_surety_btn;
					$build_result->data[$key]->built_action_button = $action_button_html;
			
				}
			}
			return response()->json($build_result);
		}
	
    } 

	 public function create(Request $request)
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
		//Auth::user()->id
		//auth()->guard('admin')
		$obj_admin_details  = login_admin_details();
		$user_id = $obj_admin_details->id;
		$value=time().$user_id;        
        if (!$request->session()->exists('temp_id')) {
                // user value cannot be found in session
                 Session::put('temp_id', $value);
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
		//dd($request);
		$arr_rules      = $arr_data = array();
		$status         = false;

		$arr_rules['_token']  	             = "required";
		// $arr_rules['voter_id']      		 = "unique:users,voter_id";
		// $arr_rules['gender']  	        	 = "required";
		// $arr_rules['date_of_birth']  	     = "required";
		// $arr_rules['first_name']  	         = "required";
		// $arr_rules['last_name']  	         = "required";
		// // $arr_rules['father_full_name']       = "required";
		// $arr_rules['address']  	         	 = "required";
		// $arr_rules['mobile_number']  	     = "required";
		$arr_rules['voting_surety']  	     = "required";
		$arr_rules['religion']  	         = "required";
		$arr_rules['caste']  	             = "required";
		$arr_rules['ward'] 					 = "required";
		$arr_rules['booth'] 				 = "required";
 	// 	$arr_rules['list']					 = "required";
		// $arr_rules['occupation']  	         = "required";
		// $arr_rules['latitude']  	         = "required";
		// $arr_rules['longitude']  	         = "required";
		
		$validator = validator::make($request->all(),$arr_rules);

		if($validator->fails()) 
		{
			/*dd($validator->messages());	*/
			return redirect()->back()->withErrors($validator)->withInput();
			
		}
		//dd($request);
		$booth = $request->input('booth');
		$voting_surety = $request->input('voting_surety');
		$religion = $request->input('religion');
		$caste = $request->input('caste');
		$ward  = $request->input('ward');
		$temp_id = $request->input('temp_id');
		$get_temp_data = $this->AddUserTempModel
							 ->where('temp_id',$temp_id)	
							 ->get();
		$arr_data = null;	
		/* get family Id */
		$getFamilyId = UsersModel::orderBy('id','desc')
									->first();	
		if(isset($getFamilyId) && $getFamilyId !=''){
				$family_id = $getFamilyId->family_id;
				$family_id = $family_id + 1;
		}
		else{
			$family_id = 1;
		}

		if(isset($get_temp_data) && count($get_temp_data) !=0){
			foreach ($get_temp_data as $key => $value) {
				$arr_data['voter_id'] = $value['voter_id'];
				$arr_data['family_id'] = $family_id;
				$arr_data['full_name'] = $value['full_name'];
				$arr_data['first_name'] = $value['first_name'];
				$arr_data['last_name'] = $value['last_name'];
				$arr_data['father_full_name'] = $value['father_full_name'];
				$arr_data['gender'] = $value['gender'];
				$arr_data['mobile_number'] = $value['contact'];
				$arr_data['date_of_birth'] = $value['date_of_birth'];
				$arr_data['email'] = $value['email'];
				$arr_data['address'] = $value['address'];
				$arr_data['latitude'] = $value['latitude'];
				$arr_data['longitude'] = $value['longitude'];
				$arr_data['occupation'] = $value['occupation'];		
				$arr_data['voting_surety']		= $voting_surety;	
				$arr_data['religion']	  	  	= $religion ? $religion:'null';
				$arr_data['caste']	      	  	= $caste ? $caste :'null';
				$arr_data['booth']	   	 	    = $booth ? $booth:'null';
				$arr_data['ward']	    	    = $ward ? $ward:'null';	
				$arr_data['admin_id'] 			= session('subadmin_id');
				
				$status = $this->BaseModel->create($arr_data);
			}

		}					 
		          /*$message    =  "Hello Welcome to Voter Management ";
                    $to_number  = "+918888910323";
				   $email_status = $this->SmsService->send_sms($message,$to_number);*/

				 
		if($status)
		{
			DB::table('add_user_temp')->where('temp_id', $temp_id)->delete();
			Session::forget('temp_id');
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

		
		$obj_data = $this->BaseModel->with('get_booth_details','get_list_details')->where('id', base64_decode($enc_id))->first();
		 // dd($obj_data->toArray());
		$arr_data =  $arr_booth = $arr_occupation= $arr_list = [];
	
		if($obj_data)
		{
			$arr_data = $obj_data->toArray();

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
		// $arr_rules['gender']  	        	 = "required";
		// $arr_rules['date_of_birth']  	     = "required";
		// $arr_rules['address']  	             = "required";
		$arr_rules['fullname']  	         = "required";
		// $arr_rules['last_name']  	         = "required";
		// $arr_rules['father_full_name']       = "required";
		// $arr_rules['mobile_number']  	     = "required";
		// $arr_rules['voting_surety']  	     = "required";
		// $arr_rules['religion']  	         = "required";
		// $arr_rules['caste']  	             = "required";
		// $arr_rules['occupation']  	         = "required";
		// $arr_rules['latitude']  	         = "required";
		// $arr_rules['longitude']  	         = "required";

		$validator = validator::make($request->all(),$arr_rules);
		
		if($validator->fails()) 
		{
			/*dd($validator->messages());	*/
			return redirect()->back()->withErrors($validator)->withInput();
			
		}
		$fullname      = $request->input('fullname', null);		
		$fullname = explode(" ",$fullname);		
		if(isset($fullname)){
			if(count($fullname) ==2){
				// foreach ($fullname as $key => $value) {
					$arr_data['first_name'] = $fullname[0];
					$arr_data['last_name'] = $fullname[1];

				// }
			}
			if(count($fullname) ==3){
					$arr_data['first_name'] = $fullname[0];
					$arr_data['middle_name'] = $fullname[1];
					$arr_data['last_name'] = $fullname[2];
			}
		}
		$arr_data['father_full_name']= $request->input('father_full_name', null);	
		$arr_data['full_name']		 = $request->input('fullname', null);	
		$arr_data['gender']	   	 	 = $request->input('gender', null);
		$arr_data['date_of_birth']	 = $request->input('date_of_birth', null);
		$arr_data['address']		 = $request->input('address', null);
		$arr_data['email']	 	     = $request->input('email', null);
		$arr_data['mobile_number']	 = $request->input('mobile_number', null);
		$arr_data['occupation']	     = $request->input('occupation', null);
		$arr_data['caste']	         = $request->input('caste', null);
		$arr_data['latitude']        = $request->input('latitude', null);		
		$arr_data['longitude']	     = $request->input('longitude', null);
		$arr_data['voting_surety']	 = $request->input('voting_surety', null);
		$arr_data['religion']	 	     = $request->input('religion', null);
		$arr_data['booth']	         = $request->input('booth', null);
		$arr_data['list']	         = $request->input('list', null);
		$arr_data['ward']	         = $request->input('ward', null);
		$arr_data['voter_id']	         = $request->input('voter_id', null);

	//dd($arr_data);
		$status = $this->BaseModel->where('id', base64_decode($enc_id))->update($arr_data);
  		// dd($status);
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
	{ 
		//dd('send wishes');
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
		// $number = "+918830272373";/*$this->BaseModel->select('mobile_number')->get();*/
		// $send_message = $this->SmsService->send_sms($message,$number);
		// if($send_message)
		// { //dd($send_message);
		// 	Session::flash('success','Message sent successfully.');
		// 	return redirect()->back();
		// }
		// else{
		// 	Session::flash('error','message not sent please try again.'); 
		// 	return redirect()->back(); 
		// }

		$today = Carbon::now()->format('d/m/Y');
       // dd($today);
		$message = "Happy birthday! May your day be filled with lots of love and happiness.";
      //dd($message);
   
		$users = $this->BaseModel->where('date_of_birth', '=', $today)->get();
      //dd($users);
		if($users && count($users)>0)
		{
			$arr_data = array_column($users->toArray(),'mobile_number') ;

	        //dd($arr_data);

			try
			{   
				//dd($arr_number);
				$arr_number  = $arr_data;
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

	public function import(){
				// $get_family_id = UsersModel::orderBy('id','desc')->first();
				// 			//$get_family_id = $get_family_id->family_id;
				// dd($get_family_id);
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
            	$unique_id = 0;

                foreach ($data as $key => $value) {

                    foreach ($value as $key1 => $val) {
                    	//dd($val['dob']);

						$arr = null;
						if($val['sno']!=''){
							// dd("Sdf");
							$get_family_id = UsersModel::orderBy('id','desc')->first();
							if(isset($get_family_id)){
								$get_family_id = $get_family_id->family_id;
								if(isset($get_family_id) && $get_family_id!=''){
									$get_family_id = $get_family_id +1;
									$family_id = $get_family_id;
								}								
							}

							else{
								$family_id = explode('.', $val['sno']);
								$family_id = $family_id[0];								
							}

						}
						else{
							$family_id = $unique_id;
						}
                    	
						$arr['family_id'] = 	$family_id;
						$arr['full_name'] = 	$val['first_name'] .' '.$val['middle_name'].' '.$val['last_name'];
						$arr['first_name'] = 	$val['first_name'];
						$arr['last_name'] = 	$val['last_name'];
						$arr['father_full_name'] = 	$val['middle_name'];
						$arr['voter_id'] = 	$val['voter_id'];
						$arr['address'] = 	$val['address'];
						$arr['gender'] = 	$val['gender'];

						$arr['date_of_birth'] = 	$val['dob'];
						$arr['house_no'] = 	$val['house_no'];
						$arr['street'] = 	$val['street'];
						$arr['village'] = 	$val['village'];
						$arr['pincode'] = 	$val['pincode'];
						$arr['mobile_number'] = 	$val['mobile_number'];
						$arr['religion'] = 	$val['religion'];
						$arr['caste'] = 	$val['caste'];
						$arr['ward'] = 	$val['ward'];
						$arr['booth'] = 	$val['booth'];
						$arr['occupation'] = 	$val['occupation'];
						$arr['voting_surety	'] = 	$val['voting_surety'];
						$arr['admin_id'] = session('subadmin_id');
											
                        /* insert data */

                       	// $check_phone_contact = UsersModel::where('mobile_number',$arr['mobile_number'])
                       	// 							->first();
                        
                        $store = UsersModel::create($arr);
                       		$unique_id = $family_id;
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
        return redirect()->back();                        
		//return view($this->module_view_folder.'.import',$this->arr_view_data);
	}

	public function add_member(Request $request){

		$arr = null;
		$fullname = $request->input('fullname',null) ;
		$fullname = explode(" ",$fullname);
		// $tr = new TranslateClient('en', 'mr');
		// echo $tr->translate($fullname);die;
		
		if(isset($fullname)){
			if(count($fullname) ==2){
				// foreach ($fullname as $key => $value) {
					$arr['first_name'] = $fullname[0];
					$arr['last_name'] = $fullname[1];

				// }
			}
			if(count($fullname) ==3){
					$arr['first_name'] = $fullname[0];
					$arr['middle_name'] = $fullname[1];
					$arr['last_name'] = $fullname[2];
			}
		}

		$arr['voter_id'] = $request->input('voter_id',null);
		$arr['fullname'] = $request->input('fullname',null);
		$arr['father_full_name'] = $request->input('father_full_name',null);
		$arr['email'] = $request->input('email',null);
		if($request->has('date_of_birth')){
			$date_of_birth =  date("Y-m-d", strtotime($request->date_of_birth));
			$arr['date_of_birth'] = $date_of_birth;			
		}

		$arr['address'] = $request->input('address',null);
		$arr['contact'] = $request->input('contact',null);
		$arr['gender'] = $request->input('gender',null);
		$arr['occupation'] = $request->input('occupation',null);
		$arr['latitude'] = $request->input('latitude');
		$arr['longitude'] = $request->input('longitude');
		$arr['temp_id'] = $request->input('temp_id');	
		// dd(date("Y/m/d", strtotime($request->date_of_birth)));
		try
		{		
			$store = $this->AddUserTempModel->create($arr);
			

			if($store){
				return 1;
			}
			else{
				return 0;
			}
						
		}
		catch(\Exception $e)
		{
			DB::rollBack();
			\Log::emergency($e);		        	
			return 0;                   	                       
		}	
	}

	/* load member */
	public function load_member(Request $request){
		$data = $this->AddUserTempModel
					->with('get_occupation_details')
					->where('temp_id',$request->temp_id)
					->get();
		$table = "";			
		if(isset($data) && count($data) !=0){
            $table.= "
			        <thead>
			              <tr>
			                <th>#</th>
			                <th>Voter Id</th>
			                <th>Fullname</th>
			               
			                <th>Father Name</th>
			                <th>Email</th>
			                <th>Contact</th>
			                <th>DOB</th>
			                <th>Gender</th>
			                <th>Occupation</th>
			                <th>Address</th>
			               
			              </tr>
			        </thead>
			        <tbody id='cart-body'>";
			            $i = 0;
			            foreach ($data as $row) {
			                $i++;
			                $date_of_birth = date("d-m-Y ",strtotime($row ? $row->date_of_birth:'' ));
			                $voter_id = $row ? $row->voter_id :'NA';
			                $first_name = $row ? $row->first_name :'NA';
			                $last_name  = $row ? $row->last_name : 'NA';
			                $fullname = $first_name ." ". $last_name; 
			                $father_full_name = $row ? $row->father_full_name : "NA";
			                $email = $row ? $row->email :"NA";
			                $contact = $row ? $row->contact : "NA";
			                $gender = $row ? $row->gender : "NA";
			                $occupation_name = $row->get_occupation_details ? $row->get_occupation_details->occupation_name : 'NA';
			                $address = $row ? $row->address : "NA"; 
			                $table.= "<tr>
			                 <td>" . $i . "</td>
			                  <td>" .$row->voter_id."</td>
			                  <td>" .$fullname."</td>
			                  <td>" . $row->father_full_name . "</td>
			                  <td>" . $row->email . "</td>
			                  <td>" . $row->contact ."</td>
			                  <td>" . $date_of_birth . "</td>
			                  <td>" . $row->gender . "</td>
			                  <td>" . $occupation_name . "</td>
			                  <td>" . $row->address . "</td>
			                
			                    </tr>";
			            }  
			            $table.= "</tbody>";
		}
		else {
            $table = "
                    <thead>
			                <th>#</th>
			                <th>Voter Id</th>
			                <th>Fullname</th>
			                <th>Father Name</th>
			                <th>Email</th>
			                <th>Contact</th>
			                <th>DOB</th>
			                <th>Gender</th>
			                <th>Occupation</th>
			                <th>Address</th>
			                <th>Action</th>                        
			                </tr>
                    </thead>
                     <tbody id='cart-body'>
                  <tr>
                      <td colspan='10'>No records found</td>
                   </tr>
                    </tbody>";
         }
         return $table;           			

	} 

	public function send_sms(){

		$time = 1154;
		$current_time = (int) date('Hi');
		if($current_time == $time) {
		   dd("curent_time");
		}
		else{
			dd("not correct time");
		}

		$arr_template    = $this->SmsTemplateModel
								->where('id','9')
								->first();
		$day = now()->day;
		$month = now()->month;
		//dd($day);

		$obj_admin_details  = login_admin_details();
		dd($obj_admin_details->contact);
		$user_info =  UsersModel::get();

		/* get Birthday Details */
		    $sender_contact =null;
    		foreach($user_info as $key => $value) {
    			//dd($value['date_of_birth']);
				$time = strtotime($value['date_of_birth']);
					if(date('m-d') == date('m-d', $time)) {
						$sender_contact[] =$value['mobile_number'];
						$first_name = $value['first_name'] ? $value['first_name'] :'';
						$last_name  = $value['last_name']  ? $value['last_name'] : '';
						$full_name[] =$first_name.$last_name;
						$user_id[] = $value['id'];
					}
    			}

		if(isset($sender_contact) && count($sender_contact)!=0){
			foreach ($sender_contact as $key => $value) {
				/* check sent flag */
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
					$senderid = "PRINFO";

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
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_TIMEOUT, 0);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
					$output = curl_exec($ch); 
					// dd($output);
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
					if($data ==null){
						$sms['template_id'] = $arr_template->id;
						$sms['contact_no'] = $value;
						$sms['user_id'] = $user_id[$key];
						$sms['created_at'] =$today;
						$sms['flag'] ='1';
						$save_sms = SentSmsModel::create($sms);
						// $save_sms_record = 
					}  			
											

			/* send sms to admin */
				$admin_no =null;
				$admin_no = array($obj_admin_details->contact);	
				foreach ($admin_no as $key_1 => $admin_con) {
						$contant = 'Following Todays Birthday '.$full_name[$key].' '.$value;					
						$username="vpawar";
						$password="Vpawar123";
						$route  = "trans1%20";
						$senderid = "PRINFO";

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
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
						curl_setopt($ch, CURLOPT_HEADER, 0);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
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
					echo "SMS  sent Successfully.!";
					\Log::info("SMS Not sent..!");				
					dd($output);    		
				}
				else{
					echo "SMS Not sent";
					\Log::info("SMS Not sent..!");	    	 	
				}    	        		
			}

		}
			// dd($output);
		else{
			echo "record found";
			\Log::info("No Record Found..!");
		}
  	}

  	/* today birthday */
  	public function today_birthday(){
    	$arr_data = [];

		$arr_data = $this->BaseModel
           ->whereRaw('DATE_FORMAT(date_of_birth, "%m-%d") = ?', [date('m-d')])

           ->get();
		if($arr_data)
		{
			$arr_data = $arr_data->toArray();
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
		$this->arr_view_data['arr_data']       = $arr_data;
		// dd($this->arr_view_data)
		// dd(session('subadmin_id'));
		return view($this->module_view_folder.'.today_birthday',$this->arr_view_data);
    }
 
	 public function export_voters(Request $request)
    {   


        $arr_data =   $arr_invoices = $arr    = [];


        $obj_invoices = $this->BaseModel->get();
        if($obj_invoices)
        { 
        	$arr_invoices    = $obj_invoices->toArray();

            $num = 1;
            foreach ($arr_invoices as $key => $data) 
            {
                $build_status_btn = $build_volunteer_btn = '';

                if(isset($arr_invoices) && sizeof($arr_invoices)>0)
                {   
                  
                    $arr_data['id']         	  = $num;
                    $arr_data['family_id']  	  = isset($data['family_id']) && !empty($data['family_id']) ? $data['family_id']:'-';

                    $arr_data['full_name'] 			  = isset($data['full_name']) && !empty($data['full_name']) ? $data['full_name']:'-';
					$arr_data['voter_id'] 			  = isset($data['voter_id']) && !empty($data['voter_id']) ? $data['voter_id']:'-';
					$arr_data['address'] 			  = isset($data['address']) && !empty($data['address']) ? $data['address']:'-';	

					if(isset($data['status'])){
						if($data['status']=='0'){
							$arr_data['status']  =  'Inactive';
						}
						else{
							$arr_data['status']  =  'Active';
						}
					}
					

					if(isset($data['voting_surety'])){
						if($data['voting_surety'] ==0){
							$arr_data['voting_surety'] =$data['voting_surety'];
						}
						if($data['voting_surety'] ==1){
							$arr_data['voting_surety'] = 'Half Surity';
						}
						if($data['voting_surety'] ==2){
							$arr_data['voting_surety'] = 'Not Surity';
						}						
					}	
																
                    $arr_data['mobile_number'] 			  = isset($data['mobile_number']) && !empty($data['mobile_number']) ? $data['mobile_number']:'-'; 
                                      
                    array_push($this->arr_view_data, $arr_data);
                    $num++;
                }
            }
            if(isset($this->arr_view_data) && !empty($this->arr_view_data))
            {  
            	// For export as CSV 
                // $start_date_month   = date("d_M", strtotime($start_date));
                // $end_date_month     = date("d_M", strtotime($end_date));
                // $date = Carbon\Carbon::now()->format('Y-m-d'); 
                // $filename           = 'Invoices_report_till_'.$date;
                // $output = fopen("php://output",'w') or die("Can't open php://output");
                // header("Content-Type:application/csv"); 
                // header("Content-Disposition:attachment;filename=".$filename.".csv"); 
                // fputcsv($output, array('Sr.No','Invoice ID','Agent','Date','Invoice Value','Paid Amount','Remaining Payment','Payment Date'));
                // foreach($this->arr_view_data as $product) {
                //     fputcsv($output, $product);
                // }
                // fclose($output) or die("Can't close php://output");

                // For export as excel
                $table = '<table><tbody><tr><td>Sr.No</td><td>Family Id</td><td>Fullname</td><td>Voter</td><td>Mobile</td><td>Address</td><td>Status</td><td>Voting Surity</td></tr>';
				foreach ($this->arr_view_data as $row) {
				    $table.= '<tr><td>'.  implode('</td><td>', $row) . '</td></tr>';
				}
				$table.= '</tbody></table>';

				header('Content-Encoding: UTF-8');
				header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
				header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
				header ("Cache-Control: no-cache, must-revalidate");
				header ("Pragma: no-cache");
				header ("Content-type: application/x-msexcel;charset=UTF-8");
				header ("Content-Disposition: attachment; filename=Voters.xls" );

				echo $table;
            }
            else
            {
                Session::flash('error','No data found.');
                return redirect()->back();
            }
        }
    }
    
}