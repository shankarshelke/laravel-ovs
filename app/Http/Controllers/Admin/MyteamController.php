<?php

namespace App\Http\Controllers\Admin;

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Common\Services\MailService;
use App\Common\Traits\MultiActionTrait;

use App\Models\UsersModel;
use App\Models\WebAdmin;
use App\Models\ModulesModel;
use App\Models\RolesModel;
use Validator;
use Session;
use DataTables;
use Response;
use DB;

class MyteamController extends Controller
{

use MultiActionTrait;
    function __construct()
    {
        $this->arr_view_data                = [];
        $this->admin_panel_slug             = config('app.project.admin_panel_slug');
        $this->admin_url_path               = url(config('app.project.admin_panel_slug'));
        $this->module_url_path              = $this->admin_url_path."/my_team";
        $this->module_title                 =trans('myteam.My Team');
        $this->module_view_folder           = "admin.myteam";
        $this->module_icon                  = "fa fa-user";
        $this->auth                         = auth()->guard('admin');

        $this->BaseModel                    = new WebAdmin();
        $this->ModulesModel                 = new ModulesModel();
        $this->RolesModel                   = new RolesModel();
        $this->MailService                  = new MailService();          

        $this->user_profile_base_img_path   = base_path().config('app.project.img_path.user_profile_image');
        $this->user_profile_public_img_path = url('/').config('app.project.img_path.user_profile_image');
        $this->user_image_base_path         = base_path().config('app.project.img_path.user_image');
        $this->user_image_public_path       = url('/').config('app.project.img_path.user_image');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arr_roles = [];
        $obj_roles = $this->RolesModel->get();
        if($obj_roles)
        {
            $arr_roles = $obj_roles->toArray();  
        }
        $this->arr_view_data['page_title']          = trans('myteam.manage')."  ".trans('myteam.My Team');
        $this->arr_view_data['parent_module_icon']  = "fa-home";
        $this->arr_view_data['parent_module_title'] = trans('myteam.dashboard');
        $this->arr_view_data['parent_module_url']   = url('/').'/admin/dashboard';
        $this->arr_view_data['module_icon']         = $this->module_icon;
        $this->arr_view_data['module_title']        = trans('myteam.manage')." ".trans('myteam.My Team');
        $this->arr_view_data['module_url_path']     = $this->module_url_path;
        $this->arr_view_data['admin_url_path']      = $this->admin_url_path;
        $this->arr_view_data['admin_panel_slug']    = $this->admin_panel_slug;
        $this->arr_view_data['arr_roles']           = $arr_roles;

        return view($this->module_view_folder.'.index',$this->arr_view_data);
    }

