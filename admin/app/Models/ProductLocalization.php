<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductLocalization extends Model
{
    protected $table = "products_translation";

    public $fillable = ['product_id', 'title', 'short_description', 'description', 'lang_code', 'is_active',];
	
	public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }
    

}
