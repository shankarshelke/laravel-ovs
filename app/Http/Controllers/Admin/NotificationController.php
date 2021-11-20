<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Common\Traits\MultiActionTrait;
use App\Models\NotificationModel;
use DataTables;

class NotificationController extends Controller
{
	use MultiActionTrait;
	function __construct()
	{
		$this->BaseModel = new NotificationModel();

		$this->arr_view_data      = [];
		$this->admin_panel_slug   = 'admin';
		$this->admin_url_path     = url(config('app.project.admin_panel_slug'));
		$this->module_url_path    = $this->admin_url_path.'/notification';
		$this->module_title       = "Notifications";
		$this->module_view_folder = "admin.notification";
		$this->module_icon        = "fa fa-bell";
		$this->auth               = auth()->guard('admin');
		$this->ip_address         = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:false;
	}

	public function index()
	{
		$this->arr_view_data['parent_module_icon']   = "fa-home";
		$this->arr_view_data['parent_module_title']  = "Dashboard";
		$this->arr_view_data['parent_module_url']    = $this->admin_url_path.'/dashboard';
		$this->arr_view_data['page_title']       = 'Manage '.$this->module_title;
		$this->arr_view_data['module_title']     = 'Manage '.$this->module_title;
		$this->arr_view_data['page_icon']        = $this->module_icon;
		$this->arr_view_data['module_icon']      = $this->module_icon;
		$this->arr_view_data['admin_panel_slug'] = $this->admin_panel_slug;
		$this->arr_view_data['module_url_path']  = $this->module_url_path;

		return view($this->module_view_folder.'.index',$this->arr_view_data);
	}

	public function get_count()
	{
		$str = '';
		$notification_count      = $this->BaseModel->where(['to_type'=>'1', 'is_read'=>'0', 'to_id'=>'0'])->count();
		$obj_notification       = $this->BaseModel->with(['user'=>function($q){
			$q->select(['id','profile_image', 'first_name', 'last_name']);
		}])->where(['to_type'=>'1', 'to_id'=>'0'])->orderBy('id', 'desc')->take('5')->get(['from_id', 'name','url','created_at']);

		$arr_notification = [];

		if($obj_notification)
		{
			$arr_notification = $obj_notification->toArray();
		}

		foreach($arr_notification as $notification)
		{
			$user_name         	 = isset($notification['user']['first_name'])? $notification['user']['first_name'].' ':'';
			$user_name			.= isset($notification['user']['last_name'])? $notification['user']['last_name'].' ':'';
			$notification_time 	 = isset($notification['created_at'])? get_notification_date_date($notification['created_at']):'';
			$url 	 = isset($notification['url'])? url($notification['url']):'javascript:void(0)';

			$notification_name = isset($notification['name'])? str_limit($notification['name'],25):'';

			if(isset($notification['user']['profile_image']))
			{
				$notification_user_image = $notification['user']['profile_image'];
			}
			else
			{
				$notification_user_image = url('front/images/avtar.png');
			}

			$str.='<li class="media"><div class="media-left"><img src="'.$notification_user_image.'" class="img-circle img-sm" alt=""></div><div class="media-body"><a href="'.$url.'" class="media-heading"><span class="text-semibold">'. str_limit(title_case($user_name),15) .'</span><span class="media-annotation pull-right">'. $notification_time .'</span></a><span class="text-muted">'. $notification_name .'</span></div></li>';
		}

		$arr_data['status'] = 'success';
		$arr_data['str']    = $str;
		$arr_data['count']    = $notification_count;
		return response()->json($arr_data);

	}

	public function load_data(Request $request)
	{
    /* Search  */
    $notification = '';
    if(\Request::has('type') && \Request::get('type')=='transaction')
    {
        $notification = \Request::get('type');
        //$x = Request::has('transaction');
    }
    else if(\Request::has('type')&& \Request::get('type')=='reservation')
    {
        $notification = \Request::get('type');
    }
    else if(\Request::has('type')&& \Request::get('type')=='general')
    {
        $notification = \Request::get('type');
    }
    /* Search  */
		$obj_data     = $this->BaseModel->where([/*'receiver_id'=>'0',*/'receiver_type'=>'admin']);	

	    if($notification!='' && isset($notification) && $notification=='general')
	    {
	        $obj_data = $obj_data->where('notification_type',$notification);
	    }
	    elseif($notification!='' && isset($notification) && $notification=='transaction')
	    {
	        $obj_data = $obj_data->where('notification_type',$notification);
	    }
	    elseif($notification!='' && isset($notification) && $notification == 'reservation')
	    {
	        $obj_data = $obj_data->where('notification_type',$notification);
	    }	
		$obj_data     = $obj_data->orderBy('created_at', 'DESC');
		$json_result  = DataTables::of($obj_data)->make(true);
		$build_result = $json_result->getData();
		$update       = $this->BaseModel->where(['receiver_id'=>'1','receiver_type'=>'admin'])
										->update(['status'=>'1']);
		if(isset($build_result->data) && sizeof($build_result->data)>0)
		{   
			foreach ($build_result->data as $key => $data) 
			{
				$notification_title   = isset($data->title)? $data->title :'NA';
				$arrived_on           = isset($data->created_at)? $data->created_at :'';
				$url                  = isset($data->redirect_url)? url($data->redirect_url) :'javascript:void(0)';
				$notification_message = isset($data->description)? '<a  href="'.$url.'">'. $data->description.'</a>' :'NA';

				$build_action_btn = '<a class="btn btn-default btn-rounded show-tooltip" title="View" href="'.$url.'"><i class="fa fa-eye" aria-hidden="true"></i></a>';
				$id = isset($data->id)? base64_encode($data->id) :'';

				$build_result->data[$key]->id       		= $id;
				$build_result->data[$key]->arrived_on       = get_formated_date($arrived_on);
				$build_result->data[$key]->notification_message     = $notification_message;
				
				$build_result->data[$key]->notification_title = $notification_title;

			}
		}
		return response()->json($build_result);
	}

}
