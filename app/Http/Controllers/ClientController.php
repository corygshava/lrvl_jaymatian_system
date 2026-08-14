<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ClientController extends Controller{
	public function get_everything(Request $req,$iscalled = false){
		$res = $this->dftres;
		$alldata = $req->all();

		if($this->ili() || $iscalled){
			$res['success'] = true;
			$res['message'] = "all clients retrieved";

			$perPage = $req->input('per_page', 10);
			$search = $req->input('search',null);
			$sortBy = $req->input('sort_by', 'created_at');
			$sortDirection = $req->input('sort_direction', 'desc');

			// filters
			$payment_plan = $req->input('payment_plan',null);
			$client_gender = $req->input('client_gender',null);
			$has_credit_balance = $req->input('with_credit_balance',null);
			$invoice_status = $req->input('has_invoice',null);

			$search = $search == '*' ? null : $search;

			$res['runlog'][] = [
				"perPage" => $perPage,
				"search" => $search,
				"sortBy" => $sortBy,
				"sortDirection" => $sortDirection,
				"payment_plan" => $payment_plan,
				"client_gender" => $client_gender,
				"credit_balance" => $has_credit_balance,
			];

			$clients = Client::query();

			$sortBy = $sortBy == null || $sortBy == '' ? 'created_at' : $sortBy;
			$sortBy = ($sortBy == 'role_name') ? "user_roles_id" : $sortBy;

			$clients = $clients
				->where('id', '<>', null)
				->withCount('samples')
				->withCount('payments')
				->withCount('reports')
				->withCount('tasks')
				->withSum('payments','amount')
				->with('mycreator')
				->get()
				// ->orderBy($sortBy, $sortDirection)
				// ->paginate($perPage)
			;

			/*
			for($i = 0;$i < count($users);$i++){
				$users[$i]->role_name = $users[$i]->myRoles?->role_name;
				$users[$i]->role_permissions = $users[$i]->myRoles?->role_permissions;
			}
			*/

			$res['result'] = true;
			$res['sendme'] = $clients;
		}

		// dd($res);

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		if($iscalled){
			return $res['sendme'];
		} else {
			return response()->json($res);
		}
	}

    public function get_all(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		if($this->ili()){
			$res['success'] = true;
			$res['message'] = "all clients retrieved";

			$perPage = $req->input('per_page', 10);
			$search = $req->input('search',null);
			$sortBy = $req->input('sort_by', 'created_at');
			$sortDirection = $req->input('sort_direction', 'desc');

			// filters
			$payment_plan = $req->input('payment_plan',null);
			$client_gender = $req->input('client_gender',null);
			$has_credit_balance = $req->input('with_credit_balance',null);
			$invoice_status = $req->input('has_invoice',null);
			$t_range = $req->input('timerange',null);

			$search = $search == '*' ? null : $search;

			$res['runlog'][] = [
				"perPage" => $perPage,
				"search" => $search,
				"sortBy" => $sortBy,
				"sortDirection" => $sortDirection,
				"payment_plan" => $payment_plan,
				"client_gender" => $client_gender,
				"credit_balance" => $has_credit_balance,
			];

			$clients = Client::query();

			if($search){
				$clients = $clients
					->where('client_name', 'like', "%{$search}%")
					->orWhere('client_contact', 'like', "%{$search}%")
					->orWhere('id_number', 'like', "%{$search}%")
					->orWhere('kra_pin', 'like', "%{$search}%");

				$res['runlog'][] = "added search filter";
			}

			// filter for payment plans
			if($payment_plan){
				$clients = $clients->where('payment_plan',$payment_plan);
			}

			// filter for activity
			if($client_gender){
				$clients = $clients->where('client_gender',$client_gender);
			}

			// filter for credit balance
			if($has_credit_balance){
				$con = $has_credit_balance == 'yes' ? '>' : '=';
				$clients = $clients->where('credit_balance',$con,0);
			}

			if($invoice_status){
				$invoice_status = strtolower($invoice_status);

				switch($invoice_status){
					case 'none':
						$clients = $clients->doesntHave('invoices');
						break;
					case 'paid':
						$clients = $clients->whereHas('invoices',function($q) {
							$q->where('status',2);
						});
						break;
					case 'unpaid':
						$clients = $clients->whereHas('invoices',function($q) {
							$q->where('status','<>',2);
						});
						break;
					default:
						$clients = $clients->has('invoices');
						break;
				}
			}

			if($t_range){
				$clients = $clients->timeRange($t_range);
			}

			$sortBy = $sortBy == null || $sortBy == '' ? 'created_at' : $sortBy;
			$sortBy = ($sortBy == 'role_name') ? "user_roles_id" : $sortBy;

			$clients = $clients
				->withCount('samples')
				->withCount('payments')
				->withCount('reports')
				->withCount('tasks')
				->withSum('payments','amount')
				->with('mycreator')
				->orderBy($sortBy, $sortDirection)
				->paginate($perPage)
			;

			/*
			for($i = 0;$i < count($users);$i++){
				$users[$i]->role_name = $users[$i]->myRoles?->role_name;
				$users[$i]->role_permissions = $users[$i]->myRoles?->role_permissions;
			}
			*/

			$res['result'] = true;
			$res['sendme'] = $clients;
		}

		// dd($res);

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}

	public function add_edit(Request $req,$iscalled = false,$p_user = null){
		// return $this->log_my_payload($req);

		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			'client_name' => ['required','string'],
			'client_contact' => ['required','string'],
			'client_gender' => ['required','string'],
			'payment_plan' => ['required','string'],
		]);
		$recid = isset($alldata['recid']) ? $alldata['recid'] : null;

		$praw = $req->input('bizdata_pricing','[]');
		$pdata = json_decode($praw,true);
		// */

		if($this->ili() || $iscalled){
			$res['success'] = true;
			$msg = "starting procedure";
			$reslt = false;

			$user = $p_user == null ? $this->cur_user() : $p_user;
			$uid = $user->id;
			$client_name = strip_tags($passed['client_name']);
			$client_contact = strip_tags($passed['client_contact']);
			$client_gender = strip_tags($passed['client_gender']);
			$payment_plan = strip_tags($passed['payment_plan']);
			$kra_pin = strip_tags($alldata['kra_pin']);
			$id_number = strip_tags($alldata['id_number']);

			$exist = Client::query()->where('client_contact',$client_contact)->exists();
			$changed = null;
			$tosend = [
				'client_name' => $client_name,
				'client_contact' => $client_contact,
				'client_gender' => $client_gender,
				'payment_plan' => $payment_plan,
				'id_number' => $id_number,
			];

			if($kra_pin){
				$tosend['kra_pin'] = $kra_pin;
			}
			if($id_number){
				$tosend['id_number'] = $id_number;
			}

			// return response()->json(['rid' => $recid]);

			if($recid === null){
				if(!$exist){
					$tosend['added_by'] = $uid;
					$tosend['payment_data'] = $pdata;
					$changed = Client::create($tosend);

					$reslt = true;
					$msg = "the new Client was added successfully";
				} else {
					$msg = "phone number is already taken";
				}
			} else {
				$changed = Client::query()->where('id',$recid)->first();
				if($changed == null){
					$msg = "invalid client";
				} else {
					$exist = Client::query()->where('client_contact',$client_contact)->where('id','<>',$recid)->exists();

					if($exist){
						$msg = 'the phone number is already taken';
					} else {
						$changed->update($tosend);
						// $changed->refresh();

						$changed = Client::where('id',$recid)
							->withCount('samples')
							->withCount('payments')
							->withCount('reports')
							->withCount('tasks')
							->withSum('payments','amount')
							->with('mycreator')
							->withCount('tasks')
							->first()
						;

						$reslt = true;
						$msg = "Client updated successfully";
					}
				}
			}

			// return response()->json(['changed' => $changed,'perms' => $perms]);

			$res['result'] = $reslt;
			$res['message'] = $msg;
			$res['sendme'] = $changed;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		if($iscalled){
			return $res;
		} else {
			return response()->json($res);
		}
	}

	/*
	public function change_credit(Request $req){
		// return $this->log_my_payload($req);
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			'client_name_2' => ['required'],
			'user_credit' => ['required','numeric'],
			'admin_password' => ['required'],
			'credit_amt' => ['required','numeric'],
			'credit_action' => ['required'],
		]);
		$recid = $req->input('recid',null);
		// * /


		if($this->ili()){
			$res['success'] = true;

			$msg = "changing credit balance";
			$reslt = false;
			$target = null;
			// $user = Auth::user();

			$vrf = $this->verifyPassword($passed['admin_password']);

			if(!$vrf){
				$msg = "invalid admin password";
			} else {
				$c_amt = $passed['credit_amt'];
				$c_act = $passed['credit_action'];
				$c_bal = $passed['user_credit'];

				$msg = "valid password";

				$target = Client::where('id',$recid)->with('mycreator')->first();

				if($target == null){
					$msg = "invalid identifier";
				} else {
					$bal = $target->credit_balance;
					$newbal = $bal;
					$keepgoing = false;

					if(!($bal == $c_bal)){
						$msg = "balance mismatch";
						$res['runlog'][] = $msg;
					} else {
						$keepgoing = true;
						$act = Str::upper($c_act);

						if($act == "ADD"){
							$newbal = $bal + $c_amt;
						} elseif($act == "SET") {
							$newbal = $c_amt;
						} elseif($act == "SUBTRACT" || $act == "MINUS"){
							$newbal = $bal - $c_amt;
						} else {
							$msg = "invalid credit action";
							$keepgoing = false;
						}
					}

					if($newbal < 0){
						$msg = "the balance will be less than zero, try again";
						$keepgoing = false;
					}

					if($keepgoing){
						$target->update([
							'credit_balance' => $newbal,
						]);
						$target->refresh();
						$msg = "credit updated successfully";
					}

					$reslt = $keepgoing;
				}
			}

			$res['result'] = $reslt;
			$res['message'] = $msg;
			$res['sendme'] = $target;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}
	// */

	public function get_one(Request $req){
		// return $this->log_my_payload($req);

		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			'id' => ['required']
		]);
		// */


		if($this->ili()){
			$res['success'] = true;
			$msg = "fetching permissions";
			$reslt = false;
			$recid = $passed['id'];

			$task = Client::where('id',$recid)
				->withCount('samples')
				->withCount('payments')
				->withCount('reports')
				->withCount('tasks')
				->withSum('payments','amount')
				->with('mycreator')
				->withCount('tasks')
				->first()
			;

			if($task == null){
				$msg = "We couldnt find the specified client";
			} else {
				$msg = "client loaded";
				$reslt = true;
			}

			$res['result'] = $reslt;
			$res['sendme'] = $task;
			$res['message'] = $msg;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}
	public function get_by_ids(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();
		$msg = "didnt even get to start";
		$out = null;

		// /*
		$passed = $req->validate([
			'client_ids' => ['required','array'],
			'client_ids.*' => ['integer','exists:clients,id'],
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
		if($this->ili()){
			$res['success'] = true;
			$_msg('starting the procedure');
			$reslt = false;

			$out = Client::query()->whereIn('id', $passed['client_ids'])
				->withCount(['tasks as unpaid_tasks_count' => function($q){
					$q->where('balance','>',0);
				}])
				->with(['tasks' => function($q){
					$q->where('balance','>',0);
				}])
				->get();
			$reslt = true;

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

	// get debtors
	public function get_all_debtors(Request $req,$iscalled = false){
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
			// $reslt = $mdta >= 7;

			// calculate total owed
			$_msg('fetching clients with unpaid samples');
			$d_clients = Client::query()->whereHas('tasks', function($q) use($t_range){
					if($t_range != null){
						$q->timeRange($t_range)->where('payment_status','<>',2);
						return;
					}
					$q->where('payment_status','<>',2);
				})->where('credit_balance','>',0)
				->withCount(['tasks as unpaid_tasks_count' => function($q){
					$q->where('balance','>',0);
				}])
				->with(['tasks' => function($q){
					$q->where('balance','>',0);
				}]);
			;
			$total_debit = (clone $d_clients)->sum('credit_balance');
			$guys = (clone $d_clients)->get();

			$out = [
				// ...$out,
				'total_owed' => $total_debit,
				'debtors' => $guys,
			];
			$_msg('all debtors loaded');

			$res['result'] = true;
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
    public function get_debtors(Request $req){
		$res = $this->dftres;
		$msg = "didnt even get to start";
		$alldata = $req->all();
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
		if($this->ili()){
			$res['success'] = true;
			$_msg("trying to get debtors");

			$perPage = $req->input('per_page', 10);
			$search = $req->input('search',null);
			$sortBy = $req->input('sort_by', 'credit_balance');
			$sortDirection = $req->input('sort_direction', 'desc');

			// filters
			$payment_plan = $req->input('payment_plan',null);
			$client_gender = $req->input('client_gender',null);
			$has_credit_balance = $req->input('with_credit_balance',null);
			$invoice_status = $req->input('has_invoice',null);
			$t_range = $req->input('timerange',null);

			$search = $search == '*' ? null : $search;

			$res['runlog'][] = [
				"perPage" => $perPage,
				"search" => $search,
				"sortBy" => $sortBy,
				"sortDirection" => $sortDirection,
				"payment_plan" => $payment_plan,
				"client_gender" => $client_gender,
				"credit_balance" => $has_credit_balance,
			];

			$clients = Client::query()->whereHas('tasks', function($q) use(&$_msg){
					$q->where('balance','>',0);
				})
				->withCount(['tasks as unpaid_tasks_count' => function($q){
					$q->where('balance','>',0);
				}])
				->with(['tasks' => function($q){
					$q->where('balance','>',0);
				}]);
			;

			if($search){
				$clients = $clients
					->where('client_name', 'like', "%{$search}%")
					->orWhere('client_contact', 'like', "%{$search}%")
					// ->orWhere('id_number', 'like', "%{$search}%")
					// ->orWhere('kra_pin', 'like', "%{$search}%")
				;

				$_msg("added search filter");
			}

			// filter for gender
			if($client_gender){
				$clients = $clients->where('client_gender',$client_gender);
				$_msg("added gender filter");
			}

			// filter for credit balance
			if($has_credit_balance){
				$con = $has_credit_balance == 'yes' ? '>' : '=';
				$clients = $clients->where('credit_balance',$con,0);
			}

			if($invoice_status){
				$invoice_status = strtolower($invoice_status);
				$_msg('invoice filter');

				switch($invoice_status){
					case 'none':
						$clients = $clients->doesntHave('invoices');
						$_msg('showing ones without invoices');
						break;
					case 'paid':
						$clients = $clients->whereHas('invoices',function($q) {
							$q->where('status',2);
						});
						$_msg('showing ones with paid invoices');
						break;
					case 'unpaid':
						$clients = $clients->whereHas('invoices',function($q) {
							$q->where('status','<>',2);
						});
						$_msg('showing ones with unpaid invoices');
						break;
					default:
						$clients = $clients->has('invoices');
						$_msg('showing ones with invoices');
						break;
				}
			}

			if($payment_plan){
				$clients = $clients->where('payment_plan',$payment_plan);
				$_msg("added payment plan filter");
			}

			if($t_range){
				$clients = $clients->timeRange($t_range);
			}

			$sortBy = $sortBy == null || $sortBy == '' ? 'created_at' : $sortBy;
			$sortBy = ($sortBy == 'role_name') ? "user_roles_id" : $sortBy;

			$clients = $clients
				->withCount('samples')
				->withCount('payments')
				->withCount('reports')
				->withCount('tasks')
				->withCount('invoices')
				->withSum('payments','amount')
				->with('mycreator')
				->orderBy($sortBy, $sortDirection)
				->paginate($perPage)
			;
			$out = $clients;

			/*
			for($i = 0;$i < count($users);$i++){
				$users[$i]->role_name = $users[$i]->myRoles?->role_name;
				$users[$i]->role_permissions = $users[$i]->myRoles?->role_permissions;
			}
			*/

			$_msg("debtors fetched");
			$res['result'] = true;
			$res['sendme'] = $out;
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
    public function get_debtors_stats(Request $req){
		$res = $this->dftres;
		$msg = "didnt even get to start";
		$alldata = $req->all();
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

			$stats = [];
			$_statme = function(array $s ) use (&$stats){
				$dvl = rand(0,500);
				$dft = [
					"value" => $dvl,
					"valtype" => 'number',			// either number, string, date or plain
					"label" => "Total Debtors",
					"type" => $dvl > 250 ? "success" : ($dvl > 200 ? "primary" : "danger"),
					"icon" => "defined:debtors"
				];
				$stats[] = [...$dft,...$s];
			};

		// ops begin
		if($this->ili()){
			$res['success'] = true;

			$_msg("trying to get debtors");

			$perPage = $req->input('per_page', 10);
			$search = $req->input('search',null);
			$sortBy = $req->input('sort_by', 'credit_balance');
			$sortDirection = $req->input('sort_direction', 'desc');

			// filters
			$payment_plan = $req->input('payment_plan',null);
			$client_gender = $req->input('client_gender',null);
			$has_credit_balance = $req->input('with_credit_balance',null);
			$invoice_status = $req->input('has_invoice',null);
			$t_range = $req->input('timerange',null);

			$search = $search == '*' ? null : $search;

			$res['runlog'][] = [
				"perPage" => $perPage,
				"search" => $search,
				"sortBy" => $sortBy,
				"sortDirection" => $sortDirection,
				"payment_plan" => $payment_plan,
				"client_gender" => $client_gender,
				"credit_balance" => $has_credit_balance,
			];

			$clients = Client::query()->whereHas('tasks', function($q) use(&$_msg){
				$q->where('balance','>',0);
			});

			if($search){
				$clients = $clients
					->where('client_name', 'like', "%{$search}%")
					->orWhere('client_contact', 'like', "%{$search}%")
					->orWhere('id_number', 'like', "%{$search}%")
					->orWhere('kra_pin', 'like', "%{$search}%");

				$_msg("added search filter");
			}

			// filter for gender
			if($client_gender){
				$clients = $clients->where('client_gender',$client_gender);
				$_msg("added gender filter");
			}

			// filter for credit balance
			if($has_credit_balance){
				$con = $has_credit_balance == 'yes' ? '>' : '=';
				$clients = $clients->where('credit_balance',$con,0);
			}

			if($invoice_status){
				$invoice_status = strtolower($invoice_status);
				$_msg('invoice filter');

				switch($invoice_status){
					case 'none':
						$clients = $clients->doesntHave('invoices');
						$_msg('showing ones without invoices');
						break;
					case 'paid':
						$clients = $clients->whereHas('invoices',function($q) {
							$q->where('status',2);
						});
						$_msg('showing ones with paid invoices');
						break;
					case 'unpaid':
						$clients = $clients->whereHas('invoices',function($q) {
							$q->where('status','<>',2);
						});
						$_msg('showing ones with unpaid invoices');
						break;
					default:
						$clients = $clients->has('invoices');
						$_msg('showing ones with invoices');
						break;
				}
			}

			if($payment_plan){
				$clients = $clients->where('payment_plan',$payment_plan);
				$_msg("added payment plan filter");
			}

			if($t_range){
				$clients = $clients->timeRange($t_range);
			}

			$sortBy = $sortBy == null || $sortBy == '' ? 'created_at' : $sortBy;
			$sortBy = ($sortBy == 'role_name') ? "user_roles_id" : $sortBy;

			$debtors = (clone $clients);
			$clients = $clients
				->withCount('samples')
				->withCount('payments')
				->withCount('reports')
				->withCount('tasks')
				->withCount('invoices')
				->withSum('payments','amount')
				->with('mycreator')
				->orderBy($sortBy, $sortDirection)
				->paginate($perPage)
			;

			// the prev was left in case i feel like attaching the filters to stats too
			// stat of stats processing, get it :)
			$debtors = Client::query()->whereHas('tasks', function($q) use($t_range){
				$q->where('balance','<>',0);
			});
			$allclients = Client::query()->where('id','<>',null);

			// total debtors
			$statval = (clone $debtors)->count();
			$thresh = [50, 70];
			$_statme(
				[
					"value" => $statval,
					"label" => "Total Debtors",
					"type" => $statval < $thresh[1] ? "success" : ($statval < $thresh[0] ? "primary" : "danger"),
					"icon" => "defined:debtors"
				]
			);

			// total client credit
			$statval = (clone $allclients)->sum('credit_balance');
			$thresh = [500000, 200000];
			$_statme(
				[
					"value" => $statval,
					"label" => "Total client credit",
					"type" => $statval < $thresh[1] ? "success" : ($statval < $thresh[0] ? "primary" : "danger"),
					"icon" => "defined:invoices"
				]
			);

			/*
			// total client credit
			$statval = Task::query()->where('balance','<>',0)->sum('balance');
			$thresh = [500000, 200000];
			$_statme(
				[
					"value" => $statval,
					"label" => "Total Balance",
					"type" => $statval < $thresh[1] ? "success" : ($statval < $thresh[0] ? "primary" : "danger"),
					"icon" => "defined:debtors"
				]
			);
			*/

			// total client credit
			$statval = Task::query()->where('balance','<>',0)->sum('balance');
			$thresh = [500000, 200000];
			$_statme(
				[
					"value" => $statval,
					"label" => "Total Unpaid Balance",
					"type" => $statval < $thresh[1] ? "success" : ($statval < $thresh[0] ? "primary" : "danger"),
					"icon" => "defined:debtors"
				]
			);

			$statval = (clone $allclients)->where('credit_balance','>',0)->min('credit_balance');
			$thresh = [10000, 5000];
			$_statme(
				[
					"value" => $statval,
					"label" => "Lowest client credit",
					"type" => $statval < $thresh[1] ? "success" : ($statval < $thresh[0] ? "primary" : "danger"),
					"icon" => "defined:invoices"
				]
			);

			$statval = (clone $allclients)->max('credit_balance');
			$thresh = [50000, 100000];
			$_statme(
				[
					"value" => $statval,
					"label" => "highest client credit",
					"type" => $statval < $thresh[1] ? "success" : ($statval < $thresh[0] ? "primary" : "danger"),
					"icon" => "defined:invoices"
				]
			);

			// total client credit
			$statval = Task::query()->where('balance','<>',0)->timeRange('today')->sum('balance');
			$thresh = [50000, 100000];
			$_statme(
				[
					"value" => $statval,
					"label" => "Total Unpaid Today",
					"type" => $statval < $thresh[0] ? "success" : ($statval < $thresh[1] ? "primary" : "danger"),
					"icon" => "defined:debtors"
				]
			);

			$out = $stats;

			/*
			for($i = 0;$i < count($users);$i++){
				$users[$i]->role_name = $users[$i]->myRoles?->role_name;
				$users[$i]->role_permissions = $users[$i]->myRoles?->role_permissions;
			}
			*/

			$_msg("debtors fetched");
			$res['result'] = true;
			$res['sendme'] = $out;
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

	public function edit_payment(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			'recid' => ['required'],
			'price_soil' => ['required'],
			'price_rock' => ['required'],
			'price_solution' => ['required'],
			'price_carbon' => ['required'],
			'price_carbon_activity' => ['required'],
		]);
		// */

		if($this->ili()){
			$res['success'] = true;
			$msg = "fetching permissions";
			$reslt = false;
			$recid = $passed['recid'];

			$client = Client::where('id',$recid)
				->withCount('samples')
				->withCount('payments')
				->withCount('reports')
				->withCount('tasks')
				->withSum('payments','amount')
				->with('mycreator')
				->withCount('tasks')
				->first()
			;

			if($client == null){
				$msg = "invalid client";
			} else {
				$msg = "client found";

				$client->payment_data = [
					'Soil' => $passed['price_soil'],
					'Rock' => $passed['price_rock'],
					'Solution' => $passed['price_solution'],
					'Carbon' => $passed['price_carbon'],
					'Carbon activity' => $passed['price_carbon_activity'],
				];
				$client->save();
				$reslt = true;
				$msg = "payment data saved successfully";
			}

			$res['result'] = $reslt;
			$res['sendme'] = $client;
			$res['message'] = $msg;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}

	public function standardize_all(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			'admin_password' => ['required'],
			'paydata' => ['required','array'],
		]);
		// */

		$update_runlog = function($wot) use (&$res){
			$res['runlog'][] = $wot;
		};

		if($this->ili()){
			$res['success'] = true;
			$msg = "fetching permissions";
			$update_runlog($msg);
			$reslt = false;

			$pw = $passed['admin_password'];
			$pdata = $passed['paydata'];

			if(!($this->verifyPassword($pw))){
				$msg = "invalid admin password";
				$update_runlog($msg);
			} else {
				$msg = "valid password";
				$update_runlog($msg);

				Client::where('id','<>',null)->update([
					'payment_data' => $pdata,
				]);

				$msg = "clients standardized";
				$update_runlog($msg);
				$reslt = true;
			}

			$res['result'] = $reslt;
			$res['sendme'] = true;
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
