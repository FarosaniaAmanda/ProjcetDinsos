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
       HEADER
    ===================================================== */

    .monitoring-header {
        margin-bottom: 26px;
        padding-top: 4px;
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
       STAT CARD
    ===================================================== */

    .monitoring-stats {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .monitoring-stat-card {
        background: #ffffff;
        border: 1px solid #e2e6f2;
        border-radius: 15px;
        padding: 20px 21px;
        display: flex;
        align-items: center;
        gap: 15px;
        min-height: 92px;
        box-shadow: 0 3px 12px rgba(37, 42, 134, 0.05);
        transition: .2s ease;
        box-sizing: border-box;
    }

    .monitoring-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(37, 42, 134, 0.08);
    }

    .monitoring-stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
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
        font-size: 13px;
        margin-bottom: 5px;
        line-height: 1.4;
    }

    .monitoring-stat-value {
        color: #252A86;
        font-size: 23px;
        line-height: 1.2;
        font-weight: 700;
    }


    /* =====================================================
       CONTENT CARD
    ===================================================== */

    .monitoring-card {
        background: #ffffff;
        border: 1px solid #e2e6f2;
        border-radius: 16px;
        box-shadow: 0 3px 14px rgba(37, 42, 134, 0.05);
        overflow: hidden;
    }

    .monitoring-card-header {
        padding: 22px 24px 21px;
        border-bottom: 1px solid #e8ebf3;
    }

    .monitoring-card-title {
        font-size: 17px;
        line-height: 1.4;
        font-weight: 700;
        color: #252A86;
        margin: 0 0 6px;
    }

    .monitoring-card-description {
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
        margin-top: 19px;
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
       RESPONSIVE
    ===================================================== */

    /* =========================
       DESKTOP
       5 CARD SATU BARIS
       ========================= */

    .monitoring-stats {
        grid-template-columns: repeat(5, minmax(0, 1fr));
    }


    /* =========================
       TABLET
       3 CARD SATU BARIS
       ========================= */

    @media (max-width: 1200px) {

        .monitoring-page {
            padding: 22px 24px 35px;
        }

        .monitoring-stats {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }


    /* =========================
       MOBILE
       2 CARD SATU BARIS
       ========================= */

    @media (max-width: 700px) {

        .monitoring-page {
            padding: 18px 14px 30px;
        }

        .monitoring-header {
            margin-bottom: 20px;
        }

        .monitoring-header h1 {
            font-size: 21px;
        }

        .monitoring-header p {
            font-size: 13px;
        }

        /*
         * PENTING:
         * Mobile dibuat 2 kolom.
         * Jangan gunakan 1fr di sini.
         */
        .monitoring-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .monitoring-stat-card {
            min-height: 78px;
            padding: 14px;
        }

        .monitoring-stat-label {
            font-size: 11px;
        }

        .monitoring-stat-value {
            font-size: 19px;
        }

        .monitoring-card-header {
            padding: 17px;
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


    /* =========================
       HP SANGAT KECIL
       TETAP 2 CARD
       ========================= */

    @media (max-width: 400px) {

        .monitoring-page {
            padding: 15px 10px 25px;
        }

        .monitoring-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
        }

        .monitoring-stat-card {
            min-height: 72px;
            padding: 12px;
            border-radius: 12px;
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
         HEADER
    ===================================================== --}}

    <div class="monitoring-header">

        <h1>
            Monitoring Pendataan
        </h1>

        <p>
            Memantau data responden yang telah dilakukan pendataan.
        </p>

    </div>


    {{-- =====================================================
         STATISTICS
    ===================================================== --}}

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


    {{-- =====================================================
         MAIN CARD
    ===================================================== --}}

    <div class="monitoring-card">

        <div class="monitoring-card-header">

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


                <div
                    class="monitoring-search-suggestions"
                    id="monitoringSearchSuggestions"
                ></div>

            </div>

        </div>


        {{-- =====================================================
             TABLE
        ===================================================== --}}

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


{{-- =====================================================
     MODAL DETAIL MONITORING
===================================================== --}}

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


                <div class="monitoring-questionnaire-empty">

                    <svg
                        width="30"
                        height="30"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                    >

                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>

                        <polyline points="14 2 14 8 20 8"></polyline>

                        <line
                            x1="8"
                            y1="13"
                            x2="16"
                            y2="13"
                        ></line>

                        <line
                            x1="8"
                            y1="17"
                            x2="16"
                            y2="17"
                        ></line>

                    </svg>


                    <strong>
                        Belum Ada Hasil Kuisioner
                    </strong>


                    <span>
                        Hasil kuisioner untuk responden ini belum tersedia.
                    </span>

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
        document.getElementById(
            'detailNamaSummary'
        );


    const detailKkSummary =
        document.getElementById(
            'detailKkSummary'
        );


    const detailStatusSummary =
        document.getElementById(
            'detailStatusSummary'
        );


    const detailNoKk =
        document.getElementById(
            'detailNoKk'
        );


    const detailNik =
        document.getElementById(
            'detailNik'
        );


    const detailNama =
        document.getElementById(
            'detailNama'
        );


    const detailJumlahAnggota =
        document.getElementById(
            'detailJumlahAnggota'
        );


    const detailWilayah =
        document.getElementById(
            'detailWilayah'
        );


    const detailPetugas =
        document.getElementById(
            'detailPetugas'
        );


    const detailTanggal =
        document.getElementById(
            'detailTanggal'
        );


    const detailStatus =
        document.getElementById(
            'detailStatus'
        );


    function openDetailModal(button) {

        detailNamaSummary.textContent =
            button.dataset.nama || '-';


        detailKkSummary.textContent =
            button.dataset.noKk || '-';


        detailStatusSummary.textContent =
            button.dataset.status || '-';


        detailNoKk.textContent =
            button.dataset.noKk || '-';


        detailNik.textContent =
            button.dataset.nik || '-';


        detailNama.textContent =
            button.dataset.nama || '-';


        detailJumlahAnggota.textContent =
            button.dataset.jumlahAnggota || '0';


        detailWilayah.textContent =
            button.dataset.wilayah || '-';


        detailPetugas.textContent =
            button.dataset.petugas || '-';


        detailTanggal.textContent =
            button.dataset.tanggal || '-';


        detailStatus.textContent =
            button.dataset.status || '-';


        modal.classList.add(
            'active'
        );


        modal.setAttribute(
            'aria-hidden',
            'false'
        );


        document.body.style.overflow =
            'hidden';

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