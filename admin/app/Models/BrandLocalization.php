<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandLocalization extends Model
{
    use HasFactory;

    protected $table = "brands_translation";

    public $fillable = ['brand_id', 'title', 'description', 'lang_code', 'is_active'];

    public function category(){
        return $this->hasOne(Brand::class,'id','brand_id');
    }
}
