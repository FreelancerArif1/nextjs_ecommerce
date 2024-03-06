<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersMeta extends Model
{
    public $fillable =[
        'user_id',
        'meta_key',
        'meta_value'
    ];
}
