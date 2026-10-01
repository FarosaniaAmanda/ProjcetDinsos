<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\KeluargaPart1;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * ============================================================
     * HALAMAN LAPORAN
     * ============================================================
     */
    public function index(Request $request)
    {
        $search  = trim((string) $request->input('search', ''));
        $periode = trim((string) $request->input('periode', ''));
        $wilayah = trim((string) $request->input('wilayah', ''));

        /*
         * Ambil data Part 1.
         *
         * Tabel utama:
         * part1_keluarga
         */
        $query = KeluargaPart1::query();

        /*
         * ========================================================
         * PENCARIAN
         * ========================================================
         *
         * Bisa mencari:
         * - No. KK
         * - NIK
         * - Nama kepala keluarga
         * - Kecamatan
         * - Kelurahan
         */
        if ($search !== '') {

            $query->where(function ($q) use ($search) {

                $like = '%' . $search . '%';

                $q->where('no_kk', 'like', $like)
                    ->orWhere('nik', 'like', $like)
                    ->orWhere('kecamatan', 'like', $like)
                    ->orWhere('kelurahan', 'like', $like);
            });
        }

        /*
         * ========================================================
         * FILTER PERIODE
         * ========================================================
         */
        if ($periode !== '') {

            $query->where(
                'keluarga_periode_kode',
                $periode
            );
        }

        /*
         * ========================================================
         * FILTER WILAYAH
         * ========================================================
         *
         * Format dari select wilayah:
         * Kecamatan - Kelurahan
         */
        if ($wilayah !== '') {

            $wilayahParts = array_map(
                'trim',
                explode(' - ', $wilayah, 2)
            );

            $kecamatan = $wilayahParts[0] ?? '';
            $kelurahan = $wilayahParts[1] ?? '';

            if ($kecamatan !== '') {

                $query->where(
                    'kecamatan',
                    $kecamatan
                );
            }

            if ($kelurahan !== '') {

                $query->where(
                    'kelurahan',
                    $kelurahan
                );
            }
        }

        /*
         * ========================================================
         * DATA LAPORAN
         * ========================================================
         */
        $items = $query
            ->orderByDesc('id')
            ->get();

        /*
         * ========================================================
         * BENTUK DATA UNTUK VIEW
         * ========================================================
         *
         * Nama kepala keluarga diambil dari tabel keluargas
         * berdasarkan no_kk.
         */
        $keluargaByNoKk = Keluarga::query()
            ->whereIn(
                'no_kk',
                $items
                    ->pluck('no_kk')
                    ->filter()
                    ->unique()
                    ->values()
                    ->all()
            )
            ->get()
            ->keyBy('no_kk');

        $laporan = $items->map(function ($item) use ($keluargaByNoKk) {

            $keluarga = $keluargaByNoKk->get(
                $item->no_kk
            );

            /*
             * Wilayah
             */
            $wilayah = collect([
                $item->kecamatan,
                $item->kelurahan,
            ])
                ->filter(function ($value) {
                    return !empty($value);
                })
                ->implode(' - ');

            /*
             * Status
             */
            $status = $item->status ?? 'Belum Selesai';

            /*
             * Kelengkapan.
             *
             * Jika status approved/selesai/disetujui,
             * dianggap lengkap.
             */
            $statusLower = strtolower(
                trim((string) $status)
            );

            $isComplete = in_array(
                $statusLower,
                [
                    'selesai',
                    'disetujui',
                    'approved',
                ],
                true
            );

            return [
                'id' => $item->id,

                'no_kk' =>
                    $item->no_kk
                    ?? $keluarga?->no_kk
                    ?? '-',

                'nik' =>
                    $item->nik
                    ?? $keluarga?->nik
                    ?? '-',

                'nama_kepala_keluarga' =>
                    $keluarga?->nama_lengkap
                    ?? '-',

                'jumlah_anggota' =>
                    $item->jml_keluarga
                    ?? $keluarga?->anggota?->count()
                    ?? 0,

                'wilayah' =>
                    $wilayah ?: '-',

                'periode' =>
                    $item->keluarga_periode_kode
                    ?? '-',

                'tanggal_pendataan' =>
                    $item->created_at
                    ? $item->created_at->format('d-m-Y')
                    : '-',

                'status' =>
                    $status,

                'is_complete' =>
                    $isComplete,
            ];
        });

        /*
         * ========================================================
         * LIST PERIODE
         * ========================================================
         */
        $periodeList = KeluargaPart1::query()
            ->whereNotNull('keluarga_periode_kode')
            ->where(
                'keluarga_periode_kode',
                '!=',
                ''
            )
            ->select('keluarga_periode_kode')
            ->distinct()
            ->orderBy('keluarga_periode_kode')
            ->pluck('keluarga_periode_kode');

        /*
         * ========================================================
         * LIST WILAYAH
         * ========================================================
         */
        $wilayahList = KeluargaPart1::query()
            ->select(
                'kecamatan',
                'kelurahan'
            )
            ->whereNotNull('kecamatan')
            ->where(
                'kecamatan',
                '!=',
                ''
            )
            ->distinct()
            ->orderBy('kecamatan')
            ->orderBy('kelurahan')
            ->get()
            ->map(function ($item) {

                return collect([
                    $item->kecamatan,
                    $item->kelurahan,
                ])
                    ->filter(function ($value) {
                        return !empty($value);
                    })
                    ->implode(' - ');
            })
            ->filter()
            ->unique()
            ->values();

        /*
         * ========================================================
         * STATISTIK
         * ========================================================
         */
        $totalResponden = $laporan->count();

        $kuisionerSelesai = $laporan
            ->filter(function ($item) {
                return $item['is_complete'] === true;
            })
            ->count();

        $petugasAktif = 0;

        $wilayahTerdata = $laporan
            ->pluck('wilayah')
            ->filter(function ($value) {
                return $value !== '-';
            })
            ->unique()
            ->count();

        /*
         * ========================================================
         * KIRIM KE VIEW
         * ========================================================
         */
        return view(
            'admin.laporan.index',
            compact(
                'laporan',
                'search',
                'periode',
                'wilayah',
                'periodeList',
                'wilayahList',
                'totalResponden',
                'kuisionerSelesai',
                'petugasAktif',
                'wilayahTerdata'
            )
        );
    }


    /**
     * ============================================================
     * EXPORT LAPORAN
     * ============================================================
     */
    public function export(Request $request)
    {
        $search  = trim((string) $request->input('search', ''));
        $periode = trim((string) $request->input('periode', ''));
        $wilayah = trim((string) $request->input('wilayah', ''));

        $query = KeluargaPart1::query();

        /*
         * Search
         */
        if ($search !== '') {

            $query->where(function ($q) use ($search) {

                $like = '%' . $search . '%';

                $q->where('no_kk', 'like', $like)
                    ->orWhere('nik', 'like', $like)
                    ->orWhere('kecamatan', 'like', $like)
                    ->orWhere('kelurahan', 'like', $like);
            });
        }

        /*
         * Periode
         */
        if ($periode !== '') {

            $query->where(
                'keluarga_periode_kode',
                $periode
            );
        }

        /*
         * Wilayah
         */
        if ($wilayah !== '') {

            $wilayahParts = array_map(
                'trim',
                explode(' - ', $wilayah, 2)
            );

            $kecamatan = $wilayahParts[0] ?? '';
            $kelurahan = $wilayahParts[1] ?? '';

            if ($kecamatan !== '') {

                $query->where(
                    'kecamatan',
                    $kecamatan
                );
            }

            if ($kelurahan !== '') {

                $query->where(
                    'kelurahan',
                    $kelurahan
                );
            }
        }

        $data = $query
            ->orderByDesc('id')
            ->get();

        return response()->streamDownload(
            function () use ($data) {

                $handle = fopen(
                    'php://output',
                    'w'
                );

                /*
                 * Header CSV
                 */
                fputcsv(
                    $handle,
                    [
                        'No.',
                        'No. KK',
                        'NIK',
                        'Jumlah Anggota',
                        'Kecamatan',
                        'Kelurahan',
                        'Periode',
                        'Tanggal Pendataan',
                        'Status',
                    ]
                );

                /*
                 * Data
                 */
                foreach ($data as $index => $item) {

                    fputcsv(
                        $handle,
                        [
                            $index + 1,
                            $item->no_kk ?? '-',
                            $item->nik ?? '-',
                            $item->jml_keluarga ?? 0,
                            $item->kecamatan ?? '-',
                            $item->kelurahan ?? '-',
                            $item->keluarga_periode_kode ?? '-',
                            $item->created_at
                                ? $item->created_at->format('d-m-Y')
                                : '-',
                            $item->status ?? 'Belum Selesai',
                        ]
                    );
                }

                fclose($handle);
            },
            'laporan-pendataan.csv',
            [
                'Content-Type' =>
                    'text/csv; charset=UTF-8',
            ]
        );
    }
}