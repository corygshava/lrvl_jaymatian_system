<?php

namespace App\Http\Controllers;

use App\Models\Sample;
use App\Models\Result;
use App\Models\Task;
use Illuminate\Http\Request;
use Mavinoo\Batch\Batch;
use Carbon\Carbon;

class SamplesController extends Controller{
	// getters
	public function get_everything(Request $req, $iscalled = false){
		$res = $this->dftres;
		// $alldata = $req->all();

		// housekeeping stuff
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

		if($this->ili() || $iscalled){
			$res['success'] = true;
			$_msg("all samples retrieved");

			$perPage = $req->input('per_page', 10);
			$search = $req->input('search');
			$sortBy = $req->input('sort_by', 'created_at');
			$sortDirection = $req->input('sort_direction', 'desc');

			// filters
			$status = $req->input('status',null);
			$with_results = $req->input('with_results',null);
			$sample_typ = $req->input('sample_type',null);
			$pay_status = $req->input('payment_status',null);

			$sortBy = $sortBy == null ? 'created_at' : $sortBy;

			$res['runlog'][] = [
				"perPage" => $perPage,
				"search" => $search,
				"sortBy" => $sortBy,
				"sortDirection" => $sortDirection,
				"status" => $status,
				"with_results" => $with_results,
			];

			// clean up sortBy to prevent issues
			if($sortBy == 'owner'){
				$sortBy = 'client_id';
			} elseif($sortBy == 'task'){
				$sortBy = 'task_id';
			}

			// return response()->json($res);

			$records = Sample::query()
				->with('worksheets')
				->with('results')
				->withCount('results')
				->withCount('reports')
				->with('mycreator')
				->with('owner')
				->with('task')
				->whereBetween('created_at', [
					Carbon::now()->subDays(40)->startOfDay(),
					// Carbon::now()->startOfMonth(),
					Carbon::now()->endOfMonth(),
				])
				->orderBy('id','asc')
			;

			// filter for task status
			if($status !== null){
				$res['runlog'][] = "added status filter";
				$records = $records->where('status',$status);
			}

			// filter for whether or not its paid
			if($with_results){
				$con = $with_results == 'yes';

				if($con){
					$records = $records->has('results');
				} else {
					$records = $records->doesntHave('results');
				}
			}

			if($pay_status){
				$con = $pay_status;

				$records = $records->whereHas('task', function($q) use ($con){
					$q->where('payment_status',"$con");
				});
			}

			if($sample_typ){
				$records = $records->where('sample_type',$sample_typ);
			}

			$records = $records->get()->toArray();

			// $extras = $extras->get()->toArray();
			// $records = $records->get()->merge($extras->get());
			// $records = [...$records, ...$extras];

			/*
			for($i = 0;$i < count($users);$i++){
				$users[$i]->role_name = $users[$i]->myRoles?->role_name;
				$users[$i]->role_permissions = $users[$i]->myRoles?->role_permissions;
			}
			*/

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

		if($iscalled){
			return $res;
		}

		return response()->json($res);
	}

	public function get_all(Request $req){
		$res = $this->dftres;
		// $alldata = $req->all();

		if($this->ili()){
			$res['success'] = true;
			$msg = "all samples retrieved";

			$perPage = $req->input('per_page', 10);
			$search = $req->input('search');
			$sortBy = $req->input('sort_by', 'created_at');
			$sortDirection = $req->input('sort_direction', 'desc');

			// filters
			$status = $req->input('status',null);
			$with_results = $req->input('with_results',null);
			$sample_typ = $req->input('sample_type',null);
			$pay_status = $req->input('payment_status',null);
			$t_range = $req->input('timerange',null);

			$sortBy = $sortBy == null ? 'created_at' : $sortBy;

			$res['runlog'][] = [
				"perPage" => $perPage,
				"search" => $search,
				"sortBy" => $sortBy,
				"sortDirection" => $sortDirection,
				"status" => $status,
				"with_results" => $with_results,
			];

			// clean up sortBy to prevent issues
			if($sortBy == 'owner'){
				$sortBy = 'client_id';
			} elseif($sortBy == 'task'){
				$sortBy = 'task_id';
			}

			// return response()->json($res);

			$records = Sample::query()
				->with('worksheets')
				->with('results')
				->withCount('results')
				->withCount('reports')
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
					->orWhere('sample_no', 'like', "%{$search}%")
					->orWhere('sample_tag', 'like', "%{$search}%")
				;
				$res['runlog'][] = "added search filter";
			}

			// filter for task status
			if($status !== null){
				$res['runlog'][] = "added status filter";
				$records = $records->where('status',$status);
			}

			// filter for whether or not its paid
			if($with_results){
				$con = $with_results == 'yes';

				if($con){
					$records = $records->has('results');
				} else {
					$records = $records->doesntHave('results');
				}
			}

			if($pay_status){
				$con = $pay_status;

				$records = $records->whereHas('task', function($q) use ($con){
					$q->where('payment_status',"$con");
				});
			}

			if($sample_typ){
				$records = $records->where('sample_type',$sample_typ);
			}

			if($t_range){
				$records = $records->timeRange($t_range);
			}

			$sortBy = $sortBy == null || $sortBy == '' ? 'created_at' : $sortBy;
			// $sortBy = ($sortBy == 'role_name') ? "user_roles_id" : $sortBy;

			$records = $records
				->orderBy($sortBy, $sortDirection)
				->paginate($perPage);

			/*
			for($i = 0;$i < count($users);$i++){
				$users[$i]->role_name = $users[$i]->myRoles?->role_name;
				$users[$i]->role_permissions = $users[$i]->myRoles?->role_permissions;
			}
			*/

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

	public function get_sample(Request $req) {
		$res = $this->dftres;
		// $alldata = $req->all();

		// /*
		$passed = $req->validate([
			'sample_id' => ['required','numeric'],
		]);
		// */


		if($this->ili()){
			$res['success'] = true;
			$msg = "fetching sample information";
			$reslt = false;

			// find a sample
			$therec = Sample::where('id',$passed['sample_id'])
				->with('worksheets')
				->with('results')
				->withCount('results')
				->withCount('reports')
				->with('mycreator')
				->with('owner')
				->with('task')
				->first()
			;

			// check if it exists
			if($therec == null){
				$msg = "sample not found";
			} else {
				$reslt = true;
				$msg = "samples fetched successfully";
			}

			$res['result'] = $reslt;
			$res['sendme'] = $therec;
			$res['message'] = $msg;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}

	public function get_samples(Request $req) {
		return $this->get_some($req);
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
			$msg = "fetching sample information";
			$reslt = false;
			$found = false;

			// find a sample
			$therecs = Sample::where('id','<>',null);

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
				$therecs = $therecs->where('id',$sample_id);
				$res['runlog'][] = 'sample_id found';
				$found = true;
			}
			if($added_by !== null){
				$therecs = $therecs->where('added_by',$added_by);
				$res['runlog'][] = 'added_by found';
				$found = true;
			}

			if($found){
				$therecs = $therecs->where('id','<>',null);
			}

			$therecs = $therecs
				->with('worksheets')
				->with('results')
				->withCount('results')
				->withCount('reports')
				->with('mycreator')
				->with('owner')
				->with('task')
				->get()
			;

			// check if it exists
			if(count($therecs) == 0){
				$msg = "sample not found";
			} else {
				$reslt = true;
				$msg = "samples fetched successfully";
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

	public function get_incomplete(Request $req){
		$res = $this->dftres;
		// $alldata = $req->all();

		if($this->ili()){
			$res['success'] = true;
			$msg = "incomplete samples retrieved";

			$perPage = $req->input('per_page', 10);
			$search = $req->input('search');
			$sortBy = $req->input('sort_by', 'created_at');
			$sortDirection = $req->input('sort_direction', 'desc');

			$sortBy = $sortBy == null ? 'created_at' : $sortBy;

			$records = Sample::query()
				->with('worksheets')
				->with('results')
				->withCount('results')
				->withCount('reports')
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
					->orWhere('sample_no', 'like', "%{$search}%")
					->orWhere('sample_tag', 'like', "%{$search}%")
				;
				$res['runlog'][] = "added search filter";
			}

			$records = $records
				->where('status',0)
				->orWhere('status',1)
			;

			$sortBy = $sortBy == null || $sortBy == '' ? 'created_at' : $sortBy;
			// $sortBy = ($sortBy == 'role_name') ? "user_roles_id" : $sortBy;

			$records = $records
				->orderBy($sortBy, $sortDirection)
				->paginate($perPage);

			/*
			for($i = 0;$i < count($users);$i++){
				$users[$i]->role_name = $users[$i]->myRoles?->role_name;
				$users[$i]->role_permissions = $users[$i]->myRoles?->role_permissions;
			}
			*/

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

	// setters
	public function change_sample_tag(Request $req,$iscalled = false,$p_user = null){
		$res = $this->dftres;
		$alldata = $req->all();
		$msg = "didnt even get to start";

		// /*
		$passed = $req->validate([
			'admin_password' => ['required'],
			'sample_tag' => ['required'],
			'sample_id' => ['required'],
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

		if($this->ili() || $iscalled){
			$res['success'] = true;
			$update_msg('starting the procedure');
			$reslt = false;

			$s_id = $passed['sample_id'];
			$s_tag = $passed['sample_tag'];
			$user = $p_user == null ? $this->cur_user() : $p_user;
			$adminpass = $passed['admin_password'];
			$ppw = '';

			$sample = Sample::query()->where('id',$s_id)->first();

			if($sample == null){
				$_msg("sample not found");
			} else {
				$_msg("sample found");

				$wegood = $this->verifyPassword($adminpass,$ppw,$user);

				if(!$wegood){
					$_msg("invalid admin password");
				} else {
					$_msg("valid password");

					$sample->sample_tag = $s_tag;
					$sample->save();
					$sample->refresh();
				}
			}

			$res['result'] = $reslt;
			$res['sendme'] = $sample;
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
	/*
	public function add_results(Request $req) {
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			'sample_id' => ['required'],
			'results' => ['required'],
		]);
		// * /


		if($this->ili()){
			$res['success'] = true;
			$msg = "submitting results";
			$reslt = false;
			$user = $this->cur_user();

			// confirm sample exists
			$thesample = Sample::where('id',$passed['sample_id'])
				->with('worksheets')
				->with('task')
				->withCount('results')
				->first()
			;

			if($thesample == null){
				$msg = "sample not found, try again";
			} else {
				// loop through passed results and figure out what to do with them
				$batch = app(Batch::class);
				$recs = [];
				$cols = [];

				foreach($passed['results'] as $rss){
					$rec = [
						'task_id' => $thesample->task_id,
						'sample_id' => $thesample->id,
						'client_id' => $thesample->client_id,
						'added_by' => $user->id,
						'sample_name' => $rss['sample_id'],
						'sample_type' => $thesample->sample_type,
						'au_ppm' => $rss['au_ppm'],
						'cu_ppm' => $rss['cu_ppm'],
					];
					$recs[] = $rec;
					$cols = array_keys($recs[0]);
				}

				$added = count($recs);
				$task = Task::where('id',$thesample->task_id)->first();
				$task->processed_sample_count += $added;
				$task->status = $task->sample_count >= $task->processed_sample_count ? 2 : 0;
				$task->save();

				$batch->insert(new Result,$cols,$recs);

				$rescount = $thesample->results_count + count($passed['results']);
				$newstate = $rescount >= $thesample->sample_count ? 2 : 1;

				$res['runlog'][] = [
					'results' => $thesample->results_count,
					'passed results' => count($passed['results']),
					'newstate' => count($passed['results']),
					'res count' => $rescount,
					'the condition' => $rescount >= $thesample->sample_count,
				];

				$thesample->status = $newstate;
				$thesample->save();

				$reslt = true;
				$msg = "Results added successfully";
			}

			$res['result'] = $reslt;
			$res['sendme'] = $reslt;
			$res['message'] = $msg;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}
	// */
}
