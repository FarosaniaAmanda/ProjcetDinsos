@extends('admin.kuisioner.layout')

@section('kuisioner-content')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
    rel="stylesheet"
>

<style>
/* =========================================================
   DASAR
========================================================= */

:root {
    --primary: #292D8F;
    --primary-dark: #222675;
    --primary-soft: #F0F1FF;
    --border: #E2E4EF;
    --text: #25283A;
    --muted: #777D91;
    --background: #F5F6FB;
    --white: #FFFFFF;
    --danger: #D64545;
}

.kuisioner-page,
.kuisioner-page * {
    box-sizing: border-box;
}

.kuisioner-page {
    width: 100%;
    min-height: 100vh;
    padding: 14px 24px 40px;
    background: var(--background);
    color: var(--text);
    font-family: 'Inter', sans-serif;
}


/* =========================================================
   ALERT
========================================================= */

.alert {
    width: 100%;
    max-width: 1180px;
    margin: 0 auto 16px;
    padding: 13px 16px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
}

.alert-warning {
    background: #FFF7E6;
    color: #946200;
    border: 1px solid #F3D58A;
}

.alert-success {
    background: #EAF8EE;
    color: #23763A;
    border: 1px solid #BFE5C9;
}


/* =========================================================
   MAIN CARD
========================================================= */

.kuisioner-card {
    width: 100%;
    max-width: 1180px;
    margin: 0 auto;
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(35, 39, 80, .05);
}


/* =========================================================
   HEADER PART
========================================================= */

.part-header {
    padding: 22px 26px;
    border-bottom: 1px solid var(--border);
    background: #FFFFFF;
}

.part-header h2 {
    margin: 0;
    font-size: 20px;
    font-weight: 800;
    color: var(--primary-dark);
}

.part-header p {
    margin: 7px 0 0;
    font-size: 13px;
    color: var(--muted);
    line-height: 1.6;
}


/* =========================================================
   FORM CONTENT
========================================================= */

.form-content {
    padding: 26px;
}

.question {
    margin-bottom: 22px;
}

.question:last-child {
    margin-bottom: 0;
}

.question-label {
    display: block;
    margin-bottom: 8px;
    font-size: 13px;
    font-weight: 700;
    color: var(--text);
    line-height: 1.5;
}

.question-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 25px;
    height: 25px;
    margin-right: 7px;
    border-radius: 7px;
    background: var(--primary-soft);
    color: var(--primary);
    font-size: 12px;
    font-weight: 800;
    vertical-align: middle;
}

.required {
    color: var(--danger);
}


/* =========================================================
   INPUT
========================================================= */

.form-control {
    width: 100%;
    min-height: 43px;
    padding: 10px 13px;
    border: 1px solid var(--border);
    border-radius: 9px;
    outline: none;
    background: #FFFFFF;
    color: var(--text);
    font-family: 'Inter', sans-serif;
    font-size: 13px;
    transition: .2s;
}

.form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(41, 45, 143, .08);
}

.form-control[readonly] {
    background: #F7F8FC;
    color: #555B70;
    cursor: default;
}

textarea.form-control {
    min-height: 95px;
    resize: vertical;
    line-height: 1.6;
}


/* =========================================================
   FORM GRID
========================================================= */

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 20px;
}

.full-width {
    grid-column: 1 / -1;
}


/* =========================================================
   RADIO
========================================================= */

.radio-group {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.radio-option {
    position: relative;
}

.radio-option input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.radio-option label {
    display: inline-flex;
    align-items: center;
    min-height: 40px;
    padding: 0 15px;
    border: 1px solid var(--border);
    border-radius: 9px;
    background: #FFFFFF;
    color: var(--text);
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: .2s;
}

.radio-option input:checked + label {
    border-color: var(--primary);
    background: var(--primary-soft);
    color: var(--primary);
}


/* =========================================================
   ALASAN TIDAK SESUAI
========================================================= */

#alasan-tidak-sesuai-wrapper {
    display: none;
    margin-top: 12px;
}

/* =========================================================
   MAP
========================================================= */

.map-wrapper {
    width: 100%;
    max-width: 100%;
    min-width: 0;
    padding: 0 10px 10px;
    box-sizing: border-box;
    overflow: hidden;
}

