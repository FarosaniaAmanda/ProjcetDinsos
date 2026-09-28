<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\ForgotPasswordController;


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
| PETUGAS
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
| RESPONDEN
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
| KUISIONER
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


/*
|--------------------------------------------------------------------------
| MASTER
|--------------------------------------------------------------------------
*/

Route::get('/master', function () {
    return view('admin.master.index');
})->name('master.index');


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


/*
|--------------------------------------------------------------------------
| PROFIL ADMIN / OPERATOR
|--------------------------------------------------------------------------
*/

Route::get('/profil', function () {
    return view('admin.profil.index');
})->name('profil.index');


/*
|--------------------------------------------------------------------------
| UBAH PASSWORD
|--------------------------------------------------------------------------
*/

// Menampilkan halaman ubah password
Route::get('/profil/ubah-password', function () {
    return view('admin.profil.ubah-password');
})->name('profil.password');


// Memproses ubah password
Route::post('/profil/ubah-password', function (Request $request) {

    $request->validate([
        'password_lama' => 'required',
        'password_baru' => 'required|min:8|confirmed',
    ], [
        'password_lama.required' => 'Password lama wajib diisi.',
        'password_baru.required' => 'Password baru wajib diisi.',
        'password_baru.min' => 'Password baru minimal 8 karakter.',
        'password_baru.confirmed' => 'Konfirmasi password tidak cocok.',
    ]);


    /*
    |--------------------------------------------------------------------------
    | Ambil user yang sedang login
    |--------------------------------------------------------------------------
    */

    $user = Auth::user();


    /*
    |--------------------------------------------------------------------------
    | Cek password lama
    |--------------------------------------------------------------------------
    */

    if (!$user || !Hash::check($request->password_lama, $user->password)) {

        return back()
            ->withErrors([
                'password_lama' => 'Password lama tidak sesuai.'
            ])
            ->withInput();

    }


    /*
    |--------------------------------------------------------------------------
    | Simpan password baru
    |--------------------------------------------------------------------------
    */

    $user->password = Hash::make($request->password_baru);

    $user->save();


    /*
    |--------------------------------------------------------------------------
    | Kembali ke halaman profil
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('profil.index')
        ->with('success', 'Password berhasil diubah.');

})->name('profil.password.update');