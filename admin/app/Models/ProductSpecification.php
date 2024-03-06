<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSpecification extends Model
{
    protected $table = 'product_specification';
    public $fillable = [
        'product_id',
        'meta_key',
        'meta_value'
       ];
}
