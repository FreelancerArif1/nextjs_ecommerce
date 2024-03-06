<?php

namespace App\Models;

use Actuallymab\LaravelComment\CanComment;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;



class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use Notifiable;
    use CanComment;
    use LogsActivity;

    protected static $logFillable = true;
    protected static $logUnguarded = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'phone', 'street_address', 'division_id', 'district_id', 'upazila_id', 'union_id', 'ip_address', 'avatar', 'remember_token', 'city', 'state', 'zip', 'fb_id', 'google_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_verified_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function userSearch()
    {
        return $this->hasOne(UserSearch::class,'user_id');
    }
    public function meta()
    {
        return $this->hasMany(UsersMeta::class);
    }

    public function address()
    {
        return $this->hasMany(Addresses::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function number_of_order($id)
    {
        $number_of_order = Order::Where('user_id', $id)->count();
        return $number_of_order;
    }

    public function order_amount($id)
    {
        return Order::Where('user_id', $id)->where('is_deleted', 0)->sum('paid_amount');
    }

    public function order_paid_amount($id)
    {
        return Order::Where('user_id', $id)->where('status', 6)->where('is_deleted', 0)->sum('paid_amount');
    }

    public function order_pending_amount($id)
    {
        return Order::Where('user_id', $id)->where('status', '!=', 6)->where('is_deleted', 0)->sum('paid_amount');
    }

    public function loyalty_point($id)
    {
        return OrderDetails::Where('user_id', $id)->where('status', 6)->sum('loyalty_point');
    }

    public function product_return($id)
    {
        return ReturnRequest::Where('user_id', $id)->count();
    }
}