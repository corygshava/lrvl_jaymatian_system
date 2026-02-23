<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model {
    protected $fillable = [
        'added_by',
        'client_name',
        'client_contact',
        'client_gender',
        'payment_plan',
        'credit_balance',
        'kra_pin',
        'id_number',
        'extra_details',
    ];

    protected $casts = [
        'credit_balance' => 'decimal:2',
        'extra_details' => 'array',
    ];

	// relationships
	public function mycreator(): BelongsTo {
		return $this->belongsTo(User::class, 'added_by');
	}
}
