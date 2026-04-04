<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FileHash extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_type',
        'myhash',
        'invoice_id',
        'report_id',
        'file_name',
        'file_format',
        'page_count',
    ];

    /**
     * Check if this file hash already exists for a different document
     */
    public function isDuplicate(): bool {
        $query = self::where('myhash', $this->file_hash)
            ->where('id', '!=', $this->id);

        if ($this->file_type === 'invoice') {
            $query->where('invoice_id', '!=', $this->invoice_id);
        } else {
            $query->where('report_id', '!=', $this->report_id);
        }

        return $query->exists();
    }

	// relations
	public function invoice(): BelongsTo {
        return $this->belongsTo(Invoice::class);
    }
    public function report(): BelongsTo {
        return $this->belongsTo(ReportsGen::class);
    }
}
