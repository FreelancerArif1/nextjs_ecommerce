<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public $fillable = [
        'title',
        'slug',
        'description',
        'meta_title',
        'meta_description',
        'status'
    ];
}
