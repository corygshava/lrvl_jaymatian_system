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
			if($payment_status){
				$tasks = $tasks->where('payment_status',$payment_status);
			}

			// filter for task status
			if($task_status){
				$tasks = $tasks->where('status',$task_status);
			}

			// filter for whether or not its paid
			if($is_paid){
				$paycon = $is_paid == "true";
				$tasks = $tasks->where('is_paid',$paycon);
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
	public function add_new_task(Request $req){
		$res = $this->dftres;
		$sent = $req->input('sentdata',null);

		if($this->ili()){
			$res['success'] = true;
			$msg = "fetching permissions";
			$user = $this->cur_user();
			$uid = $user->id;
			$reslt = false;
			$task = null;

			$validator = Validator::make($sent,[
				'paymentStats' => 'required|array',
				'paymentStats.pays_made' => 'sometimes|integer',
				'paymentStats.amount_due' => 'sometimes|numeric',
				'paymentStats.amount_paid' => 'sometimes|numeric',
				'paymentStats.balance' => 'sometimes|numeric',
				'clientData' => 'required|array',
				'clientData.id' => 'required|integer',
				'clientData.client_name' => 'required|string',
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
				$client =  Client::find($sent['clientData']['id']);

				if(!$client){
					$msg = "Invalid client data";
				} else {
					$haspaydata = isset($sent['paymentData']) && !empty($sent['paymentData']);
					$msg = "problem verifying payment data, try again";
					$cango = false;

					if(!$haspaydata){
						if(!isset($sent['admin_password'])){
							$msg = "pass an admin password first";
						} else {
							$wegood = $this->verifyPassword($sent['admin_password']);
							$res['runlog'][] = $wegood;
							$cango = $wegood;
							$msg = $wegood ? "valid admin password" : "invalid admin password";
						}
					} else {
						if($sent['paymentStats']['balance'] > 0){
							if(!isset($sent['admin_password'])){
								$msg = "pass an admin password first";
							} else {
								$wegood = $this->verifyPassword($sent['admin_password']);
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
							// $newid = Task::whereDate('created_at', today())->count() + 1;
							$newid = Task::where('id','<>',null)->count() + 1;
							$suffix = str_pad($newid,4,'0',STR_PAD_LEFT);
							$task_serial = 'JML_' . $suffix;
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

							$task = Task::create([
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
							]);

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
										$removeFromCredit += (int) $pd['amt'];
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
									];

									$cols = array_keys($payments[0]);
								}

								$batch->insert(new Payment,$cols,$payments);
							}

							$res['runlog'][] = "remove from credit: $removeFromCredit";

							if($removeFromCredit > 0){
								$client->credit_balance = $client->credit_balance - $removeFromCredit;
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

		return response()->json($res);
	}

	public function add_payment(Request $req){
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


		if($this->ili()){
			$res['success'] = true;
			$msg = "fetching permissions";
			$reslt = false;
			$task = null;

			$wegood = self::verifyPassword($passed['admin_password'],$outps);

			$res['runlog'][] = ['decrypted_pw' => $outps];

			if(!$wegood){
				$msg = "invalid admin password";
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
				} else {
					$bal = $task['balance'];
					$cost = $task['cost'];
					$paid = $task['paid'];
					$amt = $passed['amount'];
					$user = $this->cur_user();

					// /*
					Payment::create([
						'added_by' => $user->id,
						'task_id' => $task->id,
						'client_id' => $task->client->id,
						'method' => $passed['pay_method'],
						'amount' => $passed['amount'],
						'status' => 1,
						'extra_details' => json_encode(['added_via' => 'task_info']),
						'transaction_time' => Carbon::now(),
					]);
					// */

					$newbal = $bal - $amt;
					$paid += $amt;

					if($newbal < 0){
						$newbal = 0;
						// add to client_credit
						$addme = $paid - $cost;
						$newcredit = $task->client->credit_balance + $addme;

						Client::where('id',$task->client->id)
							->update([
								'credit_balance' => $newcredit,
							])
						;

						$msg = "payment was more than required, added to client credit";
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
					Task::where('id',$task->id)->update($tochange);

					$reslt = true;
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
