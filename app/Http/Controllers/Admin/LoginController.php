<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        | LOGIN MENGGUNAKAN NOMOR IDENTITAS
        |--------------------------------------------------------------------------
        */

        if (Auth::attempt([
            'nomor_identitas' => $credentials['nomor_identitas'],
            'password' => $credentials['password'],
        ])) {

            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | LOGIN GAGAL
        |--------------------------------------------------------------------------
        */

        return back()
            ->withErrors([
                'nomor_identitas' => 'Nomor Identitas atau password salah.',
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