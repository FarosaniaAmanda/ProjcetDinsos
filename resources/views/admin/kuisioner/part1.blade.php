@extends('admin.layouts.app')

@section('content')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
:root {
    --primary:#292D8F;
    --primary-dark:#222675;
    --primary-soft:#F0F1FF;
    --border:#E2E4EF;
    --text:#25283A;
    --muted:#777D91;
    --background:#F5F6FB;
    --white:#fff;
    --danger:#D64545;
}

.kuisioner-page,
.kuisioner-page * {
    box-sizing:border-box;
}

.kuisioner-page {
    width:100%;
    min-height:100vh;
    padding:14px 24px 40px;
    background:var(--background);
    color:var(--text);
    font-family:'Inter',sans-serif;
}

.kuisioner-container {
    width:100%;
    max-width:1200px;
    margin:0 auto;
}

.kuisioner-sticky {
    position: sticky;
    top: 97px;
    z-index: 1000;

    padding: 0 0 12px;

    background: var(--background);

    /* Pastikan isi yang scroll tidak terlihat menembus */
    isolation: isolate;
}

.kuisioner-sticky::before {
    content: "";
    position: absolute;

    top: -30px;
    left: 0;
    right: 0;

    height: 30px;

    background: var(--background);

    z-index: 0;
}

.kuisioner-sticky > * {
    position: relative;
    z-index: 1;
}

.kuisioner-header {
    background:linear-gradient(135deg,var(--primary-dark),var(--primary));
    border-radius:18px;
    padding:25px 28px;
    margin-bottom:14px;
    color:#fff;
    box-shadow:0 8px 24px rgba(41,45,143,.12);
}

.kuisioner-label {
    display:inline-flex;
    align-items:center;
    padding:6px 11px;
    border-radius:7px;
    background:rgba(255,255,255,.13);
    border:1px solid rgba(255,255,255,.16);
    font-size:11px;
    font-weight:700;
    letter-spacing:.4px;
    text-transform:uppercase;
    margin-bottom:10px;
}

.kuisioner-title {
    margin:0;
    font-size:25px;
    font-weight:800;
    line-height:1.3;
}

.kuisioner-subtitle {
    margin:7px 0 0;
    color:rgba(255,255,255,.78);
    font-size:13px;
    line-height:1.6;
}

/* =========================================================
   NAVIGATION PART
========================================================= */

.part-navigation {
    display:grid;
    grid-template-columns:repeat(5,1fr);
    gap:10px;
}

.part-item {
    display:flex;
    align-items:center;
    gap:10px;
    padding:13px 14px;
    border:1px solid var(--border);
    border-radius:12px;
    background:#fff;
    color:var(--muted);
    text-decoration:none !important;
    transition:.2s ease;
}

.part-item:hover {
    border-color:#BFC3E8;
    transform:translateY(-1px);
}

.part-item.active {
    background:var(--primary-soft);
    border-color:#C7CAEF;
    color:var(--primary);
}

.part-number {
    width:30px;
    height:30px;
    flex-shrink:0;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:9px;
    background:#F0F1F5;
    color:#656A7E;
    font-size:12px;
    font-weight:800;
}

.part-item.active .part-number {
    background:var(--primary);
    color:#fff;
}

.part-text {
    min-width:0;
}

.part-text strong {
    display:block;
    font-size:12px;
    font-weight:700;
    line-height:1.35;
}

.part-text small {
    display:none;
}

/* =========================================================
   ALERT
========================================================= */

.alert {
    padding:13px 16px;
    border-radius:11px;
    margin-bottom:18px;
    font-size:13px;
    font-weight:600;
}

.alert-warning {
    background:#FFF8E6;
    border:1px solid #F3DF9B;
    color:#846A16;
}

.alert-success {
    background:#ECF8F0;
    border:1px solid #BFE2C9;
    color:#267044;
}

/* =========================================================
   MAIN CARD
========================================================= */

.kuisioner-card {
    background:#fff;
    border:1px solid var(--border);
    border-radius:17px;
    box-shadow:0 5px 20px rgba(35,40,80,.05);
    overflow:hidden;
}

.part-header {
    padding:20px 24px;
    border-bottom:1px solid var(--border);
    background:#FBFBFE;
}

.part-header h2 {
    margin:0;
    font-size:18px;
    font-weight:800;
    color:var(--primary-dark);
}

