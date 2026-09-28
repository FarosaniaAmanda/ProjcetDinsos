@extends('admin.layouts.app')

@section('title', 'Monitoring Pendataan')

@section('content')

<style>
    /* =====================================================
       MONITORING PAGE
    ===================================================== */

    .monitoring-page {
        padding: 24px 32px 40px;
        background: #f5f7fb;
        min-height: calc(100vh - 70px);
        box-sizing: border-box;
    }


    /* =====================================================
       SINGLE LARGE CARD
       SEMUA ISI MONITORING BERADA DI SINI
    ===================================================== */

    .monitoring-main-card {
        width: 100%;
        background: #ffffff;
        border: 1px solid #e2e6f2;
        border-radius: 18px;
        box-shadow: 0 4px 18px rgba(37, 42, 134, 0.06);
        overflow: hidden;
    }


    /* =====================================================
       HEADER
    ===================================================== */

    .monitoring-header {
        padding: 24px 26px 20px;
        border-bottom: 1px solid #e8ebf3;
    }

    .monitoring-header h1 {
        margin: 0;
        font-size: 25px;
        line-height: 1.3;
        font-weight: 700;
        color: #252A86;
    }

    .monitoring-header p {
        margin: 8px 0 0;
        color: #64748b;
        font-size: 14px;
        line-height: 1.6;
    }


    /* =====================================================
       STATISTICS AREA
       CARD KECIL HANYA UNTUK STATISTIK
    ===================================================== */

    .monitoring-stats-wrapper {
        padding: 20px 26px 22px;
        border-bottom: 1px solid #e8ebf3;
    }

    .monitoring-stats {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 14px;
    }

    .monitoring-stat-card {
        background: #ffffff;
        border: 1px solid #e2e6f2;
        border-radius: 14px;
        padding: 17px 18px;
        display: flex;
        align-items: center;
        gap: 13px;
        min-height: 86px;
        box-shadow: 0 2px 8px rgba(37, 42, 134, 0.04);
        transition: .2s ease;
        box-sizing: border-box;
    }

    .monitoring-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 14px rgba(37, 42, 134, 0.08);
    }

    .monitoring-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eef0ff;
        color: #252A86;
        flex-shrink: 0;
    }

    .monitoring-stat-info {
        min-width: 0;
    }

    .monitoring-stat-label {
        color: #64748b;
        font-size: 12px;
        margin-bottom: 5px;
        line-height: 1.4;
    }

    .monitoring-stat-value {
        color: #252A86;
        font-size: 22px;
        line-height: 1.2;
        font-weight: 700;
    }


    /* =====================================================
       TABLE SECTION
    ===================================================== */

    .monitoring-table-section {
        width: 100%;
    }

    .monitoring-table-header {
        padding: 20px 26px 18px;
        border-bottom: 1px solid #e8ebf3;
    }

    .monitoring-table-title {
        font-size: 17px;
        line-height: 1.4;
        font-weight: 700;
        color: #252A86;
        margin: 0 0 5px;
    }

    .monitoring-table-description {
        margin: 0;
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
    }


    /* =====================================================
       SEARCH
    ===================================================== */

    .monitoring-search-wrapper {
        position: relative;
        margin-top: 17px;
    }

    .monitoring-search-box {
        position: relative;
        width: 100%;
    }

    .monitoring-search-box input {
        width: 100%;
        height: 46px;
        padding: 0 45px 0 16px;
        border: 1px solid #d5d9e7;
        border-radius: 10px;
        outline: none;
        font-size: 14px;
        color: #1e293b;
        background: #ffffff;
        transition: .2s ease;
        box-sizing: border-box;
    }

    .monitoring-search-box input::placeholder {
        color: #94a3b8;
    }

    .monitoring-search-box input:focus {
        border-color: #252A86;
        box-shadow: 0 0 0 3px rgba(37, 42, 134, 0.09);
    }

    .monitoring-search-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        pointer-events: none;
    }


    /* =====================================================
       SEARCH SUGGESTIONS
    ===================================================== */

    .monitoring-search-suggestions {
        display: none;
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: #ffffff;
        border: 1px solid #e2e6f2;
        border-radius: 10px;
        box-shadow: 0 8px 25px rgba(15, 23, 42, 0.10);
        z-index: 50;
        overflow: hidden;
    }

    .monitoring-search-suggestions.show {
        display: block;
    }

    .monitoring-suggestion-item {
        padding: 12px 15px;
        cursor: pointer;
        border-bottom: 1px solid #eef1f6;
        transition: .2s ease;
    }

    .monitoring-suggestion-item:last-child {
        border-bottom: none;
    }

    .monitoring-suggestion-item:hover {
        background: #f4f5ff;
    }

    .monitoring-suggestion-name {
        color: #252A86;
        font-size: 14px;
        font-weight: 600;
    }

    .monitoring-suggestion-detail {
        margin-top: 4px;
        color: #64748b;
        font-size: 12px;
    }


    /* =====================================================
       TABLE
    ===================================================== */

    .monitoring-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .monitoring-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 850px;
    }

    .monitoring-table th {
        background: #f1f3ff;
        color: #4b5563 !important;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .02em;
        padding: 14px 16px;
        border-bottom: 1px solid #e2e6f2;
        text-align: left;
        white-space: nowrap;
    }

    .monitoring-table td {
        padding: 15px 16px;
        border-bottom: 1px solid #eef1f6;
        color: #4b5563 !important;
        font-size: 13px;
        vertical-align: middle;
    }

    .monitoring-table td strong {
        color: #4b5563 !important;
        font-weight: 600;
    }

    .monitoring-table tbody tr {
        transition: .15s ease;
    }

    .monitoring-table tbody tr:hover {
        background: #fafbff;
    }

    .monitoring-table tbody tr:last-child td {
        border-bottom: none;
    }


    /* =====================================================
       STATUS
    ===================================================== */

    .monitoring-status {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .monitoring-status.approved {
        background: #ecfdf5;
        color: #047857;
    }

    .monitoring-status.reject {
        background: #fef2f2;
        color: #b91c1c;
    }

    .monitoring-status.draft {
        background: #fffbeb;
        color: #b45309;
    }

    .monitoring-status.pending {
        background: #eff6ff;
        color: #1d4ed8;
    }


    /* =====================================================
       DETAIL BUTTON
    ===================================================== */

    .monitoring-action-btn {
        border: 1px solid #d7daf2;
        background: #f1f3ff;
        color: #252A86;
        padding: 7px 13px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s ease;
        white-space: nowrap;
    }

    .monitoring-action-btn:hover {
        background: #252A86;
        color: #ffffff;
        border-color: #252A86;
        box-shadow: 0 3px 8px rgba(37, 42, 134, 0.18);
    }


    /* =====================================================
       EMPTY STATE
    ===================================================== */

    .monitoring-empty {
        text-align: center;
        padding: 45px 20px;
        color: #64748b;
    }

    .monitoring-empty-icon {
        width: 54px;
        height: 54px;
        margin: 0 auto 13px;
        border-radius: 14px;
        background: #f1f3ff;
        color: #8b91bd;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .monitoring-empty-title {
        font-weight: 700;
        color: #252A86;
        margin-bottom: 5px;
    }

    .monitoring-empty-text {
        font-size: 13px;
        color: #64748b;
    }


    /* =====================================================
       MODAL DETAIL
    ===================================================== */

    .monitoring-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.52);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        z-index: 3000;
        backdrop-filter: blur(3px);
    }

    .monitoring-modal-overlay.active {
        display: flex;
    }

    .monitoring-modal {
        width: min(900px, 100%);
        max-height: calc(100vh - 40px);
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 25px 70px rgba(15, 23, 42, 0.25);
        overflow: hidden;
        animation: monitoringModalShow .2s ease;
    }

    @keyframes monitoringModalShow {
        from {
            opacity: 0;
            transform: translateY(12px) scale(.98);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .monitoring-modal-header {
        padding: 18px 22px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        background: #ffffff;
    }

    .monitoring-modal-header-left h2 {
        margin: 0;
        font-size: 19px;
        color: #252A86;
        font-weight: 700;
    }

    .monitoring-modal-header-left p {
        margin: 4px 0 0;
        font-size: 12px;
        color: #64748b;
    }

    .monitoring-modal-close {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 9px;
        background: #f1f3ff;
        color: #252A86;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: .2s ease;
        flex-shrink: 0;
    }

    .monitoring-modal-close:hover {
        background: #252A86;
        color: #ffffff;
    }

    .monitoring-modal-body {
        padding: 22px;
        overflow-y: auto;
        max-height: calc(100vh - 150px);
    }


    /* =====================================================
       DETAIL SUMMARY
    ===================================================== */

    .monitoring-detail-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 22px;
    }

    .monitoring-detail-summary-card {
        border: 1px solid #e2e6f2;
        border-radius: 12px;
        padding: 15px;
        background: #f8f9ff;
    }

    .monitoring-detail-summary-label {
        color: #64748b;
        font-size: 11px;
        margin-bottom: 6px;
    }

    .monitoring-detail-summary-value {
        color: #252A86;
        font-size: 14px;
        font-weight: 700;
        word-break: break-word;
    }


    /* =====================================================
       DETAIL INFORMATION
    ===================================================== */

    .monitoring-detail-section {
        border: 1px solid #e2e6f2;
        border-radius: 13px;
        overflow: hidden;
        margin-bottom: 18px;
    }

    .monitoring-detail-section-title {
        padding: 13px 16px;
        background: #f1f3ff;
        border-bottom: 1px solid #e2e6f2;
        color: #252A86;
        font-size: 14px;
        font-weight: 700;
    }

    .monitoring-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .monitoring-detail-item {
        padding: 13px 16px;
        border-bottom: 1px solid #f1f5f9;
    }

    .monitoring-detail-item:nth-child(odd) {
        border-right: 1px solid #f1f5f9;
    }

    .monitoring-detail-label {
        color: #64748b;
        font-size: 11px;
        margin-bottom: 5px;
    }

    .monitoring-detail-value {
        color: #252A86;
        font-size: 13px;
        font-weight: 600;
        word-break: break-word;
    }


    /* =====================================================
       QUESTIONNAIRE
    ===================================================== */

    .monitoring-questionnaire-empty {
        padding: 30px 20px;
        text-align: center;
        color: #64748b;
    }

    .monitoring-questionnaire-empty svg {
        color: #8b91bd;
        margin-bottom: 9px;
    }

    .monitoring-questionnaire-empty strong {
        display: block;
        color: #252A86;
        font-size: 13px;
        margin-bottom: 4px;
    }

    .monitoring-questionnaire-empty span {
        font-size: 12px;
    }


    /* =====================================================
       QUESTIONNAIRE PART ACCORDION
    ===================================================== */

    .monitoring-questionnaire-content {
        padding: 14px;
    }

    .monitoring-questionnaire-part {
        border: 1px solid #e2e6f2;
        border-radius: 11px;
        overflow: hidden;
        margin-bottom: 10px;
        background: #ffffff;
    }

    .monitoring-questionnaire-part:last-child {
        margin-bottom: 0;
    }

    .monitoring-questionnaire-part-header {
        width: 100%;
        border: 0;
        background: #f8f9ff;
        color: #252A86;
        padding: 13px 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        cursor: pointer;
        text-align: left;
        transition: .2s ease;
    }

    .monitoring-questionnaire-part-header:hover,
    .monitoring-questionnaire-part-header.active {
        background: #eef0ff;
    }

    .monitoring-questionnaire-part-title {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .monitoring-questionnaire-part-number {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: #252A86;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .monitoring-questionnaire-part-name {
        font-size: 13px;
        font-weight: 700;
        color: #252A86;
    }

    .monitoring-questionnaire-part-meta {
        margin-top: 3px;
        font-size: 11px;
        color: #64748b;
        font-weight: 400;
    }

    .monitoring-questionnaire-chevron {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
        transition: transform .2s ease;
    }

    .monitoring-questionnaire-part-header.active .monitoring-questionnaire-chevron {
        transform: rotate(180deg);
    }

    .monitoring-questionnaire-part-body {
        display: none;
        padding: 12px;
        background: #ffffff;
    }

    .monitoring-questionnaire-part-body.active {
        display: block;
    }

    .monitoring-questionnaire-question {
        border: 1px solid #edf0f6;
        border-radius: 9px;
        padding: 11px 12px;
        margin-bottom: 9px;
        background: #ffffff;
    }

    .monitoring-questionnaire-question:last-child {
        margin-bottom: 0;
    }

    .monitoring-questionnaire-question-label {
        display: flex;
        gap: 8px;
        align-items: flex-start;
        color: #334155;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.55;
    }

    .monitoring-questionnaire-question-number {
        color: #252A86;
        font-weight: 700;
        flex-shrink: 0;
    }

    .monitoring-questionnaire-answer {
        margin-top: 8px;
        padding: 9px 10px;
        border-radius: 8px;
        background: #f8fafc;
        border-left: 3px solid #252A86;
        color: #475569;
        font-size: 12px;
        line-height: 1.55;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .monitoring-questionnaire-answer.empty {
        color: #94a3b8;
        font-style: italic;
    }

    .monitoring-questionnaire-part-empty {
        padding: 18px 10px;
        text-align: center;
        color: #94a3b8;
        font-size: 12px;
    }

    /* =====================================================
       RESPONSIVE
    ===================================================== */

    /* =====================================================
       TABLET
       3 CARD STATISTIK
    ===================================================== */

    @media (max-width: 1200px) {

        .monitoring-page {
            padding: 22px 24px 35px;
        }

        .monitoring-stats {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }


    /* =====================================================
       MOBILE
       2 CARD STATISTIK
    ===================================================== */

    @media (max-width: 700px) {

        .monitoring-page {
            padding: 16px 12px 30px;
        }

        .monitoring-main-card {
            border-radius: 15px;
        }

        .monitoring-header {
            padding: 19px 17px 17px;
        }

        .monitoring-header h1 {
            font-size: 21px;
        }

        .monitoring-header p {
            font-size: 13px;
        }

        .monitoring-stats-wrapper {
            padding: 15px;
        }

        .monitoring-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .monitoring-stat-card {
            min-height: 76px;
            padding: 13px;
        }

        .monitoring-stat-label {
            font-size: 11px;
        }

        .monitoring-stat-value {
            font-size: 19px;
        }

        .monitoring-table-header {
            padding: 17px;
        }

        .monitoring-table-title {
            font-size: 16px;
        }

        .monitoring-table-description {
            font-size: 12px;
        }

        .monitoring-modal-overlay {
            padding: 10px;
        }

        .monitoring-modal {
            border-radius: 14px;
            max-height: calc(100vh - 20px);
        }

        .monitoring-modal-header {
            padding: 15px 16px;
        }

        .monitoring-modal-body {
            padding: 15px;
            max-height: calc(100vh - 115px);
        }

        .monitoring-detail-grid {
            grid-template-columns: 1fr;
        }

        .monitoring-detail-item:nth-child(odd) {
            border-right: none;
        }

        .monitoring-detail-summary {
            grid-template-columns: 1fr;
        }
    }


    /* =====================================================
       HP SANGAT KECIL
       TETAP 2 CARD
    ===================================================== */

    @media (max-width: 400px) {

        .monitoring-page {
            padding: 12px 9px 25px;
        }

        .monitoring-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
        }

        .monitoring-stat-card {
            min-height: 70px;
            padding: 11px;
            border-radius: 11px;
        }

        .monitoring-stat-label {
            font-size: 10px;
        }

        .monitoring-stat-value {
            font-size: 18px;
        }
    }

</style>


<div class="monitoring-page">

    {{-- =====================================================
         SATU CARD BESAR
         SELURUH ISI MONITORING BERADA DI DALAM CARD INI
    ===================================================== --}}

    <div class="monitoring-main-card">


        {{-- =================================================
             HEADER MONITORING
        ================================================== --}}

        <div class="monitoring-header">

            <h1>
                Monitoring Pendataan
            </h1>

            <p>
                Memantau data responden yang telah dilakukan pendataan.
            </p>

        </div>


        {{-- =================================================
             STATISTICS
             CARD KECIL HANYA UNTUK STATISTIK
        ================================================== --}}

        <div class="monitoring-stats-wrapper">

            <div class="monitoring-stats">


                {{-- TOTAL RESPONDEN --}}

                <div class="monitoring-stat-card">

                    

                    <div class="monitoring-stat-info">

                        <div class="monitoring-stat-label">
                            Total Responden
                        </div>

                        <div class="monitoring-stat-value">
                            {{ $totalResponden ?? count($data ?? []) }}
                        </div>

                    </div>

                </div>


                {{-- SUDAH DIDATA --}}

                <div class="monitoring-stat-card">

                

                    <div class="monitoring-stat-info">

                        <div class="monitoring-stat-label">
                            Sudah Didata
                        </div>

                        <div class="monitoring-stat-value">
                            {{ $dataSudahDidata ?? count($data ?? []) }}
                        </div>

                    </div>

                </div>


                {{-- BELUM DIDATA --}}

                <div class="monitoring-stat-card">

                    <div class="monitoring-stat-info">

                        <div class="monitoring-stat-label">
                            Belum Didata
                        </div>

                        <div class="monitoring-stat-value">
                            {{ $belumDidata ?? 0 }}
                        </div>

                    </div>

                </div>


                {{-- DISETUJUI --}}

                <div class="monitoring-stat-card">


                    <div class="monitoring-stat-info">

                        <div class="monitoring-stat-label">
                            Disetujui
                        </div>

                        <div class="monitoring-stat-value">
                            {{ $disetujui ?? 0 }}
                        </div>

                    </div>

                </div>


                {{-- DITOLAK --}}

                <div class="monitoring-stat-card">


                    <div class="monitoring-stat-info">

                        <div class="monitoring-stat-label">
                            Ditolak
                        </div>

                        <div class="monitoring-stat-value">
                            {{ $ditolak ?? 0 }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
             TABLE SECTION
        ================================================== --}}

        <div class="monitoring-table-section">


            {{-- TABLE HEADER + SEARCH --}}

            <div class="monitoring-table-header">

                <div class="monitoring-table-title">
                    Data Responden
                </div>

                <p class="monitoring-table-description">
                    Daftar responden yang telah tercatat dalam sistem pendataan.
                </p>


                {{-- SEARCH --}}

                <div class="monitoring-search-wrapper">

                    <div class="monitoring-search-box">

                        <input
                            type="text"
                            id="monitoringSearch"
                            placeholder="Cari No. KK, NIK, atau Nama Kepala Keluarga"
                            autocomplete="off"
                        >

                        <div class="monitoring-search-icon">

                            <svg
                                width="18"
                                height="18"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
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

                    </div>


                    {{-- SEARCH SUGGESTIONS --}}

                    <div
                        class="monitoring-search-suggestions"
                        id="monitoringSearchSuggestions"
                    ></div>

                </div>

            </div>


            {{-- =================================================
                 TABLE
            ================================================== --}}

            <div class="monitoring-table-wrapper">

                <table class="monitoring-table">

                    <thead>

                        <tr>

                            <th>No.</th>

                            <th>No. KK</th>

                            <th>Nama Kepala Keluarga</th>

                            <th>Wilayah</th>

                            <th>Status</th>

                            <th>Petugas</th>

                            <th>Aksi</th>

                        </tr>

                    </thead>


                    <tbody id="monitoringTableBody">

                        @forelse($data ?? [] as $index => $item)

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

                                $wilayah = data_get(
                                    $item,
                                    'wilayah'
                                )
                                ?? '-';

                                $status = data_get(
                                    $item,
                                    'status'
                                )
                                ?? 'Draft';

                                $petugas = data_get(
                                    $item,
                                    'petugas'
                                )
                                ?? data_get(
                                    $item,
                                    'nama_petugas'
                                )
                                ?? '-';

                                $jumlahAnggota = data_get(
                                    $item,
                                    'jumlah_anggota_keluarga'
                                )
                                ?? data_get(
                                    $item,
                                    'jumlah_anggota'
                                )
                                ?? 0;

                                $tanggalPendataan = data_get(
                                    $item,
                                    'tanggal_pendataan'
                                )
                                ?? data_get(
                                    $item,
                                    'created_at'
                                )
                                ?? '-';

                            @endphp


                            <tr
                                data-no-kk="{{ strtolower($noKk) }}"
                                data-nik="{{ strtolower($nik) }}"
                                data-nama="{{ strtolower($nama) }}"
                                data-search="{{ strtolower(
                                    $noKk . ' ' .
                                    $nik . ' ' .
                                    $nama
                                ) }}"
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
                                    {{ $nama }}
                                </td>


                                <td>
                                    {{ $wilayah }}
                                </td>


                                <td>

                                    @php

                                        $statusClass = match (
                                            strtolower($status)
                                        ) {

                                            'approved',
                                            'disetujui'
                                                => 'approved',

                                            'reject',
                                            'ditolak'
                                                => 'reject',

                                            'pending',
                                            'menunggu'
                                                => 'pending',

                                            default
                                                => 'draft',

                                        };

                                    @endphp


                                    <span
                                        class="monitoring-status {{ $statusClass }}"
                                    >
                                        {{ $status }}
                                    </span>

                                </td>


                                <td>
                                    {{ $petugas }}
                                </td>


                                <td>

                                    <button
                                        type="button"
                                        class="monitoring-action-btn btn-detail-monitoring"

                                        data-id="{{ $itemId }}"

                                        data-no-kk="{{ e($noKk) }}"

                                        data-nik="{{ e($nik) }}"

                                        data-nama="{{ e($nama) }}"

                                        data-jumlah-anggota="{{ e($jumlahAnggota) }}"

                                        data-wilayah="{{ e($wilayah) }}"

                                        data-petugas="{{ e($petugas) }}"

                                        data-tanggal="{{ e($tanggalPendataan) }}"

                                        data-status="{{ e($status) }}"
                                        data-kuisioner="{{ e(json_encode(data_get($item, 'kuisioner', data_get($item, 'questionnaire', data_get($item, 'jawaban_kuisioner', data_get($item, 'answers', [])))), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) }}"
                                    >
                                        Detail
                                    </button>

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td colspan="7">

                                    <div class="monitoring-empty">

                                        <div class="monitoring-empty-icon">

                                            <svg
                                                width="25"
                                                height="25"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                            >

                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>

                                                <circle
                                                    cx="12"
                                                    cy="7"
                                                    r="4"
                                                ></circle>

                                            </svg>

                                        </div>


                                        <div class="monitoring-empty-title">
                                            Belum Ada Data
                                        </div>


                                        <div class="monitoring-empty-text">
                                            Belum terdapat data responden yang dapat ditampilkan.
                                        </div>

                                    </div>

                                </td>

                            </tr>

                        @endforelse


                        {{-- EMPTY SEARCH --}}

                        <tr
                            id="monitoringSearchEmpty"
                            style="display:none;"
                        >

                            <td colspan="7">

                                <div class="monitoring-empty">

                                    <div class="monitoring-empty-icon">

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


                                    <div class="monitoring-empty-title">
                                        Data Tidak Ditemukan
                                    </div>


                                    <div class="monitoring-empty-text">
                                        Tidak ada data yang sesuai dengan pencarian.
                                    </div>

                                </div>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>



