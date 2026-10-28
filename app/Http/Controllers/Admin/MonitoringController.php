<?php

namespace App\Http\Controllers\Admin;

use App\Models\Keluarga;
use App\Models\KeluargaFotoRumah;
use App\Models\KeluargaPart1;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
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
    |
    */
    protected function getData(?Builder $query = null)
    {
        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA DARI VERIFIKASI
        |--------------------------------------------------------------------------
        */

        $allData = collect(
            parent::getData($query)
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
                } elseif (
                    in_array(
                        $status,
                        [
                            'not_processed',
                            'belum',
                            'belum_didata',
                            'belum didata',
                            'belum diproses',
                        ],
                        true
                    )
                ) {

                    $item['status'] =
                        'not_processed';

                    $item['status_label'] =
                        'Belum Didata';
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
            $item instanceof Arrayable
        ) {

            $item =
                $item->toArray();

        }

        if (
            ! is_array($item)
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

        } elseif (
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

        } elseif (
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

        } elseif (
            in_array(
                $status,
                [
                    'not_processed',
                    'belum',
                    'belum_didata',
                    'belum didata',
                    'belum diproses',
                ],
                true
            )
        ) {

            $status =
                'not_processed';

        } else {

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

                'approved' => 'Disetujui',

                'rejected' => 'Ditolak',

                'pending' => 'Menunggu Verifikasi',

                'not_processed' => 'Belum Didata',

                default => 'Draft',
            };

        // Alasan penolakan diteruskan ke card Detail Monitoring.
        // Data ini berasal dari VerifikasiController / database.
        $alasanPenolakan = data_get($item, 'alasan_penolakan')
            ?? data_get($item, 'alasanPenolakan')
            ?? null;

        $item['alasan_penolakan'] = is_scalar($alasanPenolakan)
            ? (trim((string) $alasanPenolakan) ?: null)
            : null;

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

                'approved' => 'status-approved',

                'rejected' => 'status-rejected',

                'pending' => 'status-pending',

                'not_processed' => 'status-not-processed',

                default => 'status-draft',
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
                        ! is_array($part)
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
                                    ! is_array($question)
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
                                            .', '
                                            .$coordinates['longitude'];
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
                                        ! $imageUrl
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
        | LENGKAPI DATA PART 1 DARI RESPONDEN DAN TABEL KUISIONER
        |--------------------------------------------------------------------------
        | RT/RW berasal dari data responden atau jawaban Part 1.
        | Alasan alamat tidak sesuai dan koordinat diambil dari record Part 1.
        | Pencarian memakai kode periode/KK agar tidak mengambil responden lain.
        */

        $kodePeriodeSumber = data_get($item, 'keluarga_periode_kode')
            ?? data_get($item, 'kode_periode')
            ?? data_get($item, 'kode');

        $noKkSumber = data_get($item, 'no_kk')
            ?? data_get($item, 'nomor_induk_keluarga');

        $part1Sumber = null;

        $part1IdSumber = data_get($item, 'id');

        if (filled($part1IdSumber)) {
            $part1Sumber = KeluargaPart1::find($part1IdSumber);
        }

        if (! $part1Sumber && filled($kodePeriodeSumber)) {
            $part1Sumber = KeluargaPart1::where(
                'keluarga_periode_kode',
                $kodePeriodeSumber
            )->latest('id')->first();
        }

        if (! $part1Sumber && blank($kodePeriodeSumber) && filled($noKkSumber)) {
            $matchingPart1Ids = KeluargaPart1::idsMatchingIdentifierValues(
                'no_kk',
                [(string) $noKkSumber]
            );
            $part1Sumber = $matchingPart1Ids !== []
                ? KeluargaPart1::find(max($matchingPart1Ids))
                : null;
        }

        $keluargaSumber = null;
        $kodeKeluargaSumber = $part1Sumber->keluarga_periode_kode
            ?? $kodePeriodeSumber;

        if (filled($kodeKeluargaSumber)) {
            $keluargaSumber = Keluarga::where('kode', $kodeKeluargaSumber)->first();
        }

        if (! $keluargaSumber && blank($kodeKeluargaSumber) && filled($noKkSumber)) {
            $keluargaSumber = Keluarga::query()
                ->get()
                ->first(
                    static fn (Keluarga $keluarga): bool => $keluarga->no_kk === $noKkSumber
                );
        }

        $firstFilledMonitoring = static function (...$values) {
            foreach ($values as $value) {
                if (is_string($value)) {
                    $value = trim($value);
                }

                if ($value !== null && $value !== '' && $value !== '-') {
                    return $value;
                }
            }

            return null;
        };

        $rtRwSumber = $firstFilledMonitoring(
            $part1Sumber->rt_rw ?? null,
            $keluargaSumber->rt_rw ?? null
        );

        if (! $rtRwSumber) {
            $rt = $firstFilledMonitoring($keluargaSumber->rt ?? null);
            $rw = $firstFilledMonitoring($keluargaSumber->rw ?? null);

            if ($rt || $rw) {
                $rtRwSumber = 'RT '.($rt ?? '-').' / RW '.($rw ?? '-');
            }
        }

        $alamatSesuaiRaw = $part1Sumber->is_alamat_sesuai ?? null;
        $alamatSesuaiText = null;

        if ($alamatSesuaiRaw !== null && $alamatSesuaiRaw !== '') {
            $alamatSesuaiText = in_array(
                strtolower(trim((string) $alamatSesuaiRaw)),
                ['1', 'true', 'ya', 'yes', 'sesuai'],
                true
            ) ? 'Ya' : 'Tidak';
        }

        $alasanAlamat = $firstFilledMonitoring(
            $part1Sumber->alasan_tidak_sesuai ?? null
        );

        $geotagSumber = $firstFilledMonitoring(
            $part1Sumber->geotangging ?? null,
            $keluargaSumber->geotagging ?? null,
            $keluargaSumber->geotangging ?? null,
            data_get($item, 'geotagging'),
            data_get($item, 'geotangging'),
            data_get($item, 'coordinates')
        );

        // Pastikan struktur Part 1 tersedia.
        $part1IndexSumber = collect($kuisioner)->search(function ($part) {
            $number = (int) (data_get($part, 'part')
                ?? data_get($part, 'number')
                ?? data_get($part, 'part_number')
                ?? 0);

            $title = strtolower((string) (data_get($part, 'title') ?? data_get($part, 'name') ?? ''));

            return $number === 1 || str_contains($title, 'part 1') || str_contains($title, 'data keluarga');
        });

        if ($part1IndexSumber === false) {
            array_unshift($kuisioner, [
                'part' => 1,
                'number' => 1,
                'title' => 'Data Keluarga',
                'questions' => [],
            ]);
            $part1IndexSumber = 0;
        }

        if (! isset($kuisioner[$part1IndexSumber]['questions']) || ! is_array($kuisioner[$part1IndexSumber]['questions'])) {
            $kuisioner[$part1IndexSumber]['questions'] = [];
        }

        $upsertPart1Question = function (array &$questions, array $needles, string $label, $answer, ?string $type = null) {
            if ($answer === null || $answer === '') {
                return;
            }

            $foundIndex = null;

            foreach ($questions as $index => $question) {
                if (! is_array($question)) {
                    continue;
                }

                $text = strtolower(trim((string) (
                    data_get($question, 'question')
                    ?? data_get($question, 'pertanyaan')
                    ?? data_get($question, 'text')
                    ?? data_get($question, 'label')
                    ?? ''
                )));

                foreach ($needles as $needle) {
                    if ($text !== '' && str_contains($text, $needle)) {
                        $foundIndex = $index;
                        break 2;
                    }
                }
            }

            if ($foundIndex !== null) {
                $existingAnswer = data_get($questions[$foundIndex], 'answer')
                    ?? data_get($questions[$foundIndex], 'jawaban')
                    ?? data_get($questions[$foundIndex], 'value');

                if ($existingAnswer === null || trim((string) $existingAnswer) === '' || trim((string) $existingAnswer) === '-') {
                    $questions[$foundIndex]['answer'] = $answer;
                }

                if ($type) {
                    $questions[$foundIndex]['type'] = $type;
                }

                return;
            }

            $question = [
                'question' => $label,
                'text' => $label,
                'answer' => $answer,
            ];

            if ($type) {
                $question['type'] = $type;
            }

            $questions[] = $question;
        };

        $questionsPart1 = &$kuisioner[$part1IndexSumber]['questions'];

        $upsertPart1Question(
            $questionsPart1,
            ['rt/rw', 'rt / rw', 'rt rw', 'rukun tetangga', 'rukun warga'],
            'RT / RW *',
            $rtRwSumber
        );

        $upsertPart1Question(
            $questionsPart1,
            ['alamat sesuai', 'kesesuaian alamat', 'tempat tinggal saat ini sesuai', 'sesuai dengan kk'],
            'Apakah alamat tempat tinggal saat ini sesuai dengan KK? *',
            $alamatSesuaiText
        );

        if (strtolower((string) $alamatSesuaiText) === 'tidak' && $alasanAlamat) {
            foreach ($questionsPart1 as &$question) {
                if (! is_array($question)) {
                    continue;
                }

                $questionText = strtolower(trim((string) (
                    data_get($question, 'question')
                    ?? data_get($question, 'pertanyaan')
                    ?? data_get($question, 'text')
                    ?? data_get($question, 'label')
                    ?? ''
                )));

                if (
                    str_contains($questionText, 'tempat tinggal saat ini sesuai')
                    || str_contains($questionText, 'sesuai dengan kk')
                    || str_contains($questionText, 'kesesuaian alamat')
                ) {
                    $question['reason'] = $alasanAlamat;
                    break;
                }
            }

            unset($question);
        }

        if ($geotagSumber) {
            $coordinatesSumber = $this->extractCoordinates($geotagSumber);
            if ($coordinatesSumber) {
                $geotagSumber = $coordinatesSumber['latitude'].', '.$coordinatesSumber['longitude'];
            }

            $upsertPart1Question(
                $questionsPart1,
                ['geotagging', 'geotag', 'titik lokasi', 'lokasi tempat tinggal', 'koordinat'],
                'Titik lokasi (geotagging) tempat tinggal saat ini',
                $geotagSumber,
                'map'
            );

            // Jadikan koordinat tersedia juga pada level item yang dibaca oleh Blade.
            $item['geotagging'] = $geotagSumber;
            $item['geotangging'] = $geotagSumber;
            $item['coordinates'] = $geotagSumber;
        }

        unset($questionsPart1);

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

                if (! is_array($question)) {
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

                if (! $isGeotagQuestion) {
                    continue;
                }

                $answer =
                    data_get($question, 'answer')
                    ?? data_get($question, 'value')
                    ?? data_get($question, 'jawaban')
                    ?? data_get($question, 'coordinates')
                    ?? null;

                $coordinates = $this->extractCoordinates($answer);

                if (! $coordinates) {
                    $coordinates = $this->extractCoordinates([
                        'latitude' => data_get($question, 'latitude') ?? data_get($question, 'lat'),
                        'longitude' => data_get($question, 'longitude') ?? data_get($question, 'lng') ?? data_get($question, 'lon'),
                    ]);
                }

                if ($coordinates) {
                    $geotagFromQuestionnaire = $coordinates['latitude'].', '.$coordinates['longitude'];
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
            | CARI PART 6
            |--------------------------------------------------------------------------
            */

            $part6Index =
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
                    ) === 6;

                });

            /*
            |--------------------------------------------------------------------------
            | JIKA PART 6 BELUM ADA
            |--------------------------------------------------------------------------
            */

            if (
                $part6Index === false
            ) {

                $kuisioner[] = [

                    'part' => 6,

                    'title' => 'Foto Rumah',

                    'questions' => [],
                ];

                $part6Index =
                    count(
                        $kuisioner
                    ) - 1;
            }

            /*
            |--------------------------------------------------------------------------
            | PASTIKAN QUESTIONS PART 6 ADA
            |--------------------------------------------------------------------------
            */

            if (
                ! isset(
                    $kuisioner[
                        $part6Index
                    ]['questions']
                )
            ) {

                $kuisioner[
                    $part6Index
                ]['questions'] = [];
            }

            /*
            |--------------------------------------------------------------------------
            | MASUKKAN FOTO RUMAH
            |--------------------------------------------------------------------------
            */

            foreach (
                $fotoRumah as $foto
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
                    ! $imageUrl
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

                        'tampak_depan' => 'Foto Tampak Depan Rumah',

                        'tampak_belakang' => 'Foto Tampak Belakang Rumah',

                        'ruang_tamu' => 'Foto Ruang Tamu',

                        'kamar_mandi' => 'Foto Kamar Mandi',

                        'dapur' => 'Foto Dapur',

                        'kamar_tidur' => 'Foto Kamar Tidur',

                        'ruang_keluarga' => 'Foto Ruang Keluarga',

                        default => 'Foto Rumah '.
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
                            $part6Index
                        ]['questions']
                    )
                        ->contains(
                            function ($question) use (
                                $imageUrl,
                                $path,
                                $jenisFoto
                            ) {

                                if (
                                    ! is_array(
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
                | TAMBAHKAN FOTO KE PART 6
                |--------------------------------------------------------------------------
                */

                $kuisioner[
                    $part6Index
                ]['questions'][] = [

                    'number' => 0,

                    'question' => $labelFoto,

                    'text' => $labelFoto,

                    'answer' => $imageUrl,

                    'imageUrl' => $imageUrl,

                    'image_url' => $imageUrl,

                    'imageName' => $foto->nama_file
                        ?: $labelFoto,

                    'image_name' => $foto->nama_file
                        ?: $labelFoto,

                    'type' => 'image',
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

        if (! $geotag) {
            $geotag = data_get($item, 'geotagging');
        }

        if (! $geotag) {
            $geotag = data_get($item, 'coordinates');
        }

        if ($geotag) {
            $coordinates = $this->extractCoordinates($geotag);
            if ($coordinates) {
                $geotag = $coordinates['latitude'].', '.$coordinates['longitude'];
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
                $kuisioner as $part
            ) {

                foreach (
                    data_get(
                        $part,
                        'questions',
                        []
                    ) as $question
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
                ! $adaGeotagging
            ) {

                $kuisioner[0]['questions'][] = [

                    'number' => 0,

                    'question' => 'Titik lokasi (geotagging) tempat tinggal saat ini',

                    'text' => 'Titik lokasi (geotagging) tempat tinggal saat ini',

                    'answer' => $geotag,

                    'type' => 'map',
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
            $kuisioner as $partIndex => $part
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
                $questions as $questionIndex => $question
            ) {

                if (
                    ! is_array(
                        $question
                    )
                ) {

                    $question = [
                        'question' => (string) $question,

                        'answer' => '',
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
                $fotoRumah as $foto
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
                    ! $path
                ) {

                    continue;
                }

                $item['foto_rumah'][] = [

                    'jenis_foto' => $foto->jenis_foto,

                    'nama_file' => $foto->nama_file,

                    'path_file' => $path,

                    'url' => $this->makeStorageUrl(
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
        if (! $path) {
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

            if (! $publicDisk->exists($path) && Storage::disk('local')->exists($path)) {
                $contents = Storage::disk('local')->get($path);
                $publicDisk->put($path, $contents);
            }

            return $publicDisk->url($path);
        } catch (\Throwable $e) {
            return asset('storage/'.$path);
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
                if ($nested === null) {
                    continue;
                }
                $result = $this->extractCoordinates($nested);
                if ($result) {
                    return $result;
                }
            }

            return null;
        }

        $text = trim((string) $value);
        if ($text === '') {
            return null;
        }

        if ((str_starts_with($text, '{') && str_ends_with($text, '}')) || (str_starts_with($text, '[') && str_ends_with($text, ']'))) {
            $decoded = json_decode($text, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $result = $this->extractCoordinates($decoded);
                if ($result) {
                    return $result;
                }
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
        $filters = $this->validateTableFilters(
            $request,
            ['not_processed', 'draft', 'pending', 'approved', 'rejected']
        );

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
                    } elseif (
                        in_array(
                            $status,
                            [
                                'not_processed',
                                'belum',
                                'belum_didata',
                                'belum didata',
                                'belum diproses',
                            ],
                            true
                        )
                    ) {

                        $item['status'] =
                            'not_processed';

                        $item['status_label'] =
                            'Belum Didata';
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
            $allUnique->count();

        $draftCount =
            $allUnique
                ->where(
                    'status',
                    'draft'
                )
                ->count();

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
                ->where(
                    'status',
                    'not_processed'
                )
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
            ! in_array(
                $statusFilter,
                [
                    'all',
                    'not_processed',
                    'draft',
                    'pending',
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
            $this->getData(
                $this->buildFilteredPart1Query(
                    $filters,
                    ['not_processed', 'draft', 'pending', 'approved', 'rejected']
                )
            )
                ->unique(
                    $uniqueKey
                )
                ->values();

        /*
        |--------------------------------------------------------------------------
        | URUTAN DATA BERDASARKAN STATUS
        |--------------------------------------------------------------------------
        | Urutan tabel Monitoring:
        | 1. Menunggu Verifikasi
        | 2. Draft
        | 3. Disetujui
        | 4. Ditolak
        | 5. Belum Didata (status tambahan, jika ada)
        |
        | Pengurutan dilakukan sebelum filter status dan pagination,
        | sehingga urutan tetap konsisten di semua halaman tabel.
        */

        $statusPriority = [
            'pending' => 1,
            'draft' => 2,
            'approved' => 3,
            'rejected' => 4,
            'not_processed' => 5,
        ];

        $filteredData = $filteredData
            ->sortBy(function ($item) use ($statusPriority) {
                $status = (string) data_get($item, 'status', '');

                return $statusPriority[$status] ?? 99;
            })
            ->values();

        if ($statusFilter !== 'all') {
            $filteredData =
                $filteredData
                    ->where(
                        'status',
                        $statusFilter
                    )
                    ->values();
        }

        $kecamatanList = $this->getKecamatanOptions();
        $kelurahanList = $this->getKelurahanOptions($filters['kecamatan']);

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
                    'path' => $request->url(),

                    'query' => $request->query(),
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
                'draftCount',
                'menungguVerifikasi',
                'disetujui',
                'ditolak',
                'statusFilter',
                'filters',
                'kecamatanList',
                'kelurahanList'
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
            ! $item
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
