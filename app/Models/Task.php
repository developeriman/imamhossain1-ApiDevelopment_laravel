<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $table ='tbl_task'; 
    protected $fillable=[
            'task_title',
            'task_details'
        ];

}
