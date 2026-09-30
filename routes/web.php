<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Admin\RespondenController;
use App\Http\Controllers\Admin\KuisionerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LaporanController;


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

Route::get('/responden/{id}/edit-data', [RespondenController::class, 'editData'])
    ->name('responden.editData');

Route::get('/responden/kelurahan/{kecamatanId}', [RespondenController::class, 'getKelurahan'])
    ->name('responden.kelurahan');


/*
|--------------------------------------------------------------------------
| KUISIONER
|--------------------------------------------------------------------------
*/

// HALAMAN UTAMA
Route::get('/kuisioner', [KuisionerController::class, 'index'])
    ->name('kuisioner.index');


// HALAMAN DRAFT
Route::get('/kuisioner/draft', [KuisionerController::class, 'draft'])
    ->name('kuisioner.draft');


// LANJUTKAN DRAFT
Route::get('/kuisioner/draft/{id}', [KuisionerController::class, 'resumeDraft'])
    ->name('kuisioner.draft.resume');


// HALAMAN SELESAI
Route::get('/kuisioner/selesai', [KuisionerController::class, 'selesai'])
    ->name('kuisioner.selesai');


// ============================================================
// PART 1
// ============================================================

// Tampilkan Part 1
Route::get('/kuisioner/part1', [KuisionerController::class, 'part1'])
    ->name('kuisioner.part1');

// Simpan Part 1
Route::post('/kuisioner/part1', [KuisionerController::class, 'storePart1'])
    ->name('kuisioner.part1.store');


// ============================================================
// PART 2
// ============================================================

Route::get('/kuisioner/part2', [KuisionerController::class, 'part2'])
    ->name('kuisioner.part2');


// ============================================================
// PART 3
// ============================================================

Route::get('/kuisioner/part3', [KuisionerController::class, 'part3'])
    ->name('kuisioner.part3');


// ============================================================
// PART 4
// ============================================================

Route::get('/kuisioner/part4', [KuisionerController::class, 'part4'])
    ->name('kuisioner.part4');


// ============================================================
// PART 5
// ============================================================

Route::get('/kuisioner/part5', [KuisionerController::class, 'part5'])
    ->name('kuisioner.part5');


// ============================================================
// SELESAIKAN KUISIONER
// ============================================================

Route::post('/kuisioner/selesai', [KuisionerController::class, 'selesaiKuisioner'])
    ->name('kuisioner.selesai.store');


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

Route::get(
    '/laporan',
    [LaporanController::class, 'index']
)->name('laporan.index');

Route::get(
    '/laporan/export',
    [LaporanController::class, 'export']
)->name('admin.laporan.export');

/*
|--------------------------------------------------------------------------
| MASTER
|--------------------------------------------------------------------------
*/

Route::get('/master', function () {

    return redirect()
        ->route('master.pengguna.index');

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