.part-header p {
    margin:5px 0 0;
    color:var(--muted);
    font-size:12px;
}

.form-content {
    padding:25px;
}

.question {
    padding:21px 0;
    border-bottom:1px solid #ECEEF5;
}

.question:first-child {
    padding-top:0;
}

.question:last-child {
    border-bottom:0;
}

.question-title {
    display:flex;
    gap:11px;
    margin-bottom:14px;
    font-size:14px;
    line-height:1.6;
    font-weight:700;
    color:var(--text);
}

.question-number {
    width:27px;
    height:27px;
    flex-shrink:0;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:8px;
    background:var(--primary-soft);
    color:var(--primary);
    font-size:11px;
    font-weight:800;
}

.form-group {
    margin-bottom:17px;
}

.form-group:last-child {
    margin-bottom:0;
}

.form-label {
    display:block;
    margin-bottom:7px;
    font-size:12px;
    font-weight:700;
    color:#4C5164;
}

.required {
    color:var(--danger);
}

.form-input,
.form-select,
textarea.form-input {
    width:100%;
    padding:11px 13px;
    border:1px solid #D9DCE8;
    border-radius:9px;
    background:#fff;
    color:var(--text);
    font-family:inherit;
    font-size:13px;
    outline:none;
    transition:.2s ease;
}

.form-input:focus,
.form-select:focus {
    border-color:var(--primary);
    box-shadow:0 0 0 3px rgba(41,45,143,.08);
}

.form-input::placeholder {
    color:#A2A6B5;
}

.form-select {
    cursor:pointer;
}

textarea.form-input {
    min-height:95px;
    resize:vertical;
}

.form-grid {
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:16px;
}

.radio-group {
    display:flex;
    gap:18px;
    flex-wrap:wrap;
}

.radio-option {
    display:flex;
    align-items:center;
    gap:7px;
    font-size:13px;
    color:#505568;
    cursor:pointer;
}

.radio-option input {
    width:16px;
    height:16px;
    accent-color:var(--primary);
}

/* =========================================================
   MAP
========================================================= */

.map-wrapper {
    margin-top: 14px;
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    background: #F7F8FC;
}

#map {
    width: 100%;
    height: 330px;
}

.location-info {
    padding: 13px 15px;
    background: #FAFAFD;
    border-top: 1px solid var(--border);
}

.location-info p {
    margin: 0;
    font-size: 11px;
    line-height: 1.6;
    color: var(--muted);
}

.coordinate-input {
    margin-top: 9px;
}

.map-status {
    margin-top: 9px;
    font-size: 11px;
    color: var(--muted);
}

#map .leaflet-control-zoom {
    display: none !important;
}

#map .leaflet-control-attribution {
    display: none !important;
}

/* =========================================================
   FOOTER BUTTON
========================================================= */

.form-footer {
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    padding:20px 25px;
    border-top:1px solid var(--border);
    background:#FBFBFD;
}

.btn-reset,
.btn-next {
    display:inline-flex;
    align-items:center;
    justify-content:center;
    border-radius:9px;
    font-family:inherit;
    font-size:13px;
    font-weight:700;
    text-decoration:none !important;
    transition:.2s ease;
}

.btn-reset {
    min-width:105px;
    padding:11px 19px;
    border:1px solid #D9DCE8;
    background:#fff;
    color:#656A7C;
    box-shadow:0 4px 12px rgba(34,38,117,.12);
}

.btn-reset:hover {
    background:#F5F6FB;
    border-color:#C8CBDC;
    color:var(--primary) !important;
}

.btn-next {
    gap:9px;
    min-width:145px;
    padding:11px 20px;
    border:0;
    background:var(--primary);
    color:#fff;
    cursor:pointer;
}

.btn-next:hover {
    background:var(--primary-dark);
    transform:translateY(-1px);
    box-shadow:0 6px 15px rgba(41,45,143,.18);
}

/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width:900px) {

    .kuisioner-page {
        padding:12px 16px 30px;
    }

    .part-navigation {
        grid-template-columns:repeat(2,1fr);
    }

    .form-grid {
        grid-template-columns:1fr;
    }
}

