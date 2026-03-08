<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model{
	protected $fillable = [
		'task_no',
		'added_by',
		'client_id',
		'client_name',
		'status',
		'payment_status',
		'is_paid',
		'notes',
		'passed_task_data',
		'cost',
		'paid',
		'balance',
	];
	protected $hidden = [
		'passed_task_data',
	];
	protected $casts = [
		'is_paid' => 'boolean',
		'status' => 'integer',
		'passed_task_data' => 'array',
	];

	public $state_atlas = [
		0 => 'pending',
		1 => 'awaiting_payment',
		2 => 'complete',
	];
	public $payment_state_atlas = [
		0 => 'pending',
		1 => 'incomplete',
		2 => 'paid',
	];

	// helpers
	public static function getStatus(int $n){
		return self::$state_atlas[$n];
	}
	public static function getPayStatus(int $n){
		return self::$state_atlas[$n];
	}

	// relationships
	public function creator(): BelongsTo{
		return $this->belongsTo(User::class,'added_by');
	}
	public function client(): BelongsTo{
		return $this->belongsTo(Client::class,'client_id');
	}

	public function samples(): HasMany{
		return $this->hasMany(Sample::class,'task_id','id');
	}
	public function payments(): HasMany{
		return $this->hasMany(Payment::class,'task_id','id');
	}
}
