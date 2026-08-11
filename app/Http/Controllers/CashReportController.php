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
use Illuminate\Support\Facades\DB;
use Mavinoo\Batch\Batch;

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

	public function get_for_today(Request $req,bool $iscalled = false,array &$errlog = [],$passed_range = null){
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

			if($myrange == null){
				if($passed_range != null){
					$myrange = $passed_range;
				}
			}

			if(isset($req['use_range'])){
				$myrange = $req['use_range'];
			}

			$recs = DailyCashReport::query()
				->where('id','<>',null)
			;
			$lastrec = null;
			$carry_over_rec = null;
			$haslast = false;
			$redone_range = 'xx';
			$r = 'prev_day_data';

			$tryrecs = DailyCashReport::query()->timeRange('juzi')->count();
			$_rlg(['recs_from_juzi' => $tryrecs]);

			if($myrange == null || strtolower("$myrange") == 'today'){
				$lastrec = (clone $recs)
					->latest()
					->first()
				;
				$haslast = !($lastrec == null);
			} else {
				$lastrec = (clone $recs)
					->timeRange($myrange)
					->orderBy('created_at','desc')
					->first()
				;
				$r = $this->getPreviousDay($myrange);
				$_rlg(['prevdata' => $r]);
				// $redone_range = $r['start'] ?? null;
				$redone_range = $r['end'];

				if($lastrec !== null){
					$carry_over_rec = (clone $recs)
						->whereDate('created_at', $redone_range)
						->orderBy('created_at','desc')
						->first()
					;
				}

				$dbg = [
					'prev_date' => $redone_range,
					'carry_over_rec' => $carry_over_rec == null ? $carry_over_rec : $carry_over_rec->toArray(),
				];
				// $_rlg($dbg);
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
				// $_msg('you can make a daily report for today');
				$t_range = $myrange == null ? 'today' : $myrange;

				// calculate carry over cash
				$carry_over = 0;

				// debug stuff
				if(true){
					$_rlg([
						'prevday data' => $r,
						'redone range' => $redone_range,
						'timerange' => $t_range,
					]);

					if($haslast){
						$_msg('last record present');
					} else {
						$_msg('last record not found');
					}

					if($carry_over_rec == null){
						$_msg("carry over record is missing");
						$carry_over = $haslast ? $lastrec->total_balance_forward : 0;
					} else if ($myrange == null){
						$_msg("no range passed");
						$carry_over = $haslast ? $lastrec->total_balance_forward : 0;
					} else if (strtolower("$myrange") == 'today'){
						$_msg('generating today\'s report');
						$carry_over = $haslast ? $lastrec->total_balance_forward : 0;
					} else if($haslast) {
						$_msg('this range has a last record');

						$_rlg([
							'typ' => 'last rec',
							'date' => $lastrec->created_at->toDateString(),
							'carry over' => $lastrec->total_balance_forward,
						]);

						if($carry_over_rec == null){
							$_msg('but it doesnt have a carry over record');
							$carry_over = $lastrec->total_balance_forward;
						} else {
							$_msg('and it has a carry over record');
							$carry_over = $carry_over_rec->total_balance_forward;

							$_rlg([
								'typ' => 'carry over rec',
								'date' => $carry_over_rec->created_at->toDateString(),
								'carry over' => $carry_over_rec->total_balance_forward,
								'reworked_range' => $redone_range,
							]);
						}
					} else {
						$_msg('carry over will be 0');
						$carry_over = 0;
					}
				}

				/*
				if($carry_over_rec == null || ($myrange == null || strtolower("$myrange") == 'today')){
					$carry_over = $haslast ? $lastrec->total_balance_forward : 0;
				} else {
					$carry_over = $haslast ? $carry_over_rec->total_balance_forward : 0;
				}
				// */

				$_msg("carry over calculated as : $carry_over");

				// calculate sample counts
				$samples = Sample::query()
					->where('id','<>',null)
					->timeRange($t_range)
				;

				// get total income
				$payemnts = Payment::query()
					->where('id','<>',null)
					->timeRange($t_range)
				;

				if(false){
					if($haslast){
						$_rlg('added payment filter');

						if(self::isDate($t_range)){
							$payemnts = Payment::query()
								->where('id','<>',null)
								->timeRange($t_range)
							;

							$_rlg('valid date passed');
						} else {
							$payemnts = Payment::query()
								->where('id','<>',null)
								->timeRange($t_range)
							;
							$_rlg('valid date not passed');
						}
					} else {
						$_rlg('ignored payment filter');

						if(self::isDate($t_range)){
							$payemnts = Payment::query()
								->where('id','<>',null)
								->timeRange($t_range)
							;
							$_rlg('valid date passed');
						} else {
							$payemnts = Payment::query()
								->where('id','<>',null)
								->timeRange($t_range)
							;
							$_rlg('valid date not passed');
						}
					}
				}

				// if(isset($payemnts)){
					$_rlg(['payments gotten' => (clone $payemnts)->get()]);
				// }

				$total_income = (clone $payemnts)->where('payment_type','task')->sum('amount');
				$cash_from_debtors_0 = (clone $payemnts)->where('payment_type','invoice')->sum('amount');
				$cash_from_debtors_1 = (clone $payemnts)->where('payment_type','debt_payment')->sum('amount');
				$cash_from_debtors = $cash_from_debtors_0 + $cash_from_debtors_1;
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

				$_rlg(['value of r' => $r]);

				// get samples data
					$mysamples = (clone $samples)->sum('sample_count');
					$soil_samples = (clone $samples)->where('sample_type','soil')->sum('sample_count');
					$rock_samples = (clone $samples)->where('sample_type','rock')->sum('sample_count');
					$solution_samples = (clone $samples)->where('sample_type','solution')->sum('sample_count');
					$carbon_samples = (clone $samples)->where('sample_type','carbon')->sum('sample_count');
					$carbon_act_samples = (clone $samples)->where('sample_type','carbon activity')->sum('sample_count');
					if(isset($r['tym'])){
						$monthly_samples = Sample::query()
						->whereBetween('created_at', [
							Carbon::parse($r['tym'])->startOfMonth()->startOfDay(),
							Carbon::parse($r['end'])->addDay()->endOfDay(),
							// Carbon::parse($r['end'])->subDays(-1)->endOfDay(),
							// Self::getPreviousDay($r['tym'])['end']->subDay(-1)->endOfDay(),
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
					$total_payments_task = Payment::query()->timeRange($t_range)->where('payment_type','task')->get()->count();
					$total_payments_invoice = Payment::query()->timeRange($t_range)->where('payment_type','invoice')->get()->count();
					$total_payments_debt = Payment::query()->timeRange($t_range)->where('payment_type','debt_payment')->get()->count();
					$reports_generated = ReportsGen::query()->timeRange($t_range)->where('id','<>',null)->get()->count();
					$total_invoices = Invoice::query()->timeRange($t_range)->where('id','<>',null)->get()->count();
					$total_expenses = Expense::query()->timeRange($t_range)->where('id','<>',null)->get()->count();
					$unpaid_tasks = Task::query()->timeRange($t_range)->where('payment_status','<>',2)->sum('sample_count');
					$paid_tasks = Task::query()->timeRange($t_range)->where('payment_status',2)->sum('sample_count');

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
					'total_payments_debt_count' => $total_payments_debt,
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
				$toadd = $this->get_for_today($req, true, $res['runlog']);
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

	// get debtors
	public function get_debtors(Request $req,$iscalled = false){
		$res = $this->dftres;
		$alldata = $req->all();
		$msg = "didnt even get to start";
		$out = null;

		/*
		$passed = $req->validate([
			'condition' => ['required']
		]);
		// */

		// utils
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
				$update_runlog($w);
			};

		// ops begin
		if($this->ili() || $iscalled){
			$res['success'] = true;
			$update_msg('starting the procedure');
			$reslt = false;
			$t_range = $req->input('timerange',null);

			$cmdt = self::commondata();
			// $out = $cmdt['permissions'];
			$mdta = random_int(0,14);
			$reslt = $mdta >= 7;

			// calculate total owed
			$_msg('fetching clients with unpaid samples');
			$d_clients = Client::query()->whereHas('tasks', function($q) use($t_range){
				if($t_range != null){
					$q->timeRange($t_range)->where('payment_status','<>',2);
					return;
				}
				$q->where('payment_status','<>',2);
			})->where('credit_balance','>',0);
			$total_debit = (clone $d_clients)->sum('credit_balance');
			$guys = (clone $d_clients)->get();

			$out = [
				// ...$out,
				'total_owed' => $total_debit,
				'debtors' => $guys,
			];
			$_msg('all debtors loaded');

			$res['result'] = $reslt;
			$res['sendme'] = $out;
			$res['message'] = $msg;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}

	// recreator setup
	public function recreate_from_date(Request $req,$iscalled = false,$passdate = '',User $p_user = null){
		// recreates reports from a specified date till the present, might be very slow if starting from a far date
		$res = $this->dftres;
		$alldata = $req->all();
		$msg = "didnt even get to start";

		// /*
		$passed = $req->validate([
			'start_date' => ['required'],
			'admin_password' => ['required'],
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
		$_rlg = function($wot) use (&$update_runlog){
			$update_runlog($wot);
		};

		if($this->ili() || $iscalled){
			$res['success'] = true;
			$update_msg('starting the procedure');
			$reslt = false;
			$outdata = null;
			$user = null;
			$passed_pw = $passed['admin_password'];
			$ppw = 'nothing new';
			$verif = self::verifyPassword($passed_pw, $ppw, $user);

			if($p_user == null){
				self::cur_user();
			} else {
				$user = $p_user;
			}

			if(!$verif){
				$_msg('invalid password');
			} else {
				$_msg('valid password');
				// find out how many days we are regenerating
				$thadate = $passed['start_date'];
				$isdate = self::isDate($thadate);

				// get current number of reports
				$d_r_count = DailyCashReport::all()->count();

				if(!($isdate)){
					$_msg('invalid date passed');
				} else {
					// do shit
					$_msg('good date');
					$taday = Carbon::now();
					$day = Carbon::parse($thadate);
					$gap = $taday->diffInDays($day);

					$outdata = ['gap' => $gap];

					if($gap > -1){
						$_msg('you cant make a report for today onwards');
					} else {
						$_msg("the gap is $gap days");
						$_msg('you can make a report');

						$endday = (clone $day)->subDays($gap);
						$startday = (clone $day)->subDay();
						$update_runlog([
							'start_date' => $startday->toDateString(),
							'end_date' => $endday->toDateString(),
						]);

						$worklogs = [];
						$curdate = (clone $day)->toDateString();
						$pastdate = $startday->toDateString();
						$mekQueue = [];
						$report_cols = (new DailyCashReport())->getCols();
						$report_casts = (new DailyCashReport())->getCasts();
						$_rlg([
							'report columns' => $report_cols,
							'report casts' => $report_casts,
						]);

						$inner_log = [];

						// remove unregeneratable columns
						unset($report_cols['debtors']);

						try{
							$_rlg("started the transaction");
							DB::beginTransaction();

							// $_rlg('inside transaction');
							$counter = floor($gap * -1);
							$_rlg(['counter' => $counter]);

							for($n = 0;$n < $counter;$n++){
								$inner_log[] = [];
								$inner_log[$n][] = ("[$n] looped");

								$worklog = [
									'day_no' => $n,
									'curdate' => $curdate,
									'pastdate' => $pastdate,
								];

								$p_day = Carbon::parse($pastdate);
								$c_day = Carbon::parse($curdate);
								$p_dayrec = DailyCashReport::query()->whereDate('created_at', $p_day)->first();
								$c_dayrec = DailyCashReport::query()->whereDate('created_at', $c_day)->first();

								$arr = [];
								$gen_report = $this->get_for_today($req,true,$arr,(clone $c_day)->toDateString());

								$_rlg([
									'from gen report' => $arr,
								]);

								$worklog['prev_daily_report'] = $p_dayrec;
								$worklog['cur_daily_report'] = $c_dayrec;

								if($c_dayrec == null){
									$inner_log[$n][] = ("[$n] trying to create daily rec");
									// remove stuff not in the model
									unset($gen_report['time_difference']);
									unset($gen_report['spend_data_new']);

									$inner_log[$n][] = ("[$n] removed verbose props");

									$gen_report['added_by'] = $user->id;
									$rnm = strtoupper(self::mekRandomString(4));
									$gen_report['cash_report_no'] = "CRPT_XT_{$rnm}";
									$gen_report["created_at"] = $c_day->endOfDay()->format('Y-m-d H:i:s');
									$temp_r_cols = array_keys($gen_report);
									$addme = [];
									$finadd = [];

									$inner_log[$n][] = ("[$n] starting post processing on props");
									$cnt = 0;

									foreach($temp_r_cols as $c){
										// $inner_log[$n]["lastlog_".$cnt] = ("[$n] [$cnt] processing prop");

										if(in_array($c,$report_cols)){
											// $inner_log[$n]["lastlog_".$cnt] = ("[$n] [$cnt] valid prop ($c)");

											// /*
											if(is_null($gen_report[$c])){
												$inner_log[$n]["lastlog_".$cnt] = ("[$n] [$cnt] prop is null ($c)");
												$addme[$c] = null;
											} elseif(is_array($gen_report[$c])){
												$addme[$c] = json_encode($gen_report[$c]);
												// $inner_log[$n]["lastlog_".$cnt] = ("[$n] [$cnt] this is an array ($c)");
											} else {
												if(isset($report_casts[$c])){
													if(str_contains($report_casts[$c], 'decimal')){
														$me = $gen_report[$c];
														$nm = number_format($me, 2,'.','');
														$addme[$c] = $nm;
														$inner_log[$n]["lastlog_".$cnt] = ("[$n] [$cnt] this a decimal ($c) => $nm");
													} else {
														$inner_log[$n]["lastlog_".$cnt] = ("[$n] [$cnt] this shouldnt happen ($c)");
														$addme[$c] = $gen_report[$c];
													}
												} else {
													// $inner_log[$n]["lastlog_".$cnt] = ("[$n] [$cnt] prop is well something ($c)");
													$addme[$c] = $gen_report[$c];
												}
											}
											// */

											// $inner_log[$n]["lastlog_".$cnt] = ("[$n] [$cnt] prop preprocess complete ($c)");
										} else {
											$inner_log[$n]["lastlog_".$cnt] = ("[$n] [$cnt] invalid prop ($c)");
										}

										// $inner_log[$n]["lastlog_".$cnt] = ("[$n] [$cnt] updating counter");
										$cnt += 1;
									}

									// final postprocess
									foreach($report_cols as $c){
										$finadd[$c] = $addme[$c];
									}

									$inner_log[$n]['added_rec'] = $finadd;
									$mekQueue[] = $finadd;
									$worklog['operation'] = 'added';

									$inner_log[$n][] = ("[$n] report added to mek queue");
								} else {
									$worklog['operation'] = 'updated';
									// just modify the current record
									// TODO: activate for permanence
									$c_dayrec->update($gen_report);

									$inner_log[$n][] = ("[$n] report updated");
								}

								$worklog['gen_report'] = $gen_report;

								// $myday = $day
								// $affected_recs = DailyCashReport::query()->where('created_at',$myday)->get();
								// $update_runlog(['affected records' => $affected_recs]);

								$pastdate = (clone $p_day)->subDays(-1)->toDateString();
								$curdate = (clone $c_day)->subDays(-1)->toDateString();
								$worklogs[] = $worklog;
							}

							$_rlg("finished looping");

							$_rlg(['reports to make' => count($mekQueue)]);
							$_rlg(['worklogs' => $worklogs]);
							$_rlg(['tomek' => $mekQueue]);

							if(count($mekQueue) > 0){
								$batch = app(Batch::class);
								// TODO: activate for permanence
								$batch->insert(new DailyCashReport,$report_cols,$mekQueue);
							} else {
								$_msg("no new reports to create");
							}

							DB::commit();
							$_msg("all reports resynced");
							$reslt = true;
						} catch(\Exception $e) {
							DB::rollBack();

							$_rlg(['error' => $e]);
							$_msg("saving daily reports failed");
						}

						$_rlg(['inner log' => $inner_log]);
					}
				}

				$d_r_count_after = DailyCashReport::all()->count();
				$_rlg([
					'changes' => [
						'start_records' => $d_r_count,
						'end_records' => $d_r_count_after,
					]
				]);
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
