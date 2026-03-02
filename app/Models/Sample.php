<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sample extends Model
{
    protected $fillable = [
        'added_by',
		'task_id',
		'client_id',
		'sample_no',
		'sample_tag',
		'sample_type',
		'sample_count',
		'results',
		'deviation_summary',
		'status',
    ];

    protected $casts = [
        'sample_count' => 'integer',
        'results' => 'array',
        'deviation_summary' => 'array',
    ];

	public $state_atlas = [
		0 => 'pending',
		1 => 'incomplete',
		2 => 'complete',
	];

	// helpers
	public static function getStatus(int $n){
		return self::$state_atlas[$n];
	}

	// relationships
	public function mycreator(): BelongsTo {
		return $this->belongsTo(User::class, 'added_by');
	}
	public function owner(): BelongsTo {
		return $this->belongsTo(Client::class, 'client_id');
	}
	public function task(): BelongsTo {
		return $this->belongsTo(Client::class, 'task_id');
	}
}
