<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class AttributeSets extends Model 
{
 
    public $fillable = [
		'title',
        'description',
		'attribute_set_code',
        'attribute_ids',
        'is_active',
        'is_deleted'
    ];
	
	

}