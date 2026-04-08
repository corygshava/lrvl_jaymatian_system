<?php

namespace App\Traits;
use Carbon\Carbon;

trait TimeRangeScope{
	public $rangeAtlas = [
		'today',
		'this_week',
		'this_month',
		'this_year',
		'last_week',
		'last_month',
		'last_year',
		'lifetime',
	];

	public function scopeTimeRange($q, $range){
		// Handle array (date range)
		if (is_array($range)) {
			if (count($range) === 2) {
				return $q->whereBetween('created_at', [
					Carbon::parse($range[0])->startOfDay(),
					Carbon::parse($range[1])->endOfDay(),
				]);
			}
			return $q; // Invalid array, return unmodified query
		}

		// Handle single date (string that can be parsed as date)
		if ($this->isDate($range)) {
			return $q->whereDate('created_at', Carbon::parse($range));
		}

		// Handle named ranges
		$range = strtolower($range);

		return match($range){
			'today' => $q->whereDate('created_at', Carbon::today()),
			'this_week' => $q->whereBetween('created_at', [
				Carbon::now()->startOfWeek(),
				Carbon::now()->endOfWeek(),
			]),
			'this_month' => $q->whereMonth('created_at', Carbon::now()->month)
				->whereYear('created_at', Carbon::now()->year),
			'this_year' => $q->whereYear('created_at', Carbon::now()->year),
			'last_week' => $q->whereBetween('created_at', [
				Carbon::now()->subWeek()->startOfWeek(),
				Carbon::now()->subWeek()->endOfWeek(),
			]),
			'last_month' => $q->whereMonth('created_at', Carbon::now()->subMonth()->month)
				->whereYear('created_at', Carbon::now()->subMonth()->year),
			'last_year' => $q->whereYear('created_at', Carbon::now()->subYear()->year),
			'lifetime' => $q,
			default => $q,
		};
	}

	private function isDate($value): bool{
		if (!is_string($value)) {
			return false;
		}

		try {
			Carbon::parse($value);
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}
}
