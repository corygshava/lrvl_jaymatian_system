<?php

namespace App\Models;

use App\Traits\TimeRangeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model{
	use TimeRangeScope;

    protected $fillable = [
        'client_id',
        'created_by',
		'last_updated_by',

        'invoice_no',
        'total',
        'total_paid',
        'balance',
        'tasks_count',
        'task_ids',
        'invoice_data',
        'status',

		'pdf_link',
		'image_link',
		'file_slug',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'balance' => 'decimal:2',
        'task_ids' => 'array',
        'invoice_data' => 'array',
        'tasks_count' => 'integer',
        'status' => 'integer',
    ];

	protected $state_atlas = [
		0 => 'pending',
		1 => 'incomplete',
		2 => 'paid',
	];

	// relations
    public function client(): BelongsTo{
        return $this->belongsTo(Client::class);
    }
	public function creator(): BelongsTo {
		return $this->belongsTo(User::class,'created_by');
	}
	public function tasks(): BelongsToMany{
		return $this->belongsToMany(Task::class,'invoice_tasks','invoice_id','task_id');
	}
	public function payments(): HasMany{
		return $this->hasMany(Payment::class,'invoice_id');
	}
	public function filehashes(): HasMany{
		return $this->hasMany(FileHash::class,'invoice_id');
	}
}
