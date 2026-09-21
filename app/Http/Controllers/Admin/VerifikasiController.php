<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | DATA DUMMY SEMENTARA
        |--------------------------------------------------------------------------
        | Data ini digunakan sebelum database tersedia.
        */

        $data = collect([
            [
                'no' => 1,
                'no_kk' => '3575010101010001',
                'nik' => '3575010101010001',
                'nama' => 'Budi Santoso',
                'anggota' => 4,
                'status' => 'menunggu',
                'status_label' => 'Menunggu Verifikasi',
                'wilayah' => 'Bugul Kidul',
                'petugas' => 'Ahmad',
                'tanggal' => '20 September 2026',
            ],

            [
                'no' => 2,
                'no_kk' => '3575010101010002',
                'nik' => '3575010101010002',
                'nama' => 'Siti Aminah',
                'anggota' => 3,
                'status' => 'draft',
                'status_label' => 'Draft',
                'wilayah' => 'Purworejo',
                'petugas' => 'Rina',
                'tanggal' => '20 September 2026',
            ],

            [
                'no' => 3,
                'no_kk' => '3575010101010003',
                'nik' => '3575010101010003',
                'nama' => 'Agus Setiawan',
                'anggota' => 5,
                'status' => 'belum',
                'status_label' => 'Belum Diproses',
                'wilayah' => 'Gadingrejo',
                'petugas' => 'Dimas',
                'tanggal' => '19 September 2026',
            ],

            [
                'no' => 4,
                'no_kk' => '3575010101010004',
                'nik' => '3575010101010004',
                'nama' => 'Dewi Lestari',
                'anggota' => 2,
                'status' => 'disetujui',
                'status_label' => 'Disetujui',
                'wilayah' => 'Panggungrejo',
                'petugas' => 'Sari',
                'tanggal' => '18 September 2026',
            ],

            [
                'no' => 5,
                'no_kk' => '3575010101010005',
                'nik' => '3575010101010005',
                'nama' => 'Eko Prasetyo',
                'anggota' => 6,
                'status' => 'ditolak',
                'status_label' => 'Ditolak',
                'wilayah' => 'Bugul Kidul',
                'petugas' => 'Andi',
                'tanggal' => '17 September 2026',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | PENCARIAN
        |--------------------------------------------------------------------------
        */

        $search = trim($request->input('search', ''));

        if ($search !== '') {

            $searchLower = strtolower($search);

            $data = $data->filter(function ($item) use ($searchLower) {

                return
                    str_contains(
                        strtolower($item['no_kk']),
                        $searchLower
                    ) ||

                    str_contains(
                        strtolower($item['nik']),
                        $searchLower
                    ) ||

                    str_contains(
                        strtolower($item['nama']),
                        $searchLower
                    ) ||

                    str_contains(
                        strtolower($item['wilayah']),
                        $searchLower
                    ) ||

                    str_contains(
                        strtolower($item['petugas']),
                        $searchLower
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */

        $status = $request->input('status', '');

        if ($status !== '') {

            $data = $data->filter(function ($item) use ($status) {

                return $item['status'] === $status;
            });
        }

        /*
        |--------------------------------------------------------------------------
        | KIRIM DATA KE VIEW
        |--------------------------------------------------------------------------
        */

        return view('admin.verifikasi.index', [
            'data' => $data,
            'search' => $search,
            'status' => $status,
        ]);
    }
}