.map-header {
    padding: 12px 14px;
    border-bottom: 1px solid var(--border);
    background: #F8F9FC;
}

.map-header strong {
    display: block;
    font-size: 13px;
    color: var(--text);
}

.map-header span {
    display: block;
    margin-top: 3px;
    font-size: 11px;
    color: var(--muted);
}

/* PETA */
#map {
    display: block;
    width: 100%;
    max-width: 100%;
    height: 350px;
    margin: 0;
    border: 1px solid var(--border);
    border-top: none;
    border-radius: 0 0 12px 12px;
    box-sizing: border-box;
    z-index: 1;
}

/* =========================================================
   KOORDINAT DI ATAS PETA
========================================================= */

.koordinat-info {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    min-height: 62px;
    padding: 12px 20px;
    margin: 10px 0 0;
    border: 1px solid var(--border);
    border-radius: 12px 12px 0 0;
    background: #FFFFFF;
    color: var(--text);
    font-size: 13px;
    box-sizing: border-box;
}

.koordinat-info strong {
    font-weight: 700;
    white-space: nowrap;
}

#koordinatText {
    color: #777D91;
    font-size: 13px;
    overflow-wrap: anywhere;
}

/* Input koordinat tetap tersembunyi */
input[type="hidden"][name="latitude"],
input[type="hidden"][name="longitude"] {
    display: none;
}


/* =========================================================
   ERROR
========================================================= */

.field-error {
    margin-top: 6px;
    color: var(--danger);
    font-size: 11px;
    font-weight: 600;
}




/* =========================================================
   FOOTER DAN TOMBOL - FINAL
========================================================= */

.form-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;

    width: 100%;
    max-width: 100%;
    min-width: 0;
    margin-top: 24px;
    padding: 20px 32px;

    box-sizing: border-box;
    background: #FAFAFD;
    border-top: 1px solid #E5E7F0;
    border-radius: 0 0 16px 16px;
}

.form-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;

    min-width: 0;
    margin-left: auto;
}

.btn-reset,
.btn-draft,

.btn-next {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 42px;
    padding: 0 16px;
    border-radius: 9px;
    box-sizing: border-box;
    font-family: 'Inter', sans-serif;
    font-size: 13px;
    font-weight: 700;
    line-height: 1.4;
    text-decoration: none;
    white-space: nowrap;
    cursor: pointer;
    transition: background 0.2s, color 0.2s, border-color 0.2s;
}

.btn-reset {
    flex-shrink: 0;
    border: 1px solid var(--border);
    background: #FFFFFF;
    color: #555B70;
}

.btn-reset:hover {
    background: #F5F6FA;
}

.btn-draft {
    border: 1px solid var(--primary);
    background: #FFFFFF;
    color: var(--primary);
}

.btn-draft:hover {
    background: var(--primary-soft);
}

.btn-next {
    border: 1px solid var(--primary);
    background: var(--primary);
    color: #FFFFFF;
}

.btn-next:hover {
    background: var(--primary-dark);
}

/* TABLET */
@media (max-width: 768px) {
    .form-footer {
        gap: 12px;
        padding: 16px 20px;
    }

    .form-actions {
        gap: 8px;
    }

    .btn-reset,
    .btn-draft,
    .btn-next {
        min-height: 46px;
        padding: 0 14px;
        font-size: 13px;
    }
}

/* HP */
@media (max-width: 576px) {
    .form-footer {
        align-items: stretch;
        flex-direction: column;
        gap: 12px;
        padding: 16px;
    }

    .form-footer > .btn-reset {
        align-self: flex-start;
    }

    .form-actions {
        width: 100%;
        margin-left: 0;
    }

    .form-actions .btn-draft,
    .form-actions .btn-next {
        flex: 1;
        min-width: 0;
        padding: 0 10px;
        white-space: normal;
        text-align: center;
    }
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 768px) {

    .kuisioner-page {
        padding: 10px 12px 30px;
    }

    .part-header {
        padding: 18px;
    }

    .part-header h2 {
        font-size: 18px;
    }

    .form-content {
        padding: 18px;
    }

    .form-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .full-width {
        grid-column: auto;
    }

    .coordinate-grid {
        grid-template-columns: 1fr;
    }

    .form-footer {
        padding: 15px 18px;
    }

    .btn-reset,
    .btn-draft,
    .btn-next {
        flex: 1;
    }

    #map {
    height: 280px;
    }
}

