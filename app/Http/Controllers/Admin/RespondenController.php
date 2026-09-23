<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use Illuminate\Http\Request;

class RespondenController extends Controller
{
    /**
     * Menampilkan daftar responden.
     */
    public function index()
    {
        $keluargas = Keluarga::with('anggota')
            ->latest()
            ->get();

        return view(
            'admin.responden.index',
            compact('keluargas')
        );
    }

    /**
     * Menampilkan form tambah responden.
     */
    public function create()
    {
        return view('admin.responden.create');
    }

    /**
     * Menyimpan data responden.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_kk' => [
                'required',
                'string',
                'max:255',
            ],

            'nama_kepala_keluarga' => [
                'required',
                'string',
                'max:255',
            ],

            'anggota' => [
                'required',
                'array',
                'min:1',
            ],

            'anggota.*.nik' => [
                'required',
                'string',
                'max:18',
            ],

            'anggota.*.nama_lengkap' => [
                'required',
                'string',
                'max:255',
            ],

            'anggota.*.status_keluarga' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATA KELUARGA
        |--------------------------------------------------------------------------
        */

        $keluarga = Keluarga::create([
            'nomor_kk' => $request->nomor_kk,
            'nama_kepala_keluarga' => $request->nama_kepala_keluarga,
        ]);

        /*
        |--------------------------------------------------------------------------
        | SIMPAN ANGGOTA KELUARGA
        |--------------------------------------------------------------------------
        */

        foreach ($request->anggota as $anggota) {

            $statusKeluarga = $anggota['status_keluarga'] ?? '';

            if ($statusKeluarga === 'Lainnya') {
                $customStatus = trim((string) ($anggota['status_keluarga_lainnya'] ?? ''));
                $statusKeluarga = $customStatus !== '' ? $customStatus : 'Lainnya';
            }

            $keluarga->anggota()->create([
                'nik' => $anggota['nik'],
                'nama_lengkap' => $anggota['nama_lengkap'],
                'status_keluarga' => $statusKeluarga,
            ]);
        }

        return redirect()
            ->route('responden.index')
            ->with(
                'success',
                'Data responden berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan form edit responden.
     */
    public function edit($id)
    {
        $keluarga = Keluarga::with('anggota')
            ->findOrFail($id);

        return view(
            'admin.responden.edit',
            compact('keluarga')
        );
    }

    /**
     * Memperbarui data responden.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_kk' => [
                'required',
                'string',
                'max:255',
            ],

            'nama_kepala_keluarga' => [
                'required',
                'string',
                'max:255',
            ],

            'anggota' => [
                'required',
                'array',
                'min:1',
            ],

            'anggota.*.nik' => [
                'required',
                'string',
                'max:18',
            ],

            'anggota.*.nama_lengkap' => [
                'required',
                'string',
                'max:255',
            ],

            'anggota.*.status_keluarga' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $keluarga = Keluarga::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA KELUARGA
        |--------------------------------------------------------------------------
        */

        $keluarga->update([
            'nomor_kk' => $request->nomor_kk,
            'nama_kepala_keluarga' => $request->nama_kepala_keluarga,
        ]);

        /*
        |--------------------------------------------------------------------------
        | HAPUS ANGGOTA LAMA
        |--------------------------------------------------------------------------
        */

        $keluarga->anggota()->delete();

        /*
        |--------------------------------------------------------------------------
        | SIMPAN ANGGOTA BARU
        |--------------------------------------------------------------------------
        */

        foreach ($request->anggota as $anggota) {

            $statusKeluarga = $anggota['status_keluarga'] ?? '';

            if ($statusKeluarga === 'Lainnya') {
                $customStatus = trim((string) ($anggota['status_keluarga_lainnya'] ?? ''));
                $statusKeluarga = $customStatus !== '' ? $customStatus : 'Lainnya';
            }

            $keluarga->anggota()->create([
                'nik' => $anggota['nik'],
                'nama_lengkap' => $anggota['nama_lengkap'],
                'status_keluarga' => $statusKeluarga,
            ]);
        }

        return redirect()
            ->route('responden.index')
            ->with(
                'success',
                'Data responden berhasil diperbarui.'
            );
    }

    /**
     * Menghapus responden.
     */
    public function destroy($id)
    {
        $keluarga = Keluarga::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | HAPUS ANGGOTA
        |--------------------------------------------------------------------------
        */

        $keluarga->anggota()->delete();

        /*
        |--------------------------------------------------------------------------
        | HAPUS KELUARGA
        |--------------------------------------------------------------------------
        */

        $keluarga->delete();

        return redirect()
            ->route('responden.index')
            ->with(
                'success',
                'Data responden berhasil dihapus.'
            );
    }

    /**
     * Detail responden.
     */
    public function detail($id)
    {
        $keluarga = Keluarga::with('anggota')
            ->findOrFail($id);

        return response()->json([
            'id' => $keluarga->id,
            'nomor_kk' => $keluarga->nomor_kk,
            'nama_kepala_keluarga' => $keluarga->nama_kepala_keluarga,
            'alamat' => $keluarga->alamat,
            'status_pendataan' => $keluarga->status_pendataan,
            'anggota' => $keluarga->anggota,
        ]);
    }
}