@media (max-width:600px) {

    .kuisioner-sticky {
        top: 64px;
        padding-bottom: 8px;
    }

    .kuisioner-header {
        padding: 15px 16px;
        margin-bottom: 8px;
        border-radius: 13px;
    }

    .kuisioner-label {
        padding: 4px 8px;
        margin-bottom: 6px;
        font-size: 9px;
    }

    .kuisioner-title {
        font-size: 18px;
        line-height: 1.25;
    }

    .kuisioner-subtitle {
        margin-top: 5px;
        font-size: 11px;
        line-height: 1.45;
    }

    /*
    ==========================================
    NAVIGASI PART MOBILE
    HANYA MENAMPILKAN ANGKA 1 - 5
    ==========================================
    */

    .part-navigation {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 5px;
        width: 100%;
    }

    .part-item {
        height: 42px;
        min-width: 0;
        padding: 4px;
        justify-content: center;
        border-radius: 9px;
    }

    .part-number {
        width: 29px;
        height: 29px;
        border-radius: 8px;
        font-size: 11px;
    }

    .part-text {
        display: none;
    }

    /*
    ==========================================
    FORM
    ==========================================
    */

    .form-content {
        padding: 18px;
    }

    .form-footer {
        flex-direction: column-reverse;
        align-items: stretch;
    }

    .btn-reset,
    .btn-next {
        width: 100%;
    }

    .family-picker-header,
    .family-search-wrap,
    .family-list-wrap {
        padding-left: 16px;
        padding-right: 16px;
    }

    .family-selected {
        margin-left: 16px;
        margin-right: 16px;
    }

    .family-pagination {
        padding-left: 16px;
        padding-right: 16px;
    }

    .family-row {
        padding: 13px 12px;
        gap: 10px;
    }

    .family-topline {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }

    .family-name {
        white-space: normal;
    }

    #map {
        height: 280px;
    }
}

