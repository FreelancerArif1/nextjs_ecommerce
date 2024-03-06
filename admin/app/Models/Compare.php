<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;
use Request;
use Auth;

class Compare extends Model{

	protected $table = 'compares';
	
    public function product(){
         return $this->belongsTo(Product::class,'product_id');
    }
    public function meta(){
        return $this->hasMany(ProductMeta::class, 'product_id','product_id');
    }

    public function specification(){
        return $this->hasMany(ProductSpecification::class, 'product_id','product_id');
    }

	
}