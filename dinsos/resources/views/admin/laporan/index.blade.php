@extends('admin.layouts.app')

@section('title', 'Laporan Pendataan')

@section('content')

<style>
    /* =====================================================
       LAPORAN PAGE
    ===================================================== */

    .laporan-page {
        padding: 24px 32px 40px;
        background: #ffffff;
        min-height: calc(100vh - 70px);
        box-sizing: border-box;
        color: #222222;
    }


    /* =====================================================
       HEADER
    ===================================================== */

    .laporan-header {
        margin-bottom: 22px;
    }

    .laporan-header-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
    }

    .laporan-header h1 {
        margin: 0;
        font-size: 25px;
        line-height: 1.3;
        font-weight: 700;
        color: #252A86;
    }

    .laporan-header p {
        margin: 7px 0 0;
        color: #666666;
        font-size: 14px;
        line-height: 1.6;
    }


    /* =====================================================
       EXPORT BUTTON
    ===================================================== */

    .laporan-export-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 0 17px;
        border: 1px solid #252A86;
        border-radius: 9px;
        background: #252A86;
        color: #ffffff;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: .2s ease;
        white-space: nowrap;
        box-sizing: border-box;
    }

    .laporan-export-btn:hover {
        background: #1d216d;
        border-color: #1d216d;
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(37, 42, 134, .15);
    }

    .laporan-export-btn svg {
        width: 17px;
        height: 17px;
        flex-shrink: 0;
    }


    /* =====================================================
       STATISTICS
    ===================================================== */

    .laporan-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .laporan-stat-card {
        background: #ffffff;
        border: 1px solid #e4e6ec;
        border-radius: 13px;
        padding: 18px;
        min-height: 94px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 3px 12px rgba(15, 23, 42, .04);
        transition: .2s ease;
        box-sizing: border-box;
    }

    .laporan-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(15, 23, 42, .07);
    }

    .laporan-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eef0ff;
        color: #252A86;
        flex-shrink: 0;
    }

    .laporan-stat-icon.blue-light {
        background: #eaf7fb;
        color: #2f9bbd;
    }

    .laporan-stat-icon.green {
        background: #eaf7ee;
        color: #299447;
    }

    .laporan-stat-icon.orange {
        background: #fff7df;
        color: #d69212;
    }

    .laporan-stat-info {
        min-width: 0;
    }

    .laporan-stat-label {
        margin-bottom: 4px;
        color: #666666;
        font-size: 12px;
        line-height: 1.4;
    }

    .laporan-stat-value {
        color: #252A86;
        font-size: 22px;
        line-height: 1.2;
        font-weight: 700;
    }


    /* =====================================================
       MAIN CARD
    ===================================================== */

    .laporan-card {
        background: #ffffff;
        border: 1px solid #e3e5ea;
        border-radius: 15px;
        box-shadow: 0 3px 14px rgba(15, 23, 42, .045);
        overflow: hidden;
    }

    .laporan-card-header {
        padding: 20px 22px;
        border-bottom: 1px solid #e7e8ec;
    }

    .laporan-card-title {
        margin: 0 0 5px;
        color: #252A86;
        font-size: 17px;
        line-height: 1.4;
        font-weight: 700;
    }

    .laporan-card-description {
        margin: 0;
        color: #666666;
        font-size: 13px;
        line-height: 1.5;
    }


     /* =====================================================
 FILTER
 ===================================================== */
 .laporan-filter {
 display: grid;
 grid-template-columns: minmax(280px, 1.8fr) minmax(160px, .9fr) minmax(180px, 1fr) auto;
 gap: 12px;
 margin-top: 17px;
 align-items: end;
 }
 .laporan-filter-group {
 min-width: 0;
 }
 .laporan-filter-label {
 display: block;
 margin: 0 0 7px;
 color: #555555;
 font-size: 12px;
 line-height: 1.4;
 font-weight: 600;
 }
 .laporan-filter-label-hidden {
 visibility: hidden;
 }
 .laporan-search {
 position: relative;
 width: 100%;
 }
 .laporan-search input,
 .laporan-filter select {
 width: 100%;
 height: 43px;
 border: 1px solid #d9dce3;
 border-radius: 9px;
 background: #ffffff;
 color: #222222;
 font-size: 13px;
 outline: none;
 box-sizing: border-box;
 transition: .2s ease;
 }
 .laporan-search input {
 padding: 0 42px 0 14px;
 }
 .laporan-filter select {
 padding: 0 35px 0 13px;
 cursor: pointer;
 }
 .laporan-search input::placeholder {
 color: #999999;
 }
 .laporan-search input:focus,
 .laporan-filter select:focus {
 border-color: #55B5D5;
 box-shadow: 0 0 0 3px rgba(85, 181, 213, .12);
 }
 .laporan-search-icon {
 position: absolute;
 top: 50%;
 right: 14px;
 transform: translateY(-50%);
 color: #777777;
 pointer-events: none;
 display: flex;
 align-items: center;
 justify-content: center;
 }
 .laporan-reset-btn {
 width: 100%;
 min-height: 43px;
 display: inline-flex;
 align-items: center;
 justify-content: center;
 padding: 0 15px;
 border: 1px solid #252A86;
 border-radius: 9px;
 background: #ffffff;
 color: #252A86;
 font-size: 13px;
 font-weight: 600;
 text-decoration: none;
 box-sizing: border-box;
 transition: .2s ease;
 white-space: nowrap;
 }
 .laporan-reset-btn:hover {
 background: #f1f3ff;
 color: #252A86;
 }

