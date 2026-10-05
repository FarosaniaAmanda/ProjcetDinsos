@extends('admin.kuisioner.layout')

@section('kuisioner-content')

<style>
    /* =========================================================
       PART 2 CONTENT
       ========================================================= */

    .part2-card {
        width: 100%;
        background: #FFFFFF;
        border: 1px solid #E2E4EF;
        border-radius: 17px;
        box-shadow: 0 5px 20px rgba(41, 45, 143, 0.05);
        overflow: hidden;
    }

    /* =========================================================
       HEADER
       ========================================================= */

    .part2-card-header {
        padding: 25px 28px;
        border-bottom: 1px solid #E2E4EF;
        background: #FFFFFF;
    }

    .part2-card-header h2 {
        margin: 0;
        color: #292D8F;
        font-size: 20px;
        font-weight: 800;
        line-height: 1.3;
    }

    .part2-card-header p {
        margin: 7px 0 0;
        color: #777D91;
        font-size: 13px;
        font-weight: 400;
        line-height: 1.6;
    }

    /* =========================================================
       FORM
       ========================================================= */

    .part2-form {
        padding: 28px;
    }

    /* =========================================================
       QUESTION
       ========================================================= */

    .part2-question {
        padding: 22px 0;
        border-bottom: 1px solid #E9EAF2;
    }

    .part2-question:first-child {
        padding-top: 0;
    }

    .part2-question:last-child {
        border-bottom: none;
    }

    /* =========================================================
       QUESTION TITLE
       ========================================================= */

    .part2-question-title {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 16px;
        color: #25283A;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.6;
    }

    .part2-number {
        flex: 0 0 30px;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        background: #F0F1FF;
        color: #292D8F;
        font-size: 11px;
        font-weight: 800;
        line-height: 1;
    }

    /* =========================================================
       RADIO
       ========================================================= */

    .part2-radio-group {
        display: flex;
        flex-wrap: wrap;
        gap: 12px 22px;
        padding-left: 42px;
    }

    .part2-radio {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #555A6D;
        font-size: 14px;
        font-weight: 500;
        line-height: 1.5;
        cursor: pointer;
    }

    .part2-radio input {
        width: 14px;
        height: 14px;
        margin: 0;
        accent-color: #292D8F;
        cursor: pointer;
    }

    /* =========================================================
       INPUT
       ========================================================= */

    .part2-input-wrap {
        padding-left: 42px;
    }

    .part2-input {
        width: 100%;
        height: 48px;
        padding: 0 14px;
        border: 1px solid #D9DCE8;
        border-radius: 9px;
        background: #FFFFFF;
        color: #25283A;
        font-family: inherit;
        font-size: 14px;
        outline: none;
        transition: 0.2s ease;
    }

    .part2-input:focus {
        border-color: #292D8F;
        box-shadow: 0 0 0 3px rgba(41, 45, 143, 0.08);
    }

    .part2-input::placeholder {
        color: #A2A6B5;
        font-size: 13px;
    }

    /* =========================================================
       CONDITIONAL
       ========================================================= */

    .part2-conditional {
        display: none;
        margin-top: 15px;
        margin-left: 42px;
        padding: 17px;
        border: 1px solid #E2E4EF;
        border-radius: 11px;
        background: #FAFAFD;
    }

    .part2-conditional.show {
        display: block;
    }

    .part2-conditional-title {
        margin-bottom: 12px;
        color: #4D5265;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.5;
    }

    /* =========================================================
       FOOTER
       ========================================================= */

    .part2-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 20px 28px;
        border-top: 1px solid #E2E4EF;
        background: #FBFBFD;
    }

    .part2-back,
    .part2-next {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 48px;
        border-radius: 9px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: 0.2s ease;
    }

    .part2-back {
        padding: 0 19px;
        border: 1px solid #D9DCE8;
        background: #FFFFFF;
        color: #656A7C;
    }

    .part2-back:hover {
        background: #F5F6FB;
        border-color: #C8CBF7;
        color: #292D8F;
    }

    .part2-next {
        padding: 0 21px;
        border: none;
        background: #292D8F;
        color: #FFFFFF;
        cursor: pointer;
    }

    .part2-next:hover {
        background: #25297F;
        transform: translateY(-1px);
    }

    /* =========================================================
       WARNING
       ========================================================= */

    .part2-warning {
        display: none;
        margin-bottom: 18px;
        padding: 13px 16px;
        border: 1px solid #F0D58A;
        border-radius: 10px;
        background: #FFF9E9;
        color: #80691D;
        font-size: 11px;
        line-height: 1.6;
    }

    /* =========================================================
       VALIDATION ERROR
       ========================================================= */

    .part2-error {
        margin: 0 28px 20px;
        padding: 12px 15px;
        border: 1px solid #F2C2C2;
        border-radius: 9px;
        background: #FFF5F5;
        color: #B42318;
        font-size: 13px;
    }

    .part2-error ul {
        margin: 6px 0 0 18px;
        padding: 0;
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 750px) {

        .part2-form {
            padding: 22px 20px;
        }

        .part2-question-title {
            font-size: 14px;
        }

        .part2-radio-group,
        .part2-input-wrap {
            padding-left: 0;
        }

        .part2-conditional {
            margin-left: 0;
        }

        .part2-radio {
            font-size: 13px;
        }

        .part2-input {
            font-size: 13px;
        }

        .part2-footer {
            padding: 18px 20px;
        }

        .part2-error {
            margin-left: 20px;
            margin-right: 20px;
        }
    }

    @media (max-width: 560px) {

        .part2-card {
            border-radius: 13px;
        }

        .part2-card-header {
            padding: 20px;
        }

        .part2-card-header h2 {
            font-size: 18px;
        }

        .part2-card-header p {
            font-size: 12px;
        }

        .part2-form {
            padding: 20px;
        }

        .part2-question-title {
            font-size: 13px;
            line-height: 1.55;
        }

        .part2-number {
            flex: 0 0 29px;
            width: 29px;
            height: 29px;
            font-size: 10px;
        }

        .part2-radio-group {
            flex-direction: column;
            gap: 10px;
        }

        .part2-radio {
            font-size: 13px;
        }

        .part2-input {
            height: 46px;
            font-size: 13px;
        }

        .part2-conditional-title {
            font-size: 12px;
        }

        .part2-footer {
            flex-direction: column;
        }

        .part2-back,
        .part2-next {
            width: 100%;
            min-height: 46px;
            font-size: 13px;
        }

        .part2-error {
            margin-left: 20px;
            margin-right: 20px;
            font-size: 12px;
        }
    }
