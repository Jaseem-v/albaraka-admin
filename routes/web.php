<?php

use Illuminate\Support\Facades\Auth;
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


//Authentication Routes
Auth::routes(['register' => false]);
Route::get('/', [App\Http\Controllers\HomeController::class, 'login'])->name('login.form');
Route::post('/loginform/submit', [App\Http\Controllers\HomeController::class, 'loginsubmit'])->name('login.form.submit');
Route::get('/userlogout', [App\Http\Controllers\HomeController::class, 'logout'])->name('user.logout');

Route::group(['middleware'=>['auth']], function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    Route::resource('/project', App\Http\Controllers\ProjectController::class);
    Route::resource('/employee', App\Http\Controllers\EmployeeController::class);
    Route::resource('/supervisor', App\Http\Controllers\SupervisorController::class);
    Route::resource('/timesheet', App\Http\Controllers\TimeSheetController::class);
    
    Route::post('/leave/save', [App\Http\Controllers\TimeSheetController::class, 'leave_save'])->name('leave.save');
    
    Route::get('/report/view', [App\Http\Controllers\ReportController::class, 'report'])->name('report');
    Route::any('/report/print', [App\Http\Controllers\ReportController::class, 'print'])->name('report.print');
    Route::get('/report/project/view', [App\Http\Controllers\ReportController::class, 'project'])->name('project');
    Route::any('/report/project/print', [App\Http\Controllers\ReportController::class, 'project_report'])->name('project.print');
    Route::get('/report/leave/view', [App\Http\Controllers\ReportController::class, 'leave_report'])->name('leave.report');
    Route::any('/report/leave/print', [App\Http\Controllers\ReportController::class, 'leave_report_print'])->name('leave.report.print');
    Route::any('/report/time/index', [App\Http\Controllers\ReportController::class, 'time_index'])->name('time.index');
    Route::any('/report/time', [App\Http\Controllers\ReportController::class, 'time'])->name('time');

    Route::get('/setting', [App\Http\Controllers\SettingController::class, 'setting'])->name('setting');
    Route::post('/setting/save', [App\Http\Controllers\SettingController::class, 'save'])->name('setting.save');
    
    Route::get('/password', [App\Http\Controllers\HomeController::class, 'password'])->name('password');
    Route::any('/password/change/{id}', [App\Http\Controllers\HomeController::class, 'password_update'])->name('password.change');
    
    Route::get('/limit/disable/{id}', [App\Http\Controllers\SupervisorController::class, 'disable'])->name('disable');
    Route::get('/limit/enable/{id}', [App\Http\Controllers\SupervisorController::class, 'enable'])->name('enable');
});