@media (max-width: 768px) {

    /* HEADER UTAMA */
    header,
    .top-header,
    .admin-header,
    .main-header {
        position: sticky !important;
        top: 0 !important;
        z-index: 1000 !important;
        background: #ffffff !important;
    }

    /* AREA STICKY KUISIIONER */
    .kuisioner-sticky {
        position: sticky !important;
        top: 80px !important;
        z-index: 900 !important;

        background: var(--background) !important;

        padding-top: 12px !important;
        padding-bottom: 12px !important;

        /* PENTING */
        isolation: isolate !important;
        overflow: visible !important;
    }

    /*
    =========================================================
    PENUTUP AREA ATAS

    Ini mencegah isi form yang sedang di-scroll
    muncul di sela-sela header dan kuisioner.
    =========================================================
    */
    .kuisioner-sticky::before {
        content: "" !important;

        position: absolute !important;

        left: 0 !important;
        right: 0 !important;

        top: -100px !important;
        height: 100px !important;

        background: var(--background) !important;

        z-index: -1 !important;
    }

    .kuisioner-sticky > * {
        position: relative !important;
        z-index: 2 !important;
    }

    /* Card step 1-5 satu baris */
    .steps,
    .part-tabs,
    .kuisioner-steps {
        display: grid !important;
        grid-template-columns: repeat(5, 1fr) !important;
        gap: 6px !important;
        width: 100% !important;
    }

    /* Setiap card step */
    .step,
    .part-tab,
    .step-card {
        min-width: 0 !important;
        width: 100% !important;
        padding: 8px 4px !important;
        height: 54px !important;

        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    /* Hanya angka yang tampil di HP */
    .step span:not(.step-number),
    .part-tab span:not(.step-number),
    .step-title,
    .part-title,
    .step-label {
        display: none !important;
    }

    /* Nomor 1-5 */
    .step-number {
        margin: 0 !important;
        font-size: 14px !important;
        font-weight: 700 !important;
    }

    /* Hero biru tetap rapi */
    .kuisioner-hero {
        margin-top: 0 !important;
    }

    /* Supaya isi tidak tertutup sticky */
    .kuisioner-content,
    .form-content {
        scroll-margin-top: 150px !important;
    }
}

</style>

<div class="kuisioner-page">

    <div class="kuisioner-container">

        {{-- =====================================================
             HEADER + NAVIGASI
        ====================================================== --}}

        <div class="kuisioner-sticky">

            <div class="kuisioner-header">

                <div class="kuisioner-label">
                    FORM KUISIONER
                </div>

                <h1 class="kuisioner-title">
                    Kuisioner Pendataan Keluarga
                </h1>

                <p class="kuisioner-subtitle">
                    Silakan lengkapi data keluarga dengan benar dan sesuai kondisi sebenarnya.
                </p>

            </div>


            <div class="part-navigation">

                {{-- PART 1 --}}

                <a
                    href="{{ route('kuisioner.part1') }}"
                    class="part-item active"
                >

                    <div class="part-number">
                        1
                    </div>

                    <div class="part-text">
                        <strong>
                            Keluarga
                        </strong>
                    </div>

                </a>


                {{-- PART 2 --}}

                <a
                    href="{{ route('kuisioner.part2') }}"
                    class="part-item"
                >

                    <div class="part-number">
                        2
                    </div>

                    <div class="part-text">
                        <strong>
                            Kondisi Rumah
                        </strong>
                    </div>

                </a>


                {{-- PART 3 --}}

                <a
                    href="{{ route('kuisioner.part3') }}"
                    class="part-item"
                >

                    <div class="part-number">
                        3
                    </div>

                    <div class="part-text">
                        <strong>
                            Keuangan Keluarga
                        </strong>
                    </div>

                </a>


                {{-- PART 4 --}}

                <a
                    href="{{ route('kuisioner.part4') }}"
                    class="part-item"
                >

                    <div class="part-number">
                        4
                    </div>

                    <div class="part-text">
                        <strong>
                            Aset Keluarga
                        </strong>
                    </div>

                </a>


                {{-- PART 5 --}}

                <a
                    href="{{ route('kuisioner.part5') }}"
                    class="part-item"
                >

                    <div class="part-number">
                        5
                    </div>

                    <div class="part-text">
                        <strong>
                            Anggota Keluarga
                        </strong>
                    </div>

                </a>

            </div>

        </div>


        {{-- =====================================================
             ALERT
        ====================================================== --}}

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


        {{-- =====================================================
             MAIN FORM
        ====================================================== --}}

        <div
            class="kuisioner-card"
            id="form-keluarga"
        >

            <div class="part-header">

                <h2>
                    Bagian 1 — Data Keluarga
                </h2>

                <p>
                    Isi identitas dan alamat keluarga dengan lengkap.
                </p>

            </div>


            <form
                action="{{ route('kuisioner.part1.store') }}"
                method="POST"
            >

                @csrf


                <div class="form-content">

                    {{-- =================================================
                         PERTANYAAN 1
                    ================================================== --}}

                    <div class="question">

                        <div class="question-title">

                            <span class="question-number">
                                1
                            </span>

                            <span>
                                Nomor Induk Kependudukan (NIK)
                                <span class="required">*</span>
                            </span>

                        </div>


                        <div class="form-group">

                            <label class="form-label">
                                NIK
                            </label>

                            <input
                                type="text"
                                name="nik"
                                class="form-input"
                                value="{{ old('nik', $data->nik ?? '') }}"
                                placeholder="Masukkan NIK"
                            >

                        </div>

                    </div>


                    {{-- =================================================
                         PERTANYAAN 2
                    ================================================== --}}

                    <div class="question">

                        <div class="question-title">

                            <span class="question-number">
                                2
                            </span>

                            <span>
                                Nama Kepala Keluarga
                                <span class="required">*</span>
                            </span>

                        </div>


                        <div class="form-group">

                            <label class="form-label">
                                Nama Lengkap
                            </label>

                            <input
                                type="text"
                                name="nama_kepala_keluarga"
                                class="form-input"
                                value="{{ old('nama_kepala_keluarga', $data->nama_kepala_keluarga ?? '') }}"
                                placeholder="Masukkan nama kepala keluarga"
                            >

                        </div>

                    </div>


                    {{-- =================================================
                         PERTANYAAN 3
                    ================================================== --}}

                    <div class="question">

                        <div class="question-title">

                            <span class="question-number">
                                3
                            </span>

                            <span>
                                Status dalam keluarga
                                <span class="required">*</span>
                            </span>

                        </div>


                        <div class="form-group">

                            <select
                                name="status_keluarga"
                                class="form-select"
                            >

                                <option value="">
                                    -- Pilih Status --
                                </option>

                                @foreach([
                                    'Kepala Keluarga',
                                    'Istri',
                                    'Suami',
                                    'Anak',
                                    'Orang Tua',
                                    'Saudara',
                                    'Lainnya'
                                ] as $status)

                                    <option
                                        value="{{ $status }}"
                                        {{ old('status_keluarga', $data->status_keluarga ?? '') == $status ? 'selected' : '' }}
                                    >
                                        {{ $status }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>


                    {{-- =================================================
                         PERTANYAAN 4
                    ================================================== --}}

                    <div class="question">

                        <div class="question-title">

                            <span class="question-number">
                                4
                            </span>

                            <span>
                                Nomor Induk Keluarga
                            </span>

                        </div>


                        <div class="form-group">

                            <input
                                type="text"
                                name="nomor_induk_keluarga"
                                class="form-input"
                                value="{{ old('nomor_induk_keluarga', $data->nomor_induk_keluarga ?? '') }}"
                                placeholder="Masukkan nomor induk keluarga"
                            >

                        </div>

                    </div>


                    {{-- =================================================
                         PERTANYAAN 5
                    ================================================== --}}

                    <div class="question">

                        <div class="question-title">

                            <span class="question-number">
                                5
                            </span>

                            <span>
                                Nomor Kartu Keluarga (KK)
                                <span class="required">*</span>
                            </span>

                        </div>


                        <div class="form-group">

                            <input
                                type="text"
                                name="no_kk"
                                class="form-input"
                                value="{{ old('no_kk', $data->no_kk ?? '') }}"
                                placeholder="Masukkan nomor KK"
                            >

                        </div>

                    </div>


                    {{-- =================================================
                         PERTANYAAN 6
                    ================================================== --}}

                    <div class="question">

                        <div class="question-title">

                            <span class="question-number">
                                6
                            </span>

                            <span>
                                Jumlah anggota keluarga
                            </span>

                        </div>


                        <div class="form-group">

                            <input
                                type="number"
                                name="jml_keluarga"
                                class="form-input"
                                min="1"
                                value="{{ old('jml_keluarga', $data->jml_keluarga ?? '') }}"
                                placeholder="Masukkan jumlah anggota keluarga"
                            >

                        </div>

                    </div>


                    {{-- =================================================
                         PERTANYAAN 7
                    ================================================== --}}

                    <div class="question">

                        <div class="question-title">

                            <span class="question-number">
                                7
                            </span>

                            <span>
                                Provinsi
                            </span>

                        </div>


                        <div class="form-group">

                            <select
                                name="provinsi"
                                id="provinsi"
                                class="form-select"
                            >

                                <option
                                    value="JAWA TIMUR"
                                    {{ old('provinsi', $data->provinsi ?? 'JAWA TIMUR') == 'JAWA TIMUR' ? 'selected' : '' }}
                                >
                                    JAWA TIMUR
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- =================================================
                         PERTANYAAN 8
                    ================================================== --}}

                    <div class="question">

                        <div class="question-title">

                            <span class="question-number">
                                8
                            </span>

                            <span>
                                Kabupaten / Kota
                            </span>

                        </div>


                        <div class="form-group">

                            <select
                                name="daerah"
                                id="daerah"
                                class="form-select"
                            >

                                <option
                                    value="KOTA PASURUAN"
                                    {{ old('daerah', $data->daerah ?? 'KOTA PASURUAN') == 'KOTA PASURUAN' ? 'selected' : '' }}
                                >
                                    KOTA PASURUAN
                                </option>

                                <option
                                    value="KABUPATEN PASURUAN"
                                    {{ old('daerah', $data->daerah ?? '') == 'KABUPATEN PASURUAN' ? 'selected' : '' }}
                                >
                                    KABUPATEN PASURUAN
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- =================================================
                         PERTANYAAN 9
                    ================================================== --}}

                    <div class="question">

                        <div class="question-title">

                            <span class="question-number">
                                9
                            </span>

                            <span>
                                Kecamatan
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

                                @foreach([
                                    'BUGUL KIDUL',
                                    'GADINGREJO',
                                    'PANGGUNGREJO',
                                    'PURWOREJO'
                                ] as $kec)

                                    <option
                                        value="{{ $kec }}"
                                        {{ old('kecamatan', $data->kecamatan ?? '') == $kec ? 'selected' : '' }}
                                    >
                                        {{ $kec }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                    </div>


                    {{-- =================================================
                         PERTANYAAN 10
                    ================================================== --}}

                    <div class="question">

                        <div class="question-title">

                            <span class="question-number">
                                10
                            </span>

                            <span>
                                Kelurahan
                            </span>

                        </div>


                        <div class="form-group">

                            <select
                                name="kelurahan"
                                id="kelurahan"
                                class="form-select"
                            >

                                <option value="">
                                    -- Pilih Kelurahan --
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- =================================================
                         PERTANYAAN 11
                    ================================================== --}}

                    <div class="question">

                        <div class="question-title">

                            <span class="question-number">
                                11
                            </span>

                            <span>
                                Kode Pos
                            </span>

                        </div>


                        <div class="form-group">

                            <input
                                type="text"
                                name="kode_pos"
                                id="kode_pos"
                                class="form-input"
                                value="{{ old('kode_pos', $data->kode_pos ?? '') }}"
                                placeholder="Masukkan kode pos"
                            >

                        </div>

                    </div>


                    {{-- =================================================
                         PERTANYAAN 12
                    ================================================== --}}

                    <div class="question">

                        <div class="question-title">

                            <span class="question-number">
                                12
                            </span>

                            <span>
                                RT / RW
                            </span>

                        </div>


                        <div class="form-group">

                            <input
                                type="text"
                                name="rt_rw"
                                id="rt_rw"
                                class="form-input"
                                value="{{ old('rt_rw', $data->rt_rw ?? '') }}"
                                placeholder="Contoh: 001/002"
                            >

                        </div>

                    </div>


                    {{-- =================================================
                         PERTANYAAN 13
                    ================================================== --}}

                    <div class="question">

                        <div class="question-title">

                            <span class="question-number">
                                13
                            </span>

                            <span>
                                Alamat lengkap
                            </span>

                        </div>


                        <div class="form-group">

                            <textarea
                                name="alamat_lengkap"
                                id="alamat_lengkap"
                                class="form-input"
                                placeholder="Masukkan alamat lengkap"
                            >{{ old('alamat_lengkap', $data->alamat_lengkap ?? '') }}</textarea>

                        </div>

                    </div>


                    {{-- =================================================
                         PERTANYAAN 14
                    ================================================== --}}

                    <div class="question">

                        <div class="question-title">

                            <span class="question-number">
                                14
                            </span>

                            <span>
                                Detail alamat rumah
                            </span>

                        </div>


                        <div class="form-grid">

                            <div class="form-group">

                                <label class="form-label">
                                    Nama Jalan
                                </label>

                                <input
                                    type="text"
                                    name="jalan_rumah"
                                    id="jalan_rumah"
                                    class="form-input"
                                    value="{{ old('jalan_rumah', $data->jalan_rumah ?? '') }}"
                                    placeholder="Nama jalan"
                                >

                            </div>


                            <div class="form-group">

                                <label class="form-label">
                                    Nomor Rumah
                                </label>

                                <input
                                    type="text"
                                    name="nomor_rumah"
                                    id="nomor_rumah"
                                    class="form-input"
                                    value="{{ old('nomor_rumah', $data->nomor_rumah ?? '') }}"
                                    placeholder="Nomor rumah"
                                >

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                         PERTANYAAN 15
                    ================================================== --}}

                    <div class="question">

                        <div class="question-title">

                            <span class="question-number">
                                15
                            </span>

                            <span>
                                Apakah alamat sesuai dengan tempat tinggal saat ini?
                            </span>

                        </div>


                        <div class="form-group">

                            <div class="radio-group">

                                <label class="radio-option">

                                    <input
                                        type="radio"
                                        name="is_alamat_sesuai"
                                        value="1"
                                        {{ old('is_alamat_sesuai', $data->is_alamat_sesuai ?? '') == '1' ? 'checked' : '' }}
                                        onchange="toggleAlasanTidakSesuai()"
                                    >

                                    Ya

                                </label>


                                <label class="radio-option">

                                    <input
                                        type="radio"
                                        name="is_alamat_sesuai"
                                        value="0"
                                        {{ old('is_alamat_sesuai', $data->is_alamat_sesuai ?? '') == '0' ? 'checked' : '' }}
                                        onchange="toggleAlasanTidakSesuai()"
                                    >

                                    Tidak

                                </label>

                            </div>

                        </div>


                        <div
                            class="form-group"
                            id="alasan-tidak-sesuai-wrapper"
                            style="display:none;"
                        >

                            <label class="form-label">
                                Alasan tidak sesuai
                            </label>

                            <textarea
                                name="alasan_tidak_sesuai"
                                class="form-input"
                                placeholder="Jelaskan alasan alamat tidak sesuai"
                            >{{ old('alasan_tidak_sesuai', $data->alasan_tidak_sesuai ?? '') }}</textarea>

                        </div>

                    </div>


                    {{-- =================================================
                         GEOTAGGING
                    ================================================== --}}

                    <div class="question">

                        <div class="question-title">

