@extends('admin.layouts.app')

@section('content')

<style>
    :root {
        --primary: #252A86;
        --primary-dark: #1b1f68;
        --primary-soft: #eef0ff;

        --secondary: #55B5D5;
        --success: #299447;
        --success-soft: #eaf7ee;

        --warning: #F5C928;
        --warning-soft: #fff8d9;

        --danger: #D9364F;
        --danger-soft: #fff0f2;

        --text: #222222;
        --text-soft: #6b7280;
        --border: #e5e7eb;
        --bg: #f6f8fc;
        --white: #ffffff;
    }

    * {
        box-sizing: border-box;
    }

    /* =====================================================
       PAGE WRAPPER
    ====================================================== */

    .monitoring-page {
        width: 100%;
        color: var(--text);
    }

    /* =====================================================
       PAGE HEADER
    ====================================================== */

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 24px;
        margin-bottom: 28px;
    }

    .page-heading {
        min-width: 0;
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN LABEL
    |--------------------------------------------------------------------------
    */

    .page-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;

        margin: 0 0 8px;

        color: var(--primary);
        font-size: 12px;
        font-weight: 800;

        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .page-kicker::before {
        content: "";

        width: 7px;
        height: 7px;

        border-radius: 50%;
        background: var(--secondary);
    }

    /*
    |--------------------------------------------------------------------------
    | MAIN TITLE
    |--------------------------------------------------------------------------
    */

    .page-title {
        margin: 0;

        color: var(--primary);

        font-size: 30px;
        line-height: 1.2;
        font-weight: 800;

        letter-spacing: -.02em;
    }

    /*
    |--------------------------------------------------------------------------
    | SUBTITLE
    |--------------------------------------------------------------------------
    */

    .page-description {
        margin: 8px 0 0;

        color: var(--text-soft);

        font-size: 14px;
        line-height: 1.6;
    }

    /* =====================================================
       SEARCH
    ====================================================== */

    .search-area {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }

    .search-box {
        display: flex;
        align-items: center;
        gap: 10px;

        width: 290px;
        height: 44px;

        padding: 0 14px;

        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 10px;

        box-shadow: 0 3px 12px rgba(15, 23, 42, .04);

        transition: .2s ease;
    }

    .search-box:focus-within {
        border-color: var(--primary);

        box-shadow:
            0 0 0 3px rgba(37, 42, 134, .08);
    }

    .search-icon {
        color: var(--text-soft);

        font-size: 16px;

        flex-shrink: 0;
    }

    .search-box input {
        width: 100%;

        border: 0;
        outline: 0;

        background: transparent;

        color: var(--text);

        font-size: 13px;
    }

    .search-box input::placeholder {
        color: #9ca3af;
    }

    .btn-search {
        height: 44px;

        padding: 0 18px;

        border: 0;
        border-radius: 10px;

        background: var(--primary);
        color: var(--white);

        font-size: 13px;
        font-weight: 700;

        cursor: pointer;

        transition: .2s ease;

        box-shadow:
            0 4px 10px rgba(37, 42, 134, .16);
    }

    .btn-search:hover {
        background: var(--primary-dark);

        transform: translateY(-1px);
    }

    /* =====================================================
       STATISTICS
    ====================================================== */

    .stats-grid {
        display: grid;

        grid-template-columns:
            repeat(6, minmax(0, 1fr));

        gap: 14px;

        margin-bottom: 24px;
    }

    .stat-card {
        position: relative;

        min-height: 118px;

        padding: 18px;

        background: var(--white);

        border: 1px solid var(--border);
        border-radius: 14px;

        box-shadow:
            0 4px 16px rgba(15, 23, 42, .035);

        overflow: hidden;

        transition: .2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);

        box-shadow:
            0 8px 22px rgba(15, 23, 42, .07);
    }

    .stat-card::after {
        content: "";

        position: absolute;

        right: -20px;
        bottom: -30px;

        width: 80px;
        height: 80px;

        border-radius: 50%;

        background: rgba(37, 42, 134, .04);
    }

    .stat-label {
        position: relative;
        z-index: 1;

        margin-bottom: 13px;

        color: var(--text-soft);

        font-size: 12px;
        line-height: 1.4;
        font-weight: 600;
    }

    .stat-value {
        position: relative;
        z-index: 1;

        color: var(--primary);

        font-size: 28px;
        line-height: 1;

        font-weight: 800;

        letter-spacing: -.02em;
    }

    .stat-card.success .stat-value {
        color: var(--success);
    }

    .stat-card.warning .stat-value {
        color: #b28a00;
    }

    .stat-card.danger .stat-value {
        color: var(--danger);
    }

    .stat-card.info .stat-value {
        color: #287e9d;
    }

    /* =====================================================
       DATA PANEL
    ====================================================== */

    .data-panel {
        background: var(--white);

        border: 1px solid var(--border);
        border-radius: 16px;

        box-shadow:
            0 5px 20px rgba(15, 23, 42, .04);

        overflow: hidden;
    }

    .panel-header {
        display: flex;

        justify-content: space-between;
        align-items: center;

        gap: 20px;

        padding: 22px 24px;

        border-bottom: 1px solid var(--border);
    }

    .panel-heading h2 {
        margin: 0;

        color: var(--primary);

        font-size: 18px;
        line-height: 1.3;

        font-weight: 800;
    }

    .panel-heading p {
        margin: 5px 0 0;

        color: var(--text-soft);

        font-size: 12px;
        line-height: 1.5;
    }

    /* =====================================================
       FILTER
    ====================================================== */

    .filter-area {
        display: flex;

        align-items: center;

        gap: 10px;
    }

    .filter-label {
        color: var(--text-soft);

        font-size: 12px;
        font-weight: 700;
    }

    .filter-select {
        min-width: 150px;
        height: 40px;

        padding: 0 34px 0 12px;

        border: 1px solid var(--border);
        border-radius: 9px;

        background: var(--white);
        color: var(--text);

        font-size: 12px;
        font-weight: 600;

        outline: none;

        cursor: pointer;
    }

    .filter-select:focus {
        border-color: var(--primary);

        box-shadow:
            0 0 0 3px rgba(37, 42, 134, .07);
    }

    /* =====================================================
       TABLE
    ====================================================== */

    .table-container {
        width: 100%;

        overflow-x: auto;
    }

    .data-table {
        width: 100%;

        min-width: 1180px;

        border-collapse: collapse;
    }

    .data-table thead th {
        padding: 13px 14px;

        background: #f7f8ff;

        border-bottom: 1px solid var(--border);

        color: #4b5563;

        font-size: 11px;
        line-height: 1.3;

        font-weight: 800;

        text-align: left;

        text-transform: uppercase;

        letter-spacing: .045em;

        white-space: nowrap;
    }

    .data-table tbody td {
        padding: 15px 14px;

        border-bottom: 1px solid #edf0f4;

        color: #374151;

        font-size: 13px;
        line-height: 1.4;

        vertical-align: middle;
    }

    .data-table tbody tr {
        transition: background .15s ease;
    }

    .data-table tbody tr:hover {
        background: #fafbff;
    }

    .data-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .number-cell {
        width: 45px;

        color: #9ca3af !important;

        font-weight: 700;

        text-align: center;
    }

    .family-head {
        color: #1f2937 !important;

        font-weight: 700;

        white-space: nowrap;
    }

    .number-data {
        font-variant-numeric: tabular-nums;

        white-space: nowrap;
    }

    .center {
        text-align: center;
    }

    .date-cell {
        color: #6b7280 !important;

        white-space: nowrap;
    }

    /* =====================================================
       STATUS BADGES
    ====================================================== */

    .status-badge {
        display: inline-flex;

        align-items: center;

        gap: 6px;

        padding: 6px 10px;

        border-radius: 999px;

        font-size: 11px;
        line-height: 1;

        font-weight: 700;

        white-space: nowrap;
    }

    .status-badge::before {
        content: "";

        width: 6px;
        height: 6px;

        border-radius: 50%;

        background: currentColor;
    }

    .status-open {
        background: #edf8fc;

        color: #287e9d;
    }

    .status-draft {
        background: var(--warning-soft);

        color: #9b7600;
    }

    .status-submit {
        background: var(--primary-soft);

        color: var(--primary);
    }

    .status-reject {
        background: var(--danger-soft);

        color: var(--danger);
    }

    .status-approved {
        background: var(--success-soft);

        color: var(--success);
    }

    /* =====================================================
       ACTION
    ====================================================== */

    .action-cell {
        display: flex;

        align-items: center;

        gap: 8px;

        white-space: nowrap;
    }

    .btn-action {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        gap: 6px;

        min-height: 34px;

        padding: 0 12px;

        border-radius: 8px;

        font-size: 11px;

        font-weight: 700;

        line-height: 1;

        text-decoration: none;

        cursor: pointer;

        transition: all .18s ease;
    }

    .btn-action svg {
        width: 14px;
        height: 14px;

        flex-shrink: 0;
    }

    /* DETAIL */

    .btn-detail {
        border: 1px solid #dbe3ea;

        background: #f8fafc;

        color: #475569;
    }

    .btn-detail:hover {
        background: #eef2f6;

        border-color: #cbd5e1;

        color: #1e293b;

        transform: translateY(-1px);
    }

    /* EDIT */

    .btn-edit {
        border: 1px solid #d9dded;

        background: var(--primary-soft);

        color: var(--primary);
    }

    .btn-edit:hover {
        background: var(--primary);

        border-color: var(--primary);

        color: var(--white);

        transform: translateY(-1px);
    }

    /* =====================================================
       RESPONSIVE
    ====================================================== */

    @media (max-width: 1400px) {

        .stats-grid {
            grid-template-columns:
                repeat(3, minmax(0, 1fr));
        }

    }

    @media (max-width: 900px) {

        .page-header {
            flex-direction: column;

            align-items: stretch;
        }

        .search-area {
            width: 100%;
        }

        .search-box {
            flex: 1;

            width: auto;
        }

        .panel-header {
            align-items: flex-start;

            flex-direction: column;
        }

        .filter-area {
            width: 100%;

            justify-content: space-between;
        }

        .filter-select {
            flex: 1;
        }

    }

    @media (max-width: 650px) {

        .stats-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));

            gap: 10px;
        }

        .stat-card {
            min-height: 105px;

            padding: 15px;
        }

        .stat-value {
            font-size: 24px;
        }

        .page-title {
            font-size: 25px;
        }

        .search-area {
            flex-direction: column;

            align-items: stretch;
        }

        .search-box,
        .btn-search {
            width: 100%;
        }

        .panel-header {
            padding: 18px;
        }

        .filter-area {
            align-items: flex-start;

            flex-direction: column;
        }

        .filter-select {
            width: 100%;
        }

    }

    @media (max-width: 420px) {

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .page-title {
            font-size: 23px;
        }

        .page-description {
            font-size: 13px;
        }

    }