{{-- =========================================================
     MODAL DETAIL MONITORING
========================================================= --}}

<div
    class="monitoring-modal-overlay"
    id="monitoringDetailModal"
    aria-hidden="true"
>

    <div
        class="monitoring-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="monitoringDetailTitle"
    >


        {{-- MODAL HEADER --}}

        <div class="monitoring-modal-header">

            <div class="monitoring-modal-header-left">

                <h2 id="monitoringDetailTitle">
                    Detail Data Pendataan
                </h2>

                <p>
                    Informasi lengkap data responden
                </p>

            </div>


            <button
                type="button"
                class="monitoring-modal-close"
                id="closeMonitoringModal"
                aria-label="Tutup"
            >

                <svg
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >

                    <line
                        x1="18"
                        y1="6"
                        x2="6"
                        y2="18"
                    ></line>

                    <line
                        x1="6"
                        y1="6"
                        x2="18"
                        y2="18"
                    ></line>

                </svg>

            </button>

        </div>


        {{-- MODAL BODY --}}

        <div class="monitoring-modal-body">


            {{-- SUMMARY --}}

            <div class="monitoring-detail-summary">

                <div class="monitoring-detail-summary-card">

                    <div class="monitoring-detail-summary-label">
                        Nama Responden
                    </div>

                    <div
                        class="monitoring-detail-summary-value"
                        id="detailNamaSummary"
                    >
                        -
                    </div>

                </div>


                <div class="monitoring-detail-summary-card">

                    <div class="monitoring-detail-summary-label">
                        Nomor KK
                    </div>

                    <div
                        class="monitoring-detail-summary-value"
                        id="detailKkSummary"
                    >
                        -
                    </div>

                </div>


                <div class="monitoring-detail-summary-card">

                    <div class="monitoring-detail-summary-label">
                        Status Pendataan
                    </div>

                    <div
                        class="monitoring-detail-summary-value"
                        id="detailStatusSummary"
                    >
                        -
                    </div>

                </div>

            </div>


            {{-- INFORMASI RESPONDEN --}}

            <div class="monitoring-detail-section">

                <div class="monitoring-detail-section-title">
                    Informasi Responden
                </div>


                <div class="monitoring-detail-grid">


                    <div class="monitoring-detail-item">

                        <div class="monitoring-detail-label">
                            Nomor KK
                        </div>

                        <div
                            class="monitoring-detail-value"
                            id="detailNoKk"
                        >
                            -
                        </div>

                    </div>


                    <div class="monitoring-detail-item">

                        <div class="monitoring-detail-label">
                            NIK
                        </div>

                        <div
                            class="monitoring-detail-value"
                            id="detailNik"
                        >
                            -
                        </div>

                    </div>


                    <div class="monitoring-detail-item">

                        <div class="monitoring-detail-label">
                            Nama Lengkap
                        </div>

                        <div
                            class="monitoring-detail-value"
                            id="detailNama"
                        >
                            -
                        </div>

                    </div>


                    <div class="monitoring-detail-item">

                        <div class="monitoring-detail-label">
                            Jumlah Anggota Keluarga
                        </div>

                        <div
                            class="monitoring-detail-value"
                            id="detailJumlahAnggota"
                        >
                            -
                        </div>

                    </div>


                    <div class="monitoring-detail-item">

                        <div class="monitoring-detail-label">
                            Wilayah
                        </div>

                        <div
                            class="monitoring-detail-value"
                            id="detailWilayah"
                        >
                            -
                        </div>

                    </div>


                    <div class="monitoring-detail-item">

                        <div class="monitoring-detail-label">
                            Petugas
                        </div>

                        <div
                            class="monitoring-detail-value"
                            id="detailPetugas"
                        >
                            -
                        </div>

                    </div>


                    <div class="monitoring-detail-item">

                        <div class="monitoring-detail-label">
                            Tanggal Pendataan
                        </div>

                        <div
                            class="monitoring-detail-value"
                            id="detailTanggal"
                        >
                            -
                        </div>

                    </div>


                    <div class="monitoring-detail-item">

                        <div class="monitoring-detail-label">
                            Status
                        </div>

                        <div
                            class="monitoring-detail-value"
                            id="detailStatus"
                        >
                            -
                        </div>

                    </div>

                </div>

            </div>


            {{-- HASIL KUISIONER --}}

            <div class="monitoring-detail-section">

                <div class="monitoring-detail-section-title">
                    Hasil Kuisioner
                </div>


                <div
                    class="monitoring-questionnaire-content"
                    id="monitoringQuestionnaireContent"
                >
                    <div class="monitoring-questionnaire-empty">
                        <strong>Memuat Hasil Kuisioner</strong>
                        <span>Data kuisioner akan ditampilkan setelah detail dibuka.</span>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>



<script>

document.addEventListener('DOMContentLoaded', function () {


    /* =====================================================
       SEARCH MONITORING
    ===================================================== */

    const searchInput =
        document.getElementById('monitoringSearch');

    const suggestionsBox =
        document.getElementById(
            'monitoringSearchSuggestions'
        );

    const tableBody =
        document.getElementById(
            'monitoringTableBody'
        );

    const searchEmpty =
        document.getElementById(
            'monitoringSearchEmpty'
        );


    function getMonitoringRows() {

        return Array.from(
            tableBody.querySelectorAll(
                'tr[data-search]'
            )
        );

    }


    function filterTable(keyword) {

        const search =
            keyword.trim().toLowerCase();

        const rows =
            getMonitoringRows();

        let visibleCount = 0;


        rows.forEach(function (row) {

            const searchableText =
                row.getAttribute(
                    'data-search'
                ) || '';

            const match =
                searchableText.includes(
                    search
                );


            row.style.display =
                match ? '' : 'none';


            if (match) {
                visibleCount++;
            }

        });


        if (searchEmpty) {

            searchEmpty.style.display =
                rows.length > 0 &&
                visibleCount === 0
                    ? ''
                    : 'none';

        }

    }


    function showSuggestions(keyword) {

        if (!suggestionsBox) {
            return;
        }


        const search =
            keyword.trim().toLowerCase();


        suggestionsBox.innerHTML = '';


        if (!search) {

            suggestionsBox.classList.remove(
                'show'
            );

            return;

        }


        const rows =
            getMonitoringRows();


        const matchedRows =
            rows.filter(function (row) {

                const searchableText =
                    row.getAttribute(
                        'data-search'
                    ) || '';

                return searchableText.includes(
                    search
                );

            }).slice(0, 6);


        if (matchedRows.length === 0) {

            suggestionsBox.classList.remove(
                'show'
            );

            return;

        }


        matchedRows.forEach(function (row) {

            const nama =
                row.getAttribute(
                    'data-nama'
                ) || '-';


            const noKk =
                row.getAttribute(
                    'data-no-kk'
                ) || '-';


            const nik =
                row.getAttribute(
                    'data-nik'
                ) || '-';


            const item =
                document.createElement(
                    'div'
                );


            item.className =
                'monitoring-suggestion-item';


            item.innerHTML = `

                <div class="monitoring-suggestion-name">
                    ${escapeHtml(nama)}
                </div>

                <div class="monitoring-suggestion-detail">
                    No. KK: ${escapeHtml(noKk)}
                    &nbsp; | &nbsp;
                    NIK: ${escapeHtml(nik)}
                </div>

            `;


            item.addEventListener(
                'click',
                function () {

                    searchInput.value =
                        nama;


                    filterTable(
                        nama
                    );


                    suggestionsBox.classList.remove(
                        'show'
                    );

                }
            );


            suggestionsBox.appendChild(
                item
            );

        });


        suggestionsBox.classList.add(
            'show'
        );

    }


    function escapeHtml(value) {

        return String(value)

            .replace(
                /&/g,
                '&amp;'
            )

            .replace(
                /</g,
                '&lt;'
            )

            .replace(
                />/g,
                '&gt;'
            )

            .replace(
                /"/g,
                '&quot;'
            )

            .replace(
                /'/g,
                '&#039;'
            );

    }


    if (searchInput) {

        searchInput.addEventListener(
            'input',
            function () {

                filterTable(
                    this.value
                );

                showSuggestions(
                    this.value
                );

            }
        );

    }


    document.addEventListener(
        'click',
        function (event) {

            if (

                suggestionsBox &&

                searchInput &&

                !searchInput.contains(
                    event.target
                ) &&

                !suggestionsBox.contains(
                    event.target
                )

            ) {

                suggestionsBox.classList.remove(
                    'show'
                );

            }

        }
    );



    /* =====================================================
       MODAL DETAIL MONITORING
    ===================================================== */

    const modal =
        document.getElementById(
            'monitoringDetailModal'
        );


    const closeModalButton =
        document.getElementById(
            'closeMonitoringModal'
        );


    const detailNamaSummary =
        document.getElementById('detailNamaSummary');

    const detailKkSummary =
        document.getElementById('detailKkSummary');

    const detailStatusSummary =
        document.getElementById('detailStatusSummary');

    const detailNoKk =
        document.getElementById('detailNoKk');

    const detailNik =
        document.getElementById('detailNik');

    const detailNama =
        document.getElementById('detailNama');

    const detailJumlahAnggota =
        document.getElementById('detailJumlahAnggota');

    const detailWilayah =
        document.getElementById('detailWilayah');

    const detailPetugas =
        document.getElementById('detailPetugas');

    const detailTanggal =
        document.getElementById('detailTanggal');

    const detailStatus =
        document.getElementById('detailStatus');

    const questionnaireContent =
        document.getElementById('monitoringQuestionnaireContent');


    /* =====================================================
       NORMALISASI DATA KUISIONER
       Mendukung beberapa bentuk data dari controller/database.
    ===================================================== */

    function firstValue(object, keys) {
        if (!object || typeof object !== 'object') return undefined;

        for (const key of keys) {
            if (Object.prototype.hasOwnProperty.call(object, key) &&
                object[key] !== null && object[key] !== undefined &&
                object[key] !== '') {
                return object[key];
            }
        }

        return undefined;
    }

    function formatAnswer(value) {
        if (value === null || value === undefined || value === '') {
            return '';
        }

        if (Array.isArray(value)) {
            return value.map(function (item) {
                if (item && typeof item === 'object') {
                    return firstValue(item, ['label', 'nama', 'name', 'value', 'jawaban']) ?? JSON.stringify(item);
                }
                return String(item);
            }).join(', ');
        }

        if (typeof value === 'object') {
            return firstValue(value, ['label', 'nama', 'name', 'value', 'jawaban', 'answer']) ?? JSON.stringify(value);
        }

        return String(value);
    }

    function normalizeQuestion(item, fallbackNumber) {
        if (!item || typeof item !== 'object') {
            return {
                question: String(item ?? ''),
                answer: '',
                number: fallbackNumber
            };
        }

        const question = firstValue(item, [
            'pertanyaan', 'question', 'question_text', 'nama_pertanyaan',
            'teks_pertanyaan', 'text', 'label', 'judul'
        ]) ?? 'Pertanyaan';

        const answer = firstValue(item, [
            'jawaban', 'answer', 'response', 'nilai', 'value', 'hasil', 'respon', 'selected', 'selected_answer'
        ]);

        return {
            question: String(question),
            answer: formatAnswer(answer),
            number: firstValue(item, ['nomor', 'number', 'no']) ?? fallbackNumber
        };
    }

    function partNumberFromKey(key, fallback) {
        const match = String(key).match(/(?:part|bagian|section)[\s_-]*(\d+)/i);
        return match ? parseInt(match[1], 10) : fallback;
    }

    function normalizeQuestionnaire(raw) {
        let source = raw;

        if (typeof source === 'string') {
            try {
                source = JSON.parse(source);
            } catch (error) {
                return [];
            }
        }

        if (!source) return [];

        if (source.questions || source.pertanyaan || source.items) {
            source = source.questions || source.pertanyaan || source.items;
        }

        const parts = [];

        function addPart(number, title, items) {
            if (!Array.isArray(items)) return;
            parts.push({
                number: number,
                title: title || ('Part ' + number),
                items: items.map(function (item, index) {
                    return normalizeQuestion(item, index + 1);
                })
            });
        }

        if (Array.isArray(source)) {
            const structured = source.some(function (item) {
                return item && typeof item === 'object' && (
                    item.part || item.bagian || item.section ||
                    item.part_name || item.bagian_name || item.section_name ||
                    Array.isArray(item.questions) || Array.isArray(item.pertanyaan) || Array.isArray(item.items)
                );
            });

            if (structured) {
                const grouped = {};

                source.forEach(function (item) {
                    if (!item || typeof item !== 'object') return;

                    const partRaw = firstValue(item, ['part', 'bagian', 'section', 'part_number', 'bagian_number', 'section_number']) ?? 1;
                    const partMatch = String(partRaw).match(/\d+/);
                    const number = partMatch ? parseInt(partMatch[0], 10) : 1;
                    const title = firstValue(item, ['part_name', 'bagian_name', 'section_name', 'part_title', 'bagian_title', 'section_title']) || ('Part ' + number);
                    const items = item.questions || item.pertanyaan || item.items;

                    if (Array.isArray(items)) {
                        grouped[number] = grouped[number] || { number: number, title: title, items: [] };
                        grouped[number].items.push.apply(grouped[number].items, items);
                    } else {
                        grouped[number] = grouped[number] || { number: number, title: title, items: [] };
                        grouped[number].items.push(item);
                    }
                });

                Object.keys(grouped).sort(function (a, b) { return Number(a) - Number(b); }).forEach(function (key) {
                    const part = grouped[key];
                    addPart(part.number, part.title, part.items);
                });
            } else {
                addPart(1, 'Part 1', source);
            }
        } else if (typeof source === 'object') {
            const candidate = source.parts || source.bagian || source.sections;

            if (Array.isArray(candidate)) {
                candidate.forEach(function (part, index) {
                    if (Array.isArray(part)) {
                        addPart(index + 1, 'Part ' + (index + 1), part);
                        return;
                    }

                    if (!part || typeof part !== 'object') return;
                    const number = Number(firstValue(part, ['number', 'nomor', 'part_number', 'id']) ?? (index + 1));
                    const title = firstValue(part, ['title', 'name', 'nama', 'part_name', 'bagian_name']) || ('Part ' + number);
                    const items = part.questions || part.pertanyaan || part.items || part.answers || [];
                    addPart(number, title, items);
                });
            } else {
                Object.keys(source).forEach(function (key, index) {
                    const value = source[key];
                    if (!Array.isArray(value)) return;
                    const number = partNumberFromKey(key, index + 1);
                    addPart(number, /part|bagian|section/i.test(key) ? key.replace(/[_-]+/g, ' ') : 'Part ' + number, value);
                });
            }
        }

        const unique = {};
        parts.forEach(function (part) {
            if (!unique[part.number]) {
                unique[part.number] = part;
            } else {
                unique[part.number].items.push.apply(unique[part.number].items, part.items);
            }
        });

        return Object.values(unique).sort(function (a, b) { return a.number - b.number; });
    }

    function renderQuestionnaire(raw) {
        if (!questionnaireContent) return;

        const parts = normalizeQuestionnaire(raw);

        if (!parts.length || !parts.some(function (part) { return part.items.length > 0; })) {
            questionnaireContent.innerHTML = `
                <div class="monitoring-questionnaire-empty">
                    <strong>Belum Ada Hasil Kuisioner</strong>
                    <span>Hasil kuisioner untuk responden ini belum tersedia.</span>
                </div>
            `;
            return;
        }

        questionnaireContent.innerHTML = parts.map(function (part, partIndex) {
            const answered = part.items.filter(function (item) { return item.answer.trim() !== ''; }).length;
            const active = partIndex === 0;

            return `
                <div class="monitoring-questionnaire-part">
                    <button type="button" class="monitoring-questionnaire-part-header ${active ? 'active' : ''}" aria-expanded="${active ? 'true' : 'false'}">
                        <span class="monitoring-questionnaire-part-title">
                            <span class="monitoring-questionnaire-part-number">${escapeHtml(part.number)}</span>
                            <span>
                                <span class="monitoring-questionnaire-part-name">${escapeHtml(part.title)}</span>
                                <span class="monitoring-questionnaire-part-meta">${part.items.length} Pertanyaan · ${answered} Terjawab${answered < part.items.length ? ' · ' + (part.items.length - answered) + ' Belum Diisi' : ''}</span>
                            </span>
                        </span>
                        <svg class="monitoring-questionnaire-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>

                    <div class="monitoring-questionnaire-part-body ${active ? 'active' : ''}">
                        ${part.items.length ? part.items.map(function (item, index) {
                            const answer = item.answer.trim() !== '' ? item.answer : 'Belum diisi';
                            return `
                                <div class="monitoring-questionnaire-question">
                                    <div class="monitoring-questionnaire-question-label">
                                        <span class="monitoring-questionnaire-question-number">${escapeHtml(item.number || (index + 1))}.</span>
                                        <span>${escapeHtml(item.question)}</span>
                                    </div>
                                    <div class="monitoring-questionnaire-answer ${item.answer.trim() === '' ? 'empty' : ''}">${escapeHtml(answer)}</div>
                                </div>
                            `;
                        }).join('') : '<div class="monitoring-questionnaire-part-empty">Belum ada pertanyaan pada bagian ini.</div>'}
                    </div>
                </div>
            `;
        }).join('');

        questionnaireContent.querySelectorAll('.monitoring-questionnaire-part-header').forEach(function (header) {
            header.addEventListener('click', function () {
                const currentPart = this.closest('.monitoring-questionnaire-part');
                const shouldOpen = !this.classList.contains('active');

                questionnaireContent.querySelectorAll('.monitoring-questionnaire-part').forEach(function (part) {
                    const partHeader = part.querySelector('.monitoring-questionnaire-part-header');
                    const partBody = part.querySelector('.monitoring-questionnaire-part-body');
                    const isCurrent = part === currentPart;
                    const open = isCurrent && shouldOpen;

                    partHeader.classList.toggle('active', open);
                    partHeader.setAttribute('aria-expanded', open ? 'true' : 'false');
                    partBody.classList.toggle('active', open);
                });
            });
        });
    }

    function openDetailModal(button) {
        detailNamaSummary.textContent = button.dataset.nama || '-';
        detailKkSummary.textContent = button.dataset.noKk || '-';
        detailStatusSummary.textContent = button.dataset.status || '-';
        detailNoKk.textContent = button.dataset.noKk || '-';
        detailNik.textContent = button.dataset.nik || '-';
        detailNama.textContent = button.dataset.nama || '-';
        detailJumlahAnggota.textContent = button.dataset.jumlahAnggota || '0';
        detailWilayah.textContent = button.dataset.wilayah || '-';
        detailPetugas.textContent = button.dataset.petugas || '-';
        detailTanggal.textContent = button.dataset.tanggal || '-';
        detailStatus.textContent = button.dataset.status || '-';

        let questionnaire = [];
        try {
            questionnaire = button.dataset.kuisioner ? JSON.parse(button.dataset.kuisioner) : [];
        } catch (error) {
            questionnaire = [];
        }

        renderQuestionnaire(questionnaire);

        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }


    function closeDetailModal() {

        modal.classList.remove(
            'active'
        );


        modal.setAttribute(
            'aria-hidden',
            'true'
        );


        document.body.style.overflow =
            '';

        if (questionnaireContent) {
            questionnaireContent.innerHTML = `
                <div class="monitoring-questionnaire-empty">
                    <strong>Belum Ada Hasil Kuisioner</strong>
                    <span>Hasil kuisioner akan tampil saat Detail dibuka.</span>
                </div>
            `;
        }

    }


    /* =====================================================
       DETAIL BUTTON
    ===================================================== */

    document
        .querySelectorAll(
            '.btn-detail-monitoring'
        )
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    openDetailModal(
                        this
                    );

                }
            );

        });


    /* =====================================================
       CLOSE BUTTON
    ===================================================== */

    if (closeModalButton) {

        closeModalButton.addEventListener(
            'click',
            closeDetailModal
        );

    }


    /* =====================================================
       CLICK OUTSIDE MODAL
    ===================================================== */

    if (modal) {

        modal.addEventListener(
            'click',
            function (event) {

                if (
                    event.target === modal
                ) {

                    closeDetailModal();

                }

            }
        );

    }


    /* =====================================================
       ESC KEY
    ===================================================== */

    document.addEventListener(
        'keydown',
        function (event) {

            if (

                event.key === 'Escape' &&

                modal &&

                modal.classList.contains(
                    'active'
                )

            ) {

                closeDetailModal();

            }

        }
    );

});

</script>

@endsection

