<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NewsletterModel;
use App\Common\Services\MailService;
use App\Models\NewsLetterTemplateModel;
use App\Common\Traits\MultiActionTrait;

use DataTables;
use Validator;
use Session;
class NewsletterController extends Controller
{

	use MultiActionTrait;
	function __construct(){
		$this->arr_view_data      = [];
		$this->admin_panel_slug   = 'admin';
		$this->BaseModel          = new NewsletterModel();
		$this->Template           = new NewsLetterTemplateModel();
		$this->admin_url_path     = url(config('app.project.admin_panel_slug'));
		$this->module_url_path    = $this->admin_url_path.'/newsletters';
		$this->module_title       = "Newsletters";
		$this->module_view_folder = "admin.newsletters";
		$this->module_icon        = "fa fa-rss-square";
		$this->auth               = auth()->guard('admin');
		$this->MailService        = new MailService();
		$this->ip_address         = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:false;
	}
	public function index()
	{
		$obj_newsletter_template = $this->Template->where('status',"1")
												  ->get();
		if($obj_newsletter_template) 
		{
			$arr_newsletters = $obj_newsletter_template->toArray();
		}
		$this->arr_view_data['arr_newsletters']     = $arr_newsletters;
		$this->arr_view_data['parent_module_title'] = "Dashboard";
		$this->arr_view_data['parent_module_icon']  = "fa-home";
		$this->arr_view_data['parent_module_url']	= $this->admin_url_path.'/dashboard';
		$this->arr_view_data['page_title']       	= 'Manage '.$this->module_title;
		$this->arr_view_data['module_title']     	= 'Manage '.$this->module_title;
		$this->arr_view_data['page_icon']        	= $this->module_icon;
		$this->arr_view_data['module_icon']      	= $this->module_icon;
		$this->arr_view_data['admin_panel_slug'] 	= $this->admin_panel_slug;
		$this->arr_view_data['module_url_path']  	= $this->module_url_path;

		return view($this->module_view_folder.'.index',$this->arr_view_data);
	}
	public function load_data(Request $request)
	{
	
        $obj_request_data = $this->BaseModel;
		$search = $request->input('column_filter', null);

	
		if(isset($search['q_email']) && $search['q_email']!='')
		{
			$search_term = $search['q_email'];
			$obj_request_data = $obj_request_data->where('email', 'like', '%'.$search_term.'%');
		}

	

		$obj_request_data = $obj_request_data->orderBy('created_at','DESC');
		$json_result      = DataTables::of($obj_request_data)->make(true);
		$build_result     = $json_result->getData();
		
		if(isset($build_result->data) && sizeof($build_result->data)>0)
		{
			foreach ($build_result->data as $key => $data) 
			{
			
				$email             = isset($data->email)? $data->email:'NA';
				$created_at        = isset($data->created_at)? get_formated_date($data->created_at):'NA';

				$build_result->data[$key]->email      = $email;
				$build_result->data[$key]->id         = base64_encode($data->id);
				$build_result->data[$key]->created_at = $created_at;
			
			}
		}
		return response()->json($build_result);
	}
	public function create()
	{
		$this->arr_view_data['parent_module_icon']   = "fa-home";
		$this->arr_view_data['parent_module_title']  = "Dashboard";
		$this->arr_view_data['page_title'] 			 = "Add Newsletter";
        $this->arr_view_data['module_title'] 		 = "Newsletter Template";
        $this->arr_view_data['parent_module_url']    = $this->admin_url_path.'/dashboard';
		$this->arr_view_data['sub_module_title']     = 'Add '.str_singular($this->module_title).' Template';
		$this->arr_view_data['sub_module_icon']      = 'fa fa-plus';
        $this->arr_view_data['admin_panel_slug']     = $this->admin_panel_slug;
        $this->arr_view_data['module_url_path'] 	 = $this->module_url_path;
        $this->arr_view_data['module_url']           = $this->module_url_path;
        $this->arr_view_data['module_icon']     	 = $this->module_icon;

        return view($this->module_view_folder.'.create',$this->arr_view_data);
	}

	public function store(Request $request)
    {   
        $form_data = array();
        $form_data = $request->all();
        
        $arr_rules['title']        = "required";
        $arr_rules['subject']      = "required";
        $arr_rules['description']  = "required";                
        
        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails())
        {
             return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $obj_new_letter = new NewsLetterTemplateModel;

        $is_exists   	= $obj_new_letter->where('title',$form_data['title'])->count();
        if($is_exists!=0)
        {
        	Session::flash('error',$this->module_title.' allready exists!');
            return redirect()->Back();
        }    
        
        $form_data = $request->all();
        $arr_data  = array();
        
        $arr_data['title']            = $form_data['title'];
        $arr_data['subject']      	  = $form_data['subject'];
        $arr_data['news_description'] = $form_data['description'];                      
        
        $news_letter  = $obj_new_letter->create($arr_data);
        if($news_letter)
        {
        	Session::flash('success',$this->module_title.' Template added successfully.');
        	return redirect($this->module_url_path);
        }
        else
        {
        	Session::flash('error','Error while adding '.$this->module_title.' Template.');
        }
        return redirect($this->module_url_path.'/template');
    }

