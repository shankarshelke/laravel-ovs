<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\NotificationModel;
use App\Models\SiteSettingModel;
use App\Models\ContactEnquiryModel;


class AuthMiddleware
{
    function __construct()
    {  
        $this->NotificationModel    = new NotificationModel();
        $this->ContactEnquiryModel  = new ContactEnquiryModel();
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $arr_site_data = array();

        $obj_site_data = SiteSettingModel::first();

        if($obj_site_data){ $arr_site_data = $obj_site_data->toArray(); }

        view()->share('arr_global_site_setting',$arr_site_data);

        $this->auth = auth()->guard('admin');

        view()->share('admin_panel_slug',config('app.project.admin_panel_slug'));

        if($this->auth->user())
        {
            $super_admin_details = $this->auth->user()->toArray();

            $obj_user = $this->auth->user();

            $contact_enquiries = $this->ContactEnquiryModel->where(['status'=>'0'])
                                                           ->orderBy('created_at','desc')
                                                           ->limit(5)
                                                           ->get()
                                                           ->toArray();

            $contact_enquiries_count = $this->ContactEnquiryModel->where(['status'=>'0'])->count();                                         

            $notifications = $this->NotificationModel->with(['get_user_details'])
                                                     ->where(['receiver_id'=>'1', 'status'=>'0', 'receiver_type'=>'admin'])
                                                     ->orderBy('created_at','desc')
                                                     ->limit(5)
                                                     ->get()
                                                     ->toArray();


            $notification_count = $this->NotificationModel->where(['receiver_id'=>'1', 'status'=>'0', 'receiver_type'=>'admin'])->count();

            view()->share('id',$super_admin_details['id']);

            view()->share('shared_admin_details',$super_admin_details);

            view()->share('profile_image_base_img_path',base_path().config('app.project.img_path.admin_profile_image'));
            
            view()->share('profile_image_public_img_path',url('/').config('app.project.img_path.admin_profile_image'));
            
            view()->share('default_img_path',url('/').config('app.project.img_path.user_default_img_path'));

            view()->share('notifications',$notifications);

            view()->share('notification_count',$notification_count);
            
            view()->share('contact_enquiries',$contact_enquiries);

            view()->share('contact_enquiries_count',$contact_enquiries_count);

            view()->share('obj_user',$obj_user);

            return $next($request);
        }
        else
        {
        	$this->auth->logout();
            return redirect(config('app.project.admin_panel_slug'));
        }
    }
}