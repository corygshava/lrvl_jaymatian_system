<?php

namespace App\Http\Controllers;

use App\Models\ReportsGen;
use Illuminate\Http\Request;

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
			// $sample_typ = $req->input('sample_type',null);

			$sortBy = $sortBy == null ? 'created_at' : $sortBy;

			$res['runlog'][] = [
				"perPage" => $perPage,
				"search" => $search,
				"sortBy" => $sortBy,
				"sortDirection" => $sortDirection,
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
}
