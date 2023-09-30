<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TsDept extends Model
{
    protected $table = 'ts_dept';
    protected $fillable = [
        'dept_code',

    ];
}
