<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\KeluargaPart1;
use App\Models\Periode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\Expression;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private const APPROVED_STATUSES = [
        'approved',
        'disetujui',
    ];

    private const REJECTED_STATUSES = [
        'rejected',
        'reject',
        'ditolak',
    ];

    private const NOT_PROCESSED_STATUSES = [
        'not_processed',
        'belum',
        'belum_didata',
        'belum didata',
        'belum diproses',
    ];

    public function index(Request $request): View
    {
        $hasKeluargasTable = Schema::hasTable('keluargas');
        $hasPeriodesTable = Schema::hasTable('periodes');
        $hasKecamatanTable = Schema::hasTable('kecamatans');
        $hasKelurahanTable = Schema::hasTable('kelurahans');
        $hasPart1Table = Schema::hasTable('part1_keluarga');
        $hasKeluargaPeriodesTable = Schema::hasTable('keluarga_periodes');

        $periodeOptions = $hasPeriodesTable
            ? Periode::orderByDesc('id')->get()
            : collect();

        $selectedPeriodKode = trim((string) $request->query('periode_kode', ''));

        if (
            $selectedPeriodKode !== ''
            && ! $periodeOptions->contains(
                fn ($periode) => (string) $periode->kode === $selectedPeriodKode
            )
        ) {
            $selectedPeriodKode = '';
        }

        $periodeAktif = $hasPeriodesTable
            ? Periode::whereRaw('LOWER(TRIM(status_periode)) = ?', ['aktif'])->count()
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Total Responden Mengikuti Periode yang Dipilih
        |--------------------------------------------------------------------------
        */
        $keluargaQuery = Keluarga::query();

        if ($selectedPeriodKode !== '' && $hasKeluargaPeriodesTable) {
            $keluargaQuery->whereIn(
                'keluargas.kode',
                DB::table('keluarga_periodes')
                    ->where('periode_kode', $selectedPeriodKode)
                    ->select('keluarga_kode')
            );
        }

        $totalResponden = $hasKeluargasTable
            ? $keluargaQuery->count()
            : 0;

        $dataMasukQuery = Keluarga::query();

        if ($selectedPeriodKode !== '' && $hasKeluargaPeriodesTable) {
            $dataMasukQuery->whereIn(
                'keluargas.kode',
                DB::table('keluarga_periodes')
                    ->where('periode_kode', $selectedPeriodKode)
                    ->select('keluarga_kode')
            );
        }

        $dataMasukHariIni = $hasKeluargasTable
            ? $dataMasukQuery->whereDate('created_at', today())->count()
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Rekap Setiap Periode
        |--------------------------------------------------------------------------
        */
        $rekapPeriode = $periodeOptions->map(function ($periode) use (
            $hasKeluargaPeriodesTable,
            $hasPart1Table
        ) {
            $totalPeriode = 0;
            $jumlahDisetujuiPeriode = 0;
            $jumlahDitolakPeriode = 0;
            $jumlahMenungguPeriode = 0;

            if ($hasKeluargaPeriodesTable) {
                // Total responden unik yang benar-benar terhubung ke periode ini.
                $totalPeriode = DB::table('keluarga_periodes')
                    ->where('periode_kode', $periode->kode)
                    ->whereNotNull('keluarga_kode')
                    ->distinct()
                    ->count('keluarga_kode');

                if ($hasPart1Table) {
                    // Cocokkan data Part 1 dengan kode keluarga (KLG-...) atau
                    // kode relasi periode (KLP-...). Di database, Part 1 bisa
                    // menyimpan kode keluarga, bukan kode relasi periode.
                    $jumlahDisetujuiPeriode = $this->linkedVerificationQuery(
                        (string) $periode->kode
                    )
                        ->whereIn(
                            new Expression('LOWER(TRIM(part1_keluarga.status))'),
                            self::APPROVED_STATUSES
                        )
                        ->count();

                    $jumlahDitolakPeriode = $this->linkedVerificationQuery(
                        (string) $periode->kode
                    )
                        ->whereIn(
                            new Expression('LOWER(TRIM(part1_keluarga.status))'),
                            self::REJECTED_STATUSES
                        )
                        ->count();

                    $jumlahMenungguPeriode = $this->pendingVerificationQuery(
                        (string) $periode->kode
                    )->count();
                }
            }

            // Setiap status ditampilkan terpisah agar tidak tercampur.
            $periode->setAttribute('total_responden', $totalPeriode);
            $periode->setAttribute('jumlah_disetujui', $jumlahDisetujuiPeriode);
            $periode->setAttribute('jumlah_menunggu_verifikasi', $jumlahMenungguPeriode);
            $periode->setAttribute('jumlah_ditolak', $jumlahDitolakPeriode);

            return $periode;
        });

        // Jika filter periode dipilih, tabel rekap menampilkan periode yang sama.
        if ($selectedPeriodKode !== '') {
            $rekapPeriode = $rekapPeriode
                ->filter(fn ($periode) => (string) $periode->kode === $selectedPeriodKode)
                ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | Persebaran Responden per Kecamatan
        |--------------------------------------------------------------------------
        */
        if ($hasKecamatanTable && $hasKeluargasTable) {
            // Kecamatan selalu ditampilkan dalam urutan yang sama, meskipun
            // kecamatan tertentu tidak memiliki responden pada periode terpilih.
            $kecamatanQuery = DB::table('kecamatans')
                ->leftJoin(
                    'keluargas',
                    'keluargas.kecamatan_id',
                    '=',
                    'kecamatans.kecamatan_id'
                );

            if ($selectedPeriodKode !== '' && $hasKeluargaPeriodesTable) {
                // Filter periode diletakkan pada kondisi LEFT JOIN, bukan WHERE,
                // supaya kecamatan dengan jumlah 0 tetap ikut dikirim ke grafik.
                $kecamatanQuery->leftJoin('keluarga_periodes', function ($join) use ($selectedPeriodKode) {
                    $join->on('keluarga_periodes.keluarga_kode', '=', 'keluargas.kode')
                        ->where('keluarga_periodes.periode_kode', '=', $selectedPeriodKode);
                });

                $totalRespondenKecamatan = DB::raw(
                    'COUNT(DISTINCT CASE WHEN keluarga_periodes.kode IS NOT NULL THEN keluargas.id ELSE NULL END) as total_responden'
                );
            } else {
                $totalRespondenKecamatan = DB::raw(
                    'COUNT(DISTINCT keluargas.id) as total_responden'
                );
            }

            $persebaranKecamatan = $kecamatanQuery
                ->select(
                    'kecamatans.kecamatan_id',
                    'kecamatans.deskripsi as nama_kecamatan',
                    $totalRespondenKecamatan
                )
                ->groupBy(
                    'kecamatans.kecamatan_id',
                    'kecamatans.deskripsi'
                )
                ->orderBy('kecamatans.deskripsi')
                ->get();
        } else {
            $persebaranKecamatan = collect();
        }

        /*
        |--------------------------------------------------------------------------
        | Persebaran Responden per Kelurahan
        |--------------------------------------------------------------------------
        */
        if ($hasKelurahanTable && $hasKecamatanTable && $hasKeluargasTable) {
            $kelurahanQuery = DB::table('kelurahans')
                ->join(
                    'kecamatans',
                    'kecamatans.kecamatan_id',
                    '=',
                    'kelurahans.kecamatan_id'
                )
                ->leftJoin(
                    'keluargas',
                    'keluargas.kelurahan_id',
                    '=',
                    'kelurahans.kelurahan_id'
                );

            if ($selectedPeriodKode !== '' && $hasKeluargaPeriodesTable) {
                $kelurahanQuery->join(
                    'keluarga_periodes',
                    'keluarga_periodes.keluarga_kode',
                    '=',
                    'keluargas.kode'
                )->where('keluarga_periodes.periode_kode', $selectedPeriodKode);
            }

            $persebaranKelurahan = $kelurahanQuery
                ->select(
                    'kelurahans.kelurahan_id',
                    'kelurahans.deskripsi as nama_kelurahan',
                    'kecamatans.deskripsi as nama_kecamatan',
                    DB::raw('COUNT(DISTINCT keluargas.id) as total_responden')
                )
                ->groupBy(
                    'kelurahans.kelurahan_id',
                    'kelurahans.deskripsi',
                    'kecamatans.deskripsi'
                )
                ->orderBy('kecamatans.deskripsi')
                ->orderBy('kelurahans.deskripsi')
                ->get();
        } else {
            $persebaranKelurahan = collect();
        }

        /*
        |--------------------------------------------------------------------------
        | Status Verifikasi Mengikuti Periode yang Dipilih
        |--------------------------------------------------------------------------
        */
        if (! $hasPart1Table) {
            $kuisionerTerakhir = new LengthAwarePaginator([], 0, 5);

            return view('admin.dashboard', compact(
                'totalResponden',
                'periodeAktif',
                'rekapPeriode',
                'periodeOptions',
                'selectedPeriodKode',
                'dataMasukHariIni',
                'persebaranKecamatan',
                'persebaranKelurahan',
                'kuisionerTerakhir'
            ) + [
                'jumlahDisetujui' => 0,
                'jumlahMenungguVerifikasi' => 0,
                'jumlahDitolak' => 0,
            ]);
        }

        $jumlahDisetujui = $this->linkedVerificationQuery($selectedPeriodKode)
            ->whereIn(
                new Expression('LOWER(TRIM(part1_keluarga.status))'),
                self::APPROVED_STATUSES
            )
            ->count();

        $jumlahDitolak = $this->linkedVerificationQuery($selectedPeriodKode)
            ->whereIn(
                new Expression('LOWER(TRIM(part1_keluarga.status))'),
                self::REJECTED_STATUSES
            )
            ->count();

        $jumlahMenungguVerifikasi = $this->pendingVerificationQuery(
            $selectedPeriodKode
        )->count();

        $kuisionerTerakhir = $this->pendingVerificationQuery($selectedPeriodKode)
            ->select('part1_keluarga.*')
            ->latest('part1_keluarga.updated_at')
            ->paginate(5)
            ->withQueryString();

        $keluargas = Keluarga::query()
            ->get(['kode', 'no_kk', 'nama_lengkap']);

        $kuisionerTerakhir->getCollection()->each(
            function (KeluargaPart1 $item) use ($keluargas): void {
                $keluarga = $keluargas->first(
                    static fn (Keluarga $family): bool => $family->kode === $item->keluarga_periode_kode
                        || $family->no_kk === $item->no_kk
                );

                $item->setAttribute('status_verifikasi', 'pending');
                $item->setAttribute(
                    'nama_responden',
                    $keluarga?->nama_lengkap ?? $item->nama_kepala_keluarga
                );
            }
        );

        return view('admin.dashboard', compact(
            'totalResponden',
            'periodeAktif',
            'rekapPeriode',
            'periodeOptions',
            'selectedPeriodKode',
            'dataMasukHariIni',
            'persebaranKecamatan',
            'persebaranKelurahan',
            'jumlahDisetujui',
            'jumlahMenungguVerifikasi',
            'jumlahDitolak',
            'kuisionerTerakhir'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Query Data yang Sudah Terhubung dengan Keluarga
    |--------------------------------------------------------------------------
    */

    private function linkedVerificationQuery(?string $selectedPeriodKode = null): Builder
    {
        $familyNumbers = Keluarga::query()
            ->get(['no_kk'])
            ->pluck('no_kk')
            ->filter()
            ->all();

        $legacyPart1Ids = KeluargaPart1::idsMatchingIdentifierValues(
            'no_kk',
            $familyNumbers
        );

        $query = KeluargaPart1::query()
            ->where(function (Builder $query) use ($legacyPart1Ids): void {
                $query
                    // Format data yang tersimpan saat ini: KLG-... di Part 1.
                    ->whereIn(
                        'part1_keluarga.keluarga_periode_kode',
                        Keluarga::query()->select('kode')
                    )
                    // Sebagian data lama mungkin menyimpan kode relasi KLP-...
                    ->orWhereIn(
                        'part1_keluarga.keluarga_periode_kode',
                        DB::table('keluarga_periodes')->select('kode')
                    )
                    ->orWhereIn(
                        'part1_keluarga.keluarga_periode_kode',
                        DB::table('keluarga_periodes')->select('keluarga_kode')
                    )
                    ->orWhereIn('part1_keluarga.id', $legacyPart1Ids);
            });

        if ($selectedPeriodKode !== null && $selectedPeriodKode !== '') {
            $familyNumbersForPeriod = DB::table('keluarga_periodes')
                ->where('periode_kode', $selectedPeriodKode)
                ->pluck('keluarga_kode')
                ->all();
            $part1IdsMatchingPeriodFamilyNumbers = KeluargaPart1::idsMatchingIdentifierValues(
                'no_kk',
                $familyNumbersForPeriod
            );

            $query->where(function (Builder $periodQuery) use (
                $selectedPeriodKode,
                $part1IdsMatchingPeriodFamilyNumbers
            ): void {
                $periodQuery->whereExists(function (QueryBuilder $relationQuery) use ($selectedPeriodKode): void {
                    $relationQuery->selectRaw('1')
                        ->from('keluarga_periodes as kp')
                        ->where('kp.periode_kode', $selectedPeriodKode)
                        ->where(function (QueryBuilder $matchQuery): void {
                            $matchQuery
                                ->whereColumn(
                                    'kp.keluarga_kode',
                                    'part1_keluarga.keluarga_periode_kode'
                                )
                                ->orWhereColumn(
                                    'kp.kode',
                                    'part1_keluarga.keluarga_periode_kode'
                                );
                        });
                });

                if ($part1IdsMatchingPeriodFamilyNumbers !== []) {
                    $periodQuery->orWhereIn(
                        'part1_keluarga.id',
                        $part1IdsMatchingPeriodFamilyNumbers
                    );
                }
            });
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | Query Data yang Menunggu Verifikasi
    |--------------------------------------------------------------------------
    */

    private function pendingVerificationQuery(?string $selectedPeriodKode = null): Builder
    {
        return $this->linkedVerificationQuery($selectedPeriodKode)
            ->where(function (Builder $query): void {
                // Status yang secara eksplisit sudah diajukan untuk verifikasi
                // tetap dihitung meskipun penanda kelengkapan bagian belum sinkron.
                $query->whereIn(
                    new Expression('LOWER(TRIM(part1_keluarga.status))'),
                    [
                        'pending',
                        'diajukan',
                        'menunggu',
                        'diperiksa',
                        'menunggu verifikasi',
                    ]
                )
                // Status kosong/selesai atau status lain hanya dihitung jika
                // kuisioner memang sudah lengkap, dan bukan status final/awal.
                    ->orWhere(function (Builder $otherStatusQuery): void {
                        $otherStatusQuery
                            ->where(function (Builder $statusQuery): void {
                                $statusQuery
                                    ->whereNull('part1_keluarga.status')
                                    ->orWhereNotIn(
                                        new Expression('LOWER(TRIM(part1_keluarga.status))'),
                                        array_merge(
                                            self::APPROVED_STATUSES,
                                            self::REJECTED_STATUSES,
                                            self::NOT_PROCESSED_STATUSES,
                                            [
                                                'pending',
                                                'diajukan',
                                                'menunggu',
                                                'diperiksa',
                                                'menunggu verifikasi',
                                            ]
                                        )
                                    );
                            })
                            ->where(function (Builder $completeQuery): void {
                                $completeQuery
                                    ->whereRaw(
                                        'LOWER(TRIM(part1_keluarga.status)) = ?',
                                        ['selesai']
                                    )
                                    ->orWhere(function (Builder $questionnaireQuery): void {
                                        $this->whereQuestionnaireComplete(
                                            $questionnaireQuery
                                        );
                                    });
                            });
                    });
            });
    }

    /*
    |--------------------------------------------------------------------------
    | Periksa Kelengkapan Kuisioner
    |--------------------------------------------------------------------------
    */

    private function whereQuestionnaireComplete(
        Builder $query
    ): void {
        $query
            ->where('part1_keluarga.current_part', '>=', 5)

            ->whereExists(function (QueryBuilder $partQuery): void {
                $partQuery->selectRaw('1')
                    ->from('part2_kondisi_rumahs')
                    ->whereColumn(
                        'part2_kondisi_rumahs.keluarga_periode_kode',
                        'part1_keluarga.keluarga_periode_kode'
                    );
            })

            ->whereExists(function (QueryBuilder $partQuery): void {
                $partQuery->selectRaw('1')
                    ->from('part3_keuangan_keluargas')
                    ->whereColumn(
                        'part3_keuangan_keluargas.keluarga_periode_kode',
                        'part1_keluarga.keluarga_periode_kode'
                    );
            })

            ->whereExists(function (QueryBuilder $partQuery): void {
                $partQuery->selectRaw('1')
                    ->from('part4_aset_keluargas')
                    ->whereColumn(
                        'part4_aset_keluargas.keluarga_periode_kode',
                        'part1_keluarga.keluarga_periode_kode'
                    );
            })

            ->whereExists(function (QueryBuilder $familyQuery): void {
                $this->addCompleteMembersSubquery($familyQuery);
            })

            ->whereExists(function (QueryBuilder $photoQuery): void {
                $photoQuery->selectRaw('1')
                    ->from('part5_foto_rumahs')
                    ->whereColumn(
                        'part5_foto_rumahs.keluarga_periode_kode',
                        'part1_keluarga.keluarga_periode_kode'
                    );
            });
    }

    /*
    |--------------------------------------------------------------------------
    | Periksa Kelengkapan Data Anggota Keluarga
    |--------------------------------------------------------------------------
    */

    private function addCompleteMembersSubquery(
        QueryBuilder $familyQuery
    ): void {
        $familiesByNumber = Keluarga::query()
            ->get(['kode', 'no_kk'])
            ->filter(static fn (Keluarga $keluarga): bool => filled($keluarga->no_kk))
            ->groupBy('no_kk');
        $legacyMatches = [];

        KeluargaPart1::query()
            ->select(['id', 'no_kk', 'keluarga_periode_kode'])
            ->lazyById(500)
            ->each(function (KeluargaPart1 $part1Record) use ($familiesByNumber, &$legacyMatches): void {
                foreach ($familiesByNumber->get($part1Record->no_kk, collect()) as $keluarga) {
                    if ($keluarga->kode !== $part1Record->keluarga_periode_kode) {
                        $legacyMatches[] = [
                            'part1_id' => $part1Record->id,
                            'keluarga_kode' => $keluarga->kode,
                        ];
                    }
                }
            });

        $familyQuery
            ->selectRaw('1')
            ->from('keluargas as complete_family')

            ->where(function (QueryBuilder $familyMatch) use ($legacyMatches): void {
                $familyMatch
                    ->whereColumn(
                        'complete_family.kode',
                        'part1_keluarga.keluarga_periode_kode'
                    );

                if ($legacyMatches !== []) {
                    $familyMatch->orWhere(function (QueryBuilder $legacyMatch) use ($legacyMatches): void {
                        foreach ($legacyMatches as $legacyPair) {
                            $legacyMatch->orWhere(function (QueryBuilder $pairQuery) use ($legacyPair): void {
                                $pairQuery
                                    ->where('part1_keluarga.id', $legacyPair['part1_id'])
                                    ->where('complete_family.kode', $legacyPair['keluarga_kode']);
                            });
                        }
                    });
                }
            })

            ->whereExists(function (QueryBuilder $memberQuery): void {
                $memberQuery->selectRaw('1')
                    ->from('keluarga_anggotas as complete_member')
                    ->whereColumn(
                        'complete_member.keluarga_kode',
                        'complete_family.kode'
                    );
            })

            ->whereNotExists(
                function (QueryBuilder $missingMemberQuery): void {
                    $missingMemberQuery
                        ->selectRaw('1')
                        ->from('keluarga_anggotas as missing_member')

                        ->whereColumn(
                            'missing_member.keluarga_kode',
                            'complete_family.kode'
                        )

                        ->whereNotExists(
                            function (QueryBuilder $part5Query): void {
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
}
