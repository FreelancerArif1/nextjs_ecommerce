<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    use HasFactory;

    public function seller()
    {
        return $this->belongsTo(Admins::class);
    }

    public function statuses()
    {
        return $this->hasOne(Status::class, 'id', 'status');
    }
}