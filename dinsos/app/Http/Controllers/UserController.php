<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $operators = User::where('role', 'operator')
            ->orderBy('name')
            ->get();

        $verifikators = User::where('role', 'verifikator')
            ->orderBy('name')
            ->get();

        $petugas = User::where('role', 'petugas')
            ->orderBy('name')
            ->get();

        $kelurahans = Kelurahan::orderBy('deskripsi')
            ->get();

        return view(
            'admin.master.pengguna.index',
            compact(
                'operators',
                'verifikators',
                'petugas',
                'kelurahans'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'role' => [
                'required',
                Rule::in([
                    'operator',
                    'verifikator',
                    'petugas',
                ]),
            ],

            'nomor_identitas' => [
                'required',
                'string',
                'max:50',
                'unique:users,nomor_identitas',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'kelurahan_id' => [
                'required_if:role,petugas',
                'nullable',
                'string',
                'exists:kelurahans,kelurahan_id',
            ],
        ]);


        DB::transaction(function () use ($validated) {

            $kelurahan = null;

            /*
            |--------------------------------------------------------------------------
            | Ambil Kelurahan Petugas
            |--------------------------------------------------------------------------
            */

            if ($validated['role'] === 'petugas') {

                $kelurahan = Kelurahan::where(
                    'kelurahan_id',
                    $validated['kelurahan_id']
                )->firstOrFail();
            }


            /*
            |--------------------------------------------------------------------------
            | Buat User
            |--------------------------------------------------------------------------
            */

            User::create([

                'nomor_identitas' =>
                    $validated['nomor_identitas'],

                'name' =>
                    $validated['name'],

                'email' =>
                    $validated['email'],

                // Password default
                'password' =>
                    'perlinsos123',

                'role' =>
                    $validated['role'],

                'kelurahan' =>
                    $kelurahan
                        ? $kelurahan->deskripsi
                        : null,
            ]);
        });


        return response()->json([

            'success' => true,

            'message' =>
                ucfirst($validated['role'])
                . ' berhasil ditambahkan.',

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        User $user
    ) {
        $validated = $request->validate([

            'role' => [
                'required',
                Rule::in([
                    'operator',
                    'verifikator',
                    'petugas',
                ]),
            ],

            'nomor_identitas' => [
                'required',
                'string',
                'max:50',

                Rule::unique(
                    'users',
                    'nomor_identitas'
                )->ignore($user->id),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',

                Rule::unique(
                    'users',
                    'email'
                )->ignore($user->id),
            ],

            'kelurahan_id' => [
                'required_if:role,petugas',
                'nullable',
                'string',
                'exists:kelurahans,kelurahan_id',
            ],
        ]);


        DB::transaction(function () use (
            $validated,
            $user
        ) {

            $kelurahan = null;


            /*
            |--------------------------------------------------------------------------
            | Ambil Kelurahan
            |--------------------------------------------------------------------------
            */

            if ($validated['role'] === 'petugas') {

                $kelurahan = Kelurahan::where(
                    'kelurahan_id',
                    $validated['kelurahan_id']
                )->firstOrFail();
            }


            /*
            |--------------------------------------------------------------------------
            | Data User
            |--------------------------------------------------------------------------
            */

            $data = [

                'nomor_identitas' =>
                    $validated['nomor_identitas'],

                'name' =>
                    $validated['name'],

                'email' =>
                    $validated['email'],

                'role' =>
                    $validated['role'],

                'kelurahan' =>
                    $kelurahan
                        ? $kelurahan->deskripsi
                        : null,
            ];


            $user->update($data);
        });


        return response()->json([

            'success' => true,

            'message' =>
                'Data pengguna berhasil diperbarui.',

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | RESET PASSWORD
    |--------------------------------------------------------------------------
    */

    public function resetPassword(User $user)
    {
        $user->update([

            'password' =>
                'perlinsos123',

        ]);


        return response()->json([

            'success' => true,

            'message' =>
                'Password berhasil direset ke password default.',

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(User $user)
    {
        $user->delete();


        return response()->json([

            'success' => true,

            'message' =>
                'Data pengguna berhasil dihapus.',

        ]);
    }
}