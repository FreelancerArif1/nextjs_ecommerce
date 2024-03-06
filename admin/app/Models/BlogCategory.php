<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Blog;

class BlogCategory extends Model
{
    public $table = 'blog_categories';

    public function blogs(){
        return $this->hasMany(Blog::class,'category_id');
    }


}
