<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Sample;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChartsController extends Controller
{
	public function sales(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			't_range' => 'required|in:today,this_week,this_month,this_year,last_week,last_month,last_year,lifetime'
		]);
		// */

		if($this->ili()){
			$res['success'] = true;
			$msg = "sales data fetched successfully";
			$reslt = false;
			$timeframe = $passed['t_range'];

			[$labels, $data] = $this->getSalesData($timeframe);
			$outres = [
				'labels' => $labels,
				'datasets' => [
					[
						'label' => 'Sales made',
						'data' => $data,
						'borderColor' => 'rgb(75, 192, 192)',
						'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
						'tension' => 0.1
					]
				]
			];

			$reslt = true;

			$res['result'] = $reslt;
			$res['sendme'] = $outres;
			$res['message'] = $msg;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}
	private function getSalesData($timeframe = "lifetime"){
		switch ($timeframe) {
			case 'today':
				$labels = [];
				$data = [];

				for ($hour = 0; $hour < 24; $hour++) {
					$labels[] = Carbon::today()->addHours($hour)->format('H:00');
					$data[] = Task::whereBetween('created_at', [
							Carbon::today()->hour($hour)->minute(0)->second(0),
							Carbon::today()->hour($hour)->minute(59)->second(59)
						])
						->count()
					;
				}
				break;

			case 'last_week':
				// Daily breakdown for last week
				$labels = [];
				$data = [];

				$startOfWeek = Carbon::now()->subWeek()->startOfWeek();

				for ($day = 0; $day < 7; $day++) {
					$date = $startOfWeek->copy()->addDays($day);
					$labels[] = $date->format('D'); // Mon, Tue, Wed...
					$data[] = Task::whereDate('created_at', $date)->count();
				}
				break;

			case 'last_month':
				// Daily breakdown for last month
				$labels = [];
				$data = [];

				$lastMonth = Carbon::now()->subMonth();
				$daysInMonth = $lastMonth->daysInMonth;

				for ($day = 1; $day <= $daysInMonth; $day++) {
					$date = $lastMonth->copy()->day($day);
					$labels[] = $date->format('M d'); // Jan 01, Jan 02...
					$data[] = Task::whereDate('created_at', $date)->count();
				}
				break;

			case 'last_year':
				// Monthly breakdown for last year
				$labels = [];
				$data = [];

				$lastYear = Carbon::now()->subYear()->year;

				for ($month = 1; $month <= 12; $month++) {
					$labels[] = Carbon::create($lastYear, $month, 1)->format('M'); // Jan, Feb, Mar...
					$data[] = Task::whereYear('created_at', $lastYear)
						->whereMonth('created_at', $month)
						->count();
				}
				break;

			case 'lifetime':
				// Yearly breakdown for all time
				$labels = [];
				$data = [];

				$years = Task::selectRaw('YEAR(created_at) as year')
					->distinct()
					->orderBy('year')
					->pluck('year');

				foreach ($years as $year) {
					$labels[] = (string) $year;
					$data[] = Task::whereYear('created_at', $year)->count();
				}
				break;

			default:
				$labels = [];
				$data = [];
		}

		return [$labels, $data];
	}

	public function samples(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			't_range' => 'required|in:today,this_week,this_month,this_year,last_week,last_month,last_year,lifetime'
		]);
		// */

		if($this->ili()){
			$res['success'] = true;
			$msg = "sales data fetched successfully";
			$reslt = false;
			$timeframe = $passed['t_range'];

			[$labels, $data] = $this->getSamplesData($timeframe);
			$outres = [
				'labels' => $labels,
				'datasets' => [
					[
						'label' => 'Samples Delivered',
						'data' => $data,
						'borderColor' => 'rgb(75, 192, 192)',
						'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
						'tension' => 0.1
					]
				]
			];

			$reslt = true;

			$res['result'] = $reslt;
			$res['sendme'] = $outres;
			$res['message'] = $msg;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}
	private function getSamplesData($timeframe = "lifetime"){
		switch ($timeframe) {
			case 'today':
				$labels = [];
				$data = [];

				for ($hour = 0; $hour < 24; $hour++) {
					$labels[] = Carbon::today()->addHours($hour)->format('H:00');
					$data[] = Sample::whereBetween('created_at', [
							Carbon::today()->hour($hour)->minute(0)->second(0),
							Carbon::today()->hour($hour)->minute(59)->second(59)
						])
						->count()
					;
				}
				break;

			case 'last_week':
				// Daily breakdown for last week
				$labels = [];
				$data = [];

				$startOfWeek = Carbon::now()->subWeek()->startOfWeek();

				for ($day = 0; $day < 7; $day++) {
					$date = $startOfWeek->copy()->addDays($day);
					$labels[] = $date->format('D'); // Mon, Tue, Wed...
					$data[] = Sample::whereDate('created_at', $date)->count();
				}
				break;

			case 'last_month':
				// Daily breakdown for last month
				$labels = [];
				$data = [];

				$lastMonth = Carbon::now()->subMonth();
				$daysInMonth = $lastMonth->daysInMonth;

				for ($day = 1; $day <= $daysInMonth; $day++) {
					$date = $lastMonth->copy()->day($day);
					$labels[] = $date->format('M d'); // Jan 01, Jan 02...
					$data[] = Sample::whereDate('created_at', $date)->count();
				}
				break;

			case 'last_year':
				// Monthly breakdown for last year
				$labels = [];
				$data = [];

				$lastYear = Carbon::now()->subYear()->year;

				for ($month = 1; $month <= 12; $month++) {
					$labels[] = Carbon::create($lastYear, $month, 1)->format('M'); // Jan, Feb, Mar...
					$data[] = Sample::whereYear('created_at', $lastYear)
						->whereMonth('created_at', $month)
						->count();
				}
				break;

			case 'lifetime':
				// Yearly breakdown for all time
				$labels = [];
				$data = [];

				$years = Sample::selectRaw('YEAR(created_at) as year')
					->distinct()
					->orderBy('year')
					->pluck('year');

				foreach ($years as $year) {
					$labels[] = (string) $year;
					$data[] = Sample::whereYear('created_at', $year)->count();
				}
				break;

			default:
				$labels = [];
				$data = [];
		}

		return [$labels, $data];
	}

	public function visits(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			't_range' => 'required|in:today,this_week,this_month,this_year,last_week,last_month,last_year,lifetime'
		]);
		// */

		if($this->ili()){
			$res['success'] = true;
			$msg = "sales data fetched successfully";
			$reslt = false;
			$timeframe = $passed['t_range'];

			[$labels, $data] = $this->getVisitsData($timeframe);
			$outres = [
				'labels' => $labels,
				'datasets' => [
					[
						'label' => 'Visits made',
						'data' => $data,
						'borderColor' => 'rgb(75, 192, 192)',
						'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
						'tension' => 0.1
					]
				]
			];

			$reslt = true;

			$res['result'] = $reslt;
			$res['sendme'] = $outres;
			$res['message'] = $msg;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}
	private function getVisitsData($timeframe = "lifetime"){
		switch ($timeframe) {
			case 'today':
				$labels = [];
				$data = [];

				for ($hour = 0; $hour < 24; $hour++) {
					$labels[] = Carbon::today()->addHours($hour)->format('H:00');
					$data[] = Sample::whereBetween('created_at', [
							Carbon::today()->hour($hour)->minute(0)->second(0),
							Carbon::today()->hour($hour)->minute(59)->second(59)
						])
						->count()
					;
				}
				break;

			case 'last_week':
				// Daily breakdown for last week
				$labels = [];
				$data = [];

				$startOfWeek = Carbon::now()->subWeek()->startOfWeek();

				for ($day = 0; $day < 7; $day++) {
					$date = $startOfWeek->copy()->addDays($day);
					$labels[] = $date->format('D'); // Mon, Tue, Wed...
					$data[] = Sample::whereDate('created_at', $date)->count();
				}
				break;

			case 'last_month':
				// Daily breakdown for last month
				$labels = [];
				$data = [];

				$lastMonth = Carbon::now()->subMonth();
				$daysInMonth = $lastMonth->daysInMonth;

				for ($day = 1; $day <= $daysInMonth; $day++) {
					$date = $lastMonth->copy()->day($day);
					$labels[] = $date->format('M d'); // Jan 01, Jan 02...
					$data[] = Sample::whereDate('created_at', $date)->count();
				}
				break;

			case 'last_year':
				// Monthly breakdown for last year
				$labels = [];
				$data = [];

				$lastYear = Carbon::now()->subYear()->year;

				for ($month = 1; $month <= 12; $month++) {
					$labels[] = Carbon::create($lastYear, $month, 1)->format('M'); // Jan, Feb, Mar...
					$data[] = Sample::whereYear('created_at', $lastYear)
						->whereMonth('created_at', $month)
						->count();
				}
				break;

			case 'lifetime':
				// Yearly breakdown for all time
				$labels = [];
				$data = [];

				$years = Sample::selectRaw('YEAR(created_at) as year')
					->distinct()
					->orderBy('year')
					->pluck('year');

				foreach ($years as $year) {
					$labels[] = (string) $year;
					$data[] = Sample::whereYear('created_at', $year)->count();
				}
				break;

			default:
				$labels = [];
				$data = [];
		}

		return [$labels, $data];
	}

	public function income(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			't_range' => 'required|in:today,this_week,this_month,this_year,last_week,last_month,last_year,lifetime'
		]);
		// */

		if($this->ili()){
			$res['success'] = true;
			$msg = "sales data fetched successfully";
			$reslt = false;
			$timeframe = $passed['t_range'];

			[$labels, $data] = $this->getIncomeData($timeframe);
			$outres = [
				'labels' => $labels,
				'datasets' => [
					[
						'label' => 'Income',
						'data' => $data,
						'borderColor' => 'rgb(75, 192, 192)',
						'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
						'tension' => 0.1
					]
				]
			];

			$reslt = true;

			$res['result'] = $reslt;
			$res['sendme'] = $outres;
			$res['message'] = $msg;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}
	public function getIncomeData($timeframe = 'lifetime'){
		switch ($timeframe) {
			case 'today':
				$labels = [];
				$data = [];

				for ($hour = 0; $hour < 24; $hour++) {
					$labels[] = Carbon::today()->addHours($hour)->format('H:00');
					$data[] = Payment::whereBetween('created_at', [
							Carbon::today()->hour($hour)->minute(0)->second(0),
							Carbon::today()->hour($hour)->minute(59)->second(59)
						])
						->sum('amount')
					;
				}
				break;

			case 'last_week':
				// Daily breakdown for last week
				$labels = [];
				$data = [];

				$startOfWeek = Carbon::now()->subWeek()->startOfWeek();

				for ($day = 0; $day < 7; $day++) {
					$date = $startOfWeek->copy()->addDays($day);
					$labels[] = $date->format('D'); // Mon, Tue, Wed...
					$data[] = Payment::whereDate('created_at', $date)->sum('amount');
				}
				break;

			case 'last_month':
				// Daily breakdown for last month
				$labels = [];
				$data = [];

				$lastMonth = Carbon::now()->subMonth();
				$daysInMonth = $lastMonth->daysInMonth;

				for ($day = 1; $day <= $daysInMonth; $day++) {
					$date = $lastMonth->copy()->day($day);
					$labels[] = $date->format('M d'); // Jan 01, Jan 02...
					$data[] = Payment::whereDate('created_at', $date)->sum('amount');
				}
				break;

			case 'last_year':
				// Monthly breakdown for last year
				$labels = [];
				$data = [];

				$lastYear = Carbon::now()->subYear()->year;

				for ($month = 1; $month <= 12; $month++) {
					$labels[] = Carbon::create($lastYear, $month, 1)->format('M'); // Jan, Feb, Mar...
					$data[] = Payment::whereYear('created_at', $lastYear)
						->whereMonth('created_at', $month)
						->sum('amount');
				}
				break;

			case 'lifetime':
				// Yearly breakdown for all time
				$labels = [];
				$data = [];

				$years = Payment::selectRaw('YEAR(created_at) as year')
					->distinct()
					->orderBy('year')
					->pluck('year');

				foreach ($years as $year) {
					$labels[] = (string) $year;
					$data[] = Payment::whereYear('created_at', $year)->sum('amount');
				}
				break;

			default:
				$labels = [];
				$data = [];
		}

		return [$labels, $data];
	}
}
