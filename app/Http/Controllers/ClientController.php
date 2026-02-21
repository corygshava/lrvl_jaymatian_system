<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ClientController extends Controller{
    public function get_all(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		if($this->ili()){
			$res['success'] = true;
			$res['message'] = "all clients retrieved";

			$perPage = $req->input('per_page', 10);
			$search = $req->input('search');
			$sortBy = $req->input('sort_by', 'created_at');
			$sortDirection = $req->input('sort_direction', 'desc');

			// filters
			$payment_plan = $req->input('payment_plan',null);
			$client_gender = $req->input('client_gender',null);

			$res['runlog'][] = [
				"perPage" => $perPage,
				"search" => $search,
				"sortBy" => $sortBy,
				"sortDirection" => $sortDirection,
				"payment_plan" => $payment_plan,
				"client_gender" => $client_gender,
			];

			$clients = Client::query();

			if($search){
				$clients = $clients
					->where('client_name', 'like', "%{$search}%")
					->orWhere('client_contact', 'like', "%{$search}%")
					->orWhere('id_number', 'like', "%{$search}%")
					->orWhere('kra_pin', 'like', "%{$search}%");

				$res['runlog'][] = "added search filter";
			}

			// filter for user roles
			if($payment_plan){
				$clients = $clients->where('payment_plan',$payment_plan);
			}

			// filter for activity
			if($client_gender){
				$clients = $clients->where('client_gender',$client_gender);
			}

			$sortBy = $sortBy == null || $sortBy == '' ? 'created_at' : $sortBy;
			$sortBy = ($sortBy == 'role_name') ? "user_roles_id" : $sortBy;

			$clients = $clients
				// ->with('myRoles')
				->orderBy($sortBy, $sortDirection)
				->paginate($perPage);

			/*
			for($i = 0;$i < count($users);$i++){
				$users[$i]->role_name = $users[$i]->myRoles?->role_name;
				$users[$i]->role_permissions = $users[$i]->myRoles?->role_permissions;
			}
			*/

			$res['result'] = true;
			$res['sendme'] = $clients;
		}

		// dd($res);

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}

	public function add_edit(Request $req){
		// return $this->log_my_payload($req);

		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			'client_name' => ['required','string'],
			'client_contact' => ['required','string'],
			'client_gender' => ['required','string'],
			'payment_plan' => ['required','string'],
		]);
		$recid = isset($alldata['recid']) ? $alldata['recid'] : null;
		// */

		if($this->ili()){
			$res['success'] = true;
			$msg = "starting procedure";
			$reslt = false;

			$user = auth()->user();
			$uid = $user->id;
			$client_name = strip_tags($passed['client_name']);
			$client_contact = strip_tags($passed['client_contact']);
			$client_gender = strip_tags($passed['client_gender']);
			$payment_plan = strip_tags($passed['payment_plan']);
			$kra_pin = strip_tags($alldata['kra_pin']);
			$id_number = strip_tags($alldata['id_number']);

			$exist = Client::where('client_contact',$client_contact)->exists();
			$changed = null;
			$tosend = [
				'client_name' => $client_name,
				'client_contact' => $client_contact,
				'client_gender' => $client_gender,
				'payment_plan' => $payment_plan,
				'id_number' => $id_number,
			];

			if($kra_pin){
				$tosend['kra_pin'] = $kra_pin;
			}
			if($id_number){
				$tosend['id_number'] = $id_number;
			}

			// return response()->json(['rid' => $recid]);

			if($recid === null){
				if(!$exist){
					$tosend['added_by'] = $uid;
					$changed = Client::create($tosend);

					$reslt = true;
					$msg = "the new Client was added successfully";
				} else {
					$msg = "phone number is already taken";
				}
			} else {
				$changed = Client::where('id',$recid)->first();
				$changed->update($tosend);
				$changed->refresh();

				$reslt = true;
				$msg = "Client updated successfully";
			}

			// return response()->json(['changed' => $changed,'perms' => $perms]);

			$res['result'] = $reslt;
			$res['message'] = $msg;
			$res['sendme'] = $changed;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}

	public function change_credit(Request $req){
		// return $this->log_my_payload($req);
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			'client_name_2' => ['required'],
			'user_credit' => ['required','numeric'],
			'admin_password' => ['required'],
			'credit_amt' => ['required','numeric'],
			'credit_action' => ['required'],
		]);
		$recid = $req->input('recid',null);
		// */


		if($this->ili()){
			$res['success'] = true;

			$msg = "changing credit balance";
			$reslt = false;
			$target = null;
			// $user = Auth::user();

			$vrf = $this->verifyPassword($passed['admin_password']);

			if(!$vrf){
				$msg = "invalid admin password";
			} else {
				$c_amt = $passed['credit_amt'];
				$c_act = $passed['credit_action'];
				$c_bal = $passed['user_credit'];

				$msg = "valid password";

				$target = Client::find($recid);

				if($target == null){
					$msg = "invalid identifier";
				} else {
					$bal = $target->credit_balance;
					$newbal = $bal;
					$keepgoing = false;

					if(!($bal == $c_bal)){
						$msg = "balance mismatch";
						$res['runlog'][] = $msg;
					} else {
						$keepgoing = true;
						$act = Str::upper($c_act);

						if($act == "ADD"){
							$newbal = $bal + $c_amt;
						} elseif($act == "SET") {
							$newbal = $c_amt;
						} elseif($act == "SUBTRACT" || $act == "MINUS"){
							$newbal = $bal - $c_amt;
						} else {
							$msg = "invalid credit action";
							$keepgoing = false;
						}
					}

					if($keepgoing){
						$target->update([
							'credit_balance' => $newbal,
						]);
						$target->refresh();
					}

					$msg = "credit updated successfully";
					$reslt = $keepgoing;
				}
			}

			$res['result'] = $reslt;
			$res['message'] = $msg;
			$res['sendme'] = $target;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}
}
