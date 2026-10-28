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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KuisionerController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HOME
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {

        /*
        |--------------------------------------------------------------------------
        | JUMLAH RESPONDEN
        |--------------------------------------------------------------------------
        */

        $periodeRelations = $this->selectedPeriodRelationCodes();
        $respondenCount = KeluargaPeriode::query()
            ->where('periode_kode', $this->getSelectedPeriode()?->kode)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | JUMLAH BELUM DIDATA
        |--------------------------------------------------------------------------
        */

        $statusSudahDidata = [
            'draft',
            'selesai',
            'approved',
            'disetujui',
            'rejected',
            'reject',
            'ditolak',
        ];

        $kodeSudahDidata = KeluargaPart1::query()
            ->whereIn('keluarga_periode_kode', $periodeRelations)
            ->whereIn('status', $statusSudahDidata)
            ->pluck('keluarga_periode_kode')
            ->unique()
            ->toArray();

        $belumDidataCount = KeluargaPeriode::query()
            ->where('periode_kode', $this->getSelectedPeriode()?->kode)
            ->whereNotIn('kode', $kodeSudahDidata)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | JUMLAH DRAFT
        |--------------------------------------------------------------------------
        */

        $draftCount = KeluargaPart1::query()
            ->whereIn('keluarga_periode_kode', $periodeRelations)
            ->where('status', 'draft')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | JUMLAH KUISIONER SELESAI
        |--------------------------------------------------------------------------
        */

        $selesaiCount = KeluargaPart1::query()
            ->whereIn('keluarga_periode_kode', $periodeRelations)
            ->where('status', 'selesai')
            ->count();

        $ditolakCount = KeluargaPart1::query()
            ->whereIn('keluarga_periode_kode', $periodeRelations)
            ->whereIn('status', ['rejected', 'reject', 'ditolak'])
            ->count();

        $diterimaCount = KeluargaPart1::query()
            ->whereIn('keluarga_periode_kode', $periodeRelations)
            ->whereIn('status', ['approved', 'disetujui'])
            ->count();

        /*
        |--------------------------------------------------------------------------
        | PENCARIAN DRAFT
        |--------------------------------------------------------------------------
        */

        $search = trim((string) $request->input('search', ''));

        $draftQuery = KeluargaPart1::query()
            ->whereIn('keluarga_periode_kode', $periodeRelations)
            ->where('status', 'draft');

        if ($search !== '') {
            $matchingIdentifierIds = KeluargaPart1::idsMatchingIdentifierSearch($search);

            $draftQuery->where(function ($query) use ($search, $matchingIdentifierIds): void {
                $keyword = '%'.$search.'%';

                $query->where('nama_kepala_keluarga', 'like', $keyword)
                    ->orWhereIn('id', $matchingIdentifierIds);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | DATA DRAFT: MAKSIMAL 10 PER HALAMAN
        |--------------------------------------------------------------------------
        */

        $drafts = $draftQuery
            ->orderByDesc('updated_at')
            ->paginate(10, ['*'], 'draft_page')
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | HITUNG PROGRESS SETIAP DRAFT
        |--------------------------------------------------------------------------
        */

        /*
|--------------------------------------------------------------------------
| HITUNG PROGRESS SETIAP DRAFT
|--------------------------------------------------------------------------
*/

        foreach ($drafts as $draft) {

            // Progress awal berdasarkan Part yang sedang dikerjakan.
            $completedPart = max(
                1,
                min(5, (int) ($draft->current_part ?? 1))
            );

            // Status kelengkapan anggota dan foto.
            $semuaAnggotaLengkap = false;
            $semuaFotoLengkap = false;

            // Periksa Part 5 jika keluarga sudah mencapai tahap tersebut.
            if ((int) $draft->current_part >= 5) {

                /*
                |--------------------------------------------------------------------------
                | 1. PERIKSA SELURUH DATA ANGGOTA KELUARGA
                |--------------------------------------------------------------------------
                */

                $kodeKeluarga = $this->familyCodeForQuestionnaire(
                    $draft->keluarga_periode_kode
                );
                $anggota = KeluargaAnggota::where(
                    'keluarga_kode',
                    $kodeKeluarga
                )->get();

                $kodeAnggota = $anggota->pluck('kode');

                $dataPart5 = KeluargaPart5::where(
                    'keluarga_periode_kode',
                    $draft->keluarga_periode_kode
                )
                    ->whereIn('keluarga_anggota_kode', $kodeAnggota)
                    ->get()
                    ->keyBy('keluarga_anggota_kode');

                $jumlahAnggota = $anggota->count();

                $jumlahAnggotaLengkap = $anggota->filter(
                    function ($item) use ($dataPart5) {

                        $data = $dataPart5->get($item->kode);

                        return $data
                            && $this->part5DataLengkap($data);
                    }
                )->count();

                $semuaAnggotaLengkap = $jumlahAnggota > 0
                    && $jumlahAnggotaLengkap === $jumlahAnggota;

                /*
                |--------------------------------------------------------------------------
                | 2. PERIKSA TIGA FOTO RUMAH
                |--------------------------------------------------------------------------
                */

                $jumlahFotoLengkap = KeluargaFotoRumah::where(
                    'keluarga_periode_kode',
                    $draft->keluarga_periode_kode
                )
                    ->whereIn('jenis_foto', [
                        'tampak_depan',
                        'ruang_tamu',
                        'kamar_mandi',
                    ])
                    ->distinct()
                    ->count('jenis_foto');

                $semuaFotoLengkap = $jumlahFotoLengkap === 3;

                /*
                |--------------------------------------------------------------------------
                | 3. TENTUKAN PROGRESS AKHIR
                |--------------------------------------------------------------------------
                */

                if ($semuaAnggotaLengkap && $semuaFotoLengkap) {

                    // Hanya kondisi ini yang boleh mencapai 100%.
                    $completedPart = 5;
                    $progressPercent = 100;

                } elseif ($semuaAnggotaLengkap || $semuaFotoLengkap) {

                    // Salah satu tahap Part 5 selesai: maksimal 90%.
                    $completedPart = 4;
                    $progressPercent = 90;

                } else {

                    // Kedua tahap Part 5 belum lengkap.
                    $completedPart = 4;
                    $progressPercent = 80;
                }

            } else {

                // Part 1–4 tetap mengikuti progress sebelumnya.
                $progressPercent = ($completedPart / 5) * 100;
            }

            // Kirim nilai progress ke Blade.
            $draft->completedPart = $completedPart;
            $draft->progressPercent = $progressPercent;
        }

        /*
        |--------------------------------------------------------------------------
        | DATA SIAP DIAJUKAN
        |--------------------------------------------------------------------------
        */

        $submitCount = $selesaiCount;

        /*
        |--------------------------------------------------------------------------
        | KIRIM DATA KE VIEW
        |--------------------------------------------------------------------------
        */

        return view('admin.kuisioner.index', [
            'page' => 'home',
            'respondenCount' => $respondenCount,
            'belumDidataCount' => $belumDidataCount,
            'draftCount' => $draftCount,
            'selesaiCount' => $selesaiCount,
            'submitCount' => $submitCount,
            'ditolakCount' => $ditolakCount,
            'diterimaCount' => $diterimaCount,
            'drafts' => $drafts,
            'selesais' => collect(),
            'search' => $search,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DRAFT
    |--------------------------------------------------------------------------
    */

    public function draft()
    {
        $drafts = KeluargaPart1::query()
            ->whereIn('keluarga_periode_kode', $this->selectedPeriodRelationCodes())
            ->where('status', 'draft')
            ->latest('id')
            ->get();

        return view('admin.kuisioner.draft', [
            'drafts' => $drafts,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SELESAI
    |--------------------------------------------------------------------------
    */

    public function selesai()
    {

        $selesai = KeluargaPart1::query()
            ->whereIn('keluarga_periode_kode', $this->selectedPeriodRelationCodes())
            ->where('status', 'selesai')
            ->orderByDesc('updated_at')
            ->paginate(10);

        foreach ($selesai as $item) {
            $keluarga = $this->familyForQuestionnaire(
                $item->keluarga_periode_kode
            );

            if (! $keluarga && $item->no_kk) {
                $keluarga = Keluarga::where(
                    'no_kk',
                    $item->no_kk
                )->first();
            }

            $item->keluarga_data = $keluarga;
            $item->rt_rw_display = $keluarga
                ? trim((string) $keluarga->rt.' / '.(string) $keluarga->rw, ' /')
                : '-';

            $jumlahAnggotaTambahan = $keluarga
                ? KeluargaAnggota::where('keluarga_kode', $keluarga->kode)->count()
                : 0;

            $item->jumlah_keluarga_display = $item->jml_keluarga !== null
                ? $item->jml_keluarga
                : ($keluarga ? 1 + $jumlahAnggotaTambahan : '-');

            $item->daerah_display = filled($item->daerah)
                ? $item->daerah
                : (filled($item->provinsi) ? $item->provinsi : '-');

            $item->geotagging_display = filled($item->geotangging)
                ? $item->geotangging
                : ($keluarga->geotagging ?? '-');

            $item->kota_kabupaten_display = 'Kota Pasuruan';
        }

        return view('admin.kuisioner.selesai', [
            'selesai' => $selesai,
        ]);
    }

    public function ditolak(): View
    {
        $kuisioner = KeluargaPart1::query()
            ->whereIn('keluarga_periode_kode', $this->selectedPeriodRelationCodes())
            ->whereIn('status', [
                'rejected',
                'reject',
                'ditolak',
            ])
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->paginate(10);

        // Ambil data keluarga untuk data yang tampil di halaman aktif.
        $familyCodes = $kuisioner
            ->pluck('keluarga_periode_kode')
            ->filter()
            ->unique()
            ->values();

        $familyNumbers = $kuisioner
            ->pluck('no_kk')
            ->filter()
            ->unique()
            ->values();

        $families = Keluarga::query()
            ->where(function ($query) use ($familyCodes, $familyNumbers) {
                if ($familyCodes->isNotEmpty()) {
                    $query->whereIn('kode', KeluargaPeriode::query()
                        ->whereIn('kode', $familyCodes)
                        ->select('keluarga_kode'));
                }

                if ($familyNumbers->isNotEmpty()) {
                    $method = $familyCodes->isNotEmpty()
                        ? 'orWhereIn'
                        : 'whereIn';

                    $query->{$method}('no_kk', $familyNumbers);
                }
            })
            ->get([
                'kode',
                'no_kk',
                'nik',
                'nama_lengkap',
                'alamat_lengkap',
            ]);

        $familiesByCode = $families->keyBy('kode');
        $familiesByNumber = $families->keyBy('no_kk');

        foreach ($kuisioner as $item) {
            $item->keluarga_data = $familiesByCode->get(
                $this->familyCodeForQuestionnaire($item->keluarga_periode_kode)
            ) ?? $familiesByNumber->get($item->no_kk);
        }

        return view('admin.kuisioner.ditolak', [
            'kuisioner' => $kuisioner,
        ]);
    }

    public function disetujui(): View
    {
        $kuisioner = KeluargaPart1::query()
            ->whereIn('keluarga_periode_kode', $this->selectedPeriodRelationCodes())
            ->whereIn('status', [
                'approved',
                'disetujui',
            ])
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->paginate(10);

        // Ambil kode keluarga dan nomor KK dari data pada halaman aktif.
        $familyCodes = $kuisioner
            ->pluck('keluarga_periode_kode')
            ->filter()
            ->unique()
            ->values();

        $familyNumbers = $kuisioner
            ->pluck('no_kk')
            ->filter()
            ->unique()
            ->values();

        // Ambil data keluarga yang dibutuhkan oleh tabel.
        $families = Keluarga::query()
            ->where(function ($query) use ($familyCodes, $familyNumbers) {
                if ($familyCodes->isNotEmpty()) {
                    $query->whereIn('kode', KeluargaPeriode::query()
                        ->whereIn('kode', $familyCodes)
                        ->select('keluarga_kode'));
                }

                if ($familyNumbers->isNotEmpty()) {
                    $method = $familyCodes->isNotEmpty()
                        ? 'orWhereIn'
                        : 'whereIn';

                    $query->{$method}('no_kk', $familyNumbers);
                }
            })
            ->get([
                'kode',
                'no_kk',
                'nik',
                'nama_lengkap',
                'alamat_lengkap',
            ]);

        $familiesByCode = $families->keyBy('kode');
        $familiesByNumber = $families->keyBy('no_kk');

        // Hubungkan data keluarga dengan setiap kuisioner.
        foreach ($kuisioner as $item) {
            $item->keluarga_data = $familiesByCode->get(
                $this->familyCodeForQuestionnaire($item->keluarga_periode_kode)
            ) ?? $familiesByNumber->get($item->no_kk);
        }

        return view('admin.kuisioner.disetujui', [
            'kuisioner' => $kuisioner,
        ]);
    }

    private function getQuestionnairesByStatus(array $statuses): EloquentCollection
    {
        $questionnaires = KeluargaPart1::query()
            ->whereIn('keluarga_periode_kode', $this->selectedPeriodRelationCodes())
            ->whereIn('status', $statuses)
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get();

        $familyCodes = $questionnaires
            ->pluck('keluarga_periode_kode')
            ->filter()
            ->unique()
            ->values();

        $familyNumbers = $questionnaires
            ->pluck('no_kk')
            ->filter()
            ->unique()
            ->values();

        $familyCodes = KeluargaPeriode::query()
            ->whereIn('kode', $familyCodes)
            ->pluck('keluarga_kode')
            ->all();

        $families = Keluarga::query()
            ->get([
                'kode',
                'no_kk',
                'nik',
                'nama_lengkap',
            ])
            ->filter(
                static fn (Keluarga $keluarga): bool => in_array($keluarga->kode, $familyCodes, true)
                    || in_array($keluarga->no_kk, $familyNumbers->all(), true)
            );

        $familiesByCode = $families->keyBy('kode');
        $familiesByNumber = $families->keyBy('no_kk');

        foreach ($questionnaires as $questionnaire) {
            $questionnaire->keluarga_data = $familiesByCode->get(
                $this->familyCodeForQuestionnaire($questionnaire->keluarga_periode_kode)
            ) ?? $familiesByNumber->get($questionnaire->no_kk);
        }

        return $questionnaires;
    }

    public function editRejected(int $id): RedirectResponse
    {
        $questionnaire = KeluargaPart1::query()
            ->whereIn('keluarga_periode_kode', $this->selectedPeriodRelationCodes())
            ->findOrFail($id);
        $status = strtolower(trim((string) $questionnaire->status));

        if (! in_array($status, ['rejected', 'reject', 'ditolak'], true)) {
            return redirect()
                ->route('kuisioner.ditolak')
                ->with('error', 'Hanya kuisioner berstatus ditolak yang dapat diedit.');
        }

        $familyRelationCode = trim((string) $questionnaire->keluarga_periode_kode);
        $familyCode = $this->familyCodeForQuestionnaire($familyRelationCode);
        $familyExists = $familyCode !== ''
            && Keluarga::query()->where('kode', $familyCode)->exists();

        if (! $familyExists && filled($questionnaire->no_kk)) {
            $family = Keluarga::query()
                ->get()
                ->first(
                    static fn (Keluarga $keluarga): bool => $keluarga->no_kk === $questionnaire->no_kk
                );

            if ($family) {
                $familyCode = $family->kode;
                $familyRelationCode = $familyCode;
                $questionnaire->keluarga_periode_kode = $familyRelationCode;
                $questionnaire->save();
                $familyExists = true;
            }
        }

        if (! $familyExists) {
            return redirect()
                ->route('kuisioner.ditolak')
                ->with('error', 'Data keluarga tidak ditemukan sehingga kuisioner tidak dapat diedit.');
        }

        session([
            'draft_keluarga_id' => $questionnaire->id,
            'selected_keluarga_kode' => $familyRelationCode,
            'editing_rejected_questionnaire' => true,
            'part1_selesai' => false,
            'part2_selesai' => false,
            'part3_selesai' => false,
            'part4_selesai' => false,
            'part5_selesai' => false,
        ]);

        return redirect()
            ->route('kuisioner.part1')
            ->with('success', 'Kuisioner ditolak langsung dibuka untuk diedit. Status dan data yang sudah tersimpan tetap dipertahankan.');
    }

    private function editableQuestionnaireQuery(): Builder
    {
        $query = KeluargaPart1::query()
            ->whereIn('keluarga_periode_kode', $this->selectedPeriodRelationCodes());

        if (session('editing_rejected_questionnaire')) {
            return $query
                ->whereKey(session('draft_keluarga_id'))
                ->whereIn('status', ['rejected', 'reject', 'ditolak']);
        }

        return $query->where('status', 'draft');
    }

    private function getSelectedPeriode(): ?Periode
    {
        return Periode::query()->find(session('periode_id'));
    }

    private function selectedPeriodRelationCodes(): Builder
    {
        return KeluargaPeriode::query()
            ->where('periode_kode', $this->getSelectedPeriode()?->kode)
            ->select('kode');
    }

    private function getFamilyPeriodForSelectedPeriod(string $familyOrRelationCode): ?KeluargaPeriode
    {
        $periode = $this->getSelectedPeriode();

        if (! $periode) {
            return null;
        }

        return KeluargaPeriode::query()
            ->where('periode_kode', $periode->kode)
            ->where(function ($query) use ($familyOrRelationCode) {
                $query
                    ->where('kode', $familyOrRelationCode)
                    ->orWhere('keluarga_kode', $familyOrRelationCode);
            })
            ->first();
    }

    private function familyCodeForQuestionnaire(string $familyPeriodCode): string
    {
        return KeluargaPeriode::query()
            ->where('kode', $familyPeriodCode)
            ->value('keluarga_kode')
            ?? $familyPeriodCode;
    }

    private function familyForQuestionnaire(string $familyPeriodCode): ?Keluarga
    {
        return Keluarga::query()
            ->with('anggota')
            ->where('kode', $this->familyCodeForQuestionnaire($familyPeriodCode))
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | PILIH RESPONDEN
    |--------------------------------------------------------------------------
    */
    public function pilihResponden(Request $request)
    {
        $periode = $this->getSelectedPeriode();

        // Rapikan spasi pencarian.
        $search = preg_replace(
            '/\s+/u',
            ' ',
            trim((string) $request->input('search', ''))
        );

        $kataKunci = $search !== ''
            ? preg_split('/\s+/u', $search, -1, PREG_SPLIT_NO_EMPTY)
            : [];

        /*
        |--------------------------------------------------------------------------
        | KODE KELUARGA YANG SUDAH MASUK KUISIONER
        |--------------------------------------------------------------------------
        */
        $relations = KeluargaPeriode::query()
            ->where('periode_kode', $periode->kode)
            ->get(['kode', 'keluarga_kode']);

        $kodeSudahDipakai = KeluargaPart1::query()
            ->whereIn('keluarga_periode_kode', $relations->pluck('kode'))
            ->whereIn('status', [
                'draft',
                'selesai',
                'approved',
                'disetujui',
                'rejected',
                'reject',
                'ditolak',
            ])
            ->pluck('keluarga_periode_kode')
            ->unique()
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | RESPONDEN YANG BELUM DIDATA
        |--------------------------------------------------------------------------
        */
        $keluargaSudahDipakai = $relations
            ->whereIn('kode', $kodeSudahDipakai)
            ->pluck('keluarga_kode');

        $familyCodes = $relations->pluck('keluarga_kode');
        $candidateFamilies = Keluarga::query()
            ->whereIn('kode', $familyCodes)
            ->get()
            ->keyBy('kode');

        $respondenQuery = Keluarga::query()
            ->whereIn('kode', $familyCodes);

        if ($keluargaSudahDipakai->isNotEmpty()) {
            $respondenQuery->whereNotIn('kode', $keluargaSudahDipakai);
        }

        foreach ($kataKunci as $kata) {
            $keyword = '%'.$kata.'%';
            $searchPattern = '/'.str_replace(
                ['%', '_'],
                ['[\s\S]*', '[\s\S]'],
                preg_quote($keyword, '/')
            ).'/iu';

            $familyCodesMatchingIdentifiers = $candidateFamilies
                ->filter(
                    static fn (Keluarga $keluarga): bool => preg_match(
                        $searchPattern,
                        (string) $keluarga->nik
                    ) === 1 || preg_match(
                        $searchPattern,
                        (string) $keluarga->no_kk
                    ) === 1
                )
                ->pluck('kode');

            $respondenQuery->where(function (Builder $query) use (
                $keyword,
                $familyCodesMatchingIdentifiers
            ): void {
                $query
                    ->where('nama_lengkap', 'like', $keyword)
                    ->orWhereIn('kode', $familyCodesMatchingIdentifiers);
            });
        }

        if ($search !== '') {
            $respondenQuery->orderByRaw(
                'CASE
                    WHEN nama_lengkap = ? THEN 0
                    WHEN nama_lengkap LIKE ? THEN 1
                    ELSE 2
                END',
                [$search, $search.'%']
            );
        }

        $responden = $respondenQuery
            ->orderBy('nama_lengkap')
            ->paginate(10)
            ->withQueryString();

        // AJAX hanya mengembalikan isi tabel dan pagination.
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.kuisioner.pilih-responden')
                    ->with(compact('responden', 'search'))
                    ->render(),
            ]);
        }

        // Request biasa tetap menampilkan halaman lengkap.
        return view(
            'admin.kuisioner.pilih-responden',
            compact('responden', 'search')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PART 1
    |--------------------------------------------------------------------------
    */

    public function part1(Request $request)
    {
        $search = trim($request->get('search', ''));
        $periode = $this->getSelectedPeriode();
        $relations = KeluargaPeriode::query()
            ->where('periode_kode', $periode->kode)
            ->get(['kode', 'keluarga_kode']);

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA KELUARGA
        |--------------------------------------------------------------------------
        */

        $query = Keluarga::with('anggota')
            ->whereIn('kode', $relations->pluck('keluarga_kode'))
            ->orderBy('id');

        if ($search !== '') {
            $query->where(
                'nama_lengkap',
                'like',
                '%'.$search.'%'
            );
        }

        $keluargas = $query
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | AMBIL STATUS KUESIONER
        |--------------------------------------------------------------------------
        */

        $statusKeluarga = KeluargaPart1::whereIn(
            'keluarga_periode_kode',
            $relations->pluck('kode')
        )
            ->orderByDesc('id')
            ->get()
            ->keyBy('keluarga_periode_kode');

        /*
        |--------------------------------------------------------------------------
        | TEMPELKAN STATUS KE KELUARGA
        |--------------------------------------------------------------------------
        */

        foreach ($keluargas as $keluarga) {
            $relation = $relations->firstWhere('keluarga_kode', $keluarga->kode);
            $part1 = $statusKeluarga->get($relation?->kode);

            if (! $part1) {
                // BELUM PERNAH MULAI
                $keluarga->status_kuesioner = null;
                $keluarga->current_part_kuesioner = 0;
            } else {
                // SUDAH ADA DATA PART 1
                $keluarga->status_kuesioner = $part1->status ?? 'draft';
                $keluarga->current_part_kuesioner = $part1->current_part ?? 1;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | DATA KECAMATAN DAN KELURAHAN
        |--------------------------------------------------------------------------
        */

        $kecamatans = DB::table('kecamatans')
            ->orderBy('deskripsi')
            ->get();

        $kelurahans = DB::table('kelurahans')
            ->orderBy('deskripsi')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | DEFAULT
        |--------------------------------------------------------------------------
        */

        $selectedKeluarga = null;
        $data = null;

        $selectedKecamatan = null;
        $selectedKelurahan = null;
        $anggotaPertama = null;

        /*
        |--------------------------------------------------------------------------
        | JIKA MEMILIH KELUARGA
        |--------------------------------------------------------------------------
        */
        $kodeKeluargaTerpilih = $request->get(
            'keluarga',
            session('selected_keluarga_kode')
        );

        if (! $kodeKeluargaTerpilih) {
            session()->forget([
                'selected_keluarga_kode',
                'draft_keluarga_id',
                'part1_selesai',
                'part2_selesai',
                'part3_selesai',
                'part4_selesai',
                'part5_selesai',
                'editing_rejected_questionnaire',
            ]);
        }

        if ($kodeKeluargaTerpilih) {
            $relation = $this->getFamilyPeriodForSelectedPeriod(
                (string) $kodeKeluargaTerpilih
            );
            $selectedKeluarga = $relation?->keluarga()
                ->with('anggota')
                ->first();

            if ($selectedKeluarga) {

                $anggotaPertama = $selectedKeluarga->anggota->first();

                $data = $this->editableQuestionnaireQuery()->where(
                    'keluarga_periode_kode',
                    $relation->kode
                )
                    ->latest('id')
                    ->first();

                if ($selectedKeluarga->kecamatan_id) {

                    $selectedKecamatan = DB::table('kecamatans')
                        ->where(
                            'kecamatan_id',
                            $selectedKeluarga->kecamatan_id
                        )
                        ->value('deskripsi');
                }

                if ($selectedKeluarga->kelurahan_id) {

                    $selectedKelurahan = DB::table('kelurahans')
                        ->where(
                            'kelurahan_id',
                            $selectedKeluarga->kelurahan_id
                        )
                        ->value('deskripsi');
                }

                session([
                    'selected_keluarga_kode' => $relation->kode,
                ]);
            }
        }

        return view('admin.kuisioner.part1', [

            'keluargas' => $keluargas,

            'selectedKeluarga' => $selectedKeluarga,

            'data' => $data,

            'kecamatans' => $kecamatans,

            'kelurahans' => $kelurahans,

            'selectedKecamatan' => $selectedKecamatan,

            'selectedKelurahan' => $selectedKelurahan,

            'anggotaPertama' => $anggotaPertama,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN PART 1
    |--------------------------------------------------------------------------
    */

    public function storePart1(Request $request)
    {
        $validated = $request->validate([
            'nik' => [
                'nullable',
                'string',
                'max:255',
            ],

            'nama_kepala_keluarga' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status_keluarga' => [
                'nullable',
                'string',
                'max:255',
            ],

            'nomor_induk_keluarga' => [
                'nullable',
                'string',
                'max:255',
            ],

            'no_kk' => [
                'nullable',
                'string',
                'max:255',
            ],

            'jml_keluarga' => [
                'nullable',
                'string',
                'max:255',
            ],

            'jumlah_anggota' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status_keluarga' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status_dalam_keluarga' => [
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

            'kecamatan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kelurahan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kode_pos' => [
                'nullable',
                'string',
                'max:16',
            ],

            'rt_rw' => [
                'nullable',
                'string',
                'max:255',
            ],

            'alamat_lengkap' => [
                'nullable',
                'string',
                'max:255',
            ],

            'jalan_rumah' => [
                'nullable',
                'string',
                'max:255',
            ],

            'nomor_rumah' => [
                'nullable',
                'string',
                'max:255',
            ],

            'is_alamat_sesuai' => [
                'nullable',
                'boolean',
            ],

            'alasan_tidak_sesuai' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'geotangging' => [
                'nullable',
                'string',
                'max:255',
            ],

            'keluarga_kode' => [
                'nullable',
                'string',
                'max:255',
            ],

            // TAMBAHAN UNTUK TOMBOL
            'action' => [
                'required',
                'in:draft,next',
            ],
        ]);

        $user = auth()->user()->name ?? 'admin';

        /*
        |--------------------------------------------------------------------------
        | TENTUKAN KODE KELUARGA
        |--------------------------------------------------------------------------
        */

        $kodeKeluargaTerpilih =
            $validated['keluarga_kode']
            ?? session('selected_keluarga_kode');

        if (! $kodeKeluargaTerpilih) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Kode keluarga tidak ditemukan. Silakan pilih keluarga terlebih dahulu.'
                );
        }

        $relation = $this->getFamilyPeriodForSelectedPeriod(
            (string) $kodeKeluargaTerpilih
        );

        if (! $relation) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Responden tidak terhubung dengan periode yang dipilih.'
                );
        }

        $keluargaPeriodeKode = $relation->kode;

        /*
        |--------------------------------------------------------------------------
        | CARI DRAFT MILIK KELUARGA
        |--------------------------------------------------------------------------
        */

        $draft = $this->editableQuestionnaireQuery()->where(
            'keluarga_periode_kode',
            $keluargaPeriodeKode
        )
            ->latest('id')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | JIKA BELUM ADA DRAFT
        |--------------------------------------------------------------------------
        */

        if (! $draft) {
            $draft = new KeluargaPart1;
            $draft->status = 'draft';
            $draft->current_part = 1;
            $draft->created_by = $user;
        }

        /*
        |--------------------------------------------------------------------------
        | DATA DASAR
        |--------------------------------------------------------------------------
        */

        $draft->nik =
            $validated['nik'] ?? null;

        $draft->nama_kepala_keluarga =
            $validated['nama_kepala_keluarga'] ?? null;

        $draft->no_kk =
            $validated['no_kk'] ?? null;

        $draft->jml_keluarga =
            $validated['jml_keluarga']
            ?? $validated['jumlah_anggota']
            ?? null;

        $draft->provinsi =
            $validated['provinsi'] ?? null;

        $draft->daerah =
            $validated['daerah'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | KECAMATAN
        |--------------------------------------------------------------------------
        */

        $draft->kecamatan =
            $validated['kecamatan'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | KELURAHAN
        |--------------------------------------------------------------------------
        */

        $draft->kelurahan =
            $validated['kelurahan'] ?? null;

        $draft->kode_pos =
            $validated['kode_pos'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | ALAMAT LENGKAP
        |--------------------------------------------------------------------------
        | Simpan alamat persis seperti yang dikirim dari form.
        | RT/RW tetap disimpan pada kolom rt_rw, tidak digabungkan ke alamat.
        */

        $draft->alamat_lengkap = trim(
            $validated['alamat_lengkap'] ?? ''
        );

        /*
        |--------------------------------------------------------------------------
        | JALAN DAN NOMOR RUMAH
        |--------------------------------------------------------------------------
        */

        $draft->jalan_rumah =
            $validated['jalan_rumah'] ?? null;

        $draft->nomor_rumah =
            $validated['nomor_rumah'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | ALAMAT SESUAI
        |--------------------------------------------------------------------------
        */

        $draft->is_alamat_sesuai =
            $validated['is_alamat_sesuai'] ?? null;

        $draft->alasan_tidak_sesuai =
            $validated['alasan_tidak_sesuai'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | GEOTAGGING
        |--------------------------------------------------------------------------
        */

        $draft->geotangging =
            $validated['geotangging'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | HUBUNGKAN DENGAN KELUARGA
        |--------------------------------------------------------------------------
        */

        $draft->keluarga_periode_kode =
            $keluargaPeriodeKode;

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        if (! session('editing_rejected_questionnaire')) {
            $draft->status = 'draft';
        }

        /*
        |--------------------------------------------------------------------------
        | TENTUKAN PART BERDASARKAN TOMBOL
        |--------------------------------------------------------------------------
        */

        if ($validated['action'] === 'draft') {

            // Tetap di Part 1
            $draft->current_part = 1;

        } else {

            // Lanjut ke Part 2
            $draft->current_part = 2;
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATED BY
        |--------------------------------------------------------------------------
        */

        $draft->updated_by =
            $user;

        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        $draft->save();

        /*
        |--------------------------------------------------------------------------
        | SIMPAN DRAFT AKTIF KE SESSION
        |--------------------------------------------------------------------------
        */

        session([
            'draft_keluarga_id' => $draft->id,

            'selected_keluarga_kode' => $keluargaPeriodeKode,

            'part1_selesai' => false,

            'part2_selesai' => false,

            'part3_selesai' => false,

            'part4_selesai' => false,

            'part5_selesai' => false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | JIKA KLIK DRAFT
        |--------------------------------------------------------------------------
        */

        if ($validated['action'] === 'draft') {

            return redirect()
                ->route('kuisioner.index')
                ->with(
                    'success',
                    'Data Part 1 berhasil disimpan sebagai draft.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | JIKA KLIK SIMPAN & LANJUTKAN
        |--------------------------------------------------------------------------
        */

        $issues = $this->questionnaireCompletionIssues($draft, $request);

        $missingPart1 = $this->missingQuestionsForPart(
            $issues['missing_questions'],
            1
        );

        if ($missingPart1 !== []) {
            $draft->current_part = 1;
            $draft->save();

            return $this->redirectToIncompletePart(1, $missingPart1);
        }

        return redirect()
            ->route('kuisioner.part2')
            ->with(
                'success',
                'Data Part 1 berhasil disimpan. Silakan lanjutkan ke Part 2.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | PART 2
    |--------------------------------------------------------------------------
    */

    public function part2()
    {
        $keluargaKode = session('selected_keluarga_kode');

        if (! $keluargaKode) {
            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'warning',
                    'Silakan pilih keluarga dan selesaikan Part 1 terlebih dahulu.'
                );
        }

        $draftId = session('draft_keluarga_id');

        if (! $draftId) {
            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'warning',
                    'Silakan lengkapi dan simpan Part 1 terlebih dahulu.'
                );
        }

        $draft = $this->editableQuestionnaireQuery()->where('id', $draftId)
            ->where(
                'keluarga_periode_kode',
                $keluargaKode
            )
            ->first();

        if (! $draft) {

            session()->forget([
                'draft_keluarga_id',
                'selected_keluarga_kode',
                'part1_selesai',
                'part2_selesai',
                'part3_selesai',
                'part4_selesai',
                'part5_selesai',
                'editing_rejected_questionnaire',
            ]);

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'warning',
                    'Silakan pilih keluarga dan selesaikan Part 1 terlebih dahulu.'
                );
        }

        if ((int) $draft->current_part < 2) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'warning',
                    'Silakan lengkapi dan simpan Part 1 terlebih dahulu.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA PART 2
        |--------------------------------------------------------------------------
        */

        $dataPart2 = null;

        if ($draft->keluarga_periode_kode) {

            $dataPart2 = KeluargaPart2::where(
                'keluarga_periode_kode',
                $draft->keluarga_periode_kode
            )->first();
        }

        return view('admin.kuisioner.part2', [
            'dataPart2' => $dataPart2,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN PART 2
    |--------------------------------------------------------------------------
    */

    public function storePart2(Request $request)
    {
        $draftId = session('draft_keluarga_id');

        if (! $draftId) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'warning',
                    'Silakan lengkapi dan simpan Part 1 terlebih dahulu.'
                );
        }

        $draft = $this->editableQuestionnaireQuery()
            ->where('id', $draftId)
            ->first();

        if (! $draft) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'warning',
                    'Draft kuisioner tidak ditemukan.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        // Hapus titik pemisah ribuan sebelum validasi
        $request->merge([
            'sewa_bulanan' => str_replace(
                '.',
                '',
                (string) $request->input('sewa_bulanan', '')
            ),
        ]);

        $validated = $request->validate([

            'jenis_bangunan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'keluarga_lain' => [
                'nullable',
                'in:Ya,Tidak',
            ],

            'jumlah_keluarga_lain' => [
                'nullable',
                'numeric',
                'min:1',
            ],

            'jumlah_orang' => [
                'nullable',
                'numeric',
                'min:1',
            ],

            'status_kepemilikan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'bukti_kepemilikan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'sewa_bulanan' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'perkiraan_sewa' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'status_kepemilikan_lainnya' => [
                'nullable',
                'string',
                'max:255',
            ],

            'luas_lantai' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'jenis_lantai' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kondisi_lantai' => [
                'nullable',
                'string',
                'max:255',
            ],

            'jenis_dinding' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kondisi_dinding' => [
                'nullable',
                'string',
                'max:255',
            ],

            'jenis_atap' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kondisi_atap' => [
                'nullable',
                'in:Baik,Rusak Ringan,Rusak Sedang,Rusak Berat',
            ],

            'fasilitas_bab' => [
                'nullable',
                'string',
                'max:255',
            ],

            'jenis_toilet' => [
                'nullable',
                'string',
                'max:255',
            ],

            'sumber_air_minum' => [
                'nullable',
                'string',
                'max:255',
            ],

            'sumber_penerangan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'daya_listrik' => [
                'nullable',
                'string',
                'max:255',
            ],

            'id_pelanggan_pln' => [
                'nullable',
                'string',
                'max:255',
            ],

            'jumlah_meteran' => [
                'nullable',
                'numeric',
                'min:1',
            ],

            'action' => [
                'required',
                'in:draft,next',
            ],
        ]);

        $user = auth()->user()->name ?? 'admin';

        /*
        |--------------------------------------------------------------------------
        | KODE KELUARGA
        |--------------------------------------------------------------------------
        */

        $keluargaKode = $draft->keluarga_periode_kode;

        if (! $keluargaKode) {
            $keluargaKode = session('selected_keluarga_kode');
        }

        if (! $keluargaKode) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'warning',
                    'Kode keluarga tidak ditemukan. Silakan pilih keluarga kembali.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | KONVERSI KONDISI ATAP
        |--------------------------------------------------------------------------
        */

        $kondisiAtap = null;

        if (! empty($validated['kondisi_atap'])) {

            $kondisiAtap = match ($validated['kondisi_atap']) {

                'Baik' => 1,

                'Rusak Ringan' => 2,

                'Rusak Sedang' => 3,

                'Rusak Berat' => 4,

                default => null,
            };
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS KEPEMILIKAN
        |--------------------------------------------------------------------------
        */

        $kepemilikan =
            $validated['status_kepemilikan'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | HARGA SEWA
        |--------------------------------------------------------------------------
        */

        $hargaSewa = null;

        if ($kepemilikan === 'Kontrak/Sewa') {

            $hargaSewa =
                $validated['sewa_bulanan'] ?? null;

        } elseif ($kepemilikan === 'Bebas Sewa') {

            $hargaSewa =
                $validated['perkiraan_sewa'] ?? null;
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN / UPDATE
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $keluargaKode,
            $draft,
            $user,
            $kondisiAtap,
            $kepemilikan,
            $hargaSewa
        ) {

            $data = [

                'keluarga_periode_kode' => $keluargaKode,

                'jenis_bangungan' => $validated['jenis_bangunan'] ?? null,

                'is_keluarga_lain' => isset($validated['keluarga_lain'])
                        ? (
                            $validated['keluarga_lain'] === 'Ya'
                                ? 1
                                : 0
                        )
                        : null,

                'jml_keluarga_lain' => $validated['jumlah_keluarga_lain'] ?? null,

                'total_penghuni' => $validated['jumlah_orang'] ?? null,

                'kepemilikan_bangunan' => $kepemilikan,

                'bukti_kepemilikan' => $validated['bukti_kepemilikan'] ?? null,

                'status_kepemilikan_lainnya' => $kepemilikan === 'Lainnya'
                    ? ($validated['status_kepemilikan_lainnya'] ?? null)
                    : null,

                'harga_sewa_kontrak' => $hargaSewa,

                'luas_lantai' => $validated['luas_lantai'] ?? null,

                'jenis_lantai' => $validated['jenis_lantai'] ?? null,

                'kondisi_lantai' => $validated['kondisi_lantai'] ?? null,

                'jenis_dinding' => $validated['jenis_dinding'] ?? null,

                'kondisi_dinding' => $validated['kondisi_dinding'] ?? null,

                'jenis_atap' => $validated['jenis_atap'] ?? null,

                'kondisi_atap' => $kondisiAtap,

                'fasilitas_bab' => $validated['fasilitas_bab'] ?? null,

                'jenis_kloset' => $validated['jenis_toilet'] ?? null,

                'sumber_minum' => $validated['sumber_air_minum'] ?? null,

                'sumber_penerangan' => $validated['sumber_penerangan'] ?? null,

                'daya_listrik' => $validated['daya_listrik'] ?? null,

                'idpel_pln' => $validated['id_pelanggan_pln'] ?? null,

                'jml_meteran' => $validated['jumlah_meteran'] ?? null,

                'updated_by' => $user,

                'updated_at' => now(),
            ];

            $part2 = KeluargaPart2::where(
                'keluarga_periode_kode',
                $keluargaKode
            )->first();

            if (! $part2) {

                $data['created_by'] = $user;
                $data['created_at'] = now();

                KeluargaPart2::create($data);

            } else {

                $part2->update($data);
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE PROGRESS
            |--------------------------------------------------------------------------
            */

            $draft->update([

                'current_part' => $validated['action'] === 'draft' ? 2 : 3,

                'updated_by' => $user,

            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | SESSION
        |--------------------------------------------------------------------------
        */

        session([
            'draft_keluarga_id' => $draft->id,

            'part1_selesai' => true,

            'part2_selesai' => $validated['action'] === 'next',

            'part3_selesai' => false,

            'part4_selesai' => false,

            'part5_selesai' => false,
        ]);

        if ($validated['action'] === 'draft') {
            return redirect()
                ->route('kuisioner.index')
                ->with(
                    'success',
                    'Data Part 2 berhasil disimpan sebagai draft.'
                );
        }

        $issues = $this->questionnaireCompletionIssues($draft, $request);
        $missingPart2 = $this->missingQuestionsForPart(
            $issues['missing_questions'],
            2
        );

        if ($missingPart2 !== []) {
            $draft->update(['current_part' => 2]);
            session(['part2_selesai' => false]);

            return $this->redirectToIncompletePart(2, $missingPart2);
        }

        return redirect()
            ->route('kuisioner.part3')
            ->with(
                'success',
                'Data Part 2 berhasil disimpan. Silakan lanjut ke Part 3.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | PART 3
    |--------------------------------------------------------------------------
    */

    public function part3()
    {
        $keluargaKode = session('selected_keluarga_kode');

        if (! $keluargaKode) {
            return redirect()
                ->route('kuisioner.part1');
        }

        $draftId = session('draft_keluarga_id');

        if (! $draftId) {
            return redirect()
                ->route('kuisioner.part1');
        }

        $draft = $this->editableQuestionnaireQuery()->where('id', $draftId)
            ->where(
                'keluarga_periode_kode',
                $keluargaKode
            )
            ->first();

        if (! $draft) {

            session()->forget([
                'draft_keluarga_id',
                'selected_keluarga_kode',
                'part1_selesai',
                'part2_selesai',
                'part3_selesai',
                'part4_selesai',
                'part5_selesai',
            ]);

            return redirect()
                ->route('kuisioner.part1');
        }

        if ((int) $draft->current_part < 3) {

            return redirect()
                ->route('kuisioner.part1');
        }

        $dataPart3 = KeluargaPart3::where(
            'keluarga_periode_kode',
            $draft->keluarga_periode_kode
        )->first();

        return view(
            'admin.kuisioner.part3',
            compact(
                'draft',
                'dataPart3'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN PART 3
    |--------------------------------------------------------------------------
    */

    public function storePart3(Request $request)
    {
        $draftId = session('draft_keluarga_id');

        if (! $draftId) {
            return redirect()
                ->route('kuisioner.part1')
                ->with('error', 'Silakan pilih keluarga terlebih dahulu.');
        }

        $draft = $this->editableQuestionnaireQuery()
            ->where('id', $draftId)
            ->first();

        if (! $draft) {
            return redirect()
                ->route('kuisioner.part1')
                ->with('error', 'Data draft keluarga tidak ditemukan.');
        }

        if ((int) $draft->current_part < 3) {
            return redirect()
                ->route('kuisioner.part2')
                ->with('error', 'Silakan selesaikan Part 2 terlebih dahulu.');
        }

        /*
        |--------------------------------------------------------------------------
        | UBAH NOMINAL BERTITIK MENJADI ANGKA
        |--------------------------------------------------------------------------
        */

        $kolomNominal = [
            'pengeluaran_listrik_bulanan',
            'pengeluaran_pulsa_bulanan',
            'pengeluaran_internet_bulanan',
            'pengeluaran_makan_mingguan',
            'pengeluaran_nonmakan_bulanan',
            'pengeluaran_nonmakan_tahunan',
            'total_pendapatan_kerja',
            'total_pendapatan_usaha',
            'total_pendapatan_lainnya',
        ];

        foreach ($kolomNominal as $kolom) {
            $nilai = $request->input($kolom);

            if ($nilai !== null && $nilai !== '') {
                $request->merge([
                    $kolom => str_replace('.', '', (string) $nilai),
                ]);
            } else {
                $request->merge([
                    $kolom => null,
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'pengeluaran_listrik_bulanan' => ['nullable', 'numeric', 'min:0'],
            'pengeluaran_pulsa_bulanan' => ['nullable', 'numeric', 'min:0'],
            'pengeluaran_internet_bulanan' => ['nullable', 'numeric', 'min:0'],
            'pengeluaran_makan_mingguan' => ['nullable', 'numeric', 'min:0'],
            'pengeluaran_nonmakan_bulanan' => ['nullable', 'numeric', 'min:0'],
            'pengeluaran_nonmakan_tahunan' => ['nullable', 'numeric', 'min:0'],

            'pendapatan_pekerjaan' => ['nullable', 'in:Ada,Tidak'],
            'total_pendapatan_kerja' => ['nullable', 'numeric', 'min:0'],

            'total_pendapatan_usaha' => ['nullable', 'numeric', 'min:0'],

            'pendapatan_lainnya' => ['nullable', 'in:Ada,Tidak'],
            'total_pendapatan_lainnya' => ['nullable', 'numeric', 'min:0'],

            'action' => ['required', 'in:draft,next'],
        ]);

        $keluargaKode = $draft->keluarga_periode_kode
            ?: session('selected_keluarga_kode');

        if (! $keluargaKode) {
            return back()
                ->withInput()
                ->with('error', 'Kode keluarga tidak ditemukan.');
        }

        DB::beginTransaction();

        try {
            $user = auth()->user()->username
                ?? auth()->user()->name
                ?? 'admin';

            $data = [
                'keluarga_periode_kode' => $keluargaKode,

                'pengeluaran_listrik_bulanan' => $validated['pengeluaran_listrik_bulanan'] ?? null,

                'pengeluaran_pulsa_bulanan' => $validated['pengeluaran_pulsa_bulanan'] ?? null,

                'pengeluaran_internet_bulanan' => $validated['pengeluaran_internet_bulanan'] ?? null,

                'pengeluaran_makan_mingguan' => $validated['pengeluaran_makan_mingguan'] ?? null,

                'pengeluaran_nonmakan_bulanan' => $validated['pengeluaran_nonmakan_bulanan'] ?? null,

                'pengeluaran_nonmakan_tahunan' => $validated['pengeluaran_nonmakan_tahunan'] ?? null,

                'pendapatan_pekerjaan' => $validated['pendapatan_pekerjaan'] ?? null,

                'total_pendapatan_kerja' => ($validated['pendapatan_pekerjaan'] ?? null) === 'Tidak'
                        ? null
                        : ($validated['total_pendapatan_kerja'] ?? null),

                'total_pendapatan_usaha' => $validated['total_pendapatan_usaha'] ?? null,

                'pendapatan_lainnya' => $validated['pendapatan_lainnya'] ?? null,

                'total_pendapatan_lainnya' => ($validated['pendapatan_lainnya'] ?? null) === 'Tidak'
                        ? null
                        : ($validated['total_pendapatan_lainnya'] ?? null),

                'updated_by' => $user,
                'updated_at' => now(),
            ];

            $dataPart3 = KeluargaPart3::where(
                'keluarga_periode_kode',
                $keluargaKode
            )->first();

            if ($dataPart3) {
                $dataPart3->update($data);
            } else {
                $data['created_by'] = $user;
                $data['created_at'] = now();

                KeluargaPart3::create($data);
            }

            /*
            |--------------------------------------------------------------------------
            | PERBARUI PROGRES DRAFT
            |--------------------------------------------------------------------------
            */

            $draft->current_part =
                $validated['action'] === 'draft' ? 3 : 4;

            $draft->updated_by = $user;
            $draft->save();

            session([
                'draft_keluarga_id' => $draft->id,
                'selected_keluarga_kode' => $keluargaKode,
                'part3_selesai' => $validated['action'] === 'next',
                'part4_selesai' => false,
                'part5_selesai' => false,
            ]);

            DB::commit();

            if ($validated['action'] === 'draft') {
                return redirect()
                    ->route('kuisioner.index')
                    ->with(
                        'success',
                        'Data Part 3 berhasil disimpan sebagai draft.'
                    );
            }

            $issues = $this->questionnaireCompletionIssues($draft, $request);
            $missingPart3 = $this->missingQuestionsForPart(
                $issues['missing_questions'],
                3
            );

            if ($missingPart3 !== []) {
                $draft->update(['current_part' => 3]);
                session(['part3_selesai' => false]);

                return $this->redirectToIncompletePart(3, $missingPart3);
            }

            return redirect()
                ->route('kuisioner.part4')
                ->with('success', 'Data Part 3 berhasil disimpan.');

        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Data Part 3 gagal disimpan. Silakan periksa kembali data dan struktur tabel.'
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PART 4
    |--------------------------------------------------------------------------
    */

    public function part4()
    {
        $keluargaKode = session('selected_keluarga_kode');
        $draftId = session('draft_keluarga_id');

        if (! $keluargaKode || ! $draftId) {
            return redirect()->route('kuisioner.part1');
        }

        $draft = $this->editableQuestionnaireQuery()->where('id', $draftId)
            ->where('keluarga_periode_kode', $keluargaKode)
            ->first();

        if (! $draft) {
            return redirect()->route('kuisioner.part1');
        }

        if ((int) $draft->current_part < 4) {
            return redirect()->route('kuisioner.part1');
        }

        $dataPart4 = KeluargaPart4::where(
            'keluarga_periode_kode',
            $draft->keluarga_periode_kode
        )->get();

        $dataPart4Map = $dataPart4->keyBy('aset_keluarga');

        return view('admin.kuisioner.part4', compact(
            'draft',
            'dataPart4',
            'dataPart4Map'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN PART 4
    |--------------------------------------------------------------------------
    */

    public function storePart4(Request $request)
    {
        $draftId = session('draft_keluarga_id');

        if (! $draftId) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'error',
                    'Silakan pilih keluarga terlebih dahulu.'
                );
        }

        $keluargaKode = session('selected_keluarga_kode');

        $draft = $this->editableQuestionnaireQuery()
            ->where('id', $draftId)
            ->when($keluargaKode, function ($query) use ($keluargaKode) {
                $query->where('keluarga_periode_kode', $keluargaKode);
            })
            ->first();

        if (! $draft) {

            session()->forget([
                'draft_keluarga_id',
            ]);

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'error',
                    'Data draft keluarga tidak ditemukan.'
                );
        }

        if ((int) $draft->current_part < 4) {

            return redirect()
                ->route('kuisioner.part3')
                ->with(
                    'error',
                    'Silakan selesaikan Part 3 terlebih dahulu.'
                );
        }

        $request->validate([

            'aset' => ['nullable', 'array'],

            'aset.*.punya' => ['nullable', 'in:0,1'],

            'aset.*.jumlah' => ['nullable', 'integer', 'min:0'],

            'action' => ['required', 'in:draft,next'],
        ]);

        $keluargaKode = $draft->keluarga_periode_kode;

        if (! $keluargaKode) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Kode keluarga tidak ditemukan.'
                );
        }

        DB::beginTransaction();

        try {

            $asetList = [

                'Tabung Gas 3 KG',

                'Tabung Gas 5,5 KG atau Lebih',

                'Lemari Es/Kulkas',

                'AC (Air Conditioner)',

                'Emas/Perhiasan',

                'Komputer/Laptop/Tablet',

                'Sepeda Motor',

                'Mobil',

                'Rumah/Bangunan (selain yang ditempati)',

                'Lahan Lainnya',
            ];

            foreach ($asetList as $aset) {

                $data = $request->input(
                    'aset.'.md5($aset),
                    []
                );

                $punya = isset($data['punya'])
                    ? (int) $data['punya']
                    : null;

                $jumlah = $punya === 1
                    ? ($data['jumlah'] ?? null)
                    : ($punya === 0 ? 0 : null);

                $existing = KeluargaPart4::where(
                    'keluarga_periode_kode',
                    $keluargaKode
                )
                    ->where(
                        'aset_keluarga',
                        $aset
                    )
                    ->first();

                $saveData = [

                    'keluarga_periode_kode' => $keluargaKode,

                    'aset_keluarga' => $aset,

                    'is_punya_aset' => $punya,

                    'jml_aset' => $jumlah,

                    'updated_by' => auth()->user()->username ?? 'admin',

                    'updated_at' => now(),
                ];

                if ($existing) {

                    $existing->update($saveData);

                } else {

                    $saveData['created_by'] =
                        auth()->user()->username ?? 'admin';

                    $saveData['created_at'] =
                        now();

                    KeluargaPart4::create($saveData);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE PROGRESS
            |--------------------------------------------------------------------------
            */

            $draft->current_part =
                $request->action === 'draft' ? 4 : 5;

            $draft->updated_by =
                auth()->user()->username ?? 'admin';

            $draft->save();

            session([
                'part4_selesai' => $request->action === 'next',
                'part5_selesai' => false,
            ]);

            DB::commit();

            if ($request->action === 'draft') {
                return redirect()
                    ->route('kuisioner.index')
                    ->with(
                        'success',
                        'Data Part 4 berhasil disimpan sebagai draft.'
                    );
            }

            $issues = $this->questionnaireCompletionIssues($draft, $request);
            $missingPart4 = $this->missingQuestionsForPart(
                $issues['missing_questions'],
                4
            );

            if ($missingPart4 !== []) {
                $draft->update(['current_part' => 4]);
                session(['part4_selesai' => false]);

                return $this->redirectToIncompletePart(4, $missingPart4);
            }

            return redirect()
                ->route('kuisioner.part5')
                ->with(
                    'success',
                    'Data Part 4 berhasil disimpan.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Data Part 4 gagal disimpan: '.
                    $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PART 5
    |--------------------------------------------------------------------------
    */

    public function part5(Request $request)
    {
        $keluargaKode = session('selected_keluarga_kode');

        if (! $keluargaKode) {
            return redirect()
                ->route('kuisioner.part1');
        }

        $draftId = session('draft_keluarga_id');

        if (! $draftId) {
            return redirect()
                ->route('kuisioner.part1');
        }

        $draft = $this->editableQuestionnaireQuery()->where('id', $draftId)
            ->where(
                'keluarga_periode_kode',
                $keluargaKode
            )
            ->first();

        if (! $draft) {

            session()->forget([
                'draft_keluarga_id',
                'selected_keluarga_kode',
                'part1_selesai',
                'part2_selesai',
                'part3_selesai',
                'part4_selesai',
                'part5_selesai',
            ]);

            return redirect()
                ->route('kuisioner.part1');
        }

        if ((int) $draft->current_part < 5) {
            $draft->current_part = 5;
            $draft->save();
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL SELURUH ANGGOTA KELUARGA
        |--------------------------------------------------------------------------
        */

        $kodeKeluarga = $this->familyCodeForQuestionnaire(
            $draft->keluarga_periode_kode
        );
        $anggota = KeluargaAnggota::where(
            'keluarga_kode',
            $kodeKeluarga
        )
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | AMBIL JAWABAN PART 5
        |--------------------------------------------------------------------------
        */

        $dataPart5 = KeluargaPart5::where(
            'keluarga_periode_kode',
            $draft->keluarga_periode_kode
        )
            ->get()
            ->keyBy('keluarga_anggota_kode');

        /*
        |--------------------------------------------------------------------------
        | CEK KELENGKAPAN SEMUA ANGGOTA
        |--------------------------------------------------------------------------
        */

        $jumlahAnggota = $anggota->count();

        $statusPart5 = [];

        foreach ($anggota as $item) {
            $data = $dataPart5->get($item->kode);

            $statusPart5[$item->kode] = $data
                ? $this->part5DataLengkap($data)
                : false;
        }

        $jumlahSelesai = count(array_filter($statusPart5));

        $semuaAnggotaSelesai = $jumlahAnggota === 0
            && $jumlahSelesai === $jumlahAnggota;

        return view(
            'admin.kuisioner.part5',
            compact(
                'draft',
                'anggota',
                'dataPart5',
                'statusPart5',
                'jumlahAnggota',
                'jumlahSelesai',
                'semuaAnggotaSelesai'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PART 5 ANGGOTA
    |--------------------------------------------------------------------------
    */

    public function part5Anggota($kode)
    {
        $keluargaKode = session('selected_keluarga_kode');

        if (! $keluargaKode) {
            return redirect()
                ->route('kuisioner.part1');
        }

        $draftId = session('draft_keluarga_id');

        if (! $draftId) {
            return redirect()
                ->route('kuisioner.part1');
        }

        $draft = $this->editableQuestionnaireQuery()->where('id', $draftId)
            ->where(
                'keluarga_periode_kode',
                $keluargaKode
            )
            ->first();

        if (! $draft) {

            session()->forget([
                'draft_keluarga_id',
                'selected_keluarga_kode',
                'part1_selesai',
                'part2_selesai',
                'part3_selesai',
                'part4_selesai',
                'part5_selesai',
            ]);

            return redirect()
                ->route('kuisioner.part1');
        }

        if ((int) $draft->current_part < 5) {

            return redirect()
                ->route('kuisioner.part1');
        }

        /*
        |--------------------------------------------------------------------------
        | PASTIKAN ANGGOTA MILIK KELUARGA
        |--------------------------------------------------------------------------
        */

        $kodeKeluarga = $this->familyCodeForQuestionnaire(
            $draft->keluarga_periode_kode
        );
        $anggota = KeluargaAnggota::where(
            'keluarga_kode',
            $kodeKeluarga
        )
            ->where(
                'kode',
                $kode
            )
            ->first();

        if (! $anggota) {

            return redirect()
                ->route('kuisioner.part5')
                ->with(
                    'error',
                    'Anggota keluarga tidak ditemukan.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA YANG SUDAH DISIMPAN
        |--------------------------------------------------------------------------
        */

        $dataPart5 = KeluargaPart5::where(
            'keluarga_periode_kode',
            $draft->keluarga_periode_kode
        )
            ->where(
                'keluarga_anggota_kode',
                $anggota->kode
            )
            ->first();

        return view(
            'admin.kuisioner.part5-anggota',
            compact(
                'draft',
                'anggota',
                'dataPart5'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN PART 5 ANGGOTA
    |--------------------------------------------------------------------------
    */

    public function storePart5Anggota(
        Request $request,
        $kode
    ) {
        $draftId =
            session('draft_keluarga_id');

        if (! $draftId) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'error',
                    'Silakan mulai pengisian kuisioner terlebih dahulu.'
                );
        }

        $draft = $this->editableQuestionnaireQuery()
            ->whereKey($draftId)
            ->first();

        if (! $draft) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'error',
                    'Data keluarga tidak ditemukan.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CARI ANGGOTA
        |--------------------------------------------------------------------------
        */

        $kodeKeluarga = $this->familyCodeForQuestionnaire(
            $draft->keluarga_periode_kode
        );
        $anggota = KeluargaAnggota::where(
            'keluarga_kode',
            $kodeKeluarga
        )
            ->where(
                'kode',
                $kode
            )
            ->first();

        if (! $anggota) {

            return redirect()
                ->route('kuisioner.part5')
                ->with(
                    'error',
                    'Anggota keluarga tidak ditemukan.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI PART 5
        |--------------------------------------------------------------------------
        */

        $keberadaan = $request->input('keberadaan');

        $anggotaTidakAktif = in_array($keberadaan, [
            'Meninggal',
            'Sudah Pisah kartu keluarga',
        ], true);

        if ($anggotaTidakAktif) {
            $validated = $request->validate([
                'keberadaan' => 'required|string|max:255',
                'no_hp' => 'nullable|string|max:16',
            ]);

            // Pertanyaan lanjutan tidak wajib untuk kondisi ini.
            $validated = array_merge($validated, [
                'jenis_kelamin' => null,
                'tanggal_lahir' => null,
                'status_perkawinan' => null,
                'status_sekolah' => null,
                'ijazah_tertinggi' => null,
                'ijazah_lainnya' => null,
                'pekerjaan_utama' => null,
                'pekerjaan_lainnya' => null,
                'status_pekerjaan' => null,
                'kepemilikan_rekening' => null,
                'is_disabilitas_fisik' => null,
                'is_disabilitas_mental' => null,
                'is_disabilitas_intelektual' => null,
                'is_disabilitas_netra' => null,
                'is_disabilitas_rungu' => null,
                'is_disabilitas_wicara' => null,
                'keluhan_kesehatan' => [],
            ]);
        } else {
            $validated = $request->validate([
                'keberadaan' => 'required|string|max:255',
                'no_hp' => 'nullable|string|max:16',
                'jenis_kelamin' => 'required|string|max:16',
                'tanggal_lahir' => 'nullable|date',
                'status_perkawinan' => 'required|string|max:255',
                'status_sekolah' => 'required|string|max:255',
                'ijazah_tertinggi' => 'required|string|max:255',

                'ijazah_lainnya' => [
                    'nullable',
                    'required_if:ijazah_tertinggi,Lainnya',
                    'string',
                    'max:255',
                ],

                'pekerjaan_utama' => 'required|string|max:255',

                'pekerjaan_lainnya' => [
                    'nullable',
                    'required_if:pekerjaan_utama,Lainnya',
                    'string',
                    'max:255',
                ],

                'status_pekerjaan' => 'required_unless:pekerjaan_utama,Tidak Bekerja|string|max:255',

                'kepemilikan_rekening' => 'required|string|max:255',
                'is_disabilitas_fisik' => 'required|in:0,1',
                'is_disabilitas_mental' => 'required|in:0,1',
                'is_disabilitas_intelektual' => 'required|in:0,1',
                'is_disabilitas_netra' => 'required|in:0,1',
                'is_disabilitas_rungu' => 'required|in:0,1',
                'is_disabilitas_wicara' => 'required|in:0,1',

                'keluhan_kesehatan' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'keluhan_kesehatan.*' => [
                    'required',
                    'string',
                    'max:255',
                ],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $data = [

            'keluarga_periode_kode' => $draft->keluarga_periode_kode,

            'keluarga_anggota_kode' => $anggota->kode,

            'keberadaan' => $validated['keberadaan'],

            'no_hp' => $validated['no_hp'] ?? null,

            'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,

            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,

            'status_perkawinan' => $validated['status_perkawinan'] ?? null,

            'status_sekolah' => $validated['status_sekolah'] ?? null,

            'ijazah_tertinggi' => $validated['ijazah_tertinggi'] ?? null,

            'ijazah_lainnya' => ($validated['ijazah_tertinggi'] ?? null) === 'Lainnya'
                ? ($validated['ijazah_lainnya'] ?? null)
                : null,

            'pekerjaan_utama' => $validated['pekerjaan_utama'] ?? null,

            'pekerjaan_lainnya' => $validated['pekerjaan_lainnya'] ?? null,

            'status_pekerjaan' => $validated['status_pekerjaan'] ?? null,

            'kepemilikan_rekening' => $validated['kepemilikan_rekening'] ?? null,

            'is_disabilitas_fisik' => $validated['is_disabilitas_fisik'] ?? null,

            'is_disabilitas_mental' => $validated['is_disabilitas_mental'] ?? null,

            'is_disabilitas_intelektual' => $validated['is_disabilitas_intelektual'] ?? null,

            'is_disabilitas_netra' => $validated['is_disabilitas_netra'] ?? null,

            'is_disabilitas_rungu' => $validated['is_disabilitas_rungu'] ?? null,

            'is_disabilitas_wicara' => $validated['is_disabilitas_wicara'] ?? null,

            'keluhan_kesehatan' => json_encode(
                $validated['keluhan_kesehatan'] ?? [],
                JSON_UNESCAPED_UNICODE
            ),

            'created_by' => auth()->id() ?? 'system',

            'updated_by' => auth()->id() ?? 'system',

            'updated_at' => now(),
        ];

        /*
        |--------------------------------------------------------------------------
        | UPDATE ATAU INSERT
        |--------------------------------------------------------------------------
        */

        $existing = KeluargaPart5::where(
            'keluarga_periode_kode',
            $draft->keluarga_periode_kode
        )
            ->where(
                'keluarga_anggota_kode',
                $anggota->kode
            )
            ->first();

        if ($existing) {

            $existing->update($data);

        } else {

            $data['created_at'] =
                now();

            KeluargaPart5::create($data);
        }

        return redirect()
            ->route('kuisioner.part5')
            ->with(
                'success',
                'Data anggota berhasil disimpan.'
            );
    }

    /**
     * @return array{
     *     missing_questions: array<int, string>,
     *     route: string|null,
     *     route_parameters: array<string, string>
     * }
     */
    private function questionnaireCompletionIssues(
        KeluargaPart1 $draft,
        Request $request
    ): array {
        $missingQuestions = [];
        $firstRoute = null;
        $firstRouteParameters = [];

        $addMissing = function (
            int $part,
            string $question,
            string $route,
            array $routeParameters = []
        ) use (&$missingQuestions, &$firstRoute, &$firstRouteParameters): void {
            $question = preg_replace('/^(\d+)\.\s*/', '$1: ', $question);

            $missingQuestions[] = 'Part '.$part.'  Pertanyaan No. '.$question;

            if ($firstRoute === null) {
                $firstRoute = $route;
                $firstRouteParameters = $routeParameters;
            }
        };

        $isAnswered = static function (mixed $value): bool {
            return $value !== null
                && (! is_string($value) || trim($value) !== '');
        };
        $firstAnswered = static function (mixed ...$values) use ($isAnswered): mixed {
            foreach ($values as $value) {
                if ($isAnswered($value)) {
                    return $value;
                }
            }

            return null;
        };

        $keluarga = $this->familyForQuestionnaire(
            $draft->keluarga_periode_kode
        );

        $kecamatan = $draft->kecamatan;
        if (! $isAnswered($kecamatan) && $keluarga?->kecamatan_id) {
            $kecamatan = DB::table('kecamatans')
                ->where('kecamatan_id', $keluarga->kecamatan_id)
                ->value('deskripsi');
        }

        $kelurahan = $draft->kelurahan;
        if (! $isAnswered($kelurahan) && $keluarga?->kelurahan_id) {
            $kelurahan = DB::table('kelurahans')
                ->where('kelurahan_id', $keluarga->kelurahan_id)
                ->value('deskripsi');
        }

        $rt = trim((string) ($keluarga?->rt ?? ''));
        $rw = trim((string) ($keluarga?->rw ?? ''));
        $rtRw = $rt !== '' || $rw !== '' ? $rt.' / '.$rw : null;

        $kodeKeluarga = $this->familyCodeForQuestionnaire(
            $draft->keluarga_periode_kode
        );
        $jumlahAnggota = KeluargaAnggota::where(
            'keluarga_kode',
            $kodeKeluarga
        )->count();

        $part1Questions = [
            '1. NIK' => $firstAnswered($draft->nik, $keluarga?->nik),
            '2. Nama kepala keluarga' => $firstAnswered($draft->nama_kepala_keluarga, $keluarga?->nama_lengkap),
            '3. Status dalam keluarga' => $firstAnswered(
                $keluarga?->anggota->first()?->status_keluarga,
                $keluarga?->status_keluarga
            ),
            '4. Nomor KK' => $firstAnswered($draft->no_kk, $keluarga?->no_kk),
            '5. Jumlah anggota keluarga' => $jumlahAnggota,
            '6. Provinsi' => $firstAnswered($draft->provinsi, 'Jawa Timur'),
            '7. Kabupaten/Kota' => $firstAnswered($draft->daerah, 'Kota Pasuruan'),
            '8. Kecamatan' => $kecamatan,
            '9. Kelurahan' => $kelurahan,
            '10. Kode pos' => $firstAnswered($draft->kode_pos, $keluarga?->kode_pos),
            '11. RT/RW' => $rtRw,
            '12. Alamat lengkap' => $firstAnswered(
                $draft->alamat_lengkap,
                $keluarga?->alamat_lengkap
            ),
            '13. Jalan' => $firstAnswered(
                $draft->jalan_rumah,
                $keluarga?->jalan_rumah,
                $keluarga?->jalan
            ),
            '14. Nomor rumah' => $firstAnswered(
                $draft->nomor_rumah,
                $keluarga?->nomor_rumah
            ),
            '15. Kesesuaian alamat dengan KK' => $draft->is_alamat_sesuai,
        ];

        foreach ($part1Questions as $label => $value) {
            if (! $isAnswered($value)) {
                $addMissing(1, $label, 'kuisioner.part1');
            }
        }

        if (
            (string) $draft->is_alamat_sesuai === '0'
            && ! $isAnswered($draft->alasan_tidak_sesuai)
        ) {
            $addMissing(
                1,
                '15. Alasan alamat tidak sesuai dengan KK',
                'kuisioner.part1'
            );
        }

        $dataPart2 = KeluargaPart2::where(
            'keluarga_periode_kode',
            $draft->keluarga_periode_kode
        )->first();

        $part2Questions = [
            '16. Jenis bangunan tempat tinggal' => $dataPart2?->jenis_bangungan,
            '17. Keberadaan keluarga lain dalam satu bangunan' => $dataPart2?->is_keluarga_lain,
            '18. Jumlah orang yang tinggal dalam rumah' => $dataPart2?->total_penghuni,
            '19. Status kepemilikan rumah' => $dataPart2?->kepemilikan_bangunan,
            '20. Luas lantai rumah' => $dataPart2?->luas_lantai,
            '21. Jenis lantai' => $dataPart2?->jenis_lantai,
            '22. Kondisi lantai' => $dataPart2?->kondisi_lantai,
            '23. Jenis dinding' => $dataPart2?->jenis_dinding,
            '24. Kondisi dinding' => $dataPart2?->kondisi_dinding,
            '25. Jenis atap' => $dataPart2?->jenis_atap,
            '26. Kondisi atap' => $dataPart2?->kondisi_atap,
            '27. Fasilitas buang air besar' => $dataPart2?->fasilitas_bab,
            '28. Jenis kloset' => $dataPart2?->jenis_kloset,
            '29. Sumber air minum' => $dataPart2?->sumber_minum,
            '30. Sumber penerangan' => $dataPart2?->sumber_penerangan,
            '31. Daya listrik' => $dataPart2?->daya_listrik,
            '32. ID pelanggan PLN/nomor meteran' => $dataPart2?->idpel_pln,
            '33. Jumlah meteran listrik' => $dataPart2?->jml_meteran,
        ];

        foreach ($part2Questions as $label => $value) {
            if (! $isAnswered($value)) {
                $addMissing(2, $label, 'kuisioner.part2');
            }
        }

        if ((int) $dataPart2?->is_keluarga_lain === 1 && ! $isAnswered($dataPart2?->jml_keluarga_lain)) {
            $addMissing(
                2,
                '17. Jumlah keluarga lain dalam satu bangunan',
                'kuisioner.part2'
            );
        }

        if ($dataPart2?->kepemilikan_bangunan === 'Milik Sendiri' && ! $isAnswered($dataPart2?->bukti_kepemilikan)) {
            $addMissing(2, '19. Bukti kepemilikan rumah', 'kuisioner.part2');
        }

        if (
            $dataPart2?->kepemilikan_bangunan === 'Lainnya'
            && ! $isAnswered($dataPart2?->status_kepemilikan_lainnya)
        ) {
            $addMissing(
                2,
                '19. Keterangan status kepemilikan lainnya',
                'kuisioner.part2'
            );
        }

        if (
            in_array($dataPart2?->kepemilikan_bangunan, ['Kontrak/Sewa', 'Bebas Sewa'], true)
            && ! $isAnswered($dataPart2?->harga_sewa_kontrak)
        ) {
            $addMissing(
                2,
                $dataPart2->kepemilikan_bangunan === 'Kontrak/Sewa'
                    ? '19. Besaran sewa rumah per bulan'
                    : '19. Perkiraan nilai sewa rumah per bulan',
                'kuisioner.part2'
            );
        }

        $dataPart3 = KeluargaPart3::where(
            'keluarga_periode_kode',
            $draft->keluarga_periode_kode
        )->first();

        $part3Questions = [
            '34. Pengeluaran listrik per bulan' => $dataPart3?->pengeluaran_listrik_bulanan,
            '35. Pengeluaran pulsa per bulan' => $dataPart3?->pengeluaran_pulsa_bulanan,
            '36. Pengeluaran internet per bulan' => $dataPart3?->pengeluaran_internet_bulanan,
            '37. Pengeluaran makanan per minggu' => $dataPart3?->pengeluaran_makan_mingguan,
            '38. Pengeluaran bukan makanan rutin per bulan' => $dataPart3?->pengeluaran_nonmakan_bulanan,
            '39. Pengeluaran bukan makanan rutin per tahun' => $dataPart3?->pengeluaran_nonmakan_tahunan,
            '40. Ada/tidaknya pendapatan dari pekerjaan' => $dataPart3?->pendapatan_pekerjaan,
            '41. Pendapatan dari usaha per bulan' => $dataPart3?->total_pendapatan_usaha,
            '42. Ada/tidaknya pendapatan lainnya' => $dataPart3?->pendapatan_lainnya,
        ];

        foreach ($part3Questions as $label => $value) {
            if (! $isAnswered($value)) {
                $addMissing(3, $label, 'kuisioner.part3');
            }
        }

        if (
            $dataPart3?->pendapatan_pekerjaan === 'Ada'
            && ! $isAnswered($dataPart3?->total_pendapatan_kerja)
        ) {
            $addMissing(3, '40. Total pendapatan pekerjaan per bulan', 'kuisioner.part3');
        }

        if (
            $dataPart3?->pendapatan_lainnya === 'Ada'
            && ! $isAnswered($dataPart3?->total_pendapatan_lainnya)
        ) {
            $addMissing(3, '42. Total pendapatan lainnya per bulan', 'kuisioner.part3');
        }

        $asetList = [
            'Tabung Gas 3 KG',
            'Tabung Gas 5,5 KG atau Lebih',
            'Lemari Es/Kulkas',
            'AC (Air Conditioner)',
            'Emas/Perhiasan',
            'Komputer/Laptop/Tablet',
            'Sepeda Motor',
            'Mobil',
            'Rumah/Bangunan (selain yang ditempati)',
            'Lahan Lainnya',
        ];

        $dataPart4 = KeluargaPart4::where(
            'keluarga_periode_kode',
            $draft->keluarga_periode_kode
        )->get()->keyBy('aset_keluarga');

        foreach ($asetList as $index => $aset) {
            $questionNumber = $index + 43;
            $answer = $dataPart4->get($aset);

            if (! $isAnswered($answer?->is_punya_aset)) {
                $addMissing(
                    4,
                    $questionNumber.'. '.$aset,
                    'kuisioner.part4'
                );
            } elseif (
                (int) $answer->is_punya_aset === 1
                && ! $isAnswered($answer->jml_aset)
            ) {
                $addMissing(
                    4,
                    $questionNumber.'. Jumlah '.$aset,
                    'kuisioner.part4'
                );
            }
        }

        $kodeKeluarga = $this->familyCodeForQuestionnaire(
            $draft->keluarga_periode_kode
        );
        $anggotaList = KeluargaAnggota::where(
            'keluarga_kode',
            $kodeKeluarga
        )->orderBy('id')->get();

        $dataPart5 = KeluargaPart5::where(
            'keluarga_periode_kode',
            $draft->keluarga_periode_kode
        )
            ->get()
            ->keyBy('keluarga_anggota_kode');

        $part5Fields = [
            'keberadaan' => '52. Keberadaan anggota keluarga',
            'jenis_kelamin' => '54. Jenis kelamin',
            'status_perkawinan' => '56. Status perkawinan',
            'status_sekolah' => '57. Status partisipasi sekolah',
            'ijazah_tertinggi' => '58. Ijazah/STTB tertinggi',
            'pekerjaan_utama' => '59. Profesi pekerjaan utama',
            'kepemilikan_rekening' => '61. Kepemilikan rekening/dompet digital',
            'is_disabilitas_fisik' => '62. Disabilitas fisik',
            'is_disabilitas_mental' => '63. Disabilitas mental',
            'is_disabilitas_intelektual' => '64. Disabilitas intelektual',
            'is_disabilitas_netra' => '65. Disabilitas sensorik netra',
            'is_disabilitas_rungu' => '66. Disabilitas sensorik rungu',
            'is_disabilitas_wicara' => '67. Disabilitas sensorik wicara',
        ];

        foreach ($anggotaList as $anggota) {
            $answer = $dataPart5->get($anggota->kode);
            $namaAnggota = $anggota->nama_lengkap;
            $routeParameters = ['kode' => $anggota->kode];

            if (in_array($answer?->keberadaan, [
                'Meninggal',
                'Sudah Pisah kartu keluarga',
            ], true)) {
                continue;
            }

            foreach ($part5Fields as $field => $label) {
                if (! $isAnswered($answer?->{$field})) {
                    $addMissing(
                        5,
                        $namaAnggota.' — '.$label,
                        'kuisioner.part5.anggota',
                        $routeParameters
                    );
                }
            }

            if (
                $answer?->ijazah_tertinggi === 'Lainnya'
                && ! $isAnswered($answer->ijazah_lainnya)
            ) {
                $addMissing(
                    5,
                    $namaAnggota.' — 58. Keterangan ijazah lainnya',
                    'kuisioner.part5.anggota',
                    $routeParameters
                );
            }

            if (
                $answer?->pekerjaan_utama === 'Lainnya'
                && ! $isAnswered($answer->pekerjaan_lainnya)
            ) {
                $addMissing(
                    5,
                    $namaAnggota.' — 59. Keterangan pekerjaan lainnya',
                    'kuisioner.part5.anggota',
                    $routeParameters
                );
            }

            if (
                $answer?->pekerjaan_utama !== null
                && $answer->pekerjaan_utama !== 'Tidak Bekerja'
                && ! $isAnswered($answer->status_pekerjaan)
            ) {
                $addMissing(
                    5,
                    $namaAnggota.' — 60. Status kedudukan dalam pekerjaan',
                    'kuisioner.part5.anggota',
                    $routeParameters
                );
            }

            $keluhanKesehatan = $answer?->keluhan_kesehatan;
            if (is_string($keluhanKesehatan)) {
                $decodedKeluhan = json_decode($keluhanKesehatan, true);
                $keluhanKesehatan = is_array($decodedKeluhan)
                    ? $decodedKeluhan
                    : (trim($keluhanKesehatan) !== '' ? [$keluhanKesehatan] : []);
            }

            if (! is_array($keluhanKesehatan) || count($keluhanKesehatan) === 0) {
                $addMissing(
                    5,
                    $namaAnggota.' — 68. Keluhan kesehatan',
                    'kuisioner.part5.anggota',
                    $routeParameters
                );
            }
        }

        $fotoTersimpan = KeluargaFotoRumah::where(
            'keluarga_periode_kode',
            $draft->keluarga_periode_kode
        )->get()->keyBy('jenis_foto');

        $fotoQuestions = [
            'tampak_depan' => '69. Foto tampak depan rumah',
            'ruang_tamu' => '70. Foto ruang tamu',
            'kamar_mandi' => '71. Foto kamar mandi',
        ];
        $fotoDihapus = $request->input('delete_foto', []);

        foreach ($fotoQuestions as $field => $label) {
            if (
                (! $fotoTersimpan->has($field) || ! empty($fotoDihapus[$field]))
                && ! $request->hasFile($field)
            ) {
                $addMissing(5, $label, 'kuisioner.part5.foto');
            }
        }

        return [
            'missing_questions' => $missingQuestions,
            'route' => $firstRoute,
            'route_parameters' => $firstRouteParameters,
        ];
    }

    /**
     * @param array{
     *     missing_questions: array<int, string>,
     *     route: string|null,
     *     route_parameters: array<string, string>
     * } $issues
     */
    private function redirectToIncompleteQuestionnaire(array $issues): RedirectResponse
    {
        $route = $issues['route'] ?? 'kuisioner.part1';

        $redirect = redirect()->route(
            $route,
            $issues['route_parameters'] ?? []
        );

        $redirect->with(
            'warning',
            'Lengkapi pertanyaan yang belum terisi.'
        );

        // Part 5 hanya menampilkan notifikasi, tanpa daftar pertanyaan kosong.
        if (
            $route !== 'kuisioner.part5'
            && $route !== 'kuisioner.part5.foto'
            && $route !== 'kuisioner.part5.anggota'
        ) {
            $redirect->with(
                'missing_questions',
                $issues['missing_questions'] ?? []
            );
        }

        return $redirect;
    }

    /**
     * @param  array<int, string>  $missingQuestions
     * @return array<int, string>
     */
    private function missingQuestionsForPart(
        array $missingQuestions,
        int $part
    ): array {
        $partPrefix = 'Part '.$part.'  Pertanyaan No. ';

        return array_values(array_filter(
            $missingQuestions,
            static fn (string $question): bool => str_starts_with(
                $question,
                $partPrefix
            )
        ));
    }

    /**
     * @param  array<int, string>  $missingQuestions
     */
    private function redirectToIncompletePart(
        int $part,
        array $missingQuestions
    ): RedirectResponse {
        return redirect()
            ->route('kuisioner.part'.$part)
            ->withInput()
            ->with('warning', 'Lengkapi pertanyaan yang belum dijawab!')
            ->with('missing_questions', $missingQuestions);
    }

    private function part5DataLengkap($data): bool
    {
        if (! $data) {
            return false;
        }

        if (in_array($data->keberadaan, [
            'Meninggal',
            'Sudah Pisah kartu keluarga',
        ], true)) {
            return true;
        }

        $kolomWajib = [
            'keberadaan',
            'jenis_kelamin',
            'status_perkawinan',
            'status_sekolah',
            'ijazah_tertinggi',
            'pekerjaan_utama',
            'kepemilikan_rekening',
            'is_disabilitas_fisik',
            'is_disabilitas_mental',
            'is_disabilitas_intelektual',
            'is_disabilitas_netra',
            'is_disabilitas_rungu',
            'is_disabilitas_wicara',
        ];

        foreach ($kolomWajib as $kolom) {
            if (
                ! isset($data->{$kolom}) ||
                trim((string) $data->{$kolom}) === ''
            ) {
                return false;
            }
        }

        if (
            $data->ijazah_tertinggi === 'Lainnya' &&
            trim((string) $data->ijazah_lainnya) === ''
        ) {
            return false;
        }

        if (
            $data->pekerjaan_utama !== 'Tidak Bekerja' &&
            trim((string) $data->status_pekerjaan) === ''
        ) {
            return false;
        }

        $keluhan = $data->keluhan_kesehatan;

        if (is_string($keluhan)) {
            $keluhan = json_decode($keluhan, true);
        }

        if (! is_array($keluhan) || count($keluhan) < 1) {
            return false;
        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | PART 5 FOTO
    |--------------------------------------------------------------------------
    */

    public function part5Foto()
    {
        $keluargaKode = session('selected_keluarga_kode');

        if (! $keluargaKode) {
            return redirect()
                ->route('kuisioner.part1');
        }

        $draftId = session('draft_keluarga_id');

        if (! $draftId) {
            return redirect()
                ->route('kuisioner.part1');
        }

        $draft = $this->editableQuestionnaireQuery()->where('id', $draftId)
            ->where(
                'keluarga_periode_kode',
                $keluargaKode
            )
            ->first();

        if (! $draft) {

            session()->forget([
                'draft_keluarga_id',
                'selected_keluarga_kode',
                'part1_selesai',
                'part2_selesai',
                'part3_selesai',
                'part4_selesai',
                'part5_selesai',
            ]);

            return redirect()
                ->route('kuisioner.part1');
        }

        if ((int) $draft->current_part < 5) {
            return redirect()
                ->route('kuisioner.part1');
        }

        $fotoRumah = KeluargaFotoRumah::where(
            'keluarga_periode_kode',
            $draft->keluarga_periode_kode
        )
            ->get()
            ->keyBy('jenis_foto');

        return view(
            'admin.kuisioner.part5-foto',
            compact('draft', 'fotoRumah')
        );
    }
    /*--------------------------------------------------------------------------
    SIMPAN PART 5 FOTO
    --------------------------------------------------------------------------
    */

    public function storePart5Foto(Request $request)
    {
        $action = $request->input('action', 'final');
        $isDraft = $action === 'draft';
        $isAutosave = $action === 'upload';

        /*
        |--------------------------------------------------------------------------
        | AMBIL DRAFT AKTIF
        |--------------------------------------------------------------------------
        */

        $draftId =
            session('draft_keluarga_id');

        if (! $draftId) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'error',
                    'Silakan mulai pengisian kuisioner terlebih dahulu.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CARI DATA PART 1
        |--------------------------------------------------------------------------
        */

        $draft = $this->editableQuestionnaireQuery()
            ->whereKey($draftId)
            ->first();

        if (! $draft) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'error',
                    'Data keluarga tidak ditemukan.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | PASTIKAN PART 5
        |--------------------------------------------------------------------------
        */

        if ((int) $draft->current_part < 5) {

            return redirect()
                ->route('kuisioner.part4')
                ->with(
                    'error',
                    'Selesaikan Part 4 terlebih dahulu.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CEK KELENGKAPAN ANGGOTA
        |--------------------------------------------------------------------------
        | Foto boleh dibuka walaupun anggota belum lengkap.
        | Tetapi kuisioner tidak boleh berstatus selesai sebelum
        | seluruh anggota keluarga sudah didata.
        |--------------------------------------------------------------------------
        */

        $kodeKeluarga = $this->familyCodeForQuestionnaire(
            $draft->keluarga_periode_kode
        );
        $jumlahAnggota = KeluargaAnggota::where(
            'keluarga_kode',
            $kodeKeluarga
        )->count();

        $kodeAnggota = KeluargaAnggota::where(
            'keluarga_kode',
            $kodeKeluarga
        )->pluck('kode');

        $dataPart5 = KeluargaPart5::where(
            'keluarga_periode_kode',
            $draft->keluarga_periode_kode
        )
            ->whereIn('keluarga_anggota_kode', $kodeAnggota)
            ->get()
            ->keyBy('keluarga_anggota_kode');

        $jumlahAnggotaSelesai = KeluargaAnggota::where(
            'keluarga_kode',
            $kodeKeluarga
        )->get()->filter(function ($item) use ($dataPart5) {
            $data = $dataPart5->get($item->kode);

            return $data && $this->part5DataLengkap($data);
        })->count();

        $semuaAnggotaSelesai =
            $jumlahAnggota === $jumlahAnggotaSelesai;

        /*
        |--------------------------------------------------------------------------
        | VALIDASI FOTO
        |--------------------------------------------------------------------------
        */

        $isEditingRejectedQuestionnaire = session('editing_rejected_questionnaire');
        $deleteFoto = $request->input('delete_foto', []);

        if (
            $isAutosave
            && ! $request->hasFile('tampak_depan')
            && ! $request->hasFile('ruang_tamu')
            && ! $request->hasFile('kamar_mandi')
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih foto yang ingin disimpan.',
            ], 422);
        }

        $fotoTersimpan = KeluargaFotoRumah::where(
            'keluarga_periode_kode',
            $draft->keluarga_periode_kode
        )->get()->keyBy('jenis_foto');

        $aturanFoto = [];

        foreach (['tampak_depan', 'ruang_tamu', 'kamar_mandi'] as $field) {
            $fotoLamaAda = $fotoTersimpan->has($field);
            $ditandaiHapus = ! empty($deleteFoto[$field]);

            $wajibUpload = ! $isDraft && ! $isAutosave && (
                ! $fotoLamaAda || $ditandaiHapus
            );

            $aturanFoto[$field] = [
                $wajibUpload ? 'required' : 'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120',
            ];
        }

        $request->validate($aturanFoto, [
            'tampak_depan.required' => 'Foto tampak depan wajib diupload.',
            'ruang_tamu.required' => 'Foto ruang tamu wajib diupload.',
            'kamar_mandi.required' => 'Foto kamar mandi wajib diupload.',
            '*.image' => 'File harus berupa gambar.',
            '*.mimes' => 'Format foto harus JPG, JPEG, atau PNG.',
            '*.max' => 'Ukuran setiap foto maksimal 5 MB.',
        ]);

        if (! $isDraft && ! $isAutosave) {
            $completionIssues = $this->questionnaireCompletionIssues(
                $draft,
                $request
            );

            if ($completionIssues['missing_questions'] !== []) {
                return $this->redirectToIncompleteQuestionnaire($completionIssues);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | JENIS FOTO
        |--------------------------------------------------------------------------
        */

        $jenisFoto = [

            'tampak_depan' => 'Foto Tampak Depan',

            'ruang_tamu' => 'Foto Ruang Tamu',

            'kamar_mandi' => 'Foto Kamar Mandi',
        ];

        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        */

        $user = auth()->user();

        $userName = $user
            ? (
                $user->name
                ?? $user->username
                ?? 'admin'
            )
            : 'admin';

        $userId =
            auth()->id() ?? 'system';

        /*
        |--------------------------------------------------------------------------
        | SIMPAN FOTO + SELESAIKAN KUISIONER
        |--------------------------------------------------------------------------
        */

        DB::beginTransaction();

        try {

            foreach ($jenisFoto as $field => $namaJenis) {
                $file = $request->file($field);
                $markedDelete = ! empty($deleteFoto[$field]);

                $existing = KeluargaFotoRumah::where(
                    'keluarga_periode_kode',
                    $draft->keluarga_periode_kode
                )
                    ->where('jenis_foto', $field)
                    ->first();

                if ($markedDelete && ! $file) {
                    if ($existing) {
                        $pathLama = $existing->path_file;

                        $existing->delete();

                        if ($pathLama) {
                            Storage::disk('public')->delete($pathLama);
                        }
                    }

                    continue;
                }

                if (! $file) {
                    continue;
                }

                $namaFile = time()
                    .'_'
                    .$field
                    .'_'
                    .uniqid()
                    .'.'
                    .$file->getClientOriginalExtension();

                $path = $file->storeAs(
                    'kuisioner/rumah',
                    $namaFile,
                    'public'
                );

                if (! $path) {
                    throw new \RuntimeException(
                        'Gagal menyimpan foto '.$namaJenis.'.'
                    );
                }

                $data = [
                    'keluarga_periode_kode' => $draft->keluarga_periode_kode,
                    'jenis_foto' => $field,
                    'nama_file' => $namaFile,
                    'path_file' => $path,
                    'updated_by' => $userId,
                    'updated_at' => now(),
                ];

                if ($existing) {
                    $pathLama = $existing->path_file;

                    $existing->update($data);

                    if ($pathLama && $pathLama !== $path) {
                        Storage::disk('public')->delete($pathLama);
                    }
                } else {
                    $data['created_by'] = $userId;
                    $data['created_at'] = now();

                    KeluargaFotoRumah::create($data);
                }
            }

            if ($isAutosave) {
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Foto rumah berhasil disimpan.',
                ]);
            }

            if ($isDraft) {
                $draftAttributes = [
                    'current_part' => 5,
                    'updated_by' => $userName,
                    'updated_at' => now(),
                ];

                if (! $isEditingRejectedQuestionnaire) {
                    $draftAttributes['status'] = 'draft';
                }

                $draft->update($draftAttributes);

                DB::commit();

                if ($isEditingRejectedQuestionnaire) {
                    session()->forget([
                        'draft_keluarga_id',
                        'selected_keluarga_kode',
                        'part1_selesai',
                        'part2_selesai',
                        'part3_selesai',
                        'part4_selesai',
                        'part5_selesai',
                        'editing_rejected_questionnaire',
                    ]);

                    return redirect()
                        ->route('kuisioner.ditolak')
                        ->with('success', 'Perubahan kuisioner ditolak berhasil disimpan.');
                }

                return redirect()
                    ->route('kuisioner.index')
                    ->with(
                        'success',
                        'Foto rumah berhasil disimpan sebagai draft.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | JIKA ANGGOTA BELUM LENGKAP, TETAP DRAFT
            |--------------------------------------------------------------------------
            */

            if (! $semuaAnggotaSelesai) {

                DB::commit();

                return redirect()
                    ->route('kuisioner.part5')
                    ->with(
                        'warning',
                        'Foto rumah tersimpan, tetapi kuisioner belum dapat diselesaikan karena masih ada anggota keluarga yang belum didata.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | UBAH STATUS DRAFT MENJADI SELESAI
            |--------------------------------------------------------------------------
            */

            $draft->update([

                'status' => 'selesai',

                'current_part' => 5,

                'updated_by' => $userName,

                'updated_at' => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | BERSIHKAN SESSION
            |--------------------------------------------------------------------------
            */

            session()->forget([

                'draft_keluarga_id',

                'selected_keluarga_kode',

                'part1_selesai',

                'part2_selesai',

                'part3_selesai',

                'part4_selesai',

                'part5_selesai',
                'editing_rejected_questionnaire',
            ]);

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | REDIRECT
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('kuisioner.selesai')
                ->with(
                    'success',
                    'Foto rumah berhasil disimpan. Kuisioner diajukan.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            if ($isAutosave) {
                return response()->json([
                    'success' => false,
                    'message' => 'Foto rumah gagal disimpan. Silakan coba kembali.',
                ], 500);
            }

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Kuisioner gagal diselesaikan: '.
                    $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SELESAI KUISIONER
    |--------------------------------------------------------------------------
    */

    public function selesaiKuisioner(Request $request)
    {
        $draftId =
            session('draft_keluarga_id');

        if (! $draftId) {

            return redirect()
                ->route('kuisioner.index')
                ->with(
                    'warning',
                    'Tidak ada kuisioner aktif.'
                );
        }

        $draft = $this->editableQuestionnaireQuery()
            ->whereKey($draftId)
            ->firstOrFail();

        $completionIssues = $this->questionnaireCompletionIssues(
            $draft,
            $request
        );

        if ($completionIssues['missing_questions'] !== []) {
            return $this->redirectToIncompleteQuestionnaire($completionIssues);
        }

        $kodeKeluarga = $this->familyCodeForQuestionnaire(
            $draft->keluarga_periode_kode
        );
        $jumlahAnggota = KeluargaAnggota::where(
            'keluarga_kode',
            $kodeKeluarga
        )->count();

        $kodeAnggota = KeluargaAnggota::where(
            'keluarga_kode',
            $kodeKeluarga
        )->pluck('kode');

        $jumlahAnggotaTerdata = KeluargaPart5::where(
            'keluarga_periode_kode',
            $draft->keluarga_periode_kode
        )
            ->whereIn('keluarga_anggota_kode', $kodeAnggota)
            ->count();

        if ($jumlahAnggota > 0 && $jumlahAnggotaTerdata < $jumlahAnggota) {
            return redirect()
                ->route('kuisioner.part5')
                ->with(
                    'warning',
                    'Kuisioner belum dapat diselesaikan karena masih ada anggota keluarga yang belum didata.'
                );
        }

        $draft->update([

            'status' => 'selesai',

            'current_part' => 5,

            'updated_by' => auth()->user()->name ?? 'admin',
        ]);

        session()->forget([

            'draft_keluarga_id',

            'selected_keluarga_kode',

            'part1_selesai',

            'part2_selesai',

            'part3_selesai',

            'part4_selesai',

            'part5_selesai',
            'editing_rejected_questionnaire',
        ]);

        return redirect()
            ->route('kuisioner.selesai')
            ->with(
                'success',
                'Kuisioner berhasil diselesaikan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL KUISIONER SELESAI
    |--------------------------------------------------------------------------
    */

    public function detailSelesai($id)
    {
        $dataPart1 = KeluargaPart1::query()
            ->whereIn('keluarga_periode_kode', $this->selectedPeriodRelationCodes())
            ->findOrFail($id);

        if (! in_array($dataPart1->status, ['selesai', 'approved', 'disetujui'], true)) {
            return redirect()
                ->route('kuisioner.selesai')
                ->with('warning', 'Data kuisioner belum berstatus selesai atau disetujui.');
        }

        $keluargaKode = $dataPart1->keluarga_periode_kode;
        $familyCode = $this->familyCodeForQuestionnaire($keluargaKode);

        $keluarga = Keluarga::where('kode', $familyCode)->first();
        if (! $keluarga && $dataPart1->no_kk) {
            $keluarga = Keluarga::query()
                ->get()
                ->first(
                    static fn (Keluarga $family): bool => $family->no_kk === $dataPart1->no_kk
                );
        }

        // Ambil nilai pertama yang benar-benar terisi, bukan sekadar tidak-null.
        $firstFilled = static function (...$values) {
            foreach ($values as $value) {
                if (is_string($value)) {
                    $value = trim($value);
                }
                if ($value !== null && $value !== '') {
                    return $value;
                }
            }

            return null;
        };

        // Jumlah anggota: utamakan jawaban Part 1, lalu hitung data anggota.
        // Kode anggota keluarga mengacu pada kode keluarga, bukan kode periode.
        $kodeUntukAnggota = $keluarga?->kode ?? $familyCode;
        $jumlahAnggotaTambahan = $kodeUntukAnggota
            ? KeluargaAnggota::where('keluarga_kode', $kodeUntukAnggota)->count()
            : 0;
        $jumlahKeluarga = $firstFilled(
            $dataPart1->jml_keluarga,
            $dataPart1->jumlah_keluarga,
            $dataPart1->jumlah_anggota_keluarga,
            $keluarga->jumlah_anggota_keluarga ?? null,
            $keluarga->jml_keluarga ?? null
        );
        // Jika jumlah tidak diisi langsung, gunakan jumlah kepala keluarga + anggota tambahan.
        if ($jumlahKeluarga === null && ($keluarga || $jumlahAnggotaTambahan > 0)) {
            // Kepala keluarga + anggota tambahan yang tersimpan pada tabel keluarga_anggotas.
            $jumlahKeluarga = 1 + $jumlahAnggotaTambahan;
        }
        $dataPart1->jumlah_keluarga_display = $jumlahKeluarga ?? '-';

        // Nama kecamatan/kelurahan dari jawaban Part 1, atau lookup dari master wilayah.
        $namaKecamatan = $firstFilled($dataPart1->kecamatan, $keluarga->kecamatan ?? null);
        if (! $namaKecamatan && $keluarga && $keluarga->kecamatan_id) {
            $namaKecamatan = DB::table('kecamatans')
                ->where('kecamatan_id', $keluarga->kecamatan_id)
                ->value('deskripsi');
        }
        $namaKelurahan = $firstFilled($dataPart1->kelurahan, $dataPart1->desa ?? null, $keluarga->kelurahan ?? null);
        if (! $namaKelurahan && $keluarga && $keluarga->kelurahan_id) {
            $namaKelurahan = DB::table('kelurahans')
                ->where('kelurahan_id', $keluarga->kelurahan_id)
                ->value('deskripsi');
        }

        // RT/RW pada form Part 1 disimpan dalam satu kolom rt_rw.
        $rtRw = $firstFilled($dataPart1->rt_rw, $keluarga->rt_rw ?? null);
        if (! $rtRw) {
            $rt = $firstFilled($keluarga->rt ?? null);
            $rw = $firstFilled($keluarga->rw ?? null);
            $rtRw = ($rt || $rw) ? trim(($rt ?? '-').' / '.($rw ?? '-')) : null;
        }

        $provinsi = $firstFilled($dataPart1->provinsi, $keluarga->provinsi ?? null);
        // Tabel keluargas tidak memiliki kolom daerah; gunakan jawaban Part 1,
        // lalu default Kota Pasuruan jika jawaban tersebut kosong.
        $daerah = $firstFilled(
            $dataPart1->daerah,
            'Kota Pasuruan'
        );
        $alamatLengkap = $firstFilled(
            $dataPart1->alamat_lengkap,
            $keluarga->alamat_lengkap ?? null,
            $keluarga->alamat ?? null
        );
        $geotagging = $firstFilled(
            $dataPart1->geotangging,
            $keluarga->geotagging ?? null,
            $keluarga->geotangging ?? null
        );

        $dataPart1->provinsi_display = $provinsi ?? '-';
        $dataPart1->daerah_display = $daerah ?? '-';
        $dataPart1->kecamatan_display = $namaKecamatan ?? '-';
        $dataPart1->kelurahan_display = $namaKelurahan ?? '-';
        $dataPart1->rt_rw_display = $rtRw ?? '-';
        $dataPart1->alamat_lengkap_display = $alamatLengkap ?? '-';
        $dataPart1->geotagging_display = $geotagging ?? '-';
        $dataPart1->kota_kabupaten_display = $daerah ?? 'Kota Pasuruan';

        $dataPart2 = KeluargaPart2::where('keluarga_periode_kode', $keluargaKode)->first();
        $dataPart3 = KeluargaPart3::where('keluarga_periode_kode', $keluargaKode)->first();
        $dataPart4 = KeluargaPart4::where('keluarga_periode_kode', $keluargaKode)->get();
        $anggota = KeluargaAnggota::where('keluarga_kode', $familyCode)
            ->orderBy('id')
            ->get();
        $dataPart5 = KeluargaPart5::where('keluarga_periode_kode', $keluargaKode)
            ->get()
            ->keyBy('keluarga_anggota_kode');
        $fotoRumah = KeluargaFotoRumah::where('keluarga_periode_kode', $keluargaKode)
            ->get()
            ->keyBy('jenis_foto');

        return view(
            'admin.kuisioner.detail-selesai',
            compact(
                'dataPart1',
                'dataPart2',
                'dataPart3',
                'dataPart4',
                'anggota',
                'dataPart5',
                'fotoRumah',
                'keluarga'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RESUME DRAFT
    |--------------------------------------------------------------------------
    */

    public function resumeDraft($id)
    {
        $draft = KeluargaPart1::query()
            ->whereIn('keluarga_periode_kode', $this->selectedPeriodRelationCodes())
            ->where('id', $id)
            ->where(
                'status',
                'draft'
            )
            ->firstOrFail();

        session([

            'draft_keluarga_id' => $draft->id,

            'selected_keluarga_kode' => $draft->keluarga_periode_kode,

            'part1_selesai' => (int) $draft->current_part >= 2,

            'part2_selesai' => (int) $draft->current_part >= 3,

            'part3_selesai' => (int) $draft->current_part >= 4,

            'part4_selesai' => (int) $draft->current_part >= 5,

            'part5_selesai' => false,
        ]);

        switch (
            (int) $draft->current_part
        ) {

            case 1:

                return redirect()
                    ->route(
                        'kuisioner.part1'
                    );

            case 2:

                return redirect()
                    ->route(
                        'kuisioner.part2'
                    );

            case 3:

                return redirect()
                    ->route(
                        'kuisioner.part3'
                    );

            case 4:

                return redirect()
                    ->route(
                        'kuisioner.part4'
                    );

            case 5:

                return redirect()
                    ->route(
                        'kuisioner.part5.foto'
                    );

            default:

                return redirect()
                    ->route(
                        'kuisioner.part1'
                    );
        }
    }

    public function simpanDraftPart5()
    {
        $draftId = session('draft_keluarga_id');
        $keluargaKode = session('selected_keluarga_kode');

        if (! $draftId || ! $keluargaKode) {
            return redirect()
                ->route('kuisioner.part1')
                ->with('error', 'Sesi draft tidak ditemukan. Silakan buka kembali draft keluarga.');
        }

        $draft = $this->editableQuestionnaireQuery()->where('id', $draftId)
            ->where('keluarga_periode_kode', $keluargaKode)
            ->first();

        if (! $draft) {
            return redirect()
                ->route('kuisioner.index')
                ->with('error', 'Draft tidak ditemukan atau sudah selesai.');
        }

        // Simpan posisi terakhir di Part 5.
        $draft->current_part = 5;
        $draft->updated_by = auth()->user()->username
            ?? auth()->user()->name
            ?? 'admin';
        $draft->save();

        if (session('editing_rejected_questionnaire')) {
            session()->forget([
                'draft_keluarga_id',
                'selected_keluarga_kode',
                'part1_selesai',
                'part2_selesai',
                'part3_selesai',
                'part4_selesai',
                'part5_selesai',
                'editing_rejected_questionnaire',
            ]);

            return redirect()
                ->route('kuisioner.ditolak')
                ->with('success', 'Perubahan kuisioner ditolak berhasil disimpan.');
        }

        // Kembali ke halaman daftar draft agar hasilnya terlihat.
        return redirect()
            ->route('kuisioner.index')
            ->with('success', 'Draft Part 5 berhasil disimpan. Kamu bisa melanjutkan pengisian nanti.');
    }
    /*
    |--------------------------------------------------------------------------
    | CEK APAKAH DATA PART 5 ANGGOTA SUDAH LENGKAP
    |--------------------------------------------------------------------------
    */

}