</style>


{{-- =========================================================
     WARNING
     ========================================================= --}}

<div
    id="warningBox"
    class="part2-warning"
>
    Silakan lengkapi data Part 2 terlebih dahulu sebelum melanjutkan.
</div>


{{-- =========================================================
     PART 2 CARD
     ========================================================= --}}

<div class="part2-card">

    <form
        action="{{ route('kuisioner.part2.store') }}"
        method="POST"
    >

        @csrf


        {{-- =================================================
             HEADER
             ================================================= --}}

        <div class="part2-card-header">

            <h2>
                Kondisi Rumah
            </h2>

            <p>
                Silakan lengkapi data kondisi rumah keluarga dengan benar dan sesuai kondisi sebenarnya.
            </p>

        </div>


        {{-- =================================================
             VALIDATION ERROR
             ================================================= --}}

        @if ($errors->any())

            <div class="part2-error">

                <strong>
                    Data belum dapat disimpan.
                </strong>

                <ul>

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =================================================
             FORM
             ================================================= --}}

        <div class="part2-form">


            {{-- =================================================
                 16
                 ================================================= --}}

            <div class="part2-question">

                <div class="part2-question-title">

                    <div class="part2-number">
                        16
                    </div>

                    <div>
                        Jenis bangunan tempat tinggal yang ditempati
                    </div>

                </div>

                <div class="part2-radio-group">

                    @foreach([
                        'Rumah Tunggal',
                        'Apartemen',
                        'Rumah Susun',
                        'Rumah Deret',
                        'Lainnya'
                    ] as $option)

                        <label class="part2-radio">

                            <input
                                type="radio"
                                name="jenis_bangunan"
                                value="{{ $option }}"
                                {{ old('jenis_bangunan', $dataPart2->jenis_bangungan ?? '') == $option ? 'checked' : '' }}
                            >

                            {{ $option }}

                        </label>

                    @endforeach

                </div>

            </div>


            {{-- =================================================
                 17
                 ================================================= --}}

            <div class="part2-question">

                <div class="part2-question-title">

                    <div class="part2-number">
                        17
                    </div>

                    <div>
                        Apakah terdapat keluarga lain yang tinggal dalam satu bangunan rumah?
                    </div>

                </div>

                <div class="part2-radio-group">

                    <label class="part2-radio">

                        <input
                            type="radio"
                            name="keluarga_lain"
                            value="Ya"
                            onchange="toggleKeluargaLain(true)"
                            {{ old('keluarga_lain', isset($dataPart2) && $dataPart2->is_keluarga_lain == 1 ? 'Ya' : '') == 'Ya' ? 'checked' : '' }}
                        >

                        Ya

                    </label>

                    <label class="part2-radio">

                        <input
                            type="radio"
                            name="keluarga_lain"
                            value="Tidak"
                            onchange="toggleKeluargaLain(false)"
                            {{ old('keluarga_lain', isset($dataPart2) && $dataPart2->is_keluarga_lain == 0 ? 'Tidak' : '') == 'Tidak' ? 'checked' : '' }}
                        >

                        Tidak

                    </label>

                </div>


                <div
                    id="jumlahKeluargaLainBox"
                    class="part2-conditional {{ old('keluarga_lain', isset($dataPart2) && $dataPart2->is_keluarga_lain == 1 ? 'Ya' : '') == 'Ya' ? 'show' : '' }}"
                >

                    <div class="part2-conditional-title">
                        Jumlah keluarga lain yang tinggal dalam satu bangunan
                    </div>

                    <input
                        type="number"
                        name="jumlah_keluarga_lain"
                        class="part2-input"
                        min="1"
                        value="{{ old('jumlah_keluarga_lain', $dataPart2->jml_keluarga_lain ?? '') }}"
                        placeholder="Masukkan jumlah keluarga"
                    >

                </div>

            </div>


            {{-- =================================================
                 18
                 ================================================= --}}

            <div class="part2-question">

                <div class="part2-question-title">

                    <div class="part2-number">
                        18
                    </div>

                    <div>
                        Jumlah orang yang tinggal dalam rumah
                    </div>

                </div>

                <div class="part2-input-wrap">

                    <input
                        type="number"
                        name="jumlah_orang"
                        class="part2-input"
                        min="1"
                        value="{{ old('jumlah_orang', $dataPart2->total_penghuni ?? '') }}"
                        placeholder="Masukkan jumlah orang"
                    >

                </div>

            </div>


            {{-- =================================================
                 19
                 ================================================= --}}

            <div class="part2-question">

                <div class="part2-question-title">

                    <div class="part2-number">
                        19
                    </div>

                    <div>
                        Status kepemilikan rumah
                    </div>

                </div>


                <div class="part2-radio-group">

                    @foreach([
                        'Milik Sendiri',
                        'Kontrak/Sewa',
                        'Bebas Sewa',
                        'Dinas',
                        'Lainnya'
                    ] as $option)

                        <label class="part2-radio">

                            <input
                                type="radio"
                                name="status_kepemilikan"
                                value="{{ $option }}"
                                onchange="toggleKepemilikan('{{ $option }}')"
                                {{ old('status_kepemilikan', $dataPart2->kepemilikan_bangunan ?? '') == $option ? 'checked' : '' }}
                            >

                            {{ $option }}

                        </label>

                    @endforeach

                </div>


                {{-- MILIK SENDIRI --}}

                <div
                    id="buktiMilikSendiri"
                    class="part2-conditional {{ old('status_kepemilikan', $dataPart2->kepemilikan_bangunan ?? '') == 'Milik Sendiri' ? 'show' : '' }}"
                >

                    <div class="part2-conditional-title">
                        Bukti kepemilikan rumah
                    </div>

                    <div
                        class="part2-radio-group"
                        style="padding-left:0;"
                    >

                        @foreach([
                            'SHM',
                            'Sertifikat selain SHM',
                            'Surat bukti lainnya',
                            'Tidak punya'
                        ] as $option)

                            <label class="part2-radio">

                                <input
                                    type="radio"
                                    name="bukti_kepemilikan"
                                    value="{{ $option }}"
                                    {{ old('bukti_kepemilikan', $dataPart2->bukti_kepemilikan ?? '') == $option ? 'checked' : '' }}
                                >

                                @if($option === 'Sertifikat selain SHM')
                                    Sertifikat selain SHM (SHGB, SHSRS)
                                @elseif($option === 'Surat bukti lainnya')
                                    Surat bukti lainnya (Girik, Letter C, dll)
                                @else
                                    {{ $option }}
                                @endif

                            </label>

                        @endforeach

                    </div>

                </div>


                {{-- KONTRAK / SEWA --}}

                <div
                    id="kontrakBox"
                    class="part2-conditional {{ old('status_kepemilikan', $dataPart2->kepemilikan_bangunan ?? '') == 'Kontrak/Sewa' ? 'show' : '' }}"
                >

                    <div class="part2-conditional-title">
                        Besaran sewa rumah per bulan
                    </div>

                    <input
                        type="number"
                        name="sewa_bulanan"
                        class="part2-input"
                        min="50000"
                        value="{{ old('sewa_bulanan', $dataPart2->kepemilikan_bangunan ?? '') == 'Kontrak/Sewa' ? ($dataPart2->harga_sewa_kontrak ?? '') : '' }}"
                        placeholder="Minimal Rp50.000"
                    >

                </div>


                {{-- BEBAS SEWA --}}

                <div
                    id="bebasSewaBox"
                    class="part2-conditional {{ old('status_kepemilikan', $dataPart2->kepemilikan_bangunan ?? '') == 'Bebas Sewa' ? 'show' : '' }}"
                >

                    <div class="part2-conditional-title">
                        Perkiraan nilai sewa rumah per bulan
                    </div>

                    <input
                        type="number"
                        name="perkiraan_sewa"
                        class="part2-input"
                        min="50000"
                        value="{{ old('perkiraan_sewa', $dataPart2->kepemilikan_bangunan ?? '') == 'Bebas Sewa' ? ($dataPart2->harga_sewa_kontrak ?? '') : '' }}"
                        placeholder="Minimal Rp50.000"
                    >

                </div>


                {{-- LAINNYA --}}

                <div
                    id="kepemilikanLainnyaBox"
                    class="part2-conditional {{ old('status_kepemilikan', $dataPart2->kepemilikan_bangunan ?? '') == 'Lainnya' ? 'show' : '' }}"
                >

                    <div class="part2-conditional-title">
                        Status kepemilikan lainnya
                    </div>

                    <input
                        type="text"
                        name="status_kepemilikan_lainnya"
                        class="part2-input"
                        value="{{ old('status_kepemilikan_lainnya') }}"
                        placeholder="Masukkan status kepemilikan"
                    >

                </div>

            </div>


            {{-- =================================================
                 20
                 ================================================= --}}

            <div class="part2-question">

                <div class="part2-question-title">

                    <div class="part2-number">
                        20
                    </div>

                    <div>
                        Luas lantai rumah yang ditempati (m²)
                    </div>

                </div>

                <div class="part2-input-wrap">

                    <input
                        type="number"
                        name="luas_lantai"
                        class="part2-input"
                        min="1"
                        step="0.01"
                        value="{{ old('luas_lantai', $dataPart2->luas_lantai ?? '') }}"
                        placeholder="Masukkan luas lantai dalam m²"
                    >

                </div>

            </div>


            {{-- =================================================
                 21
                 ================================================= --}}

            <div class="part2-question">

                <div class="part2-question-title">

                    <div class="part2-number">
                        21
                    </div>

                    <div>
                        Jenis lantai terluas yang digunakan
                    </div>

                </div>

                <div class="part2-radio-group">

                    @foreach([
                        'Marmer/granit',
                        'Keramik',
                        'Parket/vili/karpet',
                        'Ubin/tegel/teraso',
                        'Kayu/papan',
                        'Lainnya'
                    ] as $option)

                        <label class="part2-radio">

                            <input
                                type="radio"
                                name="jenis_lantai"
                                value="{{ $option }}"
                                {{ old('jenis_lantai', $dataPart2->jenis_lantai ?? '') == $option ? 'checked' : '' }}
                            >

                            {{ $option }}

                        </label>

                    @endforeach

                </div>

            </div>


            {{-- =================================================
                 22
                 ================================================= --}}

            <div class="part2-question">

                <div class="part2-question-title">

                    <div class="part2-number">
                        22
                    </div>

                    <div>
                        Kondisi lantai rumah
                    </div>

                </div>

                <div class="part2-radio-group">

                    @foreach([
                        'Baik',
                        'Rusak Ringan',
                        'Rusak Sedang',
                        'Rusak Berat'
                    ] as $option)

                        <label class="part2-radio">

                            <input
                                type="radio"
                                name="kondisi_lantai"
                                value="{{ $option }}"
                                {{ old('kondisi_lantai', $dataPart2->kondisi_lantai ?? '') == $option ? 'checked' : '' }}
                            >

                            {{ $option }}

                        </label>

                    @endforeach

                </div>

            </div>


            {{-- =================================================
                 23
                 ================================================= --}}

            <div class="part2-question">

                <div class="part2-question-title">

                    <div class="part2-number">
                        23
                    </div>

                    <div>
                        Jenis dinding terluas yang digunakan
                    </div>

                </div>

                <div class="part2-radio-group">

                    @foreach([
                        'Tembok',
                        'Plesteran anyaman bambu/kawat',
                        'Kayu/papan/Gypsum/GRC/Calciboard',
                        'Anyaman Bambu',
                        'Batang Kayu'
                    ] as $option)

                        <label class="part2-radio">

                            <input
                                type="radio"
                                name="jenis_dinding"
                                value="{{ $option }}"
                                {{ old('jenis_dinding', $dataPart2->jenis_dinding ?? '') == $option ? 'checked' : '' }}
                            >

                            {{ $option }}

                        </label>

                    @endforeach

                </div>

            </div>


            {{-- =================================================
                 24
                 ================================================= --}}

            <div class="part2-question">

                <div class="part2-question-title">

                    <div class="part2-number">
                        24
                    </div>

                    <div>
                        Kondisi dinding rumah
                    </div>

                </div>

                <div class="part2-radio-group">

                    @foreach([
                        'Baik',
                        'Rusak Ringan',
                        'Rusak Sedang',
                        'Rusak Berat'
                    ] as $option)

                        <label class="part2-radio">

                            <input
                                type="radio"
                                name="kondisi_dinding"
                                value="{{ $option }}"
                                {{ old('kondisi_dinding', $dataPart2->kondisi_dinding ?? '') == $option ? 'checked' : '' }}
                            >

                            {{ $option }}

                        </label>

                    @endforeach

                </div>

            </div>


            {{-- =================================================
                 25
                 ================================================= --}}

            <div class="part2-question">

                <div class="part2-question-title">

                    <div class="part2-number">
                        25
                    </div>

                    <div>
                        Jenis atap terluas yang digunakan
                    </div>

                </div>

                <div class="part2-radio-group">

                    @foreach([
                        'Beton',
                        'Genteng',
                        'Seng',
                        'Asbes',
                        'Kayu/Sirap',
                        'Lainnya'
                    ] as $option)

                        <label class="part2-radio">

                            <input
                                type="radio"
                                name="jenis_atap"
                                value="{{ $option }}"
                                {{ old('jenis_atap', $dataPart2->jenis_atap ?? '') == $option ? 'checked' : '' }}
                            >

                            {{ $option }}

                        </label>

                    @endforeach

                </div>

            </div>


            {{-- =================================================
                 26
                 ================================================= --}}

            <div class="part2-question">

                <div class="part2-question-title">

                    <div class="part2-number">
                        26
                    </div>

                    <div>
                        Kondisi atap rumah
                    </div>

                </div>

                <div class="part2-radio-group">

                    @foreach([
                        'Baik',
                        'Rusak Ringan',
                        'Rusak Sedang',
                        'Rusak Berat'
                    ] as $option)

                        <label class="part2-radio">

                            <input
                                type="radio"
                                name="kondisi_atap"
                                value="{{ $option }}"
                                {{ old('kondisi_atap', isset($dataPart2) ? match((int) $dataPart2->kondisi_atap) {
                                    1 => 'Baik',
                                    2 => 'Rusak Ringan',
                                    3 => 'Rusak Sedang',
                                    4 => 'Rusak Berat',
                                    default => ''
                                } : '') == $option ? 'checked' : '' }}
                            >

                            {{ $option }}

                        </label>

                    @endforeach

                </div>

            </div>


            {{-- =================================================
                 27
                 ================================================= --}}

            <div class="part2-question">

                <div class="part2-question-title">

                    <div class="part2-number">
                        27
                    </div>

                    <div>
                        Fasilitas tempat buang air besar (BAB) yang digunakan
                    </div>

                </div>

                <div class="part2-radio-group">

                    @foreach([
                        'Sendiri',
                        'Bersama',
                        'Umum',
                        'Tidak ada',
                        'MCK Komunal',
                        'Lainnya'
                    ] as $option)

                        <label class="part2-radio">

                            <input
                                type="radio"
                                name="fasilitas_bab"
                                value="{{ $option }}"
                                {{ old('fasilitas_bab', $dataPart2->fasilitas_bab ?? '') == $option ? 'checked' : '' }}
                            >

                            {{ $option }}

                        </label>

                    @endforeach

                </div>

            </div>


            {{-- =================================================
                 28
                 ================================================= --}}

            <div class="part2-question">

                <div class="part2-question-title">

                    <div class="part2-number">
                        28
                    </div>

                    <div>
                        Jenis kloset yang digunakan
                    </div>

                </div>

                <div class="part2-radio-group">

                    @foreach([
                        'Leher angsa',
                        'Plengsengan dengan tutup',
                        'Plengsengan tanpa tutup',
                        'Cemplung/cebluk'
                    ] as $option)

                        <label class="part2-radio">

                            <input
                                type="radio"
                                name="jenis_toilet"
                                value="{{ $option }}"
                                {{ old('jenis_toilet', $dataPart2->jenis_kloset ?? '') == $option ? 'checked' : '' }}
                            >

                            {{ $option }}

                        </label>

                    @endforeach

                </div>

            </div>


            {{-- =================================================
                 29
                 ================================================= --}}

            <div class="part2-question">

                <div class="part2-question-title">

                    <div class="part2-number">
                        29
                    </div>

                    <div>
                        Sumber air minum utama
                    </div>

                </div>

                <div class="part2-radio-group">

                    @foreach([
                        'Air kemasan bermerk',
                        'Air isi ulang',
                        'Leding',
                        'Sumur bor/pompa',
                        'Sumur terlindung',
                        'Lainnya'
                    ] as $option)

                        <label class="part2-radio">

                            <input
                                type="radio"
                                name="sumber_air_minum"
                                value="{{ $option }}"
                                {{ old('sumber_air_minum', $dataPart2->sumber_minum ?? '') == $option ? 'checked' : '' }}
                            >

                            {{ $option }}

                        </label>

                    @endforeach

                </div>

            </div>


            {{-- =================================================
                 30
                 ================================================= --}}

            <div class="part2-question">

                <div class="part2-question-title">

                    <div class="part2-number">
                        30
                    </div>

                    <div>
                        Sumber penerangan utama
                    </div>

                </div>

                <div class="part2-radio-group">

                    @foreach([
                        'Listrik PLN dengan meteran',
                        'Listrik PLN tanpa meteran',
                        'Listrik Non-PLN'
                    ] as $option)

                        <label class="part2-radio">

                            <input
                                type="radio"
                                name="sumber_penerangan"
                                value="{{ $option }}"
                                {{ old('sumber_penerangan', $dataPart2->sumber_penerangan ?? '') == $option ? 'checked' : '' }}
                            >

                            {{ $option }}

                        </label>

                    @endforeach

                </div>

            </div>


            {{-- =================================================
                 31
                 ================================================= --}}

            <div class="part2-question">

                <div class="part2-question-title">

                    <div class="part2-number">
                        31
                    </div>

                    <div>
                        Daya listrik yang digunakan
                    </div>

                </div>

                <div class="part2-radio-group">

                    @foreach([
                        '450 watt',
                        '900 watt',
                        '1300 watt',
                        '2200 watt',
                        '2200 watt'
                    ] as $option)

                        <label class="part2-radio">

                            <input
                                type="radio"
                                name="daya_listrik"
                                value="{{ $option }}"
                                {{ old('daya_listrik', $dataPart2->daya_listrik ?? '') == $option ? 'checked' : '' }}
                            >

                            {{ $option }}

                        </label>

                    @endforeach

                </div>

            </div>


            {{-- =================================================
                 32
                 ================================================= --}}

            <div class="part2-question">

                <div class="part2-question-title">

                    <div class="part2-number">
                        32
                    </div>

                    <div>
                        ID Pelanggan PLN / Nomor Meteran Listrik
                    </div>

                </div>

                <div class="part2-input-wrap">

                    <input
                        type="text"
                        name="id_pelanggan_pln"
                        class="part2-input"
                        value="{{ old('id_pelanggan_pln', $dataPart2->idpel_pln ?? '') }}"
                        placeholder="Masukkan ID Pelanggan PLN / Nomor Meteran Listrik"
                    >

                </div>

            </div>


            {{-- =================================================
                 33
                 ================================================= --}}

            <div class="part2-question">

                <div class="part2-question-title">

                    <div class="part2-number">
                        33
                    </div>

                    <div>
                        Jumlah meteran listrik yang digunakan
                    </div>

                </div>

                <div class="part2-input-wrap">

                    <input
                        type="number"
                        name="jumlah_meteran"
                        class="part2-input"
                        min="1"
                        value="{{ old('jumlah_meteran', $dataPart2->jml_meteran ?? '') }}"
                        placeholder="Masukkan jumlah meteran"
                    >

                </div>

            </div>


        </div>


        {{-- =================================================
             FOOTER
             ================================================= --}}

        <div class="part2-footer">

            <a
                href="{{ route('kuisioner.part1') }}"
                class="part2-back"
            >
                ← Kembali
            </a>

            <button
                type="submit"
                class="part2-next"
            >
                Lanjut ke Part 3 →
            </button>

        </div>

    </form>

