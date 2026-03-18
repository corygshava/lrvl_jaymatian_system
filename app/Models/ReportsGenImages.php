<?php

namespace App\Models;

use App\Traits\TimeRangeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportsGenImage extends Model{
	use TimeRangeScope;

    protected $fillable = [
        'task_id',
        'sample_id',
        'reports_gen_id',
        'created_by',
		'client_id',

        'image_path',
        'image_hash',
    ];

	// relationships
    public function task(): BelongsTo{
        return $this->belongsTo(Task::class,'task_id');
    }
	public function sample(): BelongsTo {
		return $this->belongsTo(Sample::class,'sample_id');
	}
	public function report(): BelongsTo{
        return $this->belongsTo(ReportsGen::class,'reports_gen_id');
    }
	public function creator(): BelongsTo {
		return $this->belongsTo(User::class,'created_by');
	}
	public function owner(): BelongsTo {
		return $this->belongsTo(Client::class,'client_id');
	}
}
