<?php

namespace App\Http\Controllers\Backend;

use Session;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Model\UserLogin;
use Illuminate\Support\Facades\Redirect;

class ChangePasswordController extends Controller {
	public function change_password() {
		$userinfo = Session::get('userinfo');
		if ($userinfo['tipe'] == "LAIN") {
			return view ('backend.change_password');
		} else {
			return Redirect::to('/backend/dashboard');
		}
	}
	
    public function store_change_password(Request $request)
    {
		$data = [
			'error' => [],
			'status' => false,
		];

        $userinfo = Session::get('userinfo');
        $password_lama = $request->old_pass;
        $password_baru = $request->new_pass;
        $konfirmasi_password_baru = $request->confirm_new_pass;

        //cek password lama
        $cek_password_lama = UserLogin::where('password','=', md5($password_lama))->where('username','=',$userinfo['username'])->count();
        if ($cek_password_lama == 0){
            $data['error'] = "Old Password do not match";
            return redirect('/backend/change-password')->with(['data' => $data]);
        }
        if ($password_baru != $konfirmasi_password_baru){
            $data['error'] = "New Password and Confirm New Password are different";
            return redirect('/backend/change-password')->with(['data' => $data]);
        }
        if (strlen($password_baru) < 5){
            $data['error'] = "New Passwords must be at least 5 characters long";
            return redirect('/backend/change-password')->with(['data' => $data]);
        }
        if (strlen($password_baru) > 15){
            $data['error'] = "New Password must be a maximum of 15 characters";
            return redirect('/backend/change-password')->with(['data' => $data]);
        }

        UserLogin::where('username','=',$userinfo['username'])->update(['password' => md5($password_baru)]);
        $data['status'] = true;
		$data['error'] = 'Password successfully changed';
		return redirect('/backend/change-password')->with(['data' => $data]);
    }
	
}