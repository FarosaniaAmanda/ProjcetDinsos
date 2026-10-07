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

        .muted {
            color: #64748b;
        }

        .page-break {
            page-break-before: always;
        }

        /* =====================================================
           FOTO KUISIONER
        ===================================================== */

        .answer-image-wrapper {
            margin-top: 5px;
            margin-bottom: 5px;
            page-break-inside: avoid;
        }

        .answer-image {
            display: block;
            max-width: 230px;
            max-height: 180px;
            width: auto;
            height: auto;
            border: 1px solid #d9dee8;
            padding: 3px;
            background: #ffffff;
        }

        .image-caption {
            margin-top: 4px;
            color: #64748b;
            font-size: 8px;
        }

        .answer-text {
            white-space: pre-wrap;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .multiple-images {
            display: block;
        }

        .no-image {
            color: #64748b;
            font-size: 9px;
        }
    </style>
</head>

<body>

    <h1>Laporan Pendataan Keluarga</h1>

    <div class="subtitle">
        Satu keluarga berdasarkan data Verifikasi terbaru
    </div>


    {{-- =====================================================
         DATA KELUARGA DAN PENDATAAN
    ====================================================== --}}

    <h2>Data Keluarga dan Pendataan</h2>

    <table>
        <tbody>
            <tr>
                <th>No. KK</th>
                <td>{{ $item['no_kk'] ?? '-' }}</td>
            </tr>

            <tr>
                <th>Kepala Keluarga</th>
                <td>{{ $item['nama'] ?? '-' }}</td>
            </tr>

            <tr>
                <th>NIK Kepala Keluarga</th>
                <td>{{ $item['nik'] ?? '-' }}</td>
            </tr>

            <tr>
                <th>Jumlah Anggota</th>
                <td>{{ $item['jumlah_anggota'] ?? 0 }}</td>
            </tr>

            <tr>
                <th>Kecamatan / Kelurahan</th>
                <td>{{ $item['wilayah'] ?? '-' }}</td>
            </tr>

            <tr>
                <th>Alamat</th>
                <td>{{ $item['alamat_lengkap'] ?? '-' }}</td>
            </tr>

            <tr>
                <th>Kode Pos</th>
                <td>{{ $item['kode_pos'] ?? '-' }}</td>
            </tr>

            <tr>
                <th>Periode</th>
                <td>{{ $item['periode'] ?? '-' }}</td>
            </tr>

            <tr>
                <th>Tanggal Pendataan</th>
                <td>{{ $item['tanggal'] ?? '-' }}</td>
            </tr>

            <tr>
                <th>Status</th>
                <td>{{ $item['status_label'] ?? '-' }}</td>
            </tr>

            <tr>
                <th>Petugas</th>
                <td>{{ $item['petugas'] ?? '-' }}</td>
            </tr>
        </tbody>
    </table>


    {{-- =====================================================
         ANGGOTA KELUARGA
    ====================================================== --}}

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

                    <td>
                        {{ $member['nik'] ?? '-' }}
                    </td>

                    <td>
                        {{ $member['nama_lengkap'] ?? '-' }}
                    </td>

                    <td>
                        {{ $member['status_keluarga'] ?? '-' }}
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="4" class="muted">
                        Data anggota keluarga belum tersedia.
                    </td>
                </tr>

            @endforelse

        </tbody>
    </table>


    <div class="page-break"></div>


    {{-- =====================================================
         HASIL KUISIONER
    ====================================================== --}}

    <h2>Hasil Kuisioner</h2>


    @php

        /*
        |--------------------------------------------------------------------------
        | Fungsi untuk mencari file gambar lokal
        |--------------------------------------------------------------------------
        */

        $getLocalImageSrc = function ($value) {

            if (!is_string($value)) {
                return null;
            }

            $value = trim($value);

            if ($value === '') {
                return null;
            }

            /*
            |--------------------------------------------------------------------------
            | Ambil path dari URL
            |--------------------------------------------------------------------------
            |
            | Contoh:
            | http://localhost:8000/storage/kuisioner/rumah/foto.jpeg
            |
            | akan menjadi:
            | storage/kuisioner/rumah/foto.jpeg
            |
            */

            $path = $value;

            if (filter_var($value, FILTER_VALIDATE_URL)) {

                $parsedPath = parse_url($value, PHP_URL_PATH);

                if (is_string($parsedPath) && $parsedPath !== '') {
                    $path = $parsedPath;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Bersihkan slash
            |--------------------------------------------------------------------------
            */

            $path = ltrim($path, '/');

            /*
            |--------------------------------------------------------------------------
            | Hilangkan "storage/" agar menjadi path storage/app/public
            |--------------------------------------------------------------------------
            */

            if (str_starts_with($path, 'storage/')) {
                $path = substr($path, strlen('storage/'));
            }

            /*
            |--------------------------------------------------------------------------
            | Kalau langsung "public/storage/..."
            |--------------------------------------------------------------------------
            */

            if (str_starts_with($path, 'public/storage/')) {
                $path = substr($path, strlen('public/storage/'));
            }

            /*
            |--------------------------------------------------------------------------
            | Kalau path diawali "/storage"
            |--------------------------------------------------------------------------
            */

            $path = ltrim($path, '/');

            /*
            |--------------------------------------------------------------------------
            | Cek ekstensi gambar
            |--------------------------------------------------------------------------
            */

            $extension = strtolower(
                pathinfo(parse_url($value, PHP_URL_PATH) ?? $path, PATHINFO_EXTENSION)
            );

            $allowedExtensions = [
                'jpg',
                'jpeg',
                'png',
                'gif',
                'webp',
                'bmp',
            ];

            if (!in_array($extension, $allowedExtensions, true)) {
                return null;
            }

            /*
            |--------------------------------------------------------------------------
            | Kandidat lokasi file
            |--------------------------------------------------------------------------
            */

            $candidatePaths = [];

            /*
            | File pada storage/app/public
            */

            $candidatePaths[] = storage_path('app/public/' . $path);

            /*
            | File pada public/storage
            */

            $candidatePaths[] = public_path('storage/' . $path);

            /*
            | Kalau path memang sudah kuisioner/...
            */

            if (str_starts_with($path, 'kuisioner/')) {

                $candidatePaths[] = storage_path('app/public/' . $path);

                $candidatePaths[] = public_path('storage/' . $path);
            }

            /*
            |--------------------------------------------------------------------------
            | Cari file yang benar-benar ada
            |--------------------------------------------------------------------------
            */

            foreach ($candidatePaths as $filePath) {

                if (is_file($filePath) && is_readable($filePath)) {

                    $mime = 'image/jpeg';

                    if ($extension === 'png') {
                        $mime = 'image/png';
                    } elseif ($extension === 'gif') {
                        $mime = 'image/gif';
                    } elseif ($extension === 'webp') {
                        $mime = 'image/webp';
                    } elseif ($extension === 'bmp') {
                        $mime = 'image/bmp';
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Convert menjadi Base64
                    |--------------------------------------------------------------------------
                    |
                    | Ini penting untuk Dompdf.
                    | Jadi Dompdf tidak perlu mengakses localhost.
                    |
                    */

                    $contents = @file_get_contents($filePath);

                    if ($contents !== false) {

                        return 'data:' . $mime . ';base64,' . base64_encode($contents);
                    }
                }
            }

            return null;
        };


        /*
        |--------------------------------------------------------------------------
        | Fungsi mengambil gambar dari sebuah jawaban
        |--------------------------------------------------------------------------
        */

        $getImageValues = function ($answer) {

            if (is_array($answer)) {
                return $answer;
            }

            if (is_object($answer)) {
                return (array) $answer;
            }

            if (!is_string($answer)) {
                return [];
            }

            $answer = trim($answer);

            if ($answer === '') {
                return [];
            }

            /*
            |--------------------------------------------------------------------------
            | Kalau jawaban merupakan JSON array
            |--------------------------------------------------------------------------
            */

            $decoded = json_decode($answer, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {

                return $decoded;
            }

            return [$answer];
        };

    @endphp


    @forelse(($item['kuisioner'] ?? []) as $partIndex => $part)

        <h3>
            Part {{ $part['part'] ?? $partIndex + 1 }}
            -
            {{ $part['title'] ?? '' }}
        </h3>


        <table class="question-table">

            <thead>
                <tr>
                    <th>Pertanyaan</th>
                    <th>Jawaban</th>
                </tr>
            </thead>


            <tbody>

                @forelse(($part['questions'] ?? []) as $question)

                    @php

                        /*
                        |--------------------------------------------------------------------------
                        | Ambil jawaban
                        |--------------------------------------------------------------------------
                        */

                        $answer = data_get($question, 'answer')
                            ?? data_get($question, 'jawaban')
                            ?? data_get($question, 'value')
                            ?? '';


                        /*
                        |--------------------------------------------------------------------------
                        | Nama pertanyaan
                        |--------------------------------------------------------------------------
                        */

                        $questionText =
                            data_get($question, 'question')
                            ?? data_get($question, 'text')
                            ?? 'Pertanyaan';


                        /*
                        |--------------------------------------------------------------------------
                        | Cek apakah jawaban berupa array/object
                        |--------------------------------------------------------------------------
                        */

                        $rawAnswer = $answer;

                        if (is_object($rawAnswer)) {
                            $rawAnswer = (array) $rawAnswer;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Coba cari gambar
                        |--------------------------------------------------------------------------
                        */

                        $imageItems = $getImageValues($rawAnswer);

                        $imageResults = [];

                        foreach ($imageItems as $imageItem) {

                            if (is_array($imageItem)) {

                                /*
                                | Coba beberapa kemungkinan key
                                */

                                $possibleImage =
                                    $imageItem['url']
                                    ?? $imageItem['path']
                                    ?? $imageItem['file']
                                    ?? $imageItem['foto']
                                    ?? $imageItem['image']
                                    ?? $imageItem['value']
                                    ?? null;

                            } else {

                                $possibleImage = $imageItem;
                            }


                            if (!is_string($possibleImage)) {
                                continue;
                            }


                            $imageSrc = $getLocalImageSrc($possibleImage);


                            if ($imageSrc !== null) {

                                $imageResults[] = [
                                    'src' => $imageSrc,
                                    'original' => $possibleImage,
                                ];
                            }
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Tentukan jawaban teks
                        |--------------------------------------------------------------------------
                        */

                        $textAnswer = $rawAnswer;

                        if (is_array($textAnswer) || is_object($textAnswer)) {

                            $textAnswer = json_encode(
                                $textAnswer,
                                JSON_UNESCAPED_UNICODE |
                                JSON_UNESCAPED_SLASHES |
                                JSON_PRETTY_PRINT
                            );
                        }

                        if ($textAnswer === null) {
                            $textAnswer = '';
                        }

                        $textAnswer = trim((string) $textAnswer);

                    @endphp


                    <tr>

                        {{-- =====================================================
                             PERTANYAAN
                        ====================================================== --}}

                        <td>
                            {{ $questionText }}
                        </td>


                        {{-- =====================================================
                             JAWABAN
                        ====================================================== --}}

                        <td>

                            @if(count($imageResults) > 0)

                                {{-- =================================================
                                     GAMBAR
                                ================================================== --}}

                                <div class="multiple-images">

                                    @foreach($imageResults as $image)

                                        <div class="answer-image-wrapper">

                                            <img
                                                src="{{ $image['src'] }}"
                                                class="answer-image"
                                            >

                                            <div class="image-caption">
                                                Foto pendukung
                                            </div>

                                        </div>

                                    @endforeach

                                </div>


                                {{-- =================================================
                                     Kalau ada teks selain URL foto
                                ================================================== --}}

                                @if(
                                    $textAnswer !== ''
                                    &&
                                    !filter_var($textAnswer, FILTER_VALIDATE_URL)
                                )

                                    @php
                                        $cleanText = $textAnswer;

                                        /*
                                        |--------------------------------------------------------------------------
                                        | Jangan tampilkan JSON mentah kalau isinya
                                        | hanya URL/path gambar.
                                        |--------------------------------------------------------------------------
                                        */

                                        $containsOnlyImagePath =
                                            str_contains($cleanText, '/storage/')
                                            ||
                                            str_contains($cleanText, 'kuisioner/rumah/')
                                            ||
                                            str_ends_with(strtolower($cleanText), '.jpeg')
                                            ||
                                            str_ends_with(strtolower($cleanText), '.jpg')
                                            ||
                                            str_ends_with(strtolower($cleanText), '.png');
                                    @endphp


                                    @if(!$containsOnlyImagePath)

                                        <div class="answer-text">
                                            {{ $cleanText }}
                                        </div>

                                    @endif

                                @endif

                            @elseif($textAnswer !== '')

                                {{-- =================================================
                                     JAWABAN BIASA
                                ================================================== --}}

                                <div class="answer-text">
                                    {{ $textAnswer }}
                                </div>

                            @else

                                <span class="muted">
                                    Belum diisi
                                </span>

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

    @empty

        <p class="muted">
            Hasil kuisioner belum tersedia.
        </p>

    @endforelse


</body>
</html>