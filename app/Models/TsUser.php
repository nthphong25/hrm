<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TsUser extends Authenticatable
{
    protected $table = 'ts_user';
    protected $fillable = ['user_code', 'user_password'];

   
}
