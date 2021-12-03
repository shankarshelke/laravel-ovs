<?php
namespace App\Http\Middleware\Admin;

use Closure;

class CheckAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    { 
        $this->auth = auth()->guard('admin');
        if($this->auth->user())
        {
            return redirect(url('/admin/account_setting')); 
        }
        return $next($request);
    }
}
