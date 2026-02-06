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
}
