<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectedVoucher extends Model
{
    use HasFactory;

	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status'
    ];
	
	public function voucher(){
		return $this->belongsTo(Voucher::class,'voucher_id');
	}
}
