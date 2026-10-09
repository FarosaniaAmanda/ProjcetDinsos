<?php

namespace App\Http\Controllers\Admin;

use Dompdf\Dompdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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
        $filters = $this->validateTableFilters(
            $request,
            ['not_processed', 'draft', 'pending', 'approved', 'rejected']
        );

        $laporanRows = $this->eligibleRows($filters);

        $totalResponden = $laporanRows->count();
        $kuisionerSelesai = $laporanRows
            ->where('is_complete', true)
            ->count();
        $petugasAktif = 'Tidak tersedia';
        $wilayahTerdata = $laporanRows
            ->pluck('wilayah')
            ->filter(fn (string $value): bool => $value !== '-')
            ->unique()
            ->count();

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $laporan = new LengthAwarePaginator(
            $laporanRows
                ->slice(($currentPage - 1) * $perPage, $perPage)
                ->values(),
            $laporanRows->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        $kecamatanList = $this->getKecamatanOptions();
        $kelurahanList = $this->getKelurahanOptions($filters['kecamatan']);

        return view('admin.laporan.index', compact(
            'laporan',
            'filters',
            'kecamatanList',
            'kelurahanList',
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
        $filters = $this->validateTableFilters(
            $request,
            ['not_processed', 'draft', 'pending', 'approved', 'rejected']
        );

        $rows = $this->eligibleRows($filters);

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
    private function eligibleRows(
        ?array $filters = null
    ): Collection {
        $filters ??= [
            'search' => '',
            'kecamatan' => '',
            'kelurahan' => '',
            'status' => 'all',
        ];

        $periodeTerpilih = $this->getSelectedPeriode();

        $query = $this->buildFilteredPart1Query(
            $filters,
            ['not_processed', 'draft', 'pending', 'approved', 'rejected']
        );

        return $this->filterRows(collect($this->getData($query))
            ->map(function (array $item) use ($periodeTerpilih): array {
                $item['periode_kode'] = $periodeTerpilih?->kode;
                $item['periode'] = $periodeTerpilih
                    ? (string) ($periodeTerpilih->nama ?: $periodeTerpilih->kode)
                    : '-';
                $item['tanggal_pendataan'] = data_get($item, 'tanggal', '-');
                $item['status_label'] = data_get($item, 'status_label', '-');
                $item['is_complete'] = (int) data_get($item, 'progress_completed', 0) === 5;
                $item['wilayah'] = trim((string) data_get($item, 'wilayah', '')) ?: '-';

                return $item;
            })
            ->sortByDesc('id')
            ->values());
    }

    private function filterRows(Collection $rows): Collection
    {
        return $rows
            ->groupBy(function (array $item): string {
                $keluargaPeriodeKode = trim((string) data_get($item, 'keluarga_periode_kode', ''));

                if ($keluargaPeriodeKode !== '') {
                    return 'keluarga-periode:'.$keluargaPeriodeKode;
                }

                $noKk = trim((string) data_get($item, 'no_kk', ''));

                return $noKk !== ''
                    ? 'kk:'.$noKk
                    : 'record:'.data_get($item, 'id');
            })
            ->map(fn (Collection $items): array => $items->first())
            ->values();
    }
}
