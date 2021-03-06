<?php

use Illuminate\Support\Facades\Route;
use App\Model\UserLogin;
use App\Model\Lokasi;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	return redirect('backend/login');
});

Route::match(array('GET','POST'),'/backend/login','Backend\LoginController@index');
Route::get('backend/logout','Backend\LoginController@logout');

/* SUPER ADMIN */
Route::group(array('prefix' => 'backend','middleware'=> ['token_super_admin']), function()
{
	Route::get('/setting','Backend\SettingController@index');
    Route::post('/setting','Backend\SettingController@update');

    Route::get('/user/datatable','Backend\UserLoginController@datatable');
    Route::resource('user', 'Backend\UserLoginController');

    Route::get('/insert-user', function () {
        $lokasi = Lokasi::where('kode_lokasi','<>','000')->where('kode_lokasi','<>','KLI')->where('kode_lokasi','<>','SDK')->get();
        foreach ($lokasi as $lokasi):
            $insert = new Userlogin();
            $insert->username = $lokasi->kode_lokasi;
            $insert->password = '827ccb0eea8a706c4c34a16891f84e7b';
            $insert->name = $lokasi->nama_lokasi;
            $insert->user_level = 'USER';
            $insert->tipe = 'LAIN';
            $insert->user_modified = 'donny';
            $insert->reldag = $lokasi->kode_lokasi;
            $insert->lokasi = $lokasi->id;
            $insert->save();
        endforeach;
    });
});


/* ADMIN */
Route::group(array('prefix' => 'backend','middleware'=> ['token_admin']), function()
{
    Route::get('/employee/datatable','Backend\EmployeeController@datatable');
    Route::resource('employee', 'Backend\EmployeeController');

});

/* ALL */
Route::group(array('prefix' => 'backend','middleware'=> ['token_all']), function()
{
    Route::get('/dashboard','Backend\DashboardController@dashboard');

    Route::get('/change-password','Backend\ChangePasswordController@change_password');	
    Route::post('/change-password','Backend\ChangePasswordController@store_change_password');	

    Route::get('/input','Backend\InputController@index');
    Route::get('/input/search/{id}','Backend\InputController@search');

    Route::post('/input','Backend\InputController@store');

	Route::get('/general-report/datatable','Backend\ReportController@datatable');
	Route::get('/general-report','Backend\ReportController@generalReport');
	Route::get('/general-report/{lokasi}/{tanggal}/export','Backend\ReportController@export');
    Route::get('/general-report/{lokasi}/{tanggal}','Backend\ReportController@show');
    
	Route::get('/active-employee-report/datatable','Backend\ReportController@activeEmployee_datatable');
	Route::get('/active-employee-report','Backend\ReportController@activeEmployeeReport');
    Route::get('/active-employee-report/{lokasi}/{tanggal}/export','Backend\ReportController@export_active_employee');
	Route::get('/active-employee-report/{lokasi}/{tanggal}','Backend\ReportController@show_active_employee');

});


Route::group(array('prefix' => 'backend','middleware'=> ['token_it_tirta']), function()
{
    Route::get('/usert/datatable','Backend\UserTLoginController@datatable');
    Route::resource('usert', 'Backend\UserTLoginController');

    Route::get('/input-admin','Backend\InputAdminController@index');
    Route::get('/input-admin/search/{id}','Backend\InputAdminController@search');
    Route::post('/input-admin','Backend\InputAdminController@store');

    Route::get('/inject-data','Backend\InjectDataController@index');
    Route::post('/inject-data','Backend\InjectDataController@store');

});

Route::group(array('prefix' => 'backend','middleware'=> ['token_hrd_tirta']), function()
{
	Route::get('/general-reportt/datatable','Backend\ReportTController@datatable');
	Route::get('/general-reportt','Backend\ReportTController@generalReport');
	Route::get('/general-reportt/{lokasi}/{tanggal}/export','Backend\ReportTController@export');
	Route::get('/general-reportt/{lokasi}/{tanggal}','Backend\ReportTController@show');
});