<?php

namespace App\Models;

use App\Traits\TimeRangeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model {
	use TimeRangeScope;

    protected $fillable = [
        'added_by',
        'client_name',
        'client_contact',
        'client_gender',
        'payment_plan',
        'credit_balance',
        'kra_pin',
        'id_number',
        'extra_details',
        'payment_data',
	];

	protected $casts = [
		'credit_balance' => 'decimal:2',
		'extra_details' => 'array',
		'payment_data' => 'array',
    ];

	// relationships
	public function mycreator(): BelongsTo {
		return $this->belongsTo(User::class, 'added_by');
	}
	public function tasks(): HasMany {
		return $this->hasMany(Task::class,'client_id','id');
	}
	public function reports(): HasMany {
		return $this->hasMany(ReportsGen::class,'client_id','id');
	}
	public function samples(): HasMany {
		return $this->hasMany(Sample::class,'client_id','id');
	}
	public function payments(): HasMany {
		return $this->hasMany(Payment::class,'client_id','id');
	}
	public function invoices(): HasMany {
		return $this->hasMany(Invoice::class,'client_id','id');
	}
}
