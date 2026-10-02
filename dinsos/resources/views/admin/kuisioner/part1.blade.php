@extends('admin.kuisioner.layout')

@section('kuisioner-content')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --primary: #292D8F;
        --primary-dark: #222675;
        --primary-soft: #F0F1FF;
        --border: #E2E4EF;
        --text: #25283A;
        --muted: #777D91;
        --background: #F5F6FB;
        --white: #fff;
        --danger: #D64545;
    }

    /* =========================================================
       DASAR PART 1
       ========================================================= */

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
        padding: 15px 18px;
        border-radius: 11px;
        margin-bottom: 18px;
        font-size: 15px;
        font-weight: 600;
        line-height: 1.5;
    }

    .alert-warning {
        background: #FFF8E6;
        border: 1px solid #F3DF9B;
        color: #846A16;
    }

    .alert-success {
        background: #ECF8F0;
        border: 1px solid #BFE2C9;
        color: #267044;
    }

    /* =========================================================
       MAIN CARD
       ========================================================= */

    .kuisioner-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 17px;
        box-shadow: 0 5px 20px rgba(35, 40, 80, .05);
        overflow: hidden;
    }

    /* =========================================================
       HEADER PART
       ========================================================= */

    .part-header {
        padding: 24px 26px;
        border-bottom: 1px solid var(--border);
        background: #FBFBFE;
    }

    .part-header h2 {
        margin: 0;
        font-size: 22px;
        font-weight: 800;
        color: var(--primary-dark);
        line-height: 1.4;
    }

    .part-header p {
        margin: 7px 0 0;
        color: var(--muted);
        font-size: 15px;
        line-height: 1.6;
    }

    /* =========================================================
       FAMILY PICKER
       ========================================================= */

    .family-picker {
        background: #fff;
    }

    .family-picker-header {
        padding: 24px 26px 14px;
    }

    .family-picker-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 800;
        color: var(--primary-dark);
        line-height: 1.4;
    }

    .family-picker-header p {
        margin: 7px 0 0;
        color: var(--muted);
        font-size: 15px;
        line-height: 1.6;
    }

    /* =========================================================
       SEARCH
       ========================================================= */

    .family-search {
        padding: 0 26px 18px;
    }

    .family-search-box {
        position: relative;
    }

    .family-search-input {
        width: 100%;
        padding: 13px 15px 13px 44px;
        border: 1px solid #D9DCE8;
        border-radius: 9px;
        background: #fff;
        color: var(--text);
        font-family: inherit;
        font-size: 16px;
        line-height: 1.5;
        outline: none;
        transition: .2s ease;
    }

    .family-search-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(41, 45, 143, .08);
    }

    .family-search-input::placeholder {
        color: #A2A6B5;
    }

    .family-search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #8A8FA2;
        font-size: 17px;
        pointer-events: none;
    }

    .family-search-empty {
        display: none;
        text-align: center;
        padding: 25px;
        color: var(--muted);
        font-size: 15px;
    }

    /* =========================================================
       FAMILY TABLE
       ========================================================= */

    .family-table-wrap {
        padding: 0 26px 26px;
        overflow-x: auto;
    }

    .family-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
    }

    .family-table th {
        padding: 15px 17px;
        background: #F7F8FC;
        border-bottom: 1px solid var(--border);
        color: #4C5164;
        font-size: 15px;
        font-weight: 800;
        text-align: left;
        white-space: nowrap;
    }

    .family-table td {
        padding: 16px 17px;
        border-bottom: 1px solid #ECEEF5;
        color: var(--text);
        font-size: 16px;
        vertical-align: middle;
    }

    .family-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .family-table tbody tr:hover {
        background: #FAFAFE;
    }

    .family-name {
        font-weight: 700;
        color: var(--text);
        line-height: 1.6;
    }

    .family-address {
        color: #656A7E;
        line-height: 1.6;
        font-size: 15px;
    }

    .family-code {
        margin-top: 3px;
        font-size: 12px;
        color: var(--muted);
        font-weight: 500;
    }

    /* =========================================================
       BUTTON MULAI
       ========================================================= */

    .btn-start {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 82px;
        padding: 10px 18px;
        border: 0;
        border-radius: 8px;
        background: var(--primary);
        color: #fff;
        font-family: inherit;
        font-size: 15px;
        font-weight: 700;
        text-decoration: none !important;
        cursor: pointer;
        transition: .2s ease;
    }

    .btn-start:hover {
        background: var(--primary-dark);
        color: #fff !important;
        transform: translateY(-1px);
        box-shadow: 0 5px 12px rgba(41, 45, 143, .18);
    }

    /* =========================================================
       KELUARGA TERPILIH
       ========================================================= */

    .family-selected {
        margin: 0 26px 22px;
        padding: 15px 17px;
        border: 1px solid #C7CAEF;
        border-radius: 10px;
        background: var(--primary-soft);
        color: var(--primary-dark);
        font-size: 15px;
        font-weight: 600;
        line-height: 1.5;
    }

    .family-selected strong {
        font-weight: 800;
    }

    /* =========================================================
       FORM
       ========================================================= */

    #form-keluarga {
        display: none;
    }

    #form-keluarga.show {
        display: block;
    }

    .form-content {
        padding: 28px;
    }

    .question {
        padding: 23px 0;
        border-bottom: 1px solid #ECEEF5;
    }

    .question:first-child {
        padding-top: 0;
    }

    .question:last-child {
        border-bottom: 0;
    }

    .question-title {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 15px;
        font-size: 17px;
        line-height: 1.6;
        font-weight: 700;
        color: var(--text);
    }

    .question-number {
        width: 30px;
        height: 30px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: var(--primary-soft);
        color: var(--primary);
        font-size: 13px;
        font-weight: 800;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-size: 15px;
        font-weight: 700;
        color: #4C5164;
    }

    .required {
        color: var(--danger);
    }

    /* =========================================================
       INPUT
       ========================================================= */

    .form-input,
    .form-select,
    textarea.form-input {
        width: 100%;
        padding: 13px 14px;
        border: 1px solid #D9DCE8;
        border-radius: 9px;
        background: #fff;
        color: var(--text);
        font-family: inherit;
        font-size: 16px;
        line-height: 1.5;
        outline: none;
        transition: .2s ease;
    }

    .form-input:focus,
    .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(41, 45, 143, .08);
    }

    .form-input::placeholder {
        color: #A2A6B5;
    }

    .form-select {
        cursor: pointer;
    }

    textarea.form-input {
        min-height: 110px;
        resize: vertical;
    }

    /* =========================================================
       FORM GRID
       ========================================================= */

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }

    /* =========================================================
       RADIO
       ========================================================= */

    .radio-group {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .radio-option {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 16px;
        color: #505568;
        cursor: pointer;
    }

    .radio-option input {
        width: 17px;
        height: 17px;
        accent-color: var(--primary);
    }

    /* =========================================================
       MAP
       ========================================================= */

    .map-wrapper {
        margin-top: 15px;
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
        padding: 14px 16px;
        background: #FAFAFD;
        border-top: 1px solid var(--border);
    }

    .location-info p {
        margin: 0;
        font-size: 14px;
        line-height: 1.6;
        color: var(--muted);
    }

    .coordinate-input {
        margin-top: 9px;
    }

    .map-status {
        margin-top: 9px;
        font-size: 14px;
        color: var(--muted);
    }

    #map .leaflet-control-zoom {
        display: none !important;
    }

    #map .leaflet-control-attribution {
        display: none !important;
    }

    /* =========================================================
       FOOTER
       ========================================================= */

    .form-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 22px 28px;
        border-top: 1px solid var(--border);
        background: #FBFBFD;
    }

    .btn-reset,
    .btn-next {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        font-family: inherit;
        font-size: 15px;
        font-weight: 700;
        text-decoration: none !important;
        transition: .2s ease;
    }

    .btn-reset {
        min-width: 110px;
        padding: 13px 20px;
        border: 1px solid #D9DCE8;
        background: #fff;
        color: #656A7C;
        box-shadow: 0 4px 12px rgba(34, 38, 117, .12);
    }

    .btn-reset:hover {
        background: #F5F6FB;
        border-color: #C8CBDC;
        color: var(--primary) !important;
    }

    .btn-next {
        gap: 9px;
        min-width: 150px;
        padding: 13px 20px;
        border: 0;
        background: var(--primary);
        color: #fff;
        cursor: pointer;
    }

    .btn-next:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(41, 45, 143, .18);
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 900px) {
        .kuisioner-page {
            padding: 12px 16px 30px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .part-header {
            padding: 19px 18px;
        }

        .part-header h2 {
            font-size: 19px;
        }

        .part-header p {
            font-size: 14px;
        }

        .family-picker-header {
            padding: 20px 18px 12px;
        }

        .family-picker-header h3 {
            font-size: 18px;
        }

        .family-picker-header p {
            font-size: 14px;
        }

        .family-search {
            padding-left: 18px;
            padding-right: 18px;
        }

        .family-search-input {
            font-size: 15px;
            padding: 12px 14px 12px 42px;
        }

        .family-table-wrap {
            padding-left: 18px;
            padding-right: 18px;
            padding-bottom: 20px;
        }

        .family-table th {
            padding: 12px 10px;
            font-size: 13px;
        }

        .family-table td {
            padding: 13px 10px;
            font-size: 14px;
        }

        .family-name {
            line-height: 1.5;
        }

        .family-code {
            font-size: 11px;
        }

        .family-address {
            font-size: 13px;
        }

        .btn-start {
            min-width: 65px;
            padding: 8px 11px;
            font-size: 13px;
        }

        .family-selected {
            margin-left: 18px;
            margin-right: 18px;
            font-size: 14px;
        }

        .form-content {
            padding: 20px 18px;
        }

        .question {
            padding: 20px 0;
        }

        .question-title {
            font-size: 15px;
        }

        .question-number {
            width: 28px;
            height: 28px;
            font-size: 12px;
        }

        .form-label {
            font-size: 14px;
        }

        .form-input,
        .form-select,
        textarea.form-input {
            font-size: 15px;
            padding: 12px 13px;
        }

        .radio-option {
            font-size: 15px;
        }

        .location-info p,
        .map-status {
            font-size: 13px;
        }

        .form-footer {
            flex-direction: column-reverse;
            align-items: stretch;
            padding: 18px;
        }

        .btn-reset,
        .btn-next {
            width: 100%;
            font-size: 14px;
        }

        #map {
            height: 280px;
        }
    }
