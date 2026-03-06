<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model{
    protected $fillable = [
        'sample_id',
        'client_id',
        'added_by',

		'sample_name',
        'sample_type',
        'au_ppm',
        'cu_ppm',
    ];

    protected $casts = [
        'au_ppm' => 'decimal:4',
        'cu_ppm' => 'decimal:4',
    ];

	// relationships
	public function sample(): BelongsTo {
		return $this->belongsTo(Sample::class,'sample_id');
	}
	public function owner(): BelongsTo {
		return $this->belongsTo(Client::class,'client_id');
	}
	public function creator(): BelongsTo {
		return $this->belongsTo(User::class,'added_by');
	}
}
