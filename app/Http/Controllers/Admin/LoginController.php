<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Halaman login
     */
    public function index()
    {
        return view('admin.auth.login');
    }


    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nomor_identitas' => 'required',
            'password' => 'required',
        ], [
            'nomor_identitas.required' => 'Nomor Identitas wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Cari user berdasarkan Nomor Identitas
        |--------------------------------------------------------------------------
        */

        $user = \App\Models\User::where(
            'nomor_identitas',
            $credentials['nomor_identitas']
        )->first();


        /*
        |--------------------------------------------------------------------------
        | User tidak ditemukan
        |--------------------------------------------------------------------------
        */

        if (!$user) {

            return back()
                ->withErrors([
                    'nomor_identitas' =>
                        'Nomor Identitas atau password salah.',
                ])
                ->withInput(
                    $request->only('nomor_identitas')
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Cek password
        |--------------------------------------------------------------------------
        |
        | Password baru:
        | langsung dibandingkan sebagai teks biasa.
        |
        | Password lama:
        | jika masih berupa Bcrypt ($2y$),
        | tetap diverifikasi menggunakan Hash::check().
        |
        */

        $passwordBenar = false;

        $passwordDatabase = $user->getRawOriginal('password');


        /*
        |--------------------------------------------------------------------------
        | Password Bcrypt lama
        |--------------------------------------------------------------------------
        */

        if (
            is_string($passwordDatabase) &&
            str_starts_with($passwordDatabase, '$2y$')
        ) {

            $passwordBenar = Hash::check(
                $credentials['password'],
                $passwordDatabase
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Password teks biasa
        |--------------------------------------------------------------------------
        */

        else {

            $passwordBenar =
                $passwordDatabase === $credentials['password'];
        }


        /*
        |--------------------------------------------------------------------------
        | Login berhasil
        |--------------------------------------------------------------------------
        */

        if ($passwordBenar) {

            Auth::login($user);

            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }


        /*
        |--------------------------------------------------------------------------
        | Login gagal
        |--------------------------------------------------------------------------
        */

        return back()
            ->withErrors([
                'nomor_identitas' =>
                    'Nomor Identitas atau password salah.',
            ])
            ->withInput(
                $request->only('nomor_identitas')
            );
    }


    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}