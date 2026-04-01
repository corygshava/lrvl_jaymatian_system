<?php

namespace App\Models;

use App\Traits\TimeRangeScope;
use App\Traits\TrackLastUpdater;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Worksheet extends Model{
    protected $fillable = [
        'task_id',
        'client_id',
        'created_by',
        'sample_id',
        'last_updated_by',

        'sample_data',
        'sample_type',
        'sample_count',
		'readings',
		'has_readings',
		'is_published',
		'is_published',
		'published_at',
    ];

    protected $casts = [
        'sample_data' => 'array',
		'readings' => 'array',
		'has_readings' => 'boolean',
		'is_published' => 'boolean',
		'published_at' => 'datetime',
    ];

	// traits
	use TimeRangeScope;
	use TrackLastUpdater;

	// relations
    public function task(): BelongsTo{
        return $this->belongsTo(Task::class, 'task_id');
    }
    public function client(): BelongsTo{
        return $this->belongsTo(Client::class, 'client_id');
    }
    public function creator(): BelongsTo{
        return $this->belongsTo(User::class, 'created_by');
    }
    public function sample(): BelongsTo{
        return $this->belongsTo(Sample::class, 'sample_id');
    }
	public function lastupdater(): BelongsTo{
		return $this->belongsTo(User::class, 'last_updated_by');
	}
}
