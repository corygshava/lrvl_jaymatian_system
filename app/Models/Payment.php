<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'added_by',
		'task_id',
		'client_id',
		'method',
		'amount',
		'status',
		'extra_details',
		'transaction_time',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => 'integer',
        'extra_details' => 'array',
		'transaction_time' => 'date',
    ];

	public $status_atlas = [
		0 => 'pending',
		1 => 'paid',
		2 => 'failed',
	];

	// helpers
	public static function getStatus(int $n){
		return self::$status_atlas[$n];
	}

	// relationships
	public function mycreator(): BelongsTo {
		return $this->belongsTo(User::class, 'added_by');
	}
    public function task(): BelongsTo{
        return $this->belongsTo(Task::class);
    }
    public function client(): BelongsTo{
        return $this->belongsTo(Client::class);
    }
}
