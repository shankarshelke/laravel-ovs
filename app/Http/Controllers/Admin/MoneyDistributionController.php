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
use App\Models\MoneyDistributionModel;
use App\Models\VoterMoneyDistributionModel;


use App\Models\BoothModel;
use Validator;
use Session;
use DataTables;
use Response;
use DB; 

class MoneyDistributionsController extends Controller
{
	use MultiActionTrait;
    function __construct()
    {
		$this->arr_view_data                = [];
		$this->admin_panel_slug             = config('app.project.admin_panel_slug');
		$this->admin_url_path               = url(config('app.project.admin_panel_slug'));
		$this->module_url_path              = $this->admin_url_path."/money_distribution";
		$this->module_title                 = "Distribute Money";
		$this->module_view_folder           = "admin.money_distribution";
		$this->module_icon                  = "fa fa-user";
		$this->auth                         = auth()->guard('admin');
		$this->BaseModel					= new MoneyDistributionModel();
     	$this->VoterMoneyDistributionModel	= new VoterMoneyDistributionModel();
		$this->FinanceTeamModel 			= new FinanceTeamModel();
		$this->DistrictModel				= new DistrictModel();
		$this->CityModel					= new CityModel();
		$this->VillageModel					= new VillageModel();
		$this->UsersModel					= new UsersModel();
		$this->WebAdmin						= new WebAdmin();
		$this->user_profile_base_img_path   = base_path().config('app.project.img_path.user_profile_image');
		$this->user_profile_public_img_path = url('/').config('app.project.img_path.user_profile_image');
		$this->user_image_base_path         = base_path().config('app.project.img_path.user_image');
		$this->user_image_public_path 		= url('/').config('app.project.img_path.user_image');
    }

    public function index(Request $request,$type=null)
    {   
		// dd('In Index Function');

                                                                                        
		$this->arr_view_data['page_title']          = "Manage ".$this->module_title;
        $this->arr_view_data['parent_module_icon']  = "fa-home";
        $this->arr_view_data['parent_module_title'] = "Dashboard";
        $this->arr_view_data['parent_module_url']   = url('/').'/admin/dashboard';
        $this->arr_view_data['module_icon']         = $this->module_icon;
        $this->arr_view_data['module_title']        = "Manage ".$this->module_title;
		$this->arr_view_data['module_url_path']     = $this->module_url_path;
		$this->arr_view_data['admin_url_path']      = $this->admin_url_path;
		$this->arr_view_data['admin_panel_slug']    = $this->admin_panel_slug;
		// dd($this->arr_view_data);
	
		return view($this->module_view_folder.'.index',$this->arr_view_data);
    }




