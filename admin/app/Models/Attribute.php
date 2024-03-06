<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model 
{
 
    public $fillable = [
		'title',
        'description',
        'catalog_input_type',
        'is_required',
        'show_on_specification',
        'show_on_advance_search',
        'show_on_filter',
        'is_active'
    ];

}