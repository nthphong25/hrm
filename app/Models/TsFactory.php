<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TsFactory extends Model
{
    protected $table = 'ts_factory';

    protected $fillable = [
        'factory_code',
        'factory_extcode',
    ];
}