    public function load_data(Request $request,$type=null)
    {
        $obj_user = $build_result_status_btn = $built_download_button = $search_country = "";
        $arr_search_column = $request->input('column_filter');
        // $obj_user  = $this->BaseModel->select('*')->where('admin_type','=','SUBADMIN');
        $obj_user = DB::table('web_admin')->select('*')->where('admin_type','=','SUBADMIN'); 
        
        
        if(isset($arr_search_column['q_full_name']) && $arr_search_column['q_full_name']!="")
        {
            $obj_user = $obj_user->where('first_name', 'LIKE', "%".$arr_search_column['q_full_name']."%")
                                 ->orWhere('last_name', 'LIKE', "%".$arr_search_column['q_full_name']."%");
        }

        if(isset($arr_search_column['q_email']) && $arr_search_column['q_email']!="")
        {
            $obj_user = $obj_user->where('email', 'LIKE', "%".$arr_search_column['q_email']."%"); 
        }

        if(isset($arr_search_column['q_contact']) && $arr_search_column['q_contact']!="")
        {
            $obj_user = $obj_user->where('contact', 'LIKE', "%".$arr_search_column['q_contact']."%");
        }
        if(isset($arr_search_column['status']) && $arr_search_column['status']!="")
        {
            $obj_user = $obj_user->where('status', 'LIKE', "%".$arr_search_column['status']."%");
        }

       $obj_user = $obj_user->orderBy('created_at','desc');

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

                    $built_delete_href  = $this->module_url_path.'/delete/'.base64_encode($data->id);

                    $built_download_href   = $this->module_url_path.'/download/'.base64_encode($data->id);

                    $built_permission_href = $this->module_url_path.'/edit_permission/'.base64_encode($data->id);

                    if($data->status != null && $data->status == "0")
                    {
                       
                        if(get_admin_access('my_team','approve'))
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
                       
                        if(get_admin_access('my_team','approve'))
                        {
                            $build_status_btn = '<a class="label label-success label-mini" title="Active" href="'.$this->module_url_path.'/block/'.base64_encode($data->id).'" onclick="return confirm_action(this,event,\'Do you really want to inactivate this record ?\')" >Active</a>';
                        }
                        else
                        {
                            $build_status_btn = '<span class="label label-success label-mini">active</span>';
                        }
                    }

                    if(get_admin_access('my_team','view'))
                    {
                        $built_view_button = "<a class='btn btn-default btn-rounded btn-sm show-tooltip' href='".$built_view_href."' title='View' data-original-title='View'><i class='fa fa-eye' ></i> View</a>";
                    }
                    else
                    {
                        $built_view_button = '';
                    }


                    /*if(get_admin_access('my_team','edit'))
                    {
                        $built_edit_button = "<a  href='".$built_edit_href."'title='Edit' data-original-title='Edit'><i class='fa fa-pencil-square-o' ></i> Edit</a>";
                    }
                    else
                    {
                        $built_edit_button = '';
                    }*/

                    if(get_admin_access('finance_team','edit'))
                    {
                        $built_edit_button    = "<a class='btn btn-default btn-sm edit_button' href='javascript:void(0);' title='Edit' data-id=".base64_encode($data->id)."><i class='fa fa-pencil-square-o' ></i> Edit</a>";
                    }
                    else
                    {
                        $built_edit_button = '';
                    }

                    if(get_admin_access('my_team','delete'))
                    {
                        $built_delete_button = "<a class='btn btn-default btn-sm' href='".$built_delete_href."' title='Delete' onclick='return confirm_action(this,event,\"Do you really want to delete this record ?\")'><i class='fa fa-trash-o' ></i> Delete</a>";                        
                    }
                    else
                    {
                        $built_delete_button = '';
                    }

                    if(get_admin_access('my_team','permission'))
                    {
                        $built_permission_button = "<a class='btn btn-default btn-sm' href='".$built_permission_href."' title='edit permissions' data-original-title='edit permissions'><i class='fa fa-cogs' ></i> Edit Permissions</a>";                        
                    }
                    else
                    {
                        $built_permission_button = '';
                    }
                    
                    $action_button_html = '<ul class="action-list-main">';
                    $action_button_html .= '<li class="dropdown">';
                    $action_button_html .= '<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"> <i class="ti-menu"></i><span><i class="fa fa-caret-down"></i></span></a>';
                    $action_button_html .= '<ul class="action-drop-section dropdown-menu dropdown-menu-right">';
                    $action_button_html .= '<li>'.$built_view_button.'</li>';
                    $action_button_html .= '<li>'.$built_edit_button.'</li>';
                    $action_button_html .= '<li>'.$built_delete_button.'</li>';
                    $action_button_html .= '<li>'.$built_permission_button.'</li>';
                    $action_button_html .= '</ul>';
                    $action_button_html .= '</li>';
                    $action_button_html .= '</ul>';
                    
                    $id = isset($data->id)? base64_encode($data->id):'';
                    

                // dd($data);   
                $first_name = isset($data->first_name)? $data->first_name :'';
                $last_name  = isset($data->last_name)? $data->last_name :'';
                $full_name  = $first_name.' '.$last_name;
                $email      = isset($data->email)? $data->email :'';
                $contact    = isset($data->contact)? $data->contact :'';
                $status     = isset($data->status)? $data->status :'';
                // $created_at = isset($data->created_at)? get_formated_date($data->created_at) :'';
                $built_action_button            = $built_view_button.$built_edit_button.$built_delete_button.$built_permission_button;

                            
                $build_result->data[$key]->id                  = $id;               
                $build_result->data[$key]->full_name           = $full_name;    
                $build_result->data[$key]->email               = $email; 

                $build_result->data[$key]->contact             = $contact;    
                    
                // $build_result->data[$key]->status              = $status;
                // $build_result->data[$key]->created_at          = $created_at;
                $build_result->data[$key]->build_status_btn    = $build_status_btn;
                $build_result->data[$key]->built_action_button = $action_button_html;

            
                }
            }
            return response()->json($build_result);
        }
    
    } 

    public function create()
    {//dd($request->toArray());
        $arr_roles = [];
        $obj_roles = $this->RolesModel->get();
        if($obj_roles)
        {
            $arr_roles = $obj_roles->toArray();  
        }
        
        $this->arr_view_data['arr_roles']            = $arr_roles;
        $this->arr_view_data['parent_module_icon']   = "fa-home";
        $this->arr_view_data['parent_module_title']  =trans('myteam.dashboard');
        $this->arr_view_data['parent_module_url']    = url('/').'/admin/dashboard';
        $this->arr_view_data['page_title']           = trans('myteam.create').' '.str_singular(trans('myteam.My Team'));
        $this->arr_view_data['page_icon']            = $this->module_icon;
        $this->arr_view_data['module_title']         = trans('myteam.My Team').'  '.trans('myteam.manage');
        $this->arr_view_data['sub_module_title']     = trans('myteam.create').' '.trans('myteam.My Team');
        $this->arr_view_data['sub_module_icon']      = 'fa fa-plus';
        $this->arr_view_data['module_icon']          = $this->module_icon;
        $this->arr_view_data['admin_panel_slug']     = $this->admin_panel_slug;
        $this->arr_view_data['module_url_path']      = $this->module_url_path;
        $this->arr_view_data['module_url']           = $this->module_url_path;
      //  dd($this->arr_view_data);
        return view($this->module_view_folder.'.create',$this->arr_view_data);
    }

    public function store(Request $request)
    { 
        
  //dd($request->all());
        $arr_rules      = array();
        $status         = false;

        $arr_rules['_token']        = "required";
        $arr_rules['first_name']    = "required";
        $arr_rules['last_name']     = "required";
        $arr_rules['email']         = "required|email|unique:web_admin";
        $arr_rules['password']      = "required";
        $arr_rules['address']       = "required";
        $arr_rules['contact']       = "required";
        
        $validator = validator::make($request->all(),$arr_rules);

        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $arr_data = [];
        
        $profile_image              = $request->input('_token', null); 
        $arr_data['email']          = $request->input('email', null);
        $arr_data['first_name']     = $request->input('first_name', null);
        $arr_data['last_name']      = $request->input('last_name', null);
        $arr_data['role']           = $request->input('role', null);
        $arr_data['admin_type']     = $request->input('admin_type', null);
        $arr_data['password']       = bcrypt($request->input('password', null));
        $arr_data['contact']        = $request->input('contact', null);
        $arr_data['address']        = $request->input('address', null);
        $arr_data['profile_image']  = isset($profile_image)? $profile_image : "";
        $arr_data['permissions'] ='a:1:{s:9:"dashboard";a:1:{i:0;s:11:"module_view";}}';
         //dd($arr_data);
        if($request->hasFile('image'))
        { //dd($request->hasFile('image'))  ;      
            $file_extension = strtolower($request->file('image')->getClientOriginalExtension());

            if(in_array($file_extension,['png','jpg','jpeg']))
            {
                $file     = $request->file('image');
                $filename = sha1(uniqid().uniqid()) . '.' . $file->getClientOriginalExtension();
                $path     = $this->user_profile_base_img_path . $filename;
                $isUpload = $file->move($this->user_profile_base_img_path , $filename);
                if($isUpload)
                {
                    $arr_data['image'] = $filename;
                }
            }    
              else
            {
                /*Session::flash('error','Invalid File type, While creating '.str_singular($this->module_title));
                return redirect()->back();*/
                Session::flash(trans('myteam.error').' '.trans('myteam.Invalid File type, While creating').trans('myteam.My Team'));
                return redirect()->back();
            }
        }
        // dd($arr_data);
        $user = $this->BaseModel->create($arr_data);
        if($user)
        {
            $activation_link     = url('/').'/admin/my_team/verify_subadmin/'.base64_encode($user->id).'/'.base64_encode($request->input('password'));
            $data['to_email_id'] = $user->email;
            $data['to_user_name']    = $user->email;
            $data['verification_url']= $activation_link;
            $data['role'] = $user->role;
            $data['webadmin_id'] = $user->id;
            $data['first_name']  = $user->first_name;
            $data['last_name']   = $user->last_name;
            $data['password']    = $request->input('password');
             //dd($data);
           //$res_email = $this->MailService->send_user_registration_email($data);
            if($user)
            {
                return redirect($this->module_url_path)->with('success',str_singular($this->module_title).' created successfully.');                    
            }
            return redirect($this->module_url_path)->with('success',str_singular($this->module_title).' MAil Fail.'); 
        }
        return redirect()->back()->with('error','Error while creating '.str_singular($this->module_title));
    }


    public function view($enc_id)
    {
        $arr_user = [];
        $user_id  = base64_decode($enc_id);
        if(isset($user_id) && $user_id!="")
        {
            $obj_user = $this->BaseModel->where('id','=',$user_id)->first();
            //dd( $obj_user);
            $arr_user = $obj_user->toArray();
        }
        $user_id  = base64_decode($enc_id);
        
        $this->arr_view_data['arr_user']                     = $arr_user;
        $this->arr_view_data['parent_module_icon']           = "fa-home";
        $this->arr_view_data['parent_module_title']          = trans('myteam.dashboard');
        $this->arr_view_data['parent_module_url']            = $this->admin_url_path.'/dashboard';
        $this->arr_view_data['module_title']                 = trans('myteam.My Team');
        $this->arr_view_data['module_icon']                  = $this->module_icon;
        $this->arr_view_data['module_url']                   = $this->module_url_path;
        $this->arr_view_data['admin_panel_slug']             = $this->admin_panel_slug;
        $this->arr_view_data['sub_module_title']             =trans('myteam.view').' '.trans('myteam.My Team');
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

        if(is_numeric($id))
        {
            $obj_data = $this->BaseModel->where('id',$id)->first();
 
            
            {
                $arr_data = $obj_data->toArray();
            }
        $arr_roles = [];
        $obj_roles = $this->RolesModel->get();
        if($obj_roles)
        {
            $arr_roles = $obj_roles->toArray();     
        }    
        
        $this->arr_view_data['parent_module_icon']      = "fa-home";
        $this->arr_view_data['parent_module_title']     = "Dashboard";
        $this->arr_view_data['parent_module_url']       = url('/').'/admin/dashboard';
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
        $this->arr_view_data['arr_roles']               = $arr_roles;

        return view($this->module_view_folder.'.edit',$this->arr_view_data);
 
    }
    Session::flash('Something went wrong');
        return redirect()->back();
    }

