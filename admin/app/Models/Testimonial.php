<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model{
    public $fillable = [
        'name',
        'profession',
        'image',
        'dialuge',
        'status'
    ];
	protected $table = 'testimonials';
    
}
?>