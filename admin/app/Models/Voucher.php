<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\VoucherCategory;

class Voucher extends Model
{
    use HasFactory;

    public function voucher_category(){
        return $this->belongsTo(VoucherCategory::class);
    }

    
}
