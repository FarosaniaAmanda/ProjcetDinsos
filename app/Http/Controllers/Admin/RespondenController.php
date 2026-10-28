<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\KeluargaAnggota;
use App\Models\KeluargaPart1;
use App\Models\KeluargaPeriode;
use App\Models\Periode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RespondenController extends Controller
{
    /**
     * Menampilkan daftar responden.
     */
    public function index(Request $request)
    {
        $periodeTerpilih = $this->getSelectedPeriode();

        if (! $periodeTerpilih) {
            return redirect()->route('periode.pilih', ['tujuan' => 'responden.index']);
        }

        $filters = $this->respondenFilters($request);
        $keluargas = $this->respondenQuery($periodeTerpilih, $filters)
            ->orderByDesc('keluargas.created_at')
            ->paginate(5)
            ->withQueryString();

        $kecamatans = DB::table('kecamatans')
            ->orderBy('deskripsi', 'asc')
            ->get();

        $kelurahans = DB::table('kelurahans')
            ->when(
                $filters['kecamatan_id'] !== '',
                fn ($query) => $query->where('kecamatan_id', $filters['kecamatan_id'])
            )
            ->orderBy('deskripsi', 'asc')
            ->get();

        $this->enrichResponden($keluargas->getCollection(), $kecamatans, $kelurahans);

        $totalResponden = KeluargaPeriode::query()
            ->where('periode_kode', $periodeTerpilih->kode)
            ->count();
        $respondenAktif = KeluargaPeriode::query()
            ->where('periode_kode', $periodeTerpilih->kode)
            ->where('is_active', true)
            ->count();
        $respondenNonaktif = $totalResponden - $respondenAktif;

        $periodes = Periode::query()
            ->orderByDesc('tgl_awal')
            ->orderByDesc('id')
            ->get();

        $importedFromKode = $request->query('imported_from');
        $importedFrom = is_string($importedFromKode) && $importedFromKode !== ''
            ? Periode::query()->where('kode', $importedFromKode)->first()
            : null;
        $respondenDiambil = collect();

        if ($importedFrom) {
            $sourceFamilyCodes = KeluargaPeriode::query()
                ->where('periode_kode', $importedFrom->kode)
                ->where('is_active', true)
                ->select('keluarga_kode')
                ->distinct();

            $respondenDiambil = $this->respondenQuery($periodeTerpilih, $filters)
                ->whereIn('keluargas.kode', $sourceFamilyCodes)
                ->orderBy('keluargas.nama_lengkap')
                ->get();

            $this->enrichResponden($respondenDiambil, $kecamatans, $kelurahans);
        }

        return view(
            'admin.responden.index',
            compact(
                'keluargas',
                'kecamatans',
                'kelurahans',
                'periodes',
                'periodeTerpilih',
                'totalResponden',
                'respondenAktif',
                'respondenNonaktif',
                'respondenDiambil',
                'importedFrom',
                'filters'
            )
        );
    }

    /**
     * Add respondents from a source period to the period currently selected by the user.
     */
    public function ambilData(Request $request)
    {
        $periodeTujuan = $this->getSelectedPeriode();

        if (! $periodeTujuan) {
            return redirect()->route('periode.pilih', ['tujuan' => 'responden.index']);
        }

        $validated = $request->validate([
            'periode_sumber' => ['required', 'string', 'exists:periodes,kode'],
        ]);

        $periodeSumber = Periode::query()
            ->where('kode', $validated['periode_sumber'])
            ->firstOrFail();

        $jumlahDiambil = DB::transaction(function () use ($periodeSumber, $periodeTujuan): int {
            $kodeKeluargaSumber = KeluargaPeriode::query()
                ->where('periode_kode', $periodeSumber->kode)
                ->where('is_active', true)
                ->select('keluarga_kode')
                ->distinct()
                ->pluck('keluarga_kode');

            $kodeKeluargaSudahAda = KeluargaPeriode::query()
                ->where('periode_kode', $periodeTujuan->kode)
                ->whereIn('keluarga_kode', $kodeKeluargaSumber)
                ->pluck('keluarga_kode')
                ->all();

            $kodeKeluargaBaru = Keluarga::query()
                ->whereIn('kode', $kodeKeluargaSumber)
                ->whereNotIn('kode', $kodeKeluargaSudahAda)
                ->pluck('kode');

            if ($kodeKeluargaBaru->isEmpty()) {
                return 0;
            }

            $createdBy = auth()->user()?->name ?? 'admin';
            $now = now();

            foreach ($kodeKeluargaBaru->chunk(500) as $familyCodes) {
                $relations = $familyCodes->map(static fn (string $familyCode): array => [
                    'kode' => 'KLP-'.strtoupper(Str::random(10)),
                    'keluarga_kode' => $familyCode,
                    'periode_kode' => $periodeTujuan->kode,
                    'status_kuisoner' => 'belum',
                    'is_active' => true,
                    'created_by' => $createdBy,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all();

                DB::table('keluarga_periodes')->insert($relations);
            }

            return $kodeKeluargaBaru->count();
        });

        $namaPeriodeSumber = Str::startsWith(
            mb_strtolower($periodeSumber->nama),
            'periode '
        )
            ? $periodeSumber->nama
            : 'Periode '.$periodeSumber->nama;

        return redirect()
            ->route('responden.index', ['imported_from' => $periodeSumber->kode])
            ->with(
                'success',
                "Data berhasil diambil dari {$namaPeriodeSumber} sebanyak {$jumlahDiambil} responden."
            );
    }

    /**
     * Update one respondent's active status for the selected period.
     */
    public function updateStatus(Request $request, int $id)
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);
        $periodeTerpilih = $this->getSelectedPeriode();

        if (! $periodeTerpilih) {
            return response()->json([
                'message' => 'Pilih periode sebelum memperbarui status responden.',
            ], 409);
        }

        $keluarga = Keluarga::query()->findOrFail($id);
        $relation = KeluargaPeriode::query()
            ->where('keluarga_kode', $keluarga->kode)
            ->where('periode_kode', $periodeTerpilih->kode)
            ->firstOrFail();
        $relation->is_active = (bool) $validated['is_active'];
        $relation->updated_by = auth()->user()?->name ?? 'admin';
        $relation->save();

        $totalResponden = KeluargaPeriode::query()
            ->where('periode_kode', $periodeTerpilih->kode)
            ->count();
        $respondenAktif = KeluargaPeriode::query()
            ->where('periode_kode', $periodeTerpilih->kode)
            ->where('is_active', true)
            ->count();

        return response()->json([
            'success' => true,
            'is_active' => (bool) $relation->is_active,
            'total_responden' => $totalResponden,
            'responden_aktif' => $respondenAktif,
            'responden_nonaktif' => $totalResponden - $respondenAktif,
            'message' => 'Status responden berhasil diperbarui.',
        ]);
    }

    /**
     * Update statuses for all respondents matching the active list filters.
     */
    public function updateBulkStatus(Request $request)
    {
        $validated = $request->validate([
            'target_status' => ['required', 'in:aktif,nonaktif'],
            'search' => ['nullable', 'string', 'max:255'],
            'kecamatan_id' => ['nullable', 'string', 'max:13'],
            'kelurahan_id' => ['nullable', 'string', 'max:13'],
            'status' => ['nullable', 'in:aktif,nonaktif,semua'],
            'imported_from' => ['nullable', 'string', 'exists:periodes,kode'],
        ]);
        $periodeTerpilih = $this->getSelectedPeriode();

        if (! $periodeTerpilih) {
            return redirect()->route('periode.pilih', ['tujuan' => 'responden.index']);
        }

        $filters = $this->respondenFilters($request);
        $targetStatus = $validated['target_status'] === 'aktif';
        $jumlahDiubah = 0;

        DB::transaction(function () use ($periodeTerpilih, $filters, $targetStatus, &$jumlahDiubah): void {
            $this->respondenQuery($periodeTerpilih, $filters)
                ->select(['keluargas.id', 'keluargas.kode'])
                ->chunkById(500, function ($families) use (
                    $periodeTerpilih,
                    $targetStatus,
                    &$jumlahDiubah
                ): void {
                    $jumlahDiubah += DB::table('keluarga_periodes')
                        ->where('periode_kode', $periodeTerpilih->kode)
                        ->whereIn('keluarga_kode', $families->pluck('kode'))
                        ->where('is_active', '!=', $targetStatus)
                        ->update([
                            'is_active' => $targetStatus,
                            'updated_by' => auth()->user()?->name ?? 'admin',
                            'updated_at' => now(),
                        ]);
                }, 'keluargas.id', 'id');
        });

        $redirectFilters = $filters;

        if ($validated['imported_from'] ?? null) {
            $redirectFilters['imported_from'] = $validated['imported_from'];
        }

        return redirect()
            ->route('responden.index', $redirectFilters)
            ->with(
                'success',
                "{$jumlahDiubah} status responden berhasil diperbarui."
            );
    }

    /**
     * Update the status-related data shown for a page of respondents.
     */
    private function enrichResponden($respondents, $kecamatans, $kelurahans): void
    {
        $kecamatansById = $kecamatans->keyBy('kecamatan_id');
        $kelurahansById = $kelurahans->keyBy('kelurahan_id');

        foreach ($respondents as $keluarga) {
            $keluarga->nama_kepala_keluarga = $keluarga->nama_lengkap;
            $keluarga->kecamatan = $kecamatansById->get($keluarga->kecamatan_id)?->deskripsi;
            $keluarga->kelurahan = $kelurahansById->get($keluarga->kelurahan_id)?->deskripsi;
            $keluarga->jml_keluarga = $keluarga->anggota->count();
        }
    }

    /**
     * Build the selected-period respondent query and apply every list filter.
     *
     * @param  array{search: string, kecamatan_id: string, kelurahan_id: string, status: string}  $filters
     */
    private function respondenQuery(Periode $periode, array $filters): Builder
    {
        $query = Keluarga::query()
            ->join('keluarga_periodes as responden_period', function ($join) use ($periode): void {
                $join->on('responden_period.keluarga_kode', '=', 'keluargas.kode')
                    ->where('responden_period.periode_kode', '=', $periode->kode);
            })
            ->select('keluargas.*', 'responden_period.is_active as is_active')
            ->with([
                'anggota' => function ($query): void {
                    $query
                        ->where('status_keluarga', '!=', 'Kepala Keluarga')
                        ->orderBy('id');
                },
            ]);

        if ($filters['kecamatan_id'] !== '') {
            $query->where('keluargas.kecamatan_id', $filters['kecamatan_id']);
        }

        if ($filters['kelurahan_id'] !== '') {
            $query->where('keluargas.kelurahan_id', $filters['kelurahan_id']);
        }

        if ($filters['status'] !== 'semua') {
            $query->where(
                'responden_period.is_active',
                $filters['status'] === 'aktif'
            );
        }

        if ($filters['search'] !== '') {
            $searchPattern = '/'.str_replace(
                ['%', '_'],
                ['[\s\S]*', '[\s\S]'],
                preg_quote('%'.$filters['search'].'%', '/')
            ).'/iu';

            $sensitiveFamilyIds = (clone $query)
                ->get()
                ->filter(function (Keluarga $keluarga) use ($searchPattern): bool {
                    $matchesIdentifier = static fn (mixed $value): bool => preg_match(
                        $searchPattern,
                        (string) $value
                    ) === 1;

                    return $matchesIdentifier($keluarga->no_kk)
                        || $matchesIdentifier($keluarga->nik)
                        || $keluarga->anggota->contains(
                            static fn (KeluargaAnggota $anggota): bool => $matchesIdentifier($anggota->nik)
                        );
                })
                ->pluck('id')
                ->all();
            $keyword = '%'.$filters['search'].'%';

            $query->where(function (Builder $searchQuery) use ($keyword, $sensitiveFamilyIds): void {
                $searchQuery
                    ->whereIn('keluargas.id', $sensitiveFamilyIds)
                    ->orWhere('keluargas.nama_lengkap', 'like', $keyword)
                    ->orWhere('keluargas.alamat_lengkap', 'like', $keyword)
                    ->orWhereHas('anggota', function (Builder $memberQuery) use ($keyword): void {
                        $memberQuery->where('nama_lengkap', 'like', $keyword);
                    });
            });
        }

        return $query;
    }

    /**
     * @return array{search: string, kecamatan_id: string, kelurahan_id: string, status: string}
     */
    private function respondenFilters(Request $request): array
    {
        return [
            'search' => is_string($request->input('search'))
                ? trim($request->input('search'))
                : '',
            'kecamatan_id' => is_string($request->input('kecamatan_id'))
                ? trim($request->input('kecamatan_id'))
                : '',
            'kelurahan_id' => is_string($request->input('kelurahan_id'))
                ? trim($request->input('kelurahan_id'))
                : '',
            'status' => in_array($request->input('status', 'semua'), ['aktif', 'nonaktif', 'semua'], true)
                ? $request->input('status', 'semua')
                : 'semua',
        ];
    }

    /**
     * Menampilkan halaman tambah responden.
     */
    public function create()
    {
        if (! $this->getSelectedPeriode()) {
            return redirect()->route('periode.pilih', ['tujuan' => 'responden.index']);
        }

        $kecamatans = DB::table('kecamatans')
            ->orderBy('deskripsi', 'asc')
            ->get();

        return view(
            'admin.responden.create',
            compact('kecamatans')
        );
    }

    /**
     * Memeriksa duplikasi Nomor KK atau NIK untuk notifikasi langsung di form.
     * Route: POST /responden/cek-duplikat
     */
    public function cekDuplikat(Request $request)
    {
        $request->validate([
            'jenis' => ['required', 'in:kk,nik'],
            'nomor' => ['required', 'digits:16'],
            'ignore_id' => ['nullable', 'integer'],
        ]);

        $jenis = $request->input('jenis');
        $nomor = (string) $request->input('nomor');
        $ignoreId = $request->filled('ignore_id') ? (int) $request->input('ignore_id') : null;

        if ($jenis === 'kk') {
            $terdaftar = Keluarga::query()
                ->get()
                ->contains(
                    static fn (Keluarga $keluarga): bool => $keluarga->no_kk === $nomor
                        && $keluarga->id !== $ignoreId
                );

            return response()->json([
                'exists' => $terdaftar,
                'message' => $terdaftar ? 'Nomor KK sudah terdaftar.' : '',
            ]);
        }

        $terdaftar = Keluarga::query()
            ->get()
            ->contains(
                static fn (Keluarga $keluarga): bool => $keluarga->nik === $nomor
                    && $keluarga->id !== $ignoreId
            );

        if (! $terdaftar) {
            $keluargaSaatIni = $ignoreId !== null
                ? Keluarga::find($ignoreId)
                : null;

            $terdaftar = KeluargaAnggota::query()
                ->get()
                ->contains(
                    static fn (KeluargaAnggota $anggota): bool => $anggota->nik === $nomor
                        && (
                            $keluargaSaatIni === null
                            || $anggota->keluarga_kode !== $keluargaSaatIni->kode
                        )
                );
        }

        return response()->json([
            'exists' => $terdaftar,
            'message' => $terdaftar ? 'NIK sudah terdaftar.' : '',
        ]);
    }

    /**
     * Menyimpan data responden baru.
     */
    public function store(Request $request)
    {
        $periodeTerpilih = $this->getSelectedPeriode();

        if (! $periodeTerpilih) {
            return redirect()->route('periode.pilih', ['tujuan' => 'responden.index']);
        }

        /*
        |--------------------------------------------------------------------------
        | Samakan nomor KK
        |--------------------------------------------------------------------------
        */

        if (
            ! $request->filled('nomor_kk')
            && $request->filled('no_kk')
        ) {
            $request->merge([
                'nomor_kk' => $request->input('no_kk'),
            ]);
        }

        if (
            ! $request->filled('no_kk')
            && $request->filled('nomor_kk')
        ) {
            $request->merge([
                'no_kk' => $request->input('nomor_kk'),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'nomor_kk' => [
                'required',
                'digits:16',
            ],

            'nik_kepala_keluarga' => [
                'required',
                'digits:16',
            ],

            'nama_kepala_keluarga' => [
                'required',
                'string',
                'max:255',
            ],

            'anggota' => [
                'nullable',
                'array',
                'max:19',
            ],

            'anggota.*.nik' => [
                'required',
                'digits:16',
            ],

            'anggota.*.nama_lengkap' => [
                'required',
                'string',
                'max:255',
            ],

            'anggota.*.status_keluarga' => [
                'required',
                'string',
                'max:255',
            ],

            'anggota.*.status_keluarga_lainnya' => [
                'nullable',
                'string',
                'max:255',
            ],

            'provinsi' => [
                'nullable',
                'string',
                'max:255',
            ],

            'daerah' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kecamatan_id' => [
                'nullable',
                'string',
                'max:13',
            ],

            'kelurahan_id' => [
                'nullable',
                'string',
                'max:13',
            ],

            'kode_pos' => [
                'nullable',
                'string',
                'max:10',
            ],

            'rt' => [
                'nullable',
                'string',
                'max:5',
            ],

            'rw' => [
                'nullable',
                'string',
                'max:5',
            ],

            'alamat_lengkap' => [
                'nullable',
                'string',
                'max:255',
            ],

            'geotangging' => [
                'nullable',
                'string',
                'max:100',
            ],
        ], [
            'nomor_kk.unique' => 'Nomor KK sudah terdaftar. Data responden tidak dapat disimpan.',
            'nomor_kk.digits' => 'Nomor KK harus terdiri dari 16 digit.',
            'nik_kepala_keluarga.digits' => 'NIK harus terdiri dari 16 digit.',
            'anggota.*.nik.digits' => 'NIK anggota harus terdiri dari 16 digit.',
        ]);

        $this->validasiNomorKKUnik((string) $request->input('nomor_kk'));
        $this->validasiNIKUnik($request);

        DB::transaction(function () use ($request, $periodeTerpilih) {

            $createdBy =
                auth()->user()?->name ?? 'admin';

            /*
            |--------------------------------------------------------------------------
            | Generate kode keluarga
            |--------------------------------------------------------------------------
            */

            $kodeKeluarga =
                'KLG-'.strtoupper(Str::random(10));

            /*
            |--------------------------------------------------------------------------
            | Cari NIK Kepala Keluarga
            |--------------------------------------------------------------------------
            */

            $nikKepalaKeluarga =
                $request->nik_kepala_keluarga;

            /*
            |--------------------------------------------------------------------------
            | Siapkan alamat lengkap + geotagging
            |--------------------------------------------------------------------------
            */

            // Koordinat disimpan terpisah pada kolom keluargas.geotagging.
            $alamatLengkap =
                $this->hapusGeotagDariAlamat(
                    $request->alamat_lengkap
                );

            /*
            |--------------------------------------------------------------------------
            | Simpan keluarga
            |--------------------------------------------------------------------------
            */

            $keluarga = Keluarga::create([
                'kode' => $kodeKeluarga,

                'no_kk' => $request->nomor_kk,

                'nik' => $nikKepalaKeluarga,

                'nama_lengkap' => $request->nama_kepala_keluarga,

                'status_keluarga' => 'Kepala Keluarga',

                'kecamatan_id' => $request->kecamatan_id,

                'kelurahan_id' => $request->kelurahan_id,

                'kode_pos' => $request->kode_pos,

                'alamat_lengkap' => $alamatLengkap,

                'tanggal_entri' => now()->toDateString(),

                'created_by' => $createdBy,
            ]);

            DB::table('keluarga_periodes')->insert([
                'kode' => 'KLP-'.strtoupper(Str::random(10)),
                'keluarga_kode' => $keluarga->kode,
                'periode_kode' => $periodeTerpilih->kode,
                'status_kuisoner' => 'belum',
                'is_active' => true,
                'created_by' => $createdBy,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // RT/RW berada langsung di tabel keluargas.
            DB::table('keluargas')
                ->where('id', $keluarga->id)
                ->update([
                    'rt' => $request->input('rt'),
                    'rw' => $request->input('rw'),
                    'geotagging' => $request->input('geotangging'),
                    'updated_at' => now(),
                ]);

            /*
            |--------------------------------------------------------------------------
            | Simpan anggota keluarga
            |--------------------------------------------------------------------------
            */

            foreach (
                $request->input('anggota', []) as $anggota
            ) {

                $statusKeluarga =
                    $anggota['status_keluarga'] ?? '';

                /*
                |--------------------------------------------------------------------------
                | Jika status = Lainnya
                |--------------------------------------------------------------------------
                */

                if ($statusKeluarga === 'Lainnya') {

                    $customStatus =
                        trim(
                            (string) (
                                $anggota[
                                    'status_keluarga_lainnya'
                                ] ?? ''
                            )
                        );

                    $statusKeluarga =
                        $customStatus !== ''
                            ? $customStatus
                            : 'Lainnya';
                }

                KeluargaAnggota::create([
                    'kode' => 'ANG-'.
                        strtoupper(
                            Str::random(10)
                        ),

                    'keluarga_kode' => $keluarga->kode,

                    'nik' => $anggota['nik'],

                    'nama_lengkap' => $anggota['nama_lengkap'],

                    'status_keluarga' => $statusKeluarga,

                    'created_by' => $createdBy,
                ]);
            }
        });

        return redirect()
            ->route('responden.index')
            ->with(
                'success',
                'Data responden berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan halaman edit responden.
     */
    public function edit($id)
    {
        if (! $this->getSelectedPeriode()) {
            return redirect()->route('periode.pilih', ['tujuan' => 'responden.index']);
        }

        $keluarga =
            Keluarga::with([
                'anggota',
            ])
                ->findOrFail($id);

        $kecamatans = DB::table('kecamatans')
            ->orderBy('deskripsi', 'asc')
            ->get();

        return view(
            'admin.responden.edit',
            compact(
                'keluarga',
                'kecamatans'
            )
        );
    }

    /**
     * Mengambil data responden untuk modal edit.
     */
    public function editData($id)
    {
        if (! $this->getSelectedPeriode()) {
            return response()->json([
                'message' => 'Pilih periode sebelum mengedit data responden.',
            ], 409);
        }

        $keluarga =
            Keluarga::with('anggota')
                ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Ambil nama kecamatan
        |--------------------------------------------------------------------------
        */

        $kecamatan = null;

        if ($keluarga->kecamatan_id) {

            $kecamatan =
                DB::table('kecamatans')
                    ->where(
                        'kecamatan_id',
                        $keluarga->kecamatan_id
                    )
                    ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil nama kelurahan
        |--------------------------------------------------------------------------
        */

        $kelurahan = null;

        if ($keluarga->kelurahan_id) {

            $kelurahan =
                DB::table('kelurahans')
                    ->where(
                        'kelurahan_id',
                        $keluarga->kelurahan_id
                    )
                    ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil koordinat dari alamat_lengkap
        |--------------------------------------------------------------------------
        */

        $geotangging =
            $keluarga->geotagging
            ?: $this->ambilGeotagDariAlamat(
                $keluarga->alamat_lengkap
            );

        /*
        |--------------------------------------------------------------------------
        | Ambil alamat tanpa koordinat
        |--------------------------------------------------------------------------
        */

        $alamatBersih =
            $this->hapusGeotagDariAlamat(
                $keluarga->alamat_lengkap
            );

        /*
        |--------------------------------------------------------------------------
        | Urutkan anggota
        |--------------------------------------------------------------------------
        */

        $anggota =
            $keluarga->anggota
                ->where(
                    'status_keluarga',
                    '!=',
                    'Kepala Keluarga'
                )
                ->sortBy(
                    function ($anggota) {

                        $statusPriority = [
                            'Kepala Keluarga' => 1,
                            'Istri' => 2,
                            'Suami' => 3,
                            'Anak' => 4,
                            'Orang Tua' => 5,
                            'Saudara' => 6,
                            'Famili' => 7,
                        ];

                        $status =
                            (string) (
                                $anggota->status_keluarga
                                ?? ''
                            );

                        return (
                            $statusPriority[$status]
                            ?? 999
                        ) * 1000000
                        + (
                            $anggota->id
                            ?? 0
                        );
                    }
                )
                ->map(
                    function ($anggota) {

                        $statusNormal = [
                            'Kepala Keluarga',
                            'Istri',
                            'Suami',
                            'Anak',
                            'Orang Tua',
                            'Saudara',
                            'Famili',
                        ];

                        $status =
                            (string) (
                                $anggota->status_keluarga
                                ?? ''
                            );

                        return [
                            'id' => $anggota->id,

                            'kode' => $anggota->kode,

                            'nik' => $anggota->nik,

                            'nama_lengkap' => $anggota->nama_lengkap,

                            'status_keluarga' => $status,

                            'status_keluarga_lainnya' => $status !== ''
                                && ! in_array(
                                    $status,
                                    $statusNormal,
                                    true
                                )
                                    ? $status
                                    : '',
                        ];
                    }
                )
                ->values();

        $statusKuisioner = $this->getStatusKuisionerUntukKeluarga($keluarga);
        $anggotaTerkunci = $this->isAnggotaTerkunci($statusKuisioner);

        return response()->json([
            'anggota_terkunci' => $anggotaTerkunci,
            'anggota_penambahan_terkunci' => $this->isPenambahanAnggotaTerkunci($statusKuisioner),
            'status_kuisioner' => $statusKuisioner,
            'id' => $keluarga->id,

            'kode' => $keluarga->kode,

            'no_kk' => $keluarga->no_kk,

            'nik' => $keluarga->nik,

            'nik_kepala_keluarga' => $keluarga->nik,

            'nama_lengkap' => $keluarga->nama_lengkap,

            'nama_kepala_keluarga' => $keluarga->nama_lengkap,

            'status_keluarga' => $keluarga->status_keluarga,

            'provinsi' => 'Jawa Timur',

            'daerah' => 'Kota Pasuruan',

            'kecamatan_id' => $keluarga->kecamatan_id,

            'kecamatan' => $kecamatan?->deskripsi,

            'kelurahan_id' => $keluarga->kelurahan_id,

            'kelurahan' => $kelurahan?->deskripsi,

            'kode_pos' => $keluarga->kode_pos,

            'rt' => $keluarga->rt,

            'rw' => $keluarga->rw,

            'rt_rw' => trim((string) ($keluarga->rt ?? '').'/'.(string) ($keluarga->rw ?? ''), '/'),

            'alamat_lengkap' => $alamatBersih,

            /*
            |--------------------------------------------------------------------------
            | Kirim geotagging ke JavaScript
            |--------------------------------------------------------------------------
            */

            'geotangging' => $geotangging,

            'jml_keluarga' => $keluarga->anggota
                ->where(
                    'status_keluarga',
                    '!=',
                    'Kepala Keluarga'
                )
                ->count(),

            'anggota' => $anggota,
        ]);
    }

    /**
     * Mengambil Kelurahan berdasarkan Kecamatan.
     */
    public function getKelurahan($kecamatanId)
    {
        $kecamatan =
            DB::table('kecamatans')
                ->where(
                    'kecamatan_id',
                    $kecamatanId
                )
                ->first();

        if (! $kecamatan) {

            return response()->json([
                'success' => false,

                'message' => 'Kecamatan tidak ditemukan.',

                'data' => [],
            ], 404);
        }

        $kelurahans =
            DB::table('kelurahans')
                ->where(
                    'kecamatan_id',
                    $kecamatanId
                )
                ->orderBy(
                    'deskripsi',
                    'asc'
                )
                ->get([
                    'kelurahan_id',
                    'kecamatan_id',
                    'deskripsi',
                ]);

        return response()->json([
            'success' => true,

            'data' => $kelurahans,
        ]);
    }

    /**
     * Memperbarui data responden.
     */
    public function update(
        Request $request,
        $id
    ) {
        if (! $this->getSelectedPeriode()) {
            return redirect()->route('periode.pilih', ['tujuan' => 'responden.index']);
        }

        /*
        |--------------------------------------------------------------------------
        | Samakan nomor KK
        |--------------------------------------------------------------------------
        */

        if (
            ! $request->filled('nomor_kk')
            && $request->filled('no_kk')
        ) {
            $request->merge([
                'nomor_kk' => $request->input('no_kk'),
            ]);
        }

        if (
            ! $request->filled('no_kk')
            && $request->filled('nomor_kk')
        ) {
            $request->merge([
                'no_kk' => $request->input('nomor_kk'),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'nomor_kk' => [
                'required',
                'digits:16',
            ],

            'nik_kepala_keluarga' => [
                'required',
                'digits:16',
            ],

            'nama_kepala_keluarga' => [
                'required',
                'string',
                'max:255',
            ],

            'anggota' => [
                'nullable',
                'array',
                'max:19',
            ],

            'anggota.*.nik' => [
                'required',
                'digits:16',
            ],

            'anggota.*.nama_lengkap' => [
                'required',
                'string',
                'max:255',
            ],

            'anggota.*.status_keluarga' => [
                'required',
                'string',
                'max:255',
            ],

            'anggota.*.status_keluarga_lainnya' => [
                'nullable',
                'string',
                'max:255',
            ],

            'provinsi' => [
                'nullable',
                'string',
                'max:255',
            ],

            'daerah' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kecamatan_id' => [
                'nullable',
                'string',
                'max:13',
            ],

            'kelurahan_id' => [
                'nullable',
                'string',
                'max:13',
            ],

            'kode_pos' => [
                'nullable',
                'string',
                'max:10',
            ],

            'rt' => [
                'nullable',
                'string',
                'max:5',
            ],

            'rw' => [
                'nullable',
                'string',
                'max:5',
            ],

            'alamat_lengkap' => [
                'nullable',
                'string',
                'max:255',
            ],

            'geotangging' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);

        $this->validasiNomorKKUnik((string) $request->input('nomor_kk'), (int) $id);
        $this->validasiNIKUnik($request, (int) $id);

        DB::transaction(function () use (
            $request,
            $id
        ) {

            $updatedBy =
                auth()->user()?->name ?? 'admin';

            /*
            |--------------------------------------------------------------------------
            | Cari keluarga
            |--------------------------------------------------------------------------
            */

            $keluarga =
                Keluarga::findOrFail($id);

            $statusKuisioner = $this->getStatusKuisionerUntukKeluarga($keluarga);
            $anggotaTerkunci = $this->isAnggotaTerkunci($statusKuisioner);
            $anggotaMasuk = $request->input('anggota', []);

            /*
            |--------------------------------------------------------------------------
            | Cari NIK Kepala Keluarga
            |--------------------------------------------------------------------------
            */

            $nikKepalaKeluarga =
                $request->nik_kepala_keluarga;

            /*
            |--------------------------------------------------------------------------
            | Gabungkan alamat + geotagging
            |--------------------------------------------------------------------------
            */

            // Koordinat disimpan terpisah pada kolom keluargas.geotagging.
            $alamatLengkap =
                $this->hapusGeotagDariAlamat(
                    $request->alamat_lengkap
                );

            /*
            |--------------------------------------------------------------------------
            | Update keluarga
            |--------------------------------------------------------------------------
            */

            $keluarga->update([
                'no_kk' => $request->nomor_kk,

                'nik' => $nikKepalaKeluarga,

                'nama_lengkap' => $request->nama_kepala_keluarga,

                'status_keluarga' => 'Kepala Keluarga',

                'kecamatan_id' => $request->kecamatan_id,

                'kelurahan_id' => $request->kelurahan_id,

                'kode_pos' => $request->kode_pos,

                'alamat_lengkap' => $alamatLengkap,

                'updated_by' => $updatedBy,
            ]);

            // RT/RW berada langsung di tabel keluargas.
            DB::table('keluargas')
                ->where('id', $keluarga->id)
                ->update([
                    'rt' => $request->input('rt'),
                    'rw' => $request->input('rw'),
                    'geotagging' => $request->input('geotangging'),
                    'updated_at' => now(),
                ]);

            /*
            |--------------------------------------------------------------------------
            | Simpan anggota terbaru
            |--------------------------------------------------------------------------
            */

            if ($this->isPenambahanAnggotaTerkunci($statusKuisioner)) {
                foreach ($anggotaMasuk as $anggota) {
                    if (empty($anggota['id'])) {
                        throw ValidationException::withMessages([
                            'anggota' => 'Anggota baru tidak dapat ditambahkan karena kuisioner sedang menunggu verifikasi atau telah disetujui.',
                        ]);
                    }
                }
            }

            if (! $anggotaTerkunci) {
                $idAnggotaDikirim = collect($anggotaMasuk)
                    ->pluck('id')
                    ->filter()
                    ->map(fn ($idAnggota) => (int) $idAnggota)
                    ->values()
                    ->all();

                $keluarga->anggota()
                    ->where('status_keluarga', '!=', 'Kepala Keluarga')
                    ->when(
                        ! empty($idAnggotaDikirim),
                        fn ($query) => $query->whereNotIn('id', $idAnggotaDikirim),
                        fn ($query) => $query
                    )
                    ->delete();
            }

            foreach ($anggotaMasuk as $anggota) {
                $statusKeluarga = $anggota['status_keluarga'] ?? '';

                if ($statusKeluarga === 'Lainnya') {
                    $customStatus = trim((string) ($anggota['status_keluarga_lainnya'] ?? ''));
                    $statusKeluarga = $customStatus !== '' ? $customStatus : 'Lainnya';
                }

                $dataAnggota = [
                    'nik' => $anggota['nik'],
                    'nama_lengkap' => $anggota['nama_lengkap'],
                    'status_keluarga' => $statusKeluarga,
                    'updated_by' => $updatedBy,
                ];

                if (! empty($anggota['id'])) {
                    $anggotaLama = $keluarga->anggota()
                        ->where('id', $anggota['id'])
                        ->first();

                    // ID harus milik keluarga yang sedang diedit.
                    if ($anggotaLama) {
                        $anggotaLama->update($dataAnggota);

                        continue;
                    }

                    // Jangan izinkan ID anggota keluarga lain digunakan.
                    throw ValidationException::withMessages([
                        'anggota' => 'Data anggota tidak sesuai dengan keluarga yang sedang diedit.',
                    ]);
                }

                KeluargaAnggota::create(array_merge($dataAnggota, [
                    'kode' => 'ANG-'.strtoupper(Str::random(10)),
                    'keluarga_kode' => $keluarga->kode,
                    'created_by' => $updatedBy,
                ]));
            }

        });

        return redirect()
            ->route('responden.index')
            ->with(
                'success',
                'Data responden berhasil diperbarui.'
            );
    }

    /**
     * Memastikan NIK kepala keluarga dan seluruh anggota tidak duplikat
     * dengan data keluarga lain maupun antaranggota dalam satu pengiriman.
     */
    private function validasiNIKUnik(Request $request, ?int $ignoreFamilyId = null): void
    {
        $nikKepala = trim((string) $request->input('nik_kepala_keluarga', ''));
        $anggota = $request->input('anggota', []);
        $nikAnggota = collect($anggota)
            ->pluck('nik')
            ->map(fn ($nik) => trim((string) $nik))
            ->filter()
            ->values();

        $semuaNik = collect([$nikKepala])->merge($nikAnggota)->filter();
        if ($semuaNik->count() !== $semuaNik->unique()->count()) {
            throw ValidationException::withMessages([
                'nik_kepala_keluarga' => 'NIK kepala keluarga dan NIK anggota tidak boleh sama.',
                'anggota' => 'Setiap NIK harus unik dan tidak boleh digunakan lebih dari satu kali.',
            ]);
        }

        $keluargaSaatIni = $ignoreFamilyId !== null
            ? Keluarga::find($ignoreFamilyId)
            : null;

        $nikYangSudahDigunakanKeluarga = Keluarga::query()
            ->get()
            ->contains(
                static fn (Keluarga $keluarga): bool => $semuaNik->contains($keluarga->nik)
                    && (
                        $keluargaSaatIni === null
                        || $keluarga->id !== $ignoreFamilyId
                    )
            );

        if ($nikYangSudahDigunakanKeluarga) {
            throw ValidationException::withMessages([
                'nik_kepala_keluarga' => 'NIK sudah digunakan sebagai NIK kepala keluarga lain.',
                'anggota' => 'Salah satu NIK sudah digunakan oleh keluarga lain.',
            ]);
        }

        $nikAnggotaSudahDigunakan = KeluargaAnggota::query()
            ->get()
            ->contains(
                static fn (KeluargaAnggota $anggota): bool => $semuaNik->contains($anggota->nik)
                    && (
                        $keluargaSaatIni === null
                        || $anggota->keluarga_kode !== $keluargaSaatIni->kode
                    )
            );

        if ($nikAnggotaSudahDigunakan) {
            throw ValidationException::withMessages([
                'nik_kepala_keluarga' => 'NIK sudah digunakan oleh anggota keluarga lain.',
                'anggota' => 'Salah satu NIK sudah digunakan oleh anggota keluarga lain.',
            ]);
        }
    }

    private function validasiNomorKKUnik(string $nomorKk, ?int $ignoreFamilyId = null): void
    {
        $sudahTerdaftar = Keluarga::query()
            ->get()
            ->contains(
                static fn (Keluarga $keluarga): bool => $keluarga->no_kk === $nomorKk
                    && $keluarga->id !== $ignoreFamilyId
            );

        if ($sudahTerdaftar) {
            throw ValidationException::withMessages([
                'nomor_kk' => 'Nomor KK sudah terdaftar. Data responden tidak dapat disimpan.',
            ]);
        }
    }

    private function normalisasiStatusKuisioner(?string $status): string
    {
        $status = strtolower(trim((string) $status));
        $status = preg_replace('/[\s\-\/]+/u', '_', $status);

        return trim((string) $status, '_');
    }

    private function isAnggotaTerkunci(?string $status): bool
    {
        // Untuk semua status kuisioner yang sudah dimulai, anggota terdaftar
        // tidak boleh dihapus. Status kosong/belum berarti kuisioner belum dimulai.
        $status = $this->normalisasiStatusKuisioner($status);

        return $status !== '' && ! in_array(
            $status,
            ['belum', 'belum_didata', 'not_processed', 'not_started'],
            true
        );
    }

    private function isPenambahanAnggotaTerkunci(?string $status): bool
    {
        $status = $this->normalisasiStatusKuisioner($status);

        // Responden yang belum memulai pendataan kuisioner boleh menambah
        // anggota. Draft dan Ditolak juga tetap boleh menambah anggota.
        if (
            $status === ''
            || in_array($status, ['belum', 'belum_didata', 'not_processed', 'not_started'], true)
            || str_contains($status, 'draft')
            || str_contains($status, 'ditolak')
            || str_contains($status, 'rejected')
        ) {
            return false;
        }

        // Status Menunggu Verifikasi, Disetujui, Sudah Submit, dan status
        // kuisioner lain yang telah diproses tetap terkunci.
        return true;
    }

    /**
     * Mengambil status kuisioner khusus untuk periode yang sedang dipilih.
     * Jika Part 1 belum dibuat, gunakan status pada relasi keluarga-periode.
     */
    private function getStatusKuisionerUntukKeluarga(Keluarga $keluarga): ?string
    {
        $periodeTerpilih = $this->getSelectedPeriode();

        if (! $periodeTerpilih) {
            return null;
        }

        $keluargaPeriode = KeluargaPeriode::query()
            ->where('keluarga_kode', $keluarga->kode)
            ->where('periode_kode', $periodeTerpilih->kode)
            ->first();

        if (! $keluargaPeriode) {
            return KeluargaPart1::query()
                ->where('keluarga_periode_kode', $keluarga->kode)
                ->orderByDesc('id')
                ->value('status');
        }

        $statusPart1 = KeluargaPart1::query()
            ->where('keluarga_periode_kode', $keluargaPeriode->kode)
            ->orderByDesc('id')
            ->value('status');

        if (
            ! $statusPart1
            && KeluargaPeriode::query()
                ->where('keluarga_kode', $keluarga->kode)
                ->count() === 1
        ) {
            $statusPart1 = KeluargaPart1::query()
                ->where('keluarga_periode_kode', $keluarga->kode)
                ->orderByDesc('id')
                ->value('status');
        }

        return $statusPart1 ?: ($keluargaPeriode->status_kuisoner ?? null);
    }

    private function getSelectedPeriode(): ?Periode
    {
        return Periode::query()->find(session('periode_id'));
    }

    private function getKuisionerUntukKeluarga(Keluarga $keluarga): ?KeluargaPart1
    {
        $periodeTerpilih = $this->getSelectedPeriode();

        if ($periodeTerpilih) {
            $kodeKeluargaPeriode = KeluargaPeriode::query()
                ->where('keluarga_kode', $keluarga->kode)
                ->where('periode_kode', $periodeTerpilih->kode)
                ->value('kode');

            if ($kodeKeluargaPeriode) {
                return KeluargaPart1::query()
                    ->where('keluarga_periode_kode', $kodeKeluargaPeriode)
                    ->orderByDesc('id')
                    ->first();
            }

            if (
                KeluargaPeriode::query()
                    ->where('keluarga_kode', $keluarga->kode)
                    ->exists()
            ) {
                return null;
            }
        }

        return KeluargaPart1::query()
            ->where('keluarga_periode_kode', $keluarga->kode)
            ->orderByDesc('id')
            ->first();
    }

    /**
     * Menghapus data responden.
     */
    public function destroy(Request $request, $id)
    {
        if (! $this->getSelectedPeriode()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pilih periode sebelum menghapus data responden.',
                ], 409);
            }

            return redirect()->route('periode.pilih', ['tujuan' => 'responden.index']);
        }

        $keluarga =
            Keluarga::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | LINDUNGI RESPONDEN YANG SUDAH PERNAH DIBUKA DI KUISIONER
        |--------------------------------------------------------------------------
        | Jika responden sudah pernah membuka/memulai kuisioner, maka
        | KeluargaPart1 sudah memiliki data dengan status draft, selesai,
        | atau status lainnya. Semua kondisi tersebut tidak boleh dihapus.
        |--------------------------------------------------------------------------
        */

        $kodeRelasiPeriode = KeluargaPeriode::query()
            ->where('keluarga_kode', $keluarga->kode)
            ->pluck('kode');

        $dataKuisioner = KeluargaPart1::query()
            ->whereIn('keluarga_periode_kode', $kodeRelasiPeriode)
            ->orWhere('keluarga_periode_kode', $keluarga->kode)
            ->orderByDesc('id')
            ->first();

        if ($dataKuisioner && $this->isAnggotaTerkunci($dataKuisioner->status)) {
            $status = strtolower(trim((string) ($dataKuisioner->status ?? '')));

            if ($status === 'draft') {
                $pesan =
                    'Data responden tidak dapat dihapus karena sudah masuk draft kuisioner.';
            } elseif ($status === 'selesai') {
                $pesan =
                    'Data responden tidak dapat dihapus karena kuisioner sudah disubmit.';
            } else {
                $pesan =
                    'Data responden tidak dapat dihapus karena sudah masuk proses kuisioner.';
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'blocked' => true,
                    'message' => $pesan,
                ], 422);
            }

            return redirect()
                ->route('responden.index')
                ->with('error', $pesan);
        }

        DB::transaction(function () use ($keluarga) {

            $keluarga
                ->anggota()
                ->delete();

            $keluarga->delete();
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'blocked' => false,
                'message' => 'Data responden berhasil dihapus.',
                'id' => $keluarga->id,
            ]);
        }

        return redirect()
            ->route('responden.index')
            ->with(
                'success',
                'Data responden berhasil dihapus.'
            );
    }

    /**
     * Detail data responden.
     */
    public function detail($id)
    {
        if (! $this->getSelectedPeriode()) {
            return response()->json([
                'message' => 'Pilih periode sebelum melihat data responden.',
            ], 409);
        }

        $keluarga =
            Keluarga::with('anggota')
                ->findOrFail($id);

        $kecamatan = null;

        if ($keluarga->kecamatan_id) {

            $kecamatan =
                DB::table('kecamatans')
                    ->where(
                        'kecamatan_id',
                        $keluarga->kecamatan_id
                    )
                    ->first();
        }

        $kelurahan = null;

        if ($keluarga->kelurahan_id) {

            $kelurahan =
                DB::table('kelurahans')
                    ->where(
                        'kelurahan_id',
                        $keluarga->kelurahan_id
                    )
                    ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | Pisahkan alamat dari koordinat
        |--------------------------------------------------------------------------
        */

        $alamatBersih =
            $this->hapusGeotagDariAlamat(
                $keluarga->alamat_lengkap
            );

        $geotangging =
            $keluarga->geotagging
            ?: $this->ambilGeotagDariAlamat(
                $keluarga->alamat_lengkap
            );

        return response()->json([
            'id' => $keluarga->id,

            'kode' => $keluarga->kode,

            'no_kk' => $keluarga->no_kk,

            'nik' => $keluarga->nik,

            'nama_lengkap' => $keluarga->nama_lengkap,

            'status_keluarga' => $keluarga->status_keluarga,

            'provinsi' => 'Jawa Timur',

            'daerah' => 'Kota Pasuruan',

            'kecamatan_id' => $keluarga->kecamatan_id,

            'kecamatan' => $kecamatan?->deskripsi,

            'kelurahan_id' => $keluarga->kelurahan_id,

            'kelurahan' => $kelurahan?->deskripsi,

            'kode_pos' => $keluarga->kode_pos,

            'rt' => $keluarga->rt,

            'rw' => $keluarga->rw,

            'rt_rw' => trim((string) ($keluarga->rt ?? '').'/'.(string) ($keluarga->rw ?? ''), '/'),

            'alamat_lengkap' => $alamatBersih,

            'geotangging' => $geotangging,

            'jml_keluarga' => $keluarga->anggota->count(),

            'anggota' => $keluarga->anggota,
        ]);
    }

    /**
     * Menggabungkan alamat lengkap dengan koordinat.
     *
     * Contoh hasil:
     *
     * Jl. Panglima Sudirman No. 10
     * Koordinat: -7.645321, 112.906543
     */
    private function gabungkanAlamatDenganGeotag(
        ?string $alamat,
        ?string $geotangging
    ): string {

        /*
        |--------------------------------------------------------------------------
        | Bersihkan alamat dari koordinat lama
        |--------------------------------------------------------------------------
        */

        $alamat =
            $this->hapusGeotagDariAlamat(
                $alamat
            );

        $alamat =
            trim($alamat);

        $geotangging =
            trim((string) $geotangging);

        /*
        |--------------------------------------------------------------------------
        | Jika tidak ada geotagging
        |--------------------------------------------------------------------------
        */

        if ($geotangging === '') {
            return mb_substr(
                $alamat,
                0,
                255
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Tambahkan koordinat
        |--------------------------------------------------------------------------
        */

        $tambahan =
            'Koordinat: '.
            $geotangging;

        if ($alamat !== '') {

            $hasil =
                $alamat.
                "\n".
                $tambahan;

        } else {

            $hasil =
                $tambahan;
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan tidak melebihi 255 karakter
        |--------------------------------------------------------------------------
        */

        if (mb_strlen($hasil) > 255) {

            $panjangGeotag =
                mb_strlen($tambahan);

            $maksAlamat =
                255 - $panjangGeotag - 1;

            $alamat =
                mb_substr(
                    $alamat,
                    0,
                    max(0, $maksAlamat)
                );

            $hasil =
                trim($alamat).
                "\n".
                $tambahan;
        }

        return $hasil;
    }

    /**
     * Menghapus bagian "Koordinat: ..." dari alamat.
     */
    private function hapusGeotagDariAlamat(
        ?string $alamat
    ): string {

        if (
            $alamat === null
            || trim($alamat) === ''
        ) {
            return '';
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus:
        |
        | Koordinat: -7.123456, 112.123456
        |--------------------------------------------------------------------------
        */

        $alamat =
            preg_replace(
                '/\s*Koordinat:\s*-?\d+(?:\.\d+)?\s*,\s*-?\d+(?:\.\d+)?/i',
                '',
                $alamat
            );

        return trim(
            (string) $alamat
        );
    }

    /**
     * Mengambil koordinat dari alamat_lengkap.
     *
     * Hasil:
     *
     * -7.645321, 112.906543
     */
    private function ambilGeotagDariAlamat(
        ?string $alamat
    ): ?string {

        if (
            $alamat === null
            || trim($alamat) === ''
        ) {
            return null;
        }

        if (
            preg_match(
                '/(?:Koordinat:\s*)?(-?\d+(?:\.\d+)?)\s*,\s*(-?\d+(?:\.\d+)?)/i',
                $alamat,
                $matches
            )
        ) {

            return
                $matches[1].
                ', '.
                $matches[2];
        }

        return null;
    }
}
