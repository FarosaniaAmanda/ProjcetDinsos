@extends('admin.layouts.app')

@section('content')

@if(session('warning'))
    <div style="
        background:#fff7ed;
        border:1px solid #fed7aa;
        color:#9a3412;
        padding:14px 18px;
        border-radius:12px;
        margin-bottom:20px;
        font-weight:600;
    ">
        ⚠️ {{ session('warning') }}
    </div>
@endif

<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>

<style>
    .kuisioner-page {
        max-width: 1100px;
        margin: 0 auto;
    }

    .kuisioner-header {
        background: #fff;
        border: 1px solid #e7e8ee;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 4px 10px rgba(0,0,0,.03);
    }

    .kuisioner-label {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #252A86;
        margin-bottom: 8px;
    }

    .kuisioner-title {
        margin: 0;
        font-size: 28px;
        font-weight: 800;
        color: #1f2937;
    }

    .kuisioner-subtitle {
        margin: 8px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    /* =========================================================
       PART NAVIGATION
    ========================================================= */

    .part-navigation {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 10px;
        margin-bottom: 20px;
    }

    .part-item {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 13px 10px;
        text-align: center;

        color: #6b7280 !important;
        text-decoration: none !important;

        font-size: 13px;
        font-weight: 700;
        display: block;
        cursor: pointer;
        transition: .2s;
    }

    .part-item:link,
    .part-item:visited,
    .part-item:hover,
    .part-item:active {
        text-decoration: none !important;
    }

    .part-item:hover {
        border-color: #252A86;
        color: #252A86 !important;
        background: #f8f9ff;
    }

    .part-item.active {
        background: #252A86;
        border-color: #252A86;
        color: #fff !important;
    }

    .part-item.active:hover {
        background: #252A86;
        color: #fff !important;
    }

    .part-number {
        display: block;
        font-size: 11px;
        margin-bottom: 3px;
        opacity: .8;
        text-decoration: none !important;
    }

    .part-item *,
    .part-item *:hover,
    .part-item *:visited {
        text-decoration: none !important;
    }


    /* =========================================================
       FORM
    ========================================================= */

    .kuisioner-card {
        background: #fff;
        border: 1px solid #e7e8ee;
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 4px 10px rgba(0,0,0,.03);
    }

    .part-header {
        padding-bottom: 20px;
        margin-bottom: 24px;
        border-bottom: 1px solid #e5e7eb;
    }

    .part-header h2 {
        margin: 0;
        font-size: 21px;
        color: #111827;
    }

    .part-header p {
        margin: 7px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .question {
        margin-bottom: 28px;
    }

    .question-title {
        display: flex;
        gap: 8px;
        align-items: flex-start;
        margin-bottom: 12px;
        font-size: 15px;
        font-weight: 700;
        color: #1f2937;
        line-height: 1.5;
    }

    .question-number {
        min-width: 26px;
        height: 26px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #e8edff;
        color: #252A86;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 800;
    }

    .form-group {
        margin-left: 34px;
    }

    .form-label {
        display: block;
        margin-bottom: 7px;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }

    .form-input,
    .form-select {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        padding: 11px 13px;
        font-size: 14px;
        color: #1f2937;
        background: #fff;
        outline: none;
        transition: .2s;
    }

    .form-input:focus,
    .form-select:focus {
        border-color: #252A86;
        box-shadow: 0 0 0 3px rgba(37,42,134,.10);
    }

    .form-input[readonly] {
        background: #f9fafb;
        color: #4b5563;
    }

    .location-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }


    /* =========================================================
       RADIO
    ========================================================= */

    .radio-group {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .radio-option {
        position: relative;
        cursor: pointer;
    }

    .radio-option input {
        position: absolute;
        opacity: 0;
    }

    .radio-label {
        display: block;
        padding: 11px 18px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        color: #374151;
        background: #fff;
        font-size: 14px;
        font-weight: 600;
        transition: .2s;
    }

    .radio-option input:checked + .radio-label {
        background: #252A86;
        border-color: #252A86;
        color: #fff;
    }


    /* =========================================================
       ALASAN TIDAK SESUAI
    ========================================================= */

    .alasan-tidak-sesuai {
        display: none;
        margin-top: 12px;
    }

    .alasan-tidak-sesuai.show {
        display: block;
    }


    /* =========================================================
       MAP
    ========================================================= */

    .map-wrapper {
        margin-left: 34px;
    }

    #map {
        height: 400px;
        width: 100%;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        overflow: hidden;
    }

    .location-info {
        margin-top: 10px;
        padding: 10px 12px;
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        font-size: 13px;
        color: #374151;
    }

    .coordinate-input {
        margin-top: 10px;
    }

    .map-status {
        margin-top: 8px;
        font-size: 12px;
        color: #6b7280;
    }


    /* =========================================================
       FOOTER
    ========================================================= */

    .form-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }

    .required-info {
        font-size: 12px;
        color: #6b7280;
    }

    .button-group {
        display: flex;
        gap: 10px;
    }

    .btn {
        border: none;
        border-radius: 10px;
        padding: 11px 18px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-primary {
        background: #252A86;
        color: #fff;
    }

    .btn-primary:hover {
        background: #252A86;
    }


    /* =========================================================
       MOBILE
    ========================================================= */

    @media (max-width: 768px) {

        .kuisioner-header {
            padding: 18px;
        }

        .kuisioner-title {
            font-size: 22px;
        }

        .part-navigation {
            grid-template-columns: repeat(2, 1fr);
        }

        .part-item:last-child {
            grid-column: span 2;
        }

        .kuisioner-card {
            padding: 18px;
        }

        .location-grid {
            grid-template-columns: 1fr;
        }

        .form-group,
        .map-wrapper {
            margin-left: 0;
        }

        .question-title {
            font-size: 14px;
        }

        .form-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .button-group {
            width: 100%;
        }

        .button-group .btn {
            flex: 1;
        }
    }

    @media (max-width: 480px) {

        .part-navigation {
            grid-template-columns: 1fr 1fr;
            gap: 7px;
        }

        .part-item {
            font-size: 12px;
            padding: 10px 5px;
        }

        .kuisioner-card {
            padding: 15px;
        }

        .radio-group {
            flex-direction: column;
        }

        .radio-label {
            width: 100%;
            box-sizing: border-box;
        }

        #map {
            height: 300px;
        }
    }
