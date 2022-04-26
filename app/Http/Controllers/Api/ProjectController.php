<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\ProjectToUser;
use App\Models\User;
use App\Http\Requests\ProjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index()
    {
        $project = DB::table('tbl_project')->get();
        foreach($project as $val)
         {
             $usersId=ProjectToUser::where('project_id',$val->id)->pluck('user_id');
            // dd($usersId);
           $user=DB::table('tbl_users')->select('id','first_name','last_name','email')->whereIn('id', $usersId)->get();
           // dd($user);
           $val->members=$user;
         }
         return response()->json([
            'success'=>true,
            'project'=>$project
        ], 200);
         //return $project;
    }

    public function store(ProjectRequest $request)
    {
       
        try{
            DB::beginTransaction();
            $project=new Project();
            $project->project_name=$request->project_name;
            $project->project_description=$request->project_description;
            $project->start_date=$request->start_date;
            $project->target_end_date=$request->target_end_date;
            $project->end_date=$request->end_date;
            $project->save();
        
            if ($request->members && is_array($request->members)) {
                foreach($request->members as $val) {
                    $projectTouser = new ProjectToUser();
                    $projectTouser->user_id =$val;
                    $projectTouser->project_id=$project->id;
                    $projectTouser->save();
                }
            }
            
            DB::commit();
            return response()->json([
                'success'=>true,
                'message'=>'inserted successfully'
            ], 200);
        }
        catch(\Exception $e){
            DB::rollback();
            return response()->json(['error' => 'Something went wrong, try again'], 500);
        }
 }

    public function show($id)
    {
        $project=Project::findOrFail($id);
        return new ProjectResource($project);
    }

    
    public function update(Request $request, $id){
        DB::beginTransaction();
        try{
            $project=Project::findOrFail($id);
            $project->project_name=$request->project_name;
            $project->project_description=$request->project_description;
            $project->start_date=$request->start_date;
            $project->target_end_date=$request->target_end_date;
            $project->end_date=$request->end_date;  
            $project->save();
            ProjectToUser::where('project_id','=',$id)->delete();
            if ($request->members && is_array($request->members)) {
                foreach($request->members as $val) {
                    $projectTouser = new ProjectToUser();
                    $projectTouser->user_id =$val;
                    $projectTouser->project_id =$id;
                    $projectTouser->save();
                    //$projectTouser->project_id=$project->id;             
                }
            }
            DB::commit();
            return response()->json([
                'success'=>true,
                'message'=>'Update Done'
            ], 200);
        }catch(Exception $e){
            DB::rollback();
            return $e;
        }
    }
    
    public function destroy($id)
      {      
        $project=Project::findOrFail($id);
        if($project->delete())
           {
            $imm=new ProjectResource($project);
            if($imm)
                {
                    return response()->json([
                        'success'=>true,
                        'message'=>'deleted successfully',
                    ]);
                }
               else if(!$imm)
                {
                    return response()->json([
                        'success'=>false,
                        'message'=>'not deleted successfully'
                    ]);
                }
            }
    }
public function CreateUser(Request $request)
    { 
        
          $data= ProjectToUser::create([
               'project_id'=>$request->project_id,              
                'user_id'=>$request->user_id
           ]);
           return response()->json([
            'success'=>true,
            'message'=>'project to user create successfully',
            'data'=>$data,           
           ]);
    }

 public function ReadUser()
       {
             $data= DB::table('tbl_project_to_user')
             ->join('tbl_project', 'tbl_project_to_user.project_id', '=', 'tbl_project.id')
             ->join('tbl_users', 'tbl_project_to_user.user_id', '=', 'tbl_users.id')
             ->select('tbl_project.*', 'tbl_users.*')
            // ->where('tbl_project_to_user.user_id',$id)
             ->get();
             return response()->json([
                'success'=>true,
                'data'=>$data,
            ]);
       }
 public function DeleteUser($id)
   {
   
       ProjectToUser::destroy($id);
       return response()->json([
        'success'=>true,
        'message'=>'deleted successfully',
            ]); 
   }
}