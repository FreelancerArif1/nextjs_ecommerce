<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavbarLocalization extends Model
{
    use HasFactory;

    protected $table = "navbars_translation";

    public $fillable = ['navbar_id', 'title', 'lang_code', 'is_active'];

    public function category(){
        return $this->hasOne(Navbar::class,'id','navbar_id');
    }
}
