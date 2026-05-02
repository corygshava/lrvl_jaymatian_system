<?php

namespace App\Http\Controllers;

use App\Models\BizData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BizdataController extends Controller{
	public function get_all(Request $req,$iscalled = false){
		$res = $this->dftres;
		$alldata = $req->all();

		/*
		$passed = $req->validate([
			'condition' => ['required']
		]);
		// */


		if($this->ili() || $iscalled){
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

		if($iscalled){
			return $res;
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

	public function update_sth_2(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			'admin_password_mine' => ['required'],
			'itemname' => ['required'],
		]);
		// */

		$itemname = $passed['itemname'];
		// $itemvalue = $passed['itemvalue'];

		if($this->ili()){
			$res['success'] = true;
			$msg = "business information updated successfully";
			$reslt = false;
			$bizdata = null;
			$wegood = $this->verifyPassword($passed['admin_password_mine']);
			$user = $this->cur_user();

			if(!$wegood){
				$msg = "invalid admin password";
			}else {
				$bizdata = BizData::where('item_name',$itemname)->first();

				// process files
				$fyls = [];
				$newfyls = [];
				$oldfyls = [];

				// clean up old ones
				if($bizdata != null){
					$oldfyls = $bizdata->item_data;
				} else {
					$res['runlog'][] = "bizdata is null";
				}

				// upload passed images
				$fkey = 'company_logo';
				if($req->hasFile($fkey) && $req->file($fkey)->isValid()){
					$fyl = $req->file($fkey);
					$rndr = $this->mekRandomString(5);
					$ext = $fyl->getClientOriginalExtension();
					$fname = "[{$rndr}]_{$fkey}.{$ext}";
					$fyl->move(storage_path(self::$bizfiles_path),$fname);
					$newfyls[$fkey] = $fname;
				}
				$fkey = 'Technician_signature';
				if($req->hasFile($fkey) && $req->file($fkey)->isValid()){
					$fyl = $req->file($fkey);
					$rndr = $this->mekRandomString(5);
					$ext = $fyl->getClientOriginalExtension();
					$fname = "[{$rndr}]_{$fkey}.{$ext}";
					$fyl->move(storage_path(self::$bizfiles_path),$fname);
					$newfyls[$fkey] = $fname;
				}
				$fkey = 'supervisor_signature';
				if($req->hasFile($fkey) && $req->file($fkey)->isValid()){
					$fyl = $req->file($fkey);
					$rndr = $this->mekRandomString(5);
					$ext = $fyl->getClientOriginalExtension();
					$fname = "[{$rndr}]_{$fkey}.{$ext}";
					$fyl->move(storage_path(self::$bizfiles_path),$fname);
					$newfyls[$fkey] = $fname;
				}
				$fkey = 'official_stamp';
				if($req->hasFile($fkey) && $req->file($fkey)->isValid()){
					$fyl = $req->file($fkey);
					$rndr = $this->mekRandomString(5);
					$ext = $fyl->getClientOriginalExtension();
					$fname = "[{$rndr}]_{$fkey}.{$ext}";
					$fyl->move(storage_path(self::$bizfiles_path),$fname);
					$newfyls[$fkey] = $fname;
				}

				$res['runlog'][] = ["files_to_save" => $fyls];
				$fyls = [...$oldfyls,...$newfyls];

				// add details to db
				$topass = [
					'modified_by' => $user->id,
					'item_name' => $itemname,
					'item_data' => $fyls,
				];

				if($bizdata == null){
					$topass['created_by'] = $user->id;
					$bizdata = BizData::create($topass);

					$msg = "bizdata added successfully";
				} else {
					$killfiles = [];
					$killfiles_logged = [];

					foreach($oldfyls as $fname => $f){
						if(in_array($fname, array_keys($newfyls))){
							$pth = self::$bizfiles_path;
							$fpath = "{$pth}{$f}";
							$killfiles[] = storage_path($fpath);
							$killfiles_logged[] = $fpath;
						}
					}

					$delres = File::delete($killfiles);

					$res['runlog'][] = ["saved" => $oldfyls];
					$res['runlog'][] = ["deleted" => $killfiles_logged];
					$res['runlog'][] = $delres ? "deletion successful" : "Error deleting the files (some dont exist)";

					$bizdata->update($topass);

					$msg = "bizdata updated successfully";
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
