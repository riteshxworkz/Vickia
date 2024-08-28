<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class UserSessionHandler
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
        $response = $next($request);
        if(Auth::check() && Auth::user()->group != "admin") {
            if(Auth::user()->last_session != session()->getId()) {
                Auth::logout();
           }
        }
       return $response;
    }
}