</style>

@php

    $kecamatanMap = collect($kecamatans ?? [])
        ->keyBy(function ($item) {
            return (string) $item->kecamatan_id;
        })
        ->map(function ($item) {
            return $item->deskripsi;
        })
        ->toArray();

    $kelurahanMap = collect($kelurahans ?? [])
        ->keyBy(function ($item) {
            return (string) $item->kelurahan_id;
        })
        ->map(function ($item) {
            return [
                'kecamatan_id' => (string) $item->kecamatan_id,
                'deskripsi' => $item->deskripsi,
            ];
        })
        ->toArray();

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

@endphp

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


{{-- =========================================================
     PILIH KELUARGA
========================================================= --}}

<div
    class="kuisioner-card"
    id="family-picker"
>

    <div class="family-picker">

        <div class="family-picker-header">

            <h3>
                Daftar Keluarga
            </h3>

            <p>
                Pilih salah satu keluarga untuk memulai kuisioner.
            </p>

        </div>


        <div class="family-search">

            <div class="family-search-box">

                <span class="family-search-icon">⌕</span>

                <input
                    type="text"
                    id="family-search"
                    class="family-search-input"
                    placeholder="Cari nama kepala keluarga..."
                    autocomplete="off"
                >

            </div>

        </div>


        <div class="family-table-wrap">

            <table class="family-table">

                <thead>

                    <tr>

                        <th>
                            Keluarga
                        </th>

                        <th>
                            Alamat
                        </th>

                        <th>
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($keluargas ?? [] as $keluarga)

                        @php
                            $anggotaPertama = $keluarga->anggota->first();
                        @endphp

                        <tr class="family-row">

                            <td>

                                <div class="family-name">

                                    {{ $keluarga->nama_lengkap }}

                                    @if($anggotaPertama)
                                        / {{ $anggotaPertama->nama_lengkap }}
                                    @endif

                                </div>

                                <div class="family-code">
                                    {{ $keluarga->kode }}
                                </div>

                            </td>


                            <td>

                                <div class="family-address">
                                    {{ $keluarga->alamat_lengkap ?: '-' }}
                                </div>

                            </td>


                            <td>

                                <button
                                    type="button"
                                    class="btn-start"
                                    onclick="pilihKeluarga({{ Js::from([
                                        'id' => $keluarga->id,
                                        'kode' => $keluarga->kode,
                                        'nik' => $keluarga->nik,
                                        'nama_lengkap' => $keluarga->nama_lengkap,
                                        'status_keluarga' => $keluarga->status_keluarga,
                                        'no_kk' => $keluarga->no_kk,
                                        'jumlah' => $keluarga->anggota->count() + 1,
                                        'kode_pos' => $keluarga->kode_pos,
                                        'alamat_lengkap' => $keluarga->alamat_lengkap,
                                        'kecamatan_id' => $keluarga->kecamatan_id,
                                        'kelurahan_id' => $keluarga->kelurahan_id,
                                        'anggota_pertama' => $anggotaPertama
                                            ? $anggotaPertama->nama_lengkap
                                            : null,
                                    ]) }})"
                                >
                                    Mulai
                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr class="family-empty-original">

                            <td
                                colspan="3"
                                style="text-align:center;padding:30px;color:#777D91;"
                            >
                                Belum ada data keluarga.
                            </td>

                        </tr>

                    @endforelse


                    <tr
                        id="family-search-empty"
                        class="family-search-empty"
                    >

                        <td colspan="3">
                            Nama kepala keluarga tidak ditemukan.
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- =========================================================
     KELUARGA TERPILIH
