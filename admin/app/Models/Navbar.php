<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Navbar extends Model
{
    public $fillable = [
        'title',
        'link',
        'link_type',
        'sort_order',
        'status'
    ];

    
}
