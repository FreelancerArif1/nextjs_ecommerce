<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategoryLocalization extends Model
{
    use HasFactory;

    protected $table = "blog_categories_translation";

    public $fillable = ['blog_category_id', 'title', 'description', 'lang_code', 'is_active'];

    public function category(){
        return $this->hasOne(BlogCategory::class,'id','blog_category_id');
    }
}
