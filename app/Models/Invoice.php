<?php

namespace App\Models;

use App\Traits\TimeRangeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

	//
    public function client(): BelongsTo{
        return $this->belongsTo(Client::class);
    }
	public function creator(): BelongsTo {
		return $this->belongsTo(User::class,'created_by');
	}
	public function tasks(): BelongsToMany{
		return $this->belongsToMany(Invoice::class,'invoice_tasks','task_id','invoice_id');
	}
}
