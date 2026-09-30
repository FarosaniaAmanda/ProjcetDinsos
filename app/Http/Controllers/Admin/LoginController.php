<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Menampilkan halaman login
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('admin.auth.login');
    }


    /*
    |--------------------------------------------------------------------------
    | Proses Login
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'id' => 'required',
            'password' => 'required',
        ], [
            'id.required' => 'ID wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Coba Login
        |--------------------------------------------------------------------------
        |
        | Laravel akan mencari:
        | users.id = ID yang dimasukkan
        |
        | kemudian mengecek password.
        |
        */

        if (Auth::attempt([
            'id' => $credentials['id'],
            'password' => $credentials['password'],
        ])) {

            // Regenerasi session untuk keamanan
            $request->session()->regenerate();

            // Berhasil → dashboard
            return redirect()
                ->route('dashboard');
        }


        /*
        |--------------------------------------------------------------------------
        | Login Gagal
        |--------------------------------------------------------------------------
        */

        return back()
            ->withErrors([
                'id' => 'ID atau password salah.',
            ])
            ->withInput(
                $request->only('id')
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('login');
    }
}