/* =====================================================
       TABLE
    ===================================================== */

    .laporan-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .laporan-table {
        width: 100%;
        min-width: 1150px;
        border-collapse: collapse;
    }

    .laporan-table th {
        padding: 14px 15px;
        background: #f7f8fc;
        border-bottom: 1px solid #e3e5ea;
        color: #4b5563 !important;
        font-size: 12px;
        font-weight: 700;
        text-align: left;
        white-space: nowrap;
    }

    .laporan-table td {
        padding: 15px;
        border-bottom: 1px solid #eef0f3;
        color: #4b5563 !important;
        font-size: 13px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .laporan-table td strong {
        color: #4b5563 !important;
        font-weight: 600;
    }

    .laporan-table tbody tr {
        transition: .15s ease;
    }

    .laporan-table tbody tr:hover {
        background: #fafbff;
    }

    .laporan-table tbody tr:last-child td {
        border-bottom: none;
    }


    /* =====================================================
       STATUS
    ===================================================== */

    .laporan-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .laporan-status.selesai,
    .laporan-status.disetujui {
        background: #eaf7ee;
        color: #299447;
    }

    .laporan-status.belum {
        background: #f1f3f8;
        color: #667085;
    }

    .laporan-status.diproses {
        background: #fff7df;
        color: #c17b00;
    }

    .laporan-status.ditolak {
        background: #fdecef;
        color: #d9364f;
    }


    /* =====================================================
       CHECK COMPLETE
    ===================================================== */

    .laporan-complete {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    .laporan-complete.yes {
        color: #299447;
    }

    .laporan-complete.no {
        color: #888888;
    }

    .laporan-check {
        width: 19px;
        height: 19px;
        border-radius: 5px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
    }

    .laporan-check.yes {
        background: #eaf7ee;
        color: #299447;
    }

    .laporan-check.no {
        background: #f0f1f4;
        color: #999999;
    }


    /* =====================================================
       DETAIL BUTTON
    ===================================================== */

    .laporan-detail-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-width: 70px;
        height: 34px;
        padding: 0 12px;
        border: 1px solid #d5d8ed;
        border-radius: 8px;
        background: #f1f3ff;
        color: #252A86;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s ease;
    }

    .laporan-detail-btn:hover {
        background: #252A86;
        border-color: #252A86;
        color: #ffffff;
    }


    /* =====================================================
       EMPTY
    ===================================================== */

    .laporan-empty {
        padding: 45px 20px;
        text-align: center;
        color: #666666;
    }

    .laporan-empty-icon {
        width: 52px;
        height: 52px;
        margin: 0 auto 12px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f3ff;
        color: #252A86;
    }

    .laporan-empty-title {
        margin-bottom: 5px;
        color: #252A86;
        font-size: 14px;
        font-weight: 700;
    }

    .laporan-empty-text {
        color: #777777;
        font-size: 13px;
    }


    /* =====================================================
       MODAL
    ===================================================== */

    .laporan-modal-overlay {
        position: fixed;
        inset: 0;
        z-index: 5000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(15, 23, 42, .48);
        backdrop-filter: blur(3px);
        box-sizing: border-box;
    }

    .laporan-modal-overlay.active {
        display: flex;
    }

    .laporan-modal {
        width: min(850px, 100%);
        max-height: calc(100vh - 40px);
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 25px 70px rgba(15, 23, 42, .22);
        overflow: hidden;
        animation: laporanModalShow .2s ease;
    }

    @keyframes laporanModalShow {
        from {
            opacity: 0;
            transform: translateY(10px) scale(.98);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }


    /* =====================================================
       MODAL HEADER
    ===================================================== */

    .laporan-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 18px 21px;
        border-bottom: 1px solid #e5e7eb;
    }

    .laporan-modal-header h2 {
        margin: 0;
        color: #252A86;
        font-size: 18px;
        font-weight: 700;
    }

    .laporan-modal-header p {
        margin: 4px 0 0;
        color: #777777;
        font-size: 12px;
    }

    .laporan-modal-close {
        width: 35px;
        height: 35px;
        border: none;
        border-radius: 8px;
        background: #f1f3ff;
        color: #252A86;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: .2s ease;
        flex-shrink: 0;
    }

    .laporan-modal-close:hover {
        background: #252A86;
        color: #ffffff;
    }


    /* =====================================================
       MODAL BODY
    ===================================================== */

    .laporan-modal-body {
        padding: 21px;
        max-height: calc(100vh - 155px);
        overflow-y: auto;
    }


    /* =====================================================
       MODAL SUMMARY
    ===================================================== */

    .laporan-modal-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 18px;
    }

    .laporan-summary-box {
        padding: 14px;
        border: 1px solid #e2e4ea;
        border-radius: 10px;
        background: #fafbff;
    }

    .laporan-summary-label {
        margin-bottom: 5px;
        color: #777777;
        font-size: 11px;
    }

    .laporan-summary-value {
        color: #252A86;
        font-size: 14px;
        font-weight: 700;
        word-break: break-word;
    }


    /* =====================================================
       MODAL INFORMATION
    ===================================================== */

    .laporan-info-section {
        margin-bottom: 18px;
        border: 1px solid #e2e4ea;
        border-radius: 11px;
        overflow: hidden;
    }

    .laporan-info-title {
        padding: 12px 15px;
        background: #f7f8fc;
        border-bottom: 1px solid #e2e4ea;
        color: #252A86;
        font-size: 13px;
        font-weight: 700;
    }

    .laporan-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .laporan-info-item {
        padding: 13px 15px;
        border-bottom: 1px solid #eef0f3;
    }

    .laporan-info-item:nth-child(odd) {
        border-right: 1px solid #eef0f3;
    }

    .laporan-info-label {
        margin-bottom: 4px;
        color: #777777;
        font-size: 11px;
    }

    .laporan-info-value {
        color: #4b5563;
        font-size: 13px;
        font-weight: 600;
        word-break: break-word;
    }


    /* =====================================================
       MODAL FOOTER
    ===================================================== */

    .laporan-modal-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 9px;
        padding-top: 2px;
    }

    .laporan-modal-btn {
        min-height: 38px;
        padding: 0 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s ease;
    }

    .laporan-modal-btn.close {
        border: 1px solid #d8dbe2;
        background: #ffffff;
        color: #555555;
    }

    .laporan-modal-btn.close:hover {
        background: #f5f6f8;
    }

    .laporan-modal-btn.pdf {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border: 1px solid #252A86;
        background: #252A86;
        color: #ffffff;
        text-decoration: none;
    }

    .laporan-modal-btn.pdf:hover {
        background: #1d216d;
        border-color: #1d216d;
    }


    /* =====================================================
       RESPONSIVE
    ===================================================== */

    @media (max-width: 1200px) {
 .laporan-stats {
 grid-template-columns: repeat(2, minmax(0, 1fr));
 }
 .laporan-filter {
 grid-template-columns: 1fr 1fr;
 }
 .laporan-filter-reset-group {
 grid-column: 1 / -1;
 }
 .laporan-reset-btn {
 width: 100%;
 }
 }

 @media (max-width: 800px) {

        .laporan-page {
            padding: 20px 18px 30px;
        }

        .laporan-header-top {
            flex-direction: column;
            align-items: stretch;
        }

        .laporan-export-btn {
            width: 100%;
        }

        .laporan-filter {
            grid-template-columns: 1fr;
        }

        .laporan-modal-summary {
            grid-template-columns: 1fr;
        }

        .laporan-info-grid {
            grid-template-columns: 1fr;
        }

        .laporan-info-item:nth-child(odd) {
            border-right: none;
        }

    }


    @media (max-width: 600px) {

        .laporan-page {
            padding: 17px 13px 25px;
        }

        .laporan-header {
            margin-bottom: 18px;
        }

        .laporan-header h1 {
            font-size: 21px;
        }

        .laporan-header p {
            font-size: 13px;
        }

        /*
         * MOBILE:
         * 2 CARD DALAM 1 BARIS
         * TOTAL 4 CARD = 2 BARIS
         */
        .laporan-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 11px;
        }

        .laporan-stat-card {
            min-height: 82px;
            padding: 15px;
        }

        .laporan-card-header {
            padding: 17px 15px;
        }

        .laporan-modal-overlay {
            padding: 10px;
        }

        .laporan-modal {
            max-height: calc(100vh - 20px);
            border-radius: 13px;
        }

        .laporan-modal-header {
            padding: 15px;
        }

        .laporan-modal-body {
            padding: 15px;
            max-height: calc(100vh - 115px);
        }

        .laporan-modal-footer {
            flex-direction: column-reverse;
        }

        .laporan-modal-btn {
            width: 100%;
            justify-content: center;
            text-align: center;
        }

    }
