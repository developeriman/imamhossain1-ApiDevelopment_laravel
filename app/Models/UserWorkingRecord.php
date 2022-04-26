<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserWorkingRecord extends Model
{
    use HasFactory;
    protected $table = 'tbl_user_working_record';
    protected $fillable = [
        'user_id', 'time_type', 'start_time'
    ];
    public $timestamps = true;
}
