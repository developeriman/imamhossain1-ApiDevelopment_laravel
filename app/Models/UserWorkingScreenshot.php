<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWorkingScreenshot extends Model
{
    use HasFactory;

    protected $table = 'tbl_user_working_screenshot';

    protected $fillable = ['user_id','screenshot'];

    public $timestamps = true;

}
