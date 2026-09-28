@extends('admin.layouts.app')

@section('title', 'Verifikasi Data')

@push('styles')
<style>
    /* =====================================================
       CARD BESAR VERIFIKASI
    ====================================================== */

    .verification-card {
        background: #ffffff;
        border: 1px solid #e8e9ef;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
    }

    /* =====================================================
       PAGE HEADER DI DALAM CARD BESAR
    ====================================================== */

    .verification-header {
        padding: 28px 28px 22px;
    }

    .page-title {
        font-size: 26px;
        font-weight: 700;
        color: #252A86;
        line-height: 1.25;
        margin: 0 0 8px;
    }

    .page-description {
        font-size: 14px;
        color: #777;
        line-height: 1.6;
        max-width: 700px;
        margin: 0;
    }

    /* =====================================================
       ALERT SUCCESS
    ====================================================== */

    .alert-success {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 28px 20px;
        padding: 13px 16px;
        border-radius: 9px;
        background: #eaf8ef;
        border: 1px solid #bce5c9;
        color: #24723c;
        font-size: 13px;
        animation: alertFade .3s ease;
    }

    .alert-success svg {
        flex-shrink: 0;
    }

    @keyframes alertFade {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* =====================================================
       STATISTICS
       CARD KECIL HANYA UNTUK STATISTIK
    ====================================================== */

    .stats-section {
        padding: 0 28px 26px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 12px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #e8e9ef;
        border-radius: 11px;
        padding: 15px;
        min-width: 0;
        transition: all .2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }

    .stat-label {
        font-size: 11px;
        color: #777;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .stat-value {
        font-size: 23px;
        font-weight: 700;
        color: #252A86;
        line-height: 1;
    }

    /* =====================================================
       SEARCH SECTION
       MASIH DI DALAM CARD BESAR
    ====================================================== */

    .search-section {
        padding: 0 28px 24px;
    }

    .search-title {
        font-size: 14px;
        font-weight: 700;
        color: #333;
        margin-bottom: 9px;
    }

    .search-form {
        display: flex;
        align-items: center;
        width: 100%;
    }

    .search-box {
        position: relative;
        width: 100%;
    }

    .search-box > svg {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        pointer-events: none;
        z-index: 2;
    }

    .search-box input {
        width: 100%;
        height: 43px;
        padding: 0 13px 0 40px;
        border: 1px solid #dfe1e8;
        border-radius: 8px;
        outline: none;
        font-size: 13px;
        color: #333;
        background: #ffffff;
        transition: all .2s ease;
        box-sizing: border-box;
    }

    .search-box input:focus {
        border-color: #252A86;
        box-shadow: 0 0 0 3px rgba(37, 42, 134, 0.08);
    }

    .search-box input::placeholder {
        color: #a0a0a0;
    }

    /* =====================================================
       SEARCH SUGGESTIONS
    ====================================================== */

    .search-suggestions {
        position: absolute;
        left: 0;
        right: 0;
        top: calc(100% + 5px);
        background: #ffffff;
        border: 1px solid #e1e3eb;
        border-radius: 9px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        z-index: 100;
        display: none;
        max-height: 250px;
        overflow-y: auto;
    }

    .search-suggestions.show {
        display: block;
    }

    .suggestion-item {
        display: flex;
        align-items: center;
        gap: 9px;
        width: 100%;
        padding: 9px 11px;
        border: none;
        border-bottom: 1px solid #f0f1f5;
        background: #ffffff;
        text-align: left;
        cursor: pointer;
        transition: background .15s ease;
        box-sizing: border-box;
    }

    .suggestion-item:last-child {
        border-bottom: none;
    }

    .suggestion-item:hover {
        background: #f6f7ff;
    }

    .suggestion-icon {
        width: 28px;
        height: 28px;
        border-radius: 7px;
        background: #eef0ff;
        color: #252A86;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .suggestion-content {
        min-width: 0;
        flex: 1;
    }

    .suggestion-name {
        font-size: 12px;
        font-weight: 600;
        color: #333;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .suggestion-detail {
        font-size: 10.5px;
        color: #888;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .suggestion-empty {
        padding: 12px;
        text-align: center;
        font-size: 11px;
        color: #999;
    }

    .search-result {
        margin-top: 7px;
        padding-left: 2px;
        font-size: 11px;
        color: #777;
    }

    .search-result strong {
        color: #252A86;
        font-weight: 700;
    }

    /* =====================================================
       MONITORING SECTION
       BAGIAN INI BUKAN CARD TERPISAH
    ====================================================== */

    .monitoring-section {
        border-top: 1px solid #e8e9ef;
    }

    .monitoring-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        padding: 22px 28px;
    }

    .monitoring-title {
        font-size: 17px;
        font-weight: 700;
        color: #252A86;
        margin-bottom: 5px;
    }

    .monitoring-description {
        font-size: 12px;
        color: #888;
        line-height: 1.5;
    }

    /* =====================================================
       FILTER
    ====================================================== */

    .filter-wrapper {
        flex-shrink: 0;
    }

    .filter-select {
        height: 38px;
        min-width: 170px;
        padding: 0 12px;
        border: 1px solid #dfe1e8;
        border-radius: 8px;
        background: #ffffff;
        color: #444;
        font-size: 12px;
        outline: none;
        cursor: pointer;
        transition: all .2s ease;
    }

    .filter-select:focus {
        border-color: #252A86;
        box-shadow: 0 0 0 3px rgba(37, 42, 134, 0.08);
    }

    /* =====================================================
       TABLE
    ====================================================== */

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
        border-top: 1px solid #e8e9ef;
    }

    .data-table {
        width: 100%;
        min-width: 1050px;
        border-collapse: collapse;
    }

    .data-table th {
        padding: 13px 14px;
        background: #f8f8fb;
        border-bottom: 1px solid #e8e9ef;
        color: #666;
        font-size: 11px;
        font-weight: 700;
        text-align: left;
        white-space: nowrap;
    }

    .data-table td {
        padding: 14px;
        border-bottom: 1px solid #eeeef2;
        color: #444;
        font-size: 12px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .data-table tbody tr {
        transition: background .15s ease;
    }

    .data-table tbody tr:hover {
        background: #fafaff;
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* =====================================================
       STATUS BADGE
    ====================================================== */

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-pending {
        background: #fff4db;
        color: #9a6a00;
    }

    .status-draft {
        background: #f0f0f2;
        color: #666;
    }

    .status-not-processed {
        background: #f1f2f8;
        color: #5f6380;
    }

    .status-approved {
        background: #eaf8ef;
        color: #24723c;
    }

    .status-rejected {
        background: #fdecec;
        color: #a53636;
    }

    /* =====================================================
       ACTION BUTTON
    ====================================================== */

    .action-wrapper {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-detail {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 32px;
        padding: 0 11px;
        border: none;
        border-radius: 7px;
        background: #eef0ff;
        color: #252A86;
        text-decoration: none;
        font-size: 11px;
        font-weight: 600;
        transition: all .2s ease;
        white-space: nowrap;
        cursor: pointer;
    }

    .btn-detail:hover {
        background: #252A86;
        color: #ffffff;
    }

    /* =====================================================
       EMPTY STATE
    ====================================================== */

    .empty-state {
        padding: 50px 20px;
        text-align: center;
    }

    .empty-state-icon {
        width: 52px;
        height: 52px;
        margin: 0 auto 14px;
        border-radius: 50%;
        background: #f1f2f8;
        color: #8b8fa8;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-state-title {
        font-size: 14px;
        font-weight: 700;
        color: #555;
        margin-bottom: 5px;
    }

    .empty-state-description {
        font-size: 12px;
        color: #999;
    }

    /* =====================================================
       MODAL DETAIL
    ====================================================== */

    .verification-modal {
        position: fixed;
        inset: 0;
        z-index: 2000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .verification-modal.show {
        display: flex;
    }

    .verification-modal-overlay {
        position: absolute;
        inset: 0;
        background: rgba(15, 18, 45, 0.55);
        backdrop-filter: blur(2px);
    }

    .verification-modal-box {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 620px;
        max-height: calc(100vh - 40px);
        overflow-y: auto;
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
        animation: modalShow .2s ease;
    }

    @keyframes modalShow {
        from {
            opacity: 0;
            transform: translateY(12px) scale(.98);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .verification-modal-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 15px;
        padding: 18px 20px;
        border-bottom: 1px solid #e8e9ef;
    }

    .verification-modal-header-content {
        min-width: 0;
    }

    .verification-modal-kicker {
        font-size: 10px;
        font-weight: 700;
        color: #252A86;
        text-transform: uppercase;
        letter-spacing: .8px;
        margin-bottom: 4px;
    }

    .verification-modal-title {
        font-size: 19px;
        font-weight: 700;
        color: #252A86;
        margin: 0;
    }

    .verification-modal-subtitle {
        font-size: 11px;
        color: #888;
        margin-top: 4px;
    }

    .verification-modal-close {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 7px;
        background: #f3f4f8;
        color: #666;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        transition: all .2s ease;
    }

    .verification-modal-close:hover {
        background: #252A86;
        color: #ffffff;
    }

    .verification-modal-body {
        padding: 18px 20px 20px;
    }

    .verification-modal-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        margin-bottom: 18px;
    }

    .verification-field {
        background: #f8f9fb;
        border: 1px solid #ececf1;
        border-radius: 9px;
        padding: 11px 13px;
    }

    .verification-field label {
        display: block;
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #888;
        margin-bottom: 4px;
        font-weight: 700;
    }

    .verification-field strong {
        display: block;
        font-size: 12px;
        color: #333;
        line-height: 1.4;
        word-break: break-word;
    }

    .verification-form-group {
        margin-top: 4px;
    }

    .verification-form-group > label {
        display: block;
        margin-bottom: 7px;
        font-weight: 700;
        color: #333;
        font-size: 12px;
    }

    .verification-form-group select {
        width: 100%;
        height: 40px;
        border: 1px solid #dfe1e8;
        border-radius: 8px;
        padding: 0 11px;
        font-size: 12px;
        background: #fff;
        color: #333;
        outline: none;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .verification-form-group select:focus {
        border-color: #252A86;
        box-shadow: 0 0 0 3px rgba(37, 42, 134, .10);
    }

    .verification-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 16px;
    }

    .verification-btn {
        min-height: 36px;
        border: none;
        border-radius: 7px;
        padding: 8px 14px;
        cursor: pointer;
        text-decoration: none;
        font-weight: 600;
        font-size: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all .2s ease;
    }

    .verification-btn-primary {
        background: #252A86;
        color: #ffffff;
    }

    .verification-btn-primary:hover {
        background: #1e236f;
        transform: translateY(-1px);
    }

    .verification-btn-secondary {
        background: #eef0f7;
        color: #333;
    }

    .verification-btn-secondary:hover {
        background: #e1e4ee;
    }

    /* =====================================================
       RESPONSIVE
    ====================================================== */

    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .verification-header {
            padding: 24px 20px 20px;
        }

        .page-title {
            font-size: 23px;
        }

        .stats-section {
            padding: 0 20px 22px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .search-section {
            padding: 0 20px 22px;
        }

        .monitoring-header {
            padding: 20px;
            flex-direction: column;
            align-items: stretch;
        }

        .filter-wrapper {
            width: 100%;
        }

        .filter-select {
            width: 100%;
        }

        .alert-success {
            margin-left: 20px;
            margin-right: 20px;
        }

        .verification-modal {
            padding: 15px;
        }

        .verification-modal-box {
            max-height: calc(100vh - 30px);
        }
    }

    @media (max-width: 600px) {
        .verification-header {
            padding: 20px 16px 18px;
        }

        .page-title {
            font-size: 21px;
        }

        .page-description {
            font-size: 13px;
        }

        .stats-section {
            padding: 0 16px 20px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 9px;
        }

        .stat-card {
            padding: 13px;
        }

        .stat-value {
            font-size: 21px;
        }

        .search-section {
            padding: 0 16px 20px;
        }

        .search-box input {
            height: 40px;
            font-size: 12px;
        }

        .monitoring-header {
            padding: 17px 16px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .verification-modal {
            padding: 10px;
        }

        .verification-modal-box {
            border-radius: 12px;
            max-height: calc(100vh - 20px);
        }

        .verification-modal-header {
            padding: 15px 16px;
        }

        .verification-modal-body {
            padding: 15px 16px 16px;
        }

        .verification-modal-grid {
            grid-template-columns: 1fr;
            gap: 8px;
        }
    }

    @media (max-width: 400px) {
        .stats-grid {
            gap: 8px;
        }

        .stat-card {
            padding: 11px;
        }

        .stat-label {
            font-size: 10px;
        }

        .stat-value {
            font-size: 19px;
        }

        .verification-actions {
            flex-direction: column-reverse;
        }

        .verification-btn {
            width: 100%;
        }
    }
</style>
@endpush


@section('content')

    {{-- =====================================================
         SATU CARD BESAR
    ====================================================== --}}

    <div class="verification-card">

        {{-- =================================================
             HEADER VERIFIKASI
        ================================================== --}}

        <div class="verification-header">
            <h1 class="page-title">
                Sistem Verifikasi
            </h1>

            <p class="page-description">
                Kelola, periksa, dan perbarui data hasil pendataan responden.
            </p>
        </div>


        {{-- =================================================
             SUCCESS ALERT
        ================================================== --}}

        @if (session('success'))
            <div
                class="alert-success"
                id="successAlert"
            >
                <svg
                    width="18"
                    height="18"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M20 6L9 17l-5-5"></path>
                </svg>

                <span>
                    {{ session('success') }}
                </span>
            </div>
        @endif


        {{-- =================================================
             STATISTIK
        ================================================== --}}

        <div class="stats-section">
            <div class="stats-grid">

                <div class="stat-card">
                    <div class="stat-label">
                        Total Responden
                    </div>

                    <div class="stat-value">
                        100
                    </div>
                </div>


                <div class="stat-card">
                    <div class="stat-label">
                        Sudah Didata
                    </div>

                    <div class="stat-value">
                        10
                    </div>
                </div>


                <div class="stat-card">
                    <div class="stat-label">
                        Belum Didata
                    </div>

                    <div class="stat-value">
                        265
                    </div>
                </div>


                <div class="stat-card">
                    <div class="stat-label">
                        Menunggu Verifikasi
                    </div>

                    <div class="stat-value">
                        118
                    </div>
                </div>


                <div class="stat-card">
                    <div class="stat-label">
                        Disetujui
                    </div>

                    <div class="stat-value">
                        742
                    </div>
                </div>


                <div class="stat-card">
                    <div class="stat-label">
                        Ditolak
                    </div>

                    <div class="stat-value">
                        83
                    </div>
                </div>

            </div>
        </div>


        {{-- =================================================
             SEARCH
        ================================================== --}}

        <div class="search-section">

            <div class="search-title">
                Cari Data Responden
            </div>

            <div class="search-form">

                <div class="search-box">

                    <svg
                        width="17"
                        height="17"
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
                            x1="16.65"
                            y1="16.65"
                            x2="21"
                            y2="21"
                        ></line>
                    </svg>

                    <input
                        type="text"
                        id="liveSearch"
                        placeholder="Cari No.KK, NIK, atau Nama Kepala Keluarga...."
                        autocomplete="off"
                    >

                    <div
                        class="search-suggestions"
                        id="searchSuggestions"
                    ></div>

                </div>

            </div>

            <div
                class="search-result"
                id="searchResult"
                style="display: none;"
            ></div>

        </div>


        {{-- =================================================
             MONITORING
             BUKAN CARD BARU
        ================================================== --}}

        <div class="monitoring-section">

            <div class="monitoring-header">

                <div>
                    <div class="monitoring-title">
                        Data Monitoring Pendataan
                    </div>

                    <div class="monitoring-description">
                        Daftar data responden yang telah masuk ke sistem.
                    </div>
                </div>


                {{-- FILTER STATUS --}}

                <div class="filter-wrapper">

                    <form
                        action="{{ route('verifikasi.index') }}"
                        method="GET"
                    >

                        <select
                            name="status"
                            class="filter-select"
                            onchange="this.form.submit()"
                        >

                            <option
                                value="all"
                                {{ request('status', 'all') == 'all' ? 'selected' : '' }}
                            >
                                Semua Status
                            </option>

                            <option
                                value="pending"
                                {{ request('status') == 'pending' ? 'selected' : '' }}
                            >
                                Menunggu Verifikasi
                            </option>

                            <option
                                value="draft"
                                {{ request('status') == 'draft' ? 'selected' : '' }}
                            >
                                Draft
                            </option>

                            <option
                                value="not_processed"
                                {{ request('status') == 'not_processed' ? 'selected' : '' }}
                            >
                                Belum Didata
                            </option>

                            <option
                                value="approved"
                                {{ request('status') == 'approved' ? 'selected' : '' }}
                            >
                                Disetujui
                            </option>

                            <option
                                value="rejected"
                                {{ request('status') == 'rejected' ? 'selected' : '' }}
                            >
                                Ditolak
                            </option>

                        </select>

                    </form>

                </div>

            </div>


            {{-- =================================================
                 TABLE
            ================================================== --}}

            <div class="table-wrapper">

                <table class="data-table">

                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. KK</th>
                            <th>NIK</th>
                            <th>Nama Kepala Keluarga</th>
                            <th>Jumlah Anggota</th>
                            <th>Status</th>
                            <th>Wilayah Pendataan</th>
                            <th>Petugas</th>
                            <th>Tanggal Pendataan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>


                    <tbody id="dataTableBody">

                        @forelse ($data as $item)

                            @php
                                $status = strtolower(
                                    str_replace(
                                        [' ', '-'],
                                        '_',
                                        $item['status'] ?? ''
                                    )
                                );

                                if (
                                    $status === 'pending' ||
                                    $status === 'menunggu' ||
                                    $status === 'menunggu_verifikasi'
                                ) {
                                    $statusLabel = 'Menunggu Verifikasi';
                                } elseif ($status === 'draft') {
                                    $statusLabel = 'Draft';
                                } elseif (
                                    $status === 'not_processed' ||
                                    $status === 'belum_didata'
                                ) {
                                    $statusLabel = 'Belum Didata';
                                } elseif (
                                    $status === 'approved' ||
                                    $status === 'disetujui'
                                ) {
                                    $statusLabel = 'Disetujui';
                                } elseif (
                                    $status === 'rejected' ||
                                    $status === 'ditolak'
                                ) {
                                    $statusLabel = 'Ditolak';
                                } else {
                                    $statusLabel = $item['status'] ?? '-';
                                }
                            @endphp


                            <tr
                                class="data-row"
                                data-id="{{ $item['id'] ?? '' }}"
                                data-no-kk="{{ $item['no_kk'] ?? '' }}"
                                data-nik="{{ $item['nik'] ?? '' }}"
                                data-nama="{{ $item['nama'] ?? '' }}"
                                data-wilayah="{{ $item['wilayah'] ?? '' }}"
                                data-petugas="{{ $item['petugas'] ?? '' }}"
                                data-status="{{ $item['status'] ?? '' }}"
                                data-status-label="{{ $statusLabel }}"
                            >

                                <td class="row-number">
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $item['no_kk'] ?? '-' }}
                                </td>

                                <td>
                                    {{ $item['nik'] ?? '-' }}
                                </td>

                                <td>
                                    {{ $item['nama'] ?? '-' }}
                                </td>

                                <td>
                                    {{ $item['anggota'] ?? 0 }} Orang
                                </td>

                                <td>

                                    @if (
                                        $status === 'pending' ||
                                        $status === 'menunggu' ||
                                        $status === 'menunggu_verifikasi'
                                    )
                                        <span class="status-badge status-pending">
                                            Menunggu Verifikasi
                                        </span>

                                    @elseif ($status === 'draft')

                                        <span class="status-badge status-draft">
                                            Draft
                                        </span>

                                    @elseif (
                                        $status === 'not_processed' ||
                                        $status === 'belum_didata'
                                    )

                                        <span class="status-badge status-not-processed">
                                            Belum Didata
                                        </span>

                                    @elseif (
                                        $status === 'approved' ||
                                        $status === 'disetujui'
                                    )

                                        <span class="status-badge status-approved">
                                            Disetujui
                                        </span>

                                    @elseif (
                                        $status === 'rejected' ||
                                        $status === 'ditolak'
                                    )

                                        <span class="status-badge status-rejected">
                                            Ditolak
                                        </span>

                                    @else

                                        <span class="status-badge status-draft">
                                            {{ $item['status'] ?? '-' }}
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $item['wilayah'] ?? '-' }}
                                </td>

                                <td>
                                    {{ $item['petugas'] ?? '-' }}
                                </td>

                                <td>
                                    {{ $item['tanggal'] ?? '-' }}
                                </td>

                                <td>

                                    <div class="action-wrapper">

                                        <button
                                            type="button"
                                            class="btn-detail btn-open-detail"
                                        >
                                            Detail
                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr id="serverEmptyRow">
                                <td colspan="10">

                                    <div class="empty-state">

                                        <div class="empty-state-icon">

                                            <svg
                                                width="23"
                                                height="23"
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
                                                    x1="16.65"
                                                    y1="16.65"
                                                    x2="21"
                                                    y2="21"
                                                ></line>
                                            </svg>

                                        </div>

                                        <div class="empty-state-title">
                                            Data tidak ditemukan
                                        </div>

                                        <div class="empty-state-description">
                                            Belum terdapat data responden yang masuk ke sistem.
                                        </div>

                                    </div>

                                </td>
                            </tr>

                        @endforelse


                        {{-- EMPTY RESULT LIVE SEARCH --}}

                        <tr
                            id="liveEmptyRow"
                            style="display: none;"
                        >

                            <td colspan="10">

                                <div class="empty-state">

                                    <div class="empty-state-icon">

                                        <svg
                                            width="23"
                                            height="23"
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
                                                x1="16.65"
                                                y1="16.65"
                                                x2="21"
                                                y2="21"
                                            ></line>
                                        </svg>

                                    </div>

                                    <div class="empty-state-title">
                                        Data tidak ditemukan
                                    </div>

                                    <div
                                        class="empty-state-description"
                                        id="liveEmptyText"
                                    >
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


    {{-- =====================================================
         MODAL DETAIL VERIFIKASI
    ====================================================== --}}

    <div
        class="verification-modal"
        id="verificationModal"
        aria-hidden="true"
    >

        <div
            class="verification-modal-overlay"
            id="verificationModalOverlay"
        ></div>


        <div
            class="verification-modal-box"
            role="dialog"
            aria-modal="true"
            aria-labelledby="verificationModalTitle"
        >

            <div class="verification-modal-header">

                <div class="verification-modal-header-content">

                    <div class="verification-modal-kicker">
                        VERIFIKASI DATA
                    </div>

                    <h2
                        class="verification-modal-title"
                        id="verificationModalTitle"
                    >
                        Detail Verifikasi
                    </h2>

                    <div class="verification-modal-subtitle">
                        Periksa data responden dan ubah status verifikasi.
                    </div>

                </div>


                <button
                    type="button"
                    class="verification-modal-close"
                    id="closeVerificationModal"
                    aria-label="Tutup detail"
                >

                    <svg
                        width="17"
                        height="17"
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


            <div class="verification-modal-body">

                <div class="verification-modal-grid">

                    <div class="verification-field">
                        <label>No. KK</label>
                        <strong id="modalNoKK">-</strong>
                    </div>

                    <div class="verification-field">
                        <label>NIK</label>
                        <strong id="modalNIK">-</strong>
                    </div>

                    <div class="verification-field">
                        <label>Nama Kepala Keluarga</label>
                        <strong id="modalNama">-</strong>
                    </div>

                    <div class="verification-field">
                        <label>Wilayah</label>
                        <strong id="modalWilayah">-</strong>
                    </div>

                    <div class="verification-field">
                        <label>Petugas</label>
                        <strong id="modalPetugas">-</strong>
                    </div>

                    <div class="verification-field">
                        <label>Status Saat Ini</label>
                        <strong id="modalStatusLabel">-</strong>
                    </div>

                </div>


                <form
                    id="verificationUpdateForm"
                    method="POST"
                >

                    @csrf
                    @method('PUT')


                    <div class="verification-form-group">

                        <label for="modalStatus">
                            Ubah Status
                        </label>

                        <select
                            id="modalStatus"
                            name="status"
                            required
                        >

                            <option
                                value=""
                                disabled
                            >
                                Pilih Status Verifikasi
                            </option>

                            <option value="approved">
                                Disetujui
                            </option>

                            <option value="rejected">
                                Ditolak
                            </option>

                        </select>

                    </div>


                    <div class="verification-actions">

                        <button
                            type="button"
                            class="verification-btn verification-btn-secondary"
                            id="cancelVerificationModal"
                        >
                            Tutup
                        </button>

                        <button
                            type="submit"
                            class="verification-btn verification-btn-primary"
                        >
                            Simpan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =====================================================
       SUCCESS ALERT
    ====================================================== */

    const successAlert =
        document.getElementById('successAlert');

    if (successAlert) {
        setTimeout(function () {

            successAlert.style.opacity = '0';
            successAlert.style.transform =
                'translateY(-5px)';
            successAlert.style.transition =
                'all .3s ease';

            setTimeout(function () {
                successAlert.remove();
            }, 300);

        }, 3000);
    }


    /* =====================================================
       LIVE SEARCH
    ====================================================== */

    const searchInput =
        document.getElementById('liveSearch');

    const searchSuggestions =
        document.getElementById('searchSuggestions');

    const searchResult =
        document.getElementById('searchResult');

    const tableRows =
        Array.from(
            document.querySelectorAll('.data-row')
        );

    const liveEmptyRow =
        document.getElementById('liveEmptyRow');

    const liveEmptyText =
        document.getElementById('liveEmptyText');


    /* =====================================================
       DATA SEARCH
    ====================================================== */

    const searchData =
        tableRows.map(function (row) {

            return {
                row: row,
                noKK: row.dataset.noKk || '',
                nik: row.dataset.nik || '',
                nama: row.dataset.nama || ''
            };

        });


    /* =====================================================
       ESCAPE HTML
    ====================================================== */

    function escapeHtml(value) {

        const div =
            document.createElement('div');

        div.textContent =
            value ?? '';

        return div.innerHTML;
    }


    /* =====================================================
       UPDATE SEARCH RESULT
    ====================================================== */

    function updateSearchResult(keyword, count) {

        if (!searchResult) {
            return;
        }

        if (keyword.length > 0) {

            searchResult.style.display =
                'block';

            searchResult.innerHTML =
                'Menampilkan ' +
                '<strong>' +
                count +
                '</strong>' +
                ' data yang cocok dengan ' +
                '<strong>"' +
                escapeHtml(keyword) +
                '"</strong>';

        } else {

            searchResult.style.display =
                'none';

            searchResult.innerHTML =
                '';

        }
    }


    /* =====================================================
       FILTER TABLE
    ====================================================== */

    function filterTable(keyword) {

        keyword =
            keyword
                .toLowerCase()
                .trim();


        if (keyword === '') {

            tableRows.forEach(
                function (row, index) {

                    row.style.display =
                        '';

                    const numberCell =
                        row.querySelector(
                            '.row-number'
                        );

                    if (numberCell) {
                        numberCell.textContent =
                            index + 1;
                    }

                }
            );

            if (liveEmptyRow) {
                liveEmptyRow.style.display =
                    'none';
            }

            updateSearchResult('', 0);

            return [];
        }


        const filteredData =
            searchData.filter(
                function (item) {

                    const noKK =
                        item.noKK.toLowerCase();

                    const nik =
                        item.nik.toLowerCase();

                    const nama =
                        item.nama.toLowerCase();

                    return (
                        noKK.includes(keyword) ||
                        nik.includes(keyword) ||
                        nama.includes(keyword)
                    );

                }
            );


        tableRows.forEach(
            function (row) {
                row.style.display =
                    'none';
            }
        );


        filteredData.forEach(
            function (item, index) {

                item.row.style.display =
                    '';

                const numberCell =
                    item.row.querySelector(
                        '.row-number'
                    );

                if (numberCell) {
                    numberCell.textContent =
                        index + 1;
                }

            }
        );


        updateSearchResult(
            keyword,
            filteredData.length
        );


        if (liveEmptyRow) {

            if (filteredData.length === 0) {

                liveEmptyRow.style.display =
                    '';

                if (liveEmptyText) {

                    liveEmptyText.textContent =
                        'Tidak ada data yang sesuai dengan pencarian "' +
                        keyword +
                        '".';

                }

            } else {

                liveEmptyRow.style.display =
                    'none';

            }
        }


        return filteredData;
    }


    /* =====================================================
       SEARCH SUGGESTIONS
    ====================================================== */

    function showSuggestions(keyword) {

        if (!searchSuggestions) {
            return;
        }

        keyword =
            keyword
                .toLowerCase()
                .trim();


        if (keyword.length < 2) {

            searchSuggestions.classList.remove(
                'show'
            );

            searchSuggestions.innerHTML =
                '';

            return;
        }


        const matches =
            searchData
                .filter(function (item) {

                    return (
                        item.nama
                            .toLowerCase()
                            .includes(keyword) ||

                        item.noKK
                            .toLowerCase()
                            .includes(keyword) ||

                        item.nik
                            .toLowerCase()
                            .includes(keyword)
                    );

                })
                .slice(0, 5);


        searchSuggestions.innerHTML =
            '';


        if (matches.length === 0) {

            const empty =
                document.createElement('div');

            empty.className =
                'suggestion-empty';

            empty.textContent =
                'Tidak ada data yang cocok.';

            searchSuggestions.appendChild(
                empty
            );

            searchSuggestions.classList.add(
                'show'
            );

            return;
        }


        matches.forEach(
            function (item) {

                const button =
                    document.createElement(
                        'button'
                    );

                button.type =
                    'button';

                button.className =
                    'suggestion-item';


                const icon =
                    document.createElement(
                        'div'
                    );

                icon.className =
                    'suggestion-icon';

                icon.innerHTML = `
                    <svg
                        width="14"
                        height="14"
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
                            x1="16.65"
                            y1="16.65"
                            x2="21"
                            y2="21"
                        ></line>
                    </svg>
                `;


                const content =
                    document.createElement(
                        'div'
                    );

                content.className =
                    'suggestion-content';


                const name =
                    document.createElement(
                        'div'
                    );

                name.className =
                    'suggestion-name';

                name.textContent =
                    item.nama || '-';


                const detail =
                    document.createElement(
                        'div'
                    );

                detail.className =
                    'suggestion-detail';

                detail.textContent =
                    'No. KK: ' +
                    (item.noKK || '-') +
                    ' • NIK: ' +
                    (item.nik || '-');


                content.appendChild(name);
                content.appendChild(detail);

                button.appendChild(icon);
                button.appendChild(content);


                button.addEventListener(
                    'click',
                    function () {

                        searchInput.value =
                            item.nama ||
                            item.noKK ||
                            item.nik;

                        filterTable(
                            searchInput.value
                        );

                        searchSuggestions.classList.remove(
                            'show'
                        );

                    }
                );


                searchSuggestions.appendChild(
                    button
                );

            }
        );


        searchSuggestions.classList.add(
            'show'
        );
    }


    /* =====================================================
       INPUT SEARCH
    ====================================================== */

    if (searchInput) {

        searchInput.addEventListener(
            'input',
            function () {

                const keyword =
                    this.value.trim();

                filterTable(keyword);
                showSuggestions(keyword);

            }
        );


        searchInput.addEventListener(
            'focus',
            function () {

                const keyword =
                    this.value.trim();

                if (keyword.length >= 2) {
                    showSuggestions(keyword);
                }

            }
        );


        searchInput.addEventListener(
            'keydown',
            function (event) {

                if (event.key === 'Escape') {

                    searchSuggestions.classList.remove(
                        'show'
                    );

                }

            }
        );
    }


    /* =====================================================
       CLICK DI LUAR SEARCH
    ====================================================== */

    document.addEventListener(
        'click',
        function (event) {

            if (
                searchSuggestions &&
                searchInput &&
                !searchSuggestions.contains(
                    event.target
                ) &&
                !searchInput.contains(
                    event.target
                )
            ) {

                searchSuggestions.classList.remove(
                    'show'
                );

            }

        }
    );


    /* =====================================================
       MODAL DETAIL
    ====================================================== */

    const verificationModal =
        document.getElementById(
            'verificationModal'
        );

    const verificationModalOverlay =
        document.getElementById(
            'verificationModalOverlay'
        );

    const closeVerificationModal =
        document.getElementById(
            'closeVerificationModal'
        );

    const cancelVerificationModal =
        document.getElementById(
            'cancelVerificationModal'
        );

    const verificationUpdateForm =
        document.getElementById(
            'verificationUpdateForm'
        );

    const modalNoKK =
        document.getElementById(
            'modalNoKK'
        );

    const modalNIK =
        document.getElementById(
            'modalNIK'
        );

    const modalNama =
        document.getElementById(
            'modalNama'
        );

    const modalWilayah =
        document.getElementById(
            'modalWilayah'
        );

    const modalPetugas =
        document.getElementById(
            'modalPetugas'
        );

    const modalStatusLabel =
        document.getElementById(
            'modalStatusLabel'
        );

    const modalStatus =
        document.getElementById(
            'modalStatus'
        );


    /* =====================================================
       ROUTE UPDATE
    ====================================================== */

    const updateRouteTemplate =
        "{{ route('verifikasi.update', '__ID__') }}";


    /* =====================================================
       OPEN MODAL
    ====================================================== */

    function openVerificationModal(row) {

        if (!verificationModal || !row) {
            return;
        }


        const id =
            row.dataset.id || '';

        const noKK =
            row.dataset.noKk || '-';

        const nik =
            row.dataset.nik || '-';

        const nama =
            row.dataset.nama || '-';

        const wilayah =
            row.dataset.wilayah || '-';

        const petugas =
            row.dataset.petugas || '-';

        const status =
            row.dataset.status || '';

        const statusLabel =
            row.dataset.statusLabel || '-';


        modalNoKK.textContent =
            noKK;

        modalNIK.textContent =
            nik;

        modalNama.textContent =
            nama;

        modalWilayah.textContent =
            wilayah;

        modalPetugas.textContent =
            petugas;

        modalStatusLabel.textContent =
            statusLabel;


        let normalizedStatus =
            status
                .toLowerCase()
                .replace(/[\s-]+/g, '_');


        if (
            normalizedStatus ===
            'disetujui'
        ) {
            normalizedStatus =
                'approved';
        }


        if (
            normalizedStatus ===
            'ditolak'
        ) {
            normalizedStatus =
                'rejected';
        }


        if (
            normalizedStatus !== 'approved' &&
            normalizedStatus !== 'rejected'
        ) {

            modalStatus.value =
                '';

        } else {

            modalStatus.value =
                normalizedStatus;

        }


        if (
            verificationUpdateForm &&
            id
        ) {

            verificationUpdateForm.action =
                updateRouteTemplate.replace(
                    '__ID__',
                    encodeURIComponent(id)
                );

        }


        verificationModal.classList.add(
            'show'
        );

        verificationModal.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.style.overflow =
            'hidden';
    }


    /* =====================================================
       CLOSE MODAL
    ====================================================== */

    function closeModal() {

        if (!verificationModal) {
            return;
        }

        verificationModal.classList.remove(
            'show'
        );

        verificationModal.setAttribute(
            'aria-hidden',
            'true'
        );

        document.body.style.overflow =
            '';
    }


    /* =====================================================
       BUTTON DETAIL
    ====================================================== */

    document
        .querySelectorAll('.btn-open-detail')
        .forEach(
            function (button) {

                button.addEventListener(
                    'click',
                    function () {

                        const row =
                            this.closest(
                                '.data-row'
                            );

                        openVerificationModal(
                            row
                        );

                    }
                );

            }
        );


    /* =====================================================
       CLOSE BUTTON
    ====================================================== */

    if (closeVerificationModal) {

        closeVerificationModal.addEventListener(
            'click',
            closeModal
        );

    }


    if (cancelVerificationModal) {

        cancelVerificationModal.addEventListener(
            'click',
            closeModal
        );

    }


    /* =====================================================
       CLICK BACKDROP
    ====================================================== */

    if (verificationModalOverlay) {

        verificationModalOverlay.addEventListener(
            'click',
            closeModal
        );

    }


    /* =====================================================
       ESCAPE
    ====================================================== */

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape' &&
                verificationModal &&
                verificationModal.classList.contains(
                    'show'
                )
            ) {

                closeModal();

            }

        }
    );

});
</script>
@endpush