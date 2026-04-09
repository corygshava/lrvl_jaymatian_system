<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\FileopsController;

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

	public function log_intent(Request $req){
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
		$user = "guest";

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
}