</style>


<div class="laporan-page">

    {{-- =====================================================
         HEADER
    ===================================================== --}}

    <div class="laporan-header">

        <div class="laporan-header-top">

            <div>
                <h1>Laporan Pendataan</h1>

                <p>
                    Menampilkan rekapitulasi pendataan keluarga berdasarkan
                    periode, wilayah, petugas, dan status pengisian.
                </p>
            </div>


            {{-- EXPORT EXCEL --}}

            <a
                href="{{ route('admin.laporan.export') }}"
                class="laporan-export-btn"
            >

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <path d="M8 13h8"></path>
                    <path d="M8 17h5"></path>
                </svg>

                Export Excel

            </a>

        </div>

    </div>


    {{-- =====================================================
         STATISTICS
    ===================================================== --}}

    <div class="laporan-stats">


        {{-- TOTAL RESPONDEN --}}

        <div class="laporan-stat-card">

            <div class="laporan-stat-info">

                <div class="laporan-stat-label">
                    Total Responden
                </div>

                <div class="laporan-stat-value">
                    {{ $totalResponden ?? count($laporan ?? []) }}
                </div>

            </div>

        </div>


        {{-- KUISIONER SELESAI --}}

        <div class="laporan-stat-card">

            <div class="laporan-stat-info">

                <div class="laporan-stat-label">
                    Kuisioner Selesai
                </div>

                <div class="laporan-stat-value">
                    {{ $kuisionerSelesai ?? 0 }}
                </div>

            </div>

        </div>


        {{-- PETUGAS AKTIF --}}

        <div class="laporan-stat-card">

            <div class="laporan-stat-info">

                <div class="laporan-stat-label">
                    Petugas Aktif
                </div>

                <div class="laporan-stat-value">
                    {{ $petugasAktif ?? 0 }}
                </div>

            </div>

        </div>


        {{-- WILAYAH TERDATA --}}

        <div class="laporan-stat-card">

            <div class="laporan-stat-info">

                <div class="laporan-stat-label">
                    Wilayah Terdata
                </div>

                <div class="laporan-stat-value">
                    {{ $wilayahTerdata ?? 0 }}
                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         MAIN REPORT CARD
    ===================================================== --}}

    <div class="laporan-card">

        <div class="laporan-card-header">

            {{-- FILTER --}}

            <form
                method="GET"
                action="{{ route('laporan.index') }}"
                class="laporan-filter"
                id="laporanFilterForm"
            >

                {{-- PENCARIAN --}}
                <div class="laporan-filter-group laporan-filter-search">

                    <label
                        for="laporanSearch"
                        class="laporan-filter-label"
                    >
                        Pencarian Data
                    </label>

                    <div class="laporan-search">

                        <input
                            type="text"
                            name="search"
                            id="laporanSearch"
                            value="{{ $search ?? request('search') }}"
                            placeholder="Cari No. KK, NIK, atau Nama Kepala Keluarga..."
                            autocomplete="off"
                        >

                        <span
                            class="laporan-search-icon"
                            aria-hidden="true"
                        >
                            <svg
                                width="17"
                                height="17"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <circle cx="11" cy="11" r="7"></circle>
                                <line
                                    x1="16.5"
                                    y1="16.5"
                                    x2="21"
                                    y2="21"
                                ></line>
                            </svg>
                        </span>

                    </div>

                </div>

                {{-- PERIODE --}}
                <div class="laporan-filter-group">

                    <label
                        for="filterPeriode"
                        class="laporan-filter-label"
                    >
                        Periode
                    </label>

                    <select
                        name="periode"
                        id="filterPeriode"
                    >
                        <option value="">
                            Semua Periode
                        </option>

                        @foreach(($periodeList ?? []) as $itemPeriode)

                            <option
                                value="{{ $itemPeriode }}"
                                {{ ($periode ?? request('periode')) == $itemPeriode ? 'selected' : '' }}
                            >
                                {{ $itemPeriode }}
                            </option>

                        @endforeach
                    </select>

                </div>

                {{-- WILAYAH --}}
                <div class="laporan-filter-group">

                    <label
                        for="filterWilayah"
                        class="laporan-filter-label"
                    >
                        Wilayah
                    </label>

                    <select
                        name="wilayah"
                        id="filterWilayah"
                    >
                        <option value="">
                            Semua Wilayah
                        </option>

                        @foreach(($wilayahList ?? []) as $itemWilayah)

                            <option
                                value="{{ $itemWilayah }}"
                                {{ ($wilayah ?? request('wilayah')) == $itemWilayah ? 'selected' : '' }}
                            >
                                {{ $itemWilayah }}
                            </option>

                        @endforeach
                    </select>

                </div>

                {{-- RESET --}}
                <div class="laporan-filter-group laporan-filter-reset-group">

                    <span
                        class="laporan-filter-label laporan-filter-label-hidden"
                        aria-hidden="true"
                    >
                        Filter
                    </span>

                    <a
                        href="{{ route('laporan.index') }}"
                        class="laporan-reset-btn"
                        id="resetLaporanFilter"
                    >
                        Reset Filter
                    </a>

                </div>

            </form>

        <div class="laporan-table-wrapper">

            <table class="laporan-table">

                <thead>

                    <tr>

                        <th>No.</th>

                        <th>No. KK</th>

                        <th>NIK</th>

                        <th>Nama Kepala Keluarga</th>

                        <th>Jumlah Anggota</th>

                        <th>Wilayah</th>

                        <th>Periode</th>

                        <th>Tanggal Pendataan</th>

                        <th>Status</th>

                        <th>Aksi</th>

                    </tr>

                </thead>


                <tbody id="laporanTableBody">

                    @forelse(($laporan ?? []) as $index => $item)

                        @php

                            $itemId = data_get(
                                $item,
                                'id',
                                $index
                            );

                            $noKk = data_get(
                                $item,
                                'no_kk'
                            )
                            ?? data_get(
                                $item,
                                'kk'
                            )
                            ?? '-';

                            $nik = data_get(
                                $item,
                                'nik'
                            )
                            ?? '-';

                            $nama = data_get(
                                $item,
                                'nama_kepala_keluarga'
                            )
                            ?? data_get(
                                $item,
                                'nama_lengkap'
                            )
                            ?? data_get(
                                $item,
                                'nama'
                            )
                            ?? '-';

                            $jumlahAnggota = data_get(
                                $item,
                                'jumlah_anggota'
                            )
                            ?? data_get(
                                $item,
                                'jumlah_anggota_keluarga'
                            )
                            ?? 0;

                            $wilayah = data_get(
                                $item,
                                'wilayah'
                            )
                            ?? '-';

                            $periode = data_get(
                                $item,
                                'periode'
                            )
                            ?? data_get(
                                $item,
                                'nama_periode'
                            )
                            ?? '-';

                            $tanggalPendataan = data_get(
                                $item,
                                'tanggal_pendataan'
                            )
                            ?? data_get(
                                $item,
                                'created_at'
                            )
                            ?? '-';

                            $status = data_get(
                                $item,
                                'status'
                            )
                            ?? 'Belum Selesai';

                            $isComplete = data_get(
                                $item,
                                'is_complete'
                            );

                            $statusLower = strtolower($status);

                            $statusClass = match(true) {

                                in_array(
                                    $statusLower,
                                    ['selesai', 'disetujui', 'approved']
                                )
                                    => 'selesai',

                                in_array(
                                    $statusLower,
                                    ['ditolak', 'reject']
                                )
                                    => 'ditolak',

                                in_array(
                                    $statusLower,
                                    ['diproses', 'menunggu', 'pending']
                                )
                                    => 'diproses',

                                default
                                    => 'belum',

                            };

                            if (is_null($isComplete)) {
                                $isComplete = in_array(
                                    $statusLower,
                                    ['selesai', 'disetujui', 'approved']
                                );
                            }

                        @endphp


                        <tr
                            data-search="{{ strtolower(
                                $noKk . ' ' .
                                $nik . ' ' .
                                $nama
                            ) }}"

                            data-periode="{{ strtolower($periode) }}"

                            data-wilayah="{{ strtolower($wilayah) }}"
                        >

                            <td>
                                {{ $index + 1 }}
                            </td>


                            <td>
                                <strong>
                                    {{ $noKk }}
                                </strong>
                            </td>


                            <td>
                                {{ $nik }}
                            </td>


                            <td>
                                {{ $nama }}
                            </td>


                            <td>
                                {{ $jumlahAnggota }} Orang
                            </td>


                            <td>
                                {{ $wilayah }}
                            </td>


                            <td>
                                {{ $periode }}
                            </td>


                            <td>
                                {{ $tanggalPendataan }}
                            </td>


                            <td>

                                <span class="laporan-status {{ $statusClass }}">
                                    {{ $status }}
                                </span>

                            </td>


                            <td>

                                <button
                                    type="button"
                                    class="laporan-detail-btn btn-detail-laporan"

                                    data-id="{{ $itemId }}"
                                    data-no-kk="{{ e($noKk) }}"
                                    data-nik="{{ e($nik) }}"
                                    data-nama="{{ e($nama) }}"
                                    data-jumlah-anggota="{{ e($jumlahAnggota) }}"
                                    data-wilayah="{{ e($wilayah) }}"
                                    data-periode="{{ e($periode) }}"
                                    data-tanggal="{{ e($tanggalPendataan) }}"
                                    data-status="{{ e($status) }}"
                                    data-complete="{{ $isComplete ? '1' : '0' }}"
                                >

                                    Detail

                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="10">

                                <div class="laporan-empty">

                                    <div class="laporan-empty-icon">

                                        <svg
                                            width="25"
                                            height="25"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2-2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                        </svg>

                                    </div>

                                    <div class="laporan-empty-title">
                                        Belum Ada Data
                                    </div>

                                    <div class="laporan-empty-text">
                                        Belum terdapat data pendataan yang dapat ditampilkan.
                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforelse


                    </tbody>

            </table>

        </div>

    </div>

