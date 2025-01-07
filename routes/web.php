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
use App\Http\Controllers\ProductBoxController;
use App\Http\Controllers\ProductPcsController;
use App\Http\Controllers\ReportController;



Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.perform');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


Route::group(['middleware' => ['auth', 'prevent-back-history']], function () {
    Route::get('/', [ReportController::class, 'index'])->name('report.index');
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring');
    Route::get('/pcs', [ProductPcsController::class, 'index'])->name('pcs.get');
    Route::post('/pcs/store', [ProductPcsController::class, 'store'])->name('pcs.store');
    Route::get('/pcs/show', [ProductPcsController::class, 'show'])->name('pcs.show');
    Route::delete('/pcs/destroy', [ProductPcsController::class, 'destroy'])->name('pcs.destroy');
    Route::get('/box', [ProductBoxController::class, 'index'])->name('box.get');
    Route::post('/box/store', [ProductBoxController::class, 'store'])->name('box.store');
    Route::get('/box/show', [ProductBoxController::class, 'show'])->name('box.show');
    Route::delete('/box/destroy', [ProductBoxController::class, 'destroy'])->name('box.destroy');
    Route::post('/ajax_get_report', [ReportController::class, 'ajax_get_report'])->name('ajax_get_report');
});
