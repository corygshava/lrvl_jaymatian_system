<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpenseController extends Controller{
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
			$t_range = $req->input('timerange',null);

			// filters
			$ex_type = $req->input('expense_type',null);

			$sortBy = $sortBy == null ? 'created_at' : $sortBy;

			$res['runlog'][] = [
				"perPage" => $perPage,
				"search" => $search,
				"sortBy" => $sortBy,
				"sortDirection" => $sortDirection,
				"expense_type" => $ex_type,
			];

			// clean up sortBy to prevent issues
			if($sortBy == 'mycreator'){
				$sortBy = 'added_by';
			}

			// return response()->json($res);

			$records = Expense::query()
				->with('mycreator')
			;

			if($search){
				$records = $records
					->whereHas('mycreator', function($q) use ($search){
						$q->where('name','like',"%{$search}%")
							->orWhere('email','like',"%{$search}%")
						;
					})
					->orWhere('amount', 'like', "%{$search}%")
					// ->orWhere('sample_tag', 'like', "%{$search}%")
				;
				$res['runlog'][] = "added search filter";
			}

			// filter for task status
			if($ex_type){
				$records = $records->where('purpose',$ex_type);
			}

			if($t_range){
				$records = $records->timeRange($t_range);
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
    public function add_new(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();
		$msg = "didnt even get to start";

		// /*
		$passed = $req->validate([
			'admin_password' => ['required','string'],
			'purpose' => ['required','string'],
			'amount' => ['required','numeric'],
		]);
		$purpose = $passed['purpose'];
		$options = [
			'bank transfer',
			'boss expenditure',
			'lab expense',
			'salary',
			'government issue',
			'home expense',
		];
		$limits = [
			'expenditure' => 2,
			'bank transfer' => 3,
		];
		$mypermission = strtolower($purpose) == 'expenditure' ? 'ADD_EXPENSE' : 'ADD_TRANSFER';
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
			$update_msg('starting the procedure');
			$reslt = false;
			$newguy = null;
			$user = $this->cur_user();

			$purpose = $passed['purpose'];
			$amount = floatval($passed['amount']);
			$desc = $req->input('desc',"---");
			$validuse = in_array(strtolower($purpose),$options);

			if(!$validuse){
				$_msg('invalid purpose given');
			} else {
				$lastguys = Expense::timeRange('today')->where('purpose',$purpose)->latest()->get();
				// $mylimit = $limits[strtolower($purpose)];
				$mylimit = 120;
				$populus = count($lastguys);
				$wegood = $populus == 0 ? true : $lastguys[0]->created_at->lt(Carbon::now()->subDay());
				$showamt = str_pad("$populus",4,'0',STR_PAD_LEFT);
				$serial = "EXP_{$showamt}";

				if(count($lastguys)){
					$wegood = count($lastguys) < $mylimit;
				}

				if(!$wegood){
					$_msg("limit for $purpose, is $mylimit per day");
				} else {
					$_msg("saving today's $purpose");

					$toadd = [
						'purpose' => $purpose,
						'added_by' => $user->id,
						'amount' => $amount,
						'expenditure_no' => $serial,
						'desc' => $desc,
					];
					$update_runlog('adding the following');
					$update_runlog($toadd);
					Expense::create($toadd);

					$reslt = true;
				}
			}

			$res['result'] = $reslt;
			$res['sendme'] = $newguy;
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
