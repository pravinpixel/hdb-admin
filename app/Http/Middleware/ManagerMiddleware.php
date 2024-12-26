<?php

namespace App\Http\Middleware;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class ManagerMiddleware
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
            if(Sentinel::inRole('manager') || Sentinel::inRole('admin')) {
                return $next($request);
            }
            if($request->ajax() == true) {
                return response(['status' => false, 'msg' => __('auth.unauthorized_to_access_the_page')]);
            }
            Flash::error(__('auth.unauthorized_to_access_the_page'));
            return redirect()->route('admin.dashboard');
        }
        return redirect(route('login'));
    }
}
