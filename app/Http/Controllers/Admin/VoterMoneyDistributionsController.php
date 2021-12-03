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

class VoterMoneyDistributionsController extends Controller
{
	use MultiActionTrait;
    function __construct()
    {
        $this->arr_view_data                = [];
        $this->admin_panel_slug             = config('app.project.admin_panel_slug');
        $this->admin_url_path               = url(config('app.project.admin_panel_slug'));
        $this->module_url_path              = $this->admin_url_path."/voter_money_distribution";
        $this->module_title                 = "Distribute Money";
        $this->module_view_folder           = "admin.voter_money_distribution";
        $this->module_icon                  = "fa fa-user";
        $this->auth                         = auth()->guard('admin');
        $this->MoneyDistributionModel       = new MoneyDistributionModel();
        $this->BaseModel                    = new VoterMoneyDistributionModel();
        $this->FinanceTeamModel             = new FinanceTeamModel();
        $this->DistrictModel                = new DistrictModel();
        $this->CityModel                    = new CityModel();
        $this->VillageModel                 = new VillageModel();
        $this->UsersModel                   = new UsersModel();
        $this->WebAdmin                     = new WebAdmin();
        

        $this->user_profile_base_img_path   = base_path().config('app.project.img_path.user_profile_image');
        $this->user_profile_public_img_path = url('/').config('app.project.img_path.user_profile_image');
        $this->user_image_base_path         = base_path().config('app.project.img_path.user_image');
        $this->user_image_public_path       = url('/').config('app.project.img_path.user_image');
    }




