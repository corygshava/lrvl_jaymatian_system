<?php

namespace App\Http\Middleware;

use App\Http\Controllers\ViewController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissionsUI{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response{
		$conEx = new ViewController();
		$res = $conEx->dftres;
		$total = count($permissions);
		$passed = 0;
		$status = 200;
		$keepon = true;
		$msg = "";

		$update_runlog = function($wot) use (&$res){
			$res['runlog'][] = $wot;
		};
		$update_msg = function($wot,$logit = true) use (&$msg,&$update_runlog){
			$msg = $wot;
			if($logit){
				$update_runlog($wot);
			}
		};
		$_msg = function($w,$l = true) use (&$update_msg){
			$update_msg($w,$l);
		};

		if(!Auth::check()){
			$status = 401;
			$_msg("start a session first");
			$keepon = false;
		}

		// check permissions
		if($keepon) {
			if(empty($permissions)){
				$keepon = true;
			} else {
				$res['success'] = true;
				$_msg('checking permissions');

				foreach ($permissions as $p) {
					$s = $conEx->haspermission($p) ? 1 : 0;
					$passed += $s;
					$prms .= "$p,";
				}

				$_msg("you dont have permission to view this page <kbd data-role=\"permission\">{$prms}</kbd>");
				$keepon = $passed >= 1;

				$status = $keepon ? 200 : 403;
			}
		}

		if($keepon){
			return $next($request);
		}

		unset($res['runlog']);
		$res['message'] = $msg;

		// return response()->json($res,$status);
		return response($conEx->showerror($request,$res['message']),$status);
    }
}
