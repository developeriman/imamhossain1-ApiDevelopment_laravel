<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectToUser extends Model
{
    use HasFactory;
    protected $table = 'tbl_project_to_user';
    protected $fillable=[
        'user_id','project_id'
    ];
    public $timestamps=true;
}
