<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pickpoints extends Model
{
    protected $table = 'pick_points';

    public function division(){
        return $this->belongsTo(Division::class,'division_id');
    }

    public function district(){
        return $this->belongsTo(District::class,'district_id');
    }

    public function upazila(){
        return $this->belongsTo(Upazila::class,'upazila_id');
    }

    public function union(){
        return $this->belongsTo(Union::class,'union_id');
    }
    
}
