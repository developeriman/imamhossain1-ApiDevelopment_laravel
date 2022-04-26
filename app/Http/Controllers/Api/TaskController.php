<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\TaskStatus;
use App\Models\TaskToStatus;
use App\Models\TaskTime;
use App\Models\TaskComment;
use App\Models\Task; 
use App\Http\Requests\TaskRequest; 
use App\Http\Requests\TaskCommentRequest; 
use App\Models\TaskToProject;
use App\Models\ProjectToUser;
use App\Models\TaskToUser;
use App\Models\Login;
use App\Models\User;
use Carbon\Carbon;
class TaskController extends Controller
{

    //Get project according to user_id
    public function userToProject($id){
        $data = DB::table('tbl_users')->where('id','=',$id)->select('tbl_users.id','tbl_users.username')->first();
        if($data){
            foreach($data as $val){
                $projectsId=ProjectToUser::where('user_id',$val->id)->pluck('project_id');
                $project=DB::table('tbl_project')->select('tbl_project.*')->whereIn('id', $projectsId)->get();
                $val->projects=$project;
            }
            return response()->json([
                'data' => $data
            ]);
        }else{
            return response()->json([
                'message' => "No data found." 
            ]);
        }
       
    }

    // all task under a specific project
    public function indexProjectTask($id){
        $projectTask = [];
        $project = DB::table('tbl_project')->where('id',$id)->first();
        if($project){
            $taskToProject = DB::table('tbl_task_to_project')->where('project_id',$id)->pluck('task_id');
            foreach($taskToProject as $val){
                $task = DB::table('tbl_task')
                    ->where('id','=',$val)
                    ->get();
                foreach($task as $data){
                    $user = DB::table('tbl_task_to_user')
                        ->join('tbl_users','tbl_task_to_user.user_id','=','tbl_users.id')
                        ->where('tbl_task_to_user.task_id','=',$data->id)
                        ->select('tbl_users.id','tbl_users.first_name','tbl_users.first_name')
                        ->get();
                    $data->members=$user;
                }
                array_push($projectTask, $task);
            }
            return response()->json([
                'project' => $project,
                'task' => $projectTask
            ]);
        }else{
            return response()->json([
                'message' => "No data found"
            ]);
        }
    }

    //get all task of a specific user
    public function indexAllTask($id){
        $data = DB::table('tbl_task_to_user')
                ->join('tbl_task','tbl_task_to_user.task_id','=','tbl_task.id')
                ->where('tbl_task_to_user.user_id','=',$id)
                ->select('tbl_task.id','tbl_task.task_title')
                ->get();
        if(!$data->isEmpty()){
            return response()->json([
                'data' => $data
            ]);
        }else{
            return response()->json([
                'message' => "No data found."
            ]);
        }
        
    }
    
    //Task start here
    public function indexTask($id){
        $data = DB::table('tbl_task')->where('id',$id)->first();
        if($data){
            foreach($data as $val){
                $user = DB::table('tbl_task_to_user')->join('tbl_users','tbl_task_to_user.user_id','=','tbl_users.id')
                    ->where('tbl_task_to_user.task_id','=',$val->id)
                    ->select('tbl_users.id','tbl_users.first_name','tbl_users.last_name','tbl_users.username')->get();
                $val->members=$user;
            }
            return response()->json([
                'data' => $data
            ]);
        }else{
            return response()->json([
                'message' => "No data found."
            ]);
        }
        
    }

    public function storeTask(TaskRequest $request)
    {
        DB::beginTransaction();
        try{
            $data = new Task();
            $data->task_title = $request->task_title;
            $data->task_details = $request->task_details ? $request->task_details : '';
            $data->priority = $request->priority ? $request->priority : 1;
            $data->save();
            $project = new TaskToProject();
            $project->task_id=$data->id;
            $project->project_id=$request->project_id;
            $project->save();
            if($request->members && is_array($request->members)){
                foreach($request->members as $val){
                    $taskToUser = new TaskToUser();
                    $taskToUser->user_id = $val; 
                    $taskToUser->task_id = $data->id; 
                    $taskToUser->save();
                }
            }
            DB::commit();
            return response()->json([
                'message' => "Task created successfully."
                ]);
        }catch(Exception $e){
            DB::rollback();
            return $e;
        }
       
    }

    public function updateTask(Request $request,$id)
    {
        $request->validate([
            'task_title'=>'required ', 
        ]);
        DB::beginTransaction();
        try{
            $data = Task::findOrFail($id);
            $data->task_title = $request->task_title;
            $data->task_details = $request->task_details;
            $data->priority = $request->priority;
            $data->save();
            TaskToUser::where('task_id','=',$id)->delete();
            if($request->members && is_array($request->members)){
                foreach($request->members as $val){
                    $taskToUser = new TaskToUser();
                    $taskToUser->user_id = $val; 
                    $taskToUser->task_id = $id; 
                    $taskToUser->save();
                }
            }
            DB::commit();
            return response()->json([
                'message' => "Task update successfully."
                ]);
        }catch(Exception $e){
            DB::rollback();
            return $e;
        }
       
    }
    
