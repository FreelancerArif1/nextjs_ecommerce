<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporateRequestNegotiations extends Model
{
    use HasFactory;
    protected $table = 'corporate_request_negotiations';

    public function username()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function adminname()
    {
        return $this->belongsTo(Admins::class, 'admin_id', 'id');
    }
}