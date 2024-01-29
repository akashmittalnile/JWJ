<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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
        if(Auth::check())
        {
            if(Auth::user()->role == config("constant.role.Admin")){
                return $next($request);
            }else{
                Auth::logout();
                return redirect('/admin')->with('error', 'Access Denied!');
            }
        }else{
            return redirect('/admin')->with('error', 'Access Denied!');
        }
    }
}
