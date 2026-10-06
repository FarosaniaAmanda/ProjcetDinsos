<?php

namespace App\Http\Controllers\Admin;

use App\Models\Periode;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends MonitoringController
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $periode = trim((string) $request->query('periode', ''));
        $wilayah = trim((string) $request->query('wilayah', ''));

        $periodRecords = Periode::query()
            ->orderBy('tgl_awal')
            ->get(['kode', 'nama', 'tgl_awal', 'tgl_akhir']);

        $allRows = $this->eligibleRows($periodRecords);
        $laporan = $this->filterRows($allRows, $search, $periode, $wilayah);

        $totalResponden = $laporan->count();
        $kuisionerSelesai = $laporan
            ->where('is_complete', true)
            ->count();
        $petugasAktif = 'Tidak tersedia';
        $wilayahTerdata = $laporan
            ->pluck('wilayah')
            ->filter(fn (string $value): bool => $value !== '-')
            ->unique()
            ->count();

        $periodeList = $periodRecords
            ->map(fn (Periode $record): array => [
                'kode' => (string) $record->kode,
                'nama' => (string) ($record->nama ?: $record->kode),
            ])
            ->values();

        $wilayahList = $allRows
            ->pluck('wilayah')
            ->filter(fn (string $value): bool => $value !== '-')
            ->unique()
            ->sort()
            ->values();

        return view('admin.laporan.index', compact(
            'laporan',
            'search',
            'periode',
            'wilayah',
            'periodeList',
            'wilayahList',
            'totalResponden',
            'kuisionerSelesai',
            'petugasAktif',
            'wilayahTerdata',
        ));
    }

    public function showDetail(int $id): JsonResponse
    {
        $item = $this->eligibleRows()
            ->firstWhere('id', $id);

        abort_if($item === null, 404);

        return response()->json(['data' => $item]);
    }

    public function pdf(int $id)
    {
        $item = $this->eligibleRows()
            ->firstWhere('id', $id);

        abort_if($item === null, 404);

        $item['foto_rumah'] = $this->preparePdfPhotos($item['foto_rumah'] ?? []);

        $dompdf = new Dompdf([
            'defaultFont' => 'Helvetica',
            'isRemoteEnabled' => false,
            'tempDir' => storage_path('framework'),
        ]);

        $html = view('admin.laporan.pdf', ['item' => $item])->render();
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfOutput = $dompdf->output();

        $fileName = 'laporan-'.Str::slug((string) $item['no_kk']).'.pdf';

        return response($pdfOutput, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
        ]);
    }

    /**
     * Embed local questionnaire photos because remote URLs are disabled in Dompdf.
     *
     * @param  array<int, array<string, mixed>>  $photos
     * @return array<int, array<string, mixed>>
     */
    private function preparePdfPhotos(array $photos): array
    {
        return collect($photos)
            ->map(function (array $photo): array {
                $path = trim(str_replace('\\', '/', (string) data_get($photo, 'path_file', '')));
                $path = ltrim($path, '/');
                $position = strpos(strtolower($path), 'kuisioner/');

                if ($position !== false) {
                    $path = substr($path, $position);
                } else {
                    $path = preg_replace(
                        '#^(?:public/storage/|storage/app/public/|storage/app/|public/|storage/)#i',
                        '',
                        $path,
                    );
                }

                $photo['label'] = match (strtolower((string) data_get($photo, 'jenis_foto', ''))) {
                    'tampak_depan' => 'Foto Tampak Depan Rumah',
                    'tampak_belakang' => 'Foto Tampak Belakang Rumah',
                    'ruang_tamu' => 'Foto Ruang Tamu',
                    'kamar_mandi' => 'Foto Kamar Mandi',
                    'dapur' => 'Foto Dapur',
                    'kamar_tidur' => 'Foto Kamar Tidur',
                    'ruang_keluarga' => 'Foto Ruang Keluarga',
                    default => (string) (data_get($photo, 'nama_file') ?: 'Foto Rumah'),
                };

                $photo['image_data'] = null;

                if ($path === '' || str_contains($path, '..')) {
                    return $photo;
                }

                $disk = Storage::disk('public');

                if (! $disk->exists($path)) {
                    $disk = Storage::disk('local');
                }

                if (! $disk->exists($path)) {
                    return $photo;
                }

                $mimeType = $disk->mimeType($path);

                if (! is_string($mimeType) || ! str_starts_with($mimeType, 'image/')) {
                    return $photo;
                }

                $photo['image_data'] = 'data:'.$mimeType.';base64,'.base64_encode($disk->get($path));

                return $photo;
            })
            ->values()
            ->all();
    }

    public function export(Request $request): StreamedResponse
    {
        $search = trim((string) $request->query('search', ''));
        $periode = trim((string) $request->query('periode', ''));
        $wilayah = trim((string) $request->query('wilayah', ''));
        $periodRecords = Periode::query()
            ->orderBy('tgl_awal')
            ->get(['kode', 'nama', 'tgl_awal', 'tgl_akhir']);
        $rows = $this->filterRows(
            $this->eligibleRows($periodRecords),
            $search,
            $periode,
            $wilayah,
        );

        return response()->streamDownload(
            function () use ($rows): void {
                $spreadsheet = new Spreadsheet;
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Laporan Pendataan');

                $headers = [
                    'No. KK',
                    'Periode',
                    'Tanggal Pendataan',
                    'Status',
                ];

                foreach ($headers as $column => $header) {
                    $cell = $sheet->getCell([$column + 1, 1]);
                    $cell->setValueExplicit($header, DataType::TYPE_STRING);
                }

                foreach ($rows->values() as $index => $item) {
                    $values = [
                        $item['no_kk'],
                        $item['periode'],
                        $item['tanggal_pendataan'],
                        $item['status_label'],
                    ];

                    foreach ($values as $column => $value) {
                        $cell = $sheet->getCell([$column + 1, $index + 2]);
                        $cell->setValueExplicit((string) $value, DataType::TYPE_STRING);
                    }
                }

                foreach (range('A', 'D') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                $sheet->getStyle('A1:D1')->getFont()->setBold(true);
                (new Xlsx($spreadsheet))->save('php://output');
                $spreadsheet->disconnectWorksheets();
            },
            'laporan-pendataan.xlsx',
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ],
        );
    }

    /**
     * Read the same approved/rejected records exposed by Monitoring.
     */
    private function eligibleRows(?Collection $periodRecords = null): Collection
    {
        $periodRecords ??= Periode::query()
            ->orderBy('tgl_awal')
            ->get(['kode', 'nama', 'tgl_awal', 'tgl_akhir']);

        return collect($this->getData())
            ->map(function (array $item) use ($periodRecords): array {
                $createdAt = $this->parseMonitoringDate(
                    data_get($item, 'created_at')
                );
                $period = $createdAt
                    ? $periodRecords->first(function (Periode $candidate) use ($createdAt): bool {
                        if (! $candidate->tgl_awal || ! $candidate->tgl_akhir) {
                            return false;
                        }

                        return $createdAt->betweenIncluded(
                            Carbon::parse($candidate->tgl_awal)->startOfDay(),
                            Carbon::parse($candidate->tgl_akhir)->endOfDay(),
                        );
                    })
                    : null;

                $item['periode_kode'] = $period?->kode;
                $item['periode'] = $period
                    ? (string) ($period->nama ?: $period->kode)
                    : '-';
                $item['tanggal_pendataan'] = data_get($item, 'tanggal', '-');
                $item['status_label'] = data_get($item, 'status_label', '-');
                $item['is_complete'] = (int) data_get($item, 'progress_completed', 0) === 5;
                $item['wilayah'] = trim((string) data_get($item, 'wilayah', '')) ?: '-';

                return $item;
            })
            ->sortByDesc('id')
            ->values();
    }

    private function filterRows(
        Collection $rows,
        string $search,
        string $periode,
        string $wilayah,
    ): Collection {
        $search = mb_strtolower($search);

        $filtered = $rows->filter(function (array $item) use ($periode, $wilayah): bool {
            if ($periode !== '' && (string) $item['periode_kode'] !== $periode) {
                return false;
            }

            if ($wilayah !== '' && $item['wilayah'] !== $wilayah) {
                return false;
            }

            return true;
        });

        $latestPerHousehold = $filtered
            ->groupBy(function (array $item): string {
                $noKk = trim((string) data_get($item, 'no_kk', ''));

                return $noKk !== ''
                    ? 'kk:'.$noKk
                    : 'record:'.data_get($item, 'id');
            })
            ->map(fn (Collection $items): array => $items->first())
            ->values();

        if ($search === '') {
            return $latestPerHousehold;
        }

        return $latestPerHousehold
            ->filter(function (array $item) use ($search): bool {
                $searchable = mb_strtolower(implode(' ', [
                    (string) data_get($item, 'no_kk', ''),
                    (string) data_get($item, 'nik', ''),
                    (string) data_get($item, 'nama', ''),
                    (string) data_get($item, 'wilayah', ''),
                ]));

                return str_contains($searchable, $search);
            })
            ->values();
    }

    private function parseMonitoringDate(mixed $value): ?Carbon
    {
        if (! is_string($value) || trim($value) === '' || $value === '-') {
            return null;
        }

        try {
            $date = Carbon::createFromFormat('d-m-Y H:i', $value);

            return $date instanceof Carbon ? $date : null;
        } catch (\InvalidArgumentException) {
            return null;
        }
    }
}