<<<<<<< Updated upstream
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
=======
                            <span class="question-number">
                                GPS
>>>>>>> Stashed changes
                            </span>

                            <span>
                                Geotagging lokasi rumah
                            </span>

                        </div>


                        <div class="form-group">

                            <label class="form-label">
                                Koordinat lokasi
                            </label>

                            <input
                                type="text"
                                name="geotangging"
                                id="geotangging"
                                class="form-input coordinate-input"
                                value="{{ old('geotangging', $data->geotangging ?? '') }}"
                                placeholder="Klik lokasi pada peta"
                                readonly
                            >

                        </div>


                        <div class="map-wrapper">

                            <div id="map"></div>

                            <div class="location-info">

                                <p>
                                    Klik pada peta atau geser marker untuk menentukan lokasi rumah.
                                </p>

                                <div
                                    id="map-status"
                                    class="map-status"
                                >
                                    Lokasi belum dipilih.
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     FOOTER
                ================================================== --}}

<<<<<<< Updated upstream
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
=======
                <div class="form-footer">

                    <a
                        href="{{ route('kuisioner.index') }}"
                        class="btn-reset"
>>>>>>> Stashed changes
                    >
                        Batal
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

</div>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>


<script>

/* =========================================================
   DATA KELURAHAN
========================================================= */

