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
    margin-top: 14px;
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    background: #FFFFFF;
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

#map {
    width: 100%;
    height: 340px;
}


/* =========================================================
   COORDINATE
========================================================= */

.coordinate-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
    margin-top: 14px;
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
   FOOTER
========================================================= */

.form-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 18px 26px;
    border-top: 1px solid var(--border);
    background: #FAFAFD;
}

.btn-reset,
.btn-next {
    min-height: 42px;
    padding: 0 18px;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-family: 'Inter', sans-serif;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: .2s;
}

.btn-reset {
    border: 1px solid var(--border);
    background: #FFFFFF;
    color: #555B70;
}

.btn-reset:hover {
    background: #F5F6FA;
}

.btn-next {
    border: none;
    background: var(--primary);
    color: #FFFFFF;
}

.btn-next:hover {
    background: var(--primary-dark);
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
                            name="jalan"
                            id="jalan"
                            class="form-control"
                            value="{{ old(
                                'jalan',
                                $data->jalan
                                    ?? $selectedKeluarga->jalan
                                    ?? ''
                            ) }}"
                            placeholder="Masukkan nama jalan"
                        >

                        @error('jalan')
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
                         16. GEOTAGGING
                    ================================================== --}}

                    <div class="question full-width">

                        <label class="question-label">

                            Lokasi Rumah / Geotagging

                        </label>


                        <div class="map-wrapper">

                            <div class="map-header">

                                <strong>
                                    Tandai lokasi tempat tinggal
                                </strong>

                                <span>
                                    Klik pada peta atau gunakan lokasi perangkat.
                                </span>

                            </div>


                            <div id="map"></div>

                        </div>


                        <div class="coordinate-grid">

                            <div>

                                <label class="question-label">
                                    Latitude
                                </label>

                                <input
                                    type="text"
                                    name="latitude"
                                    id="latitude"
                                    class="form-control"
                                    value="{{ old('latitude', $data->latitude ?? '') }}"
                                    readonly
                                >

                            </div>


                            <div>

                                <label class="question-label">
                                    Longitude
                                </label>

                                <input
                                    type="text"
                                    name="longitude"
                                    id="longitude"
                                    class="form-control"
                                    value="{{ old('longitude', $data->longitude ?? '') }}"
                                    readonly
                                >

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 FOOTER
            ====================================================== --}}

            <div class="form-footer">

                <a
                    href="{{ route('kuisioner.pilih-responden') }}"
                    class="btn-reset"
                >
                    Kembali
                </a>


                <button
                    type="submit"
                    class="btn-next"
                >
                    Simpan &amp; Lanjut
                </button>

            </div>

        </form>

    </div>