========================================================= --}}

<div
    class="family-selected"
    id="family-selected"
    style="display:none;"
>

    Keluarga yang dipilih:

    <strong id="selected-family-name"></strong>

</div>


{{-- =========================================================
     FORM PART 1
========================================================= --}}

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

        <input
            type="hidden"
            name="keluarga_kode"
            id="keluarga_kode"
            value="{{ $selectedKeluarga->kode ?? '' }}"
        >


        <div class="form-content">

            {{-- =====================================================
                 PERTANYAAN 1
            ====================================================== --}}

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
                        id="nik"
                        class="form-input"
                        value="{{ old('nik', $data->nik ?? '') }}"
                        placeholder="Masukkan NIK"
                    >

                </div>

            </div>


            {{-- =====================================================
                 PERTANYAAN 2
            ====================================================== --}}

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
                        id="nama_kepala_keluarga"
                        class="form-input"
                        value="{{ old('nama_kepala_keluarga', $data->nama_kepala_keluarga ?? '') }}"
                        placeholder="Masukkan nama kepala keluarga"
                    >

                </div>

            </div>


            {{-- =====================================================
                 PERTANYAAN 3
            ====================================================== --}}

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
                        id="status_keluarga"
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


            {{-- =====================================================
                 PERTANYAAN 4
            ====================================================== --}}

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
                        id="nomor_induk_keluarga"
                        class="form-input"
                        value="{{ old('nomor_induk_keluarga', $data->nomor_induk_keluarga ?? '') }}"
                        placeholder="Masukkan nomor induk keluarga"
                    >

                </div>

            </div>


            {{-- =====================================================
                 PERTANYAAN 5
            ====================================================== --}}

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
                        id="no_kk"
                        class="form-input"
                        value="{{ old('no_kk', $data->no_kk ?? '') }}"
                        placeholder="Masukkan nomor KK"
                    >

                </div>

            </div>


            {{-- =====================================================
                 PERTANYAAN 6
            ====================================================== --}}

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
                        id="jml_keluarga"
                        class="form-input"
                        min="1"
                        value="{{ old('jml_keluarga', $data->jml_keluarga ?? '') }}"
                        placeholder="Masukkan jumlah anggota keluarga"
                    >

                </div>

            </div>


            {{-- =====================================================
                 PERTANYAAN 7
            ====================================================== --}}

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


            {{-- =====================================================
                 PERTANYAAN 8
            ====================================================== --}}

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


            {{-- =====================================================
                 PERTANYAAN 9
            ====================================================== --}}

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

                    <input
                        type="text"
                        name="kecamatan"
                        id="kecamatan"
                        class="form-input"
                        value="{{ $nilaiKecamatan }}"
                        placeholder="Data kecamatan dari keluarga"
                        readonly
                    >

                </div>

            </div>


            {{-- =====================================================
                 PERTANYAAN 10
            ====================================================== --}}

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

                    <input
                        type="text"
                        name="kelurahan"
                        id="kelurahan"
                        class="form-input"
                        value="{{ $nilaiKelurahan }}"
                        placeholder="Data kelurahan dari keluarga"
                        readonly
                    >

                </div>

            </div>


            {{-- =====================================================
                 PERTANYAAN 11
            ====================================================== --}}

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


            {{-- =====================================================
                 PERTANYAAN 12
            ====================================================== --}}

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


            {{-- =====================================================
                 PERTANYAAN 13
            ====================================================== --}}

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


            {{-- =====================================================
                 PERTANYAAN 14
            ====================================================== --}}

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


            {{-- =====================================================
                 PERTANYAAN 15
            ====================================================== --}}

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


            {{-- =====================================================
                 GEOTAGGING
            ====================================================== --}}

            <div class="question">

                <div class="question-title">

                    <span class="question-number">
                        GPS
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


        {{-- =====================================================
             FOOTER
        ====================================================== --}}

        <div class="form-footer">

            <button
                type="button"
                class="btn-reset"
                onclick="kembaliKeDaftar()"
            >
                Kembali
            </button>

            <button
                type="submit"
                class="btn-next"
            >
                Simpan &amp; Lanjut
            </button>

        </div>

    </form>