*/


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
                $arr_resp['msg']    = "Data displayed successfully";
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
        $arr_resp['msg']     = "Something went wrong";
        $arr_resp['data']    = $arr_data;
        return $arr_resp;
    }

    public function update(Request $request, $id='')
    {
        // dd($request->all());
        /*if($enc_id!=null)
        {
            $id       = base64_decode($enc_id);
        }
*/
        $arr_rules      = array();
        $status         = false;

        $arr_rules['first_name']    = "required";
        $arr_rules['last_name']     = "required";
        $arr_rules['email']         = "required|email";
        $arr_rules['address']       = "required";
        $arr_rules['role']          = "required";
        $arr_rules['contact']       = "required";


        $validator = validator::make($request->all(),$arr_rules);

        if ($validator->fails()) 
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $email        = $request->input('email', null);

        $arr_data = [];
       /* $arr_data['user_name']      = $request->input('user_name', null);*/
         $id                         =$request->input('enc_id');
        $arr_data['first_name']     = $request->input('first_name', null);
        $arr_data['last_name']      = $request->input('last_name', null);
        $arr_data['email']          = $request->input('email', null);
        $arr_data['admin_type']     = $request->input('admin_type', null);
        $arr_data['role']           = $request->input('role', null);
        $arr_data['contact']        = $request->input('contact', null);
        $arr_data['address']        = $request->input('address', null);

        $obj_data = $this->BaseModel->where('id','=' ,$id);
        if($obj_data)
        {
            $status_update = $obj_data->update($arr_data);
        }
        
        if($status_update) 
        {
            Session::flash('success',str_singular($this->module_title).' updated successfully.');
            return redirect($this->module_url_path)->with('success',str_singular($this->module_title).' updated successfully.');
        }
        return redirect()->back()->with('error','Error while updating '.str_singular($this->module_title));

}


    public function verify_subadmin($id,$cred)
    {
        $this->module_title                      = "Admin";
        $this->arr_view_data['module_title']     = $this->module_title." Login";
        $this->arr_view_data['page_title']       = $this->module_title." Login";
        $this->arr_view_data['admin_panel_slug'] = $this->admin_panel_slug;


        $cred       = base64_decode($cred);
        $user_id    = base64_decode($id);
        // $user_email  = base64_decode($email);
         // dd($user_id,$user_email);
        $user_detail    = $this->BaseModel->where('id',$user_id)->first();
          // dd($user_detail->toArray());
        if($user_detail == "")
        {
            Session::flash('error','Please Contact Admin.');
            return view('admin.auth.login',$this->arr_view_data);
        }
        if(isset($user_id) && $user_detail->is_verified==0)
        {   
            $arr_data['is_verified'] ='1';
            $obj_user = $this->BaseModel->where('id',$user_id)->update($arr_data)/*->first()*/;
            // dd($obj_user->toArray());
            if($obj_user)
            {   //dd($obj_user->is_verified);
                // dd($obj_user->toArray());
                $obj_user = $this->BaseModel->where('id',$user_id)->first();
                $login_link     = url('/').'/admin/';
                $arr_data['to_email_id']=$obj_user->email;
                $arr_data['to_user_name']=$obj_user->email;
                $arr_data['first_name']   =$obj_user->first_name;
                $arr_data['password']=$cred;
                $arr_data['role']   =$obj_user->role;
                $arr_data['login_url']=$login_link;
                $res_email = $this->MailService->send_user_registration_detail($arr_data);
                if ($res_email)
                {
                    Session::flash('success','Check Your mail For Login Creditionals.');
                    return view('admin.auth.login',$this->arr_view_data);
                }
                else
                {
                    Session::flash('error','Account Verified. Error While Sending Mail. Please Contact Admin');
                    return view('admin.auth.login',$this->arr_view_data);
                }
                
            }
            else
            {
                Session::flash('error','Error Occcured While Verification, Please Contact Admin');
                return view('admin.auth.login',$this->arr_view_data);
            }
        }
        elseif(isset($user_id) && $user_detail->is_verified==1)
        {
            Session::flash('success','Email Allready Verified, Please Contact Admin');
            return view('admin.auth.login',$this->arr_view_data);
        }
        else
        {
            Session::flash('error','Please Contact Admin.');
            return view('admin.auth.login',$this->arr_view_data);
        }
    
    }

    
    public function edit_permission(Request $request, $enc_id='')
    {
        
        $arr_modules = $arr_abilities = $arr_admin = [];
        $id   = base64_decode($enc_id);


        $obj_admin = $this->BaseModel->where('id', $id)->where('admin_type','SUBADMIN')->first();

        if($obj_admin)
        {
            $arr_admin = $obj_admin->toArray();
            if(isset($arr_admin['permissions'])){
                $arr_abilities = unserialize($arr_admin['permissions']);
            }
        }else{
            Session::flash('error', 'Oops! Something wents wrong.');
            return redirect($this->module_url_path);
        }

        $obj_modules = $this->ModulesModel->get();

        if($obj_modules)
        {
            $arr_modules = $obj_modules->toArray();
        }
        $this->arr_view_data['id']                      = $id;
        $this->arr_view_data['arr_modules']             = $arr_modules;
        $this->arr_view_data['arr_abilities']           = isset($arr_abilities)? $arr_abilities:'';
        $this->arr_view_data['arr_admin']               = $arr_admin;

        $this->arr_view_data['parent_module_icon']      = "icon-home2";
        $this->arr_view_data['parent_module_title']     = trans('myteam.dashboard');
        $this->arr_view_data['parent_module_url']       = $this->admin_url_path.'/dashboard';
        $this->arr_view_data['module_title']            = trans('myteam.My Team');
        $this->arr_view_data['module_icon']             = $this->module_icon;
        $this->arr_view_data['module_url']              = $this->module_url_path;
        $this->arr_view_data['admin_panel_slug']        = $this->admin_panel_slug;
        $this->arr_view_data['sub_module_title']        = trans('myteam.Edit Permissions');
        $this->arr_view_data['sub_module_icon']         = 'fa fa-edit';
        $this->arr_view_data['module_url_path']         = $this->module_url_path;

        return view($this->module_view_folder.'.edit_permission',$this->arr_view_data);
    }
    public function update_permissions(Request $request, $id=null)
    {

        
        $status    = false;
        $arr_rules = array();
        
        // dd($request->all());    

        $validator = validator::make($request->all(),$arr_rules);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if($request->has('permissions'))
        {
            $permissions = serialize($request->input('permissions'));
            $arr_update = [];
            $arr_update['permissions'] = $permissions;

            $obj_admin = $this->BaseModel->where('id', $id)->where('admin_type','=','SUBADMIN')->update($arr_update);

            if($obj_admin){
                Session::flash('success', 'Privilages updated successfully!');
                return redirect()->back();
            }else{
                Session::flash('error', 'Error while updating Privilages.');
                return redirect()->back();
            }
        }else{
            $permissions = serialize(array());
            $arr_update = [];
            $arr_update['permissions'] = $permissions;
            $obj_admin = $this->BaseModel->where('id', $id)->where('admin_type','=','SUBADMIN')->update($arr_update);

            
            if($obj_admin)
            {
                Session::flash('success', 'Privilages updated successfully!');
                return redirect()->back();
            }else{
                Session::flash('error', 'Error while updating Privilages.');
                return redirect()->back();
            }
        }
    }
}
