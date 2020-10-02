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

class UserLoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
		return view ('backend.userlogin.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $lokasi = Lokasi::select(['id', DB::raw("CONCAT(kode_lokasi,' - ',nama_lokasi) as lokasi")])->where('active',1)->pluck('lokasi','id');
        view()->share('lokasi_all', $lokasi);
		return view ('backend.userlogin.update');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[]);
        //
        $cekusername = UserLogin::where('username',$request->username)->get()->count();

        $ldapconn = ldap_connect('192.168.110.110');
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

        $searchUser = 'donny';
        $searchPass = 'gogreenab';
        $username = $request->username."@avianbrands.com";
        
        $ldap_success = false;
        if (@ldap_bind($ldapconn, $searchUser, $searchPass)) {
            $attributes = ['cn'];
            $filter = "(&(objectClass=user)(objectCategory=person)(userPrincipalName=".ldap_escape($username, null, LDAP_ESCAPE_FILTER)."))";
            $baseDn = "DC=avianbrands,DC=com";
            $results = @ldap_search($ldapconn, $baseDn, $filter, $attributes);
            $info = @ldap_get_entries($ldapconn, $results);
            $ldap_success = ($info && $info['count'] === 1);
        }

		if($cekusername > 0){
			$validator->getMessageBag()->add('username', 'Username already registered');
        } else {
            if ($request->tipe == "AD") {
                if (!$ldap_success){
                    $validator->getMessageBag()->add('username', 'Username not registered in the active directory');
                } else {
                    $data = new UserLogin();
                    $data->username = $request->username;
                    $data->reldag = $request->reldag;
                    $data->tipe = $request->tipe;
                    $data->user_level = $request->user_level;
                    $data->name = $request->name;
                    $data->email = $request->email;
                    $data->lokasi = $request->lokasi;
                    $data->user = $request->user;
                    $data->user_modified = Session::get('userinfo')['username'];
                    if($data->save()){
                        return Redirect::to('/backend/user/')->with('success', "Data saved successfully")->with('mode', 'success');
                    }
                }
            } else {
                $data = new UserLogin();
                $data->username = $request->username;
                $data->password = md5('12345');
                $data->reldag = $request->reldag;
                $data->tipe = $request->tipe;
                $data->user_level = $request->user_level;
                $data->name = $request->name;
                $data->email = $request->email;
                $data->lokasi = $request->lokasi;
                $data->user = $request->user;
                $data->user_modified = Session::get('userinfo')['username'];
                if($data->save()){
                    return Redirect::to('/backend/user/')->with('success', "Data saved successfully")->with('mode', 'success');
                }
            }
        }
		return Redirect::to('/backend/user/create')
				->withErrors($validator)
				->withInput();
    }

    public function show($id)
    {
        //
		$data = UserLogin::where('id', $id)->get();
		if ($data->count() > 0){
            $lokasi = Lokasi::find($data[0]->lokasi);
            view()->share('lokasi', $lokasi);
			return view ('backend.userlogin.view', ['data' => $data]);
		}
    }

    public function edit($id)
    {
        //
        $lokasi = Lokasi::select(['id', DB::raw("CONCAT(kode_lokasi,' - ',nama_lokasi) as lokasi")])->where('active',1)->pluck('lokasi','id');
		$data = UserLogin::where('id', $id)->get();
		if ($data->count() > 0){
            view()->share('lokasi_all', $lokasi);
			return view ('backend.userlogin.update', ['data' => $data]);
		}
    }

    public function update(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(),[]);
        $cekusername = UserLogin::where('user_login.id','<>',$id)->where('username',$request->username)->get()->count();

        $ldapconn = ldap_connect('192.168.110.110');
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

        $searchUser = 'donny';
        $searchPass = 'gogreenab';
        $username = $request->username."@avianbrands.com";
        
        $ldap_success = false;
        if (@ldap_bind($ldapconn, $searchUser, $searchPass)) {
            $attributes = ['cn'];
            $filter = "(&(objectClass=user)(objectCategory=person)(userPrincipalName=".ldap_escape($username, null, LDAP_ESCAPE_FILTER)."))";
            $baseDn = "DC=avianbrands,DC=com";
            $results = @ldap_search($ldapconn, $baseDn, $filter, $attributes);
            $info = @ldap_get_entries($ldapconn, $results);
            $ldap_success = ($info && $info['count'] === 1);
        }

		if($cekusername > 0){
			$validator->getMessageBag()->add('username', 'Username already registered');
        } else 
        {
            if ($request->tipe == "AD") {
                if (!$ldap_success){
                    $validator->getMessageBag()->add('username', 'Username not registered in the active directory');
                } else {
                    $data = UserLogin::find($id);
                    $data->username = $request->username;
                    $data->reldag = $request->reldag;
                    $data->tipe = $request->tipe;
                    $data->user_level = $request->user_level;
                    $data->name = $request->name;
                    $data->email = $request->email;
                    $data->lokasi = $request->lokasi;
                    $data->user = $request->user;
                    $data->user_modified = Session::get('userinfo')['username'];
                    if($data->save()){
                        return Redirect::to('/backend/user/')->with('success', "Data saved successfully")->with('mode', 'success');
                    }
                }
            } else {
                $data = UserLogin::find($id);
                $data->username = $request->username;
                if ($request->password_check == 1){
                    $data->password = md5($request->pwd);
                }
                $data->reldag = $request->reldag;
                $data->tipe = $request->tipe;
                $data->user_level = $request->user_level;
                $data->name = $request->name;
                $data->email = $request->email;
                $data->lokasi = $request->lokasi;
                $data->user = $request->user;
                $data->user_modified = Session::get('userinfo')['username'];
                if($data->save()){
                    return Redirect::to('/backend/user/')->with('success', "Data saved successfully")->with('mode', 'success');
                }

            }
        }
		return Redirect::to('/backend/user/'.$id."/edit")
				->withErrors($validator)
				->withInput();		
    }

    public function destroy(Request $request, $id)
    {
        //
        $delete = UserLogin::where('id', $id)->delete(); 
		return new JsonResponse(["status"=>true]);
    }
	
	public function datatable() {	
        $data = UserLogin::select(['lokasi.nama_lokasi','user_login.*'])
                    ->leftJoin('lokasi','lokasi.id','user_login.lokasi');
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
				$url_edit = url('backend/user/'.$data->id.'/edit');
				$url = url('backend/user/'.$data->id);
				$view = "<a class='btn-action btn btn-primary btn-view' href='".$url."' title='View'><i class='fa fa-eye'></i></a>";
				$edit = "<a class='btn-action btn btn-info btn-edit' href='".$url_edit."' title='Edit'><i class='fa fa-edit'></i></a>";
				$delete = "<button data-url='".$url."' onclick='deleteData(this)' class='btn-action btn btn-danger btn-delete' title='Delete'><i class='fa fa-trash-o'></i></button>";
				return $view." ".$edit." ".$delete;
            })			
            ->rawColumns(['action'])
            ->make(true);
	}
 	
}