<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskToUser extends Model
{
    use HasFactory;
     protected $table = 'tbl_task_to_user';
     protected $fillable=[
        'task_id',
        'user_id'  
    ];
}
