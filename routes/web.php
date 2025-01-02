<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\ReportController;



Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.perform');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


Route::group(['middleware' => ['auth', 'prevent-back-history']], function () {
    Route::get('/', [ReportController::class, 'index'])->name('report.index');
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring');
    Route::post('/ajax_get_report', [ReportController::class, 'ajax_get_report'])->name('ajax_get_report');
});
