<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class PartialOrderTransaction extends Model
{

    protected $table = 'partial_order_transactions';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }

    public function statuses()
    {
        return $this->hasOne(Status::class, 'id', 'status');
    }

}