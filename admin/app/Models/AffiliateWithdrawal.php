<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateWithdrawal extends Model
{
    use HasFactory;
    protected $table = "affiliate_withdrawal";
    protected $guarded = [];
    public function username()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}