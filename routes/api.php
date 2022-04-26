<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\TaskFileController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserWorkingController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ReportController;
use Carbon\Carbon;
Route::get('/', function () {
    dd(bcrypt("123456"));
    return 'Working';
});

Route::post('register',[RegisterController::class,'register']);
Route::post('login',[AuthController::class,'login']);


Route::group(['middleware' => ['auth']], function ($router) {
    
    // ridoy 
    Route::post('refresh',[AuthController::class,'refresh']);
    Route::get('users',[UserController::class,'getAllUser']);
    Route::post('user/change-password',[UserController::class,'changePassword']);
    Route::post('user/change-status',[UserController::class,'changeStatus']);
    Route::get('user/project/{id}',[TaskController::class,'userToProject']);
    Route::get('user/task/{id}',[UserController::class,'userTask']);
    Route::post('forget-password',[ForgotPasswordController::class,'forgetPassword']);
    Route::post('reset-password',[ForgotPasswordController::class,'resetPassword']);

    Route::get('task-file',[TaskFileController::class,'index']);
    Route::post('task-file/store',[TaskFileController::class,'store']);
    Route::delete('task-file/delete/{id}',[TaskFileController::class,'destroy']);

    Route::get('task-status',[TaskController::class,'taskStatus']);
    Route::get('task-to-status',[TaskController::class,'taskToStatus']);
    Route::post('task-to-status/store',[TaskController::class,'taskToStatusStore']);
    Route::delete('task-to-status/delete/{id}',[TaskController::class,'taskToStatusDelete']);

    Route::get('user-working-screenshot',[UserWorkingController::class,'fetchScreenshot']);
    Route::post('user-working-screenshot/store',[UserWorkingController::class,'userWorkingScreenshotStore']);
    Route::delete('user-working-screenshot/delete/{id}',[UserWorkingController::class,'userWorkingScreenshotDelete']);

    Route::get('user-working-record',[UserWorkingController::class,'indexUserWorkingRecord']);
    Route::post('user-working-record/store',[UserWorkingController::class,'storeUserWorkingRecord']);

    Route::get('task-time',[TaskController::class,'indexTaskTime']);
    Route::post('task-time/store',[TaskController::class,'storeTaskTime']);

    Route::get('task-comment/{id}',[TaskController::class,'indexTaskComment']);
    Route::post('task-comment/store',[TaskController::class,'storeTaskComment']);
    Route::delete('task-comment/delete/{id}',[TaskController::class,'deleteTaskComment']);

    Route::get('project/{id}/task',[TaskController::class,'indexProjectTask']);
    Route::get('task/{id}',[TaskController::class,'indexTask']);
    Route::get('task/all/{id}',[TaskController::class,'indexAllTask']);
    Route::post('task/store',[TaskController::class,'storeTask']);
    Route::delete('task/delete/{id}',[TaskController::class,'destroyTask']);
    Route::put('task/update/{id}',[TaskController::class,'updateTask']);

    Route::get('task-to-project',[TaskController::class,'indexTaskToProject']);
    Route::post('task-to-project/store',[TaskController::class,'storeTaskToProject']);
    Route::delete('task-to-project/delete/{id}',[TaskController::class,'deleteTaskToProject']);

    Route::get('task-to-user',[TaskController::class,'indexTaskToUser']);
    // Route::post('task-to-user/store',[TaskController::class,'storeTaskToUser']);
    // Route::delete('task-to-user/delete/{id}',[TaskController::class,'deleteTaskToUser']);


    Route::get('project/{project_id}/user/{user_id}/task',[ReportController::class,'projectUserTaskReport']);

    // Ahsanul
    Route::get('/project','Api\ProjectController@index');
    Route::get('/project/edit/{id}',[ProjectController::class,'show']);
    Route::put('/project/update/{id}',[ProjectController::class,'update']); 
    Route::get('project-to-user',[ProjectController::class,'ReadUser']);  
    Route::post('project-to-user/store',[ProjectController::class,'CreateUser']);
    
    Route::get('/user/report/{id}',[ReportController::class,'userReport']);
    Route::get('/user-report/{id}/{start_date}/{end_date}',[ReportController::class,'RandomDaysRecord']);
    //Iman
    Route::get('user/specific-day-record/{id}/{date}',[ReportController::class,'userSpecificDayRecord']);

    Route::group(['middleware' => ['check_admin_access']], function ($router){
        //Ahsanul
        Route::post('/project/store',[ProjectController::class,'store']);
        Route::delete('/project/delete/{id}',[ProjectController::class,'destroy']);
        Route::delete('project-to-user/delete/{id}',[ProjectController::class,'DeleteUser']);
        Route::get('project/report/{id}',[ReportController::class,'report']);

            //Ridoy
        Route::delete('user-working-record/delete/{id}',[UserWorkingController::class,'deleteUserWorkingRecord']);
        Route::delete('task-time/delete/{id}',[TaskController::class,'deleteTaskTime']);
    });
});

