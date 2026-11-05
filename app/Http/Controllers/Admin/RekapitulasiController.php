<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Periode;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RekapitulasiController extends Controller
{
    private const APPROVED_STATUSES = ['approved', 'disetujui'];

    private const REJECTED_STATUSES = ['rejected', 'reject', 'ditolak'];

    private const PENDING_STATUSES = [
        'pending',
        'menunggu',
        'menunggu_verifikasi',
        'menunggu verifikasi',
    ];

    private const NOT_PROCESSED_STATUSES = [
        'not_processed',
        'belum',
        'belum_didata',
        'belum didata',
        'belum diproses',
    ];

    public function index(Request $request): View|RedirectResponse
    {
        $periode = Periode::query()->find(session('periode_id'));

        if (! $periode) {
            return redirect()->route('periode.pilih', [
                'tujuan' => 'rekapitulasi.index',
            ]);
        }

        $requestedKecamatan = $request->query('kecamatan');
        $kecamatanForOptions = is_string($requestedKecamatan) && $requestedKecamatan !== ''
            ? $requestedKecamatan
            : null;
        $requestedKelurahan = $request->query('kelurahan');
        $kelurahanForOptions = is_string($requestedKelurahan) && $requestedKelurahan !== ''
            ? $requestedKelurahan
            : null;
        $kecamatanOptions = $this->locationOptions($periode->kode, 'kecamatan');
        $kelurahanOptions = $this->locationOptions(
            $periode->kode,
            'kelurahan',
            $kecamatanForOptions
        );
        $statusBuckets = $this->statusBuckets(
            $this->availableStatusValues(
                $periode->kode,
                $kecamatanForOptions,
                $kelurahanForOptions
            )
        );
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'kecamatan' => ['nullable', 'string', Rule::in($kecamatanOptions->all())],
            'kelurahan' => ['nullable', 'string', Rule::in($kelurahanOptions->all())],
            'status' => [
                'nullable',
                'string',
                Rule::in($statusBuckets->pluck('key')->all()),
            ],
        ]);

        $search = trim((string) ($validated['search'] ?? ''));
        $filters = [
            'search' => $search,
            'kecamatan' => $validated['kecamatan'] ?? null,
            'kelurahan' => $validated['kelurahan'] ?? null,
            'status' => $validated['status'] ?? null,
        ];

        $aggregatedRows = $this->buildAggregationQuery(
            $periode->kode,
            $statusBuckets,
            $filters
        )
            ->get()
            ->keyBy('nama_petugas');

        $petugasWithoutEntries = DB::table('users')
            ->where('role', 'petugas')
            ->whereNotNull('name')
            ->whereRaw("TRIM(name) <> ''")
            ->select('name')
            ->distinct()
            ->orderBy('name')
            ->pluck('name')
            ->filter(fn (string $name): bool => $search === ''
                || mb_stripos($name, $search) !== false)
            ->values();

        $rows = $this->combinePetugasRows($petugasWithoutEntries, $aggregatedRows, $statusBuckets);

        return view('admin.rekapitulasi.index', [
            'rows' => $rows,
            'periode' => $periode,
            'filters' => $filters,
            'kecamatanOptions' => $kecamatanOptions,
            'kelurahanOptions' => $kelurahanOptions,
            'statusBuckets' => $statusBuckets,
            'hasResults' => $aggregatedRows->isNotEmpty(),
        ]);
    }

    /**
     * @param  array{search: string, kecamatan: ?string, kelurahan: ?string, status: ?string}  $filters
     */
    private function buildAggregationQuery(
        string $periodCode,
        Collection $statusBuckets,
        array $filters
    ): Builder {
        $query = $this->buildRespondentQuery(
            $periodCode,
            $filters['kecamatan'],
            $filters['kelurahan']
        );

        if ($filters['search'] !== '') {
            $query->whereRaw(
                'LOWER(TRIM(first.created_by)) LIKE ?',
                ['%'.mb_strtolower($filters['search']).'%']
            );
        }

        if ($filters['status'] !== null) {
            $selectedBucket = $statusBuckets->firstWhere('key', $filters['status']);
            $this->whereStatusMatches($query, $selectedBucket['values']);
        }

        $query->selectRaw("COALESCE(NULLIF(TRIM(first.created_by), ''), 'Petugas tidak diketahui') AS nama_petugas")
            ->selectRaw('COUNT(*) AS total_responden');

        foreach ($statusBuckets as $index => $bucket) {
            $query->selectRaw(
                'SUM(CASE WHEN '.self::STATUS_VALUE_SQL.' IN ('
                    .implode(', ', array_fill(0, count($bucket['values']), '?'))
                    .") THEN 1 ELSE 0 END) AS status_count_{$index}",
                $bucket['values']
            );
        }

        return $query
            ->groupBy('first.created_by')
            ->orderBy('nama_petugas');
    }

    private const STATUS_VALUE_SQL = "LOWER(TRIM(COALESCE(latest.status, '')))";

    private function buildRespondentQuery(
        string $periodCode,
        ?string $kecamatan = null,
        ?string $kelurahan = null
    ): Builder {
        $firstAndLatestQuestionnaires = DB::table('part1_keluarga')
            ->select('keluarga_periode_kode')
            ->selectRaw('MIN(id) AS first_id')
            ->selectRaw('MAX(id) AS latest_id')
            ->whereNotNull('keluarga_periode_kode')
            ->where('keluarga_periode_kode', '<>', '')
            ->groupBy('keluarga_periode_kode');

        return DB::query()
            ->fromSub($firstAndLatestQuestionnaires, 'questionnaire_ids')
            ->join(
                'part1_keluarga as first',
                'first.id',
                '=',
                'questionnaire_ids.first_id'
            )
            ->join(
                'part1_keluarga as latest',
                'latest.id',
                '=',
                'questionnaire_ids.latest_id'
            )
            ->join(
                'keluarga_periodes as family_period',
                'family_period.kode',
                '=',
                'first.keluarga_periode_kode'
            )
            ->join(
                'keluargas as family',
                'family.kode',
                '=',
                'family_period.keluarga_kode'
            )
            ->where('family_period.periode_kode', $periodCode)
            ->when(
                $kecamatan !== null,
                fn (Builder $query): Builder => $query->whereRaw(
                    'TRIM(latest.kecamatan) = ?',
                    [$kecamatan]
                )
            )
            ->when(
                $kelurahan !== null,
                fn (Builder $query): Builder => $query->whereRaw(
                    'TRIM(latest.kelurahan) = ?',
                    [$kelurahan]
                )
            );
    }

    private function locationOptions(
        string $periodCode,
        string $column,
        ?string $kecamatan = null
    ): Collection {
        $query = $this->buildRespondentQuery(
            $periodCode,
            $column === 'kelurahan' ? $kecamatan : null
        );

        return $query
            ->whereNotNull("latest.{$column}")
            ->whereRaw("TRIM(latest.{$column}) <> ''")
            ->selectRaw("TRIM(latest.{$column}) AS location")
            ->distinct()
            ->orderBy('location')
            ->pluck('location');
    }

    private function availableStatusValues(
        string $periodCode,
        ?string $kecamatan,
        ?string $kelurahan
    ): Collection {
        return $this->buildRespondentQuery($periodCode, $kecamatan, $kelurahan)
            ->selectRaw(self::STATUS_VALUE_SQL.' AS status_value')
            ->distinct()
            ->orderBy('status_value')
            ->pluck('status_value');
    }

    /**
     * @param  Collection<int, string>  $statusValues
     * @return Collection<int, array{key: string, label: string, values: array<int, string>}>
     */
    private function statusBuckets(Collection $statusValues): Collection
    {
        $buckets = collect([
            ['key' => 'draft', 'label' => 'Draft', 'values' => ['draft']],
            [
                'key' => 'disetujui',
                'label' => 'Disetujui',
                'values' => self::APPROVED_STATUSES,
            ],
            ['key' => 'ditolak', 'label' => 'Ditolak', 'values' => self::REJECTED_STATUSES],
        ])->keyBy('key');

        foreach ($statusValues as $status) {
            $key = match (true) {
                in_array($status, self::APPROVED_STATUSES, true) => 'disetujui',
                in_array($status, self::REJECTED_STATUSES, true) => 'ditolak',
                in_array($status, self::PENDING_STATUSES, true) => 'menunggu_verifikasi',
                in_array($status, self::NOT_PROCESSED_STATUSES, true) => 'belum_didata',
                $status === 'draft' => 'draft',
                $status === '' => 'status_tidak_diketahui',
                default => 'status:'.$status,
            };

            if ($buckets->has($key)) {
                $bucket = $buckets->get($key);

                if (! in_array($status, $bucket['values'], true)) {
                    $bucket['values'][] = $status;
                    $buckets->put($key, $bucket);
                }

                continue;
            }

            $label = match ($key) {
                'menunggu_verifikasi' => 'Menunggu Verifikasi',
                'belum_didata' => 'Belum Didata',
                'status_tidak_diketahui' => 'Status Tidak Diketahui',
                default => mb_convert_case(
                    str_replace(['_', '-'], ' ', $status),
                    MB_CASE_TITLE,
                    'UTF-8'
                ),
            };

            $buckets->put($key, [
                'key' => $key,
                'label' => $label,
                'values' => [$status],
            ]);
        }

        return $buckets->values();
    }

    /**
     * @param  array<int, string>  $statusValues
     */
    private function whereStatusMatches(Builder $query, array $statusValues): void
    {
        $query->whereRaw(
            self::STATUS_VALUE_SQL.' IN ('.implode(', ', array_fill(0, count($statusValues), '?')).')',
            $statusValues
        );
    }

    /**
     * @param  Collection<int, string>  $petugasWithoutEntries
     * @param  Collection<string, object>  $aggregatedRows
     * @param  Collection<int, array{key: string, label: string, values: array<int, string>}>  $statusBuckets
     * @return Collection<int, array{nama_petugas: string, total_responden: int, status_counts: array<string, int>}>
     */
    private function combinePetugasRows(
        Collection $petugasWithoutEntries,
        Collection $aggregatedRows,
        Collection $statusBuckets
    ): Collection {
        return $petugasWithoutEntries
            ->concat($aggregatedRows->keys())
            ->unique()
            ->sort()
            ->values()
            ->map(function (string $namaPetugas) use ($aggregatedRows, $statusBuckets): array {
                $row = $aggregatedRows->get($namaPetugas);
                $statusCounts = [];

                foreach ($statusBuckets as $index => $bucket) {
                    $count = (int) ($row->{"status_count_{$index}"} ?? 0);
                    $statusCounts[$bucket['key']] = $count;
                }

                return [
                    'nama_petugas' => $namaPetugas,
                    'total_responden' => (int) ($row->total_responden ?? 0),
                    'status_counts' => $statusCounts,
                ];
            });
    }
}
