<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
public $fillable = [
    'transaction_id',
    'sub_total',
    'total'
   ];
}



