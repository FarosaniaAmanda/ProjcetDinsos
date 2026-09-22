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
| Petugas
|--------------------------------------------------------------------------
|
| Sementara menggunakan halaman view langsung.
| Nanti bisa diganti menggunakan PetugasController
| ketika fitur Petugas sudah dibuat.
|
*/

Route::get('/petugas', function () {
    return view('admin.petugas.index');
})->name('petugas.index');


/*
|--------------------------------------------------------------------------
| Responden
|--------------------------------------------------------------------------
|
| Sementara menggunakan halaman view langsung.
| Nanti bisa diganti menggunakan RespondenController
| ketika fitur Responden sudah dibuat.
|
*/

Route::get('/responden', function () {
    return view('admin.responden.index');
})->name('responden.index');


/*
|--------------------------------------------------------------------------
| Kuisioner
|--------------------------------------------------------------------------
|
| Sementara menggunakan halaman view langsung.
| Nanti bisa diganti menggunakan KuisionerController
| ketika fitur Kuisioner sudah dibuat.
|
*/

Route::get('/kuisioner', function () {
    return view('admin.kuisioner.index');
})->name('kuisioner.index');


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

Route::get('/monitoring/{id}', [MonitoringController::class, 'detail'])
    ->name('monitoring.detail');


/*
|--------------------------------------------------------------------------
| Laporan
|--------------------------------------------------------------------------
|

|
*/

Route::get('/laporan', function () {
    return view('admin.laporan.index');
})->name('laporan.index');


/*
|--------------------------------------------------------------------------
| Master
|--------------------------------------------------------------------------
*/

Route::get('/master', function () {
    return view('admin.master.index');
})->name('master.index');