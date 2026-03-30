<?php

namespace App\Http\Controllers;

use App\Models\Result as ModelsResult;
use App\Models\Sample;
use App\Models\Worksheet;
use App\Models\Result;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mavinoo\Batch\Batch;

class WorksheetsController extends Controller{
	public static $readings_divider = 'RPT ';
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

	public function save_readings(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			'sample_id' => ['required','numeric'],
			'readings' => ['required','array'],
		]);
		// */

		$update_runlog = function($wot) use (&$res){
			$res['runlog'][] = $wot;
		};


		if($this->ili()){
			$res['success'] = true;
			$user = $this->cur_user();
			$msg = "fetching permissions";
			$update_runlog($msg);
			$reslt = false;
			$w_sheet = null;

			$s_id = $passed['sample_id'];
			$s_readings = $passed['readings'];

			$thesample = Sample::where('id',$s_id)
				->with('worksheets')
				->with('results')
				->withCount('results')
				->withCount('reports')
				->with('mycreator')
				->with('owner')
				->with('task')
				->first()
			;

			if($thesample == null){
				$msg = "invalid sample id";
				$update_runlog($msg);
			} else {
				$msg = "sample found";
				$update_runlog($msg);

				try{
					// setup transactions block
					DB::beginTransaction();

					// important variables
					$batch = app(Batch::class);
					$cols = [];
					$parsed_readings = [];
					$results = [];

					// save readings first
					$mysheet = Worksheet::where('sample_id',$s_id)->first();

					if($mysheet == null){
						throw new \Exception("Worksheet not found", 1);
					}

					// sanitize readings
					foreach($s_readings as $r){
						$tmp_reading = [...$r];
						$tmp_reading['weight'] = (float) $r['weight'];
						$tmp_reading['au_reading'] = (float) $r['au_reading'];
						$tmp_reading['cu_reading'] = (float) $r['cu_reading'];

						$parsed_readings[] = $tmp_reading;
					}

					$mysheet->readings = $parsed_readings;
					$mysheet->has_readings = true;
					$mysheet->save();

					// calculate results using the calculator setup
					foreach($parsed_readings as $r){
						if($r['is_final']){
							$tmp_rec = [
								'task_id' => $thesample->task_id,
								'sample_id' => $s_id,
								'client_id' => $thesample->client_id,
								'added_by' => $user->id,
								'worksheet_id' => $mysheet->id,
								'last_updated_by' => $user->id,
								'sample_name' => $r['sample_id'],
								'sample_type' => $thesample->sample_type,
								'au_ppm' => 0.00,
								'cu_ppm' => 0.00,
							];
							$calc_au_ppm = 707.77;
							$calc_cu_ppm = 707.77;
							$weight = $r['weight'];
							$au_reading = $tmp_reading['au_reading'];
							$cu_reading = $tmp_reading['cu_reading'];

							// TODO: modify it to accomodate different sample types
							// calculate results based on sample type [incomplete]
							$calculate_nermal = function() use(&$weight, &$au_reading, &$cu_reading, &$calc_au_ppm, &$calc_cu_ppm){
								$calc_au_ppm = ($au_reading * 4 * (250 - (0.35 * $weight))) / ($weight * 50);
								$calc_cu_ppm = (300 / $weight) * $cu_reading;
							};
							$calculate_soil = function() use(&$weight, &$au_reading, &$cu_reading, &$calc_au_ppm, &$calc_cu_ppm,&$calculate_nermal){
								return $calculate_nermal();
							};

							switch (strtolower($thesample->sample_type)) {
								case 'soil':
									$calculate_soil();
									break;
								default:
									$calculate_nermal();
									break;
							}

							$tmp_rec['au_ppm'] = $calc_au_ppm;
							$tmp_rec['cu_ppm'] = $calc_cu_ppm;

							// fix up the name before saving
							$sname = str_contains($r['sample_id'], self::$readings_divider) ? substr($r['sample_id'],strlen(self::$readings_divider)) : $r['sample_id'];
							$tmp_rec['sample_name'] = $sname;

							$results[] = $tmp_rec;

							$cols = array_keys($tmp_rec);
						} else {
							// not meant to be saved so its ignored
						}
					}

					// delete all current results
					Result::where('sample_id',$s_id)->delete();

					// map results to results table
					if(count($results) > 0){
						$batch->insert(new Result,$cols,$results);
					}

					// update task and sample
					$thesample->status = 2;
					$thesample->save();

					$task = Task::where('id',$thesample->task_id)->with('samples')->first();

					if($task == null){
						throw new \Exception("invalid task assigned to sample");
					}

					// foreach($task)
					// $update_runlog($task->samples);
					$processed = 0;

					foreach($task->samples as $s){
						if($s->id !== $s_id){
							$processed += ($s->status == 2) ? $s->sample_count : 0;
						} else {
							$processed += $thesample->sample_count;
						}
					}

					$task->processed_sample_count = $processed;
					$task->status = $task->processed_sample_count >= $task->sample_count ? 2 : $task->status;
					$task->save();

					$reslt = true;
					$msg = 'readings saved successfully';
					$update_runlog($msg);

					DB::commit();
				} catch(\Exception $e) {
					$msg = 'Error: '.$e->getMessage();
					$update_runlog($msg);
					$update_runlog($e);

					DB::rollBack();
					$update_runlog('changes rolled back');
				}
			}

			$thesample->refresh();

			$res['result'] = $reslt;
			$res['sendme'] = $thesample;
			$res['message'] = $msg;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}

	public function publish_one(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			'sample_id' => ['required','numeric'],
		]);
		// */

		$update_runlog = function($wot) use (&$res){
			$res['runlog'][] = $wot;
		};


		if($this->ili()){
			$res['success'] = true;
			$msg = "fetching permissions";
			$update_runlog($msg);
			$reslt = false;

			$s_id = $passed['sample_id'];

			$thesample = Sample::where('id',$s_id)
				->with('worksheets')
				->with('results')
				->withCount('results')
				->withCount('reports')
				->with('mycreator')
				->with('owner')
				->with('task')
				->first()
			;

			// TODO: finish the publish worksheet thingy
			if($thesample == null){
				$msg = "invalid sample passed";
			} else {
				$sheet = Worksheet::where('sample_id',$s_id)->first();

				if($sheet == null){
					$msg = "Sample doesnt have a worksheet";
				} else {
					$msg = "Worksheet found";
					$update_runlog($sheet);

					if($sheet->has_readings === false){
						$msg = "you cant publish a worksheet without readings";
					} else {
						$msg = "publishing worksheet";

						$sheet->is_published = true;
						$sheet->save();
						$reslt = true;
						$msg = "worksheet published successfully";
					}
				}
			}

			$res['result'] = $reslt;
			$res['sendme'] = $thesample;
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
