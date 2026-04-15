<?php

namespace App\Http\Controllers;

use App\Models\BizData;
use App\Models\Client;
use App\Models\FileHash;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Task;
use App\Services\ImageHashService;
use App\services\pdfHashService;
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
		$_msg = function($w,$l = true) use (&$update_msg){
			$update_msg($w,$l);
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
					->doesntHave('invoices')
					->with('samples')
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
    public function get_my_spec_info(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();
		$msg = "authenticafion failed";

		// /*
		$passed = $req->validate([
			'client_id' => ['required','numeric'],
			'invoice_id' => ['required','numeric'],
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
			$update_msg("fetching permissions");
			$reslt = false;

			$c_id = $passed['client_id'];
			$i_id = $passed['invoice_id'];
			$incomplete = [];

			$theclient = Client::where('id',$c_id)->first();

			if($theclient == null){
				$update_msg("invalid client specified");
			} else {
				$update_msg("valid client specified");

				$invoice = Invoice::where('id',$i_id)
					->with('tasks.samples')
					->first()
				;
				$update_runlog('invoice task samples');
				// $update_runlog($invoice->tasks->samples);

				$incomplete = $invoice->invoice_data;
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
					$msg = "invalid client";
				} else {
					$tasks = Task::where('client_id',$client_id)->where('payment_status','<>',2)->with('samples')->get();
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
					$file_link = "{$sno}_invoice_{$rpts}_{$rndr}_pdf";
					$img_link = "{$sno}_invoice_{$rpts}_{$rndr}_img";
					$fyls = [];
					$img_serial = '';
					$pdf_serial = '';

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
						$imghash = new ImageHashService();
						$mfile = $req->file($fkey,null);
						$img_serial = $imghash->generateHash($mfile,$res['runlog'])['hex'];

						$fyl = $req->file($fkey);
						$myname = $img_link;
						$ext = $fyl->getClientOriginalExtension();
						$date = date('y_m');
						$daname = "{$myname}.{$ext}";
						$fname = "[{$date}] {$daname}";
						$fyl->move(storage_path(self::$invoice_images_path),$fname);
						$fyls[$fkey] = $fname;
					};

					$pdf_serial = $img_serial;
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

					$invoice = Invoice::create($toadd);

					$add_to_hashes = [
						[
							'invoice_id' => $invoice->id,
							'file_type' => 'invoice',
							'myhash' => $img_serial,
							'file_name' => $toadd['image_link'],
							'file_format' => 'image',
						],
						[
							'invoice_id' => $invoice->id,
							'file_type' => 'invoice',
							'myhash' => $pdf_serial,
							'file_name' => $toadd['pdf_link'],
							'file_format' => 'pdf',
						]
					];

					for($x = 0;$x < count($add_to_hashes);$x++){
						FileHash::create($add_to_hashes[$x]);
					}

					// $res['runlog'][] = ["newrec" => $toadd];

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
	public function change_image(Request $req) {
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			'admin_password_mine' => ['required'],
			'client_id' => ['required'],
			'invoice_no' => ['required','numeric'],
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
		$_msg = function($w,$l = true) use (&$update_msg){
			$update_msg($w,$l);
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
					$_msg("invalid client");
				} else {
					$i_id = $passed['invoice_no'];
					$invoice = Invoice::where('id',$i_id)->first();

					if($invoice == null){
						$_msg("Invalid invoice passed");
					} else {
						$rndr = Str::upper($this->mekRandomString(4));
						$sno = '';
						$rpts = $client['invoices_count'];
						$img_link = "invoice_{$rpts}_{$rndr}_img";
						$fyls = [];
						$img_serial = '';
						$pdf_serial = '';

						if($invoice->image_link){
							$delimg = $invoice->image_link;
							$path = storage_path(self::$invoice_images_path).$delimg;
							$has_fyl = is_file($path);

							if($has_fyl){
								unlink($path);
							}
						}

						$fkey = 'genimage';
						if($req->hasFile($fkey) && $req->file($fkey)->isValid()){
							$imghash = new ImageHashService();
							$mfile = $req->file($fkey,null);
							$img_serial = $imghash->generateHash($mfile,$res['runlog'])['hex'];

							$fyl = $req->file($fkey);
							$myname = $img_link;
							$ext = $fyl->getClientOriginalExtension();
							$date = date('y_m');
							$daname = "{$myname}.{$ext}";
							$fname = "[{$date}] {$daname}";
							$fyl->move(storage_path(self::$invoice_images_path),$fname);
							$fyls[$fkey] = $fname;
						};

						$invoice->image_link = $fyls['genimage'];
						$invoice->file_slug = $passed['file_slug'];
						$invoice->save();
						$invoice->refresh();

						$hashes = FileHash::where('invoice_id',$i_id)->first();
						$hashes->myhash = $img_serial;
						$hashes->file_name = $fyls['genimage'];
						$hashes->save();

						$msg = "Invoice updated successfully";
						$showthis = $invoice;
						$reslt = true;
					}
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

							$mybalance = $invoice->balance;
							$newbal = $mybalance - $amount;
							$invoice->status = $newbal <= 0 ? 2 : 1;
							// $newbal = $newbal < 0 ? 0 : $newbal;
							$invoice->balance = $newbal;
							$invoice->total_paid += $amount;

							$update_runlog("calc: $mybalance - $amount");
							$update_runlog("new balance: $newbal");
							$update_runlog("new balance: $newbal");
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

	public function verify_me(Request $req){
		// return $this->log_my_payload_fyls($req, 'true');

		$res = $this->dftres;
		$alldata = $req->all();
		$msg = "didnt even get to start";

		// /*
		$passed = $req->validate([
			'filetype' => ['required'],
			'invoice_no' => ['required'],
			'invoice_date' => ['required','date'],
			'invoice_amt' => ['required','numeric'],
			'invoice_tasks' => ['required','numeric'],
			'fileInput' => ['required','file'],
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

		if($this->ili() || true){
			$res['success'] = true;
			$_msg('starting the procedure');
			$reslt = false;

			$ftype = $passed['filetype'];
			$inv_no = $passed['invoice_no'];
			$inv_date = Carbon::parse($passed['invoice_date']);
			$amt = $passed['invoice_amt'];

			$outdata = [];

			$invoice = Invoice::where('invoice_no',$inv_no)->with('filehashes')->with('client')->first();

			if($invoice == null){
				$_msg('no invoice found with that Number');
			} else {
				$_msg('invoice found');

				// get payment data from db
				$bizdata = BizData::where('item_name','like',"%business_details%")->first();
				$dta = $bizdata->item_data ?? ['mpesa' => '!!'];
				$mpesa = $dta['mpesa'];

				$outdata['invoice_no'] = $invoice->invoice_no;
				$serial = '';
				$mfile = $req->file('fileInput',null);

				if($ftype == "image"){
					$imghash = new ImageHashService();
					$serial = $imghash->generateHash($mfile,$res['runlog'])['hex'];
				} elseif($ftype == 'pdf') {
					$pdfhash = new ImageHashService();
					$serial = $pdfhash->generateHash($mfile,$res['runlog'])['hex'];
				}

				$hashes = $invoice->filehashes->toArray();
				$savedhash = '';
				// $f_name = $ftype == 'image' ? $invoice->image_link : $invoice->pdf_link;
				$f_name = $invoice->file_slug;

				if(count($hashes) > 0){
					foreach($hashes as $h){
						if(strtolower($h['file_format']) == strtolower($ftype)){
							$savedhash = $h['myhash'];
							// $fname = $h['file_name'];
						}
					}
				}

				$outdata['invoice'] = $invoice->toArray();
				unset($outdata['invoice']['filehashes']);

				$_msg("file name: $f_name");

				$outdata['matchfile'] = strtolower($serial) == strtolower($savedhash);
				$outdata['matches'] = [$serial,$savedhash];
				$outdata = [
					...$outdata,
					'invoice_amt' => $invoice->total,
					'invoice_date' => $invoice->created_at,
					'invoice_tasks' => $invoice->tasks_count,
					'client_name' => $invoice->client->client_name,
					'client_contact' => $invoice->client->client_contact,
					'mpesa_number' => $mpesa,
					'invoice_tasks' => $invoice->tasks_count,
					'invoice_tasks' => $invoice->tasks_count,
					'file_link' => $f_name,
				];

				$reslt = true;
			}

			$res['wegood'] = $reslt;
			$res['result'] = true;
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
