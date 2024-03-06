<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    public $fillable =[
        'key',
        'value',
        'key',
        'is_active',
        'status'
    ];



}