</div>


{{-- =====================================================
     DETAIL MODAL
===================================================== --}}

<div
    class="laporan-modal-overlay"
    id="laporanDetailModal"
    aria-hidden="true"
>

    <div
        class="laporan-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="laporanDetailTitle"
    >

        <div class="laporan-modal-header">

            <div>

                <h2 id="laporanDetailTitle">
                    Detail Data Keluarga
                </h2>

                <p>
                    Informasi lengkap pendataan satu keluarga
                </p>

            </div>


            <button
                type="button"
                class="laporan-modal-close"
                id="closeLaporanModal"
                aria-label="Tutup"
            >

                <svg
                    width="19"
                    height="19"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>

            </button>

        </div>


        <div class="laporan-modal-body">

            <div class="laporan-modal-summary">

                <div class="laporan-summary-box">

                    <div class="laporan-summary-label">
                        Nama Kepala Keluarga
                    </div>

                    <div
                        class="laporan-summary-value"
                        id="modalNamaSummary"
                    >
                        -
                    </div>

                </div>


                <div class="laporan-summary-box">

                    <div class="laporan-summary-label">
                        Nomor KK
                    </div>

                    <div
                        class="laporan-summary-value"
                        id="modalKkSummary"
                    >
                        -
                    </div>

                </div>


                <div class="laporan-summary-box">

                    <div class="laporan-summary-label">
                        Status Pendataan
                    </div>

                    <div
                        class="laporan-summary-value"
                        id="modalStatusSummary"
                    >
                        -
                    </div>

                </div>

            </div>


            <div class="laporan-info-section">

                <div class="laporan-info-title">
                    Informasi Pendataan
                </div>


                <div class="laporan-info-grid">

                    <div class="laporan-info-item">

                        <div class="laporan-info-label">
                            Nomor KK
                        </div>

                        <div
                            class="laporan-info-value"
                            id="modalNoKk"
                        >
                            -
                        </div>

                    </div>


                    <div class="laporan-info-item">

                        <div class="laporan-info-label">
                            NIK
                        </div>

                        <div
                            class="laporan-info-value"
                            id="modalNik"
                        >
                            -
                        </div>

                    </div>


                    <div class="laporan-info-item">

                        <div class="laporan-info-label">
                            Nama Kepala Keluarga
                        </div>

                        <div
                            class="laporan-info-value"
                            id="modalNama"
                        >
                            -
                        </div>

                    </div>


                    <div class="laporan-info-item">

                        <div class="laporan-info-label">
                            Jumlah Anggota
                        </div>

                        <div
                            class="laporan-info-value"
                            id="modalJumlahAnggota"
                        >
                            -
                        </div>

                    </div>


                    <div class="laporan-info-item">

                        <div class="laporan-info-label">
                            Wilayah
                        </div>

                        <div
                            class="laporan-info-value"
                            id="modalWilayah"
                        >
                            -
                        </div>

                    </div>


                    <div class="laporan-info-item">

                        <div class="laporan-info-label">
                            Periode
                        </div>

                        <div
                            class="laporan-info-value"
                            id="modalPeriode"
                        >
                            -
                        </div>

                    </div>


                    <div class="laporan-info-item">

                        <div class="laporan-info-label">
                            Tanggal Pendataan
                        </div>

                        <div
                            class="laporan-info-value"
                            id="modalTanggal"
                        >
                            -
                        </div>

                    </div>


                    <div class="laporan-info-item">

                        <div class="laporan-info-label">
                            Status
                        </div>

                        <div
                            class="laporan-info-value"
                            id="modalStatus"
                        >
                            -
                        </div>

                    </div>


                    <div class="laporan-info-item">

                        <div class="laporan-info-label">
                            Kelengkapan Data
                        </div>

                        <div
                            class="laporan-info-value"
                            id="modalComplete"
                        >
                            -
                        </div>

                    </div>

                </div>

            </div>


            <div class="laporan-modal-footer">

                <button
                    type="button"
                    class="laporan-modal-btn close"
                    id="closeLaporanModalBottom"
                >
                    Tutup
                </button>


                <a
                    href="#"
                    class="laporan-modal-btn pdf"
                    id="downloadLaporanPdf"
                    target="_blank"
                >

                    <svg
                        width="16"
                        height="16"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <path d="M12 12v6"></path>
                        <path d="M9.5 15.5L12 18l2.5-2.5"></path>
                    </svg>

                    Download PDF

                </a>

            </div>

        </div>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       ELEMENT FILTER
    ===================================================== */
    const laporanFilterForm = document.getElementById('laporanFilterForm');
    const searchInput = document.getElementById('laporanSearch');
    const periodeFilter = document.getElementById('filterPeriode');
    const wilayahFilter = document.getElementById('filterWilayah');
    const tableBody = document.getElementById('laporanTableBody');

    /* =====================================================
       REALTIME SEARCH + FILTER
    ===================================================== */

    function filterLaporanTable() {

        if (!tableBody) return;

        const rows = tableBody.querySelectorAll('tr[data-search]');

        const searchValue = searchInput
            ? searchInput.value.trim().toLowerCase()
            : '';

        const periodeValue = periodeFilter
            ? periodeFilter.value.trim().toLowerCase()
            : '';

        const wilayahValue = wilayahFilter
            ? wilayahFilter.value.trim().toLowerCase()
            : '';

        let visibleCount = 0;

        rows.forEach(function (row) {

            const searchData = (
                row.dataset.search || ''
            ).toLowerCase();

            const rowPeriode = (
                row.dataset.periode || ''
            ).toLowerCase();

            const rowWilayah = (
                row.dataset.wilayah || ''
            ).toLowerCase();

            const cocokSearch =
                searchValue === '' ||
                searchData.includes(searchValue);

            const cocokPeriode =
                periodeValue === '' ||
                rowPeriode === periodeValue;

            const cocokWilayah =
                wilayahValue === '' ||
                rowWilayah === wilayahValue;

            const cocok =
                cocokSearch &&
                cocokPeriode &&
                cocokWilayah;

            if (cocok) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        /* =================================================
           EMPTY RESULT
        ================================================= */

        let emptyRow = document.getElementById(
            'laporanRealtimeEmpty'
        );

        if (visibleCount === 0 && rows.length > 0) {

            if (!emptyRow) {

                emptyRow = document.createElement('tr');

                emptyRow.id = 'laporanRealtimeEmpty';

                emptyRow.innerHTML = `
                    <td colspan="10">
                        <div class="laporan-empty">

                            <div class="laporan-empty-icon">
                                <svg
                                    width="25"
                                    height="25"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <circle
                                        cx="11"
                                        cy="11"
                                        r="7"
                                    ></circle>

                                    <line
                                        x1="16.5"
                                        y1="16.5"
                                        x2="21"
                                        y2="21"
                                    ></line>
                                </svg>
                            </div>

                            <div class="laporan-empty-title">
                                Data Tidak Ditemukan
                            </div>

                            <div class="laporan-empty-text">
                                Tidak ada data yang sesuai dengan pencarian atau filter.
                            </div>

                        </div>
                    </td>
                `;

                tableBody.appendChild(emptyRow);
            }

            emptyRow.style.display = '';

        } else if (emptyRow) {

            emptyRow.style.display = 'none';
        }
    }


    /* =====================================================
       SEARCH REALTIME
    ===================================================== */

    if (searchInput) {

        searchInput.addEventListener(
            'input',
            function () {

                filterLaporanTable();

            }
        );
    }


    /* =====================================================
       FILTER PERIODE REALTIME
    ===================================================== */

    if (periodeFilter) {

        periodeFilter.addEventListener(
            'change',
            function () {

                filterLaporanTable();

            }
        );
    }


    /* =====================================================
       FILTER WILAYAH REALTIME
    ===================================================== */

    if (wilayahFilter) {

        wilayahFilter.addEventListener(
            'change',
            function () {

                filterLaporanTable();

            }
        );
    }


    /* =====================================================
       RESET FILTER
    ===================================================== */

    const resetButton = document.getElementById(
        'resetLaporanFilter'
    );

    if (resetButton) {

        resetButton.addEventListener(
            'click',
            function (event) {

                event.preventDefault();

                if (searchInput) {
                    searchInput.value = '';
                }

                if (periodeFilter) {
                    periodeFilter.value = '';
                }

                if (wilayahFilter) {
                    wilayahFilter.value = '';
                }

                filterLaporanTable();

            }
        );
    }


    /* =====================================================
       MODAL
    ===================================================== */

    const modal = document.getElementById(
        'laporanDetailModal'
    );

    const closeModal = document.getElementById(
        'closeLaporanModal'
    );

    const closeModalBottom = document.getElementById(
        'closeLaporanModalBottom'
    );

    const downloadPdf = document.getElementById(
        'downloadLaporanPdf'
    );


    const modalNamaSummary = document.getElementById(
        'modalNamaSummary'
    );

    const modalKkSummary = document.getElementById(
        'modalKkSummary'
    );

    const modalStatusSummary = document.getElementById(
        'modalStatusSummary'
    );

    const modalNoKk = document.getElementById(
        'modalNoKk'
    );

    const modalNik = document.getElementById(
        'modalNik'
    );

    const modalNama = document.getElementById(
        'modalNama'
    );

    const modalJumlahAnggota = document.getElementById(
        'modalJumlahAnggota'
    );

    const modalWilayah = document.getElementById(
        'modalWilayah'
    );

    const modalPeriode = document.getElementById(
        'modalPeriode'
    );

    const modalTanggal = document.getElementById(
        'modalTanggal'
    );

    const modalStatus = document.getElementById(
        'modalStatus'
    );

    const modalComplete = document.getElementById(
        'modalComplete'
    );


    /* =====================================================
       OPEN MODAL
    ===================================================== */

    function openLaporanModal(button) {

        const data = button.dataset;

        if (modalNamaSummary) {
            modalNamaSummary.textContent =
                data.nama || '-';
        }

        if (modalKkSummary) {
            modalKkSummary.textContent =
                data.noKk || '-';
        }

        if (modalStatusSummary) {
            modalStatusSummary.textContent =
                data.status || '-';
        }

        if (modalNoKk) {
            modalNoKk.textContent =
                data.noKk || '-';
        }

        if (modalNik) {
            modalNik.textContent =
                data.nik || '-';
        }

        if (modalNama) {
            modalNama.textContent =
                data.nama || '-';
        }

        if (modalJumlahAnggota) {
            modalJumlahAnggota.textContent =
                (data.jumlahAnggota || '0') + ' Orang';
        }

        if (modalWilayah) {
            modalWilayah.textContent =
                data.wilayah || '-';
        }

        if (modalPeriode) {
            modalPeriode.textContent =
                data.periode || '-';
        }

        if (modalTanggal) {
            modalTanggal.textContent =
                data.tanggal || '-';
        }

        if (modalStatus) {
            modalStatus.textContent =
                data.status || '-';
        }


        /* =================================================
           KELENGKAPAN DATA
        ================================================= */

        if (modalComplete) {

            if (data.complete === '1') {

                modalComplete.innerHTML = `
                    <span class="laporan-complete yes">
                        <span class="laporan-check yes">✓</span>
                        Data lengkap
                    </span>
                `;

            } else {

                modalComplete.innerHTML = `
                    <span class="laporan-complete no">
                        <span class="laporan-check no">−</span>
                        Belum lengkap
                    </span>
                `;
            }
        }


        /* =================================================
           PDF
        ================================================= */

        if (downloadPdf && data.id) {

            downloadPdf.href =
                `/admin/laporan/${data.id}/pdf`;

        }


        /* =================================================
           SHOW MODAL
        ================================================= */

        if (modal) {

            modal.classList.add('active');

            modal.setAttribute(
                'aria-hidden',
                'false'
            );

            document.body.style.overflow =
                'hidden';
        }
    }


    /* =====================================================
       CLOSE MODAL
    ===================================================== */

    function closeLaporanModal() {

        if (!modal) return;

        modal.classList.remove('active');

        modal.setAttribute(
            'aria-hidden',
            'true'
        );

        document.body.style.overflow = '';

    }


    /* =====================================================
       DETAIL BUTTON
    ===================================================== */

    document
        .querySelectorAll('.btn-detail-laporan')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    openLaporanModal(this);

                }
            );

        });


    /* =====================================================
       CLOSE BUTTON
    ===================================================== */

    if (closeModal) {

        closeModal.addEventListener(
            'click',
            closeLaporanModal
        );

    }


    if (closeModalBottom) {

        closeModalBottom.addEventListener(
            'click',
            closeLaporanModal
        );

    }


    /* =====================================================
       CLICK OUTSIDE MODAL
    ===================================================== */

    if (modal) {

        modal.addEventListener(
            'click',
            function (event) {

                if (event.target === modal) {

                    closeLaporanModal();

                }

            }
        );

    }


    /* =====================================================
       ESC
    ===================================================== */

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape' &&
                modal &&
                modal.classList.contains('active')
            ) {

                closeLaporanModal();

            }

        }
    );



    filterLaporanTable();

});
</script>

@endsection