    public function destroyTask($id){
        DB::beginTransaction();
        try{
            DB::table('tbl_task')->where('id', $id)->delete();
            DB::table('tbl_task_to_user')->where('task_id', $id)->delete();
            DB::table('tbl_task_to_project')->where('task_id', $id)->delete();
            DB::table('tbl_task_comments')->where('task_id', $id)->delete();
            DB::table('tbl_task_time')->where('task_id', $id)->delete();
            DB::commit();
            return response()->json([
                'message'=> "Task delete successfully."
            ]);
        }catch(Exception $e){
            DB::rollback();
            return $e;
        }   
    }

    //Ridoy
    public function taskStatus(Request $req){
        $data = TaskStatus::get();
        return response()->json([
            'data'=>$data
        ]);
    }

    public function taskToStatus(Request $req){
        $data = DB::table('tbl_task_to_status')
                ->join('tbl_task','tbl_task_to_status.task_id','=','tbl_task.id')
                ->join('tbl_task_status','tbl_task_to_status.status_id','=','tbl_task_status.id')
                ->select('tbl_task_to_status.*','tbl_task.task_title','tbl_task_status.status_name')
                ->get();
        return response()->json([
            'data'=>$data
        ]);
    }
    public function taskToStatusStore(Request $req){
        TaskToStatus::create([
            'task_id'=>$req->task_id,
            'status_id'=>$req->status_id
        ]);
        return response()->json([
            'message' => 'Task-to-status create successfully.'
        ]);
    }
    public function taskToStatusDelete(Request $req, $id){
        TaskToStatus::destroy($id);
        return response()->json([
            'success' => true,
            'message' => 'Task-to-Status delete successfully.'
        ]);
    }
    

    public function indexTaskTime(){
        $data= DB::table('tbl_task_time')
                ->join('tbl_task','tbl_task_time.task_id','=','tbl_task.id')
                ->join('tbl_users','tbl_task_time.user_id','=','tbl_users.id')
                ->select('tbl_task_time.*','tbl_task.task_title','tbl_users.username')
                ->get();
        if(!$data->isEmpty()){
            return response()->json([
                'data' => $data
            ]);
        }else{
            return response()->json([
                'data' => $data
            ]);
        }
    }

    public function storeTaskTime(Request $req){
        $time = new TaskTime();
        $time->task_id = $req->task_id;
        $time->user_id = $req->user_id;
        $time->start_time = $req->start_time;
        $time->end_time = $req->end_time;
        $time->save();
        return response()->json([
            'success' => true,
            'message' => 'Task-to-time create successfully.'
        ]);
    }

    public function deleteTaskTime(Request $req, $id){
        TaskTime::destroy($id);
        return response()->json([
            'success' => true,
            'message' => 'Task-to-time delete successfully.'
        ]);
    }

    public function indexTaskComment($id){
        $data = DB::table('tbl_task_comments')
                ->join('tbl_task','tbl_task_comments.task_id','=','tbl_task.id')
                ->join('tbl_users','tbl_task_comments.user_id','=','tbl_users.id')
                ->where('tbl_task_comments.task_id',$id)
                ->select('tbl_task_comments.id','tbl_task_comments.comment','tbl_task_comments.file','tbl_task_comments.created_at','tbl_users.username')
                ->get();
        if(!$data->isEmpty()){
            return response()->json([
                'data' => $data
            ]);
        }else{
            return response()->json([
                'message' => "No data found."
            ]);
        }
    }

    public function storeTaskComment(TaskCommentRequest $req){
        $user = $req->user_id;
        $task_id = $req->task_id;
        $comment = $req->comment;
        if($req->hasFile('file')){
            $file_name = 'comment/user_id/'.date('Y-m-d-H-i-s').'_'.$req->file->getClientOriginalName();
            $req->file->move(public_path('comment/user_id'), $file_name);
        }else{
            $file_name = "";
        }
        TaskComment::create([
            'task_id' => $task_id,
            'user_id' => $user,
            'comment' => $comment ? $comment:"",
            'file' => $file_name ,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Task comment added successfully!'
        ]);  
    }

    public function deleteTaskComment(Request $req, $id){
        TaskComment::destroy($id);
        return response()->json([
            'success' => true,
            'message' => 'Task-comment delete successfully.'
        ]);
    }
}