</style>


<div class="monitoring-page">

    <!-- =====================================================
         PAGE HEADER
    ====================================================== -->

    <div class="page-header">

        <div class="page-heading">

            <!-- ADMIN -->

            <p class="page-kicker">
                ADMIN
            </p>

            <!-- JUDUL -->

            <h1 class="page-title">
                Sistem Verifikasi
            </h1>

            <!-- SUBJUDUL -->

            <p class="page-description">
                Kelola, periksa, dan perbarui data hasil pendataan responden.
            </p>

        </div>


        <!-- =================================================
             SEARCH
        ================================================== -->

        <div class="search-area">

            <div class="search-box">

                <span
                    class="search-icon"
                    aria-hidden="true"
                >
                    🔎
                </span>

                <input
                    type="text"
                    placeholder="Cari nama, NIK, atau No. KK..."
                    aria-label="Cari data responden"
                >

            </div>


            <button
                type="button"
                class="btn-search"
            >
                Cari Data
            </button>

        </div>

    </div>


    <!-- =====================================================
         STATISTICS
    ====================================================== -->

    <div class="stats-grid">

        <!-- TOTAL RESPONDEN -->

        <div class="stat-card">

            <div class="stat-label">
                Total Responden
            </div>

            <div class="stat-value">
                1.245
            </div>

        </div>


        <!-- SUDAH DIDATA -->

        <div class="stat-card info">

            <div class="stat-label">
                Sudah Didata
            </div>

            <div class="stat-value">
                980
            </div>

        </div>


        <!-- BELUM DIDATA -->

        <div class="stat-card warning">

            <div class="stat-label">
                Belum Didata
            </div>

            <div class="stat-value">
                265
            </div>

        </div>


        <!-- MENUNGGU VERIFIKASI -->

        <div class="stat-card warning">

            <div class="stat-label">
                Menunggu Verifikasi
            </div>

            <div class="stat-value">
                118
            </div>

        </div>


        <!-- DISETUJUI -->

        <div class="stat-card success">

            <div class="stat-label">
                Disetujui
            </div>

            <div class="stat-value">
                742
            </div>

        </div>


        <!-- DITOLAK -->

        <div class="stat-card danger">

            <div class="stat-label">
                Ditolak
            </div>

            <div class="stat-value">
                83
            </div>

        </div>

    </div>


    <!-- =====================================================
         DATA PANEL
    ====================================================== -->

    <div class="data-panel">


        <!-- =================================================
             PANEL HEADER
        ================================================== -->

        <div class="panel-header">

            <div class="panel-heading">

                <h2>
                    Data Hasil Pendataan
                </h2>

                <p>
                    Daftar responden yang telah dikumpulkan dan diproses oleh petugas.
                </p>

            </div>


            <!-- =================================================
                 FILTER
            ================================================== -->

            <div class="filter-area">

                <span class="filter-label">
                    Filter Status
                </span>

                <select
                    class="filter-select"
                    aria-label="Filter berdasarkan status"
                >

                    <option value="">
                        Semua Status
                    </option>

                    <option value="open">
                        Belum Diproses
                    </option>

                    <option value="draft">
                        Draft
                    </option>

                    <option value="submit">
                        Menunggu Verifikasi
                    </option>

                    <option value="reject">
                        Ditolak
                    </option>

                    <option value="approved">
                        Disetujui
                    </option>

                </select>

            </div>

        </div>


        <!-- =================================================
             TABLE
        ================================================== -->

        <div class="table-container">

            <table class="data-table">

                <thead>

                    <tr>

                        <th class="center">
                            No.
                        </th>

                        <th>
                            No. KK
                        </th>

                        <th>
                            NIK
                        </th>

                        <th>
                            Nama Kepala Keluarga
                        </th>

                        <th class="center">
                            Jumlah Anggota
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Wilayah Pendataan
                        </th>

                        <th>
                            Petugas
                        </th>

                        <th>
                            Tanggal Pendataan
                        </th>

                        <th>
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>


                    <!-- =================================================
                         DATA 1
                    ================================================== -->

                    <tr>

                        <td class="number-cell">
                            1
                        </td>

                        <td class="number-data">
                            3201012345678901
                        </td>

                        <td class="number-data">
                            3201011708990001
                        </td>

                        <td class="family-head">
                            Andi Pratama
                        </td>

                        <td class="center">
                            4
                        </td>

                        <td>

                            <span class="status-badge status-submit">
                                Menunggu Verifikasi
                            </span>

                        </td>

                        <td>
                            Bandung
                        </td>

                        <td>
                            Rina
                        </td>

                        <td class="date-cell">
                            10 September 2026
                        </td>


                        <!-- AKSI -->

                        <td>

                            <div class="action-cell">

                                <!-- DETAIL -->

                                <a
                                    href="/monitoring/1"
                                    class="btn-action btn-detail"
                                    title="Lihat detail data"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75 6.75-9.75 6.75S2.25 12 2.25 12Z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                        />

                                    </svg>

                                    Detail

                                </a>


                                <!-- EDIT -->

                                <a
                                    href="/monitoring/1/edit"
                                    class="btn-action btn-edit"
                                    title="Edit data"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="m16.862 3.487 3.651 3.651M4.5 19.5l1.04-4.16a2.25 2.25 0 0 1 .58-1.01l9.742-9.742a2.25 2.25 0 0 1 3.182 3.182L9.302 17.512a2.25 2.25 0 0 1-1.01.58L4.5 19.5Z"
                                        />

                                    </svg>

                                    Edit

                                </a>

                            </div>

                        </td>

                    </tr>


                    <!-- =================================================
                         DATA 2
                    ================================================== -->

                    <tr>

                        <td class="number-cell">
                            2
                        </td>

                        <td class="number-data">
                            3201012345678902
                        </td>

                        <td class="number-data">
                            3201011005980002
                        </td>

                        <td class="family-head">
                            Siti Rahma
                        </td>

                        <td class="center">
                            5
                        </td>

                        <td>

                            <span class="status-badge status-draft">
                                Draft
                            </span>

                        </td>

                        <td>
                            Jakarta Barat
                        </td>

                        <td>
                            Dedi
                        </td>

                        <td class="date-cell">
                            11 September 2026
                        </td>


                        <!-- AKSI -->

                        <td>

                            <div class="action-cell">

                                <!-- DETAIL -->

                                <a
                                    href="/monitoring/2"
                                    class="btn-action btn-detail"
                                    title="Lihat detail data"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75 6.75-9.75 6.75S2.25 12 2.25 12Z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                        />

                                    </svg>

                                    Detail

                                </a>


                                <!-- EDIT -->

                                <a
                                    href="/monitoring/2/edit"
                                    class="btn-action btn-edit"
                                    title="Edit data"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="m16.862 3.487 3.651 3.651M4.5 19.5l1.04-4.16a2.25 2.25 0 0 1 .58-.01l9.742-9.742a2.25 2.25 0 0 1 3.182 3.182L9.302 17.512a2.25 2.25 0 0 1-1.01.58L4.5 19.5Z"
                                        />

                                    </svg>

                                    Edit

                                </a>

                            </div>

                        </td>

                    </tr>


                    <!-- =================================================
                         DATA 3
                    ================================================== -->

                    <tr>

                        <td class="number-cell">
                            3
                        </td>

                        <td class="number-data">
                            3201012345678903
                        </td>

                        <td class="number-data">
                            3201010507970003
                        </td>

                        <td class="family-head">
                            Rizki Wardana
                        </td>

                        <td class="center">
                            3
                        </td>

                        <td>

                            <span class="status-badge status-open">
                                Belum Diproses
                            </span>

                        </td>

                        <td>
                            Semarang
                        </td>

                        <td>
                            Nanda
                        </td>

                        <td class="date-cell">
                            12 September 2026
                        </td>


                        <!-- AKSI -->

                        <td>

                            <div class="action-cell">

                                <!-- DETAIL -->

                                <a
                                    href="/monitoring/3"
                                    class="btn-action btn-detail"
                                    title="Lihat detail data"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75-6.75-9.75 6.75S2.25 12 2.25 12Z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                        />

                                    </svg>

                                    Detail

                                </a>


                                <!-- EDIT -->

                                <a
                                    href="/monitoring/3/edit"
                                    class="btn-action btn-edit"
                                    title="Edit data"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="m16.862 3.487 3.651 3.651M4.5 19.5l1.04-4.16a2.25 2.25 0 0 1 .58-1.01l9.742-9.742a2.25 2.25 0 0 1 3.182 3.182L9.302 17.512a2.25 2.25 0 0 1-1.01.58L4.5 19.5Z"
                                        />

                                    </svg>

                                    Edit

                                </a>

                            </div>

                        </td>

                    </tr>


                    <!-- =================================================
                         DATA 4
                    ================================================== -->

                    <tr>

                        <td class="number-cell">
                            4
                        </td>

                        <td class="number-data">
                            3201012345678904
                        </td>

                        <td class="number-data">
                            3201010209940004
                        </td>

                        <td class="family-head">
                            Yuni Ariska
                        </td>

                        <td class="center">
                            6
                        </td>

                        <td>

                            <span class="status-badge status-approved">
                                Disetujui
                            </span>

                        </td>

                        <td>
                            Surabaya
                        </td>

                        <td>
                            Fitra
                        </td>

                        <td class="date-cell">
                            13 September 2026
                        </td>


                        <!-- AKSI -->

                        <td>

                            <div class="action-cell">

                                <!-- DETAIL -->

                                <a
                                    href="/monitoring/4"
                                    class="btn-action btn-detail"
                                    title="Lihat detail data"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75-6.75-9.75-6.75S2.25 12 2.25 12Z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                        />

                                    </svg>

                                    Detail

                                </a>


                                <!-- EDIT -->

                                <a
                                    href="/monitoring/4/edit"
                                    class="btn-action btn-edit"
                                    title="Edit data"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="m16.862 3.487 3.651 3.651M4.5 19.5l1.04-4.16a2.25 2.25 0 0 1 .58-.01l9.742-9.742a2.25 2.25 0 0 1 3.182 3.182L9.302 17.512a2.25 2.25 0 0 1-1.01.58L4.5 19.5Z"
                                        />

                                    </svg>

                                    Edit

                                </a>

                            </div>

                        </td>

                    </tr>


                    <!-- =================================================
                         DATA 5
                    ================================================== -->

                    <tr>

                        <td class="number-cell">
                            5
                        </td>

                        <td class="number-data">
                            3201012345678905
                        </td>

                        <td class="number-data">
                            3201010806910005
                        </td>

                        <td class="family-head">
                            Bayu Santoso
                        </td>

                        <td class="center">
                            2
                        </td>

                        <td>

                            <span class="status-badge status-reject">
                                Ditolak
                            </span>

                        </td>

                        <td>
                            Bandung
                        </td>

                        <td>
                            Putri
                        </td>

                        <td class="date-cell">
                            14 September 2026
                        </td>


                        <!-- AKSI -->

                        <td>

                            <div class="action-cell">

                                <!-- DETAIL -->

                                <a
                                    href="/monitoring/5"
                                    class="btn-action btn-detail"
                                    title="Lihat detail data"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75 6.75-9.75-6.75S2.25 12 2.25 12Z"
                                        />

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                                        />

                                    </svg>

                                    Detail

                                </a>


                                <!-- EDIT -->

                                <a
                                    href="/monitoring/5/edit"
                                    class="btn-action btn-edit"
                                    title="Edit data"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.8"
                                        stroke="currentColor"
                                    >

                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="m16.862 3.487 3.651 3.651M4.5 19.5l1.04-4.16a2.25 2.25 0 0 1 .58-.01l9.742-9.742a2.25 2.25 0 0 1 3.182 3.182L9.302 17.512a2.25 2.25 0 0 1-1.01.58L4.5 19.5Z"
                                        />

                                    </svg>

                                    Edit

                                </a>

                            </div>

                        </td>

                    </tr>


                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection