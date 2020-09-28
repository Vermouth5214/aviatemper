<?php

use Illuminate\Support\Facades\Route;

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
    return view('index');
});

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
});


/* ADMIN */
Route::group(array('prefix' => 'backend','middleware'=> ['token_admin']), function()
{
    Route::get('/employee/datatable','Backend\EmployeeController@datatable');
    Route::resource('employee', 'Backend\EmployeeController');

	Route::get('/general-report/datatable','Backend\ReportController@datatable');
	Route::get('/general-report','Backend\ReportController@generalReport');
	Route::get('/general-report/{lokasi}/{tanggal}/export','Backend\ReportController@export');
	Route::get('/general-report/{lokasi}/{tanggal}','Backend\ReportController@show');
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

});

