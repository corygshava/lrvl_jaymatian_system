<?php

namespace App\Http\Controllers;

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

    public function getOptions(Request $req){
        $res = $this->dftres;
        $alldata = $req->all();

        /*
        $passed = $req->validate([
            'condition' => ['required']
        ]);
        // */

        $fetchid = self::$fetchId;
        $isapi = isset($alldata[$fetchid]) ? $alldata[$fetchid] == "true" || $alldata[$fetchid] == "yes" : false;

        if($this->ili()){
            $res['success'] = true;
            $res['message'] = "operation is complete";

            $mdta = random_int(0,14);
            $res['result'] = $mdta >= 7;
            $res['sendme'] = $mdta;
        }

        return response()->json($res);
    }
}
