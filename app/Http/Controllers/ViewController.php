<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewController extends Controller{
    public function showpermissions(Request $req){
        $res = $this->dftres;
        $alldata = $req->all();

        if($this->ili()){
            $res['success'] = true;
            $res['message'] = "fetching permissions";

            $cmdt = self::commondata();

            $res['result'] = true;
            $res['sendme'] = $cmdt['permissions'];
        }

        if(!self::$showlogs){
            if(array_key_exists('runlog',$res)){
                unset($res['runlog']);
            }
        }
        return response()->json($res);
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
        if($this->ili()){
            $cmdt = self::commondata();
            $topass = [
                "cmdt" => $cmdt
            ];

            return view('profile.userroles',$topass);
        } else {
            // return view('error');
            return response()->json("nothing here");
        }
    }
    public function showdatatable(Request $req){
        if($this->ili()){
            $cmdt = self::commondata();
            $topass = [
                "cmdt" => $cmdt
            ];

            return view('profile.datatables_remake',$topass);
        } else {
            // return view('error');
            return response()->json("nothing here");
        }
    }
}
