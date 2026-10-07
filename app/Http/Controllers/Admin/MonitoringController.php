<?php

namespace App\Http\Controllers\Admin;

use App\Models\KeluargaFotoRumah;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class MonitoringController extends VerifikasiController
{
    /*
    |--------------------------------------------------------------------------
    | DATA MONITORING
    |--------------------------------------------------------------------------
    |
    | Monitoring mengambil sumber data dari VerifikasiController.
    |
    | Yang ditampilkan di Monitoring:
    | - approved  = Disetujui
    | - rejected  = Ditolak
    |
    | Draft dan Menunggu Verifikasi tetap ada di Verifikasi,
    | tetapi belum masuk daftar Monitoring.
    |
    */
    protected function getData()
    {
        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA DARI VERIFIKASI
        |--------------------------------------------------------------------------
        */

        $allData = collect(
            parent::getData()
        );


        /*
        |--------------------------------------------------------------------------
        | NORMALISASI STATUS
        |--------------------------------------------------------------------------
        */

        $allData = $allData
            ->map(function ($item) {

                $status = strtolower(
                    trim(
                        (string) data_get(
                            $item,
                            'status',
                            ''
                        )
                    )
                );


                /*
                |--------------------------------------------------------------------------
                | DISETUJUI
                |--------------------------------------------------------------------------
                */

                if (
                    in_array(
                        $status,
                        [
                            'approved',
                            'disetujui',
                        ],
                        true
                    )
                ) {

                    $item['status'] = 'approved';

                    $item['status_label'] =
                        'Disetujui';
                }


                /*
                |--------------------------------------------------------------------------
                | DITOLAK
                |--------------------------------------------------------------------------
                */

                elseif (
                    in_array(
                        $status,
                        [
                            'rejected',
                            'reject',
                            'ditolak',
                        ],
                        true
                    )
                ) {

                    $item['status'] = 'rejected';

                    $item['status_label'] =
                        'Ditolak';
                }


                /*
                |--------------------------------------------------------------------------
                | MENUNGGU VERIFIKASI
                |--------------------------------------------------------------------------
                */

                elseif (
                    in_array(
                        $status,
                        [
                            'pending',
                            'menunggu',
                            'menunggu_verifikasi',
                            'menunggu verifikasi',
                        ],
                        true
                    )
                ) {

                    $item['status'] = 'pending';

                    $item['status_label'] =
                        'Menunggu Verifikasi';
                }


                /*
                |--------------------------------------------------------------------------
                | DRAFT
                |--------------------------------------------------------------------------
                */

                else {

                    $item['status'] = 'draft';

                    $item['status_label'] =
                        'Draft';
                }


                return $item;
            });


        /*
        |--------------------------------------------------------------------------
        | MONITORING HANYA APPROVED + REJECTED
        |--------------------------------------------------------------------------
        */

        $allData = $allData
            ->filter(function ($item) {

                return in_array(
                    data_get(
                        $item,
                        'status'
                    ),
                    [
                        'approved',
                        'rejected',
                    ],
                    true
                );
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | LENGKAPI DATA KUISIONER
        |--------------------------------------------------------------------------
        */

        return $allData
            ->map(function ($item) {

                return $this->completeMonitoringData(
                    $item
                );

            })
            ->values();
    }


    /*
    |--------------------------------------------------------------------------
    | LENGKAPI DATA MONITORING
    |--------------------------------------------------------------------------
    */

    protected function completeMonitoringData($item)
    {
        /*
        |--------------------------------------------------------------------------
        | PASTIKAN ITEM BERUPA ARRAY
        |--------------------------------------------------------------------------
        */

        if (
            $item instanceof \Illuminate\Contracts\Support\Arrayable
        ) {

            $item =
                $item->toArray();

        }

        if (
            !is_array($item)
        ) {

            $item =
                (array) $item;
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        $status = strtolower(
            trim(
                (string) data_get(
                    $item,
                    'status',
                    'draft'
                )
            )
        );


        if (
            in_array(
                $status,
                [
                    'approved',
                    'disetujui',
                ],
                true
            )
        ) {

            $status =
                'approved';

        }

        elseif (
            in_array(
                $status,
                [
                    'rejected',
                    'reject',
                    'ditolak',
                ],
                true
            )
        ) {

            $status =
                'rejected';

        }

        elseif (
            in_array(
                $status,
                [
                    'pending',
                    'menunggu',
                    'menunggu_verifikasi',
                    'menunggu verifikasi',
                ],
                true
            )
        ) {

            $status =
                'pending';

        }

        else {

            $status =
                'draft';
        }


        $item['status'] =
            $status;


        /*
        |--------------------------------------------------------------------------
        | STATUS LABEL
        |--------------------------------------------------------------------------
        */

        $item['status_label'] =
            match ($status) {

                'approved' =>
                    'Disetujui',

                'rejected' =>
                    'Ditolak',

                'pending' =>
                    'Menunggu Verifikasi',

                default =>
                    'Draft',
            };


        /*
        |--------------------------------------------------------------------------
        | STATUS CLASS
        |--------------------------------------------------------------------------
        |
        | Dipakai oleh Blade apabila diperlukan.
        |
        */

        $item['status_class'] =
            match ($status) {

                'approved' =>
                    'status-approved',

                'rejected' =>
                    'status-rejected',

                'pending' =>
                    'status-pending',

                default =>
                    'status-draft',
            };


        /*
        |--------------------------------------------------------------------------
        | AMBIL KUISIONER DARI VERIFIKASI
        |--------------------------------------------------------------------------
        */

        $kuisioner =
            collect(
                data_get(
                    $item,
                    'kuisioner',
                    []
                )
            )
            ->map(function ($part) {

                if (
                    !is_array($part)
                ) {

                    return $part;
                }


                /*
                |--------------------------------------------------------------------------
                | NORMALISASI QUESTIONS
                |--------------------------------------------------------------------------
                */

                $questions =
                    collect(
                        data_get(
                            $part,
                            'questions',
                            []
                        )
                    )
                    ->map(function ($question) {

                        if (
                            !is_array($question)
                        ) {

                            return $question;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | TEKS PERTANYAAN
                        |--------------------------------------------------------------------------
                        */

                        $questionText =
                            (string) (
                                data_get(
                                    $question,
                                    'question'
                                )
                                ??
                                data_get(
                                    $question,
                                    'pertanyaan'
                                )
                                ??
                                data_get(
                                    $question,
                                    'text'
                                )
                                ??
                                ''
                            );


                        $lowerText =
                            strtolower(
                                trim(
                                    $questionText
                                )
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | GEOTAGGING
                        |--------------------------------------------------------------------------
                        */

                        if (
                            str_contains(
                                $lowerText,
                                'geotagging'
                            )
                            ||
                            str_contains(
                                $lowerText,
                                'geotag'
                            )
                            ||
                            str_contains(
                                $lowerText,
                                'titik lokasi'
                            )
                            ||
                            str_contains(
                                $lowerText,
                                'lokasi tempat tinggal'
                            )
                            ||
                            str_contains(
                                $lowerText,
                                'koordinat'
                            )
                            ||
                            str_contains(
                                $lowerText,
                                'latitude'
                            )
                            ||
                            str_contains(
                                $lowerText,
                                'longitude'
                            )
                        ) {

                            $question['type'] =
                                'map';


                            /*
                            |--------------------------------------------------------------------------
                            | SIMPAN KOORDINAT TAMBAHAN
                            |--------------------------------------------------------------------------
                            */

                            $answer =
                                data_get(
                                    $question,
                                    'answer'
                                );


                            $coordinates =
                                $this->extractCoordinates(
                                    $answer
                                );


                            if (
                                $coordinates
                            ) {

                                $question['latitude'] =
                                    $coordinates['latitude'];

                                $question['longitude'] =
                                    $coordinates['longitude'];

                                $question['coordinates'] =
                                    $coordinates['latitude']
                                    . ', '
                                    . $coordinates['longitude'];
                            }
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | DETEKSI FOTO
                        |--------------------------------------------------------------------------
                        */

                        $type =
                            strtolower(
                                (string) (
                                    data_get(
                                        $question,
                                        'type',
                                        ''
                                    )
                                )
                            );


                        $answer =
                            data_get(
                                $question,
                                'answer',
                                ''
                            );


                        $imageUrl =
                            data_get(
                                $question,
                                'imageUrl'
                            )
                            ??
                            data_get(
                                $question,
                                'image_url'
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | JIKA TYPE IMAGE
                        |--------------------------------------------------------------------------
                        */

                        if (
                            in_array(
                                $type,
                                [
                                    'image',
                                    'foto',
                                    'photo',
                                    'gambar',
                                ],
                                true
                            )
                        ) {

                            $question['type'] =
                                'image';


                            if (
                                !$imageUrl
                                &&
                                is_string($answer)
                            ) {

                                $imageUrl =
                                    $this->makeStorageUrl(
                                        $answer
                                    );
                            }


                            if (
                                $imageUrl
                            ) {

                                $question['imageUrl'] =
                                    $imageUrl;

                                $question['image_url'] =
                                    $imageUrl;
                            }
                        }


                        return $question;

                    })
                    ->values()
                    ->all();


                $part['questions'] =
                    $questions;


                return $part;

            })
            ->values()
            ->all();


        /*
        |--------------------------------------------------------------------------
        | PASTIKAN GEOTAGGING DIBACA DARI KUISIONER
        |--------------------------------------------------------------------------
        |
        | Pada beberapa data, koordinat tidak disimpan langsung pada $item,
        | tetapi berada di jawaban Part 1. Karena Blade Monitoring membaca
        | data-geotagging dari $item, koordinat harus dinaikkan ke level item.
        |
        */

        $geotagFromQuestionnaire = null;

        foreach ($kuisioner as $part) {

            foreach (data_get($part, 'questions', []) as $question) {

                if (!is_array($question)) {
                    continue;
                }

                $questionText = strtolower(trim((string) (
                    data_get($question, 'question')
                    ?? data_get($question, 'pertanyaan')
                    ?? data_get($question, 'text')
                    ?? ''
                )));

                $isGeotagQuestion =
                    str_contains($questionText, 'geotagging')
                    || str_contains($questionText, 'geotag')
                    || str_contains($questionText, 'titik lokasi')
                    || str_contains($questionText, 'lokasi tempat tinggal')
                    || str_contains($questionText, 'koordinat')
                    || str_contains($questionText, 'latitude')
                    || str_contains($questionText, 'longitude')
                    || strtolower((string) data_get($question, 'type')) === 'map';

                if (!$isGeotagQuestion) {
                    continue;
                }

                $answer =
                    data_get($question, 'answer')
                    ?? data_get($question, 'value')
                    ?? data_get($question, 'jawaban')
                    ?? data_get($question, 'coordinates')
                    ?? null;

                $coordinates = $this->extractCoordinates($answer);

                if (!$coordinates) {
                    $coordinates = $this->extractCoordinates([
                        'latitude' => data_get($question, 'latitude') ?? data_get($question, 'lat'),
                        'longitude' => data_get($question, 'longitude') ?? data_get($question, 'lng') ?? data_get($question, 'lon'),
                    ]);
                }

                if ($coordinates) {
                    $geotagFromQuestionnaire = $coordinates['latitude'] . ', ' . $coordinates['longitude'];
                    break 2;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | KODE KELUARGA PERIODE
        |--------------------------------------------------------------------------
        */

        $kodePeriode =
            data_get(
                $item,
                'keluarga_periode_kode'
            );


        /*
        |--------------------------------------------------------------------------
        | FOTO RUMAH
        |--------------------------------------------------------------------------
        */

        if (
            $kodePeriode
        ) {

            $fotoRumah =
                KeluargaFotoRumah::where(
                    'keluarga_periode_kode',
                    $kodePeriode
                )
                ->orderBy('id')
                ->get();


            /*
            |--------------------------------------------------------------------------
            | CARI PART 5
            |--------------------------------------------------------------------------
            */

            $part5Index =
                collect(
                    $kuisioner
                )->search(function ($part) {

                    return (
                        (int) data_get(
                            $part,
                            'part',
                            data_get(
                                $part,
                                'number',
                                0
                            )
                        )
                    ) === 5;

                });


            /*
            |--------------------------------------------------------------------------
            | JIKA PART 5 BELUM ADA
            |--------------------------------------------------------------------------
            */

            if (
                $part5Index === false
            ) {

                $kuisioner[] = [

                    'part' =>
                        5,

                    'title' =>
                        'Data Anggota Keluarga',

                    'questions' =>
                        [],
                ];


                $part5Index =
                    count(
                        $kuisioner
                    ) - 1;
            }


            /*
            |--------------------------------------------------------------------------
            | PASTIKAN QUESTIONS PART 5 ADA
            |--------------------------------------------------------------------------
            */

            if (
                !isset(
                    $kuisioner[
                        $part5Index
                    ]['questions']
                )
            ) {

                $kuisioner[
                    $part5Index
                ]['questions'] = [];
            }


            /*
            |--------------------------------------------------------------------------
            | MASUKKAN FOTO RUMAH
            |--------------------------------------------------------------------------
            */

            foreach (
                $fotoRumah
                as $foto
            ) {

                $path =
                    trim(
                        str_replace(
                            '\\',
                            '/',
                            (string) (
                                $foto->path_file
                                ?? ''
                            )
                        )
                    );


                if (
                    $path === ''
                ) {

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | BUAT URL FOTO
                |--------------------------------------------------------------------------
                */

                $imageUrl =
                    $this->makeStorageUrl(
                        $path
                    );


                if (
                    !$imageUrl
                ) {

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | JENIS FOTO
                |--------------------------------------------------------------------------
                */

                $jenisFoto =
                    strtolower(
                        trim(
                            (string) (
                                $foto->jenis_foto
                                ?? ''
                            )
                        )
                    );


                /*
                |--------------------------------------------------------------------------
                | LABEL FOTO
                |--------------------------------------------------------------------------
                */

                $labelFoto =
                    match ($jenisFoto) {

                        'tampak_depan' =>
                            'Foto Tampak Depan Rumah',

                        'tampak_belakang' =>
                            'Foto Tampak Belakang Rumah',

                        'ruang_tamu' =>
                            'Foto Ruang Tamu',

                        'kamar_mandi' =>
                            'Foto Kamar Mandi',

                        'dapur' =>
                            'Foto Dapur',

                        'kamar_tidur' =>
                            'Foto Kamar Tidur',

                        'ruang_keluarga' =>
                            'Foto Ruang Keluarga',

                        default =>
                            'Foto Rumah ' .
                            ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $jenisFoto
                                )
                            ),
                    };


                /*
                |--------------------------------------------------------------------------
                | CEK AGAR FOTO TIDAK DUPLIKAT
                |--------------------------------------------------------------------------
                */

                $sudahAda =
                    collect(
                        $kuisioner[
                            $part5Index
                        ]['questions']
                    )
                    ->contains(
                        function ($question)
                        use (
                            $imageUrl,
                            $path,
                            $jenisFoto
                        ) {

                            if (
                                !is_array(
                                    $question
                                )
                            ) {

                                return false;
                            }


                            $existingUrl =
                                (string) (
                                    data_get(
                                        $question,
                                        'imageUrl'
                                    )
                                    ??
                                    data_get(
                                        $question,
                                        'image_url'
                                    )
                                    ??
                                    data_get(
                                        $question,
                                        'answer',
                                        ''
                                    )
                                );


                            $existingText =
                                strtolower(
                                    (string) (
                                        data_get(
                                            $question,
                                            'question',
                                            ''
                                        )
                                    )
                                );


                            return
                                (
                                    $existingUrl !== ''
                                    &&
                                    (
                                        $existingUrl ===
                                        $imageUrl
                                        ||
                                        str_contains(
                                            $existingUrl,
                                            $path
                                        )
                                    )
                                )
                                ||
                                (
                                    $jenisFoto !== ''
                                    &&
                                    str_contains(
                                        $existingText,
                                        str_replace(
                                            '_',
                                            ' ',
                                            $jenisFoto
                                        )
                                    )
                                );
                        }
                    );


                if (
                    $sudahAda
                ) {

                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | TAMBAHKAN FOTO KE PART 5
                |--------------------------------------------------------------------------
                */

                $kuisioner[
                    $part5Index
                ]['questions'][] = [

                    'number' =>
                        0,

                    'question' =>
                        $labelFoto,

                    'text' =>
                        $labelFoto,

                    'answer' =>
                        $imageUrl,

                    'imageUrl' =>
                        $imageUrl,

                    'image_url' =>
                        $imageUrl,

                    'imageName' =>
                        $foto->nama_file
                        ?: $labelFoto,

                    'image_name' =>
                        $foto->nama_file
                        ?: $labelFoto,

                    'type' =>
                        'image',
                ];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | PASTIKAN GEOTAGGING ADA
        |--------------------------------------------------------------------------
        */

        $geotag =
            $geotagFromQuestionnaire
            ?: data_get(
                $item,
                'geotangging'
            );


        if (!$geotag) {
            $geotag = data_get($item, 'geotagging');
        }


        if (!$geotag) {
            $geotag = data_get($item, 'coordinates');
        }


        if ($geotag) {
            $coordinates = $this->extractCoordinates($geotag);
            if ($coordinates) {
                $geotag = $coordinates['latitude'] . ', ' . $coordinates['longitude'];
                $item['geotagging'] = $geotag;
                $item['geotangging'] = $geotag;
                $item['coordinates'] = $geotag;
                $item['latitude'] = $coordinates['latitude'];
                $item['longitude'] = $coordinates['longitude'];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | JIKA ADA GEOTAGGING TETAPI BELUM ADA DI KUISIONER
        |--------------------------------------------------------------------------
        */

        if (
            $geotag
        ) {

            $adaGeotagging =
                false;


            foreach (
                $kuisioner
                as $part
            ) {

                foreach (
                    data_get(
                        $part,
                        'questions',
                        []
                    )
                    as $question
                ) {

                    $text =
                        strtolower(
                            (string) (
                                data_get(
                                    $question,
                                    'question',
                                    ''
                                )
                                ??
                                data_get(
                                    $question,
                                    'text',
                                    ''
                                )
                            )
                        );


                    if (
                        str_contains(
                            $text,
                            'geotagging'
                        )
                        ||
                        str_contains(
                            $text,
                            'geotag'
                        )
                        ||
                        str_contains(
                            $text,
                            'titik lokasi'
                        )
                    ) {

                        $adaGeotagging =
                            true;

                        break 2;
                    }
                }
            }


            /*
            |--------------------------------------------------------------------------
            | TAMBAHKAN JIKA BELUM ADA
            |--------------------------------------------------------------------------
            */

            if (
                !$adaGeotagging
            ) {

                $kuisioner[0]['questions'][] = [

                    'number' =>
                        0,

                    'question' =>
                        'Titik lokasi (geotagging) tempat tinggal saat ini',

                    'text' =>
                        'Titik lokasi (geotagging) tempat tinggal saat ini',

                    'answer' =>
                        $geotag,

                    'type' =>
                        'map',
                ];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | NOMOR PERTANYAAN
        |--------------------------------------------------------------------------
        |
        | INI BAGIAN PENTING UNTUK MENGHILANGKAN "PERTANYAAN 0".
        |
        | Nomor tidak lagi mengambil nilai number/nomor/no/urutan
        | dari database.
        |
        | Setiap Part dimulai kembali dari 1.
        |
        */

        foreach (
            $kuisioner
            as $partIndex => $part
        ) {

            $questions =
                collect(
                    data_get(
                        $part,
                        'questions',
                        []
                    )
                )
                ->values();


            foreach (
                $questions
                as $questionIndex => $question
            ) {

                if (
                    !is_array(
                        $question
                    )
                ) {

                    $question = [
                        'question' =>
                            (string) $question,

                        'answer' =>
                            '',
                    ];
                }


                /*
                |--------------------------------------------------------------------------
                | NOMOR BARU
                |--------------------------------------------------------------------------
                */

                $question['number'] =
                    $questionIndex + 1;


                $question['nomor'] =
                    $questionIndex + 1;


                $question['no'] =
                    $questionIndex + 1;


                $question['urutan'] =
                    $questionIndex + 1;


                $questions[
                    $questionIndex
                ] =
                    $question;
            }


            $kuisioner[
                $partIndex
            ]['questions'] =
                $questions
                    ->values()
                    ->all();
        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN KUISIONER
        |--------------------------------------------------------------------------
        */

        $item['kuisioner'] =
            $kuisioner;


        /*
        |--------------------------------------------------------------------------
        | DATA FOTO RUMAH TAMBAHAN
        |--------------------------------------------------------------------------
        |
        | Disediakan juga dalam key terpisah agar Blade Monitoring
        | bisa menggunakannya jika dibutuhkan.
        |
        */

        $item['foto_rumah'] =
            [];


        if (
            $kodePeriode
        ) {

            $fotoRumah =
                KeluargaFotoRumah::where(
                    'keluarga_periode_kode',
                    $kodePeriode
                )
                ->orderBy('id')
                ->get();


            foreach (
                $fotoRumah
                as $foto
            ) {

                $path =
                    trim(
                        str_replace(
                            '\\',
                            '/',
                            (string) (
                                $foto->path_file
                                ?? ''
                            )
                        )
                    );


                if (
                    !$path
                ) {

                    continue;
                }


                $item['foto_rumah'][] = [

                    'jenis_foto' =>
                        $foto->jenis_foto,

                    'nama_file' =>
                        $foto->nama_file,

                    'path_file' =>
                        $path,

                    'url' =>
                        $this->makeStorageUrl(
                            $path
                        ),
                ];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS TERAKHIR
        |--------------------------------------------------------------------------
        */

        $item['status_saat_ini'] =
            $item['status_label'];


        return $item;
    }


    /*
    |--------------------------------------------------------------------------
    | BUAT URL STORAGE
    |--------------------------------------------------------------------------
    */

    protected function makeStorageUrl($path)
    {
        if (!$path) {
            return '';
        }

        $path = trim(str_replace('\\', '/', (string) $path));
        if ($path === '') {
            return '';
        }

        if (preg_match('#^(https?:)?//#i', $path)) {
            return $path;
        }

        if (str_starts_with(strtolower($path), 'data:image/')) {
            return $path;
        }

        $path = ltrim($path, '/');
        $lowerPath = strtolower($path);
        $position = strpos($lowerPath, 'kuisioner/');

        if ($position !== false) {
            $path = substr($path, $position);
        } else {
            $path = preg_replace('#^(public/storage/|storage/app/public/|storage/app/|public/|storage/)#i', '', $path);
        }

        $path = ltrim($path, '/');
        if ($path === '') {
            return '';
        }

        try {
            $publicDisk = Storage::disk('public');

            if (!$publicDisk->exists($path) && Storage::disk('local')->exists($path)) {
                $contents = Storage::disk('local')->get($path);
                $publicDisk->put($path, $contents);
            }

            return $publicDisk->url($path);
        } catch (\Throwable $e) {
            return asset('storage/' . $path);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL KOORDINAT
    |--------------------------------------------------------------------------
    */

    protected function extractCoordinates($value)
    {
        if (is_object($value)) {
            $value = method_exists($value, 'toArray') ? $value->toArray() : (array) $value;
        }

        if (is_array($value)) {
            $latitude = data_get($value, 'latitude') ?? data_get($value, 'lat');
            $longitude = data_get($value, 'longitude') ?? data_get($value, 'lng') ?? data_get($value, 'lon');

            if (is_numeric($latitude) && is_numeric($longitude)) {
                return ['latitude' => (float) $latitude, 'longitude' => (float) $longitude];
            }

            foreach ([data_get($value, 'coordinates'), data_get($value, 'location'), data_get($value, 'value'), data_get($value, 'answer')] as $nested) {
                if ($nested === null) continue;
                $result = $this->extractCoordinates($nested);
                if ($result) return $result;
            }
        }

        $text = trim((string) $value);
        if ($text === '') return null;

        if ((str_starts_with($text, '{') && str_ends_with($text, '}')) || (str_starts_with($text, '[') && str_ends_with($text, ']'))) {
            $decoded = json_decode($text, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $result = $this->extractCoordinates($decoded);
                if ($result) return $result;
            }
        }

        if (preg_match('/(?:lat(?:itude)?)[\s:=]+(-?\d+(?:\.\d+)?).*?(?:lng|lon|longitude)[\s:=]+(-?\d+(?:\.\d+)?)/i', $text, $m)) {
            return ['latitude' => (float) $m[1], 'longitude' => (float) $m[2]];
        }

        if (preg_match('/@(-?\d+(?:\.\d+)?),\s*(-?\d+(?:\.\d+)?)/', $text, $m)) {
            return ['latitude' => (float) $m[1], 'longitude' => (float) $m[2]];
        }

        if (preg_match('/(?:[?&]|\b)(?:lat|latitude)=(-?\d+(?:\.\d+)?).*?(?:[?&]|\b)(?:lng|lon|longitude)=(-?\d+(?:\.\d+)?)/i', $text, $m)) {
            return ['latitude' => (float) $m[1], 'longitude' => (float) $m[2]];
        }

        if (preg_match('/(-?\d+(?:\.\d+)?)\s*[,;]\s*(-?\d+(?:\.\d+)?)/', $text, $m)) {
            $lat = (float) $m[1];
            $lng = (float) $m[2];
            if ($lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180) {
                return ['latitude' => $lat, 'longitude' => $lng];
            }
        }

        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | INDEX MONITORING
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request
    ) {

        /*
        |--------------------------------------------------------------------------
        | SEMUA DATA KUISIONER
        |--------------------------------------------------------------------------
        |
        | Tetap mengambil data dari Verifikasi.
        |
        */

        $allQuestionnaireData =
            collect(
                parent::getData()
            )
            ->map(function ($item) {

                $status =
                    strtolower(
                        trim(
                            (string) data_get(
                                $item,
                                'status',
                                ''
                            )
                        )
                    );


                /*
                |--------------------------------------------------------------------------
                | APPROVED
                |--------------------------------------------------------------------------
                */

                if (
                    in_array(
                        $status,
                        [
                            'approved',
                            'disetujui',
                        ],
                        true
                    )
                ) {

                    $item['status'] =
                        'approved';

                    $item['status_label'] =
                        'Disetujui';
                }


                /*
                |--------------------------------------------------------------------------
                | REJECTED
                |--------------------------------------------------------------------------
                */

                elseif (
                    in_array(
                        $status,
                        [
                            'rejected',
                            'reject',
                            'ditolak',
                        ],
                        true
                    )
                ) {

                    $item['status'] =
                        'rejected';

                    $item['status_label'] =
                        'Ditolak';
                }


                /*
                |--------------------------------------------------------------------------
                | PENDING
                |--------------------------------------------------------------------------
                */

                elseif (
                    in_array(
                        $status,
                        [
                            'pending',
                            'menunggu',
                            'menunggu_verifikasi',
                            'menunggu verifikasi',
                        ],
                        true
                    )
                ) {

                    $item['status'] =
                        'pending';

                    $item['status_label'] =
                        'Menunggu Verifikasi';
                }


                /*
                |--------------------------------------------------------------------------
                | DRAFT
                |--------------------------------------------------------------------------
                */

                else {

                    $item['status'] =
                        'draft';

                    $item['status_label'] =
                        'Draft';
                }


                return $item;
            });


        /*
        |--------------------------------------------------------------------------
        | UNIQUE KEY
        |--------------------------------------------------------------------------
        */

        $uniqueKey =
            function ($item) {

                return
                    data_get(
                        $item,
                        'keluarga_periode_kode'
                    )
                    ?:
                    data_get(
                        $item,
                        'no_kk'
                    )
                    ?:
                    data_get(
                        $item,
                        'id'
                    );
            };


        /*
        |--------------------------------------------------------------------------
        | HILANGKAN DUPLIKAT
        |--------------------------------------------------------------------------
        */

        $allUnique =
            $allQuestionnaireData
                ->unique(
                    $uniqueKey
                )
                ->values();


        /*
        |--------------------------------------------------------------------------
        | APPROVED + REJECTED
        |--------------------------------------------------------------------------
        */

        $approvedRejected =
            $allUnique
                ->filter(function ($item) {

                    return in_array(
                        data_get(
                            $item,
                            'status'
                        ),
                        [
                            'approved',
                            'rejected',
                        ],
                        true
                    );
                })
                ->values();


        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $totalResponden =
            $approvedRejected->count();


        $disetujui =
            $approvedRejected
                ->where(
                    'status',
                    'approved'
                )
                ->count();


        $ditolak =
            $approvedRejected
                ->where(
                    'status',
                    'rejected'
                )
                ->count();


        $dataSudahDidata =
            $approvedRejected->count();


        $menungguVerifikasi =
            $allUnique
                ->where(
                    'status',
                    'pending'
                )
                ->count();


        $belumDidata =
            $allUnique
                ->filter(function ($item) {

                    return !in_array(
                        data_get(
                            $item,
                            'status'
                        ),
                        [
                            'approved',
                            'rejected',
                        ],
                        true
                    );
                })
                ->count();


        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */

        $statusFilter =
            strtolower(
                trim(
                    (string) $request->query(
                        'status',
                        'all'
                    )
                )
            );


        if (
            !in_array(
                $statusFilter,
                [
                    'all',
                    'approved',
                    'rejected',
                ],
                true
            )
        ) {

            $statusFilter =
                'all';
        }


        /*
        |--------------------------------------------------------------------------
        | DATA UNTUK TABEL
        |--------------------------------------------------------------------------
        |
        | Gunakan getData() agar data:
        | - foto
        | - geotagging
        | - nomor pertanyaan
        | - status
        | sudah lengkap.
        |
        */

        $filteredData =
            $this->getData()
                ->unique(
                    $uniqueKey
                )
                ->values();


        /*
        |--------------------------------------------------------------------------
        | FILTER APPROVED
        |--------------------------------------------------------------------------
        */

        if (
            $statusFilter ===
            'approved'
        ) {

            $filteredData =
                $filteredData
                    ->where(
                        'status',
                        'approved'
                    )
                    ->values();
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER REJECTED
        |--------------------------------------------------------------------------
        */

        elseif (
            $statusFilter ===
            'rejected'
        ) {

            $filteredData =
                $filteredData
                    ->where(
                        'status',
                        'rejected'
                    )
                    ->values();
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $perPage =
            5;


        $currentPage =
            LengthAwarePaginator::resolveCurrentPage();


        $filteredData =
            $filteredData->values();


        $currentItems =
            $filteredData
                ->slice(
                    (
                        $currentPage - 1
                    ) * $perPage,
                    $perPage
                )
                ->values();


        $data =
            new LengthAwarePaginator(
                $currentItems,
                $filteredData->count(),
                $perPage,
                $currentPage,
                [
                    'path' =>
                        $request->url(),

                    'query' =>
                        $request->query(),
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.monitoring.index',
            compact(
                'data',
                'totalResponden',
                'dataSudahDidata',
                'belumDidata',
                'menungguVerifikasi',
                'disetujui',
                'ditolak',
                'statusFilter'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DETAIL MONITORING
    |--------------------------------------------------------------------------
    |
    | Detail bersifat read-only.
    | Tidak ada aksi Setujui/Tolak di Monitoring.
    |
    */

    public function detail(
        $id
    ) {

        $data =
            $this->getData();


        $item =
            $data->firstWhere(
                'id',
                (int) $id
            );


        if (
            !$item
        ) {

            abort(404);
        }


        return view(
            'admin.monitoring.show',
            compact(
                'item'
            )
        );
    }
}