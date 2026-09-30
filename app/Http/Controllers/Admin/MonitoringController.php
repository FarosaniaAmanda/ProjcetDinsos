<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\KeluargaPart1;
use Illuminate\Pagination\LengthAwarePaginator;

class MonitoringController extends Controller
{
    /**
     * ============================================================
     * AMBIL DATA MONITORING
     * ============================================================
     */
    protected function getData()
    {
        return KeluargaPart1::query()
            ->orderByDesc('id')
            ->get()
            ->map(function ($item) {

                /*
                |--------------------------------------------------------------------------
                | CARI DATA KELUARGA
                |--------------------------------------------------------------------------
                */

                $keluarga = Keluarga::query()
                    ->where('no_kk', $item->no_kk)
                    ->first();

                /*
                | Jika berdasarkan No KK tidak ditemukan,
                | coba cari berdasarkan NIK.
                */
                if (!$keluarga && !empty($item->nik)) {
                    $keluarga = Keluarga::query()
                        ->where('nik', $item->nik)
                        ->first();
                }

                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                $status = $this->normalizeStatus($item->status);

                /*
                |--------------------------------------------------------------------------
                | DATA MONITORING
                |--------------------------------------------------------------------------
                */

                return [

                    // ID DATA PART 1
                    'id' => $item->id,

                    // IDENTITAS
                    'no_kk' => $item->no_kk ?? '-',
                    'nik' => $item->nik ?? '-',

                    // NAMA KEPALA KELUARGA
                    'nama' => $keluarga
                        ? $keluarga->nama_lengkap
                        : '-',

                    // JUMLAH ANGGOTA
                    'anggota' => $item->jml_keluarga ?? 0,

                    // DETAIL ANGGOTA KELUARGA
                    'anggota_detail' => $keluarga?->anggota?->map(function ($anggota) {
                        return [
                            'nik' => $anggota->nik ?? '-',
                            'nama_lengkap' => $anggota->nama_lengkap ?? '-',
                            'status_keluarga' => $anggota->status_keluarga ?? '-',
                        ];
                    })->values()->all() ?? [],

                    // WILAYAH
                    'wilayah' => $this->buildWilayah($item),

                    // STATUS
                    'status' => $status,
                    'status_label' => $this->getStatusLabel($status),

                    // PETUGAS
                    'petugas' => $item->created_by ?? '-',

                    // TANGGAL
                    'tanggal' => $item->created_at
                        ? $item->created_at->format('d F Y H:i')
                        : '-',

                    /*
                    |--------------------------------------------------------------------------
                    | DATA PART 1
                    |--------------------------------------------------------------------------
                    */

                    'provinsi' => $item->provinsi ?? '-',

                    'daerah' => $item->daerah ?? '-',

                    'kecamatan' => $item->kecamatan ?? '-',

                    'kelurahan' => $item->kelurahan ?? '-',

                    'kode_pos' => $item->kode_pos ?? '-',

                    'rt_rw' => $item->rt_rw ?? '-',

                    'alamat_lengkap' => $item->alamat_lengkap ?? '-',

                    'jalan_rumah' => $item->jalan_rumah ?? '-',

                    'is_alamat_sesuai' => $item->is_alamat_sesuai,

                    'geotangging' => $item->geotangging ?? '-',

                    /*
                    |--------------------------------------------------------------------------
                    | PART
                    |--------------------------------------------------------------------------
                    */

                    'current_part' => $item->current_part ?? 1,

                    /*
                    |--------------------------------------------------------------------------
                    | PERIODE
                    |--------------------------------------------------------------------------
                    */

                    'keluarga_periode_kode' =>
                        $item->keluarga_periode_kode ?? null,

                    /*
                    |--------------------------------------------------------------------------
                    | CREATED AT
                    |--------------------------------------------------------------------------
                    */

                    'created_at' => $item->created_at,

                    /*
                    |--------------------------------------------------------------------------
                    | KUISONER
                    |--------------------------------------------------------------------------
                    */

                    'kuisioner' => $this->buildQuestionnaire($item),
                ];
            });
    }


    /**
     * ============================================================
     * WILAYAH
     * ============================================================
     */
    protected function buildWilayah($item)
    {
        $wilayah = collect([
            $item->kecamatan,
            $item->kelurahan,
        ])
            ->filter()
            ->implode(' - ');

        return $wilayah ?: '-';
    }


