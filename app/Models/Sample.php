<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sample extends Model
{
    protected $fillable = [
        'sample_no',
        'sample_tag',
        'sample_type',
        'sample_count',
        'date_added',
        'results',
        'deviation_summary',
    ];

    protected $casts = [
        'date_added' => 'date',
        'sample_count' => 'integer',
        'results' => 'array',
        'deviation_summary' => 'array',
    ];

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
