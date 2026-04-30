<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\FileopsController;
use App\Http\Controllers\WorksheetsController;
use App\Models\Sample;
use App\Models\User;

class ApiController extends Controller{
	public function test(Request $req){
		$res = $this->dftres;
		$res["success"] = true;
		$res["result"] = true;
		$res['message'] = "welcome to our API";
		return $res;
	}

	public function log_visit(Request $req){
		// writes to visitslog.json in the format [date] [country] -> page visited
		$fl = new FileopsController();
		$date = date('my');
		$fpath = storage_path("runtime/visits_logs/{$date}_visitlogs.json");

		$logs = [];
		$vl = $req->all();
		$where = isset($vl['where']) ? $vl['where'] : "??";
		$user = "guest";

		if(!is_file($fpath)){
			$fl::c_file($fpath);
		} else {
			$rdata = $fl::saferead($fpath,true,24);
			$logs = $rdata == '' || $rdata === false ? $logs : json_decode($rdata);
		}

		$timestamp = date('d-m-y H:i:s');
		$country = strtoupper(self::getUserCountry($req));

		if(auth()->check()){
			$user = auth()->user()->name;
		}

		$logline = [
			"timestamp" => $timestamp,
			"country" => $country,
			"location" => $where,
			"user" => $user,
		];
		$logs[] = $logline;
		$tosave = json_encode($logs,JSON_PRETTY_PRINT);
		$wirked = $fl::safewrite_txt($fpath,$tosave,true,24);

		return response()->json($wirked);
	}

	public function log_intent(Request $req,$xta = null){
		// writes to visitslog.json in the format [date] [country] -> page visited
		$fl = new FileopsController();
		$date = date('ymd');
		$year = date('y');
		$mon = date('m');
		$cc = $req->input('ccsx','00');
		$fpath = storage_path("runtime/intent_logs/{$year}/{$mon}/{$date}_{$cc}_intentlogs.json");

		$logs = [];
		$vl = $req->all();
		$where = isset($vl['where']) ? $vl['where'] : ($xta != null ? $xta["where"] : "??");
		$intent = isset($vl['intent']) ? $vl['intent'] : ($xta != null ? $xta["intent"] : "??");
		$path = isset($vl['path']) ? $vl['path'] : ($xta != null ? $xta["path"] : "??");
		$user = isset($vl['uname']) ? $vl['uname'] : ($xta != null ? $xta["uname"] : "Guest");

		if(!is_file($fpath)){
			$fl::c_file($fpath);
		} else {
			$rdata = $fl::saferead($fpath,true,24);
			$logs = $rdata == '' || $rdata === false ? $logs : json_decode($rdata);
		}

		$timestamp = date('d-m-y H:i:s');
		$country = strtoupper(self::getUserCountry($req));

		$logline = [
			"timestamp" => $timestamp,
			"country" => $country,
			"location" => $where,
			"user_id" => $cc,
			"intent" => $intent,
			"path" => $path,
		];
		$logs[] = $logline;
		$tosave = json_encode($logs,JSON_PRETTY_PRINT);
		$wirked = $fl::safewrite_txt($fpath,$tosave,true,24);

		return response()->json($wirked);
	}

	public function log_error(Request $req){
		// writes to visitslog.json in the format [date] [country] -> page visited
		$fl = new FileopsController();
		$date = date('ymd');
		$year = date('y');
		$mon = date('m');
		$cc = $req->input('ccsx','00');
		$fpath = storage_path("runtime/intent_logs/{$year}/{$mon}/{$date}_{$cc}_visitlogs.json");

		$logs = [];
		$vl = $req->all();
		$where = isset($vl['where']) ? $vl['where'] : "??";
		$intent = isset($vl['intent']) ? $vl['intent'] : "??";
		$path = isset($vl['path']) ? $vl['path'] : "??";
		$error = isset($vl['error']) ? $vl['error'] : "??";

		if(!is_file($fpath)){
			$fl::c_file($fpath);
		} else {
			$rdata = $fl::saferead($fpath,true,24);
			$logs = $rdata == '' || $rdata === false ? $logs : json_decode($rdata);
		}

		$timestamp = date('d-m-y H:i:s');
		$country = strtoupper(self::getUserCountry($req));

		$logline = [
			"timestamp" => $timestamp,
			"country" => $country,
			"location" => $where,
			"user_id" => $cc,
			"intent" => $intent,
			"path" => $path,
			"error" => $error,
		];
		$logs[] = $logline;
		$tosave = json_encode($logs,JSON_PRETTY_PRINT);
		$wirked = $fl::safewrite_txt($fpath,$tosave,true,24);

		return response()->json($wirked);
	}

