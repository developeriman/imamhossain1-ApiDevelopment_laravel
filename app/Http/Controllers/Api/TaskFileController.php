<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Login;
use App\Models\TaskFile;
use App\Http\Requests\TaskFileRequest;
use DB;
use Carbon\Carbon;

class TaskFileController extends Controller
{
    public function index(Request $req){
        $data = DB::table('tbl_task_files')
            ->join("tbl_users","tbl_task_files.user_id","=","tbl_users.id")
            ->join("tbl_task","tbl_task_files.task_id","=","tbl_task.id")
            ->select('tbl_task_files.*','tbl_users.username','tbl_task.task_title')
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

    public function store(TaskFileRequest $req){
        $user = $req->user_id;
        $task_id = $req->task_id;
        $file_name = 'task/'.date('Y-m-d-H-i-s').'_'.$req->file->getClientOriginalName();
            $req->file->move(public_path('task'), $file_name);
        TaskFile::create([
            'task_id' => $task_id,
            'user_id' => $user,   
            'file' => $file_name,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'File upload successfully!'
        ]);
    }
    
    public function destroy($id){
        TaskFile::destroy($id);
        return response()->json([
            'success'=>true,
            'message'=>'Task file delete successfully'
        ]);
    }
}
