<?php

namespace App\Http\Controllers;

use App\Models\BizData;
use App\Models\Client;
use App\Models\FileHash;
use App\Models\ReportsGen;
use App\Models\Sample;
use App\Services\ImageHashService;
use App\services\pdfHashService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ReportsController extends Controller{
    public function get_all(Request $req){
		$res = $this->dftres;
		// $alldata = $req->all();

		if($this->ili()){
			$res['success'] = true;
			$msg = "all Reports retrieved";

			$perPage = $req->input('per_page', 10);
			$search = $req->input('search');
			$sortBy = $req->input('sort_by', 'created_at');
			$sortDirection = $req->input('sort_direction', 'desc');

			// filters
			// $task_status = $req->input('Status',null);
			$sample_type = $req->input('sample_type',null);

			$sortBy = $sortBy == null ? 'created_at' : $sortBy;

			$res['runlog'][] = [
				"perPage" => $perPage,
				"search" => $search,
				"sortBy" => $sortBy,
				"sortDirection" => $sortDirection,
				"sample_type" => $sample_type,
			];

			// clean up sortBy to prevent issues
			if($sortBy == 'owner'){
				$sortBy = 'client_id';
			} elseif($sortBy == 'task'){
				$sortBy = 'task_id';
			}

			// return response()->json($res);

			$records = ReportsGen::query()
				->with('sample')
				->with('mycreator')
				->with('owner')
				->with('task')
			;

			if($search){
				$records = $records
					->whereHas('owner', function($q) use ($search){
						$q->where('client_name','like',"%{$search}%");
					})
					->orWhereHas('task', function($q) use ($search){
						$q->where('task_no','LIKE',"%{$search}%");
					})
					->orWhereHas('sample', function($q) use ($search){
						$q->where('sample_no','LIKE',"%{$search}%")
							->orWhere('sample_tag','LIKE',"%{$search}%")
						;
					})
				;
				$res['runlog'][] = "added search filter";
			}

			// place for filters
			if($sample_type){
				$records = $records->where('sample_type',$sample_type);
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
	public function get_count(Request $req){
		$res = $this->dftres;
		// $alldata = $req->all();

		if($this->ili()){
			$res['success'] = true;
			$msg = "all Reports retrieved";

			$records = ReportsGen::all()->count();

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
	public function get_some(Request $req) {
		$res = $this->dftres;
		$alldata = $req->all();

		/*
		$passed = $req->validate([
			'sample_id' => ['required','numeric','sometimes'],
		]);
		// */

		$task_id = isset($alldata['task_id']) ? $alldata['task_id'] : null;
		$client_id = isset($alldata['client_id']) ? $alldata['client_id'] : null;
		$sample_id = isset($alldata['sample_id']) ? $alldata['sample_id'] : null;
		$added_by = isset($alldata['added_by']) ? $alldata['added_by'] : null;

		$row = 'id';

		if($this->ili()){
			$res['success'] = true;
			$msg = "fetching Report information";
			$reslt = false;
			$found = false;

			// find a sample
			$therecs = ReportsGen::where('id','<>',null);

			if($task_id !== null){
				$therecs = $therecs->where('task_id',$task_id);
				$res['runlog'][] = 'task_id found';
				$found = true;
			}
			if($client_id !== null){
				$therecs = $therecs->where('client_id',$client_id);
				$res['runlog'][] = 'client_id found';
				$found = true;
			}
			if($sample_id !== null){
				$therecs = $therecs->where('sample_id',$sample_id);
				$res['runlog'][] = 'sample_id found';
				$found = true;
			}
			if($added_by !== null){
				$therecs = $therecs->where('added_by',$added_by);
				$res['runlog'][] = 'added_by found';
				$found = true;
			}

			if(!$found){
				$therecs = $therecs->where('id',null);
			}

			$therecs = $therecs
				->with('task')
				->with('sample')
				->with('mycreator')
				->with('owner')
				->get()
			;

			// check if it exists
			if(count($therecs) == 0){
				$msg = "No reports found";
			} else {
				$msg = "Reports fetched successfully";
			}

			$reslt = true;

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

			$report = ReportsGen::where('file_slug',$slug)->first();

			if($report == null){
				$_msg('the report doesnt exist');
			} else {
				$_msg('report found');

				$fyl_ = $fl == 'img' ? $report->image_link : $report->pdf_link;
				$folder = $fl == 'img' ? self::$report_images_path : self::$report_files_path;
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
	public function add_new(Request $req) {
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			'admin_password_mine' => ['required'],
			'sampleid' => ['required'],
			'report_no' => ['required'],
			// 'genfile' => ['required','file'],
			'genimage' => ['required','file'],
			'bizdata' => ['required'],
			'file_slug' => ['required'],
		]);
		// */

		$admin_password_mine = $passed['admin_password_mine'];
		$sampleid = $passed['sampleid'];
		$rno = $passed['report_no'];
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

			if(!$wegood){
				$msg = "invalid admin password";
			} else {
				$sample = Sample::where('id',$sampleid)
					->with('results')
					->withCount('results')
					->with('mycreator')
					->with('owner')
					->with('task')
					->first()
				;

				if($sample == null){
					$msg = "invalid sample";
				} else {
					$smp = $sample->toArray();
					$results = $smp['results'];
					// $res['runlog'][] = ['results' => $results];

					$rndr = Str::upper($this->mekRandomString(4));
					$sno = $sample->sample_no;
					$rpts = $sample->reports_count;
					$file_link = "{$sno}_report_{$rpts}_{$rndr}_pdf";
					$img_link = "{$sno}_report_{$rpts}_{$rndr}_img";
					$fyls = [];

					$slug = $passed['file_slug'];
					$img_serial = '';
					$pdf_serial = '';

					$fkey = 'genfile';
					$fyls[$fkey] = '--';
					/*
					if($req->hasFile($fkey) && $req->file($fkey)->isValid()){
						/*
						$imghash = new pdfHashService();
						$mfile = $req->file($fkey,null);
						$pdf_serial = $imghash->generateHash($mfile,$res['runlog'])['hex'];
						// /

						$fyl = $req->file($fkey);
						$myname = $file_link;
						$ext = $fyl->getClientOriginalExtension();
						$date = date('y_m');
						$daname = "{$myname}.{$ext}";
						$fname = "[{$date}] {$daname}";
						$fyl->move(storage_path(self::$report_files_path),$fname);
						$fyls[$fkey] = $fname;
						// $pdf_serial = '';
					};
					// */

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
						$fyl->move(storage_path(self::$report_images_path),$fname);
						$fyls[$fkey] = $fname;
					};

					$pdf_serial = $img_serial;

					// $res['runlog'][] = ['sample' => $sample];

					$toadd = [
						'task_id' => $sample->task_id,
						'sample_id' => $sample->id,
						'created_by' => $user->id,
						'client_id' => $sample->client_id,
						'report_no' => $rno,

						'company_contact' => $b_deets['phone'],
						'sample_type' => $sample->sample_type,
						'sample_count' => $sample->sample_count,
						'date_received' => $sample->created_at,
						'date_compiled' => Carbon::now(),
						'task_no' => $sample->task->task_no,
						'technical_staff_name' => $b_deets['tech_staff'],
						'authorizer' => $b_deets['auth_staff'],
						'attendant' => $user->name,
						'analytical_report' => $results,
						'deviation_data' => $bizdata['deviation_data'],
						'business_logo_img' => $bizdata['business_images']['company_logo'],
						'pdf_link' => $fyls['genfile'],
						'image_link' => $fyls['genimage'],

						'file_slug' => $slug,
					];

					// $res['runlog'][] = ["newrec" => $toadd];

					$report = ReportsGen::create($toadd);

					$add_to_hashes = [
						[
							'report_id' => $report->id,
							'file_type' => 'report',
							'myhash' => $img_serial,
							'file_name' => $toadd['image_link'],
							'file_format' => 'image',
						],
						[
							'report_id' => $report->id,
							'file_type' => 'report',
							'myhash' => $pdf_serial,
							'file_name' => $toadd['pdf_link'],
							'file_format' => 'pdf',
						]
					];

					for($x = 0;$x < count($add_to_hashes);$x++){
						FileHash::create($add_to_hashes[$x]);
					}

					$showthis = $report;
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

	public function verify_me(Request $req){
		// return $this->log_my_payload_fyls($req, 'true');

		$res = $this->dftres;
		$alldata = $req->all();
		$msg = "didnt even get to start";

		// /*
		$passed = $req->validate([
			'filetype' => ['required'],
			'task_no' => ['required'],
			'sample_type' => ['required'],
			'sample_count' => ['required','numeric'],
			'client_name' => ['required'],
			'report_date' => ['required','date'],
			'authorizer' => ['required'],
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
			$task_no = $passed['task_no'];
			$sample_type = $passed['sample_type'];
			$sample_count = $passed['sample_count'];
			$client_name = $passed['client_name'];
			$report_date = $passed['report_date'];
			$authorizer = $passed['authorizer'];

			$s_task_no = null;
			$s_sample_type = null;
			$s_sample_count = null;
			$s_name = null;
			$s_report_date = null;
			$s_authorizer = null;

			$mfile = $req->file('fileInput',null);
			$outdata = [];
			$savedhash = '';
			$serial = '';
			$flink = '';

			if($ftype == "image"){
				$imghash = new ImageHashService();
				$serial = $imghash->generateHash($mfile,$res['runlog'])['hex'];
			}

			$fhash = FileHash::where('myhash',$serial)->with('report')->first();
			$savedhash = $fhash->myhash;

			if($fhash == null){
				$_msg('filehash not found');
			} else {
				$_msg('filehash found');

				$report = $fhash->report;
				$client = Client::where('id',$report->client_id)->first();

				$s_task_no = $report->task_no;
				$s_sample_type = $report->sample_type;
				$s_sample_count = $report->sample_count;
				$s_name = $client->client_name;
				$s_report_date = $report->date_compiled;
				$s_authorizer = $report->authorizer;

				$flink = $report->file_slug;

				$reslt = true;
			}

			$outdata['matchdata'] = [
				'passed' => $serial,
				'saved' => $savedhash,
			];

			$ismatch = !($fhash == null);

			$outdata = [
				...$outdata,
				'file_link' => $flink,
				'matchfile' => $ismatch,
				'task_no' => $s_task_no,
				'sample_type' => $s_sample_type,
				'sample_count' => $s_sample_count,
				'client_name' => $s_name,
				'report_date' => $s_report_date,
				'authorizer' => $s_authorizer,
			];

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
