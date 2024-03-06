<?php

namespace App\Models;

use Actuallymab\LaravelComment\Contracts\Commentable;
use Actuallymab\LaravelComment\HasComments;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model implements Commentable
{
    use HasComments;
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logUnguarded = true;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function canBeRated(): bool
    {
        return true;
    }

    public function mustBeApproved(): bool
    {
        return true;
    } 


    public $fillable = [
        'brand_id',
        'brand_title',
        'category_id',
        'category_title',
        'delivery_location',
        'title',
        'barcode',
        'defalut_image',
        'gallery_images',
        'short_description',
        'description',
        'slug',
        'tag',
        'price',
        'product_cost',
        'packaging_cost',
        'handling_fee',
        'loyalty_point',
        'list_price',
        'tour_price',
        'vat',
        'weight',
        'special_price',
        'special_price_type',
        'special_price_start',
        'special_price_end',
        'sku',
        'manage_stock',
        'attribute_set_id',
        'qty',
        'shuffle_number',
        'in_stock',
        'viewed',
        'is_active',
        'related_products',
        'entry_by',
        'minimum_cart_value',
        'aff_commission_amount',
        'aff_commission_type',
        'aff_commission_from',
        'aff_commission_to'
    ];

    public function productCategories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function meta()
    {
        return $this->hasMany(ProductMeta::class);
    }

    public function specification()
    {
        return $this->hasMany(ProductSpecification::class);
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }


    public function defalut_image_object()
    {
        return $this->belongsTo(ConcaveMedia::class, 'default_image', 'id');
    }

    public function gallery_images_object()
    {
        return $this->hasMany(ConcaveMedia::class, 'id', 'gallery_images');
    }

    public function saleItems()
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function seller()
    {
        return $this->belongsTo(Admins::class);
    }

    public function shopinfo()
    {
        return $this->belongsTo(ShopInfo::class, 'seller_id', 'seller_id');
    }

    public function localization()
    {
        return $this->hasMany(ProductLocalization::class, 'product_id', 'id');
    }

    public function orderdetails()
    {
        return $this->hasMany(OrderDetails::class, 'product_id');
    }

    public function upazila(){
        return $this->hasMany(Upazila::class,'id','delivery_location');
    }
}