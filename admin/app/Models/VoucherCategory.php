<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Voucher;

class VoucherCategory extends Model
{
    public $table = 'voucher_categories';

    public function voucher(){
        return $this->hasMany(Voucher::class);
    }
}
