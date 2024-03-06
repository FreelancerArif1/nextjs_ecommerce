<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class OrderAutoRenewal extends Model
{
    use LogsActivity;
    protected $table = 'order_auto_renewal';
    protected static $logFillable = true;
    protected static $logUnguarded = true;
    public $fillable = [
        'order_id',
        'renewal_cycle',
        'next_order_date',
        'user_id',
        'status'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    
}
