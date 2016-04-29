<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use JWTAuth;

class JWT
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
        try {
            $token = JWTAuth::getToken();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['error'=>'Token is Invalid']);
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                $token = JWTAuth::refresh($token);
                return response()->json(['result'=>$token]);
            } else {
                return response()->json(['error'=>'Something is wrong']);
            }
        }
        return $next($request);
    }
}
