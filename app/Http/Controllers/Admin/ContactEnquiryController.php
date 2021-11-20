<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Common\Services\MailService;
use App\Models\ContactEnquiryModel;
use App\Common\Traits\MultiActionTrait;

use Validator;
use Session;
use DataTables;

class ContactEnquiryController extends Controller
{
	use MultiActionTrait;
	public function __construct(ContactEnquiryModel $contact_enquiry_model)
	{
		$this->arr_view_data           = [];
		$this->ContactEnquiryModel     = $contact_enquiry_model;
		$this->admin_panel_slug        = config('app.project.admin_panel_slug');
		$this->admin_url_path          = url(config('app.project.admin_panel_slug'));
		$this->module_url_path         = $this->admin_url_path."/contact_enquiry";
		$this->module_title            = "Contact Enquiry";
		$this->module_view_folder      = "admin.contact_enquiry";
		$this->module_icon             = "fa fa-envelope";
		$this->BaseModel               = $contact_enquiry_model;
		$this->ip_address        	   = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:false;  
		$this->auth               	   = auth()->guard('admin'); 
		$this->MailService        	   = new MailService();
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

	public function load_data(Request $request)
	{
		$arr_search_column      = $request->input('column_filter');

		$obj_contact_info = $this->BaseModel->orderBy('id','DESC');

		if(isset($arr_search_column['q_name']) && $arr_search_column['q_name']!="")
		{
			$obj_contact_info = $obj_contact_info->where('first_name', 'LIKE', "%".$arr_search_column['q_name']."%")->orWhere('last_name', 'LIKE', "%".$arr_search_column['q_name']."%");;	
		}
		if(isset($arr_search_column['q_email']) && $arr_search_column['q_email']!="")
		{
			$obj_contact_info = $obj_contact_info->where('email', 'LIKE', "%".$arr_search_column['q_email']."%");	
		}

		$obj_contact_info = $obj_contact_info->orderBy('created_at','desc');

		if($obj_contact_info)
		{
			$json_result  = DataTables::of($obj_contact_info)->make(true);
			$build_result = $json_result->getData();

			foreach ($build_result->data as $key => $data) 
			{
				
				$built_view_href   = $this->module_url_path.'/view/'.base64_encode($data->id);
				$built_reply_href   = $this->module_url_path.'/reply/'.base64_encode($data->id);
				 
				$built_bank_details_href   = $this->module_url_path.'/delete/'.base64_encode($data->id);

				if(isset($build_result->data) && sizeof($build_result->data)>0)
				{
					if($data->status != null && $data->status == "0")
					{   
						$built_reply_button ='';
						$build_status_btn = '<a class="btn btn-xs btn-danger" href="javascript:void(0)" style="cursor:auto;">No</a>';
						$built_reply_button = "<a class='btn btn-default btn-rounded show-tooltip' href='".$built_reply_href."' title='Reply'><i class='fa fa-reply' ></i></a>";
					}
					elseif($data->status != null && $data->status == "1")
					{
						$build_status_btn = '<a class="btn btn-xs btn-success" href="javascript:void(0)" style="cursor:auto;">Yes</a>';
						$built_reply_button = "";
					}


					$built_delete_button = "<a class='btn btn-default btn-rounded show-tooltip' title='Delete' href='".$built_bank_details_href."'  data-original-title='View Bank Details' onclick='return confirm_action(this,event,\"Do you really want to delete this record ?\")' ><i class='fa fa-trash-o' ></i> Delete</a>";

					$built_view_button = "<a class='btn btn-default btn-rounded show-tooltip' href='".$built_view_href."' title='View' data-original-title='View'><i class='fa fa-eye' ></i> View</a>";
                    
                    $action_button_html = '<ul class="action-list-main">';
                    $action_button_html .= '<li class="dropdown">';
                    $action_button_html .= '<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"> <i class="ti-menu"></i><span><i class="fa fa-caret-down"></i></span></a>';
                    $action_button_html .= '<ul class="action-drop-section dropdown-menu dropdown-menu-right">';
                    $action_button_html .= '<li>'.$built_view_button.'</li>';
                    $action_button_html .= '<li>'.$built_delete_button.'</li>';                                        
                    $action_button_html .= '</ul>';
                    $action_button_html .= '</li>';
                    $action_button_html .= '</ul>';


					$action_button = $built_reply_button.' '.$built_view_button.' '.$built_delete_button;
					$first_name = isset($data->first_name)? $data->first_name :'';
					$last_name = isset($data->last_name)? $data->last_name :'';
					$user_name = $first_name.' '.$last_name; 
					$id = isset($data->id)? base64_encode($data->id) :'';
				
					$build_result->data[$key]->id                  = $id;				
					$build_result->data[$key]->name                = isset($user_name)? $user_name :'';				
					$build_result->data[$key]->email               = isset($data->email)? $data->email :'';					
					$build_result->data[$key]->status              = isset($data->status)? $data->status :'';
					$build_result->data[$key]->created_at          = isset($data->created_at)? get_formated_date($data->created_at) :'';

					$build_result->data[$key]->build_status_btn    = $build_status_btn;
					$build_result->data[$key]->build_action_btn    = $action_button_html;

				}
			}
			return response()->json($build_result);
		}
	}

	public function view($enc_id = false)
	{	
		$arr_contact_enquiry = [];
		$id = "";
		if($enc_id == false)
		{
			Session::flash('error', 'Error while showing details');
			return redirect()->back();
		}

		$id = base64_decode($enc_id) ;

		$obj_contact_enquiry = $this->BaseModel->where('id',$id)->first();

		if($obj_contact_enquiry)
		{
			$arr_contact_enquiry = $obj_contact_enquiry->toArray();
		}

		$this->arr_view_data['id']                  = $enc_id;
		$this->arr_view_data['arr_contact_enquiry'] = $arr_contact_enquiry;
		$this->arr_view_data['page_title']          = "View ".str_singular($this->module_title);
		$this->arr_view_data['parent_module_icon']  = "fa-home";
		$this->arr_view_data['parent_module_title'] = "Dashboard";
		$this->arr_view_data['parent_module_url']   = $this->admin_url_path.'/dashboard';
		$this->arr_view_data['module_title']        = str_plural($this->module_title);
		$this->arr_view_data['module_icon']         = $this->module_icon;
		$this->arr_view_data['module_icon']         = $this->module_icon;
		$this->arr_view_data['module_url']          = $this->module_url_path;
		$this->arr_view_data['admin_panel_slug']    = $this->admin_panel_slug;
		$this->arr_view_data['sub_module_title']    = 'View '.str_singular($this->module_title);
		$this->arr_view_data['sub_module_icon']     = 'fa fa-eye';

		$this->arr_view_data['module_url_path']     = $this->module_url_path;
		
		return view($this->module_view_folder.'.view',$this->arr_view_data);
	}

	public function reply($enc_id='')
	{
		if($enc_id=='')
		{
			return redirect()->back();
		}

		$obj_data = $this->BaseModel->where('id', base64_decode($enc_id))->first();
	
		$this->BaseModel->where('id', base64_decode($enc_id))->update(['admin_reply'=>'1']);
		$arr_data = [];

		if($obj_data)
		{
			$arr_data = $obj_data->toArray();
		}
		else
		{
			return redirect()->back();
		}

		$this->arr_view_data['page_title']       = 'Reply '.$this->module_title;
		$this->arr_view_data['page_icon']        = $this->module_icon;
		$this->arr_view_data['parent_module_title'] = "Dashboard";
		$this->arr_view_data['parent_module_icon']  = "fa-home";
		$this->arr_view_data['parent_module_url']= $this->admin_url_path.'/dashboard';
		$this->arr_view_data['module_title']     = 'Manage '.$this->module_title;
		$this->arr_view_data['sub_module_title'] = 'Reply '.$this->module_title;
		$this->arr_view_data['sub_module_icon']  = 'fa fa-reply';
		$this->arr_view_data['module_icon']      = $this->module_icon;
		$this->arr_view_data['module_url']       = $this->module_url_path;
		$this->arr_view_data['admin_panel_slug'] = $this->admin_panel_slug;
		$this->arr_view_data['module_url_path']  = $this->module_url_path;
		$this->arr_view_data['arr_data']         = $arr_data;
		$this->arr_view_data['enc_id']           = $enc_id;

		return view($this->module_view_folder.'.reply',$this->arr_view_data);
	}

	public function send_reply(Request $request,$enc_id = false)
	{
		$id = "";

		if($enc_id == false)
		{
			Session::flash('error', 'Error while replying to '.$this->module_title);
			return redirect()->back();
		}

		$id = base64_decode($enc_id) ;

		$arr_rules      = array();
		
		$arr_rules['page_description']  =  "required";

		$validator = validator::make($request->all(),$arr_rules);

		if ($validator->fails()) 
		{
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$admin_reply = $request->input('page_description', null);

		$obj_enquiry   = $this->ContactEnquiryModel->where('id',$id)->first();

		if($obj_enquiry && sizeof($obj_enquiry) > 0)
		{
			$arr_data  = array();
			$arr_data['reply_message'] = $admin_reply;
			$arr_data['reply_status']  = 1;

			$arr_enquiry = $obj_enquiry->toArray();

			$arr_email['email_id'] = isset($arr_enquiry['email']) ? $arr_enquiry['email'] : '';
			$first_name  = isset($arr_enquiry['first_name']) ? $arr_enquiry['first_name'] : '';
			$last_name  = isset($arr_enquiry['last_name']) ? $arr_enquiry['last_name'] : '';
			$arr_email['name']  = $first_name.' '.$last_name;
			$arr_email['reply'] = html_entity_decode($admin_reply);

			$email_status = $this->MailService->send_conatct_enquiry_reply($arr_email);
        
            if($email_status!='')
            {  
            	$status = $this->BaseModel->where('id',$id)->update(['admin_reply'=>$admin_reply,'status' => '1']);
              
            	if($status)
            	{
            		Session::flash('success', $this->module_title.' reply sent successfully.');
            		return redirect($this->module_url_path);
            	}
            	else
            	{
            		Session::flash('error','Error while replying to '.str_singular($this->module_title));
            	}
            }
            else
            {
                Session::flash('error','Error while replying to '.str_singular($this->module_title));
            }

            return redirect()->back();
        }

    }

    public function delete($enc_id = FALSE)
	{
		$id = base64_decode($enc_id);
		
		$success = $this->BaseModel->where('id',$id)->delete();

		if($success)
		{
  			Session::flash('success','Message deleted successfully');
  			return redirect()->back();
		}
		else
		{
			Session::flash('error','Something went wrong');
  			return redirect()->back();
		}
	}	

}
