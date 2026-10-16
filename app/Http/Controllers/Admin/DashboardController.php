<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\KeluargaPart1;
use App\Models\Periode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
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

    public function index(): View
    {
        // Total data keluarga/responden
        $totalResponden = Keluarga::count();

        // Jumlah periode aktif
        $periodeAktif = Periode::where('status_periode', 'Aktif')->count();

        // Rekap periode tetap dipertahankan
        $rekapPeriode = Periode::orderByDesc('id')->get();

        // Data yang masuk hari ini
        $dataMasukHariIni = Keluarga::whereDate(
            'created_at',
            today()
        )->count();

        // Grafik persebaran kecamatan dari database
        $persebaranKecamatan = DB::table('kecamatans')
            ->leftJoin(
                'keluargas',
                'keluargas.kecamatan_id',
                '=',
                'kecamatans.kecamatan_id'
            )
            ->select(
                'kecamatans.deskripsi as nama_kecamatan',
                DB::raw('COUNT(keluargas.id) as total_responden')
            )
            ->groupBy(
                'kecamatans.kecamatan_id',
                'kecamatans.deskripsi'
            )
            ->orderBy('kecamatans.deskripsi')
            ->get();

        // Jumlah data yang disetujui
        $jumlahDisetujui = $this->linkedVerificationQuery()
            ->whereIn(
                new Expression('LOWER(TRIM(part1_keluarga.status))'),
                self::APPROVED_STATUSES
            )
            ->count();

        // Jumlah data yang ditolak
        $jumlahDitolak = $this->linkedVerificationQuery()
            ->whereIn(
                new Expression('LOWER(TRIM(part1_keluarga.status))'),
                self::REJECTED_STATUSES
            )
            ->count();

        // Jumlah data yang menunggu verifikasi
        $jumlahMenungguVerifikasi = $this->pendingVerificationQuery()
            ->count();

        // Mengambil nama responden untuk daftar kuisioner terakhir
        $namaRespondenQuery = Keluarga::query()
            ->select('nama_lengkap')
            ->where(function (Builder $query): void {
                $query
                    ->whereColumn(
                        'keluargas.kode',
                        'part1_keluarga.keluarga_periode_kode'
                    )
                    ->orWhereColumn(
                        'keluargas.no_kk',
                        'part1_keluarga.no_kk'
                    );
            })
            ->limit(1);

        // Daftar kuisioner terakhir tetap menggunakan pagination
        $kuisionerTerakhir = $this->linkedVerificationQuery()
            ->select('part1_keluarga.*')
            ->selectSub($namaRespondenQuery, 'nama_responden')
            ->latest('part1_keluarga.updated_at')
            ->paginate(5)
            ->withQueryString();

        $currentPageIds = $kuisionerTerakhir
            ->getCollection()
            ->modelKeys();

        $pendingIds = $currentPageIds === []
            ? []
            : $this->pendingVerificationQuery()
                ->whereIn('part1_keluarga.id', $currentPageIds)
                ->pluck('part1_keluarga.id')
                ->all();

        $kuisionerTerakhir->getCollection()->each(
            function (KeluargaPart1 $item) use ($pendingIds): void {
                $rawStatus = strtolower(trim((string) $item->status));

                $status = match (true) {
                    in_array(
                        $rawStatus,
                        self::APPROVED_STATUSES,
                        true
                    ) => 'approved',

                    in_array(
                        $rawStatus,
                        self::REJECTED_STATUSES,
                        true
                    ) => 'rejected',

                    in_array(
                        $rawStatus,
                        self::NOT_PROCESSED_STATUSES,
                        true
                    ) => 'not_processed',

                    in_array(
                        $item->getKey(),
                        $pendingIds,
                        true
                    ) => 'pending',

                    default => 'draft',
                };

                $item->setAttribute('status_verifikasi', $status);
            }
        );

        return view('admin.dashboard', compact(
            'totalResponden',
            'periodeAktif',
            'rekapPeriode',
            'dataMasukHariIni',
            'persebaranKecamatan',
            'jumlahDisetujui',
            'jumlahMenungguVerifikasi',
            'jumlahDitolak',
            'kuisionerTerakhir'
        ));
    }

    private function linkedVerificationQuery(): Builder
    {
        return KeluargaPart1::query()
            ->where(function (Builder $query): void {
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
            });
    }

    private function pendingVerificationQuery(): Builder
    {
        $query = $this->linkedVerificationQuery()
            ->where(function (Builder $query): void {
                $query
                    ->whereNull('part1_keluarga.status')
                    ->orWhereNotIn(
                        new Expression(
                            'LOWER(TRIM(part1_keluarga.status))'
                        ),
                        array_merge(
                            self::APPROVED_STATUSES,
                            self::REJECTED_STATUSES,
                            self::NOT_PROCESSED_STATUSES
                        )
                    );
            });

        $this->whereQuestionnaireComplete($query);

        return $query;
    }

    private function whereQuestionnaireComplete(Builder $query): void
    {
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

    private function addCompleteMembersSubquery(
        QueryBuilder $familyQuery
    ): void {
        $familyQuery
            ->selectRaw('1')
            ->from('keluargas as complete_family')
            ->where(function (QueryBuilder $familyMatch): void {
                $familyMatch
                    ->whereColumn(
                        'complete_family.kode',
                        'part1_keluarga.keluarga_periode_kode'
                    )
                    ->orWhereColumn(
                        'complete_family.no_kk',
                        'part1_keluarga.no_kk'
                    );
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
