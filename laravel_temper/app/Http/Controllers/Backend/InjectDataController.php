<?php

namespace App\Http\Controllers\Backend;

use Session;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use DB;
use Redirect;
use App\Imports\InjectData;
use Excel;


class InjectDataController extends Controller {
	public function index(Request $request) {
		return view ('backend.TIRTA.inject');
	}

	public function store(Request $request) {
        if ($request->hasFile('upload_file')) {
            $file = $request->file('upload_file');

            $import = new InjectData;
            Excel::import($import, $file);
            $error = $import->getError();

            return Redirect::to('/backend/inject-data/')->with('success', "Data saved successfully")->with('mode', 'success')->with('error', $error);
        } else {
            return Redirect::to('/backend/inject-data/')->with('success', "File not found")->with('mode', 'danger');
        }            
	}

}