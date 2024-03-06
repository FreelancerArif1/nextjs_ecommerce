<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageLocalization extends Model
{
    use HasFactory;

    protected $table = "pages_translation";

    public $fillable = ['page_id', 'title', 'description', 'lang_code', 'is_active'];

    public function page(){
        return $this->hasOne(Page::class,'id','page_id');
    }
}
