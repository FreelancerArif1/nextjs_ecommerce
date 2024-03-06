<?php

namespace App\Models;

use Actuallymab\LaravelComment\Contracts\Commentable;
use Actuallymab\LaravelComment\HasComments;
use Illuminate\Database\Eloquent\Model;
use App\Models\BlogCategory;

class Client extends Model implements Commentable{
	protected $table = 'clients';
	protected $fillable = ['title', 'slug', 'link', 'image', 'meta_title', 'meta_description', 'status', 'meta_keyword'];
	
    use HasComments;


    public function category(){
        return $this->hasOne(BlogCategory::class,'id');
    }
}