    public function load_data(Request $request,$type=null)
    {
    	$build_status_btn = $built_download_button = $search_country = "";
		$arr_search_column = $request->input('column_filter');
	

	    $obj_money_detail     = $this->BaseModel->with('get_admin_details')/*->get()*/;				

		//$obj_money_detail = $obj_money_detail->orderBy('created_at','asc');
		if(isset($arr_search_column['start_date']) && ($arr_search_column['end_date']) && $arr_search_column['start_date']!="" && $arr_search_column['end_date']!="" )
	    {			
			$obj_money_detail = $obj_money_detail->where('d_date','>=' ,$arr_search_column['start_date'])
												 ->where('d_date','<=' ,$arr_search_column['end_date']);
		}

	    if(isset($arr_search_column['contact']) && $arr_search_column['contact']!="")
	    {			
			$obj_money_detail = $obj_money_detail->whereHas('get_admin_details',function($q)use($arr_search_column){   
                                        $q->where('contact', 'LIKE', "%".$arr_search_column['contact']."%");
                                    });
		}
		
		if(isset($arr_search_column['full_name']) && $arr_search_column['full_name']!="")
        {
            $obj_money_detail = $obj_money_detail->whereHas('get_admin_details',function($q)use($arr_search_column){   
                                        $q->where('first_name', 'LIKE', "%".$arr_search_column['full_name']."%");
                                        $q->orwhere('last_name', 'LIKE', "%".$arr_search_column['full_name']."%");
                                    });
         }
		if(isset($arr_search_column['amount']) && $arr_search_column['amount']!="")
	    {			
			$obj_money_detail = $obj_money_detail->where('amount', 'LIKE', "%".$arr_search_column['amount']."%");
		}
		if(isset($arr_search_column['d_date']) && $arr_search_column['d_date']!="")
	    {			
			$obj_money_detail = $obj_money_detail->where('d_date', 'LIKE', "%".$arr_search_column['d_date']."%");
		}
		/*if(isset($arr_search_column['status']) && $arr_search_column['status']!="")
		{
			$obj_money_detail = $obj_money_detail->where('status', 'LIKE', "%".$arr_search_column['status']."%");
		}
*/

		if($obj_money_detail)
		{
			$json_result  = DataTables::of($obj_money_detail)->make(true);
			$build_result = $json_result->getData();

			foreach ($build_result->data as $key => $data) 
			{
				if(isset($build_result->data) && sizeof($build_result->data)>0)
				{

					$built_view_href   = $this->module_url_path.'/view/'.base64_encode($data->id);

					//$built_view_history_href   = $this->module_url_path.'/history/'.base64_encode($data->subadmin_id);



					$built_delete_href 	= $this->module_url_path.'/delete/'.base64_encode($data->id);


					
					if(get_admin_access('money_distribution','view'))
					{
						$built_view_button = "<a class='btn btn-default btn-rounded btn-sm show-tooltip' href='".$built_view_href."' title='View' data-original-title='View'><i class='fa fa-eye' ></i></a>";
						/*$built_view_history_button = "<a class='btn btn-default btn-rounded btn-sm show-tooltip' href='".$built_view_history_href."' title='View' data-original-title='Transaction'><i class='fa fa-money'></i></a>";*/
					}
					else
					{
						$built_view_button = '';
					}
                     /*if(get_admin_access('money_distribution','view_history'))
					{
						$built_view_history_button = "<a class='btn btn-default btn-rounded btn-sm show-tooltip' href='".$built_view_history_href."' title='Transaction' data-original-title='Transaction'><i class='fa fa-money'></i></a>";
					}
					else
					{
						$built_view_history_button = '';
					}
*/
					
				
					if(get_admin_access('money_distribution','delete'))
					{
						$built_delete_button  = "<a class='btn btn-default btn-sm' href='".$built_delete_href."' title='Delete' onclick='return confirm_action(this,event,\"Do you really want to delete this record ?\")'><i class='fa fa-trash-o' ></i></a>";
					}
					else
					{
						$built_delete_button = '';
					}


					
					$id = isset($data->id)? base64_encode($data->id):'';
					

					
					$contact        = isset($data->get_admin_details->contact) && $data->get_admin_details->contact!="" ? $data->get_admin_details->contact:'';
					$amount     	 = isset($data->amount) && $data->amount!="" ? $data->amount:'';
					$first_name      = isset($data->get_admin_details->first_name) && $data->get_admin_details->first_name!="" ? $data->get_admin_details->first_name:'';
					$last_name       = isset($data->get_admin_details->last_name) && $data->get_admin_details->last_name!="" ? $data->get_admin_details->last_name:'';
					$full_name       = $first_name.' '.$last_name;
					$d_date          = isset($data->d_date) && $data->d_date!="" ? $data->d_date:'';
					
					$created_at      = isset($data->created_at)?$data->created_at:'';

					$built_action_button = $built_view_button./*$built_view_history_button.*/$built_delete_button;

					
					$build_result->data[$key]->id         		  = $id;			
					$build_result->data[$key]->contact            = $contact;
					$build_result->data[$key]->amount     		  = 'Rs. '.$amount;
					$build_result->data[$key]->d_date     		  = $d_date;
					$build_result->data[$key]->full_name 		  = $full_name;	
					//$build_result->data[$key]->build_status_btn    = $build_status_btn;
					$build_result->data[$key]->built_action_button = $built_action_button;
			
				}
			}
			return response()->json($build_result);
		}
	
    }

   public function create()
	{
        $obj_finance_team = $this->FinanceTeamModel->with('get_admin_details')->get();
        if($obj_finance_team)
        {
            $arr_finance_team = $obj_finance_team->toArray();  
  			// dd($arr_finance_team);
        }
        
        
		$this->arr_view_data['parent_module_icon']   = "fa-home";
        $this->arr_view_data['parent_module_title']  = "Dashboard";
        $this->arr_view_data['parent_module_url']    = url('/').'/admin/dashboard';
		$this->arr_view_data['page_title']           = 'Create '.str_singular($this->module_title);
		$this->arr_view_data['page_icon']            = $this->module_icon;
		$this->arr_view_data['module_title']         = 'Manage '.$this->module_title;
		$this->arr_view_data['sub_module_title']     = 'Handover Money ';
		$this->arr_view_data['sub_module_icon']      = 'fa fa-plus';
		$this->arr_view_data['module_icon']          = $this->module_icon;
		$this->arr_view_data['admin_panel_slug']     = $this->admin_panel_slug;
		$this->arr_view_data['module_url_path']      = $this->module_url_path;
		$this->arr_view_data['module_url']           = $this->module_url_path;
		$this->arr_view_data['arr_finance_team']     = $arr_finance_team;
	    //dd($this->arr_view_data);
		return view($this->module_view_folder.'.create',$this->arr_view_data);
	}

