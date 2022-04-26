<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    use HasFactory;
    protected $table = 'tbl_user_login';
    protected $fillable = [
        'user_id',
        'auth_token',
    ];
    public $timestamps = true;

}
