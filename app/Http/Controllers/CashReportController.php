<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\DailyCashReport;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\ReportsGen;
use App\Models\Sample;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CashReportController extends Controller{
    public function get_all(Request $req){
		$res = $this->dftres;
		// $alldata = $req->all();

		if($this->ili()){
			$res['success'] = true;
			$msg = "all payments retrieved";

			$perPage = $req->input('per_page', 10);
			$search = $req->input('search');
			$sortBy = $req->input('sort_by', 'created_at');
			$sortDirection = $req->input('sort_direction', 'desc');

			// filters
			$timegap = $req->input('timegap',null);

			$sortBy = $sortBy == null ? 'created_at' : $sortBy;

			$res['runlog'][] = [
				"perPage" => $perPage,
				"search" => $search,
				"sortBy" => $sortBy,
				"sortDirection" => $sortDirection,
				"time gap" => $timegap,
			];

			// clean up sortBy to prevent issues
			if($sortBy == 'mycreator'){
				$sortBy = 'added_by';
			}

			// return response()->json($res);

			$records = DailyCashReport::query()
				->with('mycreator')
			;

			if($search){
				$records = $records
					->whereHas('mycreator', function($q) use ($search){
						$q->where('name','like',"%{$search}%")
							->orWhere('email','like',"%{$search}%")
						;
					})
				;
				$res['runlog'][] = "added search filter";
			}

			// filter for task status
			if($timegap){
				$records = $records->TimeRange($timegap);
			}

			$sortBy = $sortBy == null || $sortBy == '' ? 'created_at' : $sortBy;
			// $sortBy = ($sortBy == 'role_name') ? "user_roles_id" : $sortBy;

			$records = $records
				->orderBy($sortBy, $sortDirection)
				->paginate($perPage)
			;

			$res['result'] = true;
			$res['sendme'] = $records;
			$res['message'] = $msg;
		}

		// dd($res);

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}

    public function get_for_today(Request $req,bool $iscalled = false,array &$errlog = []){
		$res = $this->dftres;
		// $res = [...$res,...$oldres];
		$alldata = $req->all();
		$msg = "didnt even get to start";

		// /*
		$passed = $req->validate([
			't_range' => ['sometimes','in:today,this_week,this_month,this_year,last_week,last_month,last_year,lifetime']
		]);
		// */

		$update_runlog = function($wot) use (&$res){
			$res['runlog'][] = $wot;
		};
		$update_msg = function($wot,$logit = true) use (&$msg,&$update_runlog){
			$msg = $wot;
			if($logit){
				$update_runlog($wot);
			}
		};
		$_msg = function($w,$l = true) use (&$update_msg){
			$update_msg($w,$l);
		};
		$_rlg = function($w,$l = true) use (&$update_runlog){
			$update_runlog($w);
		};

		if($this->ili()){
			$res['success'] = true;
			$update_msg('starting the procedure');
			$reslt = false;

			$outdata = [];
			$myrange = $req->input('t_range',null);

			if(isset($req['use_range'])){
				$myrange = $req['use_range'];
			}

			$lastrec = DailyCashReport::query()
				->where('id','<>',null)
			;

			if($myrange == null || strtolower("$myrange") == 'today'){
				$lastrec = $lastrec
					->latest()
					->first()
				;
			} else {
				$lastrec = $lastrec
					->timeRange($myrange)
					->orderBy('created_at','asc')
					->first()
				;
			}
			$_rlg($lastrec);
			$timediff = null;

			if($lastrec !== null){
				$timediff = $lastrec->created_at->diffInHours(now());
			}

			$_rlg(['timediff' => $timediff]);
			$wegood = $timediff == null ? true : $timediff > 23;

			if(!$wegood && false){
				$_msg('you can only make one report per day');
			} else {
				$_msg('you can make a daily report for today');
				$t_range = $myrange == null ? 'today' : $myrange;

				// calculate carry over cash
				$carry_over = $lastrec == null ? 0 : $lastrec->total_balance_forward;
				$_msg('carry over calculated');

				// calculate sample counts
				$samples = Sample::query()
					->where('id','<>',null)
					->timeRange($t_range)
				;

				// get total income
				if($lastrec != null){
					$payemnts = Payment::query()
						->where('id','<>',null)
						->timeRange($t_range)
					;
					$_rlg('added payment filter');
				} else {
					$payemnts = Payment::query()
						->where('id','<>',null)
					;
					$_rlg('ignored payment filter');
				}

				$total_income = (clone $payemnts)->where('payment_type','task')->sum('amount');
				$cash_from_debtors = (clone $payemnts)->where('payment_type','invoice')->sum('amount');
				$total_daily_cash = $total_income + $cash_from_debtors;
				$total_cash = $total_daily_cash + $carry_over;
				// $_rlg(['payments sum' => $payemnts]);
				$_msg("calculated gross income");

				// get total expenses
				$expenses = Expense::query()
					->where('id','<>',null)
				;
				if($lastrec != null){
					$expenses = $expenses->timeRange($t_range);
				}

				$expenditure = (clone $expenses)->sum('amount');
				$total_balance_forward = $total_cash - $expenditure;
				$_msg("calculated expenses");

				// get samples data
					$mysamples = (clone $samples)->get()->count();
					$soil_samples = (clone $samples)->where('sample_type','soil')->get()->count();
					$rock_samples = (clone $samples)->where('sample_type','rock')->get()->count();
					$solution_samples = (clone $samples)->where('sample_type','solution')->get()->count();
					$carbon_samples = (clone $samples)->where('sample_type','carbon')->get()->count();
					$carbon_act_samples = (clone $samples)->where('sample_type','carbon activity')->get()->count();
					$_msg('samples data fetched');

					$sampledata = [
						'soil' => $soil_samples,
						'rock' => $rock_samples,
						'solution' => $solution_samples,
						'carbon' => $carbon_samples,
						'carbon activity' => $carbon_act_samples,
					];

				// get cash data
					$in_mpesa = (clone $payemnts)->where('payment_type','task')->where('method','mpesa')->sum('amount');
					$in_cash = (clone $payemnts)->where('payment_type','task')->where('method','cash')->sum('amount');
					$debt_mpesa = (clone $payemnts)->where('payment_type','invoice')->where('method','mpesa')->sum('amount');
					$debt_cash = (clone $payemnts)->where('payment_type','invoice')->where('method','cash')->sum('amount');
					$tots_mpesa = $in_mpesa + $debt_mpesa;
					$tots_cash = $in_cash + $debt_cash;

					$cash_data = [
						'income mpesa' => $in_mpesa,
						'income cash' => $in_cash,
						'debt mpesa' => $debt_mpesa,
						'debt cash' => $debt_cash,
						'total mpesa' => $tots_mpesa,
						'total cash' => $tots_cash,
					];
					$_msg('cash flow data compiled');

				// get expenditure data
					$director_expenditure = (clone $expenses)->where('purpose','expenditure')->sum('amount');
					$bank_transfer = (clone $expenses)->where('purpose','<>','expenditure')->sum('amount');
					$spend_data = [
						'director expenditure' => $director_expenditure,
						'Bank transfer' => $bank_transfer,
					];

				// get extra data
					$total_users = User::query()->timeRange($t_range)->where('id','<>',null)->get()->count();
					$total_clients = Client::query()->timeRange($t_range)->where('id','<>',null)->get()->count();
					$total_tasks = Task::query()->timeRange($t_range)->where('id','<>',null)->get()->count();
					// $total_samples = Sample::query()->timeRange($t_range)->where('id','<>',null)->get()->count();
					$total_payments_task = Payment::query()->timeRange($t_range)->where('id','task')->get()->count();
					$total_payments_invoice = Payment::query()->timeRange($t_range)->where('id','invoice')->get()->count();
					$reports_generated = ReportsGen::query()->timeRange($t_range)->where('id','<>',null)->get()->count();
					$total_invoices = Invoice::query()->timeRange($t_range)->where('id','<>',null)->get()->count();
					$total_expenses = Expense::query()->timeRange($t_range)->where('id','<>',null)->get()->count();
					$unpaid_tasks = Task::query()->timeRange($t_range)->where('payment_status','<>',2)->get()->count();
					$paid_tasks = Task::query()->timeRange($t_range)->where('payment_status',2)->get()->count();

				$outdata = [
					...$outdata,
					'time_difference' => $timediff,
					'carry_over' => $carry_over,
					'total_income' => $total_income,
					'cash_from_debtors' => $cash_from_debtors,
					'total_daily_cash' => $total_daily_cash,
					'total_cash' => $total_cash,
					'total_balance_forward' => $total_balance_forward,
					'expenditure' => $expenditure,

					'sample_data' => $sampledata,
					'cash_data' => $cash_data,
					'spend_data' => $spend_data,

					// extras, coz why not
					'users_count' => $total_users,
					'clients_count' => $total_clients,
					'tasks_count' => $total_tasks,
					'samples_count' => $mysamples,
					'total_payments_task_count' => $total_payments_task,
					'total_payments_invoice_count' => $total_payments_invoice,
					'reports_count' => $reports_generated,
					'invoices_count' => $total_invoices,
					'expenses_count' => $total_expenses,
					'paid_tasks' => $paid_tasks,
					'unpaid_tasks' => $unpaid_tasks,
				];

				$_msg('data compiled successfully');
				$reslt = true;
			}

			$res['result'] = $reslt;
			$res['sendme'] = $outdata;
			$res['message'] = $msg;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		if(!$iscalled){
			return response()->json($res);
		} else {
			if(isset($res['runlog'])){
				$runlog = $res['runlog'];
				$errlog = [...$errlog,...$runlog];
			}

			return $res['sendme'];
		}
	}

	public function save_me(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();
		$msg = "didnt even get to start";

		// /*
		$passed = $req->validate([
			'rptdata' => ['required']
		]);
		// */

		$update_runlog = function($wot) use (&$res){
			$res['runlog'][] = $wot;
		};
		$update_msg = function($wot,$logit = true) use (&$msg,&$update_runlog){
			$msg = $wot;
			if($logit){
				$update_runlog($wot);
			}
		};
		$_msg = function($w,$l = true) use (&$update_msg){
			$update_msg($w,$l);
		};
		$_rlg = function($w) use (&$update_runlog){
			return $update_runlog($w);
		};

		if($this->ili()){
			$res['success'] = true;
			$update_msg('starting the procedure');
			$reslt = false;
			$user = $this->cur_user();

			$toadd = $passed['rptdata'];
			$toadd['added_by'] = $user->id;
			$_msg('adding daily report');

			$lastrec = DailyCashReport::query()
				->where('id','<>',null)
				->latest()
				->first()
			;
			$timediff = null;

			if($lastrec !== null){
				$timediff = $lastrec->created_at->diffInHours(now());
			}

			$_rlg(['timediff' => $timediff]);
			$wegood = $timediff == null ? true : $timediff > 23;

			if(!$wegood){
				$_msg('You can only generate a daily report once every day');
			} else {
				$_msg('saving daily report');
				$toadd = $this->get_for_today($req,true,$res['runlog']);
				unset($toadd['carry_over']);
				$created = DailyCashReport::create($toadd);

				if($created == null){
					$_msg('error creating record');
				} else {
					$reslt = true;
					$_msg('Daily Report saved successfully');
				}
			}

			$res['result'] = $reslt;
			$res['sendme'] = $toadd;
			$res['message'] = $msg;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}
}
