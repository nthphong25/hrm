<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TsEmployeeDept extends Model
{
    protected $table = 'ts_employeedept';
    protected $fillable = [
        'employee_code',

    ];
}
