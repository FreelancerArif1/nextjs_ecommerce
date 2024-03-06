<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    public $fillable = [
        'title',
        'slider_text',
        'button_title',
        'button_link',
        'image'
    ];

    
}
