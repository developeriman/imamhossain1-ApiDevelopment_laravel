<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Guard;
use App\Models\User;
use App\Models\Login;
use App\Models\TaskTime;
use DB;

class UserController extends Controller
{
    # Get User All Task
    public function UserTask($id){
        $data = DB::table('tbl_task')->join('tbl_task_to_user','tbl_task.id','=','tbl_task_to_user.task_id')
                ->where('tbl_task_to_user.user_id',$id)
                ->select('tbl_task.id','tbl_task.task_title')
                ->distinct()
                ->get();
        if(!$data->isEmpty()){
            return response()->json([
                'data' => $data
            ]);
        }else{
            return response()->json([
                'message' => "No data found"
            ]);
        }
        
    }
    
    public function getAllUser(Request $req){
        $user = User::paginate(20);
        return response()->json([
            'success'=>true,
            'user'=>$user
        ],200);
    }


    public function changeStatus(Request $req){
            DB::beginTransaction();
            try{
                User::where('id',$req->user_id)->update(['status'=>$req->status]);
                DB::commit();
                return response()->json([
                    'success'=> true,
                    'message' => "User status change successfully"
                ]);
            }catch( Exception $e){
                DB::rollback();
                return $e;
            }
    }


    public function changePassword(ChangePasswordRequest $req){
        DB::beginTransaction();
        try{
            $user = User::where('id',$req->user_id)->first();
            if(Hash::check($req->old_password, $user->password)){
                User::where('id',$user->id)->update([
                    'password'=>bcrypt($req->password)
                ]);
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => "User password change successfully"
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Incorrect old password'
                ]);
            }
        }catch(Exception $e){
            DB::rollback();
            return $e;
        }
    }


    public function guard(){
        return Auth::guard();
    }
}
