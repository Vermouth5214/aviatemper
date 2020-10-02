<?php
namespace App\Http\Controllers\Backend;

use Session;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Http\Validator\UserValidator;
use DB;
use App\Model\UserLogin;
use App\Model\Lokasi;

 
class LoginController extends Controller {
	private function login(Request $request)
	{
		$data = [
			'request' => $request->all(),
			'response' => [
				'message' => 'An error occured',
				'error' => [],
			],
			'status' => false,
		];

		$validator = UserValidator::login($request->all());

		if( ! $validator['status']) {
			$data['response']['message'] = 'Validation error.';
			$data['response']['error'] = $validator['error'];
			return $data;
		} 
		
		//check user ada di tabel apa ga
		$login = false;
		$check = UserLogin::where('username', $request->username)->first();
		if ($check){
			//check user AD apa bukan
			if ($check->tipe == "AD"){
				$ldap['user'] = $request->username;
				$ldap['pass'] = $request->password;
				$ldap['host']   = '192.168.110.110';
				$ldap['port']   = 389;
				
				$ldap['conn'] = ldap_connect( $ldap['host'], $ldap['port'] )
					or die("Could not connect to {$ldap['host']}" );
				
				ldap_set_option($ldap['conn'], LDAP_OPT_PROTOCOL_VERSION, 3);
				$ldap['bind'] = @ldap_bind($ldap['conn'], $ldap['user'], $ldap['pass']);

				if( !$ldap['bind'] )
				{
					ldap_close( $ldap['conn'] );            
					$data['response']['message'] = 'Validation error.';
					$data['response']['error'] = array(['Username / Password not found']);
					return $data;
				}
		
				ldap_close( $ldap['conn'] );        
				$login = true;
			} else 
			if ($check->tipe == "LAIN"){
				if (md5($request->password) == $check->password){
					$login = true;
				} else {
					$data['response']['message'] = 'Validation error.';
					$data['response']['error'] = array(['Username / Password not found']);
					return $data;
				}
			}
			if ($login){
				$last_activity = UserLogin::find($check->id);
				$last_activity->last_activity = date('Y-m-d H:i:s');
				$last_activity->save();
	
				$info['username'] = $check->username;
				$info['priv'] = $check->user_level;
				$info['name'] = $check->name;
				$info['email'] = $check->email;
				$info['reldag'] = $check->reldag;
				$info['tipe'] = $check->tipe;
				$info['pt'] = $check->user;

				$lokasi = Lokasi::find($check->lokasi);
				if ($lokasi){
					$info['lokasi'] = $lokasi->nama_lokasi;
				} else {
					$info['lokasi'] = "NONE";
				}

				$data['status'] = true;
				$data['response']['data']['userinfo'] = $info;            
				$data['response']['message'] = 'Login success.';
			}
		} else {
            $data['response']['message'] = 'Validation error.';
            $data['response']['error'] = array(['Username / Password not found']);
			return $data;
		}
		return $data;
	}
	
	public function index(Request $request) {
		if($request->isMethod('GET')){
			if(Session::get('userinfo') == ""){
	            return view ('backend.login');
	        }
	        else{
	            return redirect('/backend/dashboard');
	        }
		}
	
		if($request->isMethod('POST')){
			$response = $this->login( $request );
			if ($response['status']) {
				Session::put ('userinfo',$response['response']['data']['userinfo'] );
				$return = array(
					'status' => true,
					'message' => "Login Success"
				);
				return new JsonResponse($return,200);
			}else{
				$return = array(
					'status' => false,
					'message' => $response['response']['error']
				);
				return new JsonResponse($return, 200);
			}
		}
		return view ('backend.login');
	}

	public function logout(Request $request) {
		$request->session()->flush();
        return redirect('/backend/login');
	}	

}