</div>


<script>

    /* =========================================================
       KELUARGA LAIN
       ========================================================= */

    function toggleKeluargaLain(show)
    {
        const box =
            document.getElementById('jumlahKeluargaLainBox');

        if (!box) {
            return;
        }

        if (show) {
            box.classList.add('show');
        } else {
            box.classList.remove('show');
        }
    }


    /* =========================================================
       KEPEMILIKAN RUMAH
       ========================================================= */

    function toggleKepemilikan(value)
    {
        const bukti =
            document.getElementById('buktiMilikSendiri');

        const kontrak =
            document.getElementById('kontrakBox');

        const bebasSewa =
            document.getElementById('bebasSewaBox');

        const lainnya =
            document.getElementById('kepemilikanLainnyaBox');


        if (!bukti || !kontrak || !bebasSewa || !lainnya) {
            return;
        }


        bukti.classList.remove('show');
        kontrak.classList.remove('show');
        bebasSewa.classList.remove('show');
        lainnya.classList.remove('show');


        if (value === 'Milik Sendiri') {
            bukti.classList.add('show');
        }

        if (value === 'Kontrak/Sewa') {
            kontrak.classList.add('show');
        }

        if (value === 'Bebas Sewa') {
            bebasSewa.classList.add('show');
        }

        if (value === 'Lainnya') {
            lainnya.classList.add('show');
        }
    }


    /* =========================================================
       SAAT HALAMAN DIBUKA
       ========================================================= */

    document.addEventListener('DOMContentLoaded', function () {

        const keluarga =
            document.querySelector(
                'input[name="keluarga_lain"]:checked'
            );

        if (keluarga) {
            toggleKeluargaLain(
                keluarga.value === 'Ya'
            );
        }


        const kepemilikan =
            document.querySelector(
                'input[name="status_kepemilikan"]:checked'
            );

        if (kepemilikan) {
            toggleKepemilikan(
                kepemilikan.value
            );
        }

    });

</script>

@endsection