<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;
use Request;
use Auth;

class Cart extends Model
{

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function customer()
    {
        return $this->belongsTo(User::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function meta()
    {
        return $this->hasMany(ProductMeta::class, 'product_id', 'product_id');
    }
}