<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectToUser;
use App\Models\TaskToProject;
use App\Models\TaskToUser;
use App\Models\UserWorkingRecord;
use App\Models\Project;
use DB;
use Carbon\Carbon;
class ReportController extends Controller
{
    public function report($id){
        $responseData = [];
        //here getting a project_id;
        $projectToUser=DB::table('tbl_project_to_user')
            ->where('project_id',$id)->pluck('user_id');//here getting user_id;
        $taskToUser=DB::table('tbl_task_to_project')
                ->where('project_id',$id)->pluck('task_id');//here getting task_id;
        $project = DB::table('tbl_project')->where('id',$id)->first();
        foreach($projectToUser as $val) {
            $user= DB::table('tbl_users')->where('id',$val)->select('id','first_name','last_name','username')->first();//it will gives me id,first_name,last_name,username for the tbl_users..
            $user = get_object_vars($user);
            $taskToUserIndividual=DB::table('tbl_task_to_user')
                ->where('user_id', $val)
                ->whereIn('task_id', $taskToUser)
                ->pluck('task_id');
            $total_time_task = DB::table('tbl_task_time')
                ->where("user_id", $val)
                ->whereIn('task_id', $taskToUser)
                ->get();
            $user['total_task'] = count($taskToUserIndividual);
            $user["total_time"] = 0;
            $task_time = 0;
            foreach($total_time_task as $val)
            {
                $task_time+= $this->datediff($val->start_time, $val->end_time);
            }
        $hours=$task_time;
        $hour=intval($hours/60);
        $minute=intval($hours-($hour*60));
        $task_time = date_format(date_create($hour.'.'.$minute),'H:i:s');
        $user["total_time"]=$task_time;
        array_push($responseData, $user);
        }
        return response()->json([
            'project' => $project,
            'data' => $responseData
        ]);
    }

    public function datediff($start_time,$end_time){
        $now = new \DateTime($start_time);
        $target = new \DateTime($end_time);
        $hours = ($target->getTimestamp() - $now->getTimestamp())/60;
        return $hours;
    }


    public function userReport($id)
    {
        $data = [];
        //get project details according to user
        $projects=DB::table('tbl_project')
                ->join('tbl_project_to_user','tbl_project.id','=','tbl_project_to_user.project_id')
                ->where('tbl_project_to_user.user_id',$id)->get();
        $data['project']=$projects;

        $screenshot = DB::table('tbl_user_working_screenshot')
                ->where('user_id',$id)
                ->orderBy('id','desc')
                ->take(5)->get();
        $data['screenshot'] = $screenshot;

        //get 1 day working record
        $userOneDayRecord = DB::table('tbl_user_working_record')
                ->whereDate('created_at', Carbon::today())
                ->where('user_id',$id)->where('time_type','=',1)->get();
        $dayOneRecord=0;
            foreach($userOneDayRecord as $val){
                $dayOneRecord += $this->datediff($val->start_time, $val->end_time);
        }
        $hours=$dayOneRecord;
        $hour=intval($hours/60);
        $minute=intval($hours-($hour*60));
        $dayOneRecord = date_format(date_create($hour.'.'.$minute),'H:i:s');
        $data['todayRecord']=$dayOneRecord;

        //get 7 day record
        $lastSevenDayRecord = Carbon::now()->subDays(8);
        $userLastSevenDayRecord = DB::table('tbl_user_working_record')->whereDate('created_at', '>=', $lastSevenDayRecord)
        ->where('user_id',$id)->get();
        $daySevenRecord=0;
            foreach($userLastSevenDayRecord as $val){
                $daySevenRecord += $this->datediff($val->start_time, $val->end_time);
        }
        $hours=$daySevenRecord;
        $hour=intval($hours/60);
        $minute=intval($hours-($hour*60));
        $daySevenRecord = date_format(date_create($hour.'.'.$minute),'H:i:s');
        $data['lastSevenDayRecord']=$daySevenRecord;
            return response()->json([
            'data' => $data
        ]);
    }

    public function userSpecificDayRecord($id,$date){

        $userOneDayRecord = DB::table('tbl_user_working_record')
        ->whereDate('created_at', $date)
        ->where('user_id',$id)->where('time_type','=',1)->get();

        $dayOneRecord=0;
        foreach($userOneDayRecord as $val){
            $dayOneRecord += $this->datediff($val->start_time, $val->end_time);
        }
        $hours=$dayOneRecord;
        $hour=intval($hours/60);
        $minute=intval($hours-($hour*60));
        $dayOneRecord = date_format(date_create($hour.'.'.$minute),'H:i:s');
        $data['todayRecord']=$dayOneRecord;
        return $dayOneRecord;                         
    }

    public function RandomDaysRecord($id,$date1,$date2){
        $date_1=$date1;
        $date_2=$date2;
        $userRandomDaysRecord = DB::table('tbl_user_working_record')
        ->whereDate('created_at','>=',$date_1)
        ->whereDate('created_at','<=',$date_2)
        ->where('user_id',$id)->where('time_type','=',1)->get();

        $Record=0;
        foreach($userRandomDaysRecord as $val){
            $Record += $this->datediff($val->start_time, $val->end_time);
        }
        $hours=$Record;
        $hour=intval($hours/60);
        $minute=intval($hours-($hour*60));
        $Record = date_format(date_create($hour.'.'.$minute),'H:i:s');
        // $Record = $hours.'-'.$minute.'00';
        $data['Random_Days_Record']=$Record;
        return $Record;                     
    }

    public function projectUserTaskReport($project_id, $user_id){
        $responseData = [];
        $project = Project::where('id',$project_id)->first();
        $responseData['project'] = $project;
        $user = DB::table('tbl_users')->where('id',$user_id)->select('id','username')->first();
        $responseData['user'] = $user;
        $taskToUser=DB::table('tbl_task_to_project')
                ->where('project_id',$project_id)->pluck('task_id');
        $taskToUserRecord = DB::table('tbl_task_time')
                    ->selectRaw('tbl_task.*, TIMESTAMPDIFF(minute, start_time, end_time) as duration')
                    ->join('tbl_task','tbl_task_time.task_id','=','tbl_task.id')
                    ->where('tbl_task_time.user_id',$user_id)
                    ->get();
        $responseData['task'] = $taskToUserRecord;
        return response()->json([
            'data' => $responseData
        ]);
    }
}