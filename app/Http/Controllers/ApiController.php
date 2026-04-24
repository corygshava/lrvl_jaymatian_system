<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\FileopsController;
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
}
