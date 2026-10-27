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

        /*
         * Tabel hasil kuisioner:
         * kolom Pertanyaan dan Jawaban dibuat sama lebar (50% : 50%).
         * table-layout: fixed menjaga ukuran kolom konsisten pada setiap halaman PDF.
         */
        .question-table {
            width: 100%;
            table-layout: fixed;
        }

        .question-table th,
        .question-table td {
            width: 50%;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        /* Part 5: satu tabel terpisah untuk setiap anggota keluarga. */
        .part5-member-block {
            margin: 0 0 14px;
            page-break-inside: avoid;
        }

        .part5-member-heading {
            margin: 10px 0 0;
            padding: 8px 9px;
            background: #f2f4fa;
            border: 1px solid #dfe3ed;
            border-bottom: 0;
            color: #252a86;
            font-size: 10px;
            line-height: 1.4;
        }

        .part5-member-heading span {
            color: #475569;
            font-weight: normal;
        }

        .part5-member-table {
            margin: 0;
        }

        .part5-member-table th,
        .part5-member-table td {
            width: 50%;
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

        /*
         * Data Keluarga dan Pendataan:
         * kolom kiri dan kanan dibuat sama lebar (50% : 50%).
         */
        .family-data-table {
            width: 100%;
            table-layout: fixed;
        }

        .family-data-table th,
        .family-data-table td {
            width: 50%;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        /*
         * Foto Rumah Part 6:
         * tiga foto ditampilkan berdampingan dalam satu baris.
         */
        .part6-photo-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 6px;
        }

        .part6-photo-cell {
            width: 33.3333%;
            padding: 7px;
            border: 1px solid #e1e5ec;
            vertical-align: top;
            text-align: center;
        }

        .part6-question-title {
            display: block;
            margin-bottom: 7px;
            color: #263238;
            font-weight: bold;
            font-size: 9px;
            line-height: 1.35;
            text-align: left;
        }

        .part6-photo {
            display: block;
            width: 100%;
            max-width: 170px;
            height: 125px;
            margin: 4px auto 0;
            object-fit: contain;
            border: 1px solid #dfe3ed;
            border-radius: 5px;
        }

        .part6-photo-missing {
            display: block;
            min-height: 125px;
            padding: 12px 5px;
            color: #64748b;
            font-style: italic;
            font-size: 8px;
            text-align: center;
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
            color: #000000;
            margin-top: 3px;
        }

        .pdf-leaflet-map {
            display: block;
            width: 100%;
            max-width: 520px;
            height: auto;
            margin: 8px auto 5px;
            border: 1px solid #dfe3ed;
        }

        .currency-answer {
            font-weight: normal;
            color: #000000;
        }

        .map-link {
            display: inline-block;
            margin-top: 5px;
            color: #000000;
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
    <table class="family-data-table">
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
            @if (in_array(strtolower(trim((string) ($item['status'] ?? $item['status_label'] ?? ''))), ['rejected', 'reject', 'ditolak'], true))
                <tr>
                    <th>Alasan Penolakan</th>
                    <td>{{ trim((string) ($item['alasan_penolakan'] ?? '')) ?: 'Alasan penolakan belum tersedia.' }}</td>
                </tr>
            @endif
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

        @if ((string) $partNumber === '6')
            <table class="part6-photo-table">
                <tbody>
        @elseif ((string) $partNumber !== '5')
            <table class="question-table">
                <thead>
                    <tr>
                        <th>Pertanyaan</th>
                        <th>Jawaban</th>
                    </tr>
                </thead>
                <tbody>
        @endif

        @if ((string) $partNumber === '5')
            @forelse(($part['members'] ?? []) as $member)
                @php
                    $memberName = data_get($member, 'nama')
                        ?? data_get($member, 'nama_lengkap')
                        ?? 'Anggota keluarga';
                    $memberNik = data_get($member, 'nik');
                    $memberRelation = data_get($member, 'status_keluarga');
                @endphp

                <div class="part5-member-block">
                    <h4 class="part5-member-heading">
                        {{ $memberName }}
                        @if ($memberNik)
                            <span>NIK: {{ $memberNik }}</span>
                        @endif
                        @if ($memberRelation)
                            <span> — {{ $memberRelation }}</span>
                        @endif
                    </h4>

                    <table class="question-table part5-member-table">
                        <thead>
                            <tr>
                                <th>Pertanyaan</th>
                                <th>Jawaban</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(data_get($member, 'questions', []) as $question)
                                @php
                                    $questionText = data_get($question, 'text')
                                        ?? data_get($question, 'question')
                                        ?? data_get($question, 'pertanyaan')
                                        ?? 'Pertanyaan';
                                    $answer = data_get($question, 'answer')
                                        ?? data_get($question, 'jawaban')
                                        ?? data_get($question, 'value')
                                        ?? '';

                                    if (is_array($answer) || is_object($answer)) {
                                        $answer = json_encode(
                                            $answer,
                                            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                                        );
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $questionText }}</td>
                                    <td>{{ trim((string) $answer) !== '' ? $answer : 'Belum diisi' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="muted">Belum ada jawaban pada bagian ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @empty
                <table class="question-table">
                    <thead>
                        <tr>
                            <th>Pertanyaan</th>
                            <th>Jawaban</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2" class="muted">Belum ada jawaban anggota keluarga pada bagian ini.</td>
                        </tr>
                    </tbody>
                </table>
            @endforelse
        @else
        @forelse(($part['questions'] ?? []) as $questionIndex => $question)
                    @php
                        $questionText =
                            data_get($question, 'question')
                            ?? data_get($question, 'pertanyaan')
                            ?? data_get($question, 'text')
                            ?? 'Pertanyaan';

                        // Bersihkan tanda bintang hanya pada 11 label Part 1 di PDF.
                        if ((string) $partNumber === '1') {
                            $part1ResultLabels = [
                                'NIK',
                                'Nama Kepala Keluarga',
                                'Status dalam Keluarga',
                                'Nomor KK',
                                'Jumlah Anggota Keluarga',
                                'Provinsi',
                                'Kabupaten / Kota',
                                'Kecamatan',
                                'Kelurahan',
                                'Kode Pos',
                                'RT / RW',
                                'Alamat Lengkap',
                            ];

                            $labelWithoutAsterisk = preg_replace('/\\s*\\*\\s*$/u', '', trim((string) $questionText));

                            if (in_array($labelWithoutAsterisk, $part1ResultLabels, true)) {
                                $questionText = $labelWithoutAsterisk;
                            }
                        }

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

                        /*
                        |------------------------------------------------------------------
                        | GAMBAR PETA STATIS UNTUK PDF
                        |------------------------------------------------------------------
                        | PDF Dompdf tidak menjalankan JavaScript Leaflet. Jadi peta
                        | Leaflet ditampilkan sebagai gambar peta OpenStreetMap statis
                        | berdasarkan koordinat, lalu dikonversi menjadi base64.
                        */
                        $pdfMapImageSource = '';
                        $mapValue = trim($answer);
                        $mapCoordinates = null;

                        if (
                            $isMapAnswer
                            && preg_match('/^(-?\d+(?:\.\d+)?)\s*,\s*(-?\d+(?:\.\d+)?)$/', $mapValue, $coordinateMatch)
                        ) {
                            $latitude = (float) $coordinateMatch[1];
                            $longitude = (float) $coordinateMatch[2];

                            if (
                                $latitude >= -90 && $latitude <= 90
                                && $longitude >= -180 && $longitude <= 180
                            ) {
                                $mapCoordinates = [$latitude, $longitude];

                                /*
                                 * Susun gambar peta dari tile OpenStreetMap.
                                 * Cara ini menghasilkan peta jalan/bangunan seperti Leaflet
                                 * di halaman web, tanpa bergantung pada layanan static-map.
                                 */
                                try {
                                    if (
                                        function_exists('imagecreatetruecolor')
                                        && function_exists('imagecreatefromstring')
                                        && function_exists('imagecopy')
                                        && function_exists('imagecrop')
                                    ) {
                                        $zoom = 17;
                                        $tileSize = 256;
                                        $mapWidth = 800;
                                        $mapHeight = 420;
                                        $worldSize = $tileSize * (2 ** $zoom);
                                        $safeLatitude = max(-85.05112878, min(85.05112878, $latitude));
                                        $centerPixelX = (($longitude + 180) / 360) * $worldSize;
                                        $latRadians = deg2rad($safeLatitude);
                                        $centerPixelY = (1 - log(tan($latRadians) + (1 / cos($latRadians))) / M_PI) / 2 * $worldSize;

                                        $tileStartX = (int) floor(($centerPixelX - ($mapWidth / 2)) / $tileSize);
                                        $tileStartY = (int) floor(($centerPixelY - ($mapHeight / 2)) / $tileSize);
                                        $tileEndX = (int) floor(($centerPixelX + ($mapWidth / 2)) / $tileSize);
                                        $tileEndY = (int) floor(($centerPixelY + ($mapHeight / 2)) / $tileSize);

                                        $tileColumns = $tileEndX - $tileStartX + 1;
                                        $tileRows = $tileEndY - $tileStartY + 1;
                                        $tileCanvas = imagecreatetruecolor($tileColumns * $tileSize, $tileRows * $tileSize);
                                        $background = imagecolorallocate($tileCanvas, 242, 242, 242);
                                        imagefill($tileCanvas, 0, 0, $background);
                                        $allTilesLoaded = true;

                                        for ($tileX = $tileStartX; $tileX <= $tileEndX; $tileX++) {
                                            for ($tileY = $tileStartY; $tileY <= $tileEndY; $tileY++) {
                                                $wrappedX = (($tileX % (2 ** $zoom)) + (2 ** $zoom)) % (2 ** $zoom);
                                                $tileUrl = 'https://tile.openstreetmap.org/' . $zoom . '/' . $wrappedX . '/' . $tileY . '.png';
                                                $tileBinary = false;

                                                if (function_exists('curl_init')) {
                                                    $tileCurl = curl_init($tileUrl);
                                                    curl_setopt_array($tileCurl, [
                                                        CURLOPT_RETURNTRANSFER => true,
                                                        CURLOPT_FOLLOWLOCATION => true,
                                                        CURLOPT_CONNECTTIMEOUT => 5,
                                                        CURLOPT_TIMEOUT => 10,
                                                        CURLOPT_USERAGENT => 'DinsosPasuruanPDF/1.0 (map image in report)',
                                                        CURLOPT_HTTPHEADER => ['Accept: image/png'],
                                                        CURLOPT_SSL_VERIFYPEER => true,
                                                        CURLOPT_SSL_VERIFYHOST => 2,
                                                    ]);
                                                    $tileResponse = curl_exec($tileCurl);
                                                    $tileStatus = (int) curl_getinfo($tileCurl, CURLINFO_HTTP_CODE);
                                                    curl_close($tileCurl);
                                                    if ($tileResponse !== false && $tileStatus === 200) {
                                                        $tileBinary = $tileResponse;
                                                    }
                                                } else {
                                                    $tileContext = stream_context_create([
                                                        'http' => [
                                                            'timeout' => 10,
                                                            'user_agent' => 'DinsosPasuruanPDF/1.0 (map image in report)',
                                                        ],
                                                        'ssl' => ['verify_peer' => true, 'verify_peer_name' => true],
                                                    ]);
                                                    $tileResponse = @file_get_contents($tileUrl, false, $tileContext);
                                                    if ($tileResponse !== false) {
                                                        $tileBinary = $tileResponse;
                                                    }
                                                }

                                                $tileImage = is_string($tileBinary) ? @imagecreatefromstring($tileBinary) : false;
                                                if ($tileImage === false) {
                                                    $allTilesLoaded = false;
                                                    continue;
                                                }

                                                imagecopy(
                                                    $tileCanvas,
                                                    $tileImage,
                                                    ($tileX - $tileStartX) * $tileSize,
                                                    ($tileY - $tileStartY) * $tileSize,
                                                    0,
                                                    0,
                                                    $tileSize,
                                                    $tileSize
                                                );
                                                imagedestroy($tileImage);
                                            }
                                        }

                                        if ($allTilesLoaded) {
                                            $cropX = (int) round($centerPixelX - ($tileStartX * $tileSize) - ($mapWidth / 2));
                                            $cropY = (int) round($centerPixelY - ($tileStartY * $tileSize) - ($mapHeight / 2));
                                            $cropX = max(0, min(imagesx($tileCanvas) - $mapWidth, $cropX));
                                            $cropY = max(0, min(imagesy($tileCanvas) - $mapHeight, $cropY));
                                            $mapImage = imagecrop($tileCanvas, [
                                                'x' => $cropX,
                                                'y' => $cropY,
                                                'width' => $mapWidth,
                                                'height' => $mapHeight,
                                            ]);

                                            if ($mapImage !== false) {
                                                // Penanda biru seperti marker pada peta Leaflet di halaman web.
                                                $pinBlue = imagecolorallocate($mapImage, 35, 133, 205);
                                                $pinDark = imagecolorallocate($mapImage, 25, 93, 155);
                                                imagefilledellipse($mapImage, 400, 202, 25, 25, $pinDark);
                                                imagefilledellipse($mapImage, 400, 201, 19, 19, $pinBlue);
                                                imagefilledellipse($mapImage, 400, 201, 7, 7, imagecolorallocate($mapImage, 255, 255, 255));
                                                imagefilledpolygon($mapImage, [390, 207, 410, 207, 400, 226], $pinDark);

                                                // Atribusi wajib untuk peta OpenStreetMap.
                                                $attributionColor = imagecolorallocate($mapImage, 45, 45, 45);
                                                $attributionBg = imagecolorallocatealpha($mapImage, 255, 255, 255, 20);
                                                imagefilledrectangle($mapImage, 570, 398, 797, 418, $attributionBg);
                                                imagestring($mapImage, 2, 575, 402, '(c) OpenStreetMap contributors', $attributionColor);

                                                ob_start();
                                                imagepng($mapImage);
                                                $mapBinary = ob_get_clean();
                                                if (is_string($mapBinary) && $mapBinary !== '') {
                                                    $pdfMapImageSource = 'data:image/png;base64,' . base64_encode($mapBinary);
                                                }
                                                imagedestroy($mapImage);
                                            }
                                        }

                                        imagedestroy($tileCanvas);
                                    }
                                } catch (\Throwable $mapException) {
                                    $pdfMapImageSource = '';
                                }
                            }
                        }

                        /*
                        | Format nominal Part 3 menjadi Rupiah.
                        | Format diterapkan pada pertanyaan yang jelas berkaitan
                        | dengan nominal uang agar angka biasa tidak ikut berubah.
                        */
                        $isCurrencyQuestion = (string) $partNumber === '3'
                            && preg_match(
                                '/rupiah|pendapatan|penghasilan|pengeluaran|biaya|harga|upah|gaji|nominal|nilai bantuan|jumlah bantuan|penghasilan|uang/i',
                                (string) $questionText
                            );

                        if ($isCurrencyQuestion && $answer !== '') {
                            $numericAnswer = trim($answer);
                            $numericAnswer = preg_replace('/^Rp\\.?\\s*/iu', '', $numericAnswer);
                            $numericAnswer = str_replace([' ', '.'], '', $numericAnswer);
                            $numericAnswer = str_replace(',', '.', $numericAnswer);

                            if (is_numeric($numericAnswer)) {
                                $amount = (float) $numericAnswer;
                                $formattedAmount = number_format($amount, 0, ',', '.');
                                $answer = 'Rp ' . $formattedAmount;
                            }
                        }

                        $displayAnswer =
                            $answer !== ''
                                ? $answer
                                : 'Belum diisi';
                    @endphp

                    @if ((string) $partNumber === '6')
                        @if ($questionIndex % 3 === 0)
                            <tr>
                        @endif

                        <td class="part6-photo-cell">
                            <span class="part6-question-title">
                                {{ $questionText }}
                            </span>

                            @if($isImageAnswer)
                                @if($pdfImageSource !== '')
                                    <img
                                        src="{{ $pdfImageSource }}"
                                        class="part6-photo"
                                        alt="{{ $questionText }}"
                                    >
                                @elseif($imageSource !== '')
                                    <span class="part6-photo-missing">
                                        File foto ditemukan, tetapi tidak dapat dibaca oleh PDF.
                                    </span>
                                @else
                                    <span class="part6-photo-missing">
                                        File foto tidak tersedia.
                                    </span>
                                @endif
                            @else
                                <span class="part6-photo-missing">
                                    {{ $displayAnswer }}
                                </span>
                            @endif
                        </td>

                        @if ($questionIndex % 3 === 2 || $loop->last)
                            @if ($loop->last && $questionIndex % 3 !== 2)
                                @for ($emptyCell = ($questionIndex % 3) + 1; $emptyCell < 3; $emptyCell++)
                                    <td class="part6-photo-cell"></td>
                                @endfor
                            @endif
                            </tr>
                        @endif
                    @else
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

                                    <div class="map-answer">
                                        <strong>Lokasi Rumah / Geotagging</strong>

                                        @if($mapValue !== '')
                                            @if($mapCoordinates !== null)
                                                @if($pdfMapImageSource !== '')
                                                    <img
                                                        src="{{ $pdfMapImageSource }}"
                                                        class="pdf-leaflet-map"
                                                        alt="Peta lokasi rumah berdasarkan koordinat"
                                                    >
                                                @else
                                                    <div class="map-coordinates">
                                                        Gambar peta tidak dapat dimuat. Koordinat lokasi tetap tersedia di bawah.
                                                    </div>
                                                @endif

                                                <div class="map-coordinates">
                                                    Koordinat: {{ $mapCoordinates[0] }}, {{ $mapCoordinates[1] }}
                                                </div>

                                                @php
                                                    $mapUrl = 'https://www.openstreetmap.org/?mlat='
                                                        . rawurlencode((string) $mapCoordinates[0])
                                                        . '&mlon=' . rawurlencode((string) $mapCoordinates[1])
                                                        . '#map=16/' . rawurlencode((string) $mapCoordinates[0])
                                                        . '/' . rawurlencode((string) $mapCoordinates[1]);
                                                @endphp
                                                <a class="map-link" href="{{ $mapUrl }}">
                                                    Lihat lokasi di OpenStreetMap
                                                </a>
                                            @else
                                                <div class="map-coordinates">
                                                    Format koordinat tidak valid: {{ $mapValue }}
                                                </div>
                                            @endif
                                        @else
                                            <div class="map-coordinates">
                                                Koordinat belum tersedia.
                                            </div>
                                        @endif
                                    </div>

                                @else
                                    @if($isCurrencyQuestion && $answer !== '')
                                        <span class="currency-answer">{{ $displayAnswer }}</span>
                                    @else
                                        {{ $displayAnswer }}
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endif

                @empty
                    @if ((string) $partNumber === '6')
                        <tr>
                            <td colspan="3" class="muted">
                                Belum ada foto pada bagian ini.
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="2" class="muted">
                                Belum ada jawaban pada bagian ini.
                            </td>
                        </tr>
                    @endif
                @endforelse
        @endif

        @if ((string) $partNumber !== '5')
            </tbody>
            </table>
        @endif

        @if(!$loop->last)
            <div style="height: 8px;"></div>
        @endif

    @empty
        <p class="muted">Hasil kuisioner belum tersedia.</p>
    @endforelse

</body>
</html>
