<?php

namespace App\Http\Controllers;

use App\Models\Result as ModelsResult;
use App\Models\Sample;
use App\Models\Worksheet;
use App\Models\Result;
use App\Models\Task;
use Carbon\Carbon;
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
	public function add_edit(Request $req,$iscalled = false,$p_user = null,$xtra = []){
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			's_data' => ['required','array'],
			// 'source' => ['required'],
			// 'sample_id' => ['required','numeric'],
		]);
		// */

		// housekeeping
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

			$logdata = [
				'where' => 'Jaytools_addtask',
				'intent' => 'add_task',
				'path' => './api/add_task',
				'uname' => 'API_guest',
				'source' => $req->input('source', null),
			];

		if($this->ili() || $iscalled){
			$res['success'] = true;
			$msg = "saving worksheet";
			$reslt = false;
			$w_sheet = null;
			$user = $p_user == null ? $this->cur_user() : $p_user;

			$s_id = $req->input('sample_id',null);
			$s_data = $passed['s_data'];

			$s_id = isset($xtra['s_id']) ? $xtra['s_id'] : $s_id;

			// check sample
			$thesample = Sample::where('id', $s_id)->first();

			if($thesample == null){
				$_msg("invalid sample id");
			} else {
				$_msg("sample found");

				$taskid = $thesample->task_id;
				$clientid = $thesample->client_id;
				$s_type = $thesample->sample_type;
				$s_count = $thesample->sample_count;
				$s_tag = $thesample->sample_tag;

				$toadd = [
					'task_id' => $taskid,
					'client_id' => $clientid,
					'sample_id' => $s_id,
					'sample_data' => $s_data,
					'sample_type' => $s_type,
					'sample_count' => $s_count,
					'sample_tag' => $s_tag,
				];

				$sheet = Worksheet::where('sample_id',$s_id)->first();

				// $res['runlog'][] = (['sheet' => $sheet]);
				$_msg('sheet has been found');
				$update_runlog(['user_id' => $user->id]);
				// return $res;

				if(!false){
					$_msg('commencing database modificato');
					if($sheet == null){
						$_msg("no worksheet found, creating");

						$toadd['created_by'] = $user->id;
						$toadd['last_updated_by'] = $user->id;

						$w_sheet = Worksheet::create($toadd);
						$reslt = true;
						$_msg("worksheet created successfully");
					} else {
						$_msg("worksheet found, running black magic to update");
						$i = 0;
						$new_data = [];

						if(count($sheet->sample_data) != count($s_data)){
							$_msg("wrong sample id");
						} else {
							foreach ($s_data as $entry) {
								$rec = $sheet[$i];
								$rec['sample_id'] = $entry['sample_id'];

								$new_data[] = $rec;
								$i += 1;
							}

							$sheet->sample_data = $new_data;
							$sheet->last_updated_by = $user->id;

							$update_runlog(['sheet before update' => $sheet]);
							$sheet->save();
							$sheet->refresh();

							$w_sheet = $sheet;
							$reslt = true;
							$msg = "worksheet updated successfully";
							$res['runlog'][] = $msg;
						}
					}
				} else {
					$_msg('skipping the entire database adding process');
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

		if($iscalled){
			return $res;
		} else {
			return response()->json($res);
		}
	}

	public function save_readings(Request $req,$iscalled = false,$p_user = null,$xtra = []){
		$res = $this->dftres;
		$res['extra'] = ['sheet' => null];
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
		$update_msg = function($wot,$logit = true) use (&$msg,&$update_runlog){
			$msg = $wot;
			if($logit){
				$update_runlog($wot);
			}
		};
		$_msg = function($w,$l = true) use (&$update_msg){
			$update_msg($w,$l);
		};
		$_rlg = function($w) use (&$update_runlog){
			$update_runlog($w);
		};

		if($this->ili() || $iscalled){
			$res['success'] = true;
			$user = $p_user == null ? $this->cur_user() : $p_user;
			$msg = "fetching permissions";
			$update_runlog($msg);
			$reslt = false;
			$w_sheet = null;

			$s_id = $passed['sample_id'];
			$s_readings = $passed['readings'];
			$mysheet = [];

			$thesample = Sample::query()
				->where('id',$s_id)
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
				$stype = $thesample->sample_type;

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

					// isolated calculater based functions
					$calculater = function($r) use($thesample, $user, $s_id, $mysheet, $_rlg, $update_runlog) {
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
							'au_ppm_2' => 7.77,
						];

						$calc_au_ppm = 707.77;
						$calc_cu_ppm = 707.77;
						$calc_activity = -1.33;
						$weight = $r['weight'];
						$au_reading = $r['au_reading'];
						$cu_reading = $r['cu_reading'];

						// calculate results based on sample type [done]
						$calculate_nermal = function() use(&$weight, &$au_reading, &$cu_reading, &$calc_au_ppm, &$calc_cu_ppm){
							$calc_au_ppm = ($au_reading * 4 * (250 - (0.35 * $weight))) / ($weight * 50);
							$calc_cu_ppm = (300 / $weight) * $cu_reading;
						};
						$calculate_soil = function() use(&$calculate_nermal){
							return $calculate_nermal();
						};
						$calculate_solution = function() use(&$weight, &$au_reading, &$cu_reading, &$calc_au_ppm, &$calc_cu_ppm){
							$calc_au_ppm = $au_reading;
							$calc_cu_ppm = $cu_reading;
						};
						$calculate_carbon = function() use(&$weight, &$au_reading, &$cu_reading, &$calc_au_ppm, &$calc_cu_ppm){
							$calc_au_ppm = $au_reading * 100;
							$calc_cu_ppm = $cu_reading * 100;

							/*
								$update_runlog([
									'calc_au' => "$au_reading * 100",
									'calc_cu' => "$cu_reading * 100",
									'au_ppm' => $calc_au_ppm,
									'cu_ppm' => $calc_cu_ppm,
								]);
							// */
						};
						$calculate_carbon_activity = function() use(&$weight, &$au_reading, &$cu_reading, &$calc_au_ppm, &$calc_cu_ppm,&$update_runlog,&$calc_activity){
							$act = $weight;
							$virgin = $au_reading;
							$initial = $cu_reading;

							$i = $initial;
							$v = $virgin;
							$a = $act;
							$clc = 0;

							$top = ($i - $a);
							$bottom = ($i - $v);
							if($bottom > 0){
								$clc = (float) $top / $bottom;
							}
							$calc_activity = $clc * 85;

							// /*
							$update_runlog("top: ($i - $a) = $top");
							$update_runlog("top: ($i - $v) = $bottom");
							$update_runlog("clc sum: ($top / $bottom) = $clc");
							$update_runlog("calc: $clc");
							$update_runlog("sum: $clc * 85");
							$update_runlog("final: $calc_activity");
							// */
						};
						$mek_null = function() use( &$calc_au_ppm, &$calc_cu_ppm, &$calc_activity){
							$calc_au_ppm = 0.00;
							$calc_cu_ppm = 0.00;
							$calc_activity = 0.00;
						};

						$stype = strtolower($thesample->sample_type);
						$_rlg("[calculater] type: $stype");
						// $is_nul = ;

						switch ($stype) {
							case 'soil':
								if($weight == 0){
									$mek_null();
								} else {
									$calculate_soil();
								}
								break;
							case 'carbon':
								$calculate_carbon();
								break;
							case 'solution':
								$calculate_solution();
								break;
							case 'carbon activity':
								// $update_runlog('processing carbon_activity');
								$calculate_carbon_activity();
								break;
							default:
								$calculate_nermal();
								break;
						}

						$tmp_rec['au_ppm'] = $calc_au_ppm;
						$tmp_rec['cu_ppm'] = $calc_cu_ppm;
						$tmp_rec['activity'] = $calc_activity;

						// fix up the name before saving
						$sname = str_contains($r['sample_id'], self::$readings_divider) ? substr($r['sample_id'],strlen(self::$readings_divider)) : $r['sample_id'];
						$tmp_rec['sample_name'] = $sname;

						return $tmp_rec;
					};
					$sort_this_mess = function($m){
						// so just a rudimentary sorting setup
						$ret = [];
						$sm = [];

						$getmax = function($nms){
							$max = -999999999999;
							$maxfound = false;

							foreach($nms as $n){
								if(!$maxfound){
									if($n < $max){
										$max = $n;
									}
								}

								if($n >= $max){
									$maxfound = true;
									$max = $n;
								}
							}

							return $max;
						};

						// $sm[] = $getmax($m);
						$lastnum = $getmax($m);
						$sm[] = $lastnum;
						$tmp_arr = $m;

						for($x = 1;$x < count($m);$x++){
							$key = array_search($lastnum, $tmp_arr);
							if($key !== false){
								unset($tmp_arr[$key]);
							}

							$lastnum = $getmax($tmp_arr);
							$sm[] = $lastnum;
						}

						return $sm;
					};

					$sitedata = self::getSiteData();

					if($sitedata['branch_name'] == 'migori'){
						// calculate results using the calculator setup
						// $nn = 0;
						foreach($parsed_readings as $r){
							if($r['is_final']){
								// $update_runlog($r);

								$tmp_rec = $calculater($r);
								$results[] = $tmp_rec;
								// $parsed_readings[$nn] = $r;

								$update_runlog($results);

								$cols = array_keys($tmp_rec);
							} else {
								// not meant to be saved so its ignored
							}

							// $nn += 1;
						}
					} else {
					// revamp to cater for the new result parameter
						// group readings based on their sample id
						$_rlg('grouping readings');
						$grouped = [];
						foreach($parsed_readings as $r){
							$my_sid = str_replace('RPT ', '', $r['sample_id']);
							// $_rlg(['sample_id' => $my_sid]);

							if(!isset($grouped[$my_sid])){
								// $_rlg('sample id not found');
								$grouped[$my_sid] = $r;
							} else {
								// $_rlg('sample id found');
								// $sid = $r['sample_id'];
								$already_in = $grouped[$my_sid];
								$xtradata = isset($already_in['xtras']) ? $already_in['xtras'] : [];
								$xtradata[] = $r;
								$already_in = [...$already_in, 'xtras' => $xtradata];
								$grouped[$my_sid] = $already_in;
							}
						}

						// demo of string replacement (gets rid of npt, check this to understand the sample_id isolation setup above)
						$str = "RPT notafari xxuv";
						$wot = str_replace('RPT ', '',$str);
						// $_msg($wot);

						$_rlg(['grouped' => $grouped]);
						$_rlg('trimming grouped readings');
						$newones = [];
						$in = 0;

						$stype = strtolower($thesample->sample_type);
						$_rlg(["sample type" => $stype]);
						$good = $stype == "carbon activity" || $stype == "solution";
						$_rlg(["its good" => $good]);

						foreach($grouped as $g){
							$g['is_def_checked'] = "yes matafaka";

							if(!$good){
								if($in < (count($grouped) - 1)){
									$newones[] = $g;
								}
							} else {
								$newones[] = $g;
							}

							$in += 1;
						}
						$_rlg(['newsetup' => $newones]);

						$finres = [];

						// working with the new setup after removing the reference materials from
						// the idea is to return one result entry per new setup
						foreach($newones as $ns){
							// $_rlg(['in_res' => $ns]);
							// $_rlg('processing parsed data');
							$picked = $ns;
							$n_res =  $calculater($ns);
							$cols = array_keys($n_res);
							$xts = isset($ns['xtras']) ? $ns['xtras'] : [];

							// $_rlg(['processing_this' => $ns]);

							$arr = [
								$ns,
								...$xts,
							];

							// $_rlg(['final_res_start' => $n_res]);
							$au_results = [];

							// calculate everything and get the fixed item record
							foreach($arr as $ar){
								$mres = $calculater($ar);
								$tmp_r = $mres;

								if($ar['is_final']){
									$n_res = $mres;
									// $_rlg(['[indata]' => 'is final']);
								} else {
									// $_rlg(['[indata]' => 'not final']);
								}

								// $_rlg(['indata_form' => $tmp_r]);
								$au_results[] = $mres['au_ppm'];
							}

							// $_rlg(['final_res_end' => $n_res]);
							$_rlg(['au_ppms' => $au_results]);

							$stype = strtolower($thesample->sample_type);
							if($stype != 'solution' && $stype != 'carbon activity'){
								if($stype == 'carbon'){
									// run the processing logic for carbon
									// do some black magic to sort the arrays in an order that wirks
									$sorted_au = $sort_this_mess($au_results);
									// $n_res['au_ppm'] = $sorted_au[1];
									$n_res['au_ppm_2'] = $sorted_au[2];
								} else {
									// just use the second gold value as the set thing
									$n_res['au_ppm'] = $au_results[1];
									$n_res['au_ppm_2'] = $au_results[0];
								}
							}

							// $finres[] = $arr;
							$results[] = $n_res;
						}

						$_rlg(['results' => $results]);
						// $_rlg(['finalised results' => $finres]);
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

			if($thesample !== null){
				$thesample->refresh();
				$mysheet->refresh();
			}

			$res['result'] = $reslt;
			$res['sendme'] = $thesample;
			$res['extra'] = ['sheet' => $mysheet];
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

	public function publish_one(Request $req,$iscalled = false,$p_user = null,$xtra = []){
		$res = $this->dftres;
		$res['extra'] = ['sheet' => []];
		$mysheet = [];
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			'sample_id' => ['required','numeric'],
		]);
		// */

		$update_runlog = function($wot) use (&$res){
			$res['runlog'][] = $wot;
		};

		if($this->ili() || $iscalled){
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
						$update_runlog($msg);
					} else {
						$msg = "publishing worksheet";
						$update_runlog($msg);

						$sheet->is_published = true;
						$sheet->published_at = Carbon::now();
						$sheet->save();

						$reslt = true;
						$msg = "worksheet published successfully";
						$update_runlog($msg);
					}
				}
			}

			if($thesample !== null){
				$thesample->refresh();
			}

			$res['result'] = $reslt;
			$res['sendme'] = $thesample;
			$res['message'] = $msg;
			$res['extra'] = $mysheet;
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
}
