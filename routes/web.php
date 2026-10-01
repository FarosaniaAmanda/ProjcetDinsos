<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ForgotPasswordController;
use App\Http\Controllers\Admin\KuisionerController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\RespondenController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\Admin\LaporanController;

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/', [LoginController::class, 'index'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| LUPA PASSWORD
|--------------------------------------------------------------------------
*/

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
    ->name('password.email');

Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])
    ->name('password.reset');

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
| PROFIL
|--------------------------------------------------------------------------
*/

Route::get('/profil', function () {
    return view('admin.profil.index');
})->name('profil.index');

Route::get('/profil/ubah-password', function () {
    return view('admin.profil.ubah-password');
})->name('profil.password');

Route::post('/profil/ubah-password', function (Request $request) {

    $request->validate([
        'password_lama' => 'required',
        'password_baru' => 'required|min:6',
        'password_baru_confirmation' => 'required|same:password_baru',
    ], [
        'password_lama.required' =>
            'Password lama wajib diisi.',

        'password_baru.required' =>
            'Password baru wajib diisi.',

        'password_baru.min' =>
            'Password baru minimal 6 karakter.',

        'password_baru_confirmation.required' =>
            'Konfirmasi password wajib diisi.',

        'password_baru_confirmation.same' =>
            'Konfirmasi password tidak sama.',
    ]);

    $user = Auth::user();

    if (!$user) {
        return redirect()
            ->route('login');
    }

    if (!Hash::check(
        $request->password_lama,
        $user->password
    )) {
        return back()
            ->withErrors([
                'password_lama' => 'Password lama salah.'
            ])
            ->withInput();
    }

    $user->password = Hash::make(
        $request->password_baru
    );

    $user->save();

    return redirect()
        ->route('profil.index')
        ->with(
            'success',
            'Password berhasil diubah.'
        );

})->name('profil.password.update');


/*
|--------------------------------------------------------------------------
| PERIODE
|--------------------------------------------------------------------------
*/

Route::get('/periode', [PeriodeController::class, 'index'])
    ->name('periode.index');

Route::get('/periode/tambah', [PeriodeController::class, 'create'])
    ->name('periode.create');

Route::post('/periode', [PeriodeController::class, 'store'])
    ->name('periode.store');

Route::get('/periode/{id}/edit', [PeriodeController::class, 'edit'])
    ->name('periode.edit');

Route::put('/periode/{id}', [PeriodeController::class, 'update'])
    ->name('periode.update');

Route::delete('/periode/{id}', [PeriodeController::class, 'destroy'])
    ->name('periode.destroy');


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

Route::get('/kuisioner', [KuisionerController::class, 'index'])
    ->name('kuisioner.index');

Route::get('/kuisioner/draft', [KuisionerController::class, 'draft'])
    ->name('kuisioner.draft');

Route::get('/kuisioner/draft/{id}', [KuisionerController::class, 'resumeDraft'])
    ->name('kuisioner.draft.resume');

Route::get('/kuisioner/selesai', [KuisionerController::class, 'selesai'])
    ->name('kuisioner.selesai.index');


/*
|--------------------------------------------------------------------------
| KUISIONER - PART 1
|--------------------------------------------------------------------------
*/

Route::get('/kuisioner/part1', [KuisionerController::class, 'part1'])
    ->name('kuisioner.part1');

Route::post('/kuisioner/part1', [KuisionerController::class, 'storePart1'])
    ->name('kuisioner.part1.store');


/*
|--------------------------------------------------------------------------
| KUISIONER - PART 2
|--------------------------------------------------------------------------
*/

Route::get('/kuisioner/part2', [KuisionerController::class, 'part2'])
    ->name('kuisioner.part2');

Route::post('/kuisioner/part2', [KuisionerController::class, 'storePart2'])
    ->name('kuisioner.part2.store');


/*
|--------------------------------------------------------------------------
| KUISIONER - PART 3
|--------------------------------------------------------------------------
*/

Route::get('/kuisioner/part3', [KuisionerController::class, 'part3'])
    ->name('kuisioner.part3');

Route::post('/kuisioner/part3', [KuisionerController::class, 'storePart3'])
    ->name('kuisioner.part3.store');


/*
|--------------------------------------------------------------------------
| KUISIONER - PART 4
|--------------------------------------------------------------------------
*/

Route::get('/kuisioner/part4', [KuisionerController::class, 'part4'])
    ->name('kuisioner.part4');

Route::post('/kuisioner/part4', [KuisionerController::class, 'storePart4'])
    ->name('kuisioner.part4.store');


/*
|--------------------------------------------------------------------------
| KUISIONER - PART 5
|--------------------------------------------------------------------------
*/

// ==================== PART 5 ====================

Route::get('/kuisioner/part5', [KuisionerController::class, 'part5'])
    ->name('kuisioner.part5');

Route::get('/kuisioner/part5/anggota/{kode}', [KuisionerController::class, 'part5Anggota'])
    ->name('kuisioner.part5.anggota');

Route::post('/kuisioner/part5/anggota/{kode}', [KuisionerController::class, 'storePart5Anggota'])
    ->name('kuisioner.part5.anggota.store');

// ==================== PART 5 FOTO RUMAH ====================

Route::get('/kuisioner/part5/foto', [KuisionerController::class, 'part5Foto'])
    ->name('kuisioner.part5.foto');

Route::post('/kuisioner/part5/foto', [KuisionerController::class, 'storePart5Foto'])
    ->name('kuisioner.part5.foto.store');

/*
|--------------------------------------------------------------------------
| SELESAIKAN KUISIONER
|--------------------------------------------------------------------------
*/

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


/*
|--------------------------------------------------------------------------
| MASTER - PENGGUNA
|--------------------------------------------------------------------------
*/

Route::get('/master/pengguna', [UserController::class, 'index'])
    ->name('master.pengguna.index');

Route::post('/master/pengguna', [UserController::class, 'store'])
    ->name('master.pengguna.store');

Route::put('/master/pengguna/{user}', [UserController::class, 'update'])
    ->name('master.pengguna.update');

Route::post(
    '/master/pengguna/{user}/reset-password',
    [UserController::class, 'resetPassword']
)->name('master.pengguna.reset-password');

Route::delete('/master/pengguna/{user}', [UserController::class, 'destroy'])
    ->name('master.pengguna.destroy');


/*
|--------------------------------------------------------------------------
| MASTER - OPERATOR
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
| MASTER - VERIFIKATOR
|--------------------------------------------------------------------------
*/

Route::get('/master/verifikator/create', function () {
    return view('admin.master.verifikator.create');
})->name('master.verifikator.create');

Route::get('/master/verifikator/{id}/edit', function ($id) {
    return view('admin.master.verifikator.edit');
})->name('master.verifikator.edit');