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
use App\Models\KeluargaPeriode;
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

        $allData = collect($this->getData())
            ->reject(
                fn (array $item): bool => ($item['status'] ?? null) === 'draft'
            )
            ->values();

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

        $this->whereQuestionnaireComplete($filteredQuery);

        $filteredData = collect(
            $this->getData($filteredQuery)
        )
            ->sort(function (array $a, array $b): int {
                // Urutan status pada halaman Verifikasi:
                // 1. Verifikasi (pending), 2. Disetujui, 3. Ditolak.
                $statusOrder = [
                    'pending' => 1,
                    'approved' => 2,
                    'rejected' => 3,
                ];

                $statusA = $statusOrder[$a['status'] ?? ''] ?? 99;
                $statusB = $statusOrder[$b['status'] ?? ''] ?? 99;

                if ($statusA !== $statusB) {
                    return $statusA <=> $statusB;
                }

                // Untuk status yang sama, data terbaru tetap ditampilkan lebih dulu.
                return (int) ($b['id'] ?? 0) <=> (int) ($a['id'] ?? 0);
            })
            ->values();

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

        $periodeAktif = $this->getSelectedPeriode();

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

    protected function getSelectedPeriode(): ?Periode
    {
        $periodeTerpilih = Periode::query()->find(session('periode_id'));

        if ($periodeTerpilih) {
            return $periodeTerpilih;
        }

        return Periode::query()
            ->whereRaw('LOWER(TRIM(status_periode)) = ?', ['aktif'])
            ->orderByDesc('id')
            ->first();
    }

    protected function getData(?Builder $query = null)
    {
        $periodeTerpilih = $this->getSelectedPeriode();
        $itemsQuery = $query ?? KeluargaPart1::query();

        if (KeluargaPeriode::query()->exists()) {
            $itemsQuery->whereExists(
                function (QueryBuilder $familyPeriodQuery) use ($periodeTerpilih): void {
                    $familyPeriodQuery
                        ->selectRaw('1')
                        ->from('keluarga_periodes as verification_period')
                        ->join(
                            'keluargas as verification_family',
                            'verification_family.kode',
                            '=',
                            'verification_period.keluarga_kode'
                        )
                        ->whereColumn(
                            'verification_period.kode',
                            'part1_keluarga.keluarga_periode_kode'
                        );

                    if ($periodeTerpilih) {
                        $familyPeriodQuery->where(
                            'verification_period.periode_kode',
                            $periodeTerpilih->kode
                        );
                    }
                }
            );
        } else {
            $itemsQuery->where(function (Builder $query): void {
                $query
                    ->whereIn(
                        'part1_keluarga.keluarga_periode_kode',
                        Keluarga::query()->select('kode')
                    )
                    ->orWhereIn(
                        'part1_keluarga.no_kk',
                        Keluarga::query()
                            ->whereNotNull('no_kk')
                            ->select('no_kk')
                    );
            });
        }

        $items = $itemsQuery
            ->orderByDesc('id')
            ->get();

        $familyPeriods = KeluargaPeriode::query()
            ->whereIn(
                'kode',
                $items
                    ->pluck('keluarga_periode_kode')
                    ->filter()
                    ->unique()
                    ->values()
            )
            ->when(
                $periodeTerpilih,
                fn (Builder $periodQuery): Builder => $periodQuery->where(
                    'periode_kode',
                    $periodeTerpilih->kode
                )
            )
            ->with('keluarga')
            ->get()
            ->keyBy('kode');

        $periodesByCode = Periode::query()
            ->whereIn(
                'kode',
                $familyPeriods
                    ->pluck('periode_kode')
                    ->filter()
                    ->unique()
                    ->values()
            )
            ->get()
            ->keyBy('kode');

        /*
        |--------------------------------------------------------------------------
        | MAP DATA
        |--------------------------------------------------------------------------
        */

        return $items
            ->map(function ($item) use (
                $periodeTerpilih,
                $familyPeriods,
                $periodesByCode
            ) {

                /*
                |--------------------------------------------------------------------------
                | CARI DATA KELUARGA
                |--------------------------------------------------------------------------
                */

                $familyPeriod = $familyPeriods->get(
                    $item->keluarga_periode_kode
                );

                $keluarga = $familyPeriod?->keluarga;

                /*
                |--------------------------------------------------------------------------
                | FALLBACK DATA LAMA
                |--------------------------------------------------------------------------
                */

                if (! $keluarga) {
                    if ($item->keluarga_periode_kode) {
                        $keluarga = Keluarga::where(
                            'kode',
                            $item->keluarga_periode_kode
                        )->first();
                    }

                    if (! $keluarga && $item->no_kk) {
                        $familyQuery = Keluarga::where(
                            'no_kk',
                            $item->no_kk
                        );

                        if (filled($item->nik)) {
                            $familyQuery->where('nik', $item->nik);
                        }

                        $keluarga = $familyQuery->first();
                    }
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

                $periode = $periodesByCode->get(
                    $familyPeriod?->periode_kode
                ) ?? $periodeTerpilih;

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

                    'jumlah_anggota' => $jumlahAnggota,

                    'anggota' => $jumlahAnggota,

                    'anggota_detail' => $anggotaDetail,

                    /*
                    | PERIODE
                    */

                    'periode' => $periode
                        ? (string) (
                            $periode->nama
                            ?: $periode->kode
                        )
                        : '-',

                    'periode_kode' => $periode?->kode ?? '-',

                    'periode_tanggal' => $periode
                        ? Carbon::parse(
                            $periode->tgl_awal
                        )->format('d-m-Y')
                        .' - '
                        .Carbon::parse(
                            $periode->tgl_akhir
                        )->format('d-m-Y')
                        : '-',

                    /*
                    | STATUS
                    */

                    'status' => $status,

                    // Alasan penolakan disediakan untuk Monitoring dan Laporan.
                    'alasan_penolakan' => $item->alasan_penolakan ?? null,

                    'status_label' => $this->getStatusLabel(
                        $status
                    ),

                    /*
                    | PROGRESS
                    */

                    'progress' => $progress,

                    'progress_completed' => $progress['completed'],

                    'progress_total' => $progress['total'],

                    'progress_percent' => $progress['percent'],

                    'progress_detail' => $progress['parts'],

                    /*
                    | WILAYAH
                    */

                    'wilayah' => $this->buildWilayah(
                        $item,
                        $keluarga
                    ),

                    /*
                    | PETUGAS
                    */

                    'petugas' => $item->updated_by
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

                    'provinsi' => $item->provinsi
                        ?? (
                            $keluarga->provinsi ?? '-'
                        ),

                    'daerah' => $item->daerah
                        ?? (
                            $keluarga->daerah ?? '-'
                        ),

                    'kecamatan' => $item->kecamatan
                        ?? (
                            $keluarga->kecamatan ?? '-'
                        ),

                    'kelurahan' => $item->kelurahan
                        ?? (
                            $keluarga->kelurahan ?? '-'
                        ),

                    'kode_pos' => $item->kode_pos
                        ?? (
                            $keluarga->kode_pos ?? '-'
                        ),

                    // Normalisasi alamat: beberapa versi form lama menyimpan
                    // RT/RW dan koordinat sekaligus di kolom alamat_lengkap.
                    'rt_rw' => $this->resolveRtRw(
                        $item->rt_rw ?? ($keluarga->rt_rw ?? null),
                        $item->alamat_lengkap ?? ($keluarga->alamat_lengkap ?? null),
                        $keluarga->rt ?? null,
                        $keluarga->rw ?? null
                    ),

                    'alamat_lengkap' => $this->cleanFullAddress(
                        $item->alamat_lengkap ?? ($keluarga->alamat_lengkap ?? null)
                    ),

                    'jalan_rumah' => $item->jalan_rumah
                        ?? '-',

                    'is_alamat_sesuai' => $this->resolveAddressMatch($item, $keluarga),

                    /*
                    | GEOTAGGING
                    */

                    'geotangging' => $this->resolveGeotag(
                        filled($item->geotangging ?? null)
                            ? $item->geotangging
                            : ($keluarga->geotagging ?? $keluarga->geotangging ?? null),
                        $item->alamat_lengkap ?? ($keluarga->alamat_lengkap ?? null)
                    ),

                    /*
                    | PART
                    */

                    'current_part' => (int) (
                        $item->current_part
                        ?? 1
                    ),

                    'keluarga_periode_kode' => $item->keluarga_periode_kode,

                    'created_at' => $item->created_at
                        ? $item->created_at->format(
                            'd-m-Y H:i'
                        )
                        : '-',

                    /*
                    | KUISIONER
                    */

                    'kuisioner' => $this->buildQuestionnaire(
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
                '%'.$filters['search'].'%';

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
        $query->where(function (Builder $completionQuery): void {
            $completionQuery
                ->whereIn('part1_keluarga.status', [
                    'selesai',
                    'approved',
                    'disetujui',
                    'rejected',
                    'reject',
                    'ditolak',
                ])
                ->orWhere(function (Builder $completePartsQuery): void {
                    $this->whereQuestionnairePartsComplete($completePartsQuery);
                });
        });
    }

    protected function whereQuestionnairePartsComplete(
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

        if ($rawStatus === 'selesai') {
            return 'pending';
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
                'title' => 'Pengeluaran & Pendapatan',
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
                'title' => 'Data Anggota Keluarga',
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
                    fn ($value) => (string) $value
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
                    fn ($value) => (string) $value
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

            'approved' => 'Disetujui',

            'rejected' => 'Ditolak',

            'pending' => 'Menunggu Verifikasi',

            'not_processed' => 'Belum Didata',

            default => 'Draft',
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
                fn ($value) => trim(
                    (string) $value
                ) !== ''
            )
            ->map(
                fn ($value) => trim(
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
    | NORMALISASI ALAMAT PART 1
    |--------------------------------------------------------------------------
    | Data lama kadang menyimpan RT/RW dan koordinat di dalam alamat_lengkap.
    | Helper ini memisahkan nilai tersebut hanya untuk tampilan, tanpa mengubah DB.
    */

    protected function resolveRtRw($rtRw, $alamat, $rt = null, $rw = null): string
    {
        $rtRw = trim((string) ($rtRw ?? ''));

        if ($rtRw !== '' && $rtRw !== '-') {
            if (preg_match('/RT\s*0*(\d{1,3})\s*\/?\s*RW\s*0*(\d{1,3})/i', $rtRw, $m)) {
                return sprintf('RT %03d / RW %03d', (int) $m[1], (int) $m[2]);
            }

            return $rtRw;
        }

        $rt = trim((string) ($rt ?? ''));
        $rw = trim((string) ($rw ?? ''));
        $parts = [];

        if ($rt !== '' && $rt !== '-') {
            $parts[] = 'RT '.(ctype_digit($rt) ? sprintf('%03d', (int) $rt) : $rt);
        }

        if ($rw !== '' && $rw !== '-') {
            $parts[] = 'RW '.(ctype_digit($rw) ? sprintf('%03d', (int) $rw) : $rw);
        }

        if ($parts !== []) {
            return implode(' / ', $parts);
        }

        $alamat = (string) ($alamat ?? '');
        if (preg_match('/RT\s*0*(\d{1,3})\s*\/?\s*RW\s*0*(\d{1,3})/i', $alamat, $m)) {
            return sprintf('RT %03d / RW %03d', (int) $m[1], (int) $m[2]);
        }

        return '-';
    }

    protected function resolveGeotag($geotag, $alamat): string
    {
        $geotag = trim((string) ($geotag ?? ''));

        if ($geotag !== '' && $geotag !== '-') {
            if (preg_match('/-?\d+(?:\.\d+)?\s*,\s*-?\d+(?:\.\d+)?/', $geotag, $m)) {
                return preg_replace('/\s+/', '', $m[0]);
            }
        }

        if (preg_match(
            '/(?:Koordinat\s*:\s*)?(-?\d+(?:\.\d+)?)\s*,\s*(-?\d+(?:\.\d+)?)/i',
            (string) ($alamat ?? ''),
            $m
        )) {
            return $m[1].', '.$m[2];
        }

        return $geotag !== '' && $geotag !== '-' ? $geotag : '-';
    }

    protected function cleanFullAddress($alamat): string
    {
        $alamat = trim((string) ($alamat ?? ''));

        if ($alamat === '' || $alamat === '-') {
            return '-';
        }

        // Hilangkan label koordinat beserta pasangan lintang/bujur.
        $alamat = preg_replace(
            '/[,;\s]*Koordinat\s*:\s*-?\d+(?:\.\d+)?\s*,\s*-?\d+(?:\.\d+)?/i',
            '',
            $alamat
        );

        // Hilangkan koordinat telanjang jika format lama tidak memiliki label.
        $alamat = preg_replace(
            '/[,;\s]*-?\d{1,2}\.\d{4,}\s*,\s*-?\d{2,3}\.\d{4,}/',
            '',
            $alamat
        );

        // RT/RW adalah field tersendiri, jadi buang semua pengulangan dari alamat.
        $alamat = preg_replace(
            '/(?:^|[,;]\s*)RT\s*0*\d{1,3}\s*\/?\s*RW\s*0*\d{1,3}\s*(?=[,;]|$)/i',
            ' ',
            $alamat
        );

        // Rapikan pemisah dan whitespace akibat penghapusan bagian di atas.
        $alamat = preg_replace('/\s*[,;]\s*[,;]\s*/', ', ', $alamat);
        $alamat = preg_replace('/^[\s,;]+|[\s,;]+$/', '', $alamat);
        $alamat = preg_replace('/\s{2,}/', ' ', $alamat);
        $alamat = trim($alamat, " \t\n\r\0\x0B,;");

        return $alamat !== '' ? $alamat : '-';
    }

    protected function resolveAddressMatch($item, $keluarga = null)
    {
        // Baca nama kolom yang umum dipakai oleh beberapa versi form.
        foreach ([
            'is_alamat_sesuai',
            'alamat_sesuai',
            'is_alamat_sesuai_ktp',
            'alamat_sesuai_ktp',
        ] as $attribute) {
            $value = $item->getAttribute($attribute);
            if ($value !== null && $value !== '') {
                return $value;
            }
        }

        if ($keluarga) {
            foreach ([
                'is_alamat_sesuai',
                'alamat_sesuai',
                'is_alamat_sesuai_ktp',
                'alamat_sesuai_ktp',
            ] as $attribute) {
                $value = $keluarga->getAttribute($attribute);
                if ($value !== null && $value !== '') {
                    return $value;
                }
            }
        }

        return null;
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

        $firstStoredValue = static function (mixed ...$values): mixed {
            foreach ($values as $value) {
                if ($value === null) {
                    continue;
                }

                if (is_string($value)) {
                    $value = trim($value);
                }

                if ($value !== '' && $value !== '-') {
                    return $value;
                }
            }

            return '-';
        };

        $jumlahAnggota = $firstStoredValue($item->jml_keluarga);
        if ($jumlahAnggota === '-' && $keluarga) {
            $jumlahAnggota = KeluargaAnggota::query()
                ->where('keluarga_kode', $keluarga->kode)
                ->count() + 1;
        }

        $daerah = $firstStoredValue($item->daerah, $keluarga->daerah ?? null);
        if ($daerah === '-') {
            $daerah = 'Kota Pasuruan';
        }

        $alamatSesuaiRaw = $this->resolveAddressMatch($item, $keluarga);

        $alamatSesuaiQuestion = $this->question(
            'Apakah alamat tempat tinggal saat ini sesuai dengan KK? *',
            $this->formatBoolean($alamatSesuaiRaw)
        );
        $alasanAlamatTidakSesuai = trim((string) ($item->alasan_tidak_sesuai ?? ''));

        if (
            $this->formatBoolean($alamatSesuaiRaw) === 'Tidak'
            && $alasanAlamatTidakSesuai !== ''
            && $alasanAlamatTidakSesuai !== '-'
        ) {
            $alamatSesuaiQuestion['reason'] = $alasanAlamatTidakSesuai;
        }

        $part1Questions = [
            $this->question(
                'NIK *',
                $firstStoredValue($item->nik, $keluarga->nik ?? null)
            ),
            $this->question(
                'Nama Kepala Keluarga *',
                $firstStoredValue($item->nama_kepala_keluarga, $keluarga->nama_lengkap ?? null)
            ),
            $this->question(
                'Status dalam Keluarga *',
                $firstStoredValue($keluarga->status_keluarga ?? null, $item->status_dalam_keluarga ?? null)
            ),
            $this->question(
                'Nomor KK *',
                $firstStoredValue($item->no_kk, $keluarga->no_kk ?? null)
            ),
            $this->question(
                'Jumlah Anggota Keluarga *',
                $jumlahAnggota
            ),
            $this->question(
                'Provinsi *',
                $firstStoredValue($item->provinsi, $keluarga->provinsi ?? null)
            ),
            $this->question(
                'Kabupaten / Kota *',
                $daerah
            ),
            $this->question(
                'Kecamatan *',
                $firstStoredValue($item->kecamatan, $keluarga->kecamatan ?? null)
            ),
            $this->question(
                'Kelurahan *',
                $firstStoredValue($item->kelurahan, $keluarga->kelurahan ?? null)
            ),
            $this->question(
                'Kode Pos',
                $firstStoredValue($item->kode_pos, $keluarga->kode_pos ?? null)
            ),
            $this->question(
                'RT / RW *',
                $this->resolveRtRw(
                    $item->rt_rw ?? ($keluarga->rt_rw ?? null),
                    $item->alamat_lengkap ?? ($keluarga->alamat_lengkap ?? null),
                    $keluarga->rt ?? null,
                    $keluarga->rw ?? null
                )
            ),
            $this->question(
                'Alamat Lengkap *',
                $this->cleanFullAddress(
                    $firstStoredValue($item->alamat_lengkap, $keluarga->alamat_lengkap ?? null)
                )
            ),
            $this->question(
                'Jalan',
                $firstStoredValue($item->jalan_rumah, $keluarga->jalan_rumah ?? null)
            ),
            $this->question(
                'Nomor Rumah',
                $firstStoredValue($item->nomor_rumah, $keluarga->nomor_rumah ?? null)
            ),
            $alamatSesuaiQuestion,
        ];

        $part1Questions[] = [
            'number' => 0,
            'text' => 'Lokasi Rumah / Geotagging',
            'question' => 'Lokasi Rumah / Geotagging',
            'answer' => $this->resolveGeotag(
                filled($item->geotangging ?? null)
                    ? $item->geotangging
                    : ($keluarga->geotagging ?? $keluarga->geotangging ?? null),
                $item->alamat_lengkap ?? ($keluarga->alamat_lengkap ?? null)
            ),
            'type' => 'map',
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
                        $namaAset.' - Memiliki',
                        $this->formatBoolean(
                            $asset->is_punya_aset
                        )
                    );

                $part4Questions[] =
                    $this->question(
                        $namaAset.' - Jumlah',
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

            $part5Rows = KeluargaPart5::query()
                ->where('keluarga_periode_kode', $kode)
                ->get();

            /*
             * Index data Part 5 dengan kode anggota yang dinormalisasi.
             * Ini menghindari data tidak ditemukan hanya karena perbedaan
             * tipe nilai/spasi pada kode anggota saat collection di-keyBy.
             */
            $part5Data = $part5Rows->keyBy(
                fn ($row) => trim((string) $row->keluarga_anggota_kode)
            );

            /*
            |--------------------------------------------------------------------------
            | BUILD SETIAP ANGGOTA
            |--------------------------------------------------------------------------
            */

            foreach (
                $members as $member
            ) {

                $memberCode = trim((string) ($member->kode ?? ''));

                // Cari berdasarkan kode anggota + periode yang sedang ditampilkan.
                $part5 = $memberCode !== ''
                    ? $part5Data->get($memberCode)
                    : null;

                // Fallback query langsung jika indeks collection tidak menemukan row.
                if (! $part5 && $memberCode !== '') {
                    $part5 = KeluargaPart5::query()
                        ->where('keluarga_periode_kode', $kode)
                        ->where('keluarga_anggota_kode', $memberCode)
                        ->first();
                }

                $part5Members[] = $this->buildFamilyMemberQuestionnaire(
                    $member,
                    $part5
                );
            }
        }


/*
|--------------------------------------------------------------------------
| HUBUNGKAN JAWABAN PART 5 LAMA KE ANGGOTA AKTIF
|--------------------------------------------------------------------------
| Perbarui card anggota yang sudah dibuat, bukan menambahkan card baru.
|--------------------------------------------------------------------------
*/

$kodeAnggotaAktif = $members
    ->pluck('kode')
    ->map(fn ($value) => trim((string) $value))
    ->filter()
    ->values();

$part5Lama = $part5Rows
    ->filter(function ($row) use ($kodeAnggotaAktif) {
        $kodeLama = trim((string) $row->keluarga_anggota_kode);

        return $kodeLama !== ''
            && ! $kodeAnggotaAktif->contains($kodeLama);
    })
    ->sortBy('id')
    ->values();

$anggotaTanpaJawaban = $members
    ->filter(function ($member) use ($part5Rows) {
        $kode = trim((string) $member->kode);

        return ! $part5Rows->contains(
            fn ($row) =>
                trim((string) $row->keluarga_anggota_kode) === $kode
        );
    })
    ->values();

if (
    $part5Lama->isNotEmpty()
    && $part5Lama->count() === $anggotaTanpaJawaban->count()
) {
    foreach ($anggotaTanpaJawaban as $index => $member) {
        $rowLama = $part5Lama->get($index);

        // Cari card anggota yang sudah dibuat sebelumnya.
        foreach ($part5Members as $key => $existingMember) {
            if (
                trim((string) ($existingMember['kode'] ?? ''))
                === trim((string) $member->kode)
            ) {
                // Perbarui jawaban pada card tersebut, tanpa menambah card.
                $part5Members[$key] =
                    $this->buildFamilyMemberQuestionnaire(
                        $member,
                        $rowLama
                    );

                break;
            }
        }
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

                $rawPath = trim(
                    str_replace('\\', '/', (string) $foto->path_file)
                );

                if (
                    $rawPath !== ''
                    && preg_match('/^https?:\/\//i', $rawPath)
                ) {
                    // File sudah memiliki URL lengkap.
                    $url = $rawPath;
                } else {
                    // Hilangkan prefix penyimpanan yang mungkin sudah tersimpan.
                    $path = preg_replace(
                        '#^(?:public/|storage/|app/public/)#i',
                        '',
                        ltrim($rawPath, '/')
                    );

                    $url = $path !== ''
                        ? Storage::disk('public')->url($path)
                        : '';
                }

                $jenis = str_replace(
                    '_',
                    ' ',
                    $foto->jenis_foto
                    ?? 'Foto Rumah'
                );

                $part6Questions[] = [

                    'number' => count(
                        $part6Questions
                    ) + 1,

                    'question' => 'Foto '.
                        ucwords($jenis),

                    'text' => 'Foto '.
                        ucwords($jenis),

                    'answer' => $url,

                    'imageUrl' => $url,

                    'imageName' => $foto->nama_file
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

                'title' => 'Data Keluarga',

                'questions' => $part1Questions,
            ],

            [
                'part' => 2,

                'title' => 'Kondisi Rumah',

                'questions' => $part2Questions,
            ],

            [
                'part' => 3,

                'title' => 'Pengeluaran & Pendapatan',

                'questions' => $part3Questions,
            ],

            [
                'part' => 4,

                'title' => 'Aset Keluarga',

                'questions' => $part4Questions,
            ],

            [
                'part' => 5,

                'title' => 'Data Anggota Keluarga',

                'members' => $part5Members,

                'questions' => [],
            ],

            [
                'part' => 6,

                'title' => 'Foto Rumah',

                'questions' => $part6Questions,
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

            'text' => $question,

            'question' => $question,

            'answer' => $this->formatAnswer(
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

            'kode' => $member->kode,

            'nik' => $member->nik
                ?? '-',

            'nama' => $member->nama_lengkap
                ?? '-',

            'status_keluarga' => $member->status_keluarga
                ?? '-',

            'questions' => $questions,
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
                    fn ($item) => $this->formatAnswer(
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
                'part' => request('part'),
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

$part5 = KeluargaPart5::query()
    ->where('keluarga_periode_kode', $item->keluarga_periode_kode)
    ->where('keluarga_anggota_kode', $member->kode)
    ->first();

/*
|--------------------------------------------------------------------------
| FALLBACK: CARI DATA PART 5 YANG KODENYA SUDAH TIDAK COCOK
|--------------------------------------------------------------------------
*/

if (! $part5) {
    $part5 = KeluargaPart5::query()
        ->where('keluarga_periode_kode', $item->keluarga_periode_kode)
        ->whereNotNull('keluarga_anggota_kode')
        ->where('keluarga_anggota_kode', '!=', '')
        ->orderBy('id')
        ->get()
        ->first(function ($row) use ($member) {
            // Hanya mengambil data yang belum terhubung
            // ke kode anggota aktif.
            return ! KeluargaAnggota::query()
                ->where('kode', $row->keluarga_anggota_kode)
                ->exists();
        });
}

/*
|--------------------------------------------------------------------------
| BUILD DATA DETAIL
|--------------------------------------------------------------------------
*/

$memberData = $this->buildFamilyMemberQuestionnaire(
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
                    'detail' => $item->id,

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

                    'id' => $item->id,

                    'no_kk' => $item->no_kk
                        ?? $keluarga->no_kk
                        ?? '-',

                    'nama' => $keluarga->nama_lengkap
                        ?? $item->nama_kepala_keluarga
                        ?? '-',
                ],

                'member' => $memberData,

                'backUrl' => $backUrl,
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

        $validated = $request->validate(
            [
                'status' => ['required', 'in:approved,rejected'],
                'alasan_penolakan' => [
                    'nullable',
                    'string',
                    'max:2000',
                    'required_if:status,rejected',
                ],
            ],
            [
                'status.required' => 'Silakan pilih aksi verifikasi.',
                'status.in' => 'Aksi verifikasi tidak valid.',
                'alasan_penolakan.required_if' => 'Alasan penolakan wajib diisi ketika data ditolak.',
                'alasan_penolakan.string' => 'Alasan penolakan harus berupa teks.',
                'alasan_penolakan.max' => 'Alasan penolakan maksimal 2.000 karakter.',
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
                    'Data belum dapat diverifikasi karena kuisioner belum selesai sampai Part 5.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN STATUS
        |--------------------------------------------------------------------------
        */

        $item->status = $validated['status'];

        // Alasan wajib disimpan untuk status ditolak.
        // Jika status berubah menjadi disetujui, alasan lama dibersihkan.
        $item->alasan_penolakan = $validated['status'] === 'rejected'
            ? trim((string) $validated['alasan_penolakan'])
            : null;

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

        $message = $validated['status'] === 'approved'
            ? 'Data berhasil disetujui.'
            : 'Data berhasil ditolak. Alasan penolakan berhasil disimpan.';

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
