<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Halaman Lupa Password
     */
    public function showForgotForm()
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Membuat link reset password
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withErrors([
                    'email' => 'Email tidak ditemukan.',
                ])
                ->withInput();
        }

        // Buat token
        $token = Str::random(64);

        // Simpan token ke database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Buat URL reset
        $resetUrl = url(
            '/reset-password/' .
            $token .
            '?email=' .
            urlencode($request->email)
        );

        // Karena masih localhost, tampilkan link langsung
        return view('admin.auth.reset-link', [
            'resetUrl' => $resetUrl,
        ]);
    }

    /**
     * Halaman untuk membuat password baru
     */
    public function showResetForm(Request $request, $token)
    {
        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Menyimpan password baru
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        // Ambil data token
        $resetData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetData) {
            return back()->withErrors([
                'email' => 'Link reset password tidak valid atau sudah digunakan.',
            ]);
        }

        // Cek token
        if (!Hash::check($request->token, $resetData->token)) {
            return back()->withErrors([
                'email' => 'Link reset password tidak valid.',
            ]);
        }

        // Cek masa berlaku token: 60 menit
        if (now()->diffInMinutes($resetData->created_at) > 60) {

            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            return back()->withErrors([
                'email' => 'Link reset password sudah kedaluwarsa.',
            ]);
        }

        // Cari user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'User tidak ditemukan.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN PASSWORD BARU
        |--------------------------------------------------------------------------
        | User.php memiliki cast:
        | 'password' => 'hashed'
        |
        | Jadi JANGAN menggunakan Hash::make() di sini.
        | Laravel akan melakukan hashing otomatis.
        */

        $user->password = $request->password;
        $user->save();

        // Hapus token setelah berhasil digunakan
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Kembali ke halaman login
        return redirect()
            ->route('login')
            ->with(
                'success',
                'Password berhasil diubah. Silakan login dengan password baru.'
            );
    }
}