<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BizData extends Model
{
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
