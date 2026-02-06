<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller{
    public function test(Request $req){
        $res = $this->dftres;
        $res["success"] = true;
        $res["result"] = true;
        $res['message'] = "welcome to our API";
        return $res;
    }
}
