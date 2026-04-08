<?php

namespace App\Models;

use App\Traits\TimeRangeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyCashReport extends Model{
    use TimeRangeScope;

    protected $fillable = [
		'added_by',

		'carry_over',
		'total_income',
		'cash_from_debtors',
		'total_daily_cash',
		'expenditure',
		'total_cash',
		'total_balance_forward',

		'sample_data',
		'cash_data',
		'spend_data',

		'users_count',
		'clients_count',
		'tasks_count',
		'samples_count',
		'total_payments_task_count',
		'total_payments_invoice_count',
		'reports_count',
		'invoices_count',
		'expenses_count',
	];

	protected $casts = [
		'sample_data' => 'array',
		'cash_data' => 'array',
		'spend_data' => 'array',

		'total_income' => 'decimal:2',
		'total_daily_cash' => 'decimal:2',
		'cash_from_debtors' => 'decimal:2',
		'gross_cash' => 'decimal:2',
		'expenditure' => 'decimal:2',
	];

	// relations
	public function mycreator(): BelongsTo {
		return $this->belongsTo(User::class, 'added_by');
	}
}
