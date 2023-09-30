<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TsEmployee extends Model
{
    protected $table = 'ts_employee';
    protected $fillable = [
        'employee_code',

    ];
}
