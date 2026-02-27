<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller{
    public function logsth(Request $req){
		$this->log_my_payload($req);
	}
}
