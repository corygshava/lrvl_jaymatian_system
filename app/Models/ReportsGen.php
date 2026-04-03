<?php

namespace App\Models;

use App\Traits\TimeRangeScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportsGen extends Model{
	use TimeRangeScope;

    protected $table = 'reports_gen';

    protected $fillable = [
		'task_id',
		'sample_id',
		'created_by',
		'client_id',
		'report_no',

		'company_contact',
		'sample_type',
		'sample_count',
		'date_received',
		'date_compiled',
		'task_no',
		'technical_staff_name',
		'authorizer',
		'attendant',
		'analytical_report',
		'deviation_data',
		'business_logo_img',

		'pdf_link',
		'image_link',
		'file_slug',
	];

	protected $casts = [
		'deviation_data' => 'array',
        'analytical_report' => 'array',
        'date_received' => 'date',
        'date_compiled' => 'date',
        'sample_count' => 'integer',
    ];

	// relationships
    public function task(): BelongsTo{
        return $this->belongsTo(Task::class,'task_id');
    }
	public function sample(): BelongsTo {
		return $this->belongsTo(Sample::class,'sample_id');
	}
	public function mycreator(): BelongsTo {
		return $this->belongsTo(User::class,'created_by');
	}
	public function owner(): BelongsTo {
		return $this->belongsTo(Client::class,'client_id');
	}
}
