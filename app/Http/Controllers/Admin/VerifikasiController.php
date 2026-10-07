<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\KeluargaPart1;
use App\Models\KeluargaPart2;
use App\Models\KeluargaPart3;
use App\Models\KeluargaPart4;
use App\Models\KeluargaPart5;
use App\Models\KeluargaFotoRumah;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class VerifikasiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | AMBIL SEMUA DATA
        |--------------------------------------------------------------------------
        |
        |
        */

        $allData = collect(
            $this->getData()
        );


        /*
        |--------------------------------------------------------------------------
        | STATISTIK CARD
        |--------------------------------------------------------------------------
       
        |
        */

        $totalResponden = $allData->count();

        $draftCount = $allData
            ->where('status', 'draft')
            ->count();

        $pendingCount = $allData
            ->where('status', 'pending')
            ->count();

        $approvedCount = $allData
            ->where('status', 'approved')
            ->count();

        $rejectedCount = $allData
            ->where('status', 'rejected')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        $search = trim(
            (string) $request->get('search', '')
        );

        if ($search !== '') {

            $allData = $allData
                ->filter(function ($item) use ($search) {

                    $haystack = strtolower(
                        implode(' ', [
                            $item['no_kk'] ?? '',
                            $item['nik'] ?? '',
                            $item['nama'] ?? '',
                            $item['wilayah'] ?? '',
                            $item['petugas'] ?? '',
                            $item['status_label'] ?? '',
                        ])
                    );

                    return str_contains(
                        $haystack,
                        strtolower($search)
                    );
                })
                ->values();
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */

        $statusFilter = strtolower(
            trim(
                (string) $request->get('status', '')
            )
        );

        $statusFilter = match ($statusFilter) {

            'menunggu',
            'menunggu_verifikasi',
            'pending' => 'pending',

            'disetujui',
            'approved' => 'approved',

            'ditolak',
            'rejected',
            'reject' => 'rejected',

            'draft' => 'draft',

            default => '',
        };


        if ($statusFilter !== '') {

            $allData = $allData
                ->where(
                    'status',
                    $statusFilter
                )
                ->values();
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $perPage = 5;

        $currentPage =
            LengthAwarePaginator::resolveCurrentPage();

        $currentItems = $allData
            ->slice(
                ($currentPage - 1) * $perPage,
                $perPage
            )
            ->values();

        $data = new LengthAwarePaginator(
            $currentItems,
            $allData->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.verifikasi.index',
            [
                'data' =>
                    $data,

                /*
                |--------------------------------------------------------------------------
                | CARD STATISTIK
                |--------------------------------------------------------------------------
                */

                'totalResponden' =>
                    $totalResponden,

                'draftCount' =>
                    $draftCount,

                'pendingCount' =>
                    $pendingCount,

                'approvedCount' =>
                    $approvedCount,

                'rejectedCount' =>
                    $rejectedCount,


                /*
                |--------------------------------------------------------------------------
                | KOMPATIBILITAS BLADE LAMA
                |--------------------------------------------------------------------------
                */

                'total' =>
                    $totalResponden,

                'draft' =>
                    $draftCount,

                'pending' =>
                    $pendingCount,

                'approved' =>
                    $approvedCount,

                'rejected' =>
                    $rejectedCount,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DATA VERIFIKASI
    |--------------------------------------------------------------------------
    */

    protected function getData()
    {
        $items = KeluargaPart1::query()
            ->orderByDesc('id')
            ->get();

        return $items
            ->map(function ($item) {

                /*
                |--------------------------------------------------------------------------
                | CARI DATA KELUARGA
                |--------------------------------------------------------------------------
                */

                $keluarga = null;

                if ($item->keluarga_periode_kode) {

                    $keluarga = Keluarga::where(
                        'kode',
                        $item->keluarga_periode_kode
                    )->first();
                }


                /*
                |--------------------------------------------------------------------------
                | FALLBACK BERDASARKAN NO KK
                |--------------------------------------------------------------------------
                */

                if (
                    !$keluarga &&
                    $item->no_kk
                ) {

                    $keluarga = Keluarga::where(
                        'no_kk',
                        $item->no_kk
                    )->first();
                }


                /*
                |--------------------------------------------------------------------------
                | ANGGOTA KELUARGA
                |--------------------------------------------------------------------------
                */

                $anggota = collect();

                if ($keluarga) {

                    $anggota = $keluarga
                        ->anggota()
                        ->orderBy('id')
                        ->get();
                }


                /*
                |--------------------------------------------------------------------------
                | STATUS VERIFIKASI
                |--------------------------------------------------------------------------
                */

                $status = $this->determineVerificationStatus(
                    $item,
                    $keluarga
                );


                /*
                |--------------------------------------------------------------------------
                | PROGRESS KUISIONER
                |--------------------------------------------------------------------------
                */

                $progress = $this->getProgress(
                    $item,
                    $keluarga
                );


                /*
                |--------------------------------------------------------------------------
                | DETAIL ANGGOTA
                |--------------------------------------------------------------------------
                */

                $anggotaDetail = $anggota
                    ->map(function ($member) {

                        return [
                            'kode' =>
                                $member->kode ?? null,

                            'nik' =>
                                $member->nik ?? '-',

                            'nama_lengkap' =>
                                $member->nama_lengkap ?? '-',

                            'status_keluarga' =>
                                $member->status_keluarga ?? '-',
                        ];
                    })
                    ->values()
                    ->all();


                /*
                |--------------------------------------------------------------------------
                | TANGGAL
                |--------------------------------------------------------------------------
                */

                $tanggal =
                    $item->updated_at
                    ?? $item->created_at;


                /*
                |--------------------------------------------------------------------------
                | DATA
                |--------------------------------------------------------------------------
                */

                return [

                    'id' =>
                        $item->id,


                    /*
                    |--------------------------------------------------------------------------
                    | IDENTITAS
                    |--------------------------------------------------------------------------
                    */

                    'no_kk' =>
                        $item->no_kk
                        ?? ($keluarga->no_kk ?? '-'),

                    'nik' =>
                        $item->nik
                        ?? ($keluarga->nik ?? '-'),

                    'nama' =>
                        $keluarga->nama_lengkap
                        ?? $item->nama_lengkap
                        ?? '-',


                    /*
                    |--------------------------------------------------------------------------
                    | JUMLAH ANGGOTA
                    |--------------------------------------------------------------------------
                    */

                    'jumlah_anggota' =>
                        $item->jml_keluarga
                        ?? $anggota->count(),

                    'anggota' =>
                        $item->jml_keluarga
                        ?? $anggota->count(),

                    'anggota_detail' =>
                        $anggotaDetail,


                    /*
                    |--------------------------------------------------------------------------
                    | STATUS
                    |--------------------------------------------------------------------------
                    */

                    'status' =>
                        $status,

                    'status_label' =>
                        $this->getStatusLabel(
                            $status
                        ),


                    /*
                    |--------------------------------------------------------------------------
                    | PROGRESS
                    |--------------------------------------------------------------------------
                    */

                    'progress' =>
                        $progress,

                    'progress_completed' =>
                        $progress['completed'],

                    'progress_total' =>
                        $progress['total'],

                    'progress_percent' =>
                        $progress['percent'],

                    'progress_detail' =>
                        $progress['parts'],


                    /*
                    |--------------------------------------------------------------------------
                    | WILAYAH
                    |--------------------------------------------------------------------------
                    */

                    'wilayah' =>
                        $this->buildWilayah(
                            $item,
                            $keluarga
                        ),


                    /*
                    |--------------------------------------------------------------------------
                    | PETUGAS
                    |--------------------------------------------------------------------------
                    */

                    'petugas' =>
                        $item->updated_by
                        ?? $item->created_by
                        ?? '-',

                    'tanggal' =>
                        $tanggal
                            ? $tanggal->format(
                                'd-m-Y H:i'
                            )
                            : '-',


                    /*
                    |--------------------------------------------------------------------------
                    | ALAMAT
                    |--------------------------------------------------------------------------
                    */

                    'provinsi' =>
                        $item->provinsi
                        ?? ($keluarga->provinsi ?? '-'),

                    'daerah' =>
                        $item->daerah
                        ?? ($keluarga->daerah ?? '-'),

                    'kecamatan' =>
                        $item->kecamatan
                        ?? ($keluarga->kecamatan ?? '-'),

                    'kelurahan' =>
                        $item->kelurahan
                        ?? ($keluarga->kelurahan ?? '-'),

                    'kode_pos' =>
                        $item->kode_pos
                        ?? ($keluarga->kode_pos ?? '-'),

                    'rt_rw' =>
                        $item->rt_rw
                        ?? ($keluarga->rt_rw ?? '-'),

                    'alamat_lengkap' =>
                        $item->alamat_lengkap
                        ?? ($keluarga->alamat_lengkap ?? '-'),

                    'jalan_rumah' =>
                        $item->jalan_rumah
                        ?? '-',

                    'is_alamat_sesuai' =>
                        $item->is_alamat_sesuai,


                    /*
                    |--------------------------------------------------------------------------
                    | GEOTAGGING
                    |--------------------------------------------------------------------------
                    */

                    'geotangging' =>
                        $item->geotangging ?? '-',


                    /*
                    |--------------------------------------------------------------------------
                    | PART
                    |--------------------------------------------------------------------------
                    */

                    'current_part' =>
                        (int) (
                            $item->current_part ?? 1
                        ),

                    'keluarga_periode_kode' =>
                        $item->keluarga_periode_kode,

                    'created_at' =>
                        $item->created_at
                            ? $item->created_at->format(
                                'd-m-Y H:i'
                            )
                            : '-',


                    /*
                    |--------------------------------------------------------------------------
                    | KUISIONER
                    |--------------------------------------------------------------------------
                    */

                    'kuisioner' =>
                        $this->buildQuestionnaire(
                            $item,
                            $keluarga
                        ),
                ];
            })
            ->values()
            ->all();
    }


    /*
    |--------------------------------------------------------------------------
    | MENENTUKAN STATUS VERIFIKASI
    |--------------------------------------------------------------------------
    */

    protected function determineVerificationStatus(
        KeluargaPart1 $item,
        ?Keluarga $keluarga
    ): string {

        $rawStatus = strtolower(
            trim(
                (string) $item->status
            )
        );


        /*
        |--------------------------------------------------------------------------
        | SUDAH DISETUJUI
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $rawStatus,
                [
                    'approved',
                    'disetujui',
                ],
                true
            )
        ) {
            return 'approved';
        }


        /*
        |--------------------------------------------------------------------------
        | SUDAH DITOLAK
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $rawStatus,
                [
                    'rejected',
                    'reject',
                    'ditolak',
                ],
                true
            )
        ) {
            return 'rejected';
        }


        /*
        |--------------------------------------------------------------------------
        | CEK PROGRESS
        |--------------------------------------------------------------------------
        */

        $progress = $this->getProgress(
            $item,
            $keluarga
        );


        /*
        |--------------------------------------------------------------------------
        | KUISIONER SELESAI
        |--------------------------------------------------------------------------
        
        |
        */

        if (
            $progress['completed'] === 5
        ) {
            return 'pending';
        }


        /*
        |--------------------------------------------------------------------------
        | BELUM SELESAI
        |--------------------------------------------------------------------------
        */

        return 'draft';
    }


    /*
    |--------------------------------------------------------------------------
    | PROGRESS PART 1 - 5
    |--------------------------------------------------------------------------
    */

    protected function getProgress(
        KeluargaPart1 $item,
        ?Keluarga $keluarga
    ): array {

        $kode =
            $item->keluarga_periode_kode;

        $currentPart =
            (int) (
                $item->current_part ?? 1
            );


        /*
        |--------------------------------------------------------------------------
        | PART 1
        |--------------------------------------------------------------------------
        */

        $part1 =
            $currentPart >= 2;


        /*
        |--------------------------------------------------------------------------
        | PART 2
        |--------------------------------------------------------------------------
        */

        $part2 = false;

        if ($kode) {

            $part2 =
                $currentPart >= 3
                &&
                KeluargaPart2::where(
                    'keluarga_periode_kode',
                    $kode
                )->exists();
        }


        /*
        |--------------------------------------------------------------------------
        | PART 3
        |--------------------------------------------------------------------------
        */

        $part3 = false;

        if ($kode) {

            $part3 =
                $currentPart >= 4
                &&
                KeluargaPart3::where(
                    'keluarga_periode_kode',
                    $kode
                )->exists();
        }


        /*
        |--------------------------------------------------------------------------
        | PART 4
        |--------------------------------------------------------------------------
        */

        $part4 = false;

        if ($kode) {

            $part4 =
                $currentPart >= 5
                &&
                KeluargaPart4::where(
                    'keluarga_periode_kode',
                    $kode
                )->exists();
        }


        /*
        |--------------------------------------------------------------------------
        | PART 5
        |--------------------------------------------------------------------------
        */

        $part5 =
            $this->isPart5Complete(
                $item,
                $keluarga
            );


        /*
        |--------------------------------------------------------------------------
        | DETAIL PART
        |--------------------------------------------------------------------------
        */

        $parts = [

            [
                'number' => 1,

                'title' =>
                    'Data Keluarga',

                'completed' =>
                    $part1,

                'status' =>
                    $part1
                        ? 'Selesai'
                        : 'Belum Selesai',
            ],

            [
                'number' => 2,

                'title' =>
                    'Kondisi Rumah',

                'completed' =>
                    $part2,

                'status' =>
                    $part2
                        ? 'Selesai'
                        : 'Belum Selesai',
            ],

            [
                'number' => 3,

                'title' =>
                    'Pengeluaran & Pendapatan',

                'completed' =>
                    $part3,

                'status' =>
                    $part3
                        ? 'Selesai'
                        : 'Belum Selesai',
            ],

            [
                'number' => 4,

                'title' =>
                    'Aset Keluarga',

                'completed' =>
                    $part4,

                'status' =>
                    $part4
                        ? 'Selesai'
                        : 'Belum Selesai',
            ],

            [
                'number' => 5,

                'title' =>
                    'Data Anggota Keluarga',

                'completed' =>
                    $part5,

                'status' =>
                    $part5
                        ? 'Selesai'
                        : 'Belum Selesai',
            ],
        ];


        /*
        |--------------------------------------------------------------------------
        | HITUNG PROGRESS
        |--------------------------------------------------------------------------
        */

        $completed = collect($parts)
            ->where(
                'completed',
                true
            )
            ->count();

        $total = 5;

        $percent =
            $total > 0
                ? (int) round(
                    (
                        $completed
                        / $total
                    ) * 100
                )
                : 0;


        return [

            'completed' =>
                $completed,

            'total' =>
                $total,

            'percent' =>
                $percent,

            'parts' =>
                $parts,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | CEK PART 5
    |--------------------------------------------------------------------------
    */

    protected function isPart5Complete(
        KeluargaPart1 $item,
        ?Keluarga $keluarga
    ): bool {

        /*
        |--------------------------------------------------------------------------
        | CURRENT PART MINIMAL 5
        |--------------------------------------------------------------------------
        */

        if (
            (int) (
                $item->current_part ?? 0
            ) < 5
        ) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | DATA KELUARGA WAJIB ADA
        |--------------------------------------------------------------------------
        */

        $kode =
            $item->keluarga_periode_kode;

        if (
            !$kode
            ||
            !$keluarga
        ) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL SEMUA KODE ANGGOTA
        |--------------------------------------------------------------------------
        */

        $memberCodes = $keluarga
            ->anggota()
            ->pluck('kode')
            ->filter()
            ->map(
                fn ($value) =>
                    (string) $value
            )
            ->values();


        if (
            $memberCodes->isEmpty()
        ) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL SEMUA DATA PART 5
        |--------------------------------------------------------------------------
        */

        $part5Codes =
            KeluargaPart5::where(
                'keluarga_periode_kode',
                $kode
            )
            ->pluck(
                'keluarga_anggota_kode'
            )
            ->filter()
            ->map(
                fn ($value) =>
                    (string) $value
            )
            ->unique()
            ->values();


        /*
        |--------------------------------------------------------------------------
        | SETIAP ANGGOTA HARUS MEMILIKI PART 5
        |--------------------------------------------------------------------------
        */

        return $memberCodes
            ->diff($part5Codes)
            ->isEmpty();
    }


    /*
    |--------------------------------------------------------------------------
    | LABEL STATUS
    |--------------------------------------------------------------------------
    */

    protected function getStatusLabel(
        string $status
    ): string {

        return match ($status) {

            'approved' =>
                'Disetujui',

            'rejected' =>
                'Ditolak',

            'pending' =>
                'Menunggu Verifikasi',

            default =>
                'Draft',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | WILAYAH
    |--------------------------------------------------------------------------
    */

    protected function buildWilayah(
        KeluargaPart1 $item,
        ?Keluarga $keluarga
    ): string {

        $kecamatan =
            $item->kecamatan
            ?? (
                $keluarga->kecamatan
                ?? ''
            );

        $kelurahan =
            $item->kelurahan
            ?? (
                $keluarga->kelurahan
                ?? ''
            );

        return collect([
            $kecamatan,
            $kelurahan,
        ])
            ->filter(
                fn ($value) =>
                    trim(
                        (string) $value
                    ) !== ''
            )
            ->map(
                fn ($value) =>
                    trim(
                        (string) $value
                    )
            )
            ->unique()
            ->values()
            ->implode(' / ')
            ?: '-';
    }


    /*
    |--------------------------------------------------------------------------
    | BUILD KUISIONER
    |--------------------------------------------------------------------------
    */

    protected function buildQuestionnaire(
        KeluargaPart1 $item,
        ?Keluarga $keluarga
    ): array {

        $kode =
            $item->keluarga_periode_kode;


        /*
        |--------------------------------------------------------------------------
        | PART 1 - DATA KELUARGA
        |--------------------------------------------------------------------------
        */

        $part1Questions = [

            $this->question(
                'Nomor KK',
                $item->no_kk
                    ?? (
                        $keluarga->no_kk
                        ?? '-'
                    )
            ),

            $this->question(
                'NIK',
                $item->nik
                    ?? (
                        $keluarga->nik
                        ?? '-'
                    )
            ),

            $this->question(
                'Jumlah Anggota Keluarga',
                $item->jml_keluarga
                    ?? (
                        $keluarga
                            ? $keluarga
                                ->anggota()
                                ->count()
                            : '-'
                    )
            ),

            $this->question(
                'Provinsi',
                $item->provinsi
            ),

            $this->question(
                'Daerah',
                $item->daerah
            ),

            $this->question(
                'Kecamatan',
                $item->kecamatan
            ),

            $this->question(
                'Kelurahan',
                $item->kelurahan
            ),

            $this->question(
                'Kode Pos',
                $item->kode_pos
            ),

            $this->question(
                'RT / RW',
                $item->rt_rw
            ),

            $this->question(
                'Alamat Lengkap',
                $item->alamat_lengkap
            ),

            $this->question(
                'Jalan / Rumah',
                $item->jalan_rumah
            ),

            $this->question(
                'Alamat Sesuai',
                $this->formatBoolean(
                    $item->is_alamat_sesuai
                )
            ),


            /*
            |--------------------------------------------------------------------------
            | GEOTAGGING
            |--------------------------------------------------------------------------
            */

            [
                'number' => 0,

                'text' =>
                    'Titik Lokasi (Geotagging)',

                'question' =>
                    'Titik Lokasi (Geotagging)',

                'answer' =>
                    $item->geotangging
                    ?? '-',

                'type' =>
                    'map',
            ],
        ];


        /*
        |--------------------------------------------------------------------------
        | PART 2 - KONDISI RUMAH
        |--------------------------------------------------------------------------
        */

        $part2Questions = [];

        $part2 = null;

        if ($kode) {

            $part2 =
                KeluargaPart2::where(
                    'keluarga_periode_kode',
                    $kode
                )->first();
        }

        if ($part2) {

            $part2Questions = [

                $this->question(
                    'Jenis Bangunan',
                    $part2->jenis_bangungan
                ),

                $this->question(
                    'Ada Keluarga Lain dalam Bangunan',
                    $this->formatBoolean(
                        $part2->is_keluarga_lain
                    )
                ),

                $this->question(
                    'Jumlah Keluarga Lain',
                    $part2->jml_keluarga_lain
                ),

                $this->question(
                    'Total Penghuni',
                    $part2->total_penghuni
                ),

                $this->question(
                    'Kepemilikan Bangunan',
                    $part2->kepemilikan_bangunan
                ),

                $this->question(
                    'Bukti Kepemilikan',
                    $part2->bukti_kepemilikan
                ),

                $this->question(
                    'Harga Sewa / Kontrak',
                    $part2->harga_sewa_kontrak
                ),

                $this->question(
                    'Luas Lantai',
                    $part2->luas_lantai
                ),

                $this->question(
                    'Jenis Lantai',
                    $part2->jenis_lantai
                ),

                $this->question(
                    'Kondisi Lantai',
                    $part2->kondisi_lantai
                ),

                $this->question(
                    'Jenis Dinding',
                    $part2->jenis_dinding
                ),

                $this->question(
                    'Kondisi Dinding',
                    $part2->kondisi_dinding
                ),

                $this->question(
                    'Jenis Atap',
                    $part2->jenis_atap
                ),

                $this->question(
                    'Kondisi Atap',
                    $part2->kondisi_atap
                ),

                $this->question(
                    'Fasilitas BAB',
                    $part2->fasilitas_bab
                ),

                $this->question(
                    'Jenis Kloset',
                    $part2->jenis_kloset
                ),

                $this->question(
                    'Sumber Air Minum',
                    $part2->sumber_minum
                ),

                $this->question(
                    'Sumber Penerangan',
                    $part2->sumber_penerangan
                ),

                $this->question(
                    'Daya Listrik',
                    $part2->daya_listrik
                ),

                $this->question(
                    'IDPEL PLN',
                    $part2->idpel_pln
                ),

                $this->question(
                    'Jumlah Meteran',
                    $part2->jml_meteran
                ),
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | PART 3 - PENGELUARAN & PENDAPATAN
        |--------------------------------------------------------------------------
        */

        $part3Questions = [];

        $part3 = null;

        if ($kode) {

            $part3 =
                KeluargaPart3::where(
                    'keluarga_periode_kode',
                    $kode
                )->first();
        }

        if ($part3) {

            $part3Questions = [

                $this->question(
                    'Pengeluaran Listrik Bulanan',
                    $part3->pengeluaran_listrik_bulanan
                ),

                $this->question(
                    'Pengeluaran Pulsa Bulanan',
                    $part3->pengeluaran_pulsa_bulanan
                ),

                $this->question(
                    'Pengeluaran Internet Bulanan',
                    $part3->pengeluaran_internet_bulanan
                ),

                $this->question(
                    'Pengeluaran Makan Mingguan',
                    $part3->pengeluaran_makan_mingguan
                ),

                $this->question(
                    'Pengeluaran Nonmakan Bulanan',
                    $part3->pengeluaran_nonmakan_bulanan
                ),

                $this->question(
                    'Pengeluaran Nonmakan Tahunan',
                    $part3->pengeluaran_nonmakan_tahunan
                ),

                $this->question(
                    'Total Pendapatan Kerja',
                    $part3->total_pendapatan_kerja
                ),

                $this->question(
                    'Total Pendapatan Usaha',
                    $part3->total_pendapatan_usaha
                ),

                $this->question(
                    'Total Pendapatan Lainnya',
                    $part3->total_pendapatan_lainnya
                ),
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | PART 4 - ASET KELUARGA
        |--------------------------------------------------------------------------
        */

        $part4Questions = [];

        if ($kode) {

            $part4 =
                KeluargaPart4::where(
                    'keluarga_periode_kode',
                    $kode
                )
                ->orderBy('id')
                ->get();

            foreach ($part4 as $asset) {

                $namaAset =
                    $asset->aset_keluarga
                    ?? 'Aset Keluarga';

                $part4Questions[] =
                    $this->question(
                        $namaAset . ' - Memiliki',
                        $this->formatBoolean(
                            $asset->is_punya_aset
                        )
                    );

                $part4Questions[] =
                    $this->question(
                        $namaAset . ' - Jumlah',
                        $asset->jml_aset
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | PART 5 - DATA ANGGOTA KELUARGA
        |--------------------------------------------------------------------------
        */

        $part5Questions = [];

        if (
            $kode
            &&
            $keluarga
        ) {

            $members =
                $keluarga
                    ->anggota()
                    ->orderBy('id')
                    ->get();

            $part5Data =
                KeluargaPart5::where(
                    'keluarga_periode_kode',
                    $kode
                )
                ->get()
                ->keyBy(
                    'keluarga_anggota_kode'
                );


            foreach ($members as $member) {

                $memberCode =
                    (string) (
                        $member->kode
                        ?? ''
                    );

                $part5 =
                    $part5Data->get(
                        $memberCode
                    );

                $namaMember =
                    $member->nama_lengkap
                    ?? '-';

                $prefix =
                    'Anggota: '
                    . $namaMember;


                /*
                |--------------------------------------------------------------------------
                | NIK
                |--------------------------------------------------------------------------
                */

                $part5Questions[] =
                    $this->question(
                        $prefix
                        . ' — NIK',
                        $member->nik
                    );


                /*
                |--------------------------------------------------------------------------
                | HUBUNGAN KELUARGA
                |--------------------------------------------------------------------------
                */

                $part5Questions[] =
                    $this->question(
                        $prefix
                        . ' — Hubungan Keluarga',
                        $member->status_keluarga
                    );


                if ($part5) {

                    $part5Questions[] =
                        $this->question(
                            $prefix
                            . ' — Keberadaan',
                            $part5->keberadaan
                        );

                    $part5Questions[] =
                        $this->question(
                            $prefix
                            . ' — No. HP',
                            $part5->no_hp
                        );

                    $part5Questions[] =
                        $this->question(
                            $prefix
                            . ' — Jenis Kelamin',
                            $part5->jenis_kelamin
                        );

                    $part5Questions[] =
                        $this->question(
                            $prefix
                            . ' — Tanggal Lahir',
                            $part5->tanggal_lahir
                        );

                    $part5Questions[] =
                        $this->question(
                            $prefix
                            . ' — Status Perkawinan',
                            $part5->status_perkawinan
                        );

                    $part5Questions[] =
                        $this->question(
                            $prefix
                            . ' — Status Sekolah',
                            $part5->status_sekolah
                        );

                    $part5Questions[] =
                        $this->question(
                            $prefix
                            . ' — Ijazah Tertinggi',
                            $part5->ijazah_tertinggi
                        );

                    $part5Questions[] =
                        $this->question(
                            $prefix
                            . ' — Pekerjaan Utama',
                            $part5->pekerjaan_utama
                        );

                    $part5Questions[] =
                        $this->question(
                            $prefix
                            . ' — Status Pekerjaan',
                            $part5->status_pekerjaan
                        );

                    $part5Questions[] =
                        $this->question(
                            $prefix
                            . ' — Kepemilikan Rekening',
                            $this->formatBoolean(
                                $part5->kepemilikan_rekening
                            )
                        );

                    $part5Questions[] =
                        $this->question(
                            $prefix
                            . ' — Disabilitas Fisik',
                            $this->formatBoolean(
                                $part5->is_disabilitas_fisik
                            )
                        );

                    $part5Questions[] =
                        $this->question(
                            $prefix
                            . ' — Disabilitas Mental',
                            $this->formatBoolean(
                                $part5->is_disabilitas_mental
                            )
                        );

                    $part5Questions[] =
                        $this->question(
                            $prefix
                            . ' — Disabilitas Intelektual',
                            $this->formatBoolean(
                                $part5->is_disabilitas_intelektual
                            )
                        );

                    $part5Questions[] =
                        $this->question(
                            $prefix
                            . ' — Disabilitas Netra',
                            $this->formatBoolean(
                                $part5->is_disabilitas_netra
                            )
                        );

                    $part5Questions[] =
                        $this->question(
                            $prefix
                            . ' — Disabilitas Rungu',
                            $this->formatBoolean(
                                $part5->is_disabilitas_rungu
                            )
                        );

                    $part5Questions[] =
                        $this->question(
                            $prefix
                            . ' — Disabilitas Wicara',
                            $this->formatBoolean(
                                $part5->is_disabilitas_wicara
                            )
                        );

                    $part5Questions[] =
                        $this->question(
                            $prefix
                            . ' — Keluhan Kesehatan',
                            $part5->keluhan_kesehatan
                        );

                } else {

                    $part5Questions[] =
                        $this->question(
                            $prefix
                            . ' — Data Part 5',
                            'Belum diisi'
                        );
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | FOTO RUMAH
        |--------------------------------------------------------------------------
        */

        if ($kode) {

            $fotoRumah =
                KeluargaFotoRumah::where(
                    'keluarga_periode_kode',
                    $kode
                )
                ->get()
                ->keyBy(
                    'jenis_foto'
                );


            /*
            |--------------------------------------------------------------------------
            | FOTO TAMPAK DEPAN
            |--------------------------------------------------------------------------
            */

            $fotoTampakDepan =
                $fotoRumah->get(
                    'tampak_depan'
                );

            $part5Questions[] = [

                'number' => 0,

                'question' =>
                    'Foto Tampak Depan Rumah',

                'text' =>
                    'Foto Tampak Depan Rumah',

                'answer' =>
                    $fotoTampakDepan
                        ? Storage::disk('public')->url(
                            ltrim(
                                preg_replace(
                                    '#^(public/|storage/)#i',
                                    '',
                                    str_replace(
                                        '\\',
                                        '/',
                                        $fotoTampakDepan->path_file
                                    )
                                ),
                                '/'
                            )
                        )
                        : '',

                'type' =>
                    'image',
            ];


            /*
            |--------------------------------------------------------------------------
            | FOTO RUANG TAMU
            |--------------------------------------------------------------------------
            */

            $fotoRuangTamu =
                $fotoRumah->get(
                    'ruang_tamu'
                );

            $part5Questions[] = [

                'number' => 0,

                'question' =>
                    'Foto Ruang Tamu',

                'text' =>
                    'Foto Ruang Tamu',

                'answer' =>
                    $fotoRuangTamu
                        ? Storage::disk('public')->url(
                            ltrim(
                                preg_replace(
                                    '#^(public/|storage/)#i',
                                    '',
                                    str_replace(
                                        '\\',
                                        '/',
                                        $fotoRuangTamu->path_file
                                    )
                                ),
                                '/'
                            )
                        )
                        : '',

                'type' =>
                    'image',
            ];


            /*
            |--------------------------------------------------------------------------
            | FOTO KAMAR MANDI
            |--------------------------------------------------------------------------
            */

            $fotoKamarMandi =
                $fotoRumah->get(
                    'kamar_mandi'
                );

            $part5Questions[] = [

                'number' => 0,

                'question' =>
                    'Foto Kamar Mandi',

                'text' =>
                    'Foto Kamar Mandi',

                'answer' =>
                    $fotoKamarMandi
                        ? Storage::disk('public')->url(
                            ltrim(
                                preg_replace(
                                    '#^(public/|storage/)#i',
                                    '',
                                    str_replace(
                                        '\\',
                                        '/',
                                        $fotoKamarMandi->path_file
                                    )
                                ),
                                '/'
                            )
                        )
                        : '',

                'type' =>
                    'image',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | RETURN 5 PART
        |--------------------------------------------------------------------------
        */

        return [

            [
                'part' => 1,

                'title' =>
                    'Data Keluarga',

                'questions' =>
                    $part1Questions,
            ],

            [
                'part' => 2,

                'title' =>
                    'Kondisi Rumah',

                'questions' =>
                    $part2Questions,
            ],

            [
                'part' => 3,

                'title' =>
                    'Pengeluaran & Pendapatan',

                'questions' =>
                    $part3Questions,
            ],

            [
                'part' => 4,

                'title' =>
                    'Aset Keluarga',

                'questions' =>
                    $part4Questions,
            ],

            [
                'part' => 5,

                'title' =>
                    'Data Anggota Keluarga',

                'questions' =>
                    $part5Questions,
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | QUESTION HELPER
    |--------------------------------------------------------------------------
    */

    protected function question(
        string $question,
        $answer
    ): array {

        return [

            'number' =>
                0,

            'text' =>
                $question,

            'question' =>
                $question,

            'answer' =>
                $this->formatAnswer(
                    $answer
                ),

            'type' =>
                'text',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT ANSWER
    |--------------------------------------------------------------------------
    */

    protected function formatAnswer(
        $value
    ): string {

        if (
            $value === null
            ||
            $value === ''
        ) {
            return '-';
        }

        if (is_bool($value)) {

            return $value
                ? 'Ya'
                : 'Tidak';
        }

        if (is_array($value)) {

            return implode(
                ', ',
                array_map(
                    fn ($item) =>
                        $this->formatAnswer(
                            $item
                        ),
                    $value
                )
            );
        }

        if (is_object($value)) {

            if (
                isset($value->label)
            ) {
                return (string)
                    $value->label;
            }

            if (
                isset($value->nama)
            ) {
                return (string)
                    $value->nama;
            }

            if (
                isset($value->value)
            ) {
                return (string)
                    $value->value;
            }

            return json_encode(
                $value,
                JSON_UNESCAPED_UNICODE
            );
        }

        return (string) $value;
    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT BOOLEAN
    |--------------------------------------------------------------------------
    */

    protected function formatBoolean(
        $value
    ): string {

        if (
            $value === null
            ||
            $value === ''
        ) {
            return '-';
        }

        if (
            $value === true
            ||
            $value === 1
            ||
            $value === '1' ||
            $value === 'true' ||
            strtolower(
                (string) $value
            ) === 'ya'
        ) {
            return 'Ya';
        }

        if (
            $value === false
            ||
            $value === 0 ||
            $value === '0' ||
            $value === 'false' ||
            strtolower(
                (string) $value
            ) === 'tidak'
        ) {
            return 'Tidak';
        }

        return (string) $value;
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $data = collect(
            $this->getData()
        )->firstWhere(
            'id',
            (int) $id
        );

        if (!$data) {

            return redirect()
                ->route(
                    'verifikasi.index'
                )
                ->with(
                    'error',
                    'Data verifikasi tidak ditemukan.'
                );
        }

        return view(
            'admin.verifikasi.show',
            compact('data')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS VERIFIKASI
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {

        $validated =
            $request->validate([
                'status' => [
                    'required',
                    'in:approved,rejected',
                ],
            ]);


        /*
        |--------------------------------------------------------------------------
        | CARI PART 1
        |--------------------------------------------------------------------------
        */

        $item =
            KeluargaPart1::find($id);

        if (!$item) {

            return redirect()
                ->route(
                    'verifikasi.index'
                )
                ->with(
                    'error',
                    'Data verifikasi tidak ditemukan.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CARI KELUARGA
        |--------------------------------------------------------------------------
        */

        $keluarga = null;

        if (
            $item->keluarga_periode_kode
        ) {

            $keluarga =
                Keluarga::where(
                    'kode',
                    $item->keluarga_periode_kode
                )->first();
        }


        if (
            !$keluarga
            &&
            $item->no_kk
        ) {

            $keluarga =
                Keluarga::where(
                    'no_kk',
                    $item->no_kk
                )->first();
        }


        /*
        |--------------------------------------------------------------------------
        | CEK STATUS SEBENARNYA
        |--------------------------------------------------------------------------
        */

        $actualStatus =
            $this->determineVerificationStatus(
                $item,
                $keluarga
            );


        /*
        |--------------------------------------------------------------------------
        | DRAFT TIDAK BOLEH DIVERIFIKASI
        |--------------------------------------------------------------------------
        */

        if (
            $actualStatus === 'draft'
        ) {

            return redirect()
                ->route(
                    'verifikasi.index'
                )
                ->with(
                    'error',
                    'Data belum dapat diverifikasi karena kuisioner belum selesai sampai Part 5.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | APPROVED TIDAK BOLEH DIVERIFIKASI ULANG
        |--------------------------------------------------------------------------
        */

        if (
            $actualStatus === 'approved'
        ) {

            return redirect()
                ->route(
                    'verifikasi.index'
                )
                ->with(
                    'warning',
                    'Data sudah berstatus Disetujui dan tidak dapat diverifikasi ulang.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | REJECTED TIDAK BOLEH DIVERIFIKASI ULANG
        |--------------------------------------------------------------------------
        */

        if (
            $actualStatus === 'rejected'
        ) {

            return redirect()
                ->route(
                    'verifikasi.index'
                )
                ->with(
                    'warning',
                    'Data sudah berstatus Ditolak dan tidak dapat diverifikasi ulang.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | HARUS PENDING
        |--------------------------------------------------------------------------
        */

        if (
            $actualStatus !== 'pending'
        ) {

            return redirect()
                ->route(
                    'verifikasi.index'
                )
                ->with(
                    'error',
                    'Data belum dapat diproses untuk verifikasi.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN STATUS
        |--------------------------------------------------------------------------
        */

        $item->status =
            $validated['status'];

        $item->updated_by =
            auth()->user()->name
            ??
            auth()->user()->username
            ??
            auth()->id()
            ??
            'admin';

        $item->save();


        /*
        |--------------------------------------------------------------------------
        | PESAN
        |--------------------------------------------------------------------------
        */

        $message =
            $validated['status']
                === 'approved'
                    ? 'Data berhasil disetujui.'
                    : 'Data berhasil ditolak.';


        return redirect()
            ->route(
                'verifikasi.index'
            )
            ->with(
                'success',
                $message
            );
    }
}

