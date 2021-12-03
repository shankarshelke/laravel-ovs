<?php  
namespace App\Http\Middleware\Api;

use Closure;
use Request;
use Exception;


class UserAuthMiddleware
{    
    function __construct()
    {  
       $this->auth  = auth()->guard('api_admin');
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
        $arr_responce = [];
       $auth_token = $request->header('Authorization',null);
       if(isset($auth_token) && $auth_token != null)
       {
            if($this->auth->check())
            {
                $user = $this->auth->user();
                // dd($user->all());
                // dd($user->remember_token);
                
                if(isset($user) && $user != null && isset($user->id) && $user->id != null)
                {
                    $request->request->add(['webadmin_id'=>$user->id]);
                    //$request->request->add(['user'=>$user]);

                    if($user->status == '0')
                    {
                        $msg = 'Your account blocked by admin.';
                        return $this->build_response('error',$msg,[],'json',401);
                    }
                    else
                    {
                        session(['subadmin_id' => $user->id]);
                        return $next($request);
                    }
                }
                else
                {
                    $msg = 'Invalid user token';
                    return $this->build_response('error',$msg,[],'json',401);
                }
            }
       }
       $msg = 'Token could not be parsed from the request.';
       return $this->build_response('error',$msg,[],'json',401);


    }

    /*-------------------------------------------------------*/
    public function build_response( $status = 'success',
                                    $message = "",
                                    $arr_data = [],
                                    $response_format = 'json',
                                    $response_code = 200)
    {
        $arr_result=[];

        if($response_format == 'json')
        {
            $arr_result = [
                'status' => $status,
                'msg' => $message
            ];
            
            if(count($arr_data)>0)
            {
                $arr_result['response_data'] = $arr_data;
            }
            return response()->json($arr_result,$response_code,[],JSON_UNESCAPED_UNICODE);    
        }   
    }
}