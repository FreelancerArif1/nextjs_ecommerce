<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Order extends Model
{
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logUnguarded = true;

    public $fillable = [
        'user_id',
        'address_id',
        'payment_id',
        'ip_address',
        'phone_number',
        'email',
        'shipping_method',
        'payment_method',
        'message',
        'status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function order_details()
    {
        return $this->hasMany(OrderDetails::class);
    }
    public function orderdetails()
    {
        return $this->hasOne(OrderDetails::class, 'order_id');
    }

    public function address()
    {
        return $this->belongsTo(Addresses::class);
    }

    public function pickpoint_address()
    {
        return $this->belongsTo(Pickpoints::class, 'address_id', 'id');
    }


    public function auto_renewal()
    {
        return $this->belongsTo(OrderAutoRenewal::class, 'id', 'order_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payments::class);
    }

    public function statuses()
    {
        return $this->hasOne(Status::class, 'id', 'status');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id');
    }

    public function product_return_by_order_id()
    {
        return $this->hasMany(ReturnRequest::class, 'order_id');
    }

    public static function newOrders()
    {
        $orders = Order::where('status', 0)->orWhere('status', 1)->get();
        if (!is_null($orders)) {
            return count($orders);
        } else {
            return 0;
        }
    }
}