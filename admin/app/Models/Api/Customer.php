<?php

namespace App\Models\Api;

use App\Notifications\CustomPasswordResetNotification;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Tymon\JWTAuth\Contracts\JWTSubject;


class Customer extends Authenticatable implements CanResetPassword, JWTSubject
{
    use Notifiable;
    protected $guarded = [];
    protected $hidden = ['password','remember_token','created_at','updated_at','reminder','activation','last_activity','config'];
    protected $table = 'users';


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomPasswordResetNotification($token));
    }




    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}