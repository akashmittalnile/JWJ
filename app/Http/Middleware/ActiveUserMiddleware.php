<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActiveUserMiddleware
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
            if(auth()->user()->status == 1){
                return $next($request);
            }else{
                User::where('id', auth()->user()->id)->update([
                    'fcm_token' => null
                ]);
                Auth::user()->tokens()->delete();
                return successMsg('Something went wrong please login again');
            }
        }else{
            return redirect('/admin')->with('error', 'Access Denied!');
        }
    }
}
