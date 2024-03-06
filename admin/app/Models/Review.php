<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Review extends Model
{
    protected $table = "comments";
	
	public function product(){
        return $this->hasOne(Product::class,'id','commentable_id');
    }
    
    public function user(){
        return $this->hasOne(User::class,'id','commented_id');
    }

}
