<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\DailyCashReport;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\MonthlyCashReport;
use App\Models\Payment;
use App\Models\ReportsGen;
use App\Models\Sample;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CashReportController extends Controller{
	public function route_stuff(Request $req){
		$pickwhat = $req->input('rectype','daily');

		if(strtolower($pickwhat) == 'daily'){
			$result = $this->get_all_daily($req);
		} else {
			$result = $this->get_all_monthly($req);
		}

		return $result;
	}
	public function get_all_daily(Request $req){
		$res = $this->dftres;
		// $alldata = $req->all();

		if($this->ili()){
			$res['success'] = true;
			$msg = "all payments retrieved";

			$perPage = $req->input('per_page', 10);
			$search = $req->input('search');
			$sortBy = $req->input('sort_by', 'created_at');
			$sortDirection = $req->input('sort_direction', 'desc');
			// $t_range = $req->input('timerange',null);

			// filters
			$timegap = $req->input('timerange',null);

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
					->where('created_at','like',"%{$search}%")
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
	public function get_all_monthly(Request $req){
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

			$records = MonthlyCashReport::query()
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

	public function get_one(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();
		$msg = "didnt even get to start";

		// /*
		$passed = $req->validate([
			'id' => ['required'],
			'type' => ['required','in:daily,monthly'],
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

		if($this->ili()){
			$res['success'] = true;
			$update_msg('starting the procedure');
			$reslt = false;
			$cashreport = null;

			$c_type = $passed['type'];
			$c_id = $passed['id'];

			$source = $c_type == 'daily' ? new DailyCashReport() : new MonthlyCashReport();

			$cashreport = $source->where('id',$c_id)->with('mycreator')->first();

			if($cashreport == null){
				$_msg("invalid $c_type cash report");
			} else {
				$_msg("$c_type cash report found");
				$reslt = true;
			}

			$res['result'] = $reslt;
			$res['sendme'] = $cashreport;
			$res['message'] = $msg;
		}

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
		// $alldata = $req->all();
		$msg = "didnt even get to start";

		/*
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

		if($this->ili() || $iscalled){
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
			$carry_over_rec = null;
			$haslast = false;

			if($myrange == null || strtolower("$myrange") == 'today'){
				$lastrec = $lastrec
					->latest()
					->first()
				;
				$haslast = !($lastrec == null);
			} else {
				$lastrec = $lastrec
					->timeRange($myrange)
					->orderBy('created_at','desc')
					->first()
				;
				$r = $this->getPreviousDay($myrange);
				$_rlg(['prevdata' => $r]);
				// $redone_range = $r['start'] ?? null;
				$redone_range = $r['start'];
				$carry_over_rec = null;

				if($lastrec !== null){
					$carry_over_rec = $lastrec
						->timeRange($redone_range)
						->orderBy('created_at','desc')
						->first()
					;
				}

				$dbg = [
					'prev_date' => $redone_range,
					'carry_over_rec' => $carry_over_rec == null ? $carry_over_rec : $carry_over_rec->toArray(),
				];
				$_rlg($dbg);
				$haslast = !($carry_over_rec == null);
			}

			$_rlg($lastrec);
			$timediff = null;

			if($lastrec !== null){
				$timediff = $lastrec->created_at->diffInHours(now());
			}

			$_rlg(['timediff' => $timediff]);
			$wegood = $timediff == null ? true : $timediff > 23;

			if(true){
				$_msg('you can make a daily report for today');
				$t_range = $myrange == null ? 'today' : $myrange;

				// calculate carry over cash
				$carry_over = 0;

				if($carry_over_rec == null || ($myrange == null || strtolower("$myrange") == 'today')){
					$carry_over = $haslast ? $lastrec->total_balance_forward : 0;
				} else {
					$carry_over = $haslast ? $carry_over_rec->total_balance_forward : 0;
				}

				$_msg('carry over calculated');

				// calculate sample counts
				$samples = Sample::query()
					->where('id','<>',null)
					->timeRange($t_range)
				;

				// get total income
				if($haslast){
					if(self::isDate($t_range)){
						$payemnts = Payment::query()
							->where('id','<>',null)
							->timeRange($t_range)
						;
					} else {
						$payemnts = Payment::query()
							->where('id','<>',null)
							->timeRange($t_range)
						;
					}
					$_rlg('added payment filter');
				} else {
					if(self::isDate($t_range)){
						$payemnts = Payment::query()
							->where('id','<>',null)
							->timeRange($t_range)
						;
					} else {
						$payemnts = Payment::query()
							->where('id','<>',null)
							->timeRange($t_range)
						;
					}
					$_rlg('ignored payment filter');
				}

				// if(isset($payemnts)){
					$_rlg(['payments gotten' => (clone $payemnts)->get()]);
				// }

				$total_income = (clone $payemnts)->where('payment_type','task')->sum('amount');
				$cash_from_debtors = (clone $payemnts)->where('payment_type','invoice')->orWhere('payment_type','debt_payment')->sum('amount');
				$total_daily_cash = $total_income + $cash_from_debtors;
				$total_cash = $total_daily_cash + $carry_over;
				// $_rlg(['payments sum' => $payemnts]);
				$_msg("calculated gross income");

				// get total expenses
				$expenses = Expense::query()
					->where('id','<>',null)
				;

				$expenses = $expenses->timeRange($t_range);

				$expenditure = (clone $expenses)->sum('amount');
				$total_balance_forward = $total_cash - $expenditure;
				$total_profit = $total_balance_forward - $carry_over;
				$_msg("calculated expenses");

				// get samples data
					$mysamples = (clone $samples)->sum('sample_count');
					$soil_samples = (clone $samples)->where('sample_type','soil')->sum('sample_count');
					$rock_samples = (clone $samples)->where('sample_type','rock')->sum('sample_count');
					$solution_samples = (clone $samples)->where('sample_type','solution')->sum('sample_count');
					$carbon_samples = (clone $samples)->where('sample_type','carbon')->sum('sample_count');
					$carbon_act_samples = (clone $samples)->where('sample_type','carbon activity')->sum('sample_count');
					if(isset($r['tym'])){
						$monthly_samples = Sample::query()->whereBetween('created_at', [
							$r['tym']->startOfMonth()->startOfDay(),
							$r['tym']->startOfMonth()->endOfDay(),
						])->sum('sample_count');
					} else {
						$monthly_samples = Sample::timeRange('this_month')->sum('sample_count');
					}
					$_msg('samples data fetched');

					$sampledata = [
						'soil' => $soil_samples,
						'rock' => $rock_samples,
						'solution' => $solution_samples,
						'carbon' => $carbon_samples,
						'carbon activity' => $carbon_act_samples,
						'monthly' => $monthly_samples,
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
					$spend_data = [];
					$new_spend_data = Expense::query()->timeRange($t_range)
						->selectRaw('purpose, SUM(amount) as total_amount')
						->groupBy('purpose')
						->get()
					;
					$_rlg(['spend recs' => $new_spend_data]);

					$new_spend_data = $new_spend_data->map(function($expense) {
						return [
							"p" => $expense->purpose,
							"amt" => $expense->total_amount,
						];
					})->toArray();

					foreach($new_spend_data as $nsd){
						$spend_data[$nsd["p"]] = $nsd["amt"];
					}

				// get debtors
					$d_clients = Client::query()->whereHas('tasks',function($q) use($t_range){$q->timeRange($t_range)->where('payment_status','<>',2);})->where('credit_balance','>',0);
					$total_debit = (clone $d_clients)->sum('credit_balance');
					$guys = (clone $d_clients)->get();
					$debtors = [];

					// figure out how many unpaid invoices each debtor has
					foreach($guys->toArray() as $dd){
						$tmg = $dd;
						$bal = Task::query()->timeRange($t_range)->where('client_id',$dd['id'])->sum('balance');
						$tmg['credit_balance'] = $bal;
						$tmg['invoices_count'] = Invoice::query()->where('client_id',$tmg['id'])->where('status','<>',2)->count();

						if($bal > 0){
							$debtors[] = $tmg;
						}
					}

				// get debt payment data
					$d_payments = Payment::query()
						->where('payment_type', 'invoice')
						->orWhere('payment_type', 'debt_payment')
						->timeRange($t_range)
						->selectRaw('client_id, SUM(amount) as total_payment')
						->groupBy('client_id')
						->get();

					$debt_payments = [];

					foreach($d_payments as $invoice) {
						$cquery = Client::where('id',$invoice->client_id);
						$client = (clone $cquery)->first();
						$unpaid_invoices = Invoice::where('client_id',$client->id)->where('status','<>',2)->count();

						if ($client == null) {
							$update_runlog('invalid client payment found');
						} else {
							$tmgg = (clone $cquery)->first()->toArray();
							$debt_payments[] = [
								...$tmgg,
								'invoices_count' => $unpaid_invoices,
								'payment_amount' => $invoice->total_payment,
							];
						}
					}

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

					$sampledata = [
						...$sampledata,
						'paid_tasks' => $paid_tasks,
						'unpaid_tasks' => $unpaid_tasks,
					];

				$outdata = [
					...$outdata,
					'time_difference' => $timediff,
					'carry_over' => floatval($carry_over),
					'total_owed' => $total_debit,
					'total_income' => $total_income,
					'cash_from_debtors' => $cash_from_debtors,
					'total_daily_cash' => $total_daily_cash,
					'total_cash' => $total_cash,
					'total_balance_forward' => $total_balance_forward,
					'total_profit' => $total_profit,
					'expenditure' => $expenditure,

					'sample_data' => $sampledata,
					'cash_data' => $cash_data,
					'spend_data' => $spend_data,
					'spend_data_new' => $new_spend_data,
					'debtors' => $debtors,
					'debt_pay_data' => $debt_payments,

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

	public function save_me(Request $req,$iscalled = false){
		$res = $this->dftres;
		// $alldata = $req->all();
		$msg = "didnt even get to start";

		// /*
		$passed = $iscalled ?
			["rptdata" => []]
			:
			$req->validate([
				'rptdata' => ['required'],
			])
		;
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

		if($this->ili() || $iscalled){
			$res['success'] = true;
			$update_msg('starting the procedure');
			$reslt = false;
			$user = $this->cur_user();

			$toadd = $passed['rptdata'];
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
				// $_rlg(['toadd' => $toadd]);

				$cnt = DailyCashReport::where('id','<>',null)->count();
				$num = str_pad("$cnt",4,'0',STR_PAD_LEFT);
				// unset($toadd['carry_over']);
				$toadd['added_by'] = $iscalled ? 0 : $user->id;
				$toadd['cash_report_no'] = "CRPT_D_{$num}";
				$_rlg($toadd);
				$created = null;
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

		if($iscalled){
			return $res;
		}
		return response()->json($res);
	}

	public function save_me_monthly(Request $req,$iscalled = false){
		$res = $this->dftres;
		$msg = "didnt even get to start";

		// /*
		$passed = $iscalled ?
			["rptdata" => []]
			:
			$req->validate([
				'rptdata' => ['required'],
			])
		;
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

		if($this->ili() || $iscalled){
			$res['success'] = true;
			$update_msg('starting the procedure');
			$reslt = false;
			$user = $this->cur_user();

			$toadd = $passed['rptdata'];
			// $toadd['added_by'] = $iscalled ? 0 : $user->id;
			$_msg('adding daily report');

			$lastrec = MonthlyCashReport::query()
				->where('id','<>',null)
				->latest()
				->first()
			;
			$timediff = null;

			if($lastrec !== null){
				$timediff = $lastrec->created_at->diffInDays(now());
			}

			$_rlg(['timediff' => $timediff]);
			$today = Carbon::now();
			$wegood = $timediff == null ? true : $timediff > 28;

			if(!$wegood){
				$_msg('You can only generate a monthly report once every month');
			} else {
				$_msg('saving monthly report');
				$toadd = $this->get_for_today($req,true,$res['runlog']);
				$cnt = MonthlyCashReport::where('id','<>',null)->count();
				$num = str_pad("$cnt",4,'0',STR_PAD_LEFT);
				$toadd['added_by'] = $iscalled ? 0 : $user->id;
				$toadd['cash_report_no'] = "CRPT_M_{$num}";
				$_rlg($toadd);
				$created = null;
				$created = MonthlyCashReport::create($toadd);

				if($created == null){
					$_msg('error creating record');
				} else {
					$reslt = true;
					$_msg('Monthly Report saved successfully');
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

		if($iscalled){
			return $res;
		}
		return response()->json($res);
	}

	// recreator setup
	public function recreate_from_date(Request $req,$iscalled = false,$passdate = ''){
		// recreates reports from a specified date till the present, might be very slow if starting from a far date
		$res = $this->dftres;
		$alldata = $req->all();
		$msg = "didnt even get to start";

		// /*
		$passed = $req->validate([
				'start_date' => ['required'],
			])
		;
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

		if($this->ili()){
			$res['success'] = true;
			$update_msg('starting the procedure');
			$reslt = false;
			$outdata = null;

			// find out how many days we are regenerating
			$thadate = $passed['start_date'];
			$isdate = self::isDate($thadate);

			if(!($isdate)){
				$_msg('invalid date passed');
			} else {
				// do shit
				$_msg('good date');
				$taday = Carbon::today();
				$day = Carbon::parse($thadate);
				$gap = $taday->diffInDays($day);

				$outdata = ['gap' => $gap];

				if($gap > -1){
					$_msg('you cant make a report for today onwards');
				} else {
					$_msg("the gap is $gap days");
					$_msg('you can make a report');

					$startday = $day->subDays($gap);
					$update_runlog(['start_date' => $startday->toDateString()]);

					for($n = 1;$n < ($gap * -1);$n++){
						// $myday = $day
						// $affected_recs = DailyCashReport::query()->where('created_at',$myday)->get();
						// $update_runlog(['affected records' => $affected_recs]);
					}
				}
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

		return response()->json($res);
	}
}
