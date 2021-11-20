<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\NotificationModel;
use App\Models\SiteSettingModel;
use App\Models\ContactEnquiryModel;


class CheckSubmoduleAccess
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

        $auth = \Auth::guard('admin');

        $permissions = $auth->user()->permissions;
        
        $arr_permissions = unserialize($permissions);
        
        // dd($arr_permissions);        
        if($auth->user()->admin_type == 'SUPERADMIN')
        {    
            
            return $next($request);
        }
        else
        {
            $route_chunks = [];
            // dd($request->path());
            $path = $request->path();
            $route_chunks = explode('/',$path);
            // dd($route_chunks);
            
            $modules = $route_chunks[count($route_chunks)-2];
            $submodule =  end($route_chunks);
            
            if($submodule != '' && $modules != '')
            {
                if($auth->user()->admin_type == 'SUBADMIN'){

                    if(isset($arr_permissions) && $arr_permissions != '')
                    {
                    
                        if(array_key_exists($modules, $arr_permissions) && in_array($submodule, $arr_permissions[$modules])){
                            return $next($request); 
                        }
                        else{
                            return response()->view('errors.403');
                        }

                    }
                }
            }

        }
    }
}