<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\userRoles;
use App\Http\Controllers\ViewController;
use Illuminate\Http\Request;

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
            $res['message'] = "fetching permissions";

            $user = auth()->user();
            $savedpermissions = $this->getpermissions();
            $permissions = $user->myRoles?->role_permissions ?? [];

            if($permissions == []){
                $roles = userRoles::all()->count();

                // automatically sets up the first user role if there are none set up
                if($roles === 0){
                    $urole = userRoles::query()->updateOrCreate(
                        ['role_name' => "admin"],
                        ['role_permissions' => $savedpermissions]
                    );

                    // $user->user_role_id = $urole->id;
                    $permissions = $savedpermissions;
                    $user->myRoles()->associate($urole);
                    $user->save();
                }
            }

            $res['result'] = !empty($permissions);
            $res['sendme'] = $permissions;

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
            $res['message'] = "all userRoles retrieved";

            // $users = User::all();

            /*
            $users = User::with(['myRoles' => function ($query) {
                $query->select('id', 'role_name');
            }])->get();
            // */
            // $mdta = random_int(0,14);

            $users = User::with('myRoles')->paginate();

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
}
