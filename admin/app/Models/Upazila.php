<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\District;
use App\Models\Union;

class Upazila extends Model
{
    use HasFactory;

    public function district(){
        return $this->belongsTo(District::class);
    }

    public function union(){
        return $this->hasMany(Union::class);
    }
}
