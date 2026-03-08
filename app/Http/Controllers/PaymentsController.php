<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentsController extends Controller{
	public function get_all(Request $req){
		$res = $this->dftres;
		// $alldata = $req->all();

		if($this->ili()){
			$res['success'] = true;
			$msg = "all payments retrieved";

			$perPage = $req->input('per_page', 10);
			$search = $req->input('search');
			$sortBy = $req->input('sort_by', 'created_at');
			$sortDirection = $req->input('sort_direction', 'desc');

			// filters
			$task_status = $req->input('status',null);
			$pay_method = $req->input('method',null);

			$sortBy = $sortBy == null ? 'created_at' : $sortBy;

			$res['runlog'][] = [
				"perPage" => $perPage,
				"search" => $search,
				"sortBy" => $sortBy,
				"sortDirection" => $sortDirection,
				"task_status" => $task_status,
				"pay_method" => $pay_method,
			];

			// clean up sortBy to prevent issues
			if($sortBy == 'owner'){
				$sortBy = 'client_id';
			} elseif($sortBy == 'task'){
				$sortBy = 'task_id';
			}

			// return response()->json($res);

			$records = Payment::query()
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
					->orWhere('amount', 'like', "%{$search}%")
					// ->orWhere('sample_tag', 'like', "%{$search}%")
				;
				$res['runlog'][] = "added search filter";
			}

			// filter for task status
			if($task_status){
				$records = $records->where('status',$task_status);
			}

			// filter for whether or not its paid
			if($pay_method){
				$records = $records->where('method',$pay_method);
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
			$msg = "fetching payment information";
			$reslt = false;
			$found = false;

			// find a sample
			$therecs = Payment::where('id','<>',null);

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
				->with('mycreator')
				->with('owner')
				->with('task')
				->get()
			;

			// check if it exists
			if(count($therecs) == 0){
				$msg = "No Payments found";
			} else {
				$msg = "Payments fetched successfully";
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