</div>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       ALASAN ALAMAT TIDAK SESUAI
    ========================================================= */

    function toggleAlasanTidakSesuai() {

        const selected = document.querySelector(
            'input[name="is_alamat_sesuai"]:checked'
        );

        const wrapper = document.getElementById(
            'alasan-tidak-sesuai-wrapper'
        );

        if (!wrapper) {
            return;
        }

        if (!selected || selected.value !== '0') {

            wrapper.style.display = 'none';

            return;
        }

        wrapper.style.display = 'block';
    }


    document
        .querySelectorAll(
            'input[name="is_alamat_sesuai"]'
        )
        .forEach(function (radio) {

            radio.addEventListener(
                'change',
                toggleAlasanTidakSesuai
            );

        });


    toggleAlasanTidakSesuai();


    /* =========================================================
       MAP
    ========================================================= */

    const mapElement =
        document.getElementById('map');

    if (!mapElement) {
        return;
    }


    const latitudeInput =
        document.getElementById('latitude');

    const longitudeInput =
        document.getElementById('longitude');


    const defaultLat = -7.6453;
    const defaultLng = 112.9075;


    const savedLat =
        parseFloat(
            latitudeInput?.value
        );

    const savedLng =
        parseFloat(
            longitudeInput?.value
        );


    const hasSavedLocation =
        !isNaN(savedLat) &&
        !isNaN(savedLng);


    const initialLat =
        hasSavedLocation
            ? savedLat
            : defaultLat;

    const initialLng =
        hasSavedLocation
            ? savedLng
            : defaultLng;


    const map =
        L.map('map').setView(
            [
                initialLat,
                initialLng
            ],
            hasSavedLocation
                ? 17
                : 13
        );


    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }
    ).addTo(map);


    let marker = null;


    /* =========================================================
       SET LOCATION
    ========================================================= */

    function setLocation(lat, lng) {

        if (marker) {

            map.removeLayer(marker);

        }


        marker =
            L.marker(
                [lat, lng],
                {
                    draggable: true
                }
            ).addTo(map);


        if (latitudeInput) {

            latitudeInput.value = lat;

        }


        if (longitudeInput) {

            longitudeInput.value = lng;

        }


        marker.on(
            'dragend',
            function (event) {

                const position =
                    event.target.getLatLng();

                setLocation(
                    position.lat,
                    position.lng
                );

            }
        );

    }


    /* =========================================================
       LOKASI YANG SUDAH TERSIMPAN
    ========================================================= */

    if (hasSavedLocation) {

        setLocation(
            savedLat,
            savedLng
        );

    }


    /* =========================================================
       KLIK MAP
    ========================================================= */

    map.on(
        'click',
        function (event) {

            setLocation(
                event.latlng.lat,
                event.latlng.lng
            );

        }
    );


    /* =========================================================
       GEOLOCATION PERANGKAT
    ========================================================= */

    if (
        !hasSavedLocation &&
        navigator.geolocation
    ) {

        navigator.geolocation.getCurrentPosition(

            function (position) {

                const lat =
                    position.coords.latitude;

                const lng =
                    position.coords.longitude;


                map.setView(
                    [lat, lng],
                    17
                );


                setLocation(
                    lat,
                    lng
                );

            },

            function () {

                // Lokasi perangkat tidak tersedia.

            }

        );

    }


    /* =========================================================
       GEOCODING
    ========================================================= */

    const jalanInput =
        document.getElementById('jalan');

    const nomorRumahInput =
        document.getElementById('nomor_rumah');


    let geocodeTimer = null;


    function geocodeAlamat() {

        const jalan =
            jalanInput?.value?.trim() || '';

        const nomorRumah =
            nomorRumahInput?.value?.trim() || '';

        const kecamatan =
            @json($nilaiKecamatan);

        const kelurahan =
            @json($nilaiKelurahan);


        if (
            !jalan &&
            !kelurahan &&
            !kecamatan
        ) {
            return;
        }


        const address = [

            jalan,

            nomorRumah,

            kelurahan,

            kecamatan,

            'Kota Pasuruan',

            'Jawa Timur',

            'Indonesia'

        ]
        .filter(Boolean)
        .join(', ');


        fetch(
            'https://nominatim.openstreetmap.org/search?' +
            new URLSearchParams({
                q: address,
                format: 'json',
                limit: 1
            }),
            {
                headers: {
                    'Accept': 'application/json'
                }
            }
        )

        .then(function (response) {

            return response.json();

        })

        .then(function (results) {

            if (
                !results ||
                !results.length
            ) {
                return;
            }


            const lat =
                parseFloat(
                    results[0].lat
                );

            const lng =
                parseFloat(
                    results[0].lon
                );


            if (
                isNaN(lat) ||
                isNaN(lng)
            ) {
                return;
            }


            map.setView(
                [lat, lng],
                17
            );


            setLocation(
                lat,
                lng
            );

        })

        .catch(function () {

            // Gagal geocoding tidak mengganggu form.

        });

    }


    function scheduleGeocode() {

        clearTimeout(
            geocodeTimer
        );


        geocodeTimer =
            setTimeout(
                geocodeAlamat,
                1000
            );

    }


    if (jalanInput) {

        jalanInput.addEventListener(
            'change',
            scheduleGeocode
        );

    }


    if (nomorRumahInput) {

        nomorRumahInput.addEventListener(
            'change',
            scheduleGeocode
        );

    }

});
</script>

@endsection