</style>


@php

    /* =========================================================
       KECAMATAN & KELURAHAN
    ========================================================= */

    $nilaiKecamatan = old(
        'kecamatan',
        $data->kecamatan
            ?? $selectedKecamatan
            ?? ''
    );

    $nilaiKelurahan = old(
        'kelurahan',
        $data->kelurahan
            ?? $selectedKelurahan
            ?? ''
    );


    /* =========================================================
       JUMLAH ANGGOTA
    ========================================================= */

    $jumlahAnggota = old(
        'jumlah_anggota',
        isset($selectedKeluarga)
            ? ($selectedKeluarga->anggota?->count() ?? 0)
            : ($data->jumlah_anggota ?? '')
    );


    /* =========================================================
       RT / RW
    ========================================================= */

    $nilaiRt = old(
        'rt',
        $selectedKeluarga->rt
            ?? $data->rt
            ?? ''
    );

    $nilaiRw = old(
        'rw',
        $selectedKeluarga->rw
            ?? $data->rw
            ?? ''
    );

    $nilaiRtRw = '';

    if ($nilaiRt !== '' || $nilaiRw !== '') {
        $nilaiRtRw = 'RT ' . $nilaiRt . ' / RW ' . $nilaiRw;
    }

@endphp


{{-- =========================================================
     ALERT
========================================================= --}}

@if(session('warning'))

    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>

@endif


