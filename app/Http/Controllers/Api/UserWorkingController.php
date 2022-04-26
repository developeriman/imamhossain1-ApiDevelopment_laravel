<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserWorkingScreenshot;
use App\Http\Requests\UserWorkingScreenshotRequest;
use App\Models\UserWorkingRecord;
use App\Http\Requests\UserWorkingRecordRequest;
use App\Models\User;
use App\Models\Login;
use App\Models\TaskTime;
use DB;

class UserWorkingController extends Controller
{
    public function fetchScreenshot(Request $req){
        $data = DB::table('tbl_user_working_screenshot')
                ->join('tbl_users','tbl_user_working_screenshot.user_id','=','tbl_users.id')
                ->select('tbl_user_working_screenshot.*','tbl_users.username','tbl_users.usertype')
                ->get();
        if(!$data->isEmpty()){
            return response()->json([
                'success'=>true,
                'data' => $data
            ]);
        }else{
            return response()->json([
                'message' => "No data found."
            ]);
        }
    }


    public function userWorkingScreenshotStore(UserWorkingScreenshotRequest $req)
    {
        $user_id = $req->user_id;
        $screenshot = 'screenshot/user_id/'.date('Y-m-d-H-i-s').'_'.$req->screenshot->getClientOriginalName();
        $req->screenshot->move(public_path('screenshot/user_id'), $screenshot);
    
        UserWorkingScreenshot::create([
            'user_id' => $req->user_id,
            'screenshot' => $screenshot
        ]);
        return response()->json([
            'success'=>true,
            'message'=>'Image upload successfully.'
        ]);
    }

    public function userWorkingScreenshotDelete(Request $req, $id){
        UserWorkingScreenshot::destroy($id);
        return response()->json([
            'message'=>'Screenshot delete successfully.'
        ]);
    }

    public function indexUserWorkingRecord(Request $req){
        $data = DB::table('tbl_user_working_record')
                ->join('tbl_users','tbl_user_working_record.user_id','=','tbl_users.id')
                ->select('tbl_user_working_record.*','tbl_users.first_name','tbl_users.last_name','tbl_users.usertype')
                ->get();
        if(!$data->isEmpty()){
            return response()->json([
                'success'=>true,
                'data' => $data
            ]);
        }else{
            return response()->json([
                'message'=> "No data found."
            ]);
        }
    }


    public function storeUserWorkingRecord(UserWorkingRecordRequest $req){
        DB::beginTransaction();
        try{
                $data = new UserWorkingRecord();
                $data->user_id = $req->user_id;
                $data->time_type = $req->time_type;
                $data->start_time = $req->start_time;
                $data->end_time = $req->end_time;
                $data->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Record saved successfully.'
            ],200);
        }catch(Exception $e){
            DB::rollback();
            return $e;
        }
        
    }

    public function deleteUserWorkingRecord(Request $req, $id){
        UserWorkingRecord::destroy($id);
        return response()->json([
            'message'=>'Record delete successfully.'
        ]);
    }
}
