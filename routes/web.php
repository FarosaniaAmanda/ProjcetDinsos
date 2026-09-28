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

/*
|--------------------------------------------------------------------------
| HALAMAN UTAMA
|--------------------------------------------------------------------------
*/

Route::get('/kuisioner', [KuisionerController::class, 'index'])
    ->name('kuisioner.index');


/*
|--------------------------------------------------------------------------
| MULAI KUISIONER → PART 1
|--------------------------------------------------------------------------
*/

Route::get('/kuisioner/part1', [KuisionerController::class, 'part1'])
    ->name('kuisioner.part1');


/*
|--------------------------------------------------------------------------
| SIMPAN PART 1
|--------------------------------------------------------------------------
*/

Route::post('/kuisioner/part1', [KuisionerController::class, 'storePart1'])
    ->name('kuisioner.part1.store');


/*
|--------------------------------------------------------------------------
| HALAMAN DRAFT
|--------------------------------------------------------------------------
*/

Route::get('/kuisioner/draft', [KuisionerController::class, 'draft'])
    ->name('kuisioner.draft');


/*
|--------------------------------------------------------------------------
| LANJUTKAN DRAFT
|--------------------------------------------------------------------------
*/

Route::get('/kuisioner/draft/{id}', [KuisionerController::class, 'resumeDraft'])
    ->name('kuisioner.draft.resume');


/*
|--------------------------------------------------------------------------
| HALAMAN SELESAI
|--------------------------------------------------------------------------
*/

Route::get('/kuisioner/selesai', [KuisionerController::class, 'selesai'])
    ->name('kuisioner.selesai');


/*
|--------------------------------------------------------------------------
| PART 2
|--------------------------------------------------------------------------
*/

Route::get('/kuisioner/part2', function () {

    if (!session('part1_selesai')) {
        return redirect()
            ->route('kuisioner.part1')
            ->with(
                'warning',
                'Silakan lengkapi dan simpan Part 1 terlebih dahulu.'
            );
    }

    return view('admin.kuisioner.part2');

})->name('kuisioner.part2');


/*
|--------------------------------------------------------------------------
| PART 3
|--------------------------------------------------------------------------
*/

Route::get('/kuisioner/part3', function () {

    if (!session('part2_selesai')) {
        return redirect()
            ->route('kuisioner.part2')
            ->with(
                'warning',
                'Silakan lengkapi dan simpan Part 2 terlebih dahulu.'
            );
    }

    return view('admin.kuisioner.part3');

})->name('kuisioner.part3');


/*
|--------------------------------------------------------------------------
| PART 4
|--------------------------------------------------------------------------
*/

Route::get('/kuisioner/part4', function () {

    if (!session('part3_selesai')) {
        return redirect()
            ->route('kuisioner.part3')
            ->with(
                'warning',
                'Silakan lengkapi dan simpan Part 3 terlebih dahulu.'
            );
    }

    return view('admin.kuisioner.part4');

})->name('kuisioner.part4');


/*
|--------------------------------------------------------------------------
| PART 5
|--------------------------------------------------------------------------
*/

Route::get('/kuisioner/part5', function () {

    if (!session('part4_selesai')) {
        return redirect()
            ->route('kuisioner.part4')
            ->with(
                'warning',
                'Silakan lengkapi dan simpan Part 4 terlebih dahulu.'
            );
    }

    return view('admin.kuisioner.part5');

})->name('kuisioner.part5');


/*
|--------------------------------------------------------------------------
| PART 2
|--------------------------------------------------------------------------
*/

Route::get('/kuisioner/part2', function () {

    if (!session('part1_selesai')) {

        return redirect()
            ->route('kuisioner.index')
            ->with(
                'warning',
                'Silakan lengkapi dan simpan Part 1 terlebih dahulu.'
            );
    }

    return view('admin.kuisioner.part2');

})->name('kuisioner.part2');


/*
|--------------------------------------------------------------------------
| PART 3
|--------------------------------------------------------------------------
*/

Route::get('/kuisioner/part3', function () {

    if (!session('part2_selesai')) {

        return redirect()
            ->route('kuisioner.part2')
            ->with(
                'warning',
                'Silakan lengkapi dan simpan Part 2 terlebih dahulu.'
            );
    }

    return view('admin.kuisioner.part3');

})->name('kuisioner.part3');


/*
|--------------------------------------------------------------------------
| PART 4
|--------------------------------------------------------------------------
*/

Route::get('/kuisioner/part4', function () {

    if (!session('part3_selesai')) {

        return redirect()
            ->route('kuisioner.part3')
            ->with(
                'warning',
                'Silakan lengkapi dan simpan Part 3 terlebih dahulu.'
            );
    }

    return view('admin.kuisioner.part4');

})->name('kuisioner.part4');


/*
|--------------------------------------------------------------------------
| PART 5
|--------------------------------------------------------------------------
*/

Route::get('/kuisioner/part5', function () {

    if (!session('part4_selesai')) {

        return redirect()
            ->route('kuisioner.part4')
            ->with(
                'warning',
                'Silakan lengkapi dan simpan Part 4 terlebih dahulu.'
            );
    }

    return view('admin.kuisioner.part5');

})->name('kuisioner.part5');


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