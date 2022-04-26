<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Guard;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Models\Login;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request){

        // check user active inactive status
        $userInfo = User::where(function ($query) use ($request){
            $query->where(['email' => $request->email]);
        })->first();
        if(! $userInfo){
            return response()->json([
                'success' => false,
                'message' => 'Invalid username or Password',
            ]);
        }
        if($userInfo->status != 1){
            return response()->json([
                'success' => false,
                'message'=> 'Inactive user.'
            ]);
        }
        
        if($userInfo){
            if(Hash::check($request->password,$userInfo->password)){
                $credential = $request->only('email','password');
                $token = $this->guard()->attempt($credential);
                Login::create([
                    'user_id' => $userInfo->id,
                    'auth_token'=>$token
                ]);
                return response()->json([
                    'success' => true,
                    'token' => $token,
                    'data'=> $this->guard()->user(),
                    'message' => 'Successfully logged in.',
                ],200);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid username or Password',
                ]);
            }
        }
    }

    
    public function me()
    {
        return response()->json($this->guard()->user());
    }

    
    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    
    public function guard()
    {
        return Auth::guard();
    }
}
