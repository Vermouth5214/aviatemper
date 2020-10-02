<?php

namespace App\Http\Controllers\Backend;

use Session;
use Illuminate\Http\Request;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Model\UserLogin;
use App\Model\Lokasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redirect;
use Datatables;
use Validator;

class UserTLoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
		return view ('backend.TIRTA.userlogin.index');
    }

    public function edit($id)
    {
        //
		$data = UserLogin::where('id', $id)->get();
		if ($data->count() > 0){
            $lokasi = Lokasi::find($data[0]->lokasi);
            view()->share('lokasi', $lokasi);
			return view ('backend.TIRTA.userlogin.update', ['data' => $data]);
		}
    }

    public function update(Request $request, $id)
    {
        //
        $data = UserLogin::find($id);
        $data->name = $request->name;
        if ($request->password_check == 1){
            $data->password = md5($request->pwd);
        }
        $data->user_modified = Session::get('userinfo')['username'];
        if($data->save()){
            return Redirect::to('/backend/usert/')->with('success', "Data saved successfully")->with('mode', 'success');
        }
		return Redirect::to('/backend/usert/'.$id."/edit")
				->withErrors($validator)
				->withInput();		
    }

	public function datatable() {
        $userinfo = Session::get('userinfo');
        $pt = $userinfo['pt'];

        $data = UserLogin::select(['lokasi.nama_lokasi','user_login.*'])
                    ->leftJoin('lokasi','lokasi.id','user_login.lokasi')
                    ->where('user', $pt);
        return Datatables::of($data)
            ->editColumn('user_level', function($data) {
                if ($data->user_level == "VSUPER"){
                    return "SUPER ADMIN";
                } else 
                if ($data->user_level == "VADM"){
                    return "ADMIN";
                } else 
                if ($data->user_level == "VTTIRTA"){
                    return "IT TIRTA";
                } else 
                if ($data->user_level == "VHTIRTA"){
                    return "HRD TIRTA";
                } else {
                    return $data->user_level;
                }
            })            
			->addColumn('action', function ($data) {
				$url_edit = url('backend/usert/'.$data->id.'/edit');
				$url = url('backend/usert/'.$data->id);
				$view = "";
				$edit = "<a class='btn-action btn btn-info btn-edit' href='".$url_edit."' title='Edit'><i class='fa fa-edit'></i></a>";
				$delete = "";
				return $view." ".$edit." ".$delete;
            })			
            ->rawColumns(['action'])
            ->make(true);
	}
 	
}