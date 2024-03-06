<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use  App\Models\Division;
use  App\Models\Upazila;

class District extends Model
{
	
	protected $hidden = ['status'];
	
	
    public function division(){
        return $this->belongsTo(Division::class);
    }

    public function upazila(){
        return $this->hasMany(Upazila::class);
    }
}
