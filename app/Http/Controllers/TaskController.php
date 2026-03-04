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

	public function get_all(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		if($this->ili()){
			$res['success'] = true;
			$msg = "all tasks retrieved";

			$perPage = $req->input('per_page', 10);
			$search = $req->input('search');
			$sortBy = $req->input('sort_by', 'created_at');
			$sortDirection = $req->input('sort_direction', 'desc');

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
				$tasks = $tasks->where('status',$is_paid == "true");
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
				'paymentStats.pays_made' => 'required|integer',
				'paymentStats.amount_due' => 'required|numeric',
				'paymentStats.amount_paid' => 'required|numeric',
				'paymentStats.balance' => 'required|numeric',
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
				$msg = $validator->messages()[0];
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
						// try{
							$newid = Task::whereDate('created_at', today())->count() + 1;
							$suffix = str_pad($newid,3,'0',STR_PAD_LEFT);
							$task_serial = 'TSK_' . date('Ymd') . '_' . $suffix;

							$paystate = isset($sent['paymentStats']) ? ($sent['paymentStats']['balance'] == 0 ? 2 : 1) : 0;
							$safesave = $sent;

							if(isset($safesave['admin_password'])){
								unset($safesave['admin_password']);
							}

							DB::beginTransaction();

							$task = Task::create([
								'task_no' => $task_serial,
								'added_by' => $uid,
								'client_id' => $client->id,
								'client_name' => $client->client_name,
								'status' => 0,
								'payment_status' => $paystate,
								'is_paid' => $paystate == 2,
								'notes' => $sent['note'] ?? null,
								'passed_task_data' => $safesave,
							]);

							$batch = app(Batch::class);
							$samples = [];
							$cols = [];
							foreach($sent['sampleData'] as $ind => $s_info){
								$samples[] = [
									'added_by' => $uid,
									'task_id' => $task->id,
									'client_id' => $client->id,
									'sample_no' => $task_serial . "_" . $ind,
									'sample_tag' => $s_info['tag'],
									'sample_type' => $s_info['typ'],
									'sample_count' => (int) $s_info['amt'],
									'results' => null,
									'deviation_summary' => null,
								];

								$cols = array_keys($samples[0]);
							}
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

							$reslt = true;
							DB::commit();
						/*
						}catch (\Exception $e){
							// DB::rollBack();

							$res['runlog'][] = $e;
							$msg = 'task creation failed: '.$e->getMessage();
						}
						*/
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
}
