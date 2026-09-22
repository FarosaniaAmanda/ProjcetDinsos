<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\Admin\MonitoringController;


/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
*/

Route::get('/', [LoginController::class, 'index'])
    ->name('login');


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Periode
|--------------------------------------------------------------------------
*/

Route::get('/periode', [PeriodeController::class, 'index'])
    ->name('periode.index');

Route::get('/periode/tambah', [PeriodeController::class, 'create'])
    ->name('periode.create');

Route::get('/periode/edit', [PeriodeController::class, 'edit'])
    ->name('periode.edit');


/*
|--------------------------------------------------------------------------
| Verifikasi
|--------------------------------------------------------------------------
*/

Route::get('/verifikasi', [VerifikasiController::class, 'index'])
    ->name('verifikasi.index');

Route::get('/verifikasi/{id}', [VerifikasiController::class, 'show'])
    ->name('verifikasi.show');

Route::put('/verifikasi/{id}', [VerifikasiController::class, 'update'])
    ->name('verifikasi.update');


/*
|--------------------------------------------------------------------------
| Monitoring
|--------------------------------------------------------------------------
*/

Route::get('/monitoring', [MonitoringController::class, 'index'])
    ->name('monitoring.index');


/*
|--------------------------------------------------------------------------
| Master
|--------------------------------------------------------------------------
*/

Route::get('/master', function () {
    return view('admin.master.index');
})->name('master.index');


/*
|--------------------------------------------------------------------------
| Master - Operator
|--------------------------------------------------------------------------
*/

Route::get('/master/operator/create', function () {
    return view('admin.master.operator.create');
})->name('master.operator.create');

Route::get('/master/operator/{id}/edit', function ($id) {
    return view('admin.master.operator.edit');
})->name('master.operator.edit');


/*
|--------------------------------------------------------------------------
| Master - Verifikator
|--------------------------------------------------------------------------
*/

Route::get('/master/verifikator/create', function () {
    return view('admin.master.verifikator.create');
})->name('master.verifikator.create');

Route::get('/master/verifikator/{id}/edit', function ($id) {
    return view('admin.master.verifikator.edit');
})->name('master.verifikator.edit');