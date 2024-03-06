<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogLocalization extends Model
{
    use HasFactory;

    protected $table = "blogs_translation";

    public $fillable = ['blog_id', 'title', 'description', 'lang_code', 'is_active'];

    public function category(){
        return $this->hasOne(Blog::class,'id','blog_id');
    }
}
