<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskTime extends Model
{
    use HasFactory;
    protected $table = 'tbl_task_time';
    protected $fillable = [
        'task_id','user_id', 'start_time', 'end_time' 
    ];
}
