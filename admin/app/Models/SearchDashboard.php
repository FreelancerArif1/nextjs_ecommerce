<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SearchDashboard extends Model{

	protected $table = 'search_dashboard';
	protected $fillable = [
		'title',
		'link',
		'permission',
		'status',
	];
	

}