    public function newsletter_template()
	{
		$this->arr_view_data['parent_module_icon']   = "fa-home";
		$this->arr_view_data['parent_module_title']  = "Dashboard";
		$this->arr_view_data['parent_module_url']    = $this->admin_url_path.'/dashboard';
		$this->arr_view_data['page_title']           = "Manage ".str_plural($this->module_title);
		$this->arr_view_data['module_title']         = "Manage ".$this->module_title." Templates";
		$this->arr_view_data['module_icon']          = $this->module_icon;
		$this->arr_view_data['module_url_path']      = $this->module_url_path;
		$this->arr_view_data['admin_panel_slug']     = $this->admin_panel_slug;
		$this->arr_view_data['module_url']           = $this->module_url_path;
		$this->arr_view_data['module_icon']     	 = $this->module_icon;
		return view($this->module_view_folder.'.manage_newsletter_template',$this->arr_view_data);
	}

	public function load_template_data(Request $request)
	{

		$search = $request->input('column_filter', null);

		$obj_new_letter_template = new NewsLetterTemplateModel;

		if(isset($search['q_title']) && $search['q_title']!='')
		{
			$search_term = $search['q_title'];
			$obj_new_letter_template = $obj_new_letter_template->where('title', 'like', '%'.$search_term.'%');
		}
		
		$obj_new_letter_template = $obj_new_letter_template
										   ->select(['id','subject','title','news_description','status','created_at'])->orderBy('id','desc');
		if($obj_new_letter_template)
		{
			$json_result  = DataTables::of($obj_new_letter_template)->make(true);
			
			$build_result = $json_result->getData();
				
			foreach ($build_result->data as $key => $data) 
			{
				$built_view_href           = $this->module_url_path.'/edit/'.base64_encode($data->id);
				$built_bank_details_href   = $this->module_url_path.'/delete/'.base64_encode($data->id);

				if(isset($build_result->data) && sizeof($build_result->data)>0)
				{
					$id = isset($data->id)? base64_encode($data->id) :'';

					if($data->status != null && $data->status == "0")
					{   
						$build_status_btn = '<a class="btn btn-default btn-rounded show-tooltip" title="Activate" href="'.$this->module_url_path.'/activate/'.base64_encode($data->id).'" 
						onclick="return confirm_action(this,event,\'Do you really want to activate this record ?\')" ><i class="fa fa-lock"></i></a>';
					}
					elseif($data->status != null && $data->status == "1")
					{
						$build_status_btn = '<a class="btn btn-default btn-rounded show-tooltip" title="Deactivate" href="'.$this->module_url_path.'/deactivate/'.base64_encode($data->id).'" onclick="return confirm_action(this,event,\'Do you really want to deactivate this record ?\')" ><i class="fa fa-unlock"></i></a>';
					}

					$built_view_button = "<a class='btn btn-default btn-rounded show-tooltip' href='".$built_view_href."' title='Edit' data-original-title='Edit'><i class='fa fa-pencil-square-o' ></i></a>";

					$built_delete_button = "<a class='btn btn-default btn-rounded show-tooltip' title='Delete' href='".$built_bank_details_href."'  data-original-title='View Bank Details' onclick='return confirm_action(this,event,\"Do you really want to delete this record ?\")' ><i class='fa fa-trash-o' ></i></a>";

					$action_button = $built_view_button.' '.$build_status_btn.'  '.$built_delete_button;


					$build_result->data[$key]->id                  = $id;				
					$build_result->data[$key]->title               = isset($data->title)? $data->title :'';
					$build_result->data[$key]->subject             = isset($data->subject)? $data->subject :'';
					$build_result->data[$key]->created_at          = isset($data->created_at)? get_formated_date($data->created_at) :'';
					$build_result->data[$key]->build_action_btn	   = $action_button;
				}
			}
			return response()->json($build_result);
		}
	}

	public function edit($enc_id)
	{
		$id = base64_decode($enc_id);

		$arr_data = [];

		$obj_newsletter_template = new NewsLetterTemplateModel();

		$obj_newsletter_template = $obj_newsletter_template
													->where('id',$id)
													->first();
							
		if($obj_newsletter_template)
		{
			$arr_data = $obj_newsletter_template->toArray();	
		}

		$this->arr_view_data['page_title']          = "Edit ".str_singular($this->module_title);
		$this->arr_view_data['module_title']        = "Manage ".$this->module_title." Templates";
		$this->arr_view_data['module_icon']         = $this->module_icon;
		$this->arr_view_data['parent_module_title'] = "Dashboard";
		$this->arr_view_data['parent_module_icon']  = "fa-home";
		$this->arr_view_data['admin_panel_slug']    = $this->admin_panel_slug;
		$this->arr_view_data['parent_module_url']   = $this->admin_url_path.'/dashboard';
		$this->arr_view_data['module_url']          = $this->module_url_path;
		$this->arr_view_data['sub_module_title']    = 'Edit '.$this->module_title." Template";
		$this->arr_view_data['sub_module_icon']     = 'fa fa-pencil-square-o';
		$this->arr_view_data['module_icon']     	= $this->module_icon;
		$this->arr_view_data['module_url_path']     = $this->module_url_path;
		$this->arr_view_data['arr_news_letter']     = $arr_data;
		$this->arr_view_data['enc_id']              = $enc_id;


		return view($this->module_view_folder.'.edit',$this->arr_view_data);
	}

