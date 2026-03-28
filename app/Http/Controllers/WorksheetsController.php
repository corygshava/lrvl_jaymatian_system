<?php

namespace App\Http\Controllers;

use App\Models\Sample;
use App\Models\Worksheet;
use Illuminate\Http\Request;

class WorksheetsController extends Controller{
	// get ops
    public function get_all(Request $req){
		$res = $this->dftres;
		// $alldata = $req->all();

		if($this->ili()){
			$res['success'] = true;
			$msg = "all worksheets retrieved";

			$perPage = $req->input('per_page', 10);
			$search = $req->input('search');
			$sortBy = $req->input('sort_by', 'created_at');
			$sortDirection = $req->input('sort_direction', 'desc');

			// filters
			$status = $req->input('status',null);

			$sortBy = $sortBy == null ? 'created_at' : $sortBy;

			$res['runlog'][] = [
				"perPage" => $perPage,
				"search" => $search,
				"sortBy" => $sortBy,
				"sortDirection" => $sortDirection,
				"status" => $status,
			];

			// clean up sortBy to prevent issues
			if($sortBy == 'owner'){
				$sortBy = 'client_id';
			} elseif($sortBy == 'task'){
				$sortBy = 'task_id';
			}

			// return response()->json($res);

			$records = Worksheet::query()
				->with('task')
				->with('client')
				->with('creator')
				->with('sample')
				->with('lastupdater')
			;

			if($search){
				$records = $records
					->whereHas('client', function($q) use ($search){
						$q->where('client_name','like',"%{$search}%");
					})
					->orWhereHas('task', function($q) use ($search){
						$q->where('task_no','LIKE',"%{$search}%");
					})
					// ->orWhere('sample_no', 'like', "%{$search}%")
				;
				$res['runlog'][] = "added search filter";
			}

			// filter for task status
			if($status !== null){
				$res['runlog'][] = "added status filter";
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
	public function get_one(Request $req) {
		$res = $this->dftres;
		// $alldata = $req->all();

		// /*
		$passed = $req->validate([
			'worksheet_id' => ['required','numeric'],
		]);
		// */


		if($this->ili()){
			$res['success'] = true;
			$msg = "fetching worksheet information";
			$reslt = false;

			// find a sample
			$therec = Worksheet::where('id',$passed['worksheet_id'])
				->with('task')
				->with('client')
				->with('creator')
				->with('sample')
				->with('lastupdater')
			;

			// check if it exists
			if($therec == null){
				$msg = "worksheet not found";
			} else {
				$reslt = true;
				$msg = "worksheet fetched successfully";
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
	public function get_worksheets(Request $req) {
		return $this->get_some($req);
	}
	public function get_some(Request $req) {
		$res = $this->dftres;
		$alldata = $req->all();

		$task_id = isset($alldata['task_id']) ? $alldata['task_id'] : null;
		$client_id = isset($alldata['client_id']) ? $alldata['client_id'] : null;
		$sample_id = isset($alldata['sample_id']) ? $alldata['sample_id'] : null;
		$added_by = isset($alldata['added_by']) ? $alldata['added_by'] : null;

		$row = 'id';

		if($this->ili()){
			$res['success'] = true;
			$msg = "fetching worksheets";
			$reslt = false;
			$found = false;

			// find a sample
			$therecs = Worksheet::where('id','<>',null);

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
				$therecs = $therecs->where('created_by',$added_by);
				$res['runlog'][] = 'added_by found';
				$found = true;
			}

			if($found){
				$therecs = $therecs->where('id','<>',null);
			}

			$therecs = $therecs
				->with('task')
				->with('client')
				->with('creator')
				->with('sample')
				->with('lastupdater')
				->get()
			;

			// check if it exists
			if(count($therecs) == 0){
				$msg = "worksheets not found";
			} else {
				$reslt = true;
				$msg = "worksheets fetched successfully";
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

	// set ops
	public function add_edit(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			's_data' => ['required','array'],
			'sample_id' => ['required','numeric'],
		]);
		// */

		if($this->ili()){
			$res['success'] = true;
			$msg = "saving worksheet";
			$reslt = false;
			$w_sheet = null;
			$user = $this->cur_user();

			$s_id = $passed['sample_id'];
			$s_data = $passed['s_data'];

			// check sample
			$thesample = Sample::where('id',$s_id)->first();

			if($thesample == null){
				$msg = "invalid sample id";
				$res['runlog'][] = $msg;
			} else {
				$msg = "sample found";
				$res['runlog'][] = $msg;

				$taskid = $thesample->task_id;
				$clientid = $thesample->client_id;
				$s_type = $thesample->sample_type;
				$s_count = $thesample->sample_count;

				$toadd = [
					'task_id' => $taskid,
					'client_id' => $clientid,
					'sample_id' => $s_id,
					'sample_data' => $s_data,
					'sample_type' => $s_type,
					'sample_count' => $s_count,
				];

				$sheet = Worksheet::where('sample_id',$s_id)->first();

				if($sheet == null){
					$msg = "no worksheet found, creating";
					$res['runlog'][] = $msg;

					$toadd['created_by'] = $user->id;
					$toadd['last_updated_by'] = $user->id;

					$w_sheet = Worksheet::create($toadd);
					$reslt = true;
					$msg = "worksheet created successfully";
					$res['runlog'][] = $msg;
				} else {
					$msg = "worksheet found, running black magic to update";
					$res['runlog'][] = $msg;
					$i = 0;
					$new_data = [];

					if(count($sheet->sample_data) != count($s_data)){
						$msg = "wrong sample id";
						$res['runlog'][] = $msg;
					} else {
						foreach ($s_data as $entry) {
							$rec = $sheet[$i];
							$rec['sample_id'] = $entry['sample_id'];

							$new_data[] = $rec;
							$i += 1;
						}

						$sheet->sample_data = $new_data;
						$sheet->save();
						$sheet->refresh();

						$w_sheet = $sheet;
						$reslt = true;
						$msg = "worksheet updated successfully";
						$res['runlog'][] = $msg;
					}
				}

				$res['runlog'][] = $toadd;
			}

			$res['result'] = $reslt;
			$res['sendme'] = $w_sheet;
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