	public function check_user(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();
		$res['indata'] = $alldata;
		$msg = "didnt even get to start";
		$outcome = [];

		// /*
		$passed = $req->validate([
			'email' => ['required'],
			'pw' => ['required'],
			'source' => ['required'],
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
				'where' => 'Jaytools_login',
				'intent' => 'checkuser',
				'path' => './api/checkuser',
				'uname' => 'API_guest',
				'source' => $passed['source'],
			];

		if(true){
			$res['success'] = true;
			$update_msg('starting the procedure');
			$reslt = false;

			$email = $passed['email'];
			$pw = $passed['pw'];
			$newpw = null;

			$user = User::where('email',$email)->with('myRoles')->first();

			if($user == null){
				$_msg('user not found');
			} else {
				$wegood = $this->verifyPassword($pw,$newpw,$user);
				$logdata['uname'] = $user->name;

				if(!$wegood){
					$_msg('invalid password');
				} else {
					$_msg('user details fetched successfully');
					$reslt = true;
					$outcome = $user->toArray();
				}
			}

			$res['result'] = $reslt;
			$res['sendme'] = $outcome;
			$res['message'] = $msg;
		}

		$this->log_intent($req,$logdata);

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}

	public function get_all_clients(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();
		$res['indata'] = $alldata;
		$msg = "didnt even get to start";
		$outcome = [];

		// /*
		$passed = $req->validate([
			'email' => ['required'],
			'pw' => ['required'],
			'source' => ['required'],
		]);
		// */

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

			$logdata = [
				'where' => 'Jaytools_get_clients',
				'intent' => 'get_all_clients',
				'path' => './api/get_clients_motherlode',
				'uname' => 'unauthorized User',
				'source' => $passed['source'],
			];

		if(true){
			$res['success'] = true;
			$update_msg('starting the procedure');
			$reslt = false;

			$email = $passed['email'];
			$pw = $passed['pw'];
			$newpw = null;

			$user = User::where('email',$email)->with('myRoles')->first();

			if($user == null){
				$_msg('user not found');
			} else {
				$wegood = $this->verifyPassword($pw,$newpw,$user);
				$logdata['uname'] = $user->name;

				if(!$wegood){
					$_msg('invalid password');
				} else {
					$_msg('user details verified');
					$reslt = true;

					// get all clients the same way client mgt gets users
					// or just modify clients mgt to return the stuff you need
					$cl = new ClientController();
					$outcome = $cl->get_everything($req,true);
					$_msg('all clients fetched');
				}
			}

			$res['result'] = $reslt;
			$res['sendme'] = $outcome;
			$res['message'] = $msg;
		}

		$this->log_intent($req,$logdata);

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}

	public function add_client(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();
		$res['indata'] = $alldata;
		$msg = "didnt even get to start";
		$outcome = [];

		// /*
		$passed = $req->validate([
			'email' => ['required'],
			'pw' => ['required'],
			'source' => ['required'],
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
				'where' => 'Jaytools_login',
				'intent' => 'checkuser',
				'path' => './api/checkuser',
				'uname' => 'API_guest',
				'source' => $passed['source'],
			];

		if(true){
			$res['success'] = true;
			$_msg('starting the procedure');
			$reslt = false;

			$email = $passed['email'];
			$pw = $passed['pw'];
			$newpw = null;

			$user = User::where('email',$email)->with('myRoles')->first();

			if($user == null){
				$_msg('user not found');
			} else {
				$_msg('user found');
				$wegood = $this->verifyPassword($pw,$newpw,$user);
				$logdata['uname'] = $user->name;

				if(!$wegood){
					$_msg('invalid password');
				} else {
					$_msg('user details fetched successfully');

					$cl = new ClientController();
					$adder = $cl->add_edit($req,true,$user);

					$reslt = $adder['result'];
					$res['runlog'] = [...$res['runlog'],...$adder['runlog']];
					$outt = $adder['sendme'];
					$msg = $adder['message'];
					$outcome = ($outt == null) ? null : $outt->toArray();
				}
			}

			$res['result'] = $reslt;
			$res['sendme'] = $outcome;
			$res['message'] = $msg;
		}

		$this->log_intent($req,$logdata);

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}

	public function add_task(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();
		$res['indata'] = $alldata;
		$msg = "didnt even get to start";
		$outcome = [];

		// /*
		$passed = $req->validate([
			'email' => ['required'],
			'pw' => ['required'],
			'source' => ['required'],
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
				'source' => $passed['source'],
			];

		if(true){
			$res['success'] = true;
			$update_msg('starting the procedure');
			$reslt = false;

			$email = $passed['email'];
			$pw = $passed['pw'];
			$newpw = null;

			$user = User::where('email',$email)->with('myRoles')->first();

			if($user == null){
				$_msg('user not found');
			} else {
				$wegood = $this->verifyPassword($pw,$newpw,$user);
				$logdata['uname'] = $user->name;

				if(!$wegood){
					$_msg('invalid password');
				} else {
					$_msg('user details fetched successfully');

					$cl = new TaskController();
					$adder = $cl->add_new_task($req,true,$user);

					$reslt = $adder['result'];
					$res['runlog'] = [...$res['runlog'],...$adder['runlog']];
					$outt = $adder['sendme'];
					$msg = $adder['message'];
					// $outcome = ($outt == null) ? null : $outt->toArray();
					$outcome = $outt;
				}
			}

			$res['result'] = $reslt;
			$res['sendme'] = $outcome;
			$res['message'] = $msg;
		}

		$this->log_intent($req,$logdata);

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}

	public function save_worksheet(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();
		$res['indata'] = $alldata;
		$msg = "didnt even get to start";
		$outcome = [];

		// /*
		$passed = $req->validate([
			'email' => ['required'],
			'pw' => ['required'],
			'source' => ['required'],
			's_data' => ['required','array'],
			'sample_count' => ['required'],
			'sample_tag' => ['required'],
			'sample_type' => ['required'],
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
				'source' => $passed['source'],
			];

		if(true){
			$res['success'] = true;
			$update_msg('starting the procedure');
			$reslt = false;

			$email = $passed['email'];
			$pw = $passed['pw'];
			$newpw = null;

			$user = User::where('email',$email)->with('myRoles')->first();

			if($user == null){
				$_msg('user not found');
			} else {
				$wegood = $this->verifyPassword($pw,$newpw,$user);
				$logdata['uname'] = $user->name;

				if(!$wegood){
					$_msg('invalid password');
				} else {
					$_msg('user details fetched successfully');

					// run a complex 3 part magni search to find the sample
					$s_amt = $passed['sample_count'];
					$s_tag = $passed['sample_tag'];
					$s_type = $passed['sample_type'];

					$mysample = Sample::query()
						->where('sample_type',$s_type)
						->where('sample_count',$s_amt)
						->where('sample_tag',$s_tag)
						->where('status',0)
						->first()
					;

					if($mysample == null){
						$_msg('invalid sample details');
					} else {
						$_msg('sample found');

						$s_id = $mysample->id;
						// $req['sample_id_injected'] = $s_id;
						$xtradata = ['s_id' => $s_id,'uid' => $user->id];

						$update_runlog($xtradata);
						$cl = new WorksheetsController();
						$adder = $cl->add_edit($req,true,$user,$xtradata);
						// $adder = $res;

						$update_runlog(['from worksheet' => $adder]);

						// $update_runlog([''])
						$reslt = $adder['result'];
						$res['runlog'] = [...$res['runlog'],...$adder['runlog']];
						$outt = $adder['sendme'];
						$msg = $adder['message'];
						// $outcome = ($outt == null) ? null : $outt->toArray();
						$outcome = $outt;
					}
				}
			}

			$res['result'] = $reslt;
			$res['sendme'] = $outcome;
			$res['message'] = $msg;
		}

		$this->log_intent($req,$logdata);

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}
}
