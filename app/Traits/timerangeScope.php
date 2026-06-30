<?php

namespace App\Traits;
use Carbon\Carbon;

trait TimeRangeScope{
	public $rangeAtlas = [
		'today',
		'yesterday',
		'juzi',
		'this_week',
		'this_month',
		'this_year',
		'last_week',
		'last_month',
		'last_year',
		'lifetime',
	];

	public function scopeTimeRange($q, $range, $timezone = null){
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

		// $tz = $timezone ?? config('app.timezone');
		$tz = $timezone ?? 'Africa/Nairobi';
		$storageTz = config('app.timezone'); // tz created_at is stored in (Laravel default: UTC)
		$now = Carbon::now($tz);

		return match($range){
			'today' => $q->whereBetween('created_at', [
				$now->copy()->startOfDay()->setTimezone($storageTz),
				$now->copy()->endOfDay()->setTimezone($storageTz),
			]),
			'yesterday' => $q->whereBetween('created_at', [
				$now->copy()->subDay()->startOfDay()->setTimezone($storageTz),
				$now->copy()->subDay()->endOfDay()->setTimezone($storageTz),
			]),
			'juzi' => $q->whereBetween('created_at', [
				$now->copy()->subDays(2)->startOfDay()->setTimezone($storageTz),
				$now->copy()->subDays(2)->endOfDay()->setTimezone($storageTz),
			]),
			'kabla_juzi' => $q->whereBetween('created_at', [
				$now->copy()->subDays(3)->startOfDay()->setTimezone($storageTz),
				$now->copy()->subDays(3)->endOfDay()->setTimezone($storageTz),
			]),
			'this_week' => $q->whereBetween('created_at', [
				$now->copy()->startOfWeek()->setTimezone($storageTz),
				$now->copy()->endOfWeek()->setTimezone($storageTz),
			]),
			'last_week' => $q->whereBetween('created_at', [
				$now->copy()->subWeek()->startOfWeek()->setTimezone($storageTz),
				$now->copy()->subWeek()->endOfWeek()->setTimezone($storageTz),
			]),
			'this_month' => $q->whereBetween('created_at', [
				$now->copy()->startOfMonth()->setTimezone($storageTz),
				$now->copy()->endOfMonth()->setTimezone($storageTz),
			]),
			'last_month' => $q->whereBetween('created_at', [
				$now->copy()->subMonth()->startOfMonth()->setTimezone($storageTz),
				$now->copy()->subMonth()->endOfMonth()->setTimezone($storageTz),
			]),
			'this_year' => $q->whereBetween('created_at', [
				$now->copy()->startOfYear()->setTimezone($storageTz),
				$now->copy()->endOfYear()->setTimezone($storageTz),
			]),
			'last_year' => $q->whereBetween('created_at', [
				$now->copy()->subYear()->startOfYear()->setTimezone($storageTz),
				$now->copy()->subYear()->endOfYear()->setTimezone($storageTz),
			]),
			'lifetime' => $q,
			default => $q,
		};
	}

	private function isDate(string $value): bool{
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
