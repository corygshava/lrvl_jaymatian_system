<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewController extends Controller{
    // UIs
	public function showdash(Request $req){
        if($this->ili()){
            $cmdt = self::commondata();
            $topass = [
                "cmdt" => $cmdt
            ];

            return view('dashboard',$topass);
        } else {
            return $this->showerror($req,'log in to continue');
        }
	}
	public function showconsole(Request $req){
        if($this->ili()){
            $cmdt = self::commondata();
            $topass = [
                "cmdt" => $cmdt
            ];

            return view('profile.console',$topass);
        } else {
            return $this->showerror($req,'log in to continue');
        }
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
			return $this->showerror($req,'log in to continue');
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
            return $this->showerror($req,'log in to continue');
        }
	}
	public function showusers(Request $req){
		if($this->ili()){
            $cmdt = self::commondata();
            $topass = [
                "cmdt" => $cmdt
            ];

            return view('profile.user_mgt',$topass);
        } else {
            return $this->showerror($req,'log in to continue');
        }
	}
	public function showuser(Request $req){
		if($this->ili()){
            $cmdt = self::commondata();
            $topass = [
                "cmdt" => $cmdt
            ];

            return view('profile.user_view',$topass);
        } else {
            return $this->showerror($req,'log in to continue');
        }
	}

	public function showclients(Request $req){
		if($this->ili()){
            $cmdt = self::commondata();
            $topass = [
                "cmdt" => $cmdt
            ];

            return view('profile.client_mgt',$topass);
        } else {
            return $this->showerror($req,'log in to continue');
        }
	}
	public function showclient(Request $req){
		if($this->ili()){
			$cmdt = self::commondata();
			$topass = [
				"cmdt" => $cmdt
			];

			return view('profile.clients_view',$topass);
		} else {
			return $this->showerror($req,'log in to continue');
		}
	}

	public function showtasks(Request $req){
		if($this->ili()){
            $cmdt = self::commondata();
            $topass = [
                "cmdt" => $cmdt
            ];

            return view('profile.task_mgt',$topass);
        } else {
            return $this->showerror($req,'log in to continue');
        }
	}
	public function addtask(Request $req){
		if($this->ili()){
			$cmdt = self::commondata();
			$topass = [
				"cmdt" => $cmdt
			];

			return view('profile.task_add',$topass);
		} else {
			return $this->showerror($req,'log in to continue');
		}
	}
	public function showtask(Request $req){
		if($this->ili()){
			$cmdt = self::commondata();
			$topass = [
				"cmdt" => $cmdt
			];

			return view('profile.task_view',$topass);
		} else {
			return $this->showerror($req,'log in to continue');
		}
	}
	public function showtaskinfo(Request $req){
		if($this->ili()){
			$cmdt = self::commondata();
			$topass = [
				"cmdt" => $cmdt
			];

			return view('profile.task_info',$topass);
		} else {
			return $this->showerror($req,'log in to continue');
		}
	}

	public function showsamples(Request $req){
		if($this->ili()){
            $cmdt = self::commondata();
            $topass = [
                "cmdt" => $cmdt
            ];

            return view('profile.samples_mgt',$topass);
        } else {
            return $this->showerror($req,'log in to continue');
        }
	}

	public function showpayments(Request $req){
		if($this->ili()){
            $cmdt = self::commondata();
            $topass = [
                "cmdt" => $cmdt
            ];

            return view('profile.payments_mgt',$topass);
        } else {
            return $this->showerror($req,'log in to continue');
        }
	}

    public function showerror(Request $req,$msg = 'an error occured'){
        $dta = [
            'msg' => $msg,
        ];

        return view('error',$dta);
    }

    // data
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
}
