<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeLocalization extends Model
{
    use HasFactory;

    protected $table = "attributes_translation";

    public $fillable = ['attribute_id', 'title', 'description', 'lang_code', 'is_active'];

    public function category(){
        return $this->hasOne(Attribute::class,'id','attribute_id');
    }
}
