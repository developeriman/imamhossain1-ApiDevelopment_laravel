<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Mail\ForgetPasswordMail;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use DB;
use Mail;

class ForgotPasswordController extends Controller
{

    public function forgetPassword(ForgetPasswordRequest $req){
        $randToken = str::random(10);
        $details = [
            'subject' => 'Forget Password Token',
            'token' => $randToken,
        ];
        DB::table('tbl_forget_password')->insert([
            'email' => $req->email,
            'token' => $randToken,
            'created_at' => Carbon::now()
        ]);
        Mail::to($req->email)->send(new ForgetPasswordMail($details));
        return response()->json([
            'success'=>true,
            'message'=> 'We have e-mailed your password reset token!'
        ]); 
    }
    public function resetPassword(ResetPasswordRequest $req){
        $updatedPassword=DB::table('tbl_forget_password')
                        ->where([
                            'email'=>$req->email,
                            'token'=>$req->token
                        ])->first();
        if($updatedPassword){
            $user = User::where('email', $req->email)->update([
                'password' => Hash::make($req->password)
            ]);
            DB::table('tbl_forget_password')->where('email',$req->email)->delete();
            return response()->json([
                'success' => true,
                'message' => "User password changed successfully"
            ]);
        }else{
            return response()->json([
                'success' => False,
                'message' => "Email doesn't match"
            ]);
        }
    }
}
