<?php

namespace App\Traits;
use Carbon\Carbon;

trait TimeRangeScope{
	public $rangeAtlas = [
		'today',
		'last_week',
		'last_month',
		'last_year',
		'lifetime',
	];

	public function scopeTimeRange($q, $range){
		// lastweek

		return match($range){
			'today' => $q->whereDate('created_at', Carbon::today()),
			'this_week' => $q->whereBetween('created_at', [
				Carbon::now()->subWeek()->startOfWeek(),
				Carbon::now()->subWeek()->endOfWeek(),
			]),
			'this_month' => $q->whereMonth('created_at', Carbon::now()->subMonth()->month)
				->whereYear('created_at',Carbon::now()->subMonth()->year),
			'this_year' => $q->whereYear('created_at', Carbon::now()->subYear()->year),
			'last_week' => $q->whereBetween('created_at', [
				Carbon::now()->addDays(-7)->subWeek()->startOfWeek(),
				Carbon::now()->addDays(-7)->endOfWeek(),
			]),
			'last_month' => $q->whereMonth('created_at', Carbon::now()->subMonth()->month)
				->whereYear('created_at',Carbon::now()->subMonth()->year),
			'last_year' => $q->whereYear('created_at', Carbon::now()->subYear()->year),
			'lifetime' => $q,
			default => $q,
		};
	}
}
