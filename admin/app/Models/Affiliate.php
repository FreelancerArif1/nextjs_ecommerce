<?php

namespace App\Models;

use Actuallymab\LaravelComment\Contracts\Commentable;
use Actuallymab\LaravelComment\HasComments;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;

class Affiliate extends Model
{

    protected $table = 'affiliate_history';

    public function buyer(){
        return $this->hasOne(User::class,'id','buyer_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function status(){
        return $this->belongsTo(Status::class);
    }

}


