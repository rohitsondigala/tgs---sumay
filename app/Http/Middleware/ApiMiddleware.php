<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;

class ApiMiddleware
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
        $userArray = ['PROFESSOR','STUDENT'];
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if(!in_array($user->role->title,$userArray)){
                return response()->json(['success' => false, 'message' => trans('Invalid user type'), 'data' => array()]);
            }
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['success' => false, 'message' => trans('Token is invalid'), 'data' => array()]);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['success' => false, 'message' => trans('Token is expired'), 'data' => array()]);
            }else{
                return response()->json(['success' => false, 'message' => trans('Authorization token not found '), 'data' => array()]);
            }
        }
        return $next($request);
    }
}
