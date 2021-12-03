<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BlogsModel;
use App\Common\Traits\MultiActionTrait;

use Validator;
use Session;
use DataTables;


class BlogsController extends Controller
{
	use MultiActionTrait;
    function __construct(BlogsModel $blogs
					    )
    {  	
		$this->arr_view_data                = [];
		$this->admin_panel_slug             = config('app.project.admin_panel_slug');
		$this->admin_url_path               = url(config('app.project.admin_panel_slug'));
		$this->module_url_path              = $this->admin_url_path."/blogs";
		$this->module_title                 = "Blogs";
		$this->module_view_folder           = "admin.blogs";
		$this->module_icon                  = "fa fa-comments-o";
		$this->ip_address                   = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:false;  
		$this->blog_image_base_path         = base_path().config('app.project.img_path.blogs_image');
		$this->blog_image_public_path 		= url('/').config('app.project.img_path.blogs_image');
		$this->BaseModel					= new BlogsModel();
    }

    public function index(Request $request,$type=null)
    {
    	
		$this->arr_view_data['page_title']           = "Manage ".$this->module_title;
        $this->arr_view_data['parent_module_icon']   = "fa-home";
        $this->arr_view_data['parent_module_title']  = "Dashboard";
        $this->arr_view_data['parent_module_url']    = url('/').'/admin/dashboard';
        $this->arr_view_data['module_icon']          = $this->module_icon;
        $this->arr_view_data['module_title']         = "Manage ".$this->module_title;
		$this->arr_view_data['module_url_path']      = $this->module_url_path;
		$this->arr_view_data['admin_panel_slug']     = $this->admin_panel_slug;
		$this->arr_view_data['type']     			 = $type;
		return view($this->module_view_folder.'.index',$this->arr_view_data);
    }
    public function load_data(Request $request,$type=null)
    {
    	$obj_blogs  = $build_status_btn = $build_otp_btn = "";
		$arr_search_column      = $request->input('column_filter');

		
		$obj_blogs               = $this->BaseModel;
		
		if(isset($arr_search_column['q_status']) && $arr_search_column['q_status']!="")
		{
			$obj_blogs           = $obj_blogs->where('status', 'LIKE', "%".$arr_search_column['q_status']."%");	
		}
		if(isset($arr_search_column['q_title']) && $arr_search_column['q_title']!="")
		{
			$obj_blogs = $obj_blogs->where('title', 'LIKE', "%".$arr_search_column['q_title']."%");
		}
		if(isset($arr_search_column['q_description']) && $arr_search_column['q_description']!="")
		{
			$obj_blogs = $obj_blogs->where('description', 'LIKE', "%".$arr_search_column['q_description']."%");
		}

		

		$obj_blogs               = $obj_blogs->orderBy('created_at','desc');
		if($obj_blogs)
		{
			$json_result  = DataTables::of($obj_blogs)->make(true);
			$build_result = $json_result->getData();

			foreach ($build_result->data as $key => $data) 
			{
				if(isset($build_result->data) && sizeof($build_result->data)>0)
				{
					$built_edit_href = $this->module_url_path.'/edit/'.base64_encode($data->id);
					$built_delete_href 	= $this->module_url_path.'/delete/'.base64_encode($data->id);
					
					if($data->status != null && $data->status == "0")
					{   
						$build_status_btn = '<a class="label label-danger label-mini" title="Deactive" href="'.$this->module_url_path.'/unblock/'.base64_encode($data->id).'" 
						onclick="return confirm_action(this,event,\'Do you really want to activate this record ?\')" >Inactive</a>';
					}
					elseif($data->status != null && $data->status == "1")
					{
						$build_status_btn = '<a class="label label-success label-mini" title="Active" href="'.$this->module_url_path.'/block/'.base64_encode($data->id).'" onclick="return confirm_action(this,event,\'Do you really want to inactivate this record ?\')" >Active</a>';
					}
					$built_delete_button = "<a class='btn btn-default btn-sm' href='".$built_delete_href."' title='Delete' onclick='return confirm_action(this,event,\"Do you really want to delete this record ?\")'><i class='fa fa-trash-o' ></i></a>";
					$built_edit_button = "<a class='btn btn-default btn-sm' href='".$built_edit_href."' title='Edit' ><i class='fa fa-pencil-square-o' ></i></a>";

					$id         		 = isset($data->id)? base64_encode($data->id) :'';
					$build_action_btn 	 = $built_edit_button.$built_delete_button;
					$title 			  	 = isset($data->title)? $data->title :'';
					$short_description 	 = isset($data->short_description)?$data->short_description:'';
					$status    			 = isset($data->status)? $data->status :'';
					$created_at          = isset($data->created_at)?$data->created_at:'';

					$build_result->data[$key]->id       		   = $id;				
					$build_result->data[$key]->title         	   = $title;				
					$build_result->data[$key]->description         = $short_description;	
					$build_result->data[$key]->build_status_check  = $build_status_btn;
					$build_result->data[$key]->created_at          = get_formated_date($created_at);
					$build_result->data[$key]->build_action_btn    = $build_action_btn;
					$build_result->data[$key]->build_status_btn    = $build_status_btn;

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
		$this->arr_view_data['page_title']       = 'Create '.str_singular($this->module_title);
		$this->arr_view_data['page_icon']        = $this->module_icon;
		$this->arr_view_data['module_title']     = 'Manage '.$this->module_title;
		$this->arr_view_data['sub_module_title'] = 'Create '.$this->module_title;
		$this->arr_view_data['sub_module_icon']  = 'fa fa-plus';
		$this->arr_view_data['module_icon']      = $this->module_icon;
		$this->arr_view_data['admin_panel_slug'] = $this->admin_panel_slug;
		$this->arr_view_data['module_url_path']  = $this->module_url_path;
		$this->arr_view_data['module_url']       = $this->module_url_path;
		return view($this->module_view_folder.'.create',$this->arr_view_data);
	}

	public function store(Request $request)
	{
		$arr_rules      = $arr_data = array();
		$status         = false;

		$arr_rules['_token']				= "required";
		$arr_rules['title']      	   		= "required";
		$arr_rules['description']      		= "required";
		$arr_rules['short_description']     = "required";

		$validator = validator::make($request->all(),$arr_rules);

		if($validator->fails()) 
		{
			return redirect()->back()->withErrors($validator)->withInput();
		}
		$name = $request->input('name', null);

		$arr_data['image']    			=   $request->input('_token', null);	
		$arr_data['title']				=	$request->input('title', null);	
		$arr_data['description']		=	$request->input('description', null);	
		$arr_data['short_description']	=	$request->input('short_description', null);	

		if($request->hasFile('image'))
		{         
			$file_extension = strtolower($request->file('image')->getClientOriginalExtension());

			if(in_array($file_extension,['png','jpg','jpeg']))
			{
				$file     = $request->file('image');
				$filename = sha1(uniqid().uniqid()) . '.' . $file->getClientOriginalExtension();
				$path     = $this->blog_image_base_path . $filename;
				$isUpload = $file->move($this->blog_image_base_path , $filename);
				if($isUpload)
				{
					$arr_data['image'] = $filename;
				}
			}    
			else
			{
				Session::flash('error','Invalid File type, While creating '.str_singular($this->module_title));
				return redirect()->back();
			}
		}

		$status = $this->BaseModel->create($arr_data);

		if($status)
		{
			Session::flash('success', str_singular($this->module_title).' created successfully.');
			return redirect($this->module_url_path);
		}
		Session::flash('error', 'Error while creating '.str_singular($this->module_title).'.');
		return redirect($this->module_url_path);
	}
    public function edit($enc_id='')
	{
		if($enc_id=='')
		{
			return redirect()->back();
		}

		$obj_data = $this->BaseModel->where('id', base64_decode($enc_id))->first();
		$arr_data = [];
		if($obj_data)
		{
			$arr_data = $obj_data->toArray();
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
		$this->arr_view_data['blog_image_base_path']    = $this->blog_image_base_path;
		$this->arr_view_data['blog_image_public_path']  = $this->blog_image_public_path;
		$this->arr_view_data['admin_panel_slug']        = $this->admin_panel_slug;
		$this->arr_view_data['module_url_path']         = $this->module_url_path;
		$this->arr_view_data['arr_data']                = $arr_data;
		$this->arr_view_data['enc_id']                  = $enc_id;

		return view($this->module_view_folder.'.edit',$this->arr_view_data);
	}

	public function update(Request $request, $enc_id='')
	{

		$arr_rules      = $arr_data  = array();
		$status         = false;

		
		$arr_rules['_token']      	   			= "required";
		$arr_rules['title']      	   			= "required";
		$arr_rules['description']      			= "required";
		$arr_rules['short_description']     	= "required";

		$validator = validator::make($request->all(),$arr_rules);

		if ($validator->fails()) 
		{
			return redirect()->back()->withErrors($validator)->withInput();
		}

		$arr_data['title']				=	$request->input('title', null);	
		$arr_data['short_description']	=	$request->input('short_description', null);	
		$arr_data['description']		=	htmlentities($request->input('description'));	
		if($request->hasFile('image'))
		{
			$file_extension = strtolower($request->file('image')->getClientOriginalExtension());

			if(in_array($file_extension,['png','jpg','jpeg']))
			{
				$file     = $request->file('image');
				$filename = sha1(uniqid().uniqid()) . '.' . $file->getClientOriginalExtension();
				$path     = $this->blog_image_base_path . $filename;
				$isUpload = $file->move($this->blog_image_base_path, $filename);

				if($isUpload)
				{
					$arr_data['image'] = $filename;
					/*unlink the prevoius banner image*/
					$obj_data = $this->BaseModel->where('id', base64_decode($enc_id))->first();
					if(isset($obj_data->image) && $obj_data->image!=''){
						@unlink($this->blog_image_base_path.$obj_data->image);
					}
				}
			}    
			else
			{
				Session::flash('error','Invalid File type, While creating '.str_singular($this->module_title));
				return redirect()->back();
			}
		}
	
		$status = $this->BaseModel->where('id', base64_decode($enc_id))->update($arr_data);

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
  			Session::flash('success','Blog deleted successfully');
  			return redirect()->back();
		}
		else
		{
			Session::flash('error','Something went wrong');
  			return redirect()->back();
		}
	}

   
}
