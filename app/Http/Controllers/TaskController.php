<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Payment;
use App\Models\Sample;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mavinoo\Batch\Batch;

class TaskController extends Controller{
    public function logsth(Request $req){
		return $this->log_my_payload($req);
	}

	// get ops
	public function get_all(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		if($this->ili()){
			$res['success'] = true;
			$msg = "all tasks retrieved";

			$perPage = $req->input('per_page', 10);
			$search = $req->input('search');
			$sortBy = $req->input('sort_by', 'created_at');
			$sortDirection = $req->input('sort_direction', 'asc');

			// filters
			$payment_status = $req->input('payment_status',null);
			$task_status = $req->input('Status',null);
			$is_paid = $req->input('is_paid',null);
			$has_reports = $req->input('has_reports',null);
			$t_range = $req->input('timerange',null);

			$res['runlog'][] = [
				"perPage" => $perPage,
				"search" => $search,
				"sortBy" => $sortBy,
				"sortDirection" => $sortDirection,
				"payment_status" => $payment_status,
				"task_status" => $task_status,
				"is paid" => $is_paid,
			];

			// return response()->json($res);

			$tasks = Task::query()
				->withCount('samples')
				->withCount('payments')
				->withCount('reports')
			;

			if($search){
				$tasks = $tasks
					->whereHas('client', function($q) use ($search){
						$q->where('client_name','like',"%{$search}%");
					})
					->orWhere('task_no', 'like', "%{$search}%")
					// ->orWhere('client_contact', 'like', "%{$search}%")
					// ->orWhere('id_number', 'like', "%{$search}%")
					// ->orWhere('kra_pin', 'like', "%{$search}%");
				;
				$res['runlog'][] = "added search filter";
			}

			// filter for payment status
			if($payment_status !== null){
				$tasks = $tasks->where('payment_status',$payment_status);
			}

			// filter for task status
			if($task_status !== null){
				$tasks = $tasks->where('status',$task_status);
			}

			// filter for whether or not its paid
			if($is_paid){
				$paycon = $is_paid == "true";
				$tasks = $tasks->where('is_paid',$paycon);
			}

			// filter for whether or not its paid
			if($has_reports){
				$hascon = $has_reports == "has";

				if($hascon){
					$tasks = $tasks->has('reports');
				} else {
					$tasks = $tasks->doesntHave('reports');
				}
			}

			if($t_range){
				$tasks = $tasks->timeRange($t_range);
			}

			$sortBy = $sortBy == null || $sortBy == '' ? 'created_at' : $sortBy;
			// $sortBy = ($sortBy == 'role_name') ? "user_roles_id" : $sortBy;

			$tasks = $tasks
				->with('creator')
				->with('client')
				->orderBy($sortBy, $sortDirection)
				->paginate($perPage);

			/*
			for($i = 0;$i < count($users);$i++){
				$users[$i]->role_name = $users[$i]->myRoles?->role_name;
				$users[$i]->role_permissions = $users[$i]->myRoles?->role_permissions;
			}
			*/

			$res['result'] = true;
			$res['sendme'] = $tasks;
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

			$task = Task::query()
				->where('id',$passed['id'])
				->withCount('samples')
				->withCount('payments')
				->withCount('reports')
				->with('creator')
				->with('client')
				->first()
			;

			if($task == null){
				$msg = "We couldnt find the specified task";
			} else {
				$msg = "task loaded";
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
	public function get_incomplete(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		if($this->ili()){
			$res['success'] = true;
			$msg = "incomplete tasks retrieved";
			$perPage = $req->input('per_page', 10);
			$search = $req->input('search');
			$sortBy = $req->input('sort_by', 'created_at');
			$sortDirection = $req->input('sort_direction', 'asc');

			$tasks = Task::query()
				->withCount('samples')
				->withCount('payments')
				->withCount('reports')
			;

			if($search){
				$tasks = $tasks
					->whereHas('client', function($q) use ($search){
						$q->where('client_name','like',"%{$search}%");
					})
					->orWhere('task_no', 'like', "%{$search}%")
					// ->orWhere('client_contact', 'like', "%{$search}%")
					// ->orWhere('id_number', 'like', "%{$search}%")
					// ->orWhere('kra_pin', 'like', "%{$search}%");
				;
				$res['runlog'][] = "added search filter";
			}

			$tasks = $tasks
				->where('status',0)
				->orWhere('status',1)
			;

			$sortBy = $sortBy == null || $sortBy == '' ? 'created_at' : $sortBy;
			// $sortBy = ($sortBy == 'role_name') ? "user_roles_id" : $sortBy;

			$tasks = $tasks
				->with('creator')
				->with('client')
				->orderBy($sortBy, $sortDirection)
				->paginate($perPage);

			/*
			for($i = 0;$i < count($users);$i++){
				$users[$i]->role_name = $users[$i]->myRoles?->role_name;
				$users[$i]->role_permissions = $users[$i]->myRoles?->role_permissions;
			}
			*/

			$res['result'] = true;
			$res['sendme'] = $tasks;
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

	// set ops
	public function add_new_task(Request $req,$iscalled = false,$p_user = null){
		$res = $this->dftres;
		$sent = $req->input('sentdata',null);

		$update_runlog = function($wot) use (&$res){
			$res['runlog'][] = $wot;
		};

		if($this->ili() || $iscalled){
			$res['success'] = true;
			$msg = "fetching permissions";
			$user = $p_user == null ? $this->cur_user() : $p_user;
			$uid = $user->id;
			$reslt = false;
			$task = null;

			$mekdate = $req->input('date_added',null);

			$validator = Validator::make($sent,[
				'paymentStats' => 'required|array',
				'paymentStats.pays_made' => 'sometimes|integer',
				'paymentStats.amount_due' => 'sometimes|numeric',
				'paymentStats.amount_paid' => 'sometimes|numeric',
				'paymentStats.balance' => 'sometimes|numeric',
				'clientData' => 'required|array',
				'clientData.id' => 'required|integer',
				'clientData.client_name' => 'required|string',
				'clientData.client_contact' => 'required|string',
				'sampleData' => 'required|array|min:1',
				'sampleData.*.typ' => 'required|string',
				'sampleData.*.amt' => 'required|numeric',
				'sampleData.*.tag' => 'required|string',
				'paymentData' => 'sometimes|array',
				'paymentData.*.method' => 'required_with:paymentData|string',
				'paymentData.*.amt' => 'required_with:paymentData|numeric',
				'paymentData.*.timestamp' => 'required_with:paymentData|date',
				'admin_password' => 'sometimes|string',
				'note' => 'nullable|string',
			]);

			if($validator->fails()){
				// $msg = "error in the payload";
				$msg = $validator->messages();
			} else {
				$client =  Client::where('client_contact', $sent['clientData']['client_contact'])->first();

				if(!$client){
					$msg = "Invalid client data";
				} else {
					$update_runlog('client data found');
					$haspaydata = isset($sent['paymentData']) && !empty($sent['paymentData']);
					$msg = "problem verifying payment data, try again";
					$cango = false;
					$client_balance = $sent['paymentStats']['balance'];

					if(!$haspaydata){
						if(!isset($sent['admin_password'])){
							$msg = "pass an admin password first";
						} else {
							$wegood = $iscalled ? true : $this->verifyPassword($sent['admin_password']);
							$res['runlog'][] = $wegood;
							$cango = $wegood;
							$msg = $wegood ? "valid admin password" : "invalid admin password";
						}
					} else {
						if($client_balance > 0){
							if(!isset($sent['admin_password'])){
								$msg = "pass an admin password first";
							} else {
								$wegood = $iscalled ? true : $this->verifyPassword($sent['admin_password']);
								$msg = $wegood ? "valid admin password" : "invalid admin password";
								$cango = $wegood;
							}
						} else {
							$cango = true;
						}
					}

					$res['runlog'][] = $msg;

					if(!$cango){
						$reslt = false;
					} else {
						try{
							// try figuring out if its a reconciler task
							$t_notes = $sent['note'];
							$mekdate = Carbon::now();

							if($t_notes !== ''){
								if(str_contains($t_notes,'reco_date- ')){
									$split = explode('- ',$t_notes);
									$datetext = $split[1];
									$mekdate = Carbon::createFromFormat('d/m/y H:i:s',$datetext);
								}
							}

							// $newid = Task::whereDate('created_at', today())->count() + 1;
							$newid = 0;
							$suffix = '';
							$task_serial = '';
							$exist = true;
							$addme = 1;

							// added addme to prevent an infinite loop
							do {
								$newid = Task::query()->where('id','<>',null)->count() + $addme;
								$suffix = str_pad($newid,4,'0',STR_PAD_LEFT);
								$task_serial = 'JML_' . $suffix;
								$exist = Task::query()->where('task_no',$task_serial)->exists();
								$addme += 1;
							} while ($exist);

							$sample_count = 0;

							$paystate = isset($sent['paymentStats']) ? ($sent['paymentStats']['balance'] == 0 ? 2 : 1) : 0;
							$safesave = $sent;
							$paid = $sent['paymentStats']['amount_paid'];
							$cost = $sent['paymentStats']['amount_due'];
							$f_cost = $sent['paymentStats']['full_amount_due'];
							$balance = $sent['paymentStats']['balance'];

							if(isset($safesave['admin_password'])){
								unset($safesave['admin_password']);
							}

							DB::beginTransaction();

							foreach($sent['sampleData'] as $ind => $s_info){
								$cnt = (int) $s_info['amt'];
								$sample_count += $cnt;
							}

							$newrecdata = [
								'task_no' => $task_serial,
								'added_by' => $uid,
								'client_id' => $client->id,
								'client_name' => $client->client_name,
								'status' => $paid == 0 ? 1 : 0,
								'payment_status' => $paystate,
								'is_paid' => $paystate == 2,
								'notes' => $sent['note'] ?? null,
								'passed_task_data' => $safesave,
								'paid' => $paid,
								'cost' => $cost,
								'full_cost' => $f_cost,
								'balance' => $balance,
								'sample_count' => $sample_count,
								'created_at' => $mekdate,
							];

							$task = Task::create($newrecdata);

							$samples = [];
							$cols = [];
							foreach($sent['sampleData'] as $ind => $s_info){
								$cnt = (int) $s_info['amt'];
								$samples[] = [
									'added_by' => $uid,
									'task_id' => $task->id,
									'client_id' => $client->id,
									'sample_no' => $task_serial . "_" . $ind,
									'sample_tag' => $s_info['tag'],
									'sample_type' => $s_info['typ'],
									'sample_count' => $cnt,
									'created_at' => $mekdate,
								];

								$cols = array_keys($samples[0]);
							}

							$batch = app(Batch::class);
							// batch insert sample records
							$batch->insert(new Sample,$cols,$samples);

							$removeFromCredit = 0;

							if($haspaydata){
								$payments = [];

								foreach($sent['paymentData'] as $pd){
									if(strtolower($pd['method']) == 'client credit'){
										// $tempclient = Client::find($sent['clientData']);
										// disabled due to rework of client credit subsystem
										// $removeFromCredit += (int) $pd['amt'];
									}

									$payments[] = [
										'added_by' => $uid,
										'task_id' => $task->id,
										'client_id' => $client->id,
										'method' => $pd['method'],
										'amount' => $pd['amt'],
										'status' => 1,
										'extra_details' => json_encode([
											'added_via' => 'portal',
										]),
										'transaction_time' => Carbon::parse($pd['timestamp']),
										'created_at' => $mekdate,
									];

									$cols = array_keys($payments[0]);
								}

								$batch->insert(new Payment,$cols,$payments);
							}

							// $update_runlog ("remove from credit: $removeFromCredit");
							$update_runlog ("add to client credit: $client_balance");

							if($client_balance > 0){
								// $client->credit_balance = $client->credit_balance - $removeFromCredit;
								$client->credit_balance = $client->credit_balance + $client_balance;
								$client->save();

								$res['runlog'][] = "deducted from credit balance";
							}

							$msg = "task added successfully";
							$reslt = true;
							DB::commit();
						// /*
						}catch (\Exception $e){
							DB::rollBack();

							$res['runlog'][] = $e;
							$msg = 'task creation failed: '.$e->getMessage();
						}
						// */
					}
				}
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

		if($iscalled){
			return $res;
		}
		return response()->json($res);
	}
	public function add_payment(Request $req,$iscalled = false,$p_user = null){
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			"taskid" => ['required'],
			"admin_password" => ['required'],
			"pay_method" => ['required'],
			"amount" => ['required'],
		]);
		// */

		$update_runlog = function($wot) use (&$res){
			$res['runlog'][] = $wot;
		};
		$_rlg = function($w) use($update_runlog){
			$update_runlog($w);
		};

		if($this->ili() || $iscalled){
			$res['success'] = true;
			$msg = "fetching permissions";
			$update_runlog($msg);
			$reslt = false;
			$task = null;

			$wegood = false;
			$paydate = $req->input('date_added',null);

			$_rlg(['pay_date' => $paydate]);

			if($p_user == null){
				$wegood = self::verifyPassword($passed['admin_password'], $outps);
			} else {
				$wegood = self::verifyPassword($passed['admin_password'], $outps, $p_user);
			}

			// $res['runlog'][] = ['decrypted_pw' => $outps];

			if(!$wegood){
				$msg = "invalid admin password";
				$update_runlog($msg);
			} else {
				$task = Task::query()
					->where('id',$passed['taskid'])
					->withCount('samples')
					->withCount('payments')
					->withCount('reports')
					->with('creator')
					->with('client')
					->first()
				;

				if($task == null){
					$msg = "task not found";
					$update_runlog($msg);
				} else {
					$msg = "task found";
					$update_runlog($msg);

					$bal = $task['balance'];
					$cost = $task['cost'];
					$paid = $task['paid'];
					$amt = $passed['amount'];
					$user = $p_user == null ? $this->cur_user() : $p_user;

					$c_id = $task->client_id;
					$client = Client::query()->where('id',$c_id)->first();

					if($client == null){
						$msg = "invalid client details";
						$update_runlog($msg);
					} else {
						$msg = "client found";
						$update_runlog($msg);

						$payamt = (float) $passed['amount'];
						$newrec = [
							'added_by' => $user->id,
							'task_id' => $task->id,
							'client_id' => $task->client->id,
							'method' => $passed['pay_method'],
							'amount' => $payamt,
							'status' => 1,
							'extra_details' => ['added_via' => 'task_info'],
							'transaction_time' => Carbon::now(),
							'created_at' => Carbon::now(),
						];

						if($paydate !== null){
							$newrec['transaction_time'] = Carbon::parse($paydate);
							// $newrec['created_at'] = Carbon::parse($paydate);

							$pdate = Carbon::parse($paydate);
							$_rlg(['added' => $newrec]);
						}

						if($task->created_at->isBefore(Carbon::today())){
							$newrec['payment_type'] = 'debt_payment';
						}

						// /*
						$payrec = Payment::create($newrec);

						$payrec->created_at = $newrec['created_at'];
						$payrec->save();
						// */

						$newbal = $bal - $amt;
						$paid += $amt;

						$update_runlog("client credit: ".$client->credit_balance);

						if($client->credit_balance > 0){
							$client->credit_balance = $client->credit_balance - $payamt;
							$client->credit_balance = $client->credit_balance < 0 ? 0 : $client->credit_balance;
							$client->save();

							$msg = "client credit updated";
							$update_runlog($msg);
						} else {
							$msg = "client credit not updated";
							$update_runlog($msg);
						}

						if($newbal < 0){
							$msg = "client overpaid";
							$update_runlog($msg);

							$newbal = 0;

							// remove from client_credit
							$addme = $paid - $cost;
							// $newcredit = $client->credit_balance - $addme;
							// $client->client_credit = $client->client_credit < 0 ? 0 : $client->client_credit;

							// $client->credit_balance = $newcredit;

							// $msg = "payment was more than required, added to client credit";
						} else {
							$msg = "payment added successfully";
						}

						$tochange = [
							'balance' => $newbal,
							'paid' => $paid,
							'payment_status' => $newbal == 0 ? 2 : 1,
							'is_paid' => $newbal == 0,
						];
						if($task->status == 1){
							$tochange['status'] = 0;
						}

						Task::query()->where('id',$task->id)->update($tochange);

						$reslt = true;
					}
				}
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

		if($iscalled){
			return $res;
		}

		return response()->json($res);
	}
	public function add_note(Request $req){
		$res = $this->dftres;
		// $alldata = $req->all();

		// /*
		$passed = $req->validate([
			'taskid' => ['required'],
			'note' => ['required'],
		]);
		// */


		if($this->ili()){
			$res['success'] = true;
			$msg = "fetching permissions";
			$reslt = false;

			$task = Task::query()
				->where('id',$passed['taskid'])
				->withCount('samples')
				->withCount('payments')
				->withCount('reports')
				->with('creator')
				->with('client')
				->first()
			;

			if($task == null){
				$msg = "task not found";
			} else {
				$task->update(['notes' => strip_tags($passed['note'])]);
				$task->refresh();
				$reslt = true;
				$msg = "notes updated successfully";
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
}
