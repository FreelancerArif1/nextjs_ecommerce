<?php

namespace App\Models;

use Actuallymab\LaravelComment\Contracts\Commentable;
use Actuallymab\LaravelComment\HasComments;
use Illuminate\Database\Eloquent\Model;
use App\Models\BlogCategory;

class Blog extends Model implements Commentable
{
    use HasComments;


    public function category(){
        return $this->hasOne(BlogCategory::class,'id');
    }
}


