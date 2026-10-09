<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\KeluargaAnggota;
use App\Models\KeluargaFotoRumah;
use App\Models\KeluargaPart1;
use App\Models\KeluargaPart2;
use App\Models\KeluargaPart3;
use App\Models\KeluargaPart4;
use App\Models\KeluargaPart5;
use App\Models\Periode;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VerifikasiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $filters = $this->validateTableFilters(
            $request,
            ['pending', 'approved', 'rejected']
        );

        /*
        |--------------------------------------------------------------------------
        | AMBIL SEMUA DATA
        |--------------------------------------------------------------------------
        */

        $allData = collect($this->getData());

        /*
        |--------------------------------------------------------------------------
        | STATISTIK CARD
        |--------------------------------------------------------------------------
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
        | DATA SESUAI FILTER
        |--------------------------------------------------------------------------
        */

        $filteredQuery = $this->buildFilteredPart1Query(
            $filters,
            ['pending', 'approved', 'rejected']
        );

        $filteredData = collect(
            $this->getData($filteredQuery)
        );

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $perPage = 5;

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $currentItems = $filteredData
            ->slice(
                ($currentPage - 1) * $perPage,
                $perPage
            )
            ->values();

        $data = new LengthAwarePaginator(
            $currentItems,
            $filteredData->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | FILTER WILAYAH
        |--------------------------------------------------------------------------
        */

        $kecamatanList = $this->getKecamatanOptions();

        $kelurahanList = $this->getKelurahanOptions(
            $filters['kecamatan']
        );

        /*
        |--------------------------------------------------------------------------
        | PERIODE AKTIF
        |--------------------------------------------------------------------------
        */

        $periodeAktif = Periode::query()
            ->where('status_periode', 'Aktif')
            ->orderByDesc('id')
            ->first([
                'nama',
                'kode',
            ]);

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.verifikasi.index',
            [
                'data' => $data,

                /*
                | CARD STATISTIK
                */

                'totalResponden' => $totalResponden,
                'draftCount' => $draftCount,
                'pendingCount' => $pendingCount,
                'approvedCount' => $approvedCount,
                'rejectedCount' => $rejectedCount,

                /*
                | KOMPATIBILITAS BLADE LAMA
                */

                'total' => $totalResponden,
                'draft' => $draftCount,
                'pending' => $pendingCount,
                'approved' => $approvedCount,
                'rejected' => $rejectedCount,

                /*
                | FILTER
                */

                'filters' => $filters,

                'kecamatanList' => $kecamatanList,

                'kelurahanList' => $kelurahanList,

                'periodeAktif' => $periodeAktif,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DATA VERIFIKASI
    |--------------------------------------------------------------------------
    */

    protected function getData(?Builder $query = null)
    {
        $items = ($query ?? KeluargaPart1::query())
            ->where(function ($query) {
                $query
                    ->whereIn(
                        'keluarga_periode_kode',
                        Keluarga::query()->select('kode')
                    )
                    ->orWhereIn(
                        'no_kk',
                        Keluarga::query()
                            ->whereNotNull('no_kk')
                            ->select('no_kk')
                    );
            })
            ->orderByDesc('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | DATA PERIODE
        |--------------------------------------------------------------------------
        */

        $periodRecords = Periode::query()
            ->orderBy('tgl_awal')
            ->get([
                'kode',
                'nama',
                'tgl_awal',
                'tgl_akhir',
            ]);

        /*
        |--------------------------------------------------------------------------
        | MAP DATA
        |--------------------------------------------------------------------------
        */

        return $items
            ->map(function ($item) use ($periodRecords) {

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
                | FALLBACK NO KK
                |--------------------------------------------------------------------------
                */

                if (
                    ! $keluarga &&
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
|
|
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
| DETAIL ANGGOTA KELUARGA
|--------------------------------------------------------------------------
*/

$anggotaDetail = $anggota
    ->map(function ($member) {

        return [
            'kode' => $member->kode ?? null,

            'nik' => $member->nik
                ?? $member->NIK
                ?? '-',

            'nama_lengkap' => $member->nama_lengkap
                ?? $member->nama
                ?? $member->nama_anggota
                ?? '-',

            'nama' => $member->nama_lengkap
                ?? $member->nama
                ?? $member->nama_anggota
                ?? '-',

            'status_keluarga' => $member->status_keluarga
                ?? $member->status
                ?? $member->hubungan
                ?? '-',
        ];
    })
    ->filter(function ($member) {

        return
            ($member['nik'] ?? '-') !== '-'
            ||
            ($member['nama_lengkap'] ?? '-') !== '-';
    })
    ->values()
    ->all();

$jumlahAnggota = count($anggotaDetail);

if (
    $jumlahAnggota === 0
    &&
    $item->jml_keluarga !== null
    &&
    $item->jml_keluarga !== ''
) {
    $jumlahAnggota = (int) $item->jml_keluarga;
}

                /*
                |--------------------------------------------------------------------------
                | STATUS VERIFIKASI
                |--------------------------------------------------------------------------
                */

                $status =
                    $this->determineVerificationStatus(
                        $item,
                        $keluarga
                    );

                /*
                |--------------------------------------------------------------------------
                | PROGRESS KUISIONER
                |--------------------------------------------------------------------------
                */

                $progress =
                    $this->getProgress(
                        $item,
                        $keluarga
                    );

                /*
                |--------------------------------------------------------------------------
                | PERIODE
                |--------------------------------------------------------------------------
                */

                $createdAt = $item->created_at;

                $periode = $createdAt
                    ? $periodRecords->first(
                        function (
                            Periode $candidate
                        ) use ($createdAt): bool {

                            if (
                                ! $candidate->tgl_awal ||
                                ! $candidate->tgl_akhir
                            ) {
                                return false;
                            }

                            return $createdAt->betweenIncluded(
                                Carbon::parse(
                                    $candidate->tgl_awal
                                )->startOfDay(),

                                Carbon::parse(
                                    $candidate->tgl_akhir
                                )->endOfDay()
                            );
                        }
                    )
                    : null;

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
                | RETURN DATA
                |--------------------------------------------------------------------------
                */

                return [

                    'id' => $item->id,

                    /*
                    | IDENTITAS
                    */

                    'no_kk' => $item->no_kk
                        ?? (
                            $keluarga->no_kk ?? '-'
                        ),

                    'nik' => $item->nik
                        ?? (
                            $keluarga->nik ?? '-'
                        ),

                    'nama' => $keluarga->nama_lengkap
                        ?? $item->nama_lengkap
                        ?? $item->nama_kepala_keluarga
                        ?? '-',

                    /*
                    | JUMLAH ANGGOTA
                    */

                    'jumlah_anggota' =>
                        $jumlahAnggota,

                    'anggota' =>
                        $jumlahAnggota,

                    'anggota_detail' =>
                        $anggotaDetail,

                    /*
                    | PERIODE
                    */

                    'periode' => $periode
                        ? (string) (
                            $periode->nama
                            ?: $periode->kode
                        )
                        : '-',

                    'periode_kode' =>
                        $periode?->kode ?? '-',

                    'periode_tanggal' => $periode
                        ? Carbon::parse(
                            $periode->tgl_awal
                        )->format('d-m-Y')
                        . ' - '
                        . Carbon::parse(
                            $periode->tgl_akhir
                        )->format('d-m-Y')
                        : '-',

                    /*
                    | STATUS
                    */

                    'status' => $status,

                    'status_label' =>
                        $this->getStatusLabel(
                            $status
                        ),

                    /*
                    | PROGRESS
                    */

                    'progress' => $progress,

                    'progress_completed' =>
                        $progress['completed'],

                    'progress_total' =>
                        $progress['total'],

                    'progress_percent' =>
                        $progress['percent'],

                    'progress_detail' =>
                        $progress['parts'],

                    /*
                    | WILAYAH
                    */

                    'wilayah' =>
                        $this->buildWilayah(
                            $item,
                            $keluarga
                        ),

                    /*
                    | PETUGAS
                    */

                    'petugas' =>
                        $item->updated_by
                        ?? $item->created_by
                        ?? '-',

                    'tanggal' => $tanggal
                        ? $tanggal->format(
                            'd-m-Y H:i'
                        )
                        : '-',

                    /*
                    | ALAMAT
                    */

                    'provinsi' =>
                        $item->provinsi
                        ?? (
                            $keluarga->provinsi ?? '-'
                        ),

                    'daerah' =>
                        $item->daerah
                        ?? (
                            $keluarga->daerah ?? '-'
                        ),

                    'kecamatan' =>
                        $item->kecamatan
                        ?? (
                            $keluarga->kecamatan ?? '-'
                        ),

                    'kelurahan' =>
                        $item->kelurahan
                        ?? (
                            $keluarga->kelurahan ?? '-'
                        ),

                    'kode_pos' =>
                        $item->kode_pos
                        ?? (
                            $keluarga->kode_pos ?? '-'
                        ),

                    'rt_rw' =>
                        $item->rt_rw
                        ?? (
                            $keluarga->rt_rw ?? '-'
                        ),

                    'alamat_lengkap' =>
                        $item->alamat_lengkap
                        ?? (
                            $keluarga->alamat_lengkap
                            ?? '-'
                        ),

                    'jalan_rumah' =>
                        $item->jalan_rumah
                        ?? '-',

                    'is_alamat_sesuai' =>
                        $item->is_alamat_sesuai,

                    /*
                    | GEOTAGGING
                    */

                    'geotangging' =>
                        $item->geotangging
                        ?? '-',

                    /*
                    | PART
                    */

                    'current_part' =>
                        (int) (
                            $item->current_part
                            ?? 1
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
                    | KUISIONER
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
    | VALIDATE FILTER
    |--------------------------------------------------------------------------
    */

    protected function validateTableFilters(
        Request $request,
        array $allowedStatuses
    ): array {
        $validated = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:100',
            ],

            'kecamatan' => [
                'nullable',
                'string',
                Rule::exists(
                    'kecamatans',
                    'kecamatan_id'
                ),
            ],

            'kelurahan' => [
                'nullable',
                'string',
                Rule::exists(
                    'kelurahans',
                    'kelurahan_id'
                )->where(
                    'kecamatan_id',
                    $request->input('kecamatan')
                ),
            ],

            'status' => [
                'nullable',
                'string',
                Rule::in(
                    array_merge(
                        ['all'],
                        $allowedStatuses
                    )
                ),
            ],
        ]);

        return [
            'search' => trim(
                (string) (
                    $validated['search'] ?? ''
                )
            ),

            'kecamatan' => (string) (
                $validated['kecamatan'] ?? ''
            ),

            'kelurahan' => (string) (
                $validated['kelurahan'] ?? ''
            ),

            'status' => (string) (
                $validated['status'] ?? 'all'
            ) ?: 'all',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | KECAMATAN
    |--------------------------------------------------------------------------
    */

    protected function getKecamatanOptions(): array
    {
        return DB::table('kecamatans')
            ->orderBy('deskripsi')
            ->get([
                'kecamatan_id',
                'deskripsi',
            ])
            ->map(
                fn (object $row): array => [
                    'id' => (string)
                        $row->kecamatan_id,

                    'name' => (string)
                        $row->deskripsi,
                ]
            )
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | KELURAHAN
    |--------------------------------------------------------------------------
    */

    protected function getKelurahanOptions(
        string $kecamatanId
    ): array {
        if ($kecamatanId === '') {
            return [];
        }

        return DB::table('kelurahans')
            ->where(
                'kecamatan_id',
                $kecamatanId
            )
            ->orderBy('deskripsi')
            ->get([
                'kelurahan_id',
                'deskripsi',
            ])
            ->map(
                fn (object $row): array => [
                    'id' => (string)
                        $row->kelurahan_id,

                    'name' => (string)
                        $row->deskripsi,
                ]
            )
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY FILTER
    |--------------------------------------------------------------------------
    */

    protected function buildFilteredPart1Query(
        array $filters,
        array $allowedStatuses
    ): Builder {
        $query = KeluargaPart1::query();

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($filters['search'] !== '') {

            $search =
                '%' . $filters['search'] . '%';

            $query->where(
                function (
                    Builder $query
                ) use ($search): void {

                    $query
                        ->where(
                            'part1_keluarga.no_kk',
                            'like',
                            $search
                        )

                        ->orWhere(
                            'part1_keluarga.nik',
                            'like',
                            $search
                        )

                        ->orWhere(
                            'part1_keluarga.nama_kepala_keluarga',
                            'like',
                            $search
                        )

                        ->orWhereExists(
                            function (
                                QueryBuilder $familyQuery
                            ) use ($search): void {

                                $familyQuery
                                    ->selectRaw('1')
                                    ->from(
                                        'keluargas as search_family'
                                    )

                                    ->where(
                                        function (
                                            QueryBuilder $familyMatch
                                        ): void {

                                            $familyMatch
                                                ->whereColumn(
                                                    'search_family.kode',
                                                    'part1_keluarga.keluarga_periode_kode'
                                                )

                                                ->orWhereColumn(
                                                    'search_family.no_kk',
                                                    'part1_keluarga.no_kk'
                                                );
                                        }
                                    )

                                    ->where(
                                        'search_family.nama_lengkap',
                                        'like',
                                        $search
                                    );
                            }
                        );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | WILAYAH
        |--------------------------------------------------------------------------
        */

        if ($filters['kecamatan'] !== '') {

            $kecamatanNama =
                (string) DB::table(
                    'kecamatans'
                )
                    ->where(
                        'kecamatan_id',
                        $filters['kecamatan']
                    )
                    ->value('deskripsi');

            $kelurahanIdsInKecamatan =
                DB::table(
                    'kelurahans'
                )
                    ->where(
                        'kecamatan_id',
                        $filters['kecamatan']
                    )
                    ->select('kelurahan_id');

            $query->where(
                function (
                    Builder $query
                ) use (
                    $filters,
                    $kecamatanNama,
                    $kelurahanIdsInKecamatan
                ): void {

                    $query
                        ->whereExists(
                            function (
                                QueryBuilder $familyQuery
                            ) use (
                                $filters,
                                $kelurahanIdsInKecamatan
                            ): void {

                                $familyQuery
                                    ->selectRaw('1')
                                    ->from(
                                        'keluargas as region_family'
                                    )

                                    ->where(
                                        function (
                                            QueryBuilder $familyMatch
                                        ): void {

                                            $familyMatch
                                                ->whereColumn(
                                                    'region_family.kode',
                                                    'part1_keluarga.keluarga_periode_kode'
                                                )

                                                ->orWhereColumn(
                                                    'region_family.no_kk',
                                                    'part1_keluarga.no_kk'
                                                );
                                        }
                                    )

                                    ->where(
                                        function (
                                            QueryBuilder $districtQuery
                                        ) use (
                                            $filters,
                                            $kelurahanIdsInKecamatan
                                        ): void {

                                            $districtQuery
                                                ->where(
                                                    'region_family.kecamatan_id',
                                                    $filters['kecamatan']
                                                )

                                                ->orWhereIn(
                                                    'region_family.kelurahan_id',
                                                    $kelurahanIdsInKecamatan
                                                );
                                        }
                                    );

                                if (
                                    $filters['kelurahan'] !== ''
                                ) {
                                    $familyQuery->where(
                                        'region_family.kelurahan_id',
                                        $filters['kelurahan']
                                    );
                                }
                            }
                        )

                        ->orWhere(
                            function (
                                Builder $legacyQuery
                            ) use (
                                $filters,
                                $kecamatanNama
                            ): void {

                                $legacyQuery->whereIn(
                                    'part1_keluarga.kecamatan',
                                    array_values(
                                        array_unique(
                                            array_filter([
                                                $filters['kecamatan'],
                                                $kecamatanNama,
                                            ])
                                        )
                                    )
                                );

                                if (
                                    $filters['kelurahan'] !== ''
                                ) {

                                    $kelurahanNama =
                                        (string) DB::table(
                                            'kelurahans'
                                        )
                                            ->where(
                                                'kelurahan_id',
                                                $filters['kelurahan']
                                            )
                                            ->value(
                                                'deskripsi'
                                            );

                                    $legacyQuery->whereIn(
                                        'part1_keluarga.kelurahan',
                                        array_values(
                                            array_unique(
                                                array_filter([
                                                    $filters['kelurahan'],
                                                    $kelurahanNama,
                                                ])
                                            )
                                        )
                                    );
                                }
                            }
                        );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if (
            $filters['status'] !== 'all' &&
            in_array(
                $filters['status'],
                $allowedStatuses,
                true
            )
        ) {
            $this->applyStatusConstraint(
                $query,
                $filters['status']
            );
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS CONSTRAINT
    |--------------------------------------------------------------------------
    */

    protected function applyStatusConstraint(
        Builder $query,
        string $status
    ): void {

        if ($status === 'approved') {

            $query->whereIn(
                'part1_keluarga.status',
                [
                    'approved',
                    'disetujui',
                ]
            );

            return;
        }

        if ($status === 'rejected') {

            $query->whereIn(
                'part1_keluarga.status',
                [
                    'rejected',
                    'reject',
                    'ditolak',
                ]
            );

            return;
        }

        if ($status === 'not_processed') {

            $query->whereIn(
                'part1_keluarga.status',
                [
                    'not_processed',
                    'belum',
                    'belum_didata',
                    'belum didata',
                    'belum diproses',
                ]
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS BELUM APPROVED / REJECTED
        |--------------------------------------------------------------------------
        */

        $query->where(
            function (
                Builder $query
            ): void {

                $query
                    ->whereNull(
                        'part1_keluarga.status'
                    )

                    ->orWhereNotIn(
                        'part1_keluarga.status',
                        [
                            'approved',
                            'disetujui',
                            'rejected',
                            'reject',
                            'ditolak',
                            'not_processed',
                            'belum',
                            'belum_didata',
                            'belum didata',
                            'belum diproses',
                        ]
                    );
            }
        );

        /*
        |--------------------------------------------------------------------------
        | PENDING
        |--------------------------------------------------------------------------
        */

        if ($status === 'pending') {

            $this->whereQuestionnaireComplete(
                $query
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | DRAFT
        |--------------------------------------------------------------------------
        */

        $query->where(
            function (
                Builder $incompleteQuery
            ): void {

                $incompleteQuery
                    ->whereNull(
                        'part1_keluarga.current_part'
                    )

                    ->orWhere(
                        'part1_keluarga.current_part',
                        '<',
                        2
                    )

                    ->orWhere(
                        'part1_keluarga.current_part',
                        '<',
                        3
                    )

                    ->orWhereNotExists(
                        function (
                            QueryBuilder $partQuery
                        ): void {

                            $partQuery
                                ->selectRaw('1')
                                ->from(
                                    'part2_kondisi_rumahs'
                                )

                                ->whereColumn(
                                    'part2_kondisi_rumahs.keluarga_periode_kode',
                                    'part1_keluarga.keluarga_periode_kode'
                                );
                        }
                    )

                    ->orWhere(
                        'part1_keluarga.current_part',
                        '<',
                        4
                    )

                    ->orWhereNotExists(
                        function (
                            QueryBuilder $partQuery
                        ): void {

                            $partQuery
                                ->selectRaw('1')
                                ->from(
                                    'part3_keuangan_keluargas'
                                )

                                ->whereColumn(
                                    'part3_keuangan_keluargas.keluarga_periode_kode',
                                    'part1_keluarga.keluarga_periode_kode'
                                );
                        }
                    )

                    ->orWhere(
                        'part1_keluarga.current_part',
                        '<',
                        5
                    )

                    ->orWhereNotExists(
                        function (
                            QueryBuilder $partQuery
                        ): void {

                            $partQuery
                                ->selectRaw('1')
                                ->from(
                                    'part4_aset_keluargas'
                                )

                                ->whereColumn(
                                    'part4_aset_keluargas.keluarga_periode_kode',
                                    'part1_keluarga.keluarga_periode_kode'
                                );
                        }
                    )

                    ->orWhereNotExists(
                        function (
                            QueryBuilder $familyQuery
                        ): void {

                            $this->addCompleteMembersSubquery(
                                $familyQuery
                            );
                        }
                    )

                    ->orWhereNotExists(
                        function (
                            QueryBuilder $photoQuery
                        ): void {

                            $photoQuery
                                ->selectRaw('1')
                                ->from(
                                    'part5_foto_rumahs'
                                )

                                ->whereColumn(
                                    'part5_foto_rumahs.keluarga_periode_kode',
                                    'part1_keluarga.keluarga_periode_kode'
                                );
                        }
                    );
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUESTIONNAIRE COMPLETE
    |--------------------------------------------------------------------------
    */

    protected function whereQuestionnaireComplete(
        Builder $query
    ): void {

        $query
            ->where(
                'part1_keluarga.current_part',
                '>=',
                2
            )

            ->where(
                'part1_keluarga.current_part',
                '>=',
                3
            )

            ->whereExists(
                function (
                    QueryBuilder $partQuery
                ): void {

                    $partQuery
                        ->selectRaw('1')
                        ->from(
                            'part2_kondisi_rumahs'
                        )

                        ->whereColumn(
                            'part2_kondisi_rumahs.keluarga_periode_kode',
                            'part1_keluarga.keluarga_periode_kode'
                        );
                }
            )

            ->where(
                'part1_keluarga.current_part',
                '>=',
                4
            )

            ->whereExists(
                function (
                    QueryBuilder $partQuery
                ): void {

                    $partQuery
                        ->selectRaw('1')
                        ->from(
                            'part3_keuangan_keluargas'
                        )

                        ->whereColumn(
                            'part3_keuangan_keluargas.keluarga_periode_kode',
                            'part1_keluarga.keluarga_periode_kode'
                        );
                }
            )

            ->where(
                'part1_keluarga.current_part',
                '>=',
                5
            )

            ->whereExists(
                function (
                    QueryBuilder $partQuery
                ): void {

                    $partQuery
                        ->selectRaw('1')
                        ->from(
                            'part4_aset_keluargas'
                        )

                        ->whereColumn(
                            'part4_aset_keluargas.keluarga_periode_kode',
                            'part1_keluarga.keluarga_periode_kode'
                        );
                }
            )

            ->whereExists(
                function (
                    QueryBuilder $familyQuery
                ): void {

                    $this->addCompleteMembersSubquery(
                        $familyQuery
                    );
                }
            )

            ->whereExists(
                function (
                    QueryBuilder $photoQuery
                ): void {

                    $photoQuery
                        ->selectRaw('1')
                        ->from(
                            'part5_foto_rumahs'
                        )

                        ->whereColumn(
                            'part5_foto_rumahs.keluarga_periode_kode',
                            'part1_keluarga.keluarga_periode_kode'
                        );
                }
            );
    }

    /*
    |--------------------------------------------------------------------------
    | COMPLETE MEMBER SUBQUERY
    |--------------------------------------------------------------------------
    */

    protected function addCompleteMembersSubquery(
        QueryBuilder $familyQuery
    ): void {

        $familyQuery
            ->selectRaw('1')
            ->from(
                'keluargas as complete_family'
            )

            ->where(
                function (
                    QueryBuilder $familyMatch
                ): void {

                    $familyMatch
                        ->whereColumn(
                            'complete_family.kode',
                            'part1_keluarga.keluarga_periode_kode'
                        )

                        ->orWhereColumn(
                            'complete_family.no_kk',
                            'part1_keluarga.no_kk'
                        );
                }
            )

            ->whereExists(
                function (
                    QueryBuilder $memberQuery
                ): void {

                    $memberQuery
                        ->selectRaw('1')
                        ->from(
                            'keluarga_anggotas as complete_member'
                        )

                        ->whereColumn(
                            'complete_member.keluarga_kode',
                            'complete_family.kode'
                        );
                }
            )

            ->whereNotExists(
                function (
                    QueryBuilder $missingMemberQuery
                ): void {

                    $missingMemberQuery
                        ->selectRaw('1')
                        ->from(
                            'keluarga_anggotas as missing_member'
                        )

                        ->whereColumn(
                            'missing_member.keluarga_kode',
                            'complete_family.kode'
                        )

                        ->whereNotExists(
                            function (
                                QueryBuilder $part5Query
                            ): void {

                                $part5Query
                                    ->selectRaw('1')
                                    ->from(
                                        'part5_anggota_keluargas as complete_part5'
                                    )

                                    ->whereColumn(
                                        'complete_part5.keluarga_anggota_kode',
                                        'missing_member.kode'
                                    )

                                    ->whereColumn(
                                        'complete_part5.keluarga_periode_kode',
                                        'part1_keluarga.keluarga_periode_kode'
                                    );
                            }
                        );
                }
            );
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
        | APPROVED
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
        | REJECTED
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
        | NOT PROCESSED
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $rawStatus,
                [
                    'not_processed',
                    'belum',
                    'belum_didata',
                    'belum didata',
                    'belum diproses',
                ],
                true
            )
        ) {
            return 'not_processed';
        }

        /*
        |--------------------------------------------------------------------------
        | PROGRESS
        |--------------------------------------------------------------------------
        */

        $progress = $this->getProgress(
            $item,
            $keluarga
        );

        /*
        |--------------------------------------------------------------------------
        | PENDING
        |--------------------------------------------------------------------------
        */

        if (
            $progress['completed'] ===
            $progress['total']
        ) {
            return 'pending';
        }

        /*
        |--------------------------------------------------------------------------
        | DRAFT
        |--------------------------------------------------------------------------
        */

        return 'draft';
    }

    /*
    |--------------------------------------------------------------------------
    | PROGRESS
    |--------------------------------------------------------------------------
    */

    protected function getProgress(
        KeluargaPart1 $item,
        ?Keluarga $keluarga
    ): array {

        $kode =
            $item->keluarga_periode_kode;

        $currentPart = (int) (
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
                $currentPart >= 3 &&
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
                $currentPart >= 4 &&
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
                $currentPart >= 5 &&
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
        | PART 6 FOTO RUMAH
        |--------------------------------------------------------------------------
        */

        $part6 = false;

        if ($kode) {

            $part6 =
                KeluargaFotoRumah::where(
                    'keluarga_periode_kode',
                    $kode
                )->exists();
        }

        /*
        |--------------------------------------------------------------------------
        | DETAIL PART
        |--------------------------------------------------------------------------
        */

        $parts = [

            [
                'number' => 1,
                'title' => 'Data Keluarga',
                'completed' => $part1,
                'status' => $part1
                    ? 'Selesai'
                    : 'Belum Selesai',
            ],

            [
                'number' => 2,
                'title' => 'Kondisi Rumah',
                'completed' => $part2,
                'status' => $part2
                    ? 'Selesai'
                    : 'Belum Selesai',
            ],

            [
                'number' => 3,
                'title' =>
                    'Pengeluaran & Pendapatan',
                'completed' => $part3,
                'status' => $part3
                    ? 'Selesai'
                    : 'Belum Selesai',
            ],

            [
                'number' => 4,
                'title' => 'Aset Keluarga',
                'completed' => $part4,
                'status' => $part4
                    ? 'Selesai'
                    : 'Belum Selesai',
            ],

            [
                'number' => 5,
                'title' =>
                    'Data Anggota Keluarga',
                'completed' => $part5,
                'status' => $part5
                    ? 'Selesai'
                    : 'Belum Selesai',
            ],

            [
                'number' => 6,
                'title' => 'Foto Rumah',
                'completed' => $part6,
                'status' => $part6
                    ? 'Selesai'
                    : 'Belum Selesai',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | HITUNG
        |--------------------------------------------------------------------------
        */

        $completed = collect($parts)
            ->where(
                'completed',
                true
            )
            ->count();

        $total = 6;

        $percent = $total > 0
            ? (int) round(
                (
                    $completed / $total
                ) * 100
            )
            : 0;

        return [

            'completed' => $completed,

            'total' => $total,

            'percent' => $percent,

            'parts' => $parts,
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
        | CURRENT PART
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
        | KELUARGA HARUS ADA
        |--------------------------------------------------------------------------
        */

        $kode =
            $item->keluarga_periode_kode;

        if (
            ! $kode ||
            ! $keluarga ||
            ! $keluarga->kode
        ) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | SEMUA KODE ANGGOTA
        |--------------------------------------------------------------------------
        |
        | DIUBAH:
        | Ambil langsung dari keluarga_anggotas.
        |
        */

        $memberCodes =
            KeluargaAnggota::query()
                ->where(
                    'keluarga_kode',
                    $keluarga->kode
                )
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
        | KODE PART 5
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
        | SEMUA ANGGOTA HARUS ADA
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

            'not_processed' =>
                'Belum Didata',

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
                $keluarga->kecamatan ?? ''
            );

        $kelurahan =
            $item->kelurahan
            ?? (
                $keluarga->kelurahan ?? ''
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
        | PART 1
        |--------------------------------------------------------------------------
        */

        $part1Questions = [

            $this->question(
                'Nomor KK',
                $item->no_kk
                    ?? (
                        $keluarga->no_kk ?? '-'
                    )
            ),

            $this->question(
                'NIK',
                $item->nik
                    ?? (
                        $keluarga->nik ?? '-'
                    )
            ),

            $this->question(
                'Jumlah Anggota Keluarga',
                $item->jml_keluarga
                    ?? (
                        $keluarga
                            ? KeluargaAnggota::query()
                                ->where(
                                    'keluarga_kode',
                                    $keluarga->kode
                                )
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
            | GEOTAGGING
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

                'type' => 'map',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | PART 2
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
        | PART 3
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
        | PART 4
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
        | PART 5 - ANGGOTA KELUARGA
        |--------------------------------------------------------------------------
        */

        $part5Members = [];

        if (
            $kode &&
            $keluarga &&
            $keluarga->kode
        ) {

            /*
            |--------------------------------------------------------------------------
            | AMBIL ANGGOTA LANGSUNG DARI DATABASE
            |--------------------------------------------------------------------------
            */

            $members =
                KeluargaAnggota::query()
                    ->where(
                        'keluarga_kode',
                        $keluarga->kode
                    )
                    ->orderBy('id')
                    ->get();

            /*
            |--------------------------------------------------------------------------
            | KEPALA KELUARGA TIDAK DITAMPILKAN
            | SEBAGAI DETAIL PART 5
            |--------------------------------------------------------------------------
            */

            $members =
                $members->reject(
                    function ($member) {

                        return strtolower(
                            trim(
                                (string) (
                                    $member->status_keluarga
                                    ?? ''
                                )
                            )
                        ) === 'kepala keluarga';
                    }
                );

            /*
            |--------------------------------------------------------------------------
            | DATA PART 5
            |--------------------------------------------------------------------------
            */

            $part5Data =
                KeluargaPart5::where(
                    'keluarga_periode_kode',
                    $kode
                )
                    ->get()
                    ->keyBy(
                        'keluarga_anggota_kode'
                    );

            /*
            |--------------------------------------------------------------------------
            | BUILD SETIAP ANGGOTA
            |--------------------------------------------------------------------------
            */

            foreach (
                $members as $member
            ) {

                $memberCode =
                    (string) (
                        $member->kode ?? ''
                    );

                $part5Members[] =
                    $this->buildFamilyMemberQuestionnaire(
                        $member,
                        $part5Data->get(
                            $memberCode
                        )
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PART 6 - FOTO RUMAH
        |--------------------------------------------------------------------------
        */

        $part6Questions = [];

        if ($kode) {

            $fotoRumah =
                KeluargaFotoRumah::where(
                    'keluarga_periode_kode',
                    $kode
                )->get();

            foreach (
                $fotoRumah as $foto
            ) {

                $path = ltrim(
                    preg_replace(
                        '#^(public/|storage/)#i',
                        '',
                        str_replace(
                            '\\',
                            '/',
                            $foto->path_file
                        )
                    ),
                    '/'
                );

                $url = $path
                    ? Storage::disk(
                        'public'
                    )->url($path)
                    : '';

                $jenis = str_replace(
                    '_',
                    ' ',
                    $foto->jenis_foto
                    ?? 'Foto Rumah'
                );

                $part6Questions[] = [

                    'number' =>
                        count(
                            $part6Questions
                        ) + 1,

                    'question' =>
                        'Foto ' .
                        ucwords($jenis),

                    'text' =>
                        'Foto ' .
                        ucwords($jenis),

                    'answer' => $url,

                    'imageUrl' => $url,

                    'imageName' =>
                        $foto->nama_file
                        ?? basename($path),

                    'type' => 'image',
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | RETURN
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

                'members' =>
                    $part5Members,

                'questions' => [],
            ],

            [
                'part' => 6,

                'title' =>
                    'Foto Rumah',

                'questions' =>
                    $part6Questions,
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

            'number' => 0,

            'text' =>
                $question,

            'question' =>
                $question,

            'answer' =>
                $this->formatAnswer(
                    $answer
                ),

            'type' => 'text',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | FAMILY MEMBER QUESTIONNAIRE
    |--------------------------------------------------------------------------
    */

    protected function buildFamilyMemberQuestionnaire(
        KeluargaAnggota $member,
        ?KeluargaPart5 $part5
    ): array {

        $questions = [

            $this->question(
                'NIK',
                $member->nik
            ),

            $this->question(
                'Hubungan Keluarga',
                $member->status_keluarga
            ),
        ];

        if (! $part5) {

            $questions[] =
                $this->question(
                    'Data Part 5',
                    'Belum diisi'
                );

        } else {

            $questions = array_merge(
                $questions,
                [

                    $this->question(
                        'Keberadaan',
                        $part5->keberadaan
                    ),

                    $this->question(
                        'No. HP',
                        $part5->no_hp
                    ),

                    $this->question(
                        'Jenis Kelamin',
                        $part5->jenis_kelamin
                    ),

                    $this->question(
                        'Tanggal Lahir',
                        $part5->tanggal_lahir
                    ),

                    $this->question(
                        'Status Perkawinan',
                        $part5->status_perkawinan
                    ),

                    $this->question(
                        'Status Sekolah',
                        $part5->status_sekolah
                    ),

                    $this->question(
                        'Ijazah Tertinggi',
                        $part5->ijazah_tertinggi
                    ),

                    $this->question(
                        'Pekerjaan Utama',
                        $part5->pekerjaan_utama
                    ),

                    $this->question(
                        'Status Pekerjaan',
                        $part5->status_pekerjaan
                    ),

                    $this->question(
                        'Kepemilikan Rekening',
                        $this->formatBoolean(
                            $part5->kepemilikan_rekening
                        )
                    ),

                    $this->question(
                        'Disabilitas Fisik',
                        $this->formatBoolean(
                            $part5->is_disabilitas_fisik
                        )
                    ),

                    $this->question(
                        'Disabilitas Mental',
                        $this->formatBoolean(
                            $part5->is_disabilitas_mental
                        )
                    ),

                    $this->question(
                        'Disabilitas Intelektual',
                        $this->formatBoolean(
                            $part5->is_disabilitas_intelektual
                        )
                    ),

                    $this->question(
                        'Disabilitas Netra',
                        $this->formatBoolean(
                            $part5->is_disabilitas_netra
                        )
                    ),

                    $this->question(
                        'Disabilitas Rungu',
                        $this->formatBoolean(
                            $part5->is_disabilitas_rungu
                        )
                    ),

                    $this->question(
                        'Disabilitas Wicara',
                        $this->formatBoolean(
                            $part5->is_disabilitas_wicara
                        )
                    ),

                    $this->question(
                        'Keluhan Kesehatan',
                        $part5->keluhan_kesehatan
                    ),
                ]
            );
        }

        return [

            'kode' =>
                $member->kode,

            'nik' =>
                $member->nik
                ?? '-',

            'nama' =>
                $member->nama_lengkap
                ?? '-',

            'status_keluarga' =>
                $member->status_keluarga
                ?? '-',

            'questions' =>
                $questions,
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
            $value === null ||
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
            $value === null ||
            $value === ''
        ) {
            return '-';
        }

        if (
            $value === true ||
            $value === 1 ||
            $value === '1' ||
            $value === 'true' ||
            strtolower(
                (string) $value
            ) === 'ya'
        ) {
            return 'Ya';
        }

        if (
            $value === false ||
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

        if (! $data) {

            return redirect()
                ->route(
                    'verifikasi.index'
                )
                ->with(
                    'error',
                    'Data verifikasi tidak ditemukan.'
                );
        }

        return redirect()->route(
            'verifikasi.index',
            [
                'detail' => $id,
                'part' =>
                    request('part'),
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MEMBER DETAIL
    |--------------------------------------------------------------------------
    */

    public function memberDetail(
        int $id,
        string $memberCode
    ): View {

        $item =
            KeluargaPart1::findOrFail(
                $id
            );

        /*
        |--------------------------------------------------------------------------
        | CARI KELUARGA
        |--------------------------------------------------------------------------
        */

        $keluarga =
            $item->keluarga_periode_kode
            ? Keluarga::where(
                'kode',
                $item->keluarga_periode_kode
            )->first()
            : null;

        if (
            ! $keluarga &&
            $item->no_kk
        ) {

            $keluarga =
                Keluarga::where(
                    'no_kk',
                    $item->no_kk
                )->first();
        }

        abort_if(
            ! $keluarga,
            404
        );

        /*
        |--------------------------------------------------------------------------
        | CARI ANGGOTA
        |--------------------------------------------------------------------------
        |
        | DIUBAH:
        | Ambil langsung berdasarkan keluarga_kode.
        |
        */

        $member =
            KeluargaAnggota::query()
                ->where(
                    'keluarga_kode',
                    $keluarga->kode
                )
                ->where(
                    'kode',
                    $memberCode
                )
                ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | KEPALA KELUARGA TIDAK BOLEH MASUK DETAIL PART 5
        |--------------------------------------------------------------------------
        */

        abort_if(
            strtolower(
                trim(
                    (string) (
                        $member->status_keluarga
                        ?? ''
                    )
                )
            ) === 'kepala keluarga',
            404
        );

        /*
        |--------------------------------------------------------------------------
        | DATA PART 5 ANGGOTA
        |--------------------------------------------------------------------------
        */

        $part5 =
            KeluargaPart5::query()
                ->where(
                    'keluarga_periode_kode',
                    $item->keluarga_periode_kode
                )
                ->where(
                    'keluarga_anggota_kode',
                    $member->kode
                )
                ->first();

        /*
        |--------------------------------------------------------------------------
        | BUILD DATA DETAIL
        |--------------------------------------------------------------------------
        */

        $memberData =
            $this->buildFamilyMemberQuestionnaire(
                $member,
                $part5
            );

        /*
        |--------------------------------------------------------------------------
        | URL KEMBALI
        |--------------------------------------------------------------------------
        |
        | Tetap kembali ke halaman hasil kuisioner Part 5.
        |
        */

        $backUrl = route(
            'verifikasi.index',
            array_merge(
                request()->query(),
                [
                    'detail' =>
                        $item->id,

                    'part' => 5,
                ]
            )
        );

        /*
        
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.verifikasi.anggota',
            [
                'responden' => [

                    'id' =>
                        $item->id,

                    'no_kk' =>
                        $item->no_kk
                        ?? $keluarga->no_kk
                        ?? '-',

                    'nama' =>
                        $keluarga->nama_lengkap
                        ?? $item->nama_kepala_keluarga
                        ?? '-',
                ],

                'member' =>
                    $memberData,

                'backUrl' =>
                    $backUrl,
            ]
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
    ): RedirectResponse {

        $validated =
            $request->validate(
                [
                    'status' => [
                        'required',
                        'in:approved,rejected',
                    ],
                ]
            );

        /*
        |--------------------------------------------------------------------------
        | CARI PART 1
        |--------------------------------------------------------------------------
        */

        $item =
            KeluargaPart1::find(
                $id
            );

        if (! $item) {

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
            ! $keluarga &&
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
        | STATUS SEBENARNYA
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
                    'Data belum dapat diverifikasi karena kuisioner belum selesai sampai Part 6.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN STATUS
        |--------------------------------------------------------------------------
        */

        $item->status =
            $validated['status'];

        $user =
            $request->user();

        $item->updated_by =
            $user?->name
            ?? $user?->username
            ?? $user?->getAuthIdentifier()
            ?? 'admin';

        $item->save();

        /*
        |--------------------------------------------------------------------------
        | PESAN
        |--------------------------------------------------------------------------
        */

        $message =
            $validated['status'] === 'approved'
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