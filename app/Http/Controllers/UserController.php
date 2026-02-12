<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\userRoles;
use Illuminate\Http\Request;

class UserController extends Controller{
	public function index(Request $req){
		if($this->ili()){
			return redirect('/dashboard');
		} else {
			return redirect('/login');
		}
	}

    public function showdash(Request $req){
        $cmdt = self::commondata();
        $topass = [
            "cmdt" => $cmdt
        ];

        return view('dashboard',$topass);
    }

    public function showconsole(Request $req){
        $cmdt = self::commondata();
        $topass = [
            "cmdt" => $cmdt
        ];

        return view('profile.console',$topass);
    }
    public function showroles(Request $req){
        $cmdt = self::commondata();
        $topass = [
            "cmdt" => $cmdt
        ];

        return view('profile.userroles',$topass);
    }

    // opts

    public function getOptions(Request $req){
        $res = $this->dftres;
        $alldata = $req->all();

        /*
        $passed = $req->validate([
            'condition' => ['required']
        ]);
        // */


        if($this->ili()){
            $res['success'] = true;
            $res['message'] = "fetching permissions";

            $cmdt = self::commondata();
            $mdta = random_int(0,14);

            $res['result'] = $mdta >= 7;
            $res['sendme'] = $cmdt['permissions'];
        }

        if(!self::$showlogs){
            if(array_key_exists('runlog',$res)){
                unset($res['runlog']);
            }
        }
        return response()->json($res);
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

            dd($permissions);
        }

        if(!self::$showlogs){
            if(array_key_exists('runlog',$res)){
                unset($res['runlog']);
            }
        }

        return response()->json($res);
    }
}