</div>


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    /* =========================================================
       DATA KECAMATAN & KELURAHAN
    ========================================================= */

    const kecamatanMap = @json($kecamatanMap);
    const kelurahanMap = @json($kelurahanMap);


    /* =========================================================
       SEARCH KEPALA KELUARGA
    ========================================================= */

    const familySearch =
        document.getElementById('family-search');

    const familySearchEmpty =
        document.getElementById('family-search-empty');


    if (familySearch) {

        familySearch.addEventListener(
            'input',
            function () {

                const keyword =
                    this.value.trim().toLowerCase();

                const rows =
                    document.querySelectorAll('.family-row');

                let visibleRows = 0;


                rows.forEach(function (row) {

                    const nameElement =
                        row.querySelector('.family-name');

                    const name =
                        nameElement
                            ? nameElement.textContent.trim().toLowerCase()
                            : '';

                    const matched =
                        keyword === '' ||
                        name.includes(keyword);


                    row.style.display =
                        matched ? '' : 'none';


                    if (matched) {
                        visibleRows++;
                    }

                });


                if (familySearchEmpty) {

                    familySearchEmpty.style.display =
                        keyword !== '' && visibleRows === 0
                            ? 'table-row'
                            : 'none';

                }

            }
        );

    }


    /* =========================================================
       PILIH KELUARGA
    ========================================================= */

    function pilihKeluarga(keluarga)
    {

        document.getElementById('family-picker').style.display = 'none';

        document.getElementById('form-keluarga')
            .classList.add('show');


        const selectedBox =
            document.getElementById('family-selected');

        const selectedName =
            document.getElementById('selected-family-name');


        selectedBox.style.display = 'block';


        selectedName.textContent =
            keluarga.nama_lengkap +
            (
                keluarga.anggota_pertama
                    ? ' / ' + keluarga.anggota_pertama
                    : ''
            );


        /* DATA KELUARGA */

        setValue(
            'nik',
            keluarga.nik
        );

        setValue(
            'nama_kepala_keluarga',
            keluarga.nama_lengkap
        );

        setValue(
            'status_keluarga',
            keluarga.status_keluarga
        );


        /* NOMOR INDUK KELUARGA */

        setValue(
            'nomor_induk_keluarga',
            keluarga.nik
        );


        setValue(
            'no_kk',
            keluarga.no_kk
        );

        setValue(
            'jml_keluarga',
            keluarga.jumlah
        );


        /* KODE KELUARGA */

        setValue(
            'keluarga_kode',
            keluarga.kode
        );


        /* ALAMAT */

        setValue(
            'kode_pos',
            keluarga.kode_pos
        );

        setValue(
            'alamat_lengkap',
            keluarga.alamat_lengkap
        );


        /* KECAMATAN */

        let namaKecamatan = '';

        if (
            keluarga.kecamatan_id !== null &&
            keluarga.kecamatan_id !== undefined
        ) {

            const kecamatanId =
                String(keluarga.kecamatan_id);

            namaKecamatan =
                kecamatanMap[kecamatanId] || '';

        }


        setValue(
            'kecamatan',
            namaKecamatan
        );


        /* KELURAHAN */

        let namaKelurahan = '';

        if (
            keluarga.kelurahan_id !== null &&
            keluarga.kelurahan_id !== undefined
        ) {

            const kelurahanId =
                String(keluarga.kelurahan_id);

            if (kelurahanMap[kelurahanId]) {

                namaKelurahan =
                    kelurahanMap[kelurahanId].deskripsi;

            }

        }


        setValue(
            'kelurahan',
            namaKelurahan
        );


        /* SCROLL KE FORM */

        setTimeout(function () {

            document.getElementById('form-keluarga')
                .scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });


            if (typeof map !== 'undefined') {

                setTimeout(function () {

                    map.invalidateSize();

                }, 500);

            }

        }, 100);

    }


    /* =========================================================
       SET VALUE
    ========================================================= */

    function setValue(id, value)
    {

        const element =
            document.getElementById(id);

        if (!element) {
            return;
        }

        element.value =
            value ?? '';

    }


    /* =========================================================
       KEMBALI KE DAFTAR
    ========================================================= */

    function kembaliKeDaftar()
    {

        document.getElementById('form-keluarga')
            .classList.remove('show');

        document.getElementById('family-selected')
            .style.display = 'none';

        document.getElementById('family-picker')
            .style.display = 'block';


        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

    }


    /* =========================================================
       ALASAN ALAMAT
    ========================================================= */

    window.toggleAlasanTidakSesuai =
        function () {

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
            parseFloat(parts[0]);

        const lng =
            parseFloat(parts[1]);


        if (
            !isNaN(lat) &&
            !isNaN(lng)
        ) {

            map =
                L.map('map').setView(
                    [lat, lng],
                    16
                );


            marker =
                L.marker(
                    [lat, lng],
                    {
                        draggable: true
                    }
                ).addTo(map);

        }

    }


    if (!map) {

        map =
            L.map('map').setView(
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
                    draggable: true
                }
            ).addTo(map);

    }


    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }
    ).addTo(map);


    function updateCoordinate(lat, lng)
    {

        const value =
            lat.toFixed(6) +
            ',' +
            lng.toFixed(6);


        document.getElementById(
            'geotangging'
        ).value = value;


        document.getElementById(
            'map-status'
        ).textContent =
            'Lokasi dipilih: ' + value;

    }


    marker.on(
        'dragend',
        function (event) {

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
        function (event) {

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

        const lat =
            parseFloat(parts[0]);

        const lng =
            parseFloat(parts[1]);


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
        function (id) {

            const element =
                document.getElementById(id);

            if (!element) {
                return;
            }


            element.addEventListener(
                'change',
                updateMapFromAddress
            );

        }
    );


    async function updateMapFromAddress()
    {

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

            jalan && nomorRumah
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


        if (!query) {
            return;
        }


        try {

            const response =
                await fetch(
                    'https://nominatim.openstreetmap.org/search?' +
                    new URLSearchParams({

                        q: query,

                        format: 'json',

                        limit: '1',

                        countrycodes: 'id',

                        addressdetails: '1'

                    })
                );


            if (!response.ok) {

                throw new Error(
                    'Gagal mengambil data lokasi.'
                );

            }


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


            let zoom = 13;


            if (kecamatan) {
                zoom = 14;
            }


            if (kelurahan) {
                zoom = 16;
            }


            if (rtRw) {
                zoom = 17;
            }


            map.setView(
                [
                    lat,
                    lng
                ],
                zoom,
                {
                    animate: true
                }
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
        function () {
            map.invalidateSize();
        },
        300
    );
</script>

@endsection