<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporateRequests extends Model
{

    use HasFactory;
    protected $table = 'corporate_requests';
    protected $fillable = [
        'user_id',
        'request_id',
        'amount',
        'discount',
        'invoice',
        'work_order',
        'preferable_date',
        'for_delivery',
        'delivery_date',
        'payment_details',
        'payment_status',
        'deal_status',
        'created_at',
        'updated_at'
    ];

    public function username()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function statuses()
    {
        return $this->belongsTo(Status::class, 'deal_status', 'id');
    }

    public function payment_status()
    {
        return $this->belongsTo(Status::class, 'payment_status', 'id');
    }

    public function negotiations()
    {
        return $this->hasMany(CorporateRequestNegotiations::class, 'corporate_request_id', 'id');
    }

    public function request_details(){
        return $this->hasMany(CorporateRequestDetails::class, 'corporate_request_id','id');
    }
}