	public function store(Request $request)
	{ 
		 // dd($request->all());
		$arr_rules      = $arr_data = array();
		$arr_rules['subadmin_id']  	     ="required";
		$arr_rules['contact']  	    	 ="contact";
		$arr_rules['amount']  	    	 ="required";

		$validator = validator::make($request->all(),$arr_rules);

		if($validator->fails()) 
		{
			return redirect()->back()->withErrors($validator)->withInput();	
		}
		$obj_distruibuted_village =$this->FinanceTeamModel->where('subadmin_id',$request->input('subadmin_id'))->first();
		if ($obj_distruibuted_village)
		{
			$arr_details = $obj_distruibuted_village->toArray();
		}

		$arr_data['subadmin_id']	= $request->input('subadmin_id', null);
		$arr_data['contact']    	= $request->input('contact', null);
		$arr_data['amount']    		= $request->input('amount', null);
		//$arr_data['village_id'] 	= $arr_details['village_id'];
	//	$arr_data['city_id'] 		= $arr_details['city_id'];
		$arr_data['d_date'] 		= date('Y-m-d');
		// dd($arr_data);

		$obj_create = $this->BaseModel->create($arr_data);
		if($obj_create)
		{
			Session::flash('success', ' Amount Distributed successfully.');
			return redirect($this->module_url_path);
		}
		Session::flash('error', 'Error while Distributing Money.');
		return redirect($this->module_url_path.'/create');
	}

 
	public function view($enc_id)
	{
		$obj_amount = $this->BaseModel->where('id',base64_decode($enc_id))->with('get_admin_details'/*'get_village_details','get_city_details'*/)->first();
			if($obj_amount)
			{
			  $arr_amount = $obj_amount->toArray();
		    }
		    // dd($arr_amount);						
		    
			

		
		$this->arr_view_data['arr_amount']             		 = $arr_amount;
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
		// dd($this->arr_view_data);
		return view($this->module_view_folder.'.view',$this->arr_view_data);
	}

     //*****************view History************************/
	public function history($enc_id)  
	{
		// dd(base64_decode($enc_id));

		$obj_history = $this->BaseModel->where('subadmin_id',base64_decode($enc_id))->with('get_admin_details')->get();
		if($obj_history)
		{
		  $arr_history = $obj_history->toArray();
	    }
		    
		
		$this->arr_view_data['arr_history']             	 = $arr_history;
		$this->arr_view_data['parent_module_icon']           = "fa-home";
		$this->arr_view_data['parent_module_title']          = "Dashboard";
		$this->arr_view_data['parent_module_url']            = $this->admin_url_path.'/dashboard';
		$this->arr_view_data['module_title']                 = "Transcation History of ".$this->module_title;
		$this->arr_view_data['module_icon']                  = $this->module_icon;
		$this->arr_view_data['module_url']                   = $this->module_url_path;
		$this->arr_view_data['admin_panel_slug']             = $this->admin_panel_slug;
		$this->arr_view_data['sub_module_title']             = 'History '.str_singular($this->module_title);
		$this->arr_view_data['sub_module_icon']              = 'fa fa-money';
		$this->arr_view_data['module_url_path']              = $this->module_url_path;
		$this->arr_view_data['user_profile_base_img_path']   = $this->user_profile_base_img_path;
		$this->arr_view_data['user_profile_public_img_path'] = $this->user_profile_public_img_path;
		$this->arr_view_data['user_image_base_path']         = $this->user_image_base_path;
		$this->arr_view_data['user_image_public_path']       = $this->user_image_public_path;
		// dd($this->arr_view_data);
		return view($this->module_view_folder.'.view_history',$this->arr_view_data);
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
			$arr_status['role_status']	= '0';
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
    
}