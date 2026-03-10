<?php

namespace App\Http\Controllers;

use App\Models\BizData;
use Illuminate\Http\Request;

class BizdataController extends Controller{
	public function get_all(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		/*
		$passed = $req->validate([
			'condition' => ['required']
		]);
		// */


		if($this->ili()){
			$res['success'] = true;
			$msg = "fetching business data";
			$reslt = false;

			$bizdata = BizData::all();
			$reslt = true;

			$res['result'] = $reslt;
			$res['sendme'] = $bizdata;
			$res['message'] = $msg;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}
    public function update_sth(Request $req) {
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			'admin_password' => ['required'],
			'itemname' => ['required'],
			'itemvalue' => ['required'],
		]);
		// */

		$itemname = $passed['itemname'];
		$itemvalue = $passed['itemvalue'];

		if($this->ili()){
			$res['success'] = true;
			$msg = "business information updated successfully";
			$reslt = false;
			$bizdata = null;
			$wegood = $this->verifyPassword($passed['admin_password']);
			$user = $this->cur_user();

			if(!$wegood){
				$msg = "invalid admin password";
			}else {
				$bizdata = BizData::where('item_name',$itemname)->first();

				if($bizdata == null){
					$bizdata = BizData::create([
						'created_by' => $user->id,
						'modified_by' => $user->id,
						'item_name' => $itemname,
						'item_data' => $itemvalue,
					]);

					// $msg = "bizdata updated successfully";
				} else {
					$bizdata->item_data = $itemvalue;
					$bizdata->modified_by = $user->id;
					$bizdata->save();

					// $msg = "bizdata updated successfully";
				}

				$msg = "bizdata updated successfully";
				$reslt = true;
			}

			$res['result'] = $reslt;
			$res['sendme'] = $bizdata;
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
