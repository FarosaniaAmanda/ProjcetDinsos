<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Verifikasi Data | Sistem Pendataan Sensus</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f6fa;
            color: #252525;
            min-height: 100vh;
        }

        /* =====================================================
           SIDEBAR
        ====================================================== */

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;

            width: 260px;
            height: 100vh;

            background: #252A86;
            color: #ffffff;

            display: flex;
            flex-direction: column;

            z-index: 1000;

            overflow-y: auto;
        }

        .sidebar-logo {
            height: 90px;

            display: flex;
            align-items: center;

            gap: 12px;

            padding: 15px 20px;

            border-bottom: 1px solid rgba(255,255,255,0.12);
        }

        .sidebar-logo img {
            width: 50px;
            height: 50px;

            object-fit: contain;

            border-radius: 8px;

            background: #ffffff;
        }

        .sidebar-logo-text {
            font-size: 15px;
            font-weight: 700;
            line-height: 1.3;
        }

        .sidebar-menu {
            padding: 20px 14px;

            flex: 1;
        }

        .menu-title {
            font-size: 11px;
            font-weight: 700;

            color: rgba(255,255,255,0.55);

            text-transform: uppercase;

            letter-spacing: 1px;

            margin: 8px 10px 12px;
        }

        .menu-link {
            display: flex;
            align-items: center;

            gap: 12px;

            width: 100%;

            padding: 12px 14px;

            margin-bottom: 5px;

            border-radius: 9px;

            color: rgba(255,255,255,0.82);

            text-decoration: none;

            font-size: 14px;
            font-weight: 500;

            transition: all .2s ease;
        }

        .menu-link:hover {
            background: rgba(255,255,255,0.10);
            color: #ffffff;
        }

        .menu-link.active {
            background: #ffffff;
            color: #252A86;

            font-weight: 700;

            box-shadow: 0 4px 12px rgba(0,0,0,0.10);
        }

        .menu-icon {
            width: 20px;
            height: 20px;

            display: flex;
            align-items: center;
            justify-content: center;

            flex-shrink: 0;
        }

        .sidebar-footer {
            padding: 15px 14px;

            border-top: 1px solid rgba(255,255,255,0.12);
        }

        .logout-link {
            display: flex;
            align-items: center;

            gap: 12px;

            width: 100%;

            padding: 12px 14px;

            border-radius: 9px;

            color: rgba(255,255,255,0.85);

            text-decoration: none;

            font-size: 14px;

            transition: all .2s ease;
        }

        .logout-link:hover {
            background: rgba(255,255,255,0.10);
            color: #ffffff;
        }


        /* =====================================================
           MAIN
        ====================================================== */

        .main {
            margin-left: 260px;

            min-height: 100vh;

            width: calc(100% - 260px);

            display: flex;
            flex-direction: column;
        }


        /* =====================================================
           HEADER
        ====================================================== */

        .header {
            height: 75px;

            background: #ffffff;

            border-bottom: 1px solid #e8e9ef;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 30px;

            position: sticky;
            top: 0;

            z-index: 900;
        }

        .header-left {
            display: flex;
            align-items: center;

            gap: 14px;
        }

        .header-logo {
            width: 42px;
            height: 42px;

            object-fit: contain;

            border-radius: 7px;
        }

        .header-title {
            font-size: 16px;
            font-weight: 700;

            color: #252A86;
        }

        .header-subtitle {
            font-size: 12px;

            color: #777;

            margin-top: 3px;
        }

        .admin-profile {
            display: flex;
            align-items: center;

            gap: 10px;
        }

        .admin-info {
            text-align: right;
        }

        .admin-name {
            font-size: 13px;
            font-weight: 700;

            color: #333;
        }

        .admin-role {
            font-size: 11px;

            color: #888;

            margin-top: 2px;
        }

        .admin-avatar {
            width: 38px;
            height: 38px;

            border-radius: 50%;

            background: #252A86;

            color: #ffffff;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 14px;
            font-weight: 700;
        }


        /* =====================================================
           CONTENT
        ====================================================== */

        .content {
            padding: 30px;
        }


        /* =====================================================
           PAGE HEADER
        ====================================================== */

        .page-header {
            margin-bottom: 25px;
        }

        .page-kicker {
            font-size: 12px;

            color: #252A86;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: 1px;

            margin-bottom: 7px;
        }

        .page-title {
            font-size: 28px;

            color: #222;

            font-weight: 700;

            margin-bottom: 7px;
        }

        .page-description {
            font-size: 14px;

            color: #777;

            line-height: 1.6;
        }


        /* =====================================================
           SEARCH
        ====================================================== */

        .search-panel {
            background: #ffffff;

            border: 1px solid #e7e8ee;

            border-radius: 12px;

            padding: 18px;

            margin-bottom: 22px;

            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }

        .search-form {
            display: flex;

            gap: 12px;

            width: 100%;
        }

        .search-box {
            flex: 1;

            position: relative;
        }

        .search-box input {
            width: 100%;

            height: 44px;

            border: 1px solid #dfe1e8;

            border-radius: 8px;

            padding: 0 15px;

            font-size: 13px;

            outline: none;

            transition: .2s ease;
        }

        .search-box input:focus {
            border-color: #252A86;

            box-shadow: 0 0 0 3px rgba(37,42,134,0.08);
        }

        .btn-search {
            height: 44px;

            padding: 0 22px;

            border: none;

            border-radius: 8px;

            background: #252A86;

            color: #ffffff;

            font-size: 13px;

            font-weight: 600;

            cursor: pointer;

            transition: .2s ease;
        }

        .btn-search:hover {
            background: #1e236f;
        }


        /* =====================================================
           STATISTICS
        ====================================================== */

        .stats-grid {
            display: grid;

            grid-template-columns: repeat(6, 1fr);

            gap: 14px;

            margin-bottom: 24px;
        }

        .stat-card {
            background: #ffffff;

            border: 1px solid #e7e8ee;

            border-radius: 12px;

            padding: 18px;

            box-shadow: 0 2px 8px rgba(0,0,0,0.03);

            transition: transform .2s ease,
                        box-shadow .2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);

            box-shadow: 0 6px 16px rgba(0,0,0,0.07);
        }

        .stat-label {
            font-size: 11px;

            color: #777;

            margin-bottom: 8px;

            line-height: 1.4;
        }

        .stat-value {
            font-size: 24px;

            font-weight: 700;

            color: #252A86;
        }


        /* =====================================================
           DATA PANEL
        ====================================================== */

        .data-panel {
            background: #ffffff;

            border: 1px solid #e7e8ee;

            border-radius: 12px;

            box-shadow: 0 2px 8px rgba(0,0,0,0.03);

            overflow: hidden;
        }

        .data-panel-header {
            padding: 20px 22px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            border-bottom: 1px solid #ececf1;
        }

        .data-panel-title {
            font-size: 17px;

            font-weight: 700;

            color: #252525;
        }

        .data-panel-description {
            font-size: 12px;

            color: #888;

            margin-top: 4px;
        }

        .filter-select {
            height: 40px;

            min-width: 170px;

            border: 1px solid #dfe1e8;

            border-radius: 8px;

            padding: 0 12px;

            background: #ffffff;

            color: #444;

            font-size: 12px;

            outline: none;

            cursor: pointer;
        }

        .filter-select:focus {
            border-color: #252A86;

            box-shadow: 0 0 0 3px rgba(37,42,134,0.08);
        }


        /* =====================================================
           TABLE
        ====================================================== */

        .table-wrapper {
            width: 100%;

            overflow-x: auto;
        }

        .data-table {
            width: 100%;

            min-width: 1250px;

            border-collapse: collapse;
        }

        .data-table th {
            background: #f8f8fb;

            color: #666;

            font-size: 11px;

            font-weight: 700;

            text-align: left;

            padding: 13px 14px;

            border-bottom: 1px solid #e7e8ee;

            white-space: nowrap;
        }

        .data-table td {
            padding: 14px;

            font-size: 12px;

            color: #444;

            border-bottom: 1px solid #eeeeF3;

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
           STATUS
        ====================================================== */

        .status {
            display: inline-flex;

            align-items: center;

            padding: 5px 9px;

            border-radius: 20px;

            font-size: 10px;

            font-weight: 700;

            white-space: nowrap;
        }

        .status-warning {
            background: #fff7df;

            color: #a87900;
        }

        .status-draft {
            background: #f1f2f5;

            color: #686b73;
        }

        .status-info {
            background: #eaf0ff;

            color: #3d5ab8;
        }

        .status-success {
            background: #e8f7ee;

            color: #21864a;
        }

        .status-danger {
            background: #fdecec;

            color: #c74343;
        }


        /* =====================================================
           ACTION BUTTON
        ====================================================== */

        .action-group {
            display: flex;

            gap: 6px;
        }

        .btn-action {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            height: 32px;

            padding: 0 10px;

            border-radius: 6px;

            text-decoration: none;

            font-size: 11px;

            font-weight: 600;

            border: 1px solid transparent;

            transition: .2s ease;
        }

        .btn-detail {
            background: #eef0ff;

            color: #252A86;

            border-color: #dfe3ff;
        }

        .btn-detail:hover {
            background: #e2e5ff;
        }

        .btn-edit {
            background: #fff8e5;

            color: #a47a00;

            border-color: #f3e4b5;
        }

        .btn-edit:hover {
            background: #fff1c7;
        }


        /* =====================================================
           RESPONSIVE
        ====================================================== */

        @media (max-width: 1200px) {

            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 900px) {

            .sidebar {
                width: 220px;
            }

            .main {
                margin-left: 220px;

                width: calc(100% - 220px);
            }

            .content {
                padding: 20px;
            }

            .header {
                padding: 0 20px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 700px) {

            .sidebar {
                position: relative;

                width: 100%;

                height: auto;
            }

            .sidebar-logo {
                height: 75px;
            }

            .sidebar-menu {
                padding: 12px;
            }

            .sidebar-footer {
                display: none;
            }

            .main {
                margin-left: 0;

                width: 100%;
            }

            .header {
                position: relative;

                height: auto;

                min-height: 70px;

                padding: 12px 16px;
            }

            .header-subtitle {
                display: none;
            }

            .admin-info {
                display: none;
            }

            .content {
                padding: 16px;
            }

            .page-title {
                font-size: 23px;
            }

            .search-form {
                flex-direction: column;
            }

            .btn-search {
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .data-panel-header {
                align-items: flex-start;

                flex-direction: column;
            }

            .filter-select {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside class="sidebar">

        <div class="sidebar-logo">

            <img
                src="{{ asset('images/dinsos.png') }}"
                alt="Logo"
            >

            <div class="sidebar-logo-text">
                Sistem Pendataan<br>
                Sensus
            </div>

        </div>


        <nav class="sidebar-menu">

            <div class="menu-title">
                Menu Utama
            </div>


            <!-- Dashboard -->

            <a
                href="/dashboard"
                class="menu-link {{ request()->is('dashboard') ? 'active' : '' }}"
            >

                <span class="menu-icon">

                    <svg
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                    </svg>

                </span>

                <span>Dashboard</span>

            </a>


            <!-- Periode -->

            <a
                href="/periode"
                class="menu-link {{ request()->is('periode*') ? 'active' : '' }}"
            >

                <span class="menu-icon">

                    <svg
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <rect
                            x="3"
                            y="4"
                            width="18"
                            height="17"
                            rx="2"
                        ></rect>

                        <line
                            x1="16"
                            y1="2"
                            x2="16"
                            y2="6"
                        ></line>

                        <line
                            x1="8"
                            y1="2"
                            x2="8"
                            y2="6"
                        ></line>

                        <line
                            x1="3"
                            y1="10"
                            x2="21"
                            y2="10"
                        ></line>
                    </svg>

                </span>

                <span>Periode</span>

            </a>


            <!-- Petugas -->

            <a
                href="#"
                class="menu-link"
            >

                <span class="menu-icon">

                    <svg
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"
                        ></path>

                        <circle
                            cx="9"
                            cy="7"
                            r="4"
                        ></circle>

                        <path
                            d="M22 21v-2a4 4 0 0 0-3-3.87"
                        ></path>

                        <path
                            d="M16 3.13a4 4 0 0 1 0 7.75"
                        ></path>
                    </svg>

                </span>

                <span>Petugas</span>

            </a>


            <!-- Responden -->

            <a
                href="#"
                class="menu-link"
            >

                <span class="menu-icon">

                    <svg
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"
                        ></path>

                        <circle
                            cx="12"
                            cy="7"
                            r="4"
                        ></circle>
                    </svg>

                </span>

                <span>Responden</span>

            </a>


            <!-- Kuisioner -->

            <a
                href="#"
                class="menu-link"
            >

                <span class="menu-icon">

                    <svg
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"
                        ></path>

                        <polyline
                            points="14 2 14 8 20 8"
                        ></polyline>

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

                </span>

                <span>Kuisioner</span>

            </a>


            <!-- Verifikator -->

            <a
                href="{{ route('verifikasi.index') }}"
                class="menu-link {{ request()->is('verifikasi*') ? 'active' : '' }}"
            >

                <span class="menu-icon">

                    <svg
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M9 11l3 3L22 4"></path>

                        <path
                            d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"
                        ></path>
                    </svg>

                </span>

                <span>Verifikasi</span>

            </a>


            <!-- Monitoring -->

            <a
                href="/monitoring"
                class="menu-link {{ request()->is('monitoring*') ? 'active' : '' }}"
            >

                <span class="menu-icon">

                    <svg
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <polyline
                            points="3 3 3 21 21 21"
                        ></polyline>

                        <polyline
                            points="7 16 11 12 14 15 21 8"
                        ></polyline>
                    </svg>

                </span>

                <span>Monitoring</span>

            </a>


            <!-- Laporan -->

            <a
                href="#"
                class="menu-link"
            >

                <span class="menu-icon">

                    <svg
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"
                        ></path>

                        <polyline
                            points="14 2 14 8 20 8"
                        ></polyline>

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

                </span>

                <span>Laporan</span>

            </a>


            <!-- Master -->

            <a
                href="#"
                class="menu-link"
            >

                <span class="menu-icon">

                    <svg
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <circle
                            cx="12"
                            cy="12"
                            r="3"
                        ></circle>

                        <path
                            d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06-1.42 1.42-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21h-2v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06-1.42-1.42.06-.06A1.65 1.65 0 0 0 8.6 15a1.65 1.65 0 0 0-1.51-1H7v-2h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06 1.42-1.42.06.06a1.65 1.65 0 0 0 1.82.33h.01A1.65 1.65 0 0 0 12.52 6H12V4h2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06 1.42 1.42-.06.06A1.65 1.65 0 0 0 18.6 9a1.65 1.65 0 0 0 1.51 1H20v2h-.09a1.65 1.65 0 0 0-1.51 1z"
                        ></path>
                    </svg>

                </span>

                <span>Master</span>

            </a>

        </nav>


        <!-- =====================================================
             LOGOUT
        ====================================================== -->

        <div class="sidebar-footer">

            <a
                href="/logout"
                class="logout-link"
            >

                <span class="menu-icon">

                    <svg
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"
                        ></path>

                        <polyline
                            points="16 17 21 12 16 7"
                        ></polyline>

                        <line
                            x1="21"
                            y1="12"
                            x2="9"
                            y2="12"
                        ></line>
                    </svg>

                </span>

                <span>Keluar</span>

            </a>

        </div>

    </aside>


    <!-- =====================================================
         MAIN
    ====================================================== -->

    <div class="main">


        <!-- =====================================================
             HEADER
        ====================================================== -->

        <header class="header">

            <div class="header-left">

                <div>

                    <div class="header-title">
                        Sistem Pendataan Dinas Sosial Kota Pasuruan
                    </div>

                    <div class="header-subtitle">
                        Panel Administrasi
                    </div>

                </div>

            </div>


            <div class="admin-profile">

                <div class="admin-info">

                    <div class="admin-name">
                        Operator
                    </div>

                    <div class="admin-role">
                        Admin
                    </div>

                </div>

                <div class="admin-avatar">
                    A
                </div>

            </div>

        </header>


        <!-- =====================================================
             CONTENT
        ====================================================== -->

        <main class="content">


            <!-- PAGE HEADER -->

            <div class="page-header">

                <div class="page-kicker">
                    ADMIN
                </div>

                <h1 class="page-title">
                    Sistem Verifikasi
                </h1>

                <p class="page-description">
                    Kelola, periksa, dan perbarui data hasil pendataan responden.
                </p>

            </div>


            <!-- =================================================
                 SEARCH
            ================================================== -->

            <div class="search-panel">

                <form
                    action="{{ route('verifikasi.index') }}"
                    method="GET"
                    class="search-form"
                >

                    <div class="search-box">

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari berdasarkan nama, NIK, atau No. KK..."
                        >

                    </div>

                    <button
                        type="submit"
                        class="btn-search"
                    >
                        Cari Data
                    </button>

                </form>

            </div>


            <!-- =================================================
                 STATISTICS
            ================================================== -->

            <div class="stats-grid">


                <div class="stat-card">

                    <div class="stat-label">
                        Total Responden
                    </div>

                    <div class="stat-value">
                        1.245
                    </div>

                </div>


                <div class="stat-card">

                    <div class="stat-label">
                        Sudah Didata
                    </div>

                    <div class="stat-value">
                        980
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


            <!-- =================================================
                 DATA PANEL
            ================================================== -->

            <div class="data-panel">


                <div class="data-panel-header">

                    <div>

                        <div class="data-panel-title">
                            Data Hasil Pendataan
                        </div>

                        <div class="data-panel-description">
                            Daftar data responden yang telah masuk ke sistem.
                        </div>

                    </div>


                    <form
                        action="{{ route('verifikasi.index') }}"
                        method="GET"
                    >

                        @if(request('search'))

                            <input
                                type="hidden"
                                name="search"
                                value="{{ request('search') }}"
                            >

                        @endif

                        <select
                            name="status"
                            class="filter-select"
                            onchange="this.form.submit()"
                        >

                            <option value="">
                                Semua Status
                            </option>

                            <option
                                value="menunggu"
                                {{ request('status') == 'menunggu' ? 'selected' : '' }}
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
                                value="belum"
                                {{ request('status') == 'belum' ? 'selected' : '' }}
                            >
                                Belum Diproses
                            </option>

                            <option
                                value="disetujui"
                                {{ request('status') == 'disetujui' ? 'selected' : '' }}
                            >
                                Disetujui
                            </option>

                            <option
                                value="ditolak"
                                {{ request('status') == 'ditolak' ? 'selected' : '' }}
                            >
                                Ditolak
                            </option>

                        </select>

                    </form>

                </div>


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


                        <tbody>


                            <!-- DATA 1 -->

                            <tr>

                                <td>1</td>

                                <td>3575010101010001</td>

                                <td>3575010101010001</td>

                                <td>
                                    Budi Santoso
                                </td>

                                <td>
                                    4 Orang
                                </td>

                                <td>

                                    <span class="status status-warning">
                                        Menunggu Verifikasi
                                    </span>

                                </td>

                                <td>
                                    Bugul Kidul
                                </td>

                                <td>
                                    Ahmad
                                </td>

                                <td>
                                    20 September 2026
                                </td>

                                <td>

                                    <div class="action-group">

                                        <a
                                            href="/monitoring/1"
                                            class="btn-action btn-detail"
                                        >
                                            Detail
                                        </a>

                                        <a
                                            href="/monitoring/1/edit"
                                            class="btn-action btn-edit"
                                        >
                                            Edit
                                        </a>

                                    </div>

                                </td>

                            </tr>


                            <!-- DATA 2 -->

                            <tr>

                                <td>2</td>

                                <td>3575010101010002</td>

                                <td>3575010101010002</td>

                                <td>
                                    Siti Aminah
                                </td>

                                <td>
                                    3 Orang
                                </td>

                                <td>

                                    <span class="status status-draft">
                                        Draft
                                    </span>

                                </td>

                                <td>
                                    Purworejo
                                </td>

                                <td>
                                    Rina
                                </td>

                                <td>
                                    20 September 2026
                                </td>

                                <td>

                                    <div class="action-group">

                                        <a
                                            href="/monitoring/2"
                                            class="btn-action btn-detail"
                                        >
                                            Detail
                                        </a>

                                        <a
                                            href="/monitoring/2/edit"
                                            class="btn-action btn-edit"
                                        >
                                            Edit
                                        </a>

                                    </div>

                                </td>

                            </tr>


                            <!-- DATA 3 -->

                            <tr>

                                <td>3</td>

                                <td>3575010101010003</td>

                                <td>3575010101010003</td>

                                <td>
                                    Agus Setiawan
                                </td>

                                <td>
                                    5 Orang
                                </td>

                                <td>

                                    <span class="status status-info">
                                        Belum Diproses
                                    </span>

                                </td>

                                <td>
                                    Gadingrejo
                                </td>

                                <td>
                                    Dimas
                                </td>

                                <td>
                                    19 September 2026
                                </td>

                                <td>

                                    <div class="action-group">

                                        <a
                                            href="/monitoring/3"
                                            class="btn-action btn-detail"
                                        >
                                            Detail
                                        </a>

                                        <a
                                            href="/monitoring/3/edit"
                                            class="btn-action btn-edit"
                                        >
                                            Edit
                                        </a>

                                    </div>

                                </td>

                            </tr>


                            <!-- DATA 4 -->

                            <tr>

                                <td>4</td>

                                <td>3575010101010004</td>

                                <td>3575010101010004</td>

                                <td>
                                    Dewi Lestari
                                </td>

                                <td>
                                    2 Orang
                                </td>

                                <td>

                                    <span class="status status-success">
                                        Disetujui
                                    </span>

                                </td>

                                <td>
                                    Panggungrejo
                                </td>

                                <td>
                                    Sari
                                </td>

                                <td>
                                    18 September 2026
                                </td>

                                <td>

                                    <div class="action-group">

                                        <a
                                            href="/monitoring/4"
                                            class="btn-action btn-detail"
                                        >
                                            Detail
                                        </a>

                                        <a
                                            href="/monitoring/4/edit"
                                            class="btn-action btn-edit"
                                        >
                                            Edit
                                        </a>

                                    </div>

                                </td>

                            </tr>


                            <!-- DATA 5 -->

                            <tr>

                                <td>5</td>

                                <td>3575010101010005</td>

                                <td>3575010101010005</td>

                                <td>
                                    Eko Prasetyo
                                </td>

                                <td>
                                    6 Orang
                                </td>

                                <td>

                                    <span class="status status-danger">
                                        Ditolak
                                    </span>

                                </td>

                                <td>
                                    Bugul Kidul
                                </td>

                                <td>
                                    Andi
                                </td>

                                <td>
                                    17 September 2026
                                </td>

                                <td>

                                    <div class="action-group">

                                        <a
                                            href="/monitoring/5"
                                            class="btn-action btn-detail"
                                        >
                                            Detail
                                        </a>

                                        <a
                                            href="/monitoring/5/edit"
                                            class="btn-action btn-edit"
                                        >
                                            Edit
                                        </a>

                                    </div>

                                </td>

                            </tr>


                        </tbody>

                    </table>

                </div>

            </div>

        </main>

    </div>

</body>

</html>