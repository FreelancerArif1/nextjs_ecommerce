<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderLocalization extends Model
{
    use HasFactory;

    protected $table = "sliders_translation";

    public $fillable = ['slider_id', 'title', 'description', 'lang_code', 'is_active'];

    public function category(){
        return $this->hasOne(Slider::class,'id','slider_id');
    }
}
