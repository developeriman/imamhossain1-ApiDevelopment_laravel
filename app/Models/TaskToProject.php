<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskToProject extends Model
{
    use HasFactory;
     protected $table = 'tbl_task_to_project';
     protected $fillable=[
        'task_id',
        'project_id'  
    ];
       public $timestamps = true;
}
