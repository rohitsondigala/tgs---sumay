<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
        if(auth()->check()){
           if(user_role() == 'ADMIN'){
                return $next($request);
            }else{
                return route('logout');
            }
        }else{
            return route('logout');
        }
    }
}