	public function update(Request $request, $enc_id)
    {
        $id   	   = base64_decode($enc_id);
        $arr_rules = array();
        $status    = FALSE;
        
        $arr_rules['title']         = "required";
        $arr_rules['subject']       = "required";
        $arr_rules['description']   = "required";     
        
        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $form_data = array();
        $form_data = $request->all(); 
        $arr_data = array();
        
        $arr_data = [ 'title'            =>  $form_data['title'],
                      'subject'          =>  $form_data['subject'],
                      'news_description' =>  $form_data['description'],
                    ];
		
		$obj_newsletter_template = new NewsLetterTemplateModel();

        $status = $obj_newsletter_template->where('id',$id)->update($arr_data);
        if ($status) 
        {
        	Session::flash('success',$this->module_title.' Template updated successfully.');   
        	return redirect($this->module_url_path);
        }
        else
        {
        	Session::flash('error','Error while updating '.$this->module_title.' Template.');    
        }
        return redirect($this->module_url_path);
    }

    public function send_email(Request $request)
    { 
        $arr_rules = array();
        $arr_rules['news_letter'] = "required";
        $validator = Validator::make($request->all(),$arr_rules);
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $news_letter_id = base64_decode($request->input('news_letter'));

        $checked_record = $request->input('checked_record');

        if(isset($checked_record) && sizeof($checked_record)>0)
        {   
        	$obj_newsletter_template = new NewsLetterTemplateModel();

            $newsletter_details = $obj_newsletter_template->where('id',$news_letter_id)->first();
            
            if (isset($newsletter_details) && $newsletter_details!=FALSE)
            {
                $newsletter_details = $newsletter_details->toArray();  
                
                
                foreach ($checked_record as $key => $email_id) 
                {  
                  $project_name = config('app.project.name');
                  $mail_subject = isset($newsletter_details['subject'])?$newsletter_details['subject']:'';
                  $mail_title   = isset($newsletter_details['title'])?$newsletter_details['title']:'';
                  $mail_message = isset($newsletter_details['news_description'])?html_entity_decode($newsletter_details['news_description']):'';
                  $mail_form    = isset($admin_email)?$admin_email->site_email_address:'jait@webwingtechnologies.com';
                          
                   $arr_built_content   = [
                   							'TITLE'           => $mail_title,
                                            'MESSAGE'        => $mail_message,
                                            'SUBJECT'        => $mail_subject,
                                            'PROJECT_NAME'     => config('app.project.name')
                                          ];
                     
                    if($arr_built_content)
                    {   
                        $arr_mail_data                         = [];
                        $arr_mail_data['email_template_id']    = '17';
                        $arr_mail_data['arr_built_content']    = $arr_built_content;
                        $arr_mail_data['user']                 = array('email'=> $email_id);
                        $arr_mail_data['attachment']           = $newsletter_details['news_description'];
                        $arr_mail_data['mail_type']            = 'newsletter';  
                    }

                    try
                    {    
                		$obj_email_service  = new MailService();
                		$email_status  		= $obj_email_service->send_mail_newsletter($arr_mail_data,$arr_built_content);
                    	Session::flash('success','Newsletter sent successfully.');
                    }
                    catch(\Exception $e)
                    {
                    	dd($e);
						Session::flash('error','Newsletter not sent please try again.');                    	                       
                    }
                }
            }
        }
        else
        {
        	Session::flash('error','Please select subscriber to send newsletter.');    
            return redirect()->back();
        }
        return redirect()->back();        
    }

	public function activate($enc_id = FALSE)
	{
		$id = base64_decode($enc_id);
		
		$success = $this->Template->where('id',$id)->update(['status'=>'1']);

		if($success)
		{
  			Session::flash('success','NewsLetter Template activated successfully');
  			return redirect()->back();
		}
		else
		{
			Session::flash('error','Something went wrong');
  			return redirect()->back();
		}


	}
	
	public function deactivate($enc_id = FALSE)
	{
		$id = base64_decode($enc_id);
		
		$success = $this->Template->where('id',$id)->update(['status'=>'0']);

		if($success)
		{
  			Session::flash('success','NewsLetter Template deactivated successfully');
  			return redirect()->back();
		}
		else
		{
			Session::flash('error','Something went wrong');
  			return redirect()->back();
		}
	}
	
	public function delete($enc_id = FALSE)
	{
		$id = base64_decode($enc_id);
		
		$success = $this->Template->where('id',$id)->delete();

		if($success)
		{
  			Session::flash('success','NewsLetter Template delete successfully');
  			return redirect()->back();
		}
		else
		{
			Session::flash('error','Something went wrong');
  			return redirect()->back();
		}
	}


}
