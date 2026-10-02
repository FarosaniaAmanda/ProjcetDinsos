<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PetugasController extends Controller
{
    /**
     * Menampilkan daftar petugas.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // DATA DUMMY SEMENTARA
        // Belum menggunakan database.
        $dataPetugas = collect([
            (object) [
                'id' => 1,
                'nama_lengkap' => 'Faiz Dwi Lestari',
                'nik' => '3575000000000001',
                'jenis_kelamin' => 'Laki-laki',
                'tgl_lahir' => '2003-05-26',
                'no_hp' => '081234567890',
                'email' => 'faiz@gmail.com',
                'alamat_rumah' => 'Mancilan, Pasuruan',
                'wilayah_tugas' => 'Kelurahan Mancilan',
                'username' => 'faiz',
                'terakhir_login' => null,
            ],

            (object) [
                'id' => 2,
                'nama_lengkap' => 'Siti Aminah',
                'nik' => '3575000000000002',
                'jenis_kelamin' => 'Perempuan',
                'tgl_lahir' => '1998-04-12',
                'no_hp' => '081234567891',
                'email' => 'siti@gmail.com',
                'alamat_rumah' => 'Purutrejo, Pasuruan',
                'wilayah_tugas' => 'Kelurahan Purutrejo',
                'username' => 'siti.aminah',
                'terakhir_login' => '2026-09-21 08:30:00',
            ],

            (object) [
                'id' => 3,
                'nama_lengkap' => 'Budi Santoso',
                'nik' => '3575000000000003',
                'jenis_kelamin' => 'Laki-laki',
                'tgl_lahir' => '1995-08-20',
                'no_hp' => '081234567892',
                'email' => 'budi@gmail.com',
                'alamat_rumah' => 'Bugul, Pasuruan',
                'wilayah_tugas' => 'Kelurahan Bugul',
                'username' => 'budi.santoso',
                'terakhir_login' => '2026-09-20 09:15:00',
            ],

            (object) [
                'id' => 4,
                'nama_lengkap' => 'Dewi Anggraini',
                'nik' => '3575000000000004',
                'jenis_kelamin' => 'Perempuan',
                'tgl_lahir' => '1999-11-05',
                'no_hp' => '081234567893',
                'email' => 'dewi@gmail.com',
                'alamat_rumah' => 'Kebonsari, Pasuruan',
                'wilayah_tugas' => 'Kelurahan Kebonsari',
                'username' => 'dewi.anggraini',
                'terakhir_login' => null,
            ],
        ]);

        $dataPetugas = $dataPetugas->merge(
            collect(session('petugas_sementara', []))
                ->map(fn (array $item) => (object) $item)
        );

        // SEARCHING BIASA
        if ($search) {

            $searchLower = strtolower($search);

            $dataPetugas = $dataPetugas
                ->filter(function ($item) use ($searchLower) {

                    return
                        str_contains(
                            strtolower($item->nama_lengkap),
                            $searchLower
                        )
                        ||
                        str_contains(
                            strtolower($item->username),
                            $searchLower
                        )
                        ||
                        str_contains(
                            strtolower($item->wilayah_tugas),
                            $searchLower
                        );
                })
                ->values();
        }

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $petugas = new LengthAwarePaginator(
            $dataPetugas->forPage($currentPage, $perPage)->values(),
            $dataPetugas->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.petugas.index', compact('petugas', 'search'));
    }


    /**
     * Menampilkan form tambah petugas.
     */
    public function create()
    {
        return view('admin.petugas.create');
    }


    /**
     * Menyimpan data petugas.
     *
     * SEMENTARA HANYA UNTUK TAMPILAN.
     * Belum menyimpan ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'nik' => ['required', 'string', 'max:20'],
            'jenis_kelamin' => ['required', 'string', 'max:20'],
            'tgl_lahir' => ['required', 'date'],
            'no_hp' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:100'],
            'alamat_rumah' => ['required', 'string', 'max:255'],
            'wilayah_tugas' => ['required', 'string', 'max:150'],
            'username' => ['required', 'string', 'max:50'],
        ]);

        $temporaryPetugas = session('petugas_sementara', []);
        $validated['id'] = count($temporaryPetugas) + 5;
        $validated['terakhir_login'] = null;
        $temporaryPetugas[] = $validated;

        session(['petugas_sementara' => $temporaryPetugas]);

        return redirect()
            ->route('petugas.index')
            ->with(
                'success',
                'Data petugas berhasil ditambahkan sementara dan belum disimpan ke database.'
            );
    }


    /**
     * Menampilkan form edit petugas.
     *
     * SEMENTARA MENGGUNAKAN DATA DUMMY.
     */
    public function edit($id)
    {
        $dataPetugas = [
            1 => [
                'id' => 1,
                'nama_lengkap' => 'Faiz Dwi Lestari',
                'nik' => '3575000000000001',
                'jenis_kelamin' => 'Laki-laki',
                'tgl_lahir' => '2003-05-26',
                'no_hp' => '081234567890',
                'email' => 'faiz@gmail.com',
                'alamat_rumah' => 'Mancilan, Pasuruan',
                'wilayah_tugas' => 'Kelurahan Mancilan',
                'username' => 'faiz',
            ],

            2 => [
                'id' => 2,
                'nama_lengkap' => 'Siti Aminah',
                'nik' => '3575000000000002',
                'jenis_kelamin' => 'Perempuan',
                'tgl_lahir' => '1998-04-12',
                'no_hp' => '081234567891',
                'email' => 'siti@gmail.com',
                'alamat_rumah' => 'Purutrejo, Pasuruan',
                'wilayah_tugas' => 'Kelurahan Purutrejo',
                'username' => 'siti.aminah',
            ],

            3 => [
                'id' => 3,
                'nama_lengkap' => 'Budi Santoso',
                'nik' => '3575000000000003',
                'jenis_kelamin' => 'Laki-laki',
                'tgl_lahir' => '1995-08-20',
                'no_hp' => '081234567892',
                'email' => 'budi@gmail.com',
                'alamat_rumah' => 'Bugul, Pasuruan',
                'wilayah_tugas' => 'Kelurahan Bugul',
                'username' => 'budi.santoso',
            ],

            4 => [
                'id' => 4,
                'nama_lengkap' => 'Dewi Anggraini',
                'nik' => '3575000000000004',
                'jenis_kelamin' => 'Perempuan',
                'tgl_lahir' => '1999-11-05',
                'no_hp' => '081234567893',
                'email' => 'dewi@gmail.com',
                'alamat_rumah' => 'Kebonsari, Pasuruan',
                'wilayah_tugas' => 'Kelurahan Kebonsari',
                'username' => 'dewi.anggraini',
            ],
        ];

        // Kalau ID tidak ditemukan
        if (!isset($dataPetugas[$id])) {
            return redirect()
                ->route('petugas.index')
                ->with(
                    'success',
                    'Data petugas tidak ditemukan.'
                );
        }

        // Ubah array menjadi object
        $petugas = (object) $dataPetugas[$id];

        return view(
            'admin.petugas.edit',
            compact('petugas')
        );
    }


    /**
     * Memperbarui data petugas.
     *
     * SEMENTARA HANYA UNTUK TAMPILAN.
     * Belum menyimpan perubahan ke database.
     */
    public function update(Request $request, $id)
    {
        return redirect()
            ->route('petugas.index')
            ->with(
                'success',
                'Data petugas berhasil diperbarui.'
            );
    }


    /**
     * Menghapus data petugas.
     *
     * SEMENTARA HANYA UNTUK TAMPILAN.
     * Belum menghapus dari database.
     */
    public function destroy($id)
    {
        return redirect()
            ->route('petugas.index')
            ->with(
                'success',
                'Data petugas berhasil dihapus.'
            );
    }
}