const dataKelurahan =
    @json($dataKelurahan ?? []);


<<<<<<< Updated upstream
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
=======
const kecamatanSelect =
    document.getElementById(
        'kecamatan'
>>>>>>> Stashed changes
    );


const kelurahanSelect =
    document.getElementById(
        'kelurahan'
    );


const savedKecamatan =
    @json(
        old(
            'kecamatan',
            $data->kecamatan ?? ''
        )
    );


const savedKelurahan =
    @json(
        old(
            'kelurahan',
            $data->kelurahan ?? ''
        )
    );


function loadKelurahan(
    kecamatan,
    selected = ''
) {

    kelurahanSelect.innerHTML = '';


    const defaultOption =
        document.createElement(
            'option'
        );


    defaultOption.value = '';


    defaultOption.textContent =
        '-- Pilih Kelurahan --';


    kelurahanSelect.appendChild(
        defaultOption
    );


    if (!kecamatan) {
        return;
    }


    const daftar =
        dataKelurahan[kecamatan] || [];


    daftar.forEach(
        function(kelurahan) {

            const option =
                document.createElement(
                    'option'
                );


            option.value =
                kelurahan;


            option.textContent =
                kelurahan;


            if (
                kelurahan === selected
            ) {

                option.selected =
                    true;

            }


            kelurahanSelect.appendChild(
                option
            );

        }
    );

}


