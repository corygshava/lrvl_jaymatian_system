<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Mylog extends Model{
	protected $table="logs";

    protected $fillable = [
		'user_id',
		'user_name',
		'path',
		'ip_address',
		'intent',
		'method',
		'headers',
		'payload',
		'permission_required',
		'extra_details',
    ];

    protected $casts = [
        'payload' => 'array',
        'headers' => 'array',
        'extra_details' => 'array',
    ];

    protected static function booted(): void{
        static::created(function ($log) {
            $log->saveToLocalFile();
        });
    }

    public function saveToLocalFile(): void{
        $date = now()->format('Y-m-d');
        $path = "runtime/usagelogs/{$this->user_id}/[{$date}]_logs.json";

        $existingLogs = Storage::exists($path)
            ? json_decode(Storage::get($path), true)
            : [];

        $existingLogs[] = $this->toArray();

        Storage::put($path, json_encode($existingLogs, JSON_PRETTY_PRINT));
    }
}
