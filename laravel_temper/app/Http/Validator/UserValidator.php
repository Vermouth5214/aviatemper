<?php

namespace App\Http\Validator;

use Request;
use Validator;
use App\Model\UserLogin;

class UserValidator
{
	public static function login($request)
	{
		$data = [
			'status' => false,
			'error' => [],
			'data' => []
		];	
		
    	$validator = Validator::make($request, [
    		'email' => 'required_without:username|email|exists:users,email',
            'password' => 'required',
            'username' => 'exists:user_login,username|required_without:email|between:2,50'
    	]);

    	if($validator->fails()) {
    		$data['error'] = $validator->errors()->toArray();
    		return $data;
    	} else {
    		$data['status'] = true;
    		return $data;
    	}    			
    }
    

}