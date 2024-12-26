<?php

namespace App\Http\Middleware;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class EmployeeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Sentinel::check()) {
            if(Sentinel::inRole('employee') || Sentinel::inRole('admin')) {
                return $next($request);
            }
            $route_name = request()->route()->getName();
            if ( Sentinel::hasAccess($route_name)) {
                return $next($request);
            }
            if($request->ajax() == true) {
                return response(['status' => false, 'msg' => __('auth.unauthorized_to_access_the_page').$route_name]);
            }
            Flash::error(__('auth.unauthorized_to_access_the_page'));
            return redirect()->route('employee.dashboard');
        }
        return redirect(route('login'));
    }
}
