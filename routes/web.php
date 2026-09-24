<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Admin\RespondenController;
use App\Http\Controllers\Admin\UserController;


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/', [LoginController::class, 'index'])
    ->name('login');


/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| PERIODE
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
| PETUGAS
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
| RESPONDEN
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
| RESPONDEN - AJAX
|--------------------------------------------------------------------------
*/

/*
 * Mengambil data responden untuk modal edit
 */
Route::get('/responden/{id}/edit-data', [RespondenController::class, 'editData'])
    ->name('responden.editData');

/*
 * Mengambil kelurahan berdasarkan kecamatan
 */
Route::get('/responden/kelurahan/{kecamatanId}', [RespondenController::class, 'getKelurahan'])
    ->name('responden.kelurahan');


/*
|--------------------------------------------------------------------------
| KUISIONER
|--------------------------------------------------------------------------
*/

Route::get('/kuisioner', function () {
    return view('admin.kuisioner.index');
})->name('kuisioner.index');


/*
|--------------------------------------------------------------------------
| VERIFIKASI
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
| MONITORING
|--------------------------------------------------------------------------
*/

Route::get('/monitoring', [MonitoringController::class, 'index'])
    ->name('monitoring.index');

Route::get('/monitoring/{id}', [MonitoringController::class, 'detail'])
    ->name('monitoring.detail');


/*
|--------------------------------------------------------------------------
| LAPORAN
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
| MASTER
|--------------------------------------------------------------------------
*/

Route::get('/master', function () {
    return redirect()->route('master.pengguna.index');
})->name('master.index');

Route::get('/master/pengguna', [UserController::class, 'index'])
    ->name('master.pengguna.index');

Route::post('/master/pengguna', [UserController::class, 'store'])
    ->name('master.pengguna.store');

Route::put('/master/pengguna/{user}', [UserController::class, 'update'])
    ->name('master.pengguna.update');

Route::delete('/master/pengguna/{user}', [UserController::class, 'destroy'])
    ->name('master.pengguna.destroy');

Route::get('/master/petugas/rt-rw/{kelurahanId}', [UserController::class, 'getRtRw'])
    ->name('master.petugas.rt-rw');