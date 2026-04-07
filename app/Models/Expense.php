<?php

namespace App\Models;

use App\Traits\TimeRangeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model{
	use TimeRangeScope;

    protected $fillable = [
		'purpose',
		'added_by',
		'amount',
		'desc',
		'expenditure_no',
	];

	// relations
	public function mycreator(): BelongsTo {
		return $this->belongsTo(User::class, 'added_by');
	}
}
