<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporateRequestDetails extends Model
{
    use HasFactory;
    protected $table = 'corporate_request_details';
    protected $fillable = [
        'user_id',
        'qty',
        'amount',
        'discount',
        'payment_amount',
        'invoice',
        'work_order',
        'preferable_date',
        'delivery_date',
        'payment_details',
        'payment_status',
        'deal_status',
        'is_deleted',
        'created_at',
        'updated_at',
    ];

    public function shop_name()
    {
        return $this->belongsTo(ShopInfo::class, 'seller_id', 'seller_id');
    }

    public function productname()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function statuses()
    {
        return $this->belongsTo(Status::class, 'status', 'id');
    }

    public function children($request_id)
    {
        return CorporateRequests::where('request_id', $request_id)->where('is_deleted', 0)->get();
    }
}