<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\userRoles;
use App\Http\Controllers\ViewController;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Sample;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller{
	public function index(Request $req){
		if($this->ili()){
			return redirect('/dashboard');
		} else {
			return redirect('/login');
		}
	}

	public function checkadmin(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		if($this->ili()){
			$res['success'] = true;
			$res['message'] = "checking status";

			$isadmin = $this->isadmin();

			$res['result'] = true;
			$res['sendme'] = $isadmin;

			// dd($rolename);
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}
		return response()->json($res);
	}

	// opts
	public function getMenu(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		if($this->ili()){
			$res['success'] = true;
			$res['message'] = "fetching permissions";

			$cmdt = self::commondata();
			$mdta = random_int(0,14);

			$res['result'] = $mdta >= 7;
			$res['sendme'] = $cmdt['permissions'];

			// dd($cmdt);
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}
		return response()->json($res);
	}

	public function showpermissions(Request $req){
		$vc = new ViewController();
		return $vc->showpermissions($req);
	}

	public function mypermissions(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		if($this->ili()){
			$res['success'] = true;
			$reslt = false;
			$msg = "confirming your permissions";

			$perms = [];
			$permissions = $this->getmypermissions();

			if(!empty($permissions)){
				// $perms = array_keys($permissions);
				$perms = $permissions;
				$msg = "permissions found";
				$reslt = true;
			} else {
				$msg = "no permissions found";
			}

			$res['result'] = $reslt;
			$res['sendme'] = $perms;
			$res['message'] = $msg;

			// dd($permissions);
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}

	public function get_all(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		if($this->ili()){
			$res['success'] = true;
			$res['message'] = "all users retrieved";

			$perPage = $req->input('per_page', 10);
			$search = $req->input('search');
			$sortBy = $req->input('sort_by', 'created_at');
			$sortDirection = $req->input('sort_direction', 'desc');

			// filters
			$user_roles_id = $req->input('user_roles_id',null);
			$is_active = $req->input('is_active',null);

			$res['runlog'][] = [
				"perPage" => $perPage,
				"search" => $search,
				"sortBy" => $sortBy,
				"sortDirection" => $sortDirection,
			];

			$users = User::query()->where('id','<>',0);

			if($search){
				$users = $users
					->where('name', 'like', "%{$search}%")
					->orWhere('email', 'like', "%{$search}%");

				$res['runlog'][] = "added search filter";
			}

			// filter for user roles
			if($user_roles_id){
				$users = $users->where('user_roles_id',$user_roles_id);
			}

			// filter for activity
			if($is_active){
				$acc = $is_active === 'true' ? 1 : 0;
				$users = $users->where('is_active',$acc);
			}

			$sortBy = $sortBy == null || $sortBy == '' ? 'created_at' : $sortBy;
			$sortBy = ($sortBy == 'role_name') ? "user_roles_id" : $sortBy;

			$users = $users
				->with('myRoles')
				->orderBy($sortBy, $sortDirection)
				->paginate($perPage);

			for($i = 0;$i < count($users);$i++){
				$users[$i]->role_name = $users[$i]->myRoles?->role_name;
				$users[$i]->role_permissions = $users[$i]->myRoles?->role_permissions;
			}

			$res['result'] = true;
			$res['sendme'] = $users;
		}

		// dd($res);

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}
	public function get_current(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		if($this->ili()){
			$res['success'] = true;
			$res['message'] = "User information retrieved";

			$sess_user = $this->cur_user();
			$uid = $sess_user->id;

			$res['runlog'][] = $uid;
			$user = User::where('id',$uid)->with('myRoles')->get()[0];

			$user['role_name'] = $user->myRoles->role_name;
			$user['role_permissions'] = $user->myRoles->role_permissions;

			$res['result'] = true;
			$res['sendme'] = $user;
		}

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
			'user_name' => ['required'],
			'user_email' => ['required'],
			'user_password' => ['required'],
			'user_role_name' => ['required'],
		]);
		$recid = isset($alldata['recid']) ? $alldata['recid'] : null;
		// */

		if($this->ili()){
			$res['success'] = true;
			$msg = "starting procedure";
			$reslt = false;

			// $op = self::log_payload($alldata,'indata');
			$u_name = $passed['user_name'];
			$u_pass = $passed['user_password'];
			$u_email = $passed['user_email'];
			$u_role_name = $passed['user_role_name'];
			$u_phone = $alldata['user_contact'];

			$res['runlog'][] = $u_phone;

			$exist = User::where('email',$u_email)->exists();
			$changed = null;
			$tosend = [
				'name' => $u_name,
				'email' => $u_email,
				'user_roles_id' => $u_role_name,
			];

			if($u_phone != null){
				$tosend['phone_number'] = $u_phone;
			}

			// return response()->json(['rid' => $recid]);

			if($recid === null){
				if(!$exist){
					$tosend['password'] = $u_pass;
					$changed = User::create($tosend);

					$reslt = true;
					$msg = "the new user was added successfully";
				} else {
					$msg = "email is already taken";
				}
			} else {
				$changed = User::where('id',$recid)->with('myRoles')->first();
				$changed->update($tosend);
				$changed->refresh();

				$changed['role_name'] = $changed->myRoles->role_name;
				$changed['role_permissions'] = $changed->myRoles->role_permissions;

				$reslt = true;
				$msg = "user updated successfully";
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
	public function change_password(Request $req){
		// return $this->log_my_payload($req);
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			'user_name' => ['required'],
			'admin_password' => ['required'],
			'user_password' => ['required'],
		]);
		$recid = $req->input('recid',null);
		// */


		if($this->ili()){
			$res['success'] = true;

			$msg = "changing password";
			$reslt = false;
			// $user = Auth::user();
			$vrf = $this->verifyPassword($passed['admin_password']);

			if(!$vrf){
				$msg = "invalid admin password";
			} else {
				$msg = "valid password";

				$target = User::find($recid);

				if($target == null){
					$msg = "invalid user identifier";
				} else {
					$target->update([
						'password' => Hash::make($passed['user_password']),
					]);

					$msg = "pasword updated successfully";
					$reslt = true;
				}
			}

			$res['result'] = $reslt;
			$res['message'] = $msg;
			$res['sendme'] = $reslt;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}
	public function toggle_active(Request $req){
		// return $this->log_my_payload($req);
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			'admin_password' => ['required'],
		]);
		$recid = $req->input('recid',null);
		// */

		if($this->ili()){
			$res['success'] = true;

			$msg = "toggling status";
			$reslt = false;
			$outdata = null;
			$vrf = $this->verifyPassword($passed['admin_password']);

			if(!$vrf){
				$msg = "invalid admin password";
			} else {
				$msg = "valid password";

				$target = User::where('id',$recid)->with('myRoles')->get()[0];

				if($target == null){
					$msg = "invalid user identifier";
				} else {
					$opname = $target->is_active ? 'deactivated' : 'activated';

					$target->update([
						'is_active' => !($target->is_active),
					]);
					$target->refresh();

					$target['role_name'] = $target->myRoles->role_name;
					$target['role_permissions'] = $target->myRoles->role_permissions;

					$msg = "user $opname successfully";
					$reslt = true;
					$outdata = $target;
				}
			}

			$res['result'] = $reslt;
			$res['message'] = $msg;
			$res['sendme'] = $outdata;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}

	public function admin_stats(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		// /*
		$passed = $req->validate([
			// 't_range' => 'required|in:today,this_week,this_month,this_year,last_week,last_month,last_year,lifetime',
			't_range' => 'required',
		]);
		// */


		if($this->ili()){
			$res['success'] = true;
			$msg = "fetching staff stats";
			$reslt = false;
			$outdata = [];
			$timerange = $passed['t_range'];

			// prepare the output shapes
			$tp_1 = [
				'icon' => 'fa-money-bill',
				'caption' => 'sales',
				'amt' => 54300,
			];
			$tp_2 = [
				'icon' => 'fa-folder',
				'caption' => 'tasks',
				'amt_progress' => 3,
				'amt_total' => 54300,
			];

			// prepare the data
				// tasks
				/*
					$tasksmade = Task::TimeRange($timerange)->count();
					$tmp_box['caption'] = 'Client visits';
					$tmp_box['icon'] = 'fa-users';
					$tmp_box['amt'] = $tasksmade;
					$outdata[] = $tmp_box;

					$s_done = Sample::where('id','<>',null)->where('status',2)->count();
					$s_all = Sample::where('id','<>',null)->count();
					$tmp_box = [];
					$tmp_box['caption'] = 'Samples';
					$tmp_box['icon'] = 'fa-flask';
					$tmp_box['amt_progress'] = $s_done;
					$tmp_box['amt_total'] = $s_all;
					$outdata[] = $tmp_box;

					$s_done = Task::where('id','<>',null)->where('status',2)->count();
					$s_all = Task::where('id','<>',null)->count();
					$tmp_box = [];
					$tmp_box['caption'] = 'Complete Tasks';
					$tmp_box['icon'] = 'fa-folder';
					$tmp_box['amt_progress'] = $s_done;
					$tmp_box['amt_total'] = $s_all;
					$outdata[] = $tmp_box;
				// */

				// lifetime data
					$s_done = Sample::where('id','<>',null)->where('status','<>',2)->count();
					$tmp_box = ['incomplete_samples_total' => $s_done];
					$outdata = [...$outdata,...$tmp_box];

					$s_done = Task::where('id','<>',null)->where('status','<>',2)->count();
					$tmp_box = ['incomplete_tasks_total' => $s_done];
					$outdata = [...$outdata,...$tmp_box];

					$s_done = Invoice::where('id','<>',null)->where('status','<>',2)->count();
					$tmp_box = ['unpaid_invoices_total' => $s_done];
					$outdata = [...$outdata,...$tmp_box];

					$s_done = Task::where('id','<>',null)->where('payment_status','<>',2)->count();
					$tmp_box = ['unpaid_tasks_total' => $s_done];
					$outdata = [...$outdata,...$tmp_box];

				// timerange data
					$s_done = Payment::timeRange($timerange)->where('status',1)->sum('amount');
					$tmp_box = ['total income' => $s_done];
					$outdata = [...$outdata,...$tmp_box];

					$cashguy = new CashReportController();
					$req['use_range'] = 'today';
					$today_data_all = $cashguy->get_for_today($req,true,$res['runlog']);
					$outdata = [...$outdata,'today' => $today_data_all];
					$s_done = $today_data_all['total_balance_forward'];
					$tmp_box = ['cash_available' => $s_done];
					$outdata = [...$outdata,...$tmp_box];

			$reslt = true;

			$res['result'] = $reslt;
			$res['sendme'] = $outdata;
			$res['message'] = $msg;
		}

		if(!self::$showlogs){
			if(array_key_exists('runlog',$res)){
				unset($res['runlog']);
			}
		}

		return response()->json($res);
	}
	public function staff_stats(Request $req){
		$res = $this->dftres;
		$alldata = $req->all();

		/*
		$passed = $req->validate([
			't_range' => 'required|in:today,this_week,this_month,this_year,last_week,last_month,last_year,lifetime'
		]);
		// */


		if($this->ili()){
			$res['success'] = true;
			$msg = "fetching staff stats";
			$reslt = false;
			$outdata = [];
			$timerange = 'lifetime';

			// prepare the data
				// tasks
				// lifetime data
					$s_done = Sample::where('id','<>',null)->where('status','<>',2)->count();
					$tmp_box = ['incomplete_samples_total' => $s_done];
					$outdata = [...$outdata,...$tmp_box];

					$s_done = Task::where('id','<>',null)->where('status','<>',2)->count();
					$tmp_box = ['incomplete_tasks_total' => $s_done];
					$outdata = [...$outdata,...$tmp_box];

					$s_done = Invoice::where('id','<>',null)->where('status','<>',2)->count();
					$tmp_box = ['unpaid_invoices_total' => $s_done];
					$outdata = [...$outdata,...$tmp_box];

					$s_done = Task::where('id','<>',null)->where('payment_status','<>',2)->count();
					$tmp_box = ['unpaid_tasks_total' => $s_done];
					$outdata = [...$outdata,...$tmp_box];

				// today's info
					$cashguy = new CashReportController();
					$req['use_range'] = 'today';
					$today_data_all = $cashguy->get_for_today($req,true,$res['runlog']);
					$outdata = [...$outdata,'today' => $today_data_all];
					$s_done = $today_data_all['total_balance_forward'];
					$tmp_box = ['cash_available' => $s_done];
					$outdata = [...$outdata,...$tmp_box];

			$reslt = true;

			$res['result'] = $reslt;
			$res['sendme'] = $outdata;
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
