<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class MonitoringController extends Controller
{
    /**
     * Data dummy monitoring
     */
    protected function getData()
    {
        return collect([
            [
                'id' => 1,
                'no_kk' => '3575010101010001',
                'nik' => '3575010101010001',
                'nama' => 'Budi Santoso',
                'anggota' => 4,
                'wilayah' => 'Bugul Kidul',
                'status' => 'Menunggu',
                'petugas' => 'Ahmad',
                'tanggal' => '20 September 2026',
            ],

            [
                'id' => 2,
                'no_kk' => '3575010101010002',
                'nik' => '3575010101010002',
                'nama' => 'Siti Aminah',
                'anggota' => 3,
                'wilayah' => 'Purworejo',
                'status' => 'Selesai',
                'petugas' => 'Rina',
                'tanggal' => '20 September 2026',
            ],

            [
                'id' => 3,
                'no_kk' => '3575010101010003',
                'nik' => '3575010101010003',
                'nama' => 'Agus Setiawan',
                'anggota' => 5,
                'wilayah' => 'Gadingrejo',
                'status' => 'Diproses',
                'petugas' => 'Dimas',
                'tanggal' => '19 September 2026',
            ],
        ]);
    }


    /**
     * Halaman utama monitoring
     */
    public function index()
    {
        $data = $this->getData();

        return view(
            'admin.monitoring.index',
            compact('data')
        );
    }


    /**
     * Halaman detail monitoring
     */
    public function detail($id)
    {
        $data = $this->getData();

        // Cari data berdasarkan ID
        $item = $data->firstWhere(
            'id',
            (int) $id
        );

        // Jika ID tidak ditemukan
        if (!$item) {
            abort(404);
        }

        return view(
            'admin.monitoring.show',
            compact('item')
        );
    }
}