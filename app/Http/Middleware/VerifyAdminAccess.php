<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Http\Request;
use App\Models\Login;

class VerifyAdminAccess
{
    
    public function handle(Request $request, Closure $next)
    {
        $user=auth()->user();
        if($user->usertype == '1'){
            return $next($request);
        }
        return response()->json([
            'message'=>'Access Forbidden'
        ], 403);
      
    }

}