@if(session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif


<div class="kuisioner-page">

    <div class="kuisioner-card">

        {{-- =====================================================
             HEADER
        ====================================================== --}}

        <div class="part-header">

            <h2>
                Bagian 1 — Data Keluarga
            </h2>

            <p>
                Data keluarga otomatis diambil dari data responden
                yang telah dipilih. Periksa kembali sebelum melanjutkan.
            </p>

        </div>


        {{-- =====================================================
             FORM
        ====================================================== --}}

        <form
            action="{{ route('kuisioner.part1.store') }}"
            method="POST"
        >

            @csrf


            {{-- KODE KELUARGA --}}
            <input
                type="hidden"
                name="keluarga_kode"
                value="{{ $selectedKeluarga->kode ?? '' }}"
            >


            <div class="form-content">

                <div class="form-grid">


                    {{-- =================================================
                         1. NIK
                    ================================================== --}}

                    <div class="question">

                        <label class="question-label">

                            <span class="question-number">
                                1
                            </span>

                            NIK

                            <span class="required">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            name="nik"
                            class="form-control"
                            value="{{ old('nik', $data->nik ?? $selectedKeluarga->nik ?? '') }}"
                            readonly
                        >

                        @error('nik')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         2. NAMA KEPALA KELUARGA
                    ================================================== --}}

                    <div class="question">

                        <label class="question-label">

                            <span class="question-number">
                                2
                            </span>

                            Nama Kepala Keluarga

                            <span class="required">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            name="nama_kepala_keluarga"
                            class="form-control"
                            value="{{ old('nama_kepala_keluarga', $data->nama_kepala_keluarga ?? $selectedKeluarga->nama_lengkap ?? '') }}"
                            readonly
                        >

                        @error('nama_kepala_keluarga')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         3. STATUS DALAM KELUARGA
                    ================================================== --}}

                    <div class="question">

                        <label class="question-label">

                            <span class="question-number">
                                3
                            </span>

                            Status dalam Keluarga

                            <span class="required">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            name="status_dalam_keluarga"
                            class="form-control"
                            value="{{ old('status_dalam_keluarga', $data->status_dalam_keluarga ?? $selectedKeluarga->status_keluarga ?? '') }}"
                            readonly
                        >

                        @error('status_dalam_keluarga')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         4. NO KK
                    ================================================== --}}

                    <div class="question">

                        <label class="question-label">

                            <span class="question-number">
                                4
                            </span>

                            Nomor KK

                            <span class="required">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            name="no_kk"
                            class="form-control"
                            value="{{ old('no_kk', $data->no_kk ?? $selectedKeluarga->no_kk ?? '') }}"
                            readonly
                        >

                        @error('no_kk')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         5. JUMLAH ANGGOTA
                    ================================================== --}}

                    <div class="question">

                        <label class="question-label">

                            <span class="question-number">
                                5
                            </span>

                            Jumlah Anggota Keluarga

                            <span class="required">
                                *
                            </span>

                        </label>

                        <input
                            type="number"
                            name="jumlah_anggota"
                            class="form-control"
                            value="{{ $jumlahAnggota }}"
                            readonly
                        >

                        @error('jumlah_anggota')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         6. PROVINSI
                    ================================================== --}}

                    <div class="question">

                        <label class="question-label">

                            <span class="question-number">
                                6
                            </span>

                            Provinsi

                            <span class="required">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            name="provinsi"
                            class="form-control"
                            value="{{ old('provinsi', $data->provinsi ?? $selectedKeluarga->provinsi ?? 'Jawa Timur') }}"
                            readonly
                        >

                    </div>


                    {{-- =================================================
                         7. KABUPATEN / KOTA
                    ================================================== --}}

                    <div class="question">

                        <label class="question-label">

                            <span class="question-number">
                                7
                            </span>

                            Kabupaten / Kota

                            <span class="required">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            name="kabupaten_kota"
                            class="form-control"
                            value="{{ old('kabupaten_kota', $data->kabupaten_kota ?? $selectedKeluarga->kabupaten_kota ?? 'Kota Pasuruan') }}"
                            readonly
                        >

                    </div>


                    {{-- =================================================
                         8. KECAMATAN
                    ================================================== --}}

                    <div class="question">

                        <label class="question-label">

                            <span class="question-number">
                                8
                            </span>

                            Kecamatan

                            <span class="required">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            name="kecamatan"
                            class="form-control"
                            value="{{ $nilaiKecamatan }}"
                            readonly
                        >

                        @error('kecamatan')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         9. KELURAHAN
                    ================================================== --}}

                    <div class="question">

                        <label class="question-label">

                            <span class="question-number">
                                9
                            </span>

                            Kelurahan

                            <span class="required">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            name="kelurahan"
                            class="form-control"
                            value="{{ $nilaiKelurahan }}"
                            readonly
                        >

                        @error('kelurahan')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                         10. KODE POS
                    ================================================== --}}

                    <div class="question">

                        <label class="question-label">

                            <span class="question-number">
                                10
                            </span>

                            Kode Pos

                        </label>

                        <input
                            type="text"
                            name="kode_pos"
                            class="form-control"
                            value="{{ old('kode_pos', $data->kode_pos ?? $selectedKeluarga->kode_pos ?? '') }}"
                            readonly
                        >

                    </div>


                    {{-- =================================================
                         11. RT / RW
                    ================================================== --}}

                    <div class="question">

                        <label class="question-label">

                            <span class="question-number">
                                11
                            </span>

                            RT / RW

                            <span class="required">
                                *
                            </span>

                        </label>

                        <input
                            type="text"
                            name="rt_rw"
                            class="form-control"
                            value="{{ old('rt_rw', $data->rt_rw ?? $nilaiRtRw) }}"
                            readonly
                        >

                        @error('rt_rw')

                            <div class="field-error">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- =================================================
                        12. ALAMAT LENGKAP
                    ================================================== --}}
                    <div class="question full-width">

                        <label class="question-label">
                            <span class="question-number">12</span>
                            Alamat Lengkap
                            <span class="required">*</span>
                        </label>

                        <textarea
                            name="alamat_lengkap"
                            class="form-control"
                            readonly
                        >{{ old(
                            'alamat_lengkap',
                            $data->alamat_lengkap
                                ?? $selectedKeluarga->alamat_lengkap
                                ?? $selectedKeluarga->alamat
                                ?? ''
                        ) }}</textarea>

                        @error('alamat_lengkap')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                {{-- =================================================
                    13. JALAN
                ================================================== --}}
                <div class="question">

                    <label class="question-label">
                        <span class="question-number">13</span>
                        Jalan
                    </label>

                    <input
                        type="text"
                        name="jalan_rumah"
                        id="jalan"
                        class="form-control"
                        value="{{ old(
                            'jalan_rumah',
                            $data->jalan_rumah
                                ?? $selectedKeluarga->jalan_rumah
                                ?? $selectedKeluarga->jalan
                                ?? ''
                        ) }}"
                        placeholder="Masukkan nama jalan"
                    >

                    @error('jalan_rumah')
                        <div class="field-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                    {{-- =================================================
                        14. NOMOR RUMAH
                    ================================================== --}}
                    <div class="question">

                        <label class="question-label">
                            <span class="question-number">14</span>
                            Nomor Rumah
                        </label>

                        <input
                            type="text"
                            name="nomor_rumah"
                            id="nomor_rumah"
                            class="form-control"
                            value="{{ old(
                                'nomor_rumah',
                                $data->nomor_rumah
                                    ?? $selectedKeluarga->nomor_rumah
                                    ?? ''
                            ) }}"
                            placeholder="Masukkan nomor rumah"
                        >

                        @error('nomor_rumah')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    
                    {{-- =================================================
                        15. ALAMAT SESUAI KK
                    ================================================== --}}
                    <div class="question full-width">

                        <label class="question-label">
                            <span class="question-number">15</span>
                            Apakah alamat tempat tinggal saat ini sesuai dengan KK?
                            <span class="required">*</span>
                        </label>

                        <div class="radio-group">

                            <div class="radio-option">
                                <input
                                    type="radio"
                                    name="is_alamat_sesuai"
                                    id="alamat_sesuai_ya"
                                    value="1"
                                    {{ (string) old('is_alamat_sesuai', $data->is_alamat_sesuai ?? '') === '1' ? 'checked' : '' }}
                                    required
                                >
                                <label for="alamat_sesuai_ya">Ya</label>
                            </div>

                            <div class="radio-option">
                                <input
                                    type="radio"
                                    name="is_alamat_sesuai"
                                    id="alamat_sesuai_tidak"
                                    value="0"
                                    {{ (string) old('is_alamat_sesuai', $data->is_alamat_sesuai ?? '') === '0' ? 'checked' : '' }}
                                    required
                                >
                                <label for="alamat_sesuai_tidak">Tidak</label>
                            </div>

                        </div>

                        @error('is_alamat_sesuai')
                            <div class="field-error">{{ $message }}</div>
                        @enderror

                        <div id="alasan-tidak-sesuai-wrapper">

                            <label class="question-label" for="alasan_tidak_sesuai">
                                Jika tidak, jelaskan alasannya
                                <span class="required">*</span>
                            </label>

                            <textarea
                                name="alasan_tidak_sesuai"
                                id="alasan_tidak_sesuai"
                                class="form-control"
                                placeholder="Masukkan alasan alamat tidak sesuai dengan KK"
                            >{{ old('alasan_tidak_sesuai', $data->alasan_tidak_sesuai ?? '') }}</textarea>

                            @error('alasan_tidak_sesuai')
                                <div class="field-error">{{ $message }}</div>
                            @enderror

                        </div>

                    </div>

                    
                    {{-- =================================================
                        16. GEOTAGGING
                    ================================================== --}}

                    @php
                        $geotaggingAwal = $selectedKeluarga->geotagging ?? '';

                        $koordinat = array_map(
                            'trim',
                            explode(',', $geotaggingAwal)
                        );

                        $latitudeDefault = count($koordinat) >= 2
                            && is_numeric($koordinat[0])
                            ? $koordinat[0]
                            : '-7.6466338';

                        $longitudeDefault = count($koordinat) >= 2
                            && is_numeric($koordinat[1])
                            ? $koordinat[1]
                            : '112.9079133';

                        $latitudeAwal = old('latitude', $latitudeDefault);
                        $longitudeAwal = old('longitude', $longitudeDefault);
                    @endphp

                    <div class="question full-width">

                        <label class="question-label">
                            Lokasi Rumah / Geotagging
                        </label>

                        <div class="map-wrapper">

                            <div class="koordinat-info">
                                <strong>Koordinat Lokasi:</strong>
                                <span id="koordinatText">
                                    {{ $latitudeAwal }},{{ $longitudeAwal }}
                                </span>
                            </div>

                            <div id="map"></div>

                        </div>

                        <input
                            type="hidden"
                            name="latitude"
                            id="latitude"
                            value="{{ $latitudeAwal }}"
                        >

                        <input
                            type="hidden"
                            name="longitude"
                            id="longitude"
                            value="{{ $longitudeAwal }}"
                        >

                    </div>


            {{-- =====================================================
                 FOOTER
            ====================================================== --}}

            
                    {{-- PENUTUP FORM GRID DAN FORM CONTENT --}}
                    </div>
                </div>

                {{-- =====================================================
                     FOOTER
                ====================================================== --}}
                <div class="form-footer">
                    <button
                        type="button"
                        class="btn-reset"
                        onclick="window.history.back()"
                    >
                        Batal
                    </button>

                    <div class="form-actions">
                        <button
                            type="submit"
                            class="btn-draft"
                            name="action"
                            value="draft"
                        >
                            Draft
                        </button>

                        <button
                            type="submit"
                            class="btn-next"
                            name="action"
                            value="next"
                        >
                            Lanjut ke Part 2 →
                        </button>
                    </div>
                </div>

        </form>

    </div>

