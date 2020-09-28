<?php


namespace App\Http\Controllers\Backend;

use Session;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use DB;
use Redirect;

class DashboardController extends Controller {
	public function dashboard(Request $request) {
		$userinfo = Session::get('userinfo');
		if ($userinfo['priv'] != 'VSUPER'){
			return redirect('/backend/input');
		}
		return view ('backend.dashboard');
	}
}