    /**
     * ============================================================
     * DATA KUISONER PART 1
     * ============================================================
     */
    protected function buildQuestionnaire($item)
    {
        return [
            [
                'part' => 1,

                'title' => 'Data Keluarga & Alamat',

                'questions' => [

                    [
                        'number' => 1,
                        'question' => 'NIK',
                        'answer' => $item->nik ?? '-',
                    ],

                    [
                        'number' => 2,
                        'question' => 'Nomor Kartu Keluarga',
                        'answer' => $item->no_kk ?? '-',
                    ],

                    [
                        'number' => 3,
                        'question' => 'Jumlah anggota keluarga',
                        'answer' => $item->jml_keluarga ?? '-',
                    ],

                    [
                        'number' => 4,
                        'question' =>
                            'Provinsi tempat tinggal keluarga saat ini',
                        'answer' => $item->provinsi ?? '-',
                    ],

                    [
                        'number' => 5,
                        'question' =>
                            'Kabupaten/Kota tempat tinggal keluarga saat ini',
                        'answer' => $item->daerah ?? '-',
                    ],

                    [
                        'number' => 6,
                        'question' =>
                            'Kecamatan tempat tinggal keluarga saat ini',
                        'answer' => $item->kecamatan ?? '-',
                    ],

                    [
                        'number' => 7,
                        'question' =>
                            'Desa/Kelurahan tempat tinggal keluarga saat ini',
                        'answer' => $item->kelurahan ?? '-',
                    ],

                    [
                        'number' => 8,
                        'question' => 'Nomor Kode Pos',
                        'answer' => $item->kode_pos ?? '-',
                    ],

                    [
                        'number' => 9,
                        'question' =>
                            'Satuan Lingkungan Setempat (RT/RW/Dusun dll)',
                        'answer' => $item->rt_rw ?? '-',
                    ],

                    [
                        'number' => 10,
                        'question' =>
                            'Alamat Lengkap Rumah/Tempat Tinggal Anda?',
                        'answer' => $item->alamat_lengkap ?? '-',
                    ],

                    [
                        'number' => 11,
                        'question' =>
                            'Nama jalan Rumah/Tempat Tinggal Anda?',
                        'answer' => $item->jalan_rumah ?? '-',
                    ],

                    [
                        'number' => 12,
                        'question' => 'Nomor Rumah',
                        'answer' => '-',
                    ],

                    [
                        'number' => 13,
                        'question' =>
                            'Apakah alamat tempat tinggal saat ini sesuai dengan Kartu Keluarga?',
                        'answer' =>
                            $this->formatAlamatSesuai(
                                $item->is_alamat_sesuai
                            ),
                    ],

                    [
                        'number' => 14,
                        'question' =>
                            'Titik lokasi (geotagging) tempat tinggal saat ini',
                        'answer' => $item->geotangging ?? '-',
                    ],
                ],
            ],
        ];
    }


    /**
     * ============================================================
     * FORMAT ALAMAT
     * ============================================================
     */
    protected function formatAlamatSesuai($value)
    {
        if ($value === null || $value === '') {
            return '-';
        }

        return (bool) $value
            ? 'Ya, Sesuai'
            : 'Tidak Sesuai';
    }


    /**
     * ============================================================
     * NORMALISASI STATUS
     * ============================================================
     */
    protected function normalizeStatus($status)
    {
        return match (strtolower(trim((string) $status))) {

            'approved',
            'disetujui'
                => 'approved',

            'rejected',
            'reject',
            'ditolak'
                => 'rejected',

            'pending',
            'menunggu'
                => 'pending',

            'draft'
                => 'draft',

            'not_processed',
            'belum'
                => 'not_processed',

            default
                => 'pending',
        };
    }


    /**
     * ============================================================
     * LABEL STATUS
     * ============================================================
     */
    protected function getStatusLabel($status)
    {
        return match ($status) {

            'approved'
                => 'Disetujui',

            'rejected'
                => 'Ditolak',

            'pending'
                => 'Menunggu',

            'draft'
                => 'Draft',

            'not_processed'
                => 'Belum Diproses',

            default
                => 'Menunggu',
        };
    }


    /**
     * ============================================================
     * HALAMAN MONITORING
     * ============================================================
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | AMBIL SEMUA DATA
        |--------------------------------------------------------------------------
        |
        | getData() tetap mengambil seluruh data.
        | Pagination dilakukan setelah data selesai diproses.
        |
        */

        $allData = $this->getData();

        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        |
        | Statistik dihitung dari SELURUH data,
        | bukan hanya data yang sedang tampil di halaman.
        |
        */

        $totalResponden = $allData->count();

        $dataSudahDidata = $allData
            ->whereIn('status', [
                'approved',
                'rejected',
            ])
            ->count();

        $belumDidata = $allData
            ->whereIn('status', [
                'draft',
                'pending',
                'not_processed',
            ])
            ->count();

        $disetujui = $allData
            ->where('status', 'approved')
            ->count();

        $ditolak = $allData
            ->where('status', 'rejected')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------

        |
        */

        $perPage = 5;

        /*
        |--------------------------------------------------------------------------
        | HALAMAN AKTIF
        |--------------------------------------------------------------------------
        */

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        /*
        |--------------------------------------------------------------------------
        | PASTIKAN INDEX COLLECTION NORMAL
        |--------------------------------------------------------------------------
        */

        $allData = $allData->values();

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA SESUAI HALAMAN
        |--------------------------------------------------------------------------
        */

        $currentItems = $allData
            ->slice(
                ($currentPage - 1) * $perPage,
                $perPage
            )
            ->values();

        /*
        |--------------------------------------------------------------------------
        | BUAT PAGINATOR
        |--------------------------------------------------------------------------
        */

        $data = new LengthAwarePaginator(
            $currentItems,
            $allData->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),

                'query' => request()->query(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | KIRIM KE VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.monitoring.index',
            compact(
                'data',
                'totalResponden',
                'dataSudahDidata',
                'belumDidata',
                'disetujui',
                'ditolak'
            )
        );
    }


    /**
     * ============================================================
     * DETAIL MONITORING
     * ============================================================
     */
    public function detail($id)
    {
        /*
        |--------------------------------------------------------------------------
        | DETAIL TETAP MENGGUNAKAN SELURUH DATA
        |--------------------------------------------------------------------------
        |
        | Jangan menggunakan collection yang sudah dipagination.
        | Dengan begitu Detail berdasarkan ID tetap dapat ditemukan
        | meskipun datanya berada di halaman 2, 3, dan seterusnya.
        |
        */

        $data = $this->getData();

        $item = $data->firstWhere(
            'id',
            (int) $id
        );

        if (!$item) {
            abort(404);
        }

        return view(
            'admin.monitoring.show',
            compact('item')
        );
    }
}