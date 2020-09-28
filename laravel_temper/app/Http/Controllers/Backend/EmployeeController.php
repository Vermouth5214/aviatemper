<?php

namespace App\Http\Controllers\Backend;

use Session;
use Illuminate\Http\Request;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Model\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redirect;
use Datatables;
use Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
		return view ('backend.employee.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
		return view ('backend.employee.update');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = new Employee();
        $data->nik = '9999999999';
        $data->nama = strtoupper($request->nama);
        $data->posisi = $request->posisi;
        $data->active = $request->active;
        $data->user_modified = Session::get('userinfo')['username'];
        if($data->save()){
            return Redirect::to('/backend/employee/')->with('success', "Data saved successfully")->with('mode', 'success');
        }
		return Redirect::to('/backend/employee/create')
				->withErrors($validator)
				->withInput();
    }

    public function show($id)
    {
        //
		$data = Employee::where('id', $id)->get();
		if ($data->count() > 0){
			return view ('backend.employee.view', ['data' => $data]);
		}
    }

    public function edit($id)
    {
        //
		$data = Employee::where('id', $id)->get();
		if ($data->count() > 0){
			return view ('backend.employee.update', ['data' => $data]);
		}
    }

    public function update(Request $request, $id)
    {
        //
        $data = Employee::find($id);
        $data->nama = strtoupper($request->nama);
        $data->posisi = $request->posisi;
        $data->active = $request->active;
        $data->user_modified = Session::get('userinfo')['username'];
        if($data->save()){
            return Redirect::to('/backend/employee/')->with('success', "Data saved successfully")->with('mode', 'success');
        }
		return Redirect::to('/backend/employee/'.$id."/edit")
				->withErrors($validator)
				->withInput();		
    }

    public function destroy(Request $request, $id)
    {
        //
        $update = Employee::find($id);
        $update->active = 0;
        $update->save();
		return new JsonResponse(["status"=>true]);
    }
	
	public function datatable() {	
        $data = Employee::where('active','<>',0);
        return Datatables::of($data)
			->addColumn('action', function ($data) {
				$url_edit = url('backend/employee/'.$data->id.'/edit');
				$url = url('backend/employee/'.$data->id);
				$view = "<a class='btn-action btn btn-primary btn-view' href='".$url."' title='View'><i class='fa fa-eye'></i></a>";
				$edit = "<a class='btn-action btn btn-info btn-edit' href='".$url_edit."' title='Edit'><i class='fa fa-edit'></i></a>";
				$delete = "<button data-url='".$url."' onclick='deleteData(this)' class='btn-action btn btn-danger btn-delete' title='Delete'><i class='fa fa-trash-o'></i></button>";
				return $view." ".$edit." ".$delete;
            })			
            ->rawColumns(['action'])
            ->make(true);
	}
 	
}