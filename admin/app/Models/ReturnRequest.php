<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    protected $table = "product_return";

    public function seller()
    {
        return $this->belongsTo(Admins::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statuses()
    {
        return $this->hasOne(Status::class, 'id', 'status');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function order_details()
    {
        return $this->hasOne(OrderDetails::class, 'id', 'order_details_id');
    }
}