</div>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // =========================================
    // 1. ALASAN ALAMAT TIDAK SESUAI
    // =========================================
    const radiosAlamat = document.querySelectorAll(
        'input[name="is_alamat_sesuai"]'
    );

    const alasanWrapper = document.getElementById(
        'alasan-tidak-sesuai-wrapper'
    );

    const alasanInput = document.getElementById(
        'alasan_tidak_sesuai'
    );

    function toggleAlasan() {
        const selected = document.querySelector(
            'input[name="is_alamat_sesuai"]:checked'
        );

        const tidakSesuai = selected && selected.value === '0';

        if (alasanWrapper) {
            alasanWrapper.style.display = tidakSesuai ? 'block' : 'none';
        }

        if (alasanInput) {
            alasanInput.required = !!tidakSesuai;
        }
    }

    radiosAlamat.forEach(function (radio) {
        radio.addEventListener('change', toggleAlasan);
    });

    toggleAlasan();

    // =========================================
    // 2. PETA GEOTAGGING
    // =========================================
    const mapElement = document.getElementById('map');
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const koordinatText = document.getElementById('koordinatText');

    if (!mapElement || !latInput || !lngInput || typeof L === 'undefined') {
        return;
    }

    const defaultLat = -7.6453;
    const defaultLng = 112.9075;

    let lat = parseFloat(latInput.value);
    let lng = parseFloat(lngInput.value);

    const lokasiValid =
        Number.isFinite(lat) &&
        Number.isFinite(lng) &&
        lat >= -90 && lat <= 90 &&
        lng >= -180 && lng <= 180;

    if (!lokasiValid) {
        lat = defaultLat;
        lng = defaultLng;
    }

    const map = L.map('map').setView([lat, lng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const marker = L.marker([lat, lng], {
        draggable: true
    }).addTo(map);

    function updateKoordinat(latitude, longitude) {
        latInput.value = Number(latitude).toFixed(7);
        lngInput.value = Number(longitude).toFixed(7);

        if (koordinatText) {
            koordinatText.textContent =
                latInput.value + ', ' + lngInput.value;
        }
    }

    updateKoordinat(lat, lng);

    marker.on('dragend', function () {
        const posisi = marker.getLatLng();
        updateKoordinat(posisi.lat, posisi.lng);
    });

    map.on('click', function (event) {
        marker.setLatLng(event.latlng);
        updateKoordinat(event.latlng.lat, event.latlng.lng);
    });

    setTimeout(function () {
        map.invalidateSize();
    }, 200);

    // Gunakan lokasi perangkat hanya jika koordinat awal belum valid
    if (!lokasiValid && navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                map.setView([latitude, longitude], 17);
                marker.setLatLng([latitude, longitude]);

                updateKoordinat(latitude, longitude);
            },
            function () {
                // Jika izin lokasi ditolak, gunakan koordinat default.
            }
        );
    }
});
</script>

@endsection