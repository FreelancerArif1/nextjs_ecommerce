<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMeta extends Model
{
    public $fillable = [
        'product_id',
        'meta_key',
        'meta_value'
       ];
}

