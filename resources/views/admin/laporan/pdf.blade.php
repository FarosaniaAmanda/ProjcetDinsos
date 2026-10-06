<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendataan {{ $item['no_kk'] ?? '-' }}</title>
    <style>
        @page {
            margin: 28px 32px;
        }

        body {
            color: #263238;
            font-family: Helvetica, sans-serif;
            font-size: 10px;
            line-height: 1.45;
        }

        h1 {
            margin: 0 0 4px;
            color: #252a86;
            font-size: 19px;
        }

        .subtitle {
            margin-bottom: 18px;
            color: #64748b;
        }

        h2 {
            margin: 18px 0 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #dfe3ed;
            color: #252a86;
            font-size: 13px;
        }

        h3 {
            margin: 12px 0 0;
            padding: 7px 9px;
            background: #f2f4fa;
            color: #252a86;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 7px 8px;
            border: 1px solid #e1e5ec;
            text-align: left;
            vertical-align: top;
        }

        th {
            width: 26%;
            background: #f8f9fc;
            font-weight: bold;
        }

        .question-table th:first-child {
            width: 42%;
        }

        .question-photo {
            display: block;
            width: auto;
            max-width: 360px;
            max-height: 240px;
            margin: 4px auto;
            object-fit: contain;
            border: 1px solid #dfe3ed;
            border-radius: 6px;
        }

        .question-photo-missing {
            color: #64748b;
            font-style: italic;
        }

        .map-answer {
            padding: 7px 9px;
            background: #f8f9fc;
            border: 1px solid #e1e5ec;
            border-radius: 5px;
        }

        .map-coordinates {
            font-family: Helvetica, sans-serif;
            font-size: 9px;
            color: #475569;
            margin-top: 3px;
        }

        .map-link {
            display: inline-block;
            margin-top: 5px;
            color: #252a86;
            text-decoration: none;
            font-size: 9px;
        }

        .muted {
            color: #64748b;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <h1>Laporan Pendataan Keluarga</h1>
    <div class="subtitle">Satu keluarga berdasarkan data Verifikasi terbaru</div>

    <h2>Data Keluarga dan Pendataan</h2>
    <table>
        <tbody>
            <tr><th>No. KK</th><td>{{ $item['no_kk'] ?? '-' }}</td></tr>
            <tr><th>Kepala Keluarga</th><td>{{ $item['nama'] ?? '-' }}</td></tr>
            <tr><th>NIK Kepala Keluarga</th><td>{{ $item['nik'] ?? '-' }}</td></tr>
            <tr><th>Jumlah Anggota</th><td>{{ $item['jumlah_anggota'] ?? 0 }}</td></tr>
            <tr><th>Kecamatan / Kelurahan</th><td>{{ $item['wilayah'] ?? '-' }}</td></tr>
            <tr><th>Alamat</th><td>{{ $item['alamat_lengkap'] ?? '-' }}</td></tr>
            <tr><th>Kode Pos</th><td>{{ $item['kode_pos'] ?? '-' }}</td></tr>
            <tr><th>Periode</th><td>{{ $item['periode'] ?? '-' }}</td></tr>
            <tr><th>Tanggal Pendataan</th><td>{{ $item['tanggal'] ?? '-' }}</td></tr>
            <tr><th>Status</th><td>{{ $item['status_label'] ?? '-' }}</td></tr>
            <tr><th>Petugas</th><td>{{ $item['petugas'] ?? '-' }}</td></tr>
        </tbody>
    </table>

    <h2>Anggota Keluarga</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>NIK</th>
                <th>Nama Lengkap</th>
                <th>Status Keluarga</th>
            </tr>
        </thead>
        <tbody>
            @forelse(($item['anggota_detail'] ?? []) as $index => $member)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $member['nik'] ?? '-' }}</td>
                    <td>{{ $member['nama_lengkap'] ?? '-' }}</td>
                    <td>{{ $member['status_keluarga'] ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="muted">Data anggota keluarga belum tersedia.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="page-break"></div>

    <h2>Hasil Kuisioner</h2>

    @forelse(($item['kuisioner'] ?? []) as $partIndex => $part)
        @php
            $partNumber = $part['part'] ?? $part['number'] ?? ($partIndex + 1);
            $partTitle = $part['title'] ?? '';
        @endphp

        <h3>Part {{ $partNumber }}{{ $partTitle !== '' ? ' - ' . $partTitle : '' }}</h3>

        <table class="question-table">
            <thead>
                <tr>
                    <th>Pertanyaan</th>
                    <th>Jawaban</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($part['questions'] ?? []) as $questionIndex => $question)
                    @php
                        $questionText =
                            data_get($question, 'question')
                            ?? data_get($question, 'pertanyaan')
                            ?? data_get($question, 'text')
                            ?? 'Pertanyaan';

                        $questionType = strtolower((string) (
                            data_get($question, 'type')
                            ?? data_get($question, 'tipe')
                            ?? ''
                        ));

                        $answer =
                            data_get($question, 'answer')
                            ?? data_get($question, 'jawaban')
                            ?? data_get($question, 'value')
                            ?? '';

                        $imageSource =
                            data_get($question, 'image_data')
                            ?? data_get($question, 'imageData')
                            ?? data_get($question, 'imageUrl')
                            ?? data_get($question, 'image_url')
                            ?? data_get($question, 'url')
                            ?? data_get($question, 'path_file')
                            ?? data_get($question, 'path')
                            ?? '';

                        if (is_array($answer) || is_object($answer)) {
                            $answer = json_encode(
                                $answer,
                                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                            );
                        }

                        $answer = (string) $answer;
                        $imageSource = (string) $imageSource;

                        $isImageAnswer =
                            $questionType === 'image'
                            || $questionType === 'foto'
                            || $imageSource !== ''
                            || preg_match(
                                '/\.(?:jpe?g|png|gif|webp)(?:\?.*)?$/i',
                                $answer
                            );

                        if ($imageSource === '' && $isImageAnswer) {
                            $imageSource = $answer;
                        }

                        $isMapAnswer =
                            $questionType === 'map'
                            || $questionType === 'location'
                            || str_contains(strtolower($questionText), 'geotagging')
                            || str_contains(strtolower($questionText), 'titik lokasi')
                            || str_contains(strtolower($questionText), 'koordinat lokasi');

                        /*
                        |------------------------------------------------------------------
                        | PDF IMAGE SOURCE
                        |------------------------------------------------------------------
                        | Dompdf sering tidak dapat membaca URL seperti
                        | http://127.0.0.1:8000/storage/... atau localhost/storage/...
                        | secara langsung. Karena itu foto harus diubah menjadi
                        | data URI (base64) sebelum dikirim ke Dompdf.
                        */
                        $pdfImageSource = '';

                        if ($imageSource !== '') {

                            /* Sudah berupa base64/data URI. */
                            if (str_starts_with($imageSource, 'data:image/')) {

                                $pdfImageSource = $imageSource;

                            } else {

                                $cleanImagePath = $imageSource;

                                /*
                                | Jika yang tersimpan adalah URL localhost / URL APP,
                                | ambil hanya bagian path-nya.
                                */
                                if (filter_var($cleanImagePath, FILTER_VALIDATE_URL)) {
                                    $cleanImagePath = parse_url(
                                        $cleanImagePath,
                                        PHP_URL_PATH
                                    ) ?: '';
                                }

                                $cleanImagePath = str_replace(
                                    '\\',
                                    '/',
                                    trim((string) $cleanImagePath)
                                );

                                /*
                                | Hilangkan prefix umum Laravel/public storage.
                                */
                                $cleanImagePath = preg_replace(
                                    '#^https?://[^/]+/storage/#i',
                                    '',
                                    $cleanImagePath
                                );

                                $cleanImagePath = preg_replace(
                                    '#^/?storage/#i',
                                    '',
                                    $cleanImagePath
                                );

                                $cleanImagePath = preg_replace(
                                    '#^/?public/#i',
                                    '',
                                    $cleanImagePath
                                );

                                $cleanImagePath = ltrim(
                                    $cleanImagePath,
                                    '/'
                                );

                                /*
                                | Baca file langsung dari storage/app/public.
                                | Ini yang membuat Dompdf dapat menampilkan foto
                                | walaupun aplikasi berjalan di localhost.
                                */
                                try {

                                    $publicDisk =
                                        \Illuminate\Support\Facades\Storage::disk('public');

                                    if (
                                        $cleanImagePath !== ''
                                        && $publicDisk->exists($cleanImagePath)
                                    ) {

                                        $absoluteImagePath =
                                            $publicDisk->path($cleanImagePath);

                                        if (is_file($absoluteImagePath)) {

                                            $binaryImage =
                                                file_get_contents($absoluteImagePath);

                                            if ($binaryImage !== false) {

                                                $mimeType =
                                                    function_exists('mime_content_type')
                                                        ? mime_content_type($absoluteImagePath)
                                                        : 'image/jpeg';

                                                if (!str_starts_with((string) $mimeType, 'image/')) {
                                                    $mimeType = 'image/jpeg';
                                                }

                                                $pdfImageSource =
                                                    'data:' .
                                                    $mimeType .
                                                    ';base64,' .
                                                    base64_encode($binaryImage);
                                            }
                                        }
                                    }

                                } catch (\Throwable $imageException) {
                                    $pdfImageSource = '';
                                }

                            }
                        }

                        $displayAnswer =
                            $answer !== ''
                                ? $answer
                                : 'Belum diisi';
                    @endphp

                    <tr>
                        <td>
                            {{ $questionText }}
                        </td>

                        <td>
                            @if($isImageAnswer)

                                @if($pdfImageSource !== '')
                                    <img
                                        src="{{ $pdfImageSource }}"
                                        class="question-photo"
                                        alt="{{ $questionText }}"
                                    >
                                @elseif($imageSource !== '')
                                    <span class="question-photo-missing">
                                        File foto ditemukan, tetapi file tidak dapat dibaca oleh PDF.
                                    </span>
                                @else
                                    <span class="question-photo-missing">
                                        File foto tidak tersedia.
                                    </span>
                                @endif

                            @elseif($isMapAnswer)

                                @php
                                    $mapValue = trim($answer);
                                @endphp

                                <div class="map-answer">
                                    <strong>Titik lokasi pendataan</strong>

                                    @if($mapValue !== '')
                                        <div class="map-coordinates">
                                            Koordinat: {{ $mapValue }}
                                        </div>

                                        @if(preg_match('/^-?\d+(?:\.\d+)?\s*,\s*-?\d+(?:\.\d+)?$/', $mapValue))
                                            @php
                                                $mapUrl = 'https://www.google.com/maps?q=' . rawurlencode($mapValue);
                                            @endphp
                                            <a class="map-link" href="{{ $mapUrl }}">
                                                Lihat lokasi di Google Maps
                                            </a>
                                        @endif
                                    @else
                                        <div class="map-coordinates">
                                            Koordinat belum tersedia.
                                        </div>
                                    @endif
                                </div>

                            @else
                                {{ $displayAnswer }}
                            @endif
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="2" class="muted">
                            Belum ada jawaban pada bagian ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(!$loop->last)
            <div style="height: 8px;"></div>
        @endif

    @empty
        <p class="muted">Hasil kuisioner belum tersedia.</p>
    @endforelse

</body>
</html>
