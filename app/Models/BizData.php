<?php

namespace App\Models;

use App\Traits\TimeRangeScope;
use Illuminate\Database\Eloquent\Model;

class BizData extends Model{
	use TimeRangeScope;

    protected $fillable = [
		'item_name',
		'item_data',
		'created_by',
		'modified_by',
	];

	protected $hidden = [
		// 'id'
	];

	protected $casts = [
		'item_data' => 'array',
	];
}
