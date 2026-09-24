<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Admin\RespondenController;


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

Route::get('/periode/edit/{id}', [PeriodeController::class, 'edit'])
    ->name('periode.edit');


/*
|--------------------------------------------------------------------------
| Petugas
|--------------------------------------------------------------------------
*/

Route::get('/petugas', [PetugasController::class, 'index'])
    ->name('petugas.index');

Route::get('/petugas/tambah', [PetugasController::class, 'create'])
    ->name('petugas.create');

Route::post('/petugas/simpan', [PetugasController::class, 'store'])
    ->name('petugas.store');

Route::get('/petugas/edit/{id}', [PetugasController::class, 'edit'])
    ->name('petugas.edit');

Route::put('/petugas/update/{id}', [PetugasController::class, 'update'])
    ->name('petugas.update');

Route::delete('/petugas/hapus/{id}', [PetugasController::class, 'destroy'])
    ->name('petugas.destroy');


/*
|--------------------------------------------------------------------------
| Responden
|--------------------------------------------------------------------------
*/

Route::get('/responden', [RespondenController::class, 'index'])
    ->name('responden.index');

Route::get('/responden/tambah', [RespondenController::class, 'create'])
    ->name('responden.create');

Route::post('/responden/simpan', [RespondenController::class, 'store'])
    ->name('responden.store');

Route::get('/responden/edit/{id}', [RespondenController::class, 'edit'])
    ->name('responden.edit');

Route::put('/responden/update/{id}', [RespondenController::class, 'update'])
    ->name('responden.update');

Route::delete('/responden/hapus/{id}', [RespondenController::class, 'destroy'])
    ->name('responden.destroy');


/*
|--------------------------------------------------------------------------
| Kuisioner
|--------------------------------------------------------------------------
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
*/

Route::get('/laporan', function () {
    return view('admin.laporan.index');
})->name('laporan.index');

Route::get('/laporan/export', function () {
    return response()->streamDownload(function () {
        echo "No. KK,Periode,Tanggal Pendataan,Status\n";
    }, 'laporan-pendataan.csv', [
        'Content-Type' => 'text/csv',
    ]);
})->name('admin.laporan.export');


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