kecamatanSelect.addEventListener(
    'change',
    function() {

        loadKelurahan(
            this.value
        );

    }
);


if (savedKecamatan) {

    loadKelurahan(
        savedKecamatan,
        savedKelurahan
    );

}


/* =========================================================
   ALASAN ALAMAT
========================================================= */

window.toggleAlasanTidakSesuai =
    function() {

        const selected =
            document.querySelector(
                'input[name="is_alamat_sesuai"]:checked'
            );


        const wrapper =
            document.getElementById(
                'alasan-tidak-sesuai-wrapper'
            );


        if (
            !selected ||
            selected.value !== '0'
        ) {

            wrapper.style.display =
                'none';

            return;

        }


        wrapper.style.display =
            'block';

    };


toggleAlasanTidakSesuai();


/* =========================================================
   MAP
========================================================= */

const defaultLat =
    -7.6453;


const defaultLng =
    112.9068;


const savedCoordinate =
    @json(
        old(
            'geotangging',
            $data->geotangging ?? ''
        )
    );


let map;

let marker;


if (
    savedCoordinate &&
    savedCoordinate.includes(',')
) {

    const parts =
        savedCoordinate.split(',');


    const lat =
        parseFloat(
            parts[0]
        );


    const lng =
        parseFloat(
            parts[1]
        );


    if (
        !isNaN(lat) &&
        !isNaN(lng)
    ) {

        map =
            L.map(
                'map'
            ).setView(
                [
                    lat,
                    lng
                ],
                16
            );


        marker =
            L.marker(
                [
                    lat,
                    lng
                ],
                {
                    draggable:true
                }
            ).addTo(
                map
            );

    }

}


