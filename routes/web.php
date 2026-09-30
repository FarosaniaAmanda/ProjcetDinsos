<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KuisionerController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\RespondenController;
use App\Http\Controllers\Admin\VerifikasiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

// Menampilkan halaman login
Route::get('/', [LoginController::class, 'index'])
    ->name('login');

// Memproses login
Route::post('/login', [LoginController::class, 'login'])
    ->name('login.process');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| LUPA PASSWORD
|--------------------------------------------------------------------------
*/

// Menampilkan halaman lupa password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])
    ->name('password.request');

// Memproses permintaan reset password
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
    ->name('password.email');

// Menampilkan halaman reset password
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])
    ->name('password.reset');

// Memproses password baru
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])
    ->name('password.update');

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

Route::get('/periode/edit', [PeriodeController::class, 'edit'])
    ->name('periode.edit');

Route::put('/periode/{id}', [PeriodeController::class, 'update'])
    ->name('periode.update');

Route::delete('/periode/{id}', [PeriodeController::class, 'destroy'])
    ->name('periode.destroy');

/*
|--------------------------------------------------------------------------
| Petugas
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
| Responden
|--------------------------------------------------------------------------
*/

Route::get('/responden/{id}/edit-data', [RespondenController::class, 'editData'])
    ->name('responden.editData');

Route::get('/responden/kelurahan/{kecamatanId}', [RespondenController::class, 'getKelurahan'])
    ->name('responden.kelurahan');

Route::get('/responden/peta/{kelurahanId}', [RespondenController::class, 'map'])
    ->name('responden.map');

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

    return redirect()
        ->route('master.pengguna.index');

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
