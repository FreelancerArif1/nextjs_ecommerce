<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Upazila;

class Union extends Model
{
    use HasFactory;
	
	protected $hidden = ['url'];

    public function upazila(){
        return $this->belongsTo(Upazila::class);
    }
}