if (!map) {

    map =
        L.map(
            'map'
        ).setView(
            [
                defaultLat,
                defaultLng
            ],
            13
        );


    marker =
        L.marker(
            [
                defaultLat,
                defaultLng
            ],
            {
                draggable:true
            }
        ).addTo(
            map
        );

}


L.tileLayer(
    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    {
        maxZoom:19,

        attribution:
            '&copy; OpenStreetMap'
    }
).addTo(
    map
);


function updateCoordinate(
    lat,
    lng
) {

    const value =
        lat.toFixed(6) +
        ',' +
        lng.toFixed(6);


    document.getElementById(
        'geotangging'
    ).value =
        value;


    document.getElementById(
        'map-status'
    ).textContent =
        'Lokasi dipilih: ' +
        value;

}


marker.on(
    'dragend',
    function(event) {

        const position =
            event.target.getLatLng();


        updateCoordinate(
            position.lat,
            position.lng
        );

    }
);


map.on(
    'click',
    function(event) {

        marker.setLatLng(
            event.latlng
        );


        updateCoordinate(
            event.latlng.lat,
            event.latlng.lng
        );

    }
);


if (
    savedCoordinate &&
    savedCoordinate.includes(',')
) {

    const parts =
        savedCoordinate.split(',');


<<<<<<< Updated upstream
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
=======
    const lat =
        parseFloat(
            parts[0]
        );


    const lng =
        parseFloat(
            parts[1]
        );
>>>>>>> Stashed changes


    if (
        !isNaN(lat) &&
        !isNaN(lng)
    ) {

        updateCoordinate(
            lat,
            lng
        );

    }

}


/* =========================================================
   GEOCODING
========================================================= */

const addressFields = [

    'provinsi',
    'daerah',
    'kecamatan',
    'kelurahan',
    'kode_pos',
    'rt_rw',
    'alamat_lengkap',
    'jalan_rumah',
    'nomor_rumah'

];


addressFields.forEach(
    function(id) {

        const element =
            document.getElementById(
                id
            );


        if (!element) {
            return;
        }


        element.addEventListener(
            'change',
            updateMapFromAddress
        );

    }
);


async function updateMapFromAddress() {

<<<<<<< Updated upstream
        /*
         * Kelurahan
         */
        if (kelurahanValue) {
            queryParts.push(kelurahanValue);
        }
=======
    const provinsi =
        document.getElementById(
            'provinsi'
        )?.value || '';


    const daerah =
        document.getElementById(
            'daerah'
        )?.value || '';


    const kecamatan =
        document.getElementById(
            'kecamatan'
        )?.value || '';


    const kelurahan =
        document.getElementById(
            'kelurahan'
        )?.value || '';


    const kodePos =
        document.getElementById(
            'kode_pos'
        )?.value || '';


    const rtRw =
        document.getElementById(
            'rt_rw'
        )?.value || '';
>>>>>>> Stashed changes


    const alamat =
        document.getElementById(
            'alamat_lengkap'
        )?.value || '';


    const jalan =
        document.getElementById(
            'jalan_rumah'
        )?.value || '';


    const nomorRumah =
        document.getElementById(
            'nomor_rumah'
        )?.value || '';


    const query = [

        alamat,

        rtRw,

        jalan &&
        nomorRumah
            ? jalan + ' ' + nomorRumah
            : jalan,

        kelurahan,

        kecamatan,

        daerah,

        provinsi,

        kodePos,

        'Indonesia'

    ]
    .filter(Boolean)
    .join(', ');

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

    if (!query) {
        return;
    }


    try {

        const response =
            await fetch(

                'https://nominatim.openstreetmap.org/search?' +

                new URLSearchParams(
                    {
                        q:query,

                        format:'json',

                        limit:'1',

                        countrycodes:'id',

                        addressdetails:'1'
                    }
                )

            );


        const results =
            await response.json();


        if (!results.length) {
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
            [
                lat,
                lng
            ],
            16
        );


        marker.setLatLng(
            [
                lat,
                lng
            ]
        );


        updateCoordinate(
            lat,
            lng
        );


    } catch (error) {

        console.error(
            'Gagal mencari lokasi:',
            error
        );

    }

}


setTimeout(
    function() {

        map.invalidateSize();

    },
    300
);

</script>

@endsection