<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class SellerAccountHistory extends Model
{
    protected $table = "seller_account_history";
	
	public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
