<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verifikasi Data | Sistem Pendataan Dinas Sosial Kota Pasuruan</title>

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
            transition: transform .3s ease;
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
           MOBILE MENU BUTTON
        ====================================================== */

        .mobile-menu-btn {
            display: none;

            width: 42px;
            height: 42px;

            border: none;
            border-radius: 8px;

            background: #f1f2f8;

            cursor: pointer;

            align-items: center;
            justify-content: center;

            flex-direction: column;

            gap: 5px;

            flex-shrink: 0;
        }

        .mobile-menu-btn span {
            display: block;

            width: 20px;
            height: 2px;

            background: #252A86;

            border-radius: 2px;

            transition: all .25s ease;
        }


        /* =====================================================
           SIDEBAR OVERLAY
        ====================================================== */

        .sidebar-overlay {
            display: none;
        }


        /* =====================================================
           RESPONSIVE
        ====================================================== */

        @media (max-width: 1200px) {

            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }

        }


        /* =====================================================
           TABLET
        ====================================================== */

        @media (max-width: 900px) {

            .sidebar {
                position: fixed;

                top: 0;
                left: 0;

                width: 270px;
                height: 100vh;

                transform: translateX(-100%);

                transition: transform .3s ease;

                z-index: 1100;

                overflow-y: auto;
            }

            .sidebar.active {
                transform: translateX(0);
            }


            /* OVERLAY */

            .sidebar-overlay {
                display: block;

                position: fixed;

                inset: 0;

                background: rgba(0, 0, 0, .40);

                opacity: 0;

                visibility: hidden;

                transition: all .3s ease;

                z-index: 1050;
            }

            .sidebar-overlay.active {
                opacity: 1;

                visibility: visible;
            }


            /* MAIN */

            .main {
                margin-left: 0;

                width: 100%;
            }


            /* HEADER */

            .header {
                height: 70px;

                padding: 0 20px;

                position: sticky;

                top: 0;

                z-index: 900;
            }


            /* HAMBURGER */

            .mobile-menu-btn {
                display: flex;
            }

            .header-left {
                gap: 12px;
            }

            .header-title {
                font-size: 15px;
            }

            .header-subtitle {
                display: none;
            }


            /* CONTENT */

            .content {
                padding: 20px;
            }


            /* STATISTICS */

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

        }


        /* =====================================================
           MOBILE
        ====================================================== */

        @media (max-width: 600px) {

            .header {
                height: 64px;

                min-height: 64px;

                padding: 0 14px;
            }

            .mobile-menu-btn {
                width: 38px;

                height: 38px;
            }

            .mobile-menu-btn span {
                width: 18px;
            }

            .header-title {
                font-size: 13px;

                line-height: 1.3;
            }

            .admin-info {
                display: none;
            }

            .admin-avatar {
                width: 34px;

                height: 34px;

                font-size: 13px;
            }

            .content {
                padding: 16px;
            }

            .page-title {
                font-size: 22px;
            }

            .page-description {
                font-size: 13px;
            }

            .search-form {
                flex-direction: column;
            }

            .btn-search {
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: 1fr;

                gap: 10px;
            }

            .stat-card {
                padding: 15px;
            }

            .data-panel-header {
                align-items: flex-start;

                flex-direction: column;

                gap: 12px;
            }

            .filter-select {
                width: 100%;
            }

        }


        /* =====================================================
           SMALL MOBILE
        ====================================================== */

        @media (max-width: 400px) {

            .header-title {
                font-size: 12px;
            }

            .content {
                padding: 12px;
            }

            .page-title {
                font-size: 20px;
            }

            .sidebar {
                width: 250px;
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
                Dinas Sosial Kota Pasuruan
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


            <!-- Verifikasi -->

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


        <!-- LOGOUT -->

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


    <!-- OVERLAY MOBILE -->

    <div
        class="sidebar-overlay"
        id="sidebarOverlay"
    ></div>


    <!-- MAIN -->

    <div class="main">


        <!-- HEADER -->

        <header class="header">

            <!-- HAMBURGER MOBILE -->

            <button
                class="mobile-menu-btn"
                id="mobileMenuBtn"
                type="button"
                aria-label="Buka menu"
                aria-expanded="false"
            >
                <span></span>
                <span></span>
                <span></span>
            </button>


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


        <!-- CONTENT -->

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


            <!-- SEARCH -->

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


            <!-- STATISTICS -->

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


            <!-- DATA PANEL -->

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

                            @forelse($data as $item)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        {{ $item['no_kk'] }}
                                    </td>

                                    <td>
                                        {{ $item['nik'] }}
                                    </td>

                                    <td>
                                        {{ $item['nama'] }}
                                    </td>

                                    <td>
                                        {{ $item['anggota'] }} Orang
                                    </td>

                                    <td>

                                        @if($item['status'] === 'menunggu')

                                            <span class="status status-warning">
                                                {{ $item['status_label'] }}
                                            </span>

                                        @elseif($item['status'] === 'draft')

                                            <span class="status status-draft">
                                                {{ $item['status_label'] }}
                                            </span>

                                        @elseif($item['status'] === 'belum')

                                            <span class="status status-info">
                                                {{ $item['status_label'] }}
                                            </span>

                                        @elseif($item['status'] === 'disetujui')

                                            <span class="status status-success">
                                                {{ $item['status_label'] }}
                                            </span>

                                        @elseif($item['status'] === 'ditolak')

                                            <span class="status status-danger">
                                                {{ $item['status_label'] }}
                                            </span>

                                        @endif

                                    </td>

                                    <td>
                                        {{ $item['wilayah'] }}
                                    </td>

                                    <td>
                                        {{ $item['petugas'] }}
                                    </td>

                                    <td>
                                        {{ $item['tanggal'] }}
                                    </td>

                                    <td>

                                        <div class="action-group">

                                            <a
                                                href="/monitoring/{{ $item['no'] }}"
                                                class="btn-action btn-detail"
                                            >
                                                Detail
                                            </a>

                                            <a
                                                href="/monitoring/{{ $item['no'] }}/edit"
                                                class="btn-action btn-edit"
                                            >
                                                Edit
                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="10"
                                        style="text-align: center; padding: 40px; color: #888;"
                                    >

                                        @if($search)

                                            Data dengan kata pencarian
                                            "<strong>{{ $search }}</strong>"
                                            tidak ditemukan.

                                        @else

                                            Tidak ada data.

                                        @endif

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </main>

    </div>


    <!-- =====================================================
         MOBILE SIDEBAR SCRIPT
    ====================================================== -->

    <script>

        const mobileMenuBtn =
            document.getElementById('mobileMenuBtn');

        const sidebar =
            document.querySelector('.sidebar');

        const sidebarOverlay =
            document.getElementById('sidebarOverlay');


        /* BUKA / TUTUP SIDEBAR */

        mobileMenuBtn.addEventListener('click', function () {

            const isOpen =
                sidebar.classList.toggle('active');

            sidebarOverlay.classList.toggle('active');

            mobileMenuBtn.setAttribute(
                'aria-expanded',
                isOpen ? 'true' : 'false'
            );

        });


        /* TUTUP SAAT OVERLAY DIKLIK */

        sidebarOverlay.addEventListener('click', function () {

            sidebar.classList.remove('active');

            sidebarOverlay.classList.remove('active');

            mobileMenuBtn.setAttribute(
                'aria-expanded',
                'false'
            );

        });


        /* TUTUP SIDEBAR SETELAH MEMILIH MENU */

        document
            .querySelectorAll('.sidebar .menu-link')
            .forEach(function (link) {

                link.addEventListener('click', function () {

                    if (window.innerWidth <= 900) {

                        sidebar.classList.remove('active');

                        sidebarOverlay.classList.remove('active');

                        mobileMenuBtn.setAttribute(
                            'aria-expanded',
                            'false'
                        );

                    }

                });

            });


        /* RESET SAAT KEMBALI KE DESKTOP */

        window.addEventListener('resize', function () {

            if (window.innerWidth > 900) {

                sidebar.classList.remove('active');

                sidebarOverlay.classList.remove('active');

                mobileMenuBtn.setAttribute(
                    'aria-expanded',
                    'false'
                );

            }

        });

    </script>

</body>

</html>