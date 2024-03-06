<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopInfo extends Model
{
	protected $table = "shop_info";
	protected $hidden = ['email', 'trade_license', 'phone', 'created_at', 'updated_at'];

	public function division()
	{
		return $this->belongsTo(Division::class, 'division');
	}

	public function district()
	{
		return $this->belongsTo(District::class, 'district');
	}

	public function upazila()
	{
		return $this->belongsTo(Upazila::class, 'area');
	}

	public function union()
	{
		return $this->belongsTo(Union::class, 'shop_union');
	}
}