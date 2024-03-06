<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{

    protected $fillable = [];
    
    public function division(){
        return $this->belongsTo(Division::class,'shipping_division');
    }

    public function district(){
        return $this->belongsTo(District::class,'shipping_district');
    }

    public function upazila(){
        return $this->belongsTo(Upazila::class,'shipping_thana');
    }

    public function union(){
        return $this->belongsTo(Union::class,'shipping_union');
    }
}