</style>


<div class="kuisioner-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="kuisioner-header">

        <div class="kuisioner-label">
            Kuisioner Pendataan Keluarga
        </div>

        <h1 class="kuisioner-title">
            Form Kuisioner
        </h1>

        <p class="kuisioner-subtitle">
            Silakan lengkapi data keluarga sesuai dengan kondisi tempat tinggal saat ini.
        </p>

    </div>


    {{-- =====================================================
         PART NAVIGATION
    ====================================================== --}}

    <div class="part-navigation">

        <a href="{{ route('kuisioner.part1') }}" class="part-item active">
            <span class="part-number">PART 1</span>
            Identitas & Tempat Tinggal
        </a>

        <a href="{{ route('kuisioner.part2') }}" class="part-item">
            <span class="part-number">PART 2</span>
            Kondisi Tempat Tinggal
        </a>

        <a href="{{ route('kuisioner.part3') }}" class="part-item">
            <span class="part-number">PART 3</span>
            Kepemilikan Aset Keluarga
        </a>

        <a href="{{ route('kuisioner.part4') }}" class="part-item">
            <span class="part-number">PART 4</span>
            Data Anggota Keluarga
        </a>

        <a href="{{ route('kuisioner.part5') }}" class="part-item">
            <span class="part-number">PART 5</span>
            Penutup
        </a>

    </div>


    {{-- =====================================================
         FORM PART 1
    ====================================================== --}}

    <div class="kuisioner-card">

        <div class="part-header">

            <h2>
                Part 1 — Identitas & Tempat Tinggal
            </h2>

            <p>
                Data identitas keluarga dan lokasi tempat tinggal responden.
            </p>

        </div>


        <form action="{{ route('kuisioner.part1.store') }}" method="POST">
        
        @csrf


            {{-- 1 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">1</span>

                    <span>
                        Siapa nama Keluarga di Kartu Keluarga (KK) Anda?
                    </span>
                </div>

                <div class="form-group">

                    <div class="location-grid">

                        <div>

                            <label class="form-label">
                                NIK
                            </label>

                            <input
                                type="text"
                                name="nik"
                                class="form-input"
                                maxlength="16"
                                inputmode="numeric"
                                placeholder="Masukkan NIK"
                            >

                        </div>

                        <div>

                            <label class="form-label">
                                Nama
                            </label>

                            <input
                                type="text"
                                name="nama_kepala_keluarga"
                                class="form-input"
                                placeholder="Nama kepala keluarga"
                            >

                        </div>

                    </div>


                    <div style="margin-top:12px;">

                        <label class="form-label">
                            Status Keluarga
                        </label>

                        <select
                            name="status_keluarga"
                            class="form-select"
                        >

                            <option value="">
                                -- Pilih Status --
                            </option>

                            <option value="Kepala Keluarga">
                                Kepala Keluarga
                            </option>

                            <option value="Istri">
                                Istri
                            </option>

                            <option value="Suami">
                                Suami
                            </option>

                            <option value="Anak">
                                Anak
                            </option>

                            <option value="Orang Tua">
                                Orang Tua
                            </option>

                            <option value="Saudara">
                                Saudara
                            </option>

                            <option value="Famili">
                                Famili
                            </option>

                            <option value="Lainnya">
                                Lainnya
                            </option>

                        </select>

                    </div>

                </div>

            </div>


            {{-- 2 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">2</span>

                    <span>
                        Berapa Nomor Induk Keluarga Anda?
                    </span>
                </div>

                <div class="form-group">

                    <input
                        type="text"
                        name="nomor_induk_keluarga"
                        class="form-input"
                        inputmode="numeric"
                        placeholder="Masukkan nomor induk keluarga"
                    >

                </div>

            </div>


            {{-- 3 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">3</span>

                    <span>
                        Berapa Nomor Kartu Keluarga Anda?
                    </span>
                </div>

                <div class="form-group">

                    <input
                        type="text"
                        name="no_kk"
                        class="form-input"
                        maxlength="16"
                        inputmode="numeric"
                        placeholder="Masukkan nomor KK"
                    >

                </div>

            </div>


            {{-- 4 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">4</span>

                    <span>
                        Berapa jumlah anggota keluarga Anda?
                    </span>
                </div>

                <div class="form-group">

                    <input
                        type="number"
                        name="jml_keluarga"
                        class="form-input"
                        min="1"
                        placeholder="Masukkan jumlah anggota keluarga"
                    >

                </div>

            </div>


            {{-- 5 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">5</span>

                    <span>
                        Provinsi tempat tinggal keluarga saat ini
                    </span>
                </div>

                <div class="form-group">

                    <select
                        name="provinsi"
                        id="provinsi"
                        class="form-select"
                    >

                        <option value="JAWA TIMUR" selected>
                            JAWA TIMUR
                        </option>

                    </select>

                </div>

            </div>


            {{-- 6 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">6</span>

                    <span>
                        Kabupaten/Kota tempat tinggal keluarga saat ini
                    </span>
                </div>

                <div class="form-group">

                    <select
                        name="daerah"
                        id="daerah"
                        class="form-select"
                    >

                        <option value="">
                            -- Pilih Kabupaten/Kota --
                        </option>

                        <option value="KOTA PASURUAN">
                            KOTA PASURUAN
                        </option>

                        <option value="KABUPATEN PASURUAN">
                            KABUPATEN PASURUAN
                        </option>

                    </select>

                </div>

            </div>


            {{-- 7 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">7</span>

                    <span>
                        Kecamatan tempat tinggal keluarga saat ini
                    </span>
                </div>

                <div class="form-group">

                    <select
                        name="kecamatan"
                        id="kecamatan"
                        class="form-select"
                    >

                        <option value="">
                            -- Pilih Kecamatan --
                        </option>

                        <option value="Bugul Kidul">
                            Bugul Kidul
                        </option>

                        <option value="Gadingrejo">
                            Gadingrejo
                        </option>

                        <option value="Panggungrejo">
                            Panggungrejo
                        </option>

                        <option value="Purworejo">
                            Purworejo
                        </option>

                    </select>

                </div>

            </div>


            {{-- 8 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">8</span>

                    <span>
                        Desa/Kelurahan tempat tinggal keluarga saat ini
                    </span>
                </div>

                <div class="form-group">

                    <select
                        name="kelurahan"
                        id="kelurahan"
                        class="form-select"
                        disabled
                    >

                        <option value="">
                            -- Pilih Kecamatan terlebih dahulu --
                        </option>

                    </select>

                </div>

            </div>


            {{-- 9 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">9</span>

                    <span>
                        Nomor Kode Pos
                    </span>
                </div>

                <div class="form-group">

                    <input
                        type="text"
                        name="kode_pos"
                        class="form-input"
                        maxlength="5"
                        inputmode="numeric"
                        placeholder="Masukkan kode pos"
                    >

                </div>

            </div>


            {{-- 10 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">10</span>

                    <span>
                        Satuan Lingkungan Setempat (RT/RW/Dusun dll)
                    </span>
                </div>

                <div class="form-group">

                    <input
                        type="text"
                        name="rt_rw"
                        id="rt_rw"
                        class="form-input"
                        placeholder="Contoh: RT 001 / RW 002"
                    >

                </div>

            </div>


            {{-- 11 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">11</span>

                    <span>
                        Alamat Lengkap Rumah/Tempat Tinggal Anda?
                    </span>
                </div>

                <div class="form-group">

                    <textarea
                        name="alamat_lengkap"
                        class="form-input"
                        rows="3"
                        placeholder="Masukkan alamat lengkap"
                    ></textarea>

                </div>

            </div>


            {{-- 12 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">12</span>

                    <span>
                        Apakah alamat tempat tinggal saat ini sesuai dengan
                        Kartu Keluarga?
                    </span>
                </div>

                <div class="form-group">

                    <div class="radio-group">

                        <label class="radio-option">

                            <input
                                type="radio"
                                name="is_alamat_sesuai"
                                value="1"
                                onchange="toggleAlasanTidakSesuai()"
                            >

                            <span class="radio-label">
                                Ya, Sesuai
                            </span>

                        </label>


                        <label class="radio-option">

                            <input
                                type="radio"
                                name="is_alamat_sesuai"
                                value="0"
                                onchange="toggleAlasanTidakSesuai()"
                            >

                            <span class="radio-label">
                                Tidak Sesuai
                            </span>

                        </label>

                    </div>


                    <div
                        id="alasanTidakSesuai"
                        class="alasan-tidak-sesuai"
                    >

                        <label class="form-label">
                            Alasan alamat tidak sesuai
                        </label>

                        <textarea
                            name="alasan_tidak_sesuai"
                            id="alasan_tidak_sesuai"
                            class="form-input"
                            rows="3"
                            placeholder="Masukkan alasan mengapa alamat tempat tinggal tidak sesuai dengan KK"
                        ></textarea>

                    </div>

                </div>

            </div>


            {{-- 13 --}}
            <div class="question">

                <div class="question-title">
                    <span class="question-number">13</span>

                    <span>
                        Titik lokasi (geotagging) tempat tinggal saat ini
                    </span>
                </div>

                <div class="map-wrapper">

                    <div id="map"></div>

                    <div class="location-info">
                        Pilih Kecamatan dan Kelurahan terlebih dahulu.
                        Peta akan menyesuaikan lokasi secara otomatis.
                        Marker juga dapat digeser secara manual.
                    </div>

                    <div
                        id="mapStatus"
                        class="map-status"
                    >
                        Lokasi awal belum ditentukan.
                    </div>

                    <input
                        type="text"
                        name="geotangging"
                        id="geotangging"
                        class="form-input coordinate-input"
                        placeholder="Koordinat akan muncul di sini"
                        readonly
                    >

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="form-footer">

                <div class="required-info">
                    * Pastikan data yang dimasukkan sudah benar.
                </div>

                <div class="button-group">

                    <button
                        type="reset"
                        class="btn btn-secondary"
                    >
                        Reset
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Simpan & Lanjut
                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>


<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =========================================================
       DATA KELURAHAN
    ========================================================= */

    const dataKelurahan = {

        "Bugul Kidul": [
            "Blandongan",
            "Kepel",
            "Tapaan",
            "Bakalan",
            "Krampyangan",
            "Bugul Kidul"
        ],

        "Gadingrejo": [
            "Karangketug",
            "Gentong",
            "Sebani",
            "Petahunan",
            "Bukir",
            "Randusari",
            "Krapyakrejo",
            "Gadingrejo"
        ],

        "Panggungrejo": [
            "Karanganyar",
            "Tambaan",
            "Trajeng",
            "Bangilan",
            "Kebonsari",
            "Mayangan",
            "Ngemplakrejo",
            "Petamanan",
            "Pekuncen",
            "Bugul Lor",
            "Kandangsapi",
            "Panggungrejo",
            "Mandaranrejo"
        ],

        "Purworejo": [
            "Pohjentrek",
            "Purworejo",
            "Sekarputih",
            "Wirogunan"
        ]

    };


    /* =========================================================
       ELEMENT
    ========================================================= */

    const provinsi =
        document.getElementById('provinsi');

    const daerah =
        document.getElementById('daerah');

    const kecamatan =
        document.getElementById('kecamatan');

    const kelurahan =
        document.getElementById('kelurahan');

    const rtRw =
        document.getElementById('rt_rw');

    const coordinateInput =
        document.getElementById('geotangging');

    const mapStatus =
        document.getElementById('mapStatus');


    /* =========================================================
       KELURAHAN BERDASARKAN KECAMATAN
    ========================================================= */

    kecamatan.addEventListener('change', function () {

        const value = this.value;

        kelurahan.innerHTML =
            '<option value="">-- Pilih Kelurahan --</option>';

        kelurahan.disabled = true;

        if (dataKelurahan[value]) {

            dataKelurahan[value].forEach(function (nama) {

                const option =
                    document.createElement('option');

                option.value = nama;
                option.textContent = nama;

                kelurahan.appendChild(option);

            });

            kelurahan.disabled = false;
        }

        updateMapFromAddress();
    });


    kelurahan.addEventListener('change', function () {
        updateMapFromAddress();
    });


    daerah.addEventListener('change', function () {
        updateMapFromAddress();
    });


    provinsi.addEventListener('change', function () {
        updateMapFromAddress();
    });


    rtRw.addEventListener('change', function () {
        updateMapFromAddress();
    });


    rtRw.addEventListener('blur', function () {
        updateMapFromAddress();
    });


    /* =========================================================
       ALASAN TIDAK SESUAI
    ========================================================= */

    window.toggleAlasanTidakSesuai = function () {

        const tidakSesuai =
            document.querySelector(
                'input[name="is_alamat_sesuai"][value="0"]'
            );

        const alasan =
            document.getElementById('alasanTidakSesuai');

        const textarea =
            document.getElementById('alasan_tidak_sesuai');

        if (tidakSesuai.checked) {

            alasan.classList.add('show');

            textarea.focus();

        } else {

            alasan.classList.remove('show');

            textarea.value = '';

        }

    };


    /* =========================================================
       MAP
    ========================================================= */

    const defaultLat = -7.6458;
    const defaultLng = 112.9075;

    const map = L.map('map').setView(
        [defaultLat, defaultLng],
        13
    );


    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }
    ).addTo(map);


    const marker = L.marker(
        [defaultLat, defaultLng],
        {
            draggable: true
        }
    ).addTo(map);


    function updateCoordinate(lat, lng) {

        coordinateInput.value =
            lat.toFixed(6) +
            ', ' +
            lng.toFixed(6);

    }


    updateCoordinate(
        defaultLat,
        defaultLng
    );


    /* =========================================================
       MARKER DIGESER
    ========================================================= */

    marker.on('dragend', function () {

        const position =
            marker.getLatLng();

        updateCoordinate(
            position.lat,
            position.lng
        );

        mapStatus.textContent =
            'Lokasi ditentukan secara manual dari marker.';

    });


    /* =========================================================
       KLIK PETA
    ========================================================= */

    map.on('click', function (e) {

        marker.setLatLng(e.latlng);

        updateCoordinate(
            e.latlng.lat,
            e.latlng.lng
        );

        mapStatus.textContent =
            'Lokasi ditentukan berdasarkan titik yang dipilih pada peta.';

    });


    /* =========================================================
       GEOCODING OPENSTREETMAP
    ========================================================= */

    let searchTimer = null;


    async function updateMapFromAddress() {

        const provinsiValue =
            provinsi.value.trim();

        const daerahValue =
            daerah.value.trim();

        const kecamatanValue =
            kecamatan.value.trim();

        const kelurahanValue =
            kelurahan.value.trim();

        const rtRwValue =
            rtRw.value.trim();

        /*
         * Minimal kecamatan harus dipilih
         */
        if (!kecamatanValue) {

            mapStatus.textContent =
                'Silakan pilih kecamatan terlebih dahulu.';

            return;
        }


        /*
         * Buat alamat pencarian
         */
        let queryParts = [];


        /*
         * RT/RW
         */
        if (rtRwValue) {
            queryParts.push(rtRwValue);
        }


        /*
         * Kelurahan
         */
        if (kelurahanValue) {
            queryParts.push(kelurahanValue);
        }


        /*
         * Kecamatan
         */
        if (kecamatanValue) {
            queryParts.push(kecamatanValue);
        }


        /*
         * Kota/Kabupaten
         */
        if (daerahValue) {
            queryParts.push(daerahValue);
        }


        /*
         * Provinsi
         */
        if (provinsiValue) {
            queryParts.push(provinsiValue);
        }


        queryParts.push('Indonesia');


        const query =
            queryParts.join(', ');


        mapStatus.textContent =
            'Mencari lokasi: ' + query;


        try {

            clearTimeout(searchTimer);


            searchTimer = setTimeout(
                async function () {

                    const url =
                        'https://nominatim.openstreetmap.org/search?' +
                        new URLSearchParams({

                            q: query,

                            format: 'json',

                            limit: 1,

                            countrycodes: 'id',

                            addressdetails: 1

                        });


                    const response =
                        await fetch(url, {
                            headers: {
                                'Accept':
                                    'application/json'
                            }
                        });


                    if (!response.ok) {
                        throw new Error(
                            'Gagal mengambil data lokasi.'
                        );
                    }


                    const data =
                        await response.json();


                    if (data.length > 0) {

                        const lat =
                            parseFloat(data[0].lat);

                        const lng =
                            parseFloat(data[0].lon);


                        /*
                         * Zoom berdasarkan kelengkapan alamat
                         */

                        let zoom = 13;

                        if (kecamatanValue) {
                            zoom = 14;
                        }

                        if (kelurahanValue) {
                            zoom = 16;
                        }

                        if (rtRwValue) {
                            zoom = 17;
                        }

                        map.setView(
                            [lat, lng],
                            zoom,
                            {
                                animate: true
                            }
                        );


                        marker.setLatLng(
                            [lat, lng]
                        );


                        updateCoordinate(
                            lat,
                            lng
                        );


                        mapStatus.textContent =
                            'Lokasi peta disesuaikan dengan alamat yang dipilih.';

                    } else {

                        mapStatus.textContent =
                            'Lokasi belum ditemukan secara otomatis. Silakan geser marker secara manual.';

                    }

                },
                700
            );

        } catch (error) {

            console.error(error);

            mapStatus.textContent =
                'Lokasi tidak dapat ditemukan. Silakan tentukan marker secara manual.';

        }

    }


    /* =========================================================
       RESET
    ========================================================= */

    const form =
        document.querySelector('form');


    form.addEventListener('reset', function () {

        setTimeout(function () {

            kelurahan.innerHTML =
                '<option value="">-- Pilih Kecamatan terlebih dahulu --</option>';

            kelurahan.disabled = true;


            map.setView(
                [defaultLat, defaultLng],
                13
            );


            marker.setLatLng(
                [defaultLat, defaultLng]
            );


            updateCoordinate(
                defaultLat,
                defaultLng
            );


            mapStatus.textContent =
                'Lokasi awal belum ditentukan.';

        }, 50);

    });

});
</script>

@endsection