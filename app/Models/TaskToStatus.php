<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskToStatus extends Model
{
    use HasFactory;

    protected $table= 'tbl_task_to_status';

    protected $fillable = [
        'task_id','status_id'
    ];

    public $timestamps = true;

}
