<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
    public function register(RegisterRequest $req){
        try{
            $user = User::create([
                'first_name' => $req->first_name,
                'last_name' => $req->last_name,
                'username' => $req->username,
                'email' => $req->email,
                'password' => Hash::make($req->password),
                'usertype' => 2,
                'status' => $req->status ? $req->status : 1
            ]);
            return response()->json([
                'success' => true,
                'message' => 'User create successfully',
                'user' => $user
            ],200);
        }catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ],400);
        }
    }
}
