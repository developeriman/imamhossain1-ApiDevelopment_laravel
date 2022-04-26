<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForgetPassword extends Model
{
    use HasFactory;
    protected $table = 'tbl_forget_password';
    protected $fillable = [
        'email', 'token','created_at'
    ];
    public $timestamps = true;

}
