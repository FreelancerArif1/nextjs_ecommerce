<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryLocalization extends Model
{
    use HasFactory;

    protected $table = "categories_translation";

    public $fillable = ['category_id', 'title', 'description', 'lang_code', 'is_active'];

    public function category(){
        return $this->hasOne(Category::class,'id','category_id');
    }
}
