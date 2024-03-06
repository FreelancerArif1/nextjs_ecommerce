<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trash extends Model
{
    protected $table = 'trash';

    public function admin(){
        return $this->hasOne(Admins::class,'id','deleted_by');
    }
}
