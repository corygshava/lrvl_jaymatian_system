<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class InvoiceController extends Controller{
	public function get_all(Request $req){
		$res = $this->dftres;
		// $alldata = $req->all();

		$update_runlog = function($wot) use (&$res){
			$res['runlog'][] = $wot;
		};
		$update_msg = function($wot,$logit = true) use (&$msg,&$update_runlog){
			$msg = $wot;
			if($logit){
				$update_runlog($wot);
			}
		};

		if($this->ili()){
			$res['success'] = true;
			$msg = "all Invoices retrieved";

			$perPage = $req->input('per_page', 10);
			$search = $req->input('search');
			$sortBy = $req->input('sort_by', 'created_at');
			$sortDirection = $req->input('sort_direction', 'desc');

			// filters
			// $task_status = $req->input('Status',null);
			$status = $req->input('status',null);

			$sortBy = $sortBy == null ? 'created_at' : $sortBy;

			$res['runlog'][] = [
				"perPage" => $perPage,
				"search" => $search,
				"sortBy" => $sortBy,
				"sortDirection" => $sortDirection,
				'status' => $status,
			];

			// clean up sortBy to prevent issues
			if($sortBy == 'client'){
				$sortBy = 'client_id';
			}

			// return response()->json($res);

			$records = Invoice::query()
				->with('client')
				->with('creator')
				->with('tasks')
				->withCount('tasks')
				->with('payments')
				->withCount('payments')
			;

			if($search){
				$records = $records
					->whereHas('client', function($q) use ($search){
						$q->where('client_name','like',"%{$search}%");
					})
					->orWhereHas('tasks', function($q) use ($search){
						$q->where('task_no','LIKE',"%{$search}%");
					})
					->orWhere('invoice_no','LIKE',"%{$search}%")
				;
				$res['runlog'][] = "added search filter";
			}

			// place for filters
			if($status){
				$records = $records->where('status',$status);
			}

			$sortBy = $sortBy == null || $sortBy == '' ? 'created_at' : $sortBy;
			// $sortBy = ($sortBy == 'role_name') ? "user_roles_id" : $sortBy;

			$records = $records
				->orderBy($sortBy, $sortDirection)
				->paginate($perPage);

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
    public function get_my_info(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();
		$msg = "authenticafion failed";

		// /*
		$passed = $req->validate([
			'client_id' => ['required','numeric'],
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

		if($this->ili()){
			$res['success'] = true;
			$update_msg("fetching permissions");
			$reslt = false;

			$c_id = $passed['client_id'];
			$incomplete = [];

			$theclient = Client::where('id',$c_id)->first();

			if($theclient == null){
				$update_msg("invalid client specified");
			} else {
				$update_msg("valid client specified");

				$incomplete = Task::where('client_id',$c_id)
					->where('payment_status','<>',2)
					->get()
				;
				$cnt = count($incomplete);
				$has_some = $cnt > 0;

				if(!$has_some){
					$update_msg("all tasks are fully paid, an Invoice is unnecessary");
				} else {
					$update_msg("found $cnt unpaid tasks");
					$reslt = true;

					$invs = Invoice::all()->count();
					$res['xtra'] = [
						'invoices_count' => $invs,
					];
				}
			}

			$res['result'] = $reslt;
			$res['sendme'] = $incomplete;
			$res['message'] = $msg;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}
	public function get_some(Request $req) {
		$res = $this->dftres;
		$alldata = $req->all();

		/*
		$passed = $req->validate([
			'sample_id' => ['required','numeric','sometimes'],
		]);
		// */

		$client_id = isset($alldata['client_id']) ? $alldata['client_id'] : null;
		$added_by = isset($alldata['added_by']) ? $alldata['added_by'] : null;

		$row = 'id';

		if($this->ili()){
			$res['success'] = true;
			$msg = "fetching invoices";
			$reslt = false;
			$found = false;

			// find a sample
			$therecs = Invoice::where('id','<>',null)
			;

			if($client_id !== null){
				$therecs = $therecs->where('client_id',$client_id);
				$res['runlog'][] = 'client_id found';
				$found = true;
			}
			if($added_by !== null){
				$therecs = $therecs->where('added_by',$added_by);
				$res['runlog'][] = 'added_by found';
				$found = true;
			}

			if(!$found){
				$therecs = $therecs->where('id','=',null);
			}

			$therecs = $therecs
				->with('client')
				->with('creator')
				->with('tasks')
				->withCount('tasks')
				->with('payments')
				->withCount('payments')
				->get()
			;

			// check if it exists
			if(count($therecs) == 0){
				$msg = "invoices not found";
				$reslt = true;
			} else {
				$reslt = true;
				$msg = "invoices fetched successfully";
			}

			$res['result'] = $reslt;
			$res['sendme'] = $therecs;
			$res['message'] = $msg;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}

	public function get_from_slug(Request $req,$fl = 'img',$slug = ''){
		$res = $this->dftres;
		$alldata = $req->all();
		$msg = "didnt even get to start";

		/*
		$passed = $req->validate([
			'condition' => ['required']
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
			$_msg('starting the procedure');
			$reslt = false;

			$report = Invoice::where('file_slug',$slug)->first();

			if($report == null){
				$_msg('the invoice doesnt exist');
			} else {
				$_msg('invoice found');

				$fyl_ = $fl == 'img' ? $report->image_link : $report->pdf_link;
				$folder = $fl == 'img' ? self::$invoice_images_path : self::$invoice_files_path;
				$fyl_link = "{$folder}{$fyl_}";
				$path = storage_path($fyl_link);

				if(File::exists($path)){
					$reslt = true;
					return response()->file($path);
				}
			}

			$res['result'] = $reslt;
			$res['sendme'] = $report;
			$res['message'] = $msg;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		if(!$reslt){
			http_response_code(404);
			$vw = new ViewController();
			return $vw->showerror($req,"the invoice file serial: <b>$slug</b> doesnt exist<br>$msg");
		}

		return null;
		return response()->json($res);
	}

	// add ops
	public function add_new(Request $req) {
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			'admin_password_mine' => ['required'],
			'client_id' => ['required'],
			'invoice_no' => ['required'],
			'genfile' => ['required','file'],
			'genimage' => ['required','file'],
			'bizdata' => ['required'],
			'file_slug' => ['required'],
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

		$client_id = $passed['client_id'];
		$rno = $passed['invoice_no'];
		$bizdata = json_decode($passed['bizdata'],true);

		$b_deets = $bizdata['business_details'];

		// $res['runlog'][] = json_decode($bizdata);

		if($this->ili()){
			$res['success'] = true;
			$msg = "business information updated successfully";
			$reslt = false;
			$wegood = $this->verifyPassword($passed['admin_password_mine']);
			$user = $this->cur_user();
			$showthis = null;
			$invoice = null;
			$slug = $passed['file_slug'];

			if(!$wegood){
				$msg = "invalid admin password";
			} else {
				$client = Client::where('id',$client_id)
					->withCount('invoices')
					->first()
				;

				if($client == null){
					$msg = "invalid sample";
				} else {
					$tasks = Task::where('client_id',$client_id)->where('payment_status','<>',2)->get();
					$task_ids = [];
					$total = 0;
					$savetasks = $tasks->toArray();

					$update_runlog(['tasks' => $tasks]);

					foreach($savetasks as $t){
						$task_ids[] = $t['id'];
						$total += (float) $t['balance'];
					}

					$update_runlog(['task_ids' => $task_ids]);

					$owner = $client->toArray();
					// $res['runlog'][] = ['results' => $results];

					$rndr = Str::upper($this->mekRandomString(4));
					$sno = '';
					$rpts = $owner['invoices_count'];
					$file_link = "{$sno}_{$rpts}_{$rndr}_pdf";
					$img_link = "{$sno}_{$rpts}_{$rndr}_img";
					$fyls = [];

					$fkey = 'genfile';
					if($req->hasFile($fkey) && $req->file($fkey)->isValid()){
						$fyl = $req->file($fkey);
						$myname = $file_link;
						$ext = $fyl->getClientOriginalExtension();
						$date = date('y_m');
						$daname = "{$myname}.{$ext}";
						$fname = "[{$date}] {$daname}";
						$fyl->move(storage_path(self::$invoice_files_path),$fname);
						$fyls[$fkey] = $fname;
					};

					$fkey = 'genimage';
					if($req->hasFile($fkey) && $req->file($fkey)->isValid()){
						$fyl = $req->file($fkey);
						$myname = $img_link;
						$ext = $fyl->getClientOriginalExtension();
						$date = date('y_m');
						$daname = "{$myname}.{$ext}";
						$fname = "[{$date}] {$daname}";
						$fyl->move(storage_path(self::$invoice_images_path),$fname);
						$fyls[$fkey] = $fname;
					};

					// $res['runlog'][] = ['sample' => $sample];

					$toadd = [
						'client_id' => $client_id,
						'created_by' => $user->id,
						'invoice_no' => $rno,

						'total' => $total,
						'total_paid' => 0,
						'balance' => $total,
						'tasks_count' => count($tasks),
						'task_ids' => $task_ids,
						'invoice_data' => $savetasks,
						'status' => 0,

						'last_updated_by' => $user->id,
						'pdf_link' => $fyls['genfile'],
						'image_link' => $fyls['genimage'],

						'file_slug' => $slug,
					];

					// $res['runlog'][] = ["newrec" => $toadd];

					$invoice = Invoice::create($toadd);
					$invoice->tasks()->sync($task_ids);
					$msg = "Invoice saved successfully";
					$showthis = $invoice;
					$reslt = true;
				}
			}

			$res['result'] = $reslt;
			$res['sendme'] = $showthis;
			$res['message'] = $msg;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}

	public function add_payment(Request $req) {
		$res = $this->dftres;
		$alldata = $req->all();
		$msg = "didnt even start";

		// /*
		$passed = $req->validate([
			'invoice_id' => ['required','numeric'],
			'admin_password' => ['required'],
			'pay_method' => ['required'],
			'amount' => ['required','numeric'],
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
		$_msg = function($wot,$logit = true) use(&$update_msg){
			$update_msg($wot,$logit);
		};

		if($this->ili()){
			$res['success'] = true;
			$update_msg('starting the procedure');
			$reslt = false;
			$invoice = null;
			$user = $this->cur_user();

			$inv_id = $passed['invoice_id'];
			$admin_password = $passed['admin_password'];
			$pay_method = $passed['pay_method'];
			$amount = (float) $passed['amount'];

			if(!$this->verifyPassword($admin_password)){
				$_msg('invalid admin password');
			} else {
				$invoice = Invoice::where('id',$inv_id)
					->with('client')
					->with('creator')
					->with('tasks')
					->withCount('tasks')
					->with('payments')
					->withCount('payments')
					->first()
				;

				if($invoice == null){
					$_msg('invalid invoice passed');
				} else {
					$client = Client::where('id',$invoice->client_id);

					if($client == null){
						$_msg('invalid client, regenerate the invoice');
					} else {
						$_msg('comencing database update');

						try{
							DB::beginTransaction();

							$_msg('valid invoice passed');

							$tasks = $invoice->tasks;
							$client = $invoice->client;
							$toremove = $amount;

							foreach($tasks as $t){
								if($toremove > 0){
									$paid = $t->paid;
									$cost = $t->cost;
									$bal = $t->balance;
									$addme = $bal;

									if($toremove < $addme){
										$addme = $toremove;
									}

									$t->paid += $addme;
									$t->balance -= $addme;
									$t->save();

									$toremove -= $addme;
								}
							}

							$_msg('all tasks updated accordingly');

							Payment::create([
								'added_by' => $user->id,
								// 'task_id' => -1,
								'invoice_id' => $invoice->id,
								'client_id' => $invoice->client->id,
								'method' => $pay_method,
								'amount' => $amount,
								'status' => 1,
								'payment_type' => 'invoice',
								'extra_details' => ['added_via' => 'invoice_payment'],
								'transaction_time' => Carbon::now(),
							]);

							$_msg('added payment record');

							$cred = $client->credit_balance - $amount;
							$client->credit_balance = $cred;
							$client->save();

							$newbal = $invoice->balance - $amount;
							$invoice->status = $newbal <= 0 ? 2 : 1;
							$newbal = $newbal < 0 ? 0 : $newbal;
							$invoice->balance = $newbal;
							$invoice->total_paid = $amount;
							$invoice->save();
							$invoice->refresh();

							$reslt = true;
							$_msg = "invoice updated successfully";

							DB::commit();
						} catch(\Exception $e){
							DB::rollBack();
							$_msg('adding payment record failed');
							$update_runlog($e);
						}
					}
				}
			}

			$res['result'] = $reslt;
			$res['sendme'] = $invoice;
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
