<?php

namespace App\Models;

use App\Traits\TimeRangeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

class Sample extends Model{
	use TimeRangeScope;

    protected $fillable = [
        'added_by',
		'task_id',
		'client_id',
		'sample_no',
		'sample_tag',
		'sample_type',
		'sample_count',
		'deviation_summary',
		'status',
		'has_report',
    ];

    protected $casts = [
        'sample_count' => 'integer',
        'results' => 'array',
        'deviation_summary' => 'array',
        'has_report' => 'boolean',
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
		return $this->belongsTo(Task::class, 'task_id');
	}

	// /*
	public function results(): HasMany {
		return $this->hasMany(Result::class,'sample_id','id');
	}
	public function reports(): HasMany {
		return $this->hasMany(ReportsGen::class,'sample_id','id');
	}
	// */
}
