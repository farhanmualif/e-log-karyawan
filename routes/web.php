<?php

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

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Routes yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/karyawan', 'KaryawanController@index')->name('karyawan');
    Route::put('/karyawan/{nik}/update', 'KaryawanController@updateKaryawan')->name('karyawan.update.sideout');
    Route::post('/karyawan/{nik}/activate', 'KaryawanController@activate')->name('karyawan.activate');
    Route::put('/karyawan/{id}/password', 'KaryawanController@updatePassword')->name('karyawan.update-password');
    Route::put('/karyawan/{id}/role', 'KaryawanController@updateRole')->name('karyawan.update-role');
    Route::put('/karyawan/{id}', 'KaryawanController@update')->name('karyawan.update');

    Route::get('/password/change', 'ChangePasswordController@showChangeForm')->name('password.change.form');
    Route::post('/password/change', 'ChangePasswordController@changePassword')->name('password.change');

    // Profile Routes
    Route::get('/profile', 'ProfileController@show')->name('profile.show');
    Route::put('/profile', 'ProfileController@update')->name('profile.update');

    // Log Aktivitas Routes
    Route::get('/log-aktivitas', 'LogAktivitasController@index')->name('log-aktivitas.index');
    Route::get('/log-aktivitas/my-activity', 'LogAktivitasController@myActivity')->name('log-aktivitas.my-activity');
    Route::get('/log-aktivitas/create', 'LogAktivitasController@create')->name('log-aktivitas.create');
    Route::post('/log-aktivitas', 'LogAktivitasController@store')->name('log-aktivitas.store');
    Route::get('/log-aktivitas/show', 'LogAktivitasController@show')->name('log-aktivitas.show');
    Route::get('/log-aktivitas/{id}/edit', 'LogAktivitasController@edit')->name('log-aktivitas.edit');
    Route::put('/log-aktivitas/{id}', 'LogAktivitasController@update')->name('log-aktivitas.update');
    Route::delete('/log-aktivitas/{id}', 'LogAktivitasController@destroy')->name('log-aktivitas.destroy');
    Route::post('/log-aktivitas/{id}/approve', 'LogAktivitasController@approve')->name('log-aktivitas.approve');
    Route::post('/log-aktivitas/{id}/reject', 'LogAktivitasController@reject')->name('log-aktivitas.reject');
    Route::post('/log-aktivitas/bulk-approve', 'LogAktivitasController@bulkApprove')->name('log-aktivitas.bulk-approve');
    Route::post('/log-aktivitas/bulk-reject', 'LogAktivitasController@bulkReject')->name('log-aktivitas.bulk-reject');
    Route::post('/log-aktivitas/bulk-approve-ids', 'LogAktivitasController@bulkApproveByIds')->name('log-aktivitas.bulk-approve-ids');
    Route::post('/log-aktivitas/bulk-reject-ids', 'LogAktivitasController@bulkRejectByIds')->name('log-aktivitas.bulk-reject-ids');
    Route::get('/log-aktivitas/detail-activity/{user_id}', 'LogAktivitasController@detailActivity')->name('log-aktivitas.detail-activity');
    Route::get('/log-aktivitas/detail-activity-by-departemen/{departemen_id}', 'LogAktivitasController@detailActivityByDepId')->name('log-aktivitas.detail-activity-by-departemen');
    Route::get('/log-aktivitas/detail-activity-by-departemen/{departemen_id}/user/{user_id}/activities', 'LogAktivitasController@getActivitiesByUser')->name('log-aktivitas.get-activities-by-user');
    Route::get('/api/log-activity/status', 'Api\ApiDashboard@getLogActivityStatus')->name('log-activity.status');
    Route::get('/api/log-activity/departemen', 'Api\ApiDashboard@getActivityByDepartemen')->name('log-activity.departemen');

    // Data Master - Departemen Routes
    Route::get('/data-master/departemen', 'DepartemenController@index')->name('departemen.index');
    Route::get('/data-master/departemen/create', 'DepartemenController@create')->name('departemen.create');
    Route::post('/data-master/departemen', 'DepartemenController@store')->name('departemen.store');
    Route::get('/data-master/departemen/{id}/edit', 'DepartemenController@edit')->name('departemen.edit');
    Route::put('/data-master/departemen/{id}', 'DepartemenController@update')->name('departemen.update');
    Route::delete('/data-master/departemen/{id}', 'DepartemenController@destroy')->name('departemen.destroy');

    // Data Master - Unit Routes
    Route::get('/data-master/unit', 'UnitController@index')->name('unit.index');
    Route::get('/data-master/unit/create', 'UnitController@create')->name('unit.create');
    Route::post('/data-master/unit', 'UnitController@store')->name('unit.store');
    Route::get('/data-master/unit/{id}/edit', 'UnitController@edit')->name('unit.edit');
    Route::put('/data-master/unit/{id}', 'UnitController@update')->name('unit.update');
    Route::delete('/data-master/unit/{id}', 'UnitController@destroy')->name('unit.destroy');
});
