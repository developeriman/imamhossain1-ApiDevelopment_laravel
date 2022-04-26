<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    use HasFactory;
    protected $table = 'tbl_task_comments';
    protected $fillable = [
        'task_id','user_id','comment','file'
    ];
}