    public function index(Request $request,$type=null)
    {    
        $obj_finance_team = $this->FinanceTeamModel->with('get_admin_details')->where('subadmin_id',session('subadmin_id'))->get();
     
        if($obj_finance_team)
        {
            $arr_finance_team = $obj_finance_team->toArray();  
        }
        $obj_admin         = $this->WebAdmin->where('id','=',session('subadmin_id'))->first();
        // dd( $obj_admin);
        // if($obj_admin->admin_type=='SUBADMIN')
        // {
        //     $obj_voter_team = $this->UsersModel->where('role_status','1')->get();
                                           
        // }
        // else
        // {
        //   $obj_voter_team = $this->UsersModel->where('role_status','1')->get()  ;
        // }
        $obj_voter_team = $this->UsersModel->get()  ;


        $obj_admin  = $this->WebAdmin->where('id','=',session('subadmin_id'))->first();
        if($obj_admin->admin_type=='SUBADMIN')
        {
            $obj_admin_money = $this->MoneyDistributionModel->where('subadmin_id','=',$obj_admin->id)->get();
            
            $obj_voter_money = $this->BaseModel->where('subadmin_id','=',$obj_admin->id)->get();
            
        }

        else
        {
            $obj_admin_money = $this->MoneyDistributionModel->get();
            $obj_voter_money = $this->BaseModel->get();
        }
        if($obj_admin_money)
        {
            $arr_admin_money = $obj_admin_money->toArray();
            $admin_money=0;
            foreach ($arr_admin_money as $key => $value) 
            {
                $admin_money=$admin_money+$value['amount'];
            }
        }
        if($obj_voter_money)
        {
            $arr_voter_money = $obj_voter_money->toArray();
            $voter_money=0;
            foreach ($arr_voter_money as $key => $value) 
            {
                $voter_money=$voter_money+$value['amount'];
            }
        }
        if($obj_voter_team)
        {
            $arr_voter_team = $obj_voter_team->toArray();  
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
         $this->arr_view_data['arr_voter_team']       = $arr_voter_team;
        $this->arr_view_data['admin_money']          = $admin_money;
        $this->arr_view_data['voter_money']          = $voter_money;
        // dd($this->arr_view_data);
        $obj_admin         = $this->WebAdmin->where('id','=',session('subadmin_id'))->first();
        if($obj_admin)
        {$type=$obj_admin->admin_type;}
        $this->arr_view_data['type']    = $type;

        return view($this->module_view_folder.'.index',$this->arr_view_data);
    }



    public function load_data(Request $request,$type=null)
    {
        $obj_user          = $build_status_btn = $built_download_button = $search_country = "";
        $arr_search_column = $request->input('column_filter');
        $user_details      = $this->BaseModel->getTable();
        
        $obj_admin         = $this->WebAdmin->where('id','=',session('subadmin_id'))->first();
        if($obj_admin->admin_type=='SUBADMIN')
        {
            $obj_user      = $this->BaseModel->where('subadmin_id','=',session('subadmin_id'))->with(['get_admin_details','get_user_details','get_village_details']);
        }
        else
        {
            $obj_user      = $this->BaseModel->with(['get_admin_details','get_user_details']);
        }
        if(isset($arr_search_column['start_date']) && ($arr_search_column['end_date']) && $arr_search_column['start_date']!="" && $arr_search_column['end_date']!="" )
        {           
            $obj_user = $obj_user->where('d_date','>=' ,$arr_search_column['start_date'])
                                                 ->where('d_date','<=' ,$arr_search_column['end_date']);
        }


        if(isset($arr_search_column['voter_name']) && $arr_search_column['voter_name']!="")
        {

            $obj_user = $obj_user->whereHas('get_user_details',function($q)use($arr_search_column){   
                                        $q->where('first_name', 'LIKE', "%".$arr_search_column['voter_name']."%");
                                        $q->orwhere('last_name', 'LIKE', "%".$arr_search_column['voter_name']."%");
                                    });
        }
                         
         
       if(isset($arr_search_column['full_name']) && $arr_search_column['full_name']!="")
        {

            $obj_user = $obj_user->whereHas('get_admin_details',function($q)use($arr_search_column){   
                                        $q->where('first_name', 'LIKE', "%".$arr_search_column['full_name']."%");
                                        $q->orwhere('last_name', 'LIKE', "%".$arr_search_column['full_name']."%");
                                    });
        }


       if(isset($arr_search_column['amount']) && $arr_search_column['amount']!="")
        {
            
            $obj_user = $obj_user->where('amount', 'LIKE', "%".$arr_search_column['amount']."%");   
        } 
        if(isset($arr_search_column['d_date']) && $arr_search_column['d_date']!="")
        {           
            $obj_money_detail = $obj_money_detail->where('d_date', 'LIKE', "%".$arr_search_column['d_date']."%");
        }   

        // if(isset($arr_search_column['q_status']) && $arr_search_column['q_status']!="")
        // {
        //     $obj_user = $obj_user->where('status', 'LIKE', "%".$arr_search_column['q_status']."%");
    
        // }                     
        
        

        $obj_user = $obj_user->orderBy('created_at','asc');

        if($obj_user)
        {
            $json_result  = DataTables::of($obj_user)->make(true);
            $build_result = $json_result->getData();
            // dd($build_result->data);
            foreach ($build_result->data as $key => $data) 
            {
                if(isset($build_result->data) && sizeof($build_result->data)>0)
                {

                    $built_view_href          = $this->module_url_path.'/view/'.base64_encode($data->id);

                    $built_transaction_href   = $this->module_url_path.'/transaction/'.base64_encode($data->id);

                   // $built_edit_href          = $this->module_url_path.'/edit/'.base64_encode($data->id);

                    $built_delete_href        = $this->module_url_path.'/delete/'.base64_encode($data->id);

                    $built_download_href      = $this->module_url_path.'/download/'.base64_encode($data->id);

                    if($data->status != null && $data->status == "0")
                    {
                        if(get_admin_access('voter_money_distribution','approve'))
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
                        if(get_admin_access('voter_money_distribution','approve'))
                        {
                            $build_status_btn = '<a class="label label-success label-mini" title="Active" href="'.$this->module_url_path.'/block/'.base64_encode($data->id).'" onclick="return confirm_action(this,event,\'Do you really want to inactivate this record ?\')" >Active</a>';
                        }
                        else
                        {
                            $build_status_btn = '<span class="label label-success label-mini">active</span>';
                        }

                    }

                        if(get_admin_access('voter_money_distribution','view'))
                        {
                            $built_view_button = " 
                            <a class='btn btn-default btn-rounded btn-sm show-tooltip' href='".$built_view_href."' title='View' data-original-title='View'><i class='fa fa-eye' ></i> View</a>
                            ";
                        }
                        else
                        {
                            $built_view_button = '';
                        }

                      
                    
                        if(get_admin_access('voter_money_distribution','delete'))
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
                    $action_button_html .= '<li>'.$built_delete_button.'</li>';                    
                    $action_button_html .= '</ul>';
                    $action_button_html .= '</li>';
                    $action_button_html .= '</ul>';
                        
                    $id                 = isset($data->id)? base64_encode($data->id):'';
                    //$webadmin_id        = isset($data->webadmin_id)?$data->webadmin_id:'';
                    $first_name         = isset($data->get_user_details->first_name)? $data->get_user_details->first_name :'';
                    $last_name          = isset($data->get_user_details->last_name)? $data->get_user_details->last_name :'';
                    $voter_name         = $first_name.' '.$last_name;
                    
                    $first_name         = isset($data->get_admin_details->first_name)? $data->get_admin_details->first_name :'';
                    $last_name          = isset($data->get_admin_details->last_name)? $data->get_admin_details->last_name :'';
                    $full_name          = $first_name.' '.$last_name;
                    $amount             = isset($data->amount) && $data->amount!="" ? $data->amount:'';
                    $d_date          = isset($data->d_date) && $data->d_date!="" ? $data->d_date:'';
                    $created_at         = isset($data->created_at)?$data->created_at:'';
                    $built_action_button= $built_view_button./*$built_edit_button.*/$built_delete_button;

                    $build_result->data[$key]->id                  = $id;
                    $build_result->data[$key]->voter_name          = $voter_name;
                    $build_result->data[$key]->full_name           = $full_name;
                 // $build_result->data[$key]->village_name        = $village_name;
                    $build_result->data[$key]->amount              = /*'Rs.'.*/$amount;
                    $build_result->data[$key]->d_date              = $d_date;

                    $build_result->data[$key]->build_status_btn    = $build_status_btn;
                    $build_result->data[$key]->built_action_button = $action_button_html;
            
                }
            }
            return response()->json($build_result);
        }
    
    } 
 
    public function create()
    {    
        $obj_finance_team = $this->FinanceTeamModel->with('get_admin_details')->where('subadmin_id',session('subadmin_id'))->get();
     
        if($obj_finance_team)
        {
            $arr_finance_team = $obj_finance_team->toArray();  
        }
        $obj_admin         = $this->WebAdmin->where('id','=',session('subadmin_id'))->first();
        // dd( $obj_admin);
        if($obj_admin->admin_type=='SUBADMIN')
        {
            $obj_voter_team = $this->UsersModel->where('role_status','1') ->get();
                                           
        }
        else
        {
          $obj_voter_team = $this->UsersModel->where('role_status','1')->get()  ;
        }


        $obj_admin  = $this->WebAdmin->where('id','=',session('subadmin_id'))->first();
        if($obj_admin->admin_type=='SUBADMIN')
        {
            $obj_admin_money = $this->MoneyDistributionModel->where('subadmin_id','=',$obj_admin->id)->get();
            
            $obj_voter_money = $this->BaseModel->where('subadmin_id','=',$obj_admin->id)->get();
            
        }

        else
        {
            $obj_admin_money = $this->MoneyDistributionModel->get();
            $obj_voter_money = $this->BaseModel->get();
        }
        if($obj_admin_money)
        {
            $arr_admin_money = $obj_admin_money->toArray();
            $admin_money=0;
            foreach ($arr_admin_money as $key => $value) 
            {
                $admin_money=$admin_money+$value['amount'];
            }
        }
        if($obj_voter_money)
        {
            $arr_voter_money = $obj_voter_money->toArray();
            $voter_money=0;
            foreach ($arr_voter_money as $key => $value) 
            {
                $voter_money=$voter_money+$value['amount'];
            }
        }
        if($obj_voter_team)
        {
            $arr_voter_team = $obj_voter_team->toArray();  
        }
              
     /*$obj_user=$this->FinanceTeamModel->with('get_admin_details')->where('subadmin_id',session('subadmin_id'))->get();
     if($obj_user->admin_type=="SUBADMIN")
     {
        $arr_user=$obj_user->toArray();
     }
       /* $arr_districts = [];
        $this->arr_view_data['remaining_balance']    = $admin_money-$voter_money;

        $obj_districts = $this->DistrictModel->get();
        if($obj_districts)
        {
            $arr_districts = $obj_districts->toArray();
        }*/

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
        $this->arr_view_data['arr_voter_team']       = $arr_voter_team;
        $this->arr_view_data['admin_money']          = $admin_money;
        $this->arr_view_data['voter_money']          = $voter_money;
        $this->arr_view_data['remaining_balance']    = $admin_money-$voter_money;
    //dd($this->arr_view_data);
        
        return view($this->module_view_folder.'.create',$this->arr_view_data);
    }


        public function store(Request $request)
        { 
            $obj_user =$this->BaseModel->with('get_admin_details')->where('user_id',$request->input('user_id',null))->first();
            if($obj_user) {
                Session::flash('error', 'Already money distributed by '.$obj_user->get_admin_details->first_name. ' ' .$obj_user->get_admin_details->last_name);
                return redirect($this->module_url_path);
            }
            
            $arr_rules      = $arr_data = array();
            $arr_rules['user_id']               ="required";
            $arr_rules['amount']                ="required";

            $validator = validator::make($request->all(),$arr_rules);

            if($validator->fails()) 
            {
                return redirect()->back()->withErrors($validator)->withInput(); 
            }

            $remaining_balance       = $request->input('remaining_balance');
        
            if($request->input('amount') > $request->input('remaining_balance'))
            {
                Session::flash('error', ' Enter Amount less than Wallet Amount.');
                return redirect()->back()->withInput(); 
            }
              
            $obj_accountant =$this->MoneyDistributionModel->where('subadmin_id','=',session('subadmin_id'))->first();
            $obj_user =$this->UsersModel->where('id','=',$request->input('user_id',null))->first();
            
            if ($obj_accountant)
            { 
                $arr_accountant            = $obj_accountant->toArray();
            }
            else
             {
                $arr_user                  = $obj_user->toArray();
             }   

            $arr_data['subadmin_id']    = session('subadmin_id');
            $arr_data['user_id']        = $request->input('user_id',null);
            $arr_data['amount']         = $request->input('amount', null);
            $arr_data['d_date']         = date('Y-m-d');
            //dd($arr_data);

            $obj_create = $this->BaseModel->create($arr_data);
            if($obj_create)
            {
                $arr_status['role_status']  = '0';
                $obj_voter = $this->UsersModel->where('id',$obj_create['user_id'])->/*first()*/update($arr_status);
                Session::flash('success', ' Voter Amount Distributed successfully.');
                return redirect($this->module_url_path);
            }

            Session::flash('error', 'Error while Distributing Money.');
            return redirect($this->module_url_path.'/create');
        }



    public function view($enc_id)
    {
        $obj_amount = $this->BaseModel->where('id',base64_decode($enc_id))->with('get_admin_details','get_user_details')->first();
            if($obj_amount)
            {
              $arr_amount = $obj_amount->toArray();
            }
            
           
        $this->arr_view_data['arr_amount']                   = $arr_amount;
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
    //dd($this->arr_view_data);
        return view($this->module_view_folder.'.view',$this->arr_view_data);
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
    
}