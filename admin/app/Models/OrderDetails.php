<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class OrderDetails extends Model
{
    use LogsActivity;
    protected static $logFillable = true;
    protected static $logUnguarded = true;

    protected $table = 'order_details';

    public $fillable = [
        'user_id',
        'order_id',
        'product_id',
        'product_sku',
        'product_qty',
        'product_options',
        'shipping_method',
        'shipping_cost',
        'packaging_cost',
        'security_charge',
        'price',
        'seller_id',
        'shipping_company_id',
        'tracking_id',
        'is_promotion',
        'reviewed',
        'loyalty_point',
        'status',
        'created_at',
        'updated_at'
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function shopinfo()
    {
        return $this->hasOne(ShopInfo::class, 'seller_id', 'seller_id');
    }



    public function meta()
    {
        return $this->hasMany(ProductMeta::class, 'product_id', 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seller()
    {
        return $this->belongsTo(Admins::class);
    }

    public function statuses()
    {
        return $this->hasOne(Status::class, 'id', 'status');
    }


    public function productreturn()
    {
        return $this->hasMany(ReturnRequiest::class, 'order_details_id');
    }

    public function product_return_by_order_details_id()
    {
        return $this->hasOne(ReturnRequest::class, 'order_details_id');
    }

    // public function address(){
    //     return $this->belongsTo(Addresses::class);
    // }
    // public function payment(){
    //     return $this->belongsTo(Payments::class);
    // }

    // public static function newOrders(){
    //     $orders = Order::where('status',0)->orWhere('status',1)->get();
    //     if(!is_null($orders)){
    //         return count($orders);
    //     }else{
    //         return 0;
    //     }
    // }

}
