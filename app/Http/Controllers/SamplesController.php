<?php

namespace App\Http\Controllers;

use App\Models\Sample;
use App\Models\Result;
use Illuminate\Http\Request;
use Mavinoo\Batch\Batch;

class SamplesController extends Controller{
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
			$task_status = $req->input('Status',null);
			$with_results = $req->input('with_results',null);
			$sample_typ = $req->input('sample_type',null);

			$sortBy = $sortBy == null ? 'created_at' : $sortBy;

			$res['runlog'][] = [
				"perPage" => $perPage,
				"search" => $search,
				"sortBy" => $sortBy,
				"sortDirection" => $sortDirection,
				"task_status" => $task_status,
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
				->with('results')
				->withCount('results')
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
			if($task_status){
				$records = $records->where('status',$task_status);
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

			if($sample_typ){
				$records = $records->where('sample_type',$sample_typ);
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
				->with('results')
				->withCount('results')
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

	public function add_results(Request $req) {
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			'sample_id' => ['required'],
			'results' => ['required'],
		]);
		// */


		if($this->ili()){
			$res['success'] = true;
			$msg = "submitting results";
			$reslt = false;
			$user = $this->cur_user();

			// confirm sample exists
			$thesample = Sample::where('id',$passed['sample_id'])
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
}
