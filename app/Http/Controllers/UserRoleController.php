<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\userRoles;
use Illuminate\Http\Request;

class UserRoleController extends Controller{
	public function get_all(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		if($this->ili()){
			$res['success'] = true;
			$res['message'] = "all userRoles retrieved";

			$sysroles = userRoles::withCount('users')->get();
			// $mdta = random_int(0,14);

			$res['result'] = true;
			$res['sendme'] = $sysroles;
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

		// /*
		$passed = $req->validate([
			'user_role_name' => ['required']
		]);
		$recid = isset($alldata['recid']) ? $alldata['recid'] : null;
		// */

		if($this->ili()){
			$res['success'] = true;
			$msg = "payload stored";
			$reslt = false;

			// $op = self::log_payload($alldata,'indata');
			$perms = explode(',',strip_tags($alldata['permissions']));
			$rname = strip_tags($passed['user_role_name']);
			$exist = userRoles::where('role_name',$rname)->exists();
			$changed = null;
			$tosend = [
						'role_name' => $rname,
						'role_permissions' => $perms,
					];

			// return response()->json(['rid' => $recid]);

			if($recid === null){
				if(!$exist){
					$changed = userRoles::create($tosend);

					$reslt = true;
					$msg = "the new role was added successfully";
				} else {
					$msg = "a role exists with the same name";
				}
			} else {
				$changed = userRoles::where('id',$recid)->update($tosend);

				$reslt = true;
				$msg = "user role updated successfully";
			}

			// return response()->json(['changed' => $changed,'perms' => $perms]);

			$res['result'] = $reslt;
			$res['message'] = $msg;
			$res['sendme'] = true;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}

	public function delete_role(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		$passed = $req->validate([
			'recid' => ['required']
		]);
		$recid = $passed['recid'];

		if($this->ili()){
			$res['success'] = true;
			$reslt = false;
			$msg = "deleted successfully";

			$role = userRoles::withCount('users')->find($recid);

			if($role==null){
				$msg = "invalid Role";
			} elseif ($role->users_count == 0){
				// userRoles::where('id',$recid)->delete();
				$role->delete();
				$msg = "Role deleted successfully";
				$reslt = true;
			} else {
				$msg = "you cant delete a role with users";
			}

			$res['result'] = $reslt;
			$res['message'] = $msg;
			$res['sendme'] = true;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}

	public function log_my_payload(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			'recid' => ['required']
		]);
		// */


		if($this->ili()){
			$res['success'] = true;

			$reslt = self::log_payload($alldata,'delete_role');
			$msg = "logging payload";

			$res['result'] = $reslt;
			$res['message'] = $msg;
			$res['sendme'] = true;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}
}
