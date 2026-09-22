<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class VerifikasiController extends Controller
{
    /**
     * Data dummy/default
     *
     * Status yang disimpan menggunakan kode:
     * pending
     * draft
     * not_processed
     * approved
     * rejected
     */
    protected function getDefaultData()
    {
        return collect([
            [
                'id' => 1,
                'no_kk' => '3575010101010001',
                'nik' => '3575010101010001',
                'nama' => 'Budi Santoso',
                'anggota' => 4,
                'status' => 'pending',
                'status_label' => 'Pending Verification',
                'wilayah' => 'Bugul Kidul',
                'petugas' => 'Ahmad',
                'tanggal' => '20 September 2026',
            ],

            [
                'id' => 2,
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
                'id' => 3,
                'no_kk' => '3575010101010003',
                'nik' => '3575010101010003',
                'nama' => 'Agus Setiawan',
                'anggota' => 5,
                'status' => 'not_processed',
                'status_label' => 'Not Processed',
                'wilayah' => 'Gadingrejo',
                'petugas' => 'Dimas',
                'tanggal' => '19 September 2026',
            ],

            [
                'id' => 4,
                'no_kk' => '3575010101010004',
                'nik' => '3575010101010004',
                'nama' => 'Dewi Lestari',
                'anggota' => 2,
                'status' => 'approved',
                'status_label' => 'Approved',
                'wilayah' => 'Panggungrejo',
                'petugas' => 'Sari',
                'tanggal' => '18 September 2026',
            ],

            [
                'id' => 5,
                'no_kk' => '3575010101010005',
                'nik' => '3575010101010005',
                'nama' => 'Eko Prasetyo',
                'anggota' => 6,
                'status' => 'rejected',
                'status_label' => 'Rejected',
                'wilayah' => 'Bugul Kidul',
                'petugas' => 'Andi',
                'tanggal' => '17 September 2026',
            ],
        ]);
    }


    /**
     * Mengubah status lama menjadi status standar.
     *
     * Ini berguna jika session sebelumnya masih menyimpan
     * status seperti:
     * menunggu, belum, disetujui, ditolak.
     */
    protected function normalizeStatus($status)
    {
        return match ($status) {

            // Status standar
            'pending' => 'pending',
            'draft' => 'draft',
            'not_processed' => 'not_processed',
            'approved' => 'approved',
            'rejected' => 'rejected',

            // Status lama
            'menunggu' => 'pending',
            'belum' => 'not_processed',
            'disetujui' => 'approved',
            'ditolak' => 'rejected',

            // Jika kosong
            null, '' => 'pending',

            // Jika status tidak dikenal
            default => 'pending',
        };
    }


    /**
     * Mengambil label berdasarkan status.
     */
    protected function getStatusLabel($status)
    {
        return match ($status) {

            'pending' => 'Pending Verification',

            'draft' => 'Draft',

            'not_processed' => 'Not Processed',

            'approved' => 'Approved',

            'rejected' => 'Rejected',

            default => 'Pending Verification',
        };
    }


    /**
     * Mengambil data dari session.
     */
    protected function getData()
    {
        $data = session('verifikasi_data');


        /**
         * Jika session belum tersedia,
         * gunakan data dummy.
         */
        if (empty($data)) {

            $data = $this->getDefaultData();

            $this->saveData($data);
        }


        /**
         * Pastikan data menjadi Collection.
         */
        if (!$data instanceof Collection) {
            $data = collect($data);
        }


        /**
         * Normalisasi setiap data.
         *
         * Tujuannya supaya data lama yang masih memiliki
         * status Indonesia tidak menyebabkan Unknown.
         */
        $data = $data->map(function ($item) {

            /**
             * Pastikan ID tersedia.
             */
            $item['id'] = $item['id'] ?? ($item['no'] ?? null);


            /**
             * Normalisasi status.
             */
            $status = $this->normalizeStatus(
                $item['status'] ?? null
            );


            /**
             * Simpan status standar.
             */
            $item['status'] = $status;


            /**
             * Pastikan label juga sesuai dengan status.
             */
            $item['status_label'] = $this->getStatusLabel(
                $status
            );


            return $item;
        });


        /**
         * Simpan kembali hasil normalisasi
         * supaya session menggunakan format terbaru.
         */
        $this->saveData($data);


        return $data;
    }


    /**
     * Menyimpan data ke session.
     */
    protected function saveData($data)
    {
        if (!$data instanceof Collection) {
            $data = collect($data);
        }

        session([
            'verifikasi_data' => $data
                ->values()
                ->all()
        ]);
    }


    /**
 * Halaman utama verifikasi.
 */
public function index(Request $request)
{
    $data = $this->getData();

    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */
    $search = trim(
        $request->input('search', '')
    );

    if ($search !== '') {

        $searchLower = strtolower($search);

        $data = $data->filter(function ($item) use ($searchLower) {

            return
                str_contains(
                    strtolower($item['no_kk'] ?? ''),
                    $searchLower
                )
                ||
                str_contains(
                    strtolower($item['nik'] ?? ''),
                    $searchLower
                )
                ||
                str_contains(
                    strtolower($item['nama'] ?? ''),
                    $searchLower
                )
                ||
                str_contains(
                    strtolower($item['wilayah'] ?? ''),
                    $searchLower
                )
                ||
                str_contains(
                    strtolower($item['petugas'] ?? ''),
                    $searchLower
                );
        });
    }

    /*
    |--------------------------------------------------------------------------
    | FILTER STATUS
    |--------------------------------------------------------------------------
    */
    $status = $request->input(
        'status',
        ''
    );

    /*
    |--------------------------------------------------------------------------
    | Jika memilih All Status
    |--------------------------------------------------------------------------
    */
    if ($status === 'all') {
        $status = '';
    }

    /*
    |--------------------------------------------------------------------------
    | Filter berdasarkan status
    |--------------------------------------------------------------------------
    */
    if ($status !== '') {

        $status = $this->normalizeStatus(
            $status
        );

        $data = $data->filter(function ($item) use ($status) {

            return ($item['status'] ?? '') === $status;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    | 30 data setiap halaman
    |--------------------------------------------------------------------------
    */
    $perPage = 30;

    /*
    |--------------------------------------------------------------------------
    | Menentukan halaman saat ini
    |--------------------------------------------------------------------------
    */
    $currentPage = LengthAwarePaginator::resolveCurrentPage();

    /*
    |--------------------------------------------------------------------------
    | Reset index Collection
    |--------------------------------------------------------------------------
    */
    $data = $data->values();

    /*
    |--------------------------------------------------------------------------
    | Ambil data sesuai halaman
    |--------------------------------------------------------------------------
    */
    $currentItems = $data
        ->slice(
            ($currentPage - 1) * $perPage,
            $perPage
        )
        ->values();

    /*
    |--------------------------------------------------------------------------
    | Buat paginator
    |--------------------------------------------------------------------------
    */
    $data = new LengthAwarePaginator(
        $currentItems,
        $data->count(),
        $perPage,
        $currentPage,
        [
            'path' => $request->url(),
            'query' => $request->query(),
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | Kirim ke Blade
    |--------------------------------------------------------------------------
    */
    return view(
        'admin.verifikasi.index',
        [
            'data' => $data,
            'search' => $search,
            'status' => $status,
        ]
    );
}


    /**
     * Halaman detail / edit verifikasi.
     */
    public function show($id)
    {
        $item = $this->getData()
            ->firstWhere(
                'id',
                (int) $id
            );


        if (!$item) {
            abort(404);
        }


        return view(
            'admin.verifikasi.show',
            compact('item')
        );
    }


    /**
     * Update status verifikasi.
     *
     * Hanya approved dan rejected
     * yang dapat dipilih pada halaman edit.
     */
    public function update(
        Request $request,
        $id
    ) {

        /**
         * Validasi status.
         */
        $request->validate(
            [
                'status' => 'required|in:approved,rejected',
            ],
            [
                'status.required' =>
                    'Silakan pilih status verifikasi.',

                'status.in' =>
                    'Status yang dipilih tidak valid.',
            ]
        );


        /**
         * Ambil data.
         */
        $data = $this->getData();


        /**
         * Cari data berdasarkan ID.
         */
        $item = $data->firstWhere(
            'id',
            (int) $id
        );


        if (!$item) {
            abort(404);
        }


        /**
         * Ambil status baru.
         */
        $statusValue = $request->input(
            'status'
        );


        /**
         * Pastikan status menggunakan
         * kode standar.
         */
        $statusValue = $this->normalizeStatus(
            $statusValue
        );


        /**
         * Tentukan label status.
         */
        $statusLabel = $this->getStatusLabel(
            $statusValue
        );


        /**
         * Update data.
         */
        $data = $data->map(
            function ($row) use (
                $id,
                $statusValue,
                $statusLabel
            ) {

                if (
                    (int) ($row['id'] ?? 0)
                    ===
                    (int) $id
                ) {

                    $row['status'] =
                        $statusValue;

                    $row['status_label'] =
                        $statusLabel;
                }


                return $row;
            }
        );


        /**
         * Simpan perubahan ke session.
         */
        $this->saveData($data);


        /**
         * Kembali ke halaman verifikasi.
         */
        return redirect()
            ->route('verifikasi.index')
            ->with(
                'success',
                'Status berhasil diperbarui menjadi '
                . $statusLabel
                . '.'
            );
    }
}