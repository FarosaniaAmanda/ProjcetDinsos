<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Master | Sistem Pendataan Dinas Sosial Kota Pasuruan
    </title>

    <style>

        /* =====================================================
           RESET
        ====================================================== */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        html,
        body {
            width: 100%;
            min-height: 100%;
        }


        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f6fa;
            color: #252525;
            min-height: 100vh;
            overflow-x: hidden;
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

            z-index: 1000;

            overflow-y: auto;

            transition: transform .3s ease;
        }


        .sidebar-logo {

            height: 90px;

            display: flex;
            align-items: center;

            gap: 12px;

            padding: 15px 18px;

            border-bottom:
                1px solid rgba(255,255,255,.12);
        }


        .sidebar-logo img {

            width: 50px;
            height: 50px;

            object-fit: contain;

            flex-shrink: 0;
        }


        .sidebar-logo-text {

            font-size: 13px;

            line-height: 1.4;

            font-weight: 600;
        }


        .sidebar-menu {

            padding: 18px 12px;
        }


        .menu-title {

            font-size: 11px;

            color: rgba(255,255,255,.55);

            text-transform: uppercase;

            letter-spacing: 1px;

            margin:
                10px 10px 12px;
        }


        .menu-link {

            display: flex;

            align-items: center;

            gap: 12px;

            width: 100%;

            padding: 12px 14px;

            margin-bottom: 5px;

            border-radius: 9px;

            color: rgba(255,255,255,.85);

            text-decoration: none;

            font-size: 14px;

            transition: all .2s ease;
        }


        .menu-link:hover {

            background:
                rgba(255,255,255,.10);

            color: #ffffff;
        }


        .menu-link.active {

            background: #ffffff;

            color: #252A86;

            font-weight: 600;
        }


        .menu-icon {

            width: 20px;

            min-width: 20px;

            display: flex;

            align-items: center;

            justify-content: center;
        }


        .menu-icon svg {

            width: 18px;
            height: 18px;
        }


        /* =====================================================
           MAIN
        ====================================================== */

        .main {

            margin-left: 260px;

            min-height: 100vh;

            width:
                calc(100% - 260px);

            display: flex;

            flex-direction: column;
        }


        /* =====================================================
           HEADER
        ====================================================== */

        .header {

            height: 75px;

            background: #ffffff;

            border-bottom:
                1px solid #e8e9ef;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 0 30px;

            position: sticky;

            top: 0;

            z-index: 900;
        }


        /* =====================================================
           HAMBURGER
           SAMA DENGAN VERIFIKASI
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

            transition:
                all .25s ease;
        }


        /* =====================================================
           HEADER LEFT
        ====================================================== */

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


        /* =====================================================
           ADMIN PROFILE
        ====================================================== */

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
           MASTER TABS
        ====================================================== */

        .master-tabs {

            display: flex;

            gap: 14px;

            margin-bottom: 20px;

            overflow-x: auto;

            padding-bottom: 4px;
        }


        .master-tab {

            flex: 1;

            min-width: 210px;

            background: #ffffff;

            border:
                1px solid #e7e8ee;

            border-radius: 10px;

            padding: 18px;

            cursor: pointer;

            text-align: left;

            transition:
                all .2s ease;
        }


        .master-tab:hover {

            border-color: #252A86;
        }


        .master-tab.active {

            background: #252A86;

            border-color: #252A86;

            color: #ffffff;
        }


        .master-tab-title {

            font-size: 16px;

            font-weight: 700;

            margin-bottom: 6px;
        }


        .master-tab-desc {

            font-size: 13px;

            color: #777;

            line-height: 1.5;
        }


        .master-tab.active
        .master-tab-desc {

            color:
                rgba(255,255,255,.80);
        }


        /* =====================================================
           SECTION
        ====================================================== */

        .master-section {

            display: none;
        }


        .master-section.active {

            display: block;
        }


        /* =====================================================
           CARD
        ====================================================== */

        .data-card {

            background: #ffffff;

            border:
                1px solid #e7e8ee;

            border-radius: 10px;

            overflow: hidden;

            margin-bottom: 20px;
        }


        .data-card-header {

            padding: 20px;

            border-bottom:
                1px solid #e7e8ee;
        }


        .data-card-header h3 {

            font-size: 18px;

            color: #222;

            margin-bottom: 6px;
        }


        .data-card-header p {

            font-size: 13px;

            color: #777;

            line-height: 1.5;
        }


        /* =====================================================
           FORM
        ====================================================== */

        .form-body {

            padding: 20px;
        }


        .form-grid {

            display: grid;

            grid-template-columns:
                repeat(2, minmax(0, 1fr));

            gap: 16px;
        }


        .form-group {

            display: flex;

            flex-direction: column;

            gap: 7px;
        }


        .form-group.full {

            grid-column:
                1 / -1;
        }


        .form-group label {

            font-size: 13px;

            font-weight: 700;

            color: #333;
        }


        .form-group input,
        .form-group select,
        .form-group textarea {

            width: 100%;

            border:
                1px solid #dcdfe8;

            border-radius: 8px;

            padding: 11px 12px;

            font-family:
                Arial, Helvetica, sans-serif;

            font-size: 13px;

            color: #333;

            outline: none;

            background: #ffffff;
        }


        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {

            border-color: #252A86;

            box-shadow:
                0 0 0 3px
                rgba(37,42,134,.08);
        }


        .form-group textarea {

            min-height: 90px;

            resize: vertical;
        }


        .form-actions {

            display: flex;

            justify-content: flex-end;

            gap: 10px;

            margin-top: 20px;
        }


        .btn {

            border: none;

            border-radius: 8px;

            padding: 11px 18px;

            font-size: 13px;

            font-weight: 700;

            cursor: pointer;
        }


        .btn-primary {

            background: #252A86;

            color: #ffffff;
        }


        .btn-secondary {

            background: #f1f2f8;

            color: #444;
        }


        /* =====================================================
           TABLE
        ====================================================== */

        .table-wrapper {

            width: 100%;

            overflow-x: auto;

            -webkit-overflow-scrolling:
                touch;
        }


        .data-table {

            width: 100%;

            min-width: 1250px;

            border-collapse:
                collapse;
        }


        .data-table th {

            background: #f7f8fb;

            color: #333;

            font-size: 12px;

            font-weight: 700;

            text-align: left;

            padding: 13px 15px;

            border-bottom:
                1px solid #e7e8ee;

            white-space: nowrap;
        }


        .data-table td {

            padding: 13px 15px;

            border-bottom:
                1px solid #eef0f3;

            color: #555;

            font-size: 12px;

            white-space: nowrap;
        }


        .empty-row {

            text-align: center !important;

            color: #999 !important;

            padding: 30px !important;
        }


        .action-buttons {

            display: flex;

            gap: 7px;
        }


        .btn-edit,
        .btn-delete {

            border: none;

            border-radius: 6px;

            padding: 7px 10px;

            font-size: 11px;

            font-weight: 600;

            cursor: pointer;
        }


        .btn-edit {

            background: #eef0ff;

            color: #252A86;
        }


        .btn-delete {

            background: #fff0f0;

            color: #dc2626;
        }


        /* =====================================================
           SIDEBAR OVERLAY
        ====================================================== */

        .sidebar-overlay {

            display: none;
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

                transform:
                    translateX(-100%);

                transition:
                    transform .3s ease;

                z-index: 1100;

                overflow-y: auto;
            }


            .sidebar.active {

                transform:
                    translateX(0);
            }


            .sidebar-overlay {

                display: block;

                position: fixed;

                inset: 0;

                background:
                    rgba(0,0,0,.40);

                opacity: 0;

                visibility: hidden;

                transition:
                    all .3s ease;

                z-index: 1050;
            }


            .sidebar-overlay.active {

                opacity: 1;

                visibility: visible;
            }


            .main {

                margin-left: 0;

                width: 100%;
            }


            .header {

                height: 70px;

                padding: 0 20px;

                position: sticky;

                top: 0;

                z-index: 900;
            }


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


            .content {

                padding: 20px;
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


            /* SAMA DENGAN VERIFIKASI */

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

                white-space: nowrap;

                overflow: hidden;

                text-overflow: ellipsis;
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


            .master-tabs {

                gap: 10px;
            }


            .master-tab {

                min-width: 210px;

                padding: 14px;
            }


            .data-card-header {

                padding: 16px;
            }


            .form-body {

                padding: 16px;
            }


            .form-grid {

                grid-template-columns: 1fr;

                gap: 14px;
            }


            .form-group.full {

                grid-column: auto;
            }


            .form-actions {

                flex-direction: column;
            }


            .form-actions .btn {

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

    <aside class="sidebar" id="sidebar">

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


            <!-- DASHBOARD -->

            <a
                href="/dashboard"
                class="menu-link
                    {{ request()->is('dashboard') ? 'active' : '' }}"
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
                            y="3"
                            width="7"
                            height="7"
                        ></rect>

                        <rect
                            x="14"
                            y="3"
                            width="7"
                            height="7"
                        ></rect>

                        <rect
                            x="3"
                            y="14"
                            width="7"
                            height="7"
                        ></rect>

                        <rect
                            x="14"
                            y="14"
                            width="7"
                            height="7"
                        ></rect>

                    </svg>

                </span>

                <span>
                    Dashboard
                </span>

            </a>


            <!-- PERIODE -->

            <a
                href="/periode"
                class="menu-link
                    {{ request()->is('periode*') ? 'active' : '' }}"
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

                <span>
                    Periode
                </span>

            </a>


            <!-- PETUGAS -->

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

                <span>
                    Petugas
                </span>

            </a>


            <!-- RESPONDEN -->

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
                            cx="9"
                            cy="7"
                            r="4"
                        ></circle>

                        <path
                            d="M3 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"
                        ></path>

                        <line
                            x1="19"
                            y1="8"
                            x2="19"
                            y2="14"
                        ></line>

                        <line
                            x1="16"
                            y1="11"
                            x2="22"
                            y2="11"
                        ></line>

                    </svg>

                </span>

                <span>
                    Responden
                </span>

            </a>


            <!-- KUISIONER -->

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

                <span>
                    Kuisioner
                </span>

            </a>


            <!-- VERIFIKASI -->

            <a
                href="{{ route('verifikasi.index') }}"
                class="menu-link
                    {{ request()->is('verifikasi*') ? 'active' : '' }}"
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
                            points="20 6 9 17 4 12"
                        ></polyline>

                    </svg>

                </span>

                <span>
                    Verifikasi
                </span>

            </a>


            <!-- MONITORING -->

            <a
                href="/monitoring"
                class="menu-link
                    {{ request()->is('monitoring*') ? 'active' : '' }}"
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
                            r="9"
                        ></circle>

                        <circle
                            cx="12"
                            cy="12"
                            r="3"
                        ></circle>

                    </svg>

                </span>

                <span>
                    Monitoring
                </span>

            </a>


            <!-- LAPORAN -->

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

                <span>
                    Laporan
                </span>

            </a>


            <!-- MASTER -->

            <a
                href="{{ route('master.index') }}"
                class="menu-link
                    {{ request()->is('master*') ? 'active' : '' }}"
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
                            d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06-1.42 1.42-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21h-2v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06-1.42-1.42.06-.06A1.65 1.65 0 0 0 9.6 15a1.65 1.65 0 0 0-1.51-1H8v-2h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06 1.42-1.42.06.06A1.65 1.65 0 0 0 12.51 8a1.65 1.65 0 0 0 1-1.51V6h2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06 1.42 1.42-.06.06A1.65 1.65 0 0 0 19.4 10a1.65 1.65 0 0 0 1.51 1H21v2h-.09a1.65 1.65 0 0 0-1.51 1z"
                        ></path>

                    </svg>

                </span>

                <span>
                    Master
                </span>

            </a>


            <!-- KELUAR -->

            <a
                href="/logout"
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

                <span>
                    Keluar
                </span>

            </a>

        </nav>

    </aside>


    <!-- OVERLAY MOBILE -->

    <div
        class="sidebar-overlay"
        id="sidebarOverlay"
    ></div>


    <!-- =====================================================
         MAIN
    ====================================================== -->

    <div class="main">


        <!-- =================================================
             HEADER
        ================================================== -->

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


        <!-- =================================================
             CONTENT
        ================================================== -->

        <main class="content">


            <!-- PAGE HEADER -->

            <div class="page-header">

                <div class="page-kicker">
                    MASTER
                </div>

                <h1 class="page-title">
                    Master
                </h1>

                <p class="page-description">
                    Kelola data operator, verifikator, dan petugas.
                </p>

            </div>


            <!-- =================================================
                 TABS
            ================================================== -->

            <div class="master-tabs">


                <button
                    type="button"
                    class="master-tab active"
                    onclick="showSection('operator', this)"
                >

                    <div class="master-tab-title">
                        Operator
                    </div>

                    <div class="master-tab-desc">
                        Kelola data operator sistem.
                    </div>

                </button>


                <button
                    type="button"
                    class="master-tab"
                    onclick="showSection('verifikator', this)"
                >

                    <div class="master-tab-title">
                        Verifikator
                    </div>

                    <div class="master-tab-desc">
                        Kelola data verifikator.
                    </div>

                </button>


                <button
                    type="button"
                    class="master-tab"
                    onclick="showSection('petugas', this)"
                >

                    <div class="master-tab-title">
                        Petugas
                    </div>

                    <div class="master-tab-desc">
                        Melihat data petugas dan wilayah.
                    </div>

                </button>


            </div>


            <!-- =================================================
                 OPERATOR
            ================================================== -->

            <div
                class="master-section active"
                id="operator"
            >


                <!-- FORM -->

                <div class="data-card">

                    <div class="data-card-header">

                        <h3>
                            Tambah Operator
                        </h3>

                        <p>
                            Masukkan data lengkap operator.
                        </p>

                    </div>


                    <div class="form-body">

                        <form>

                            <div class="form-grid">


                                <div class="form-group">

                                    <label>
                                        Nama Lengkap
                                    </label>

                                    <input
                                        type="text"
                                        placeholder="Masukkan nama lengkap"
                                    >

                                </div>


                                <div class="form-group">

                                    <label>
                                        NIK
                                    </label>

                                    <input
                                        type="text"
                                        placeholder="Masukkan NIK"
                                    >

                                </div>


                                <div class="form-group">

                                    <label>
                                        Jenis Kelamin
                                    </label>

                                    <select>

                                        <option value="">
                                            Pilih jenis kelamin
                                        </option>

                                        <option value="L">
                                            Laki-laki
                                        </option>

                                        <option value="P">
                                            Perempuan
                                        </option>

                                    </select>

                                </div>


                                <div class="form-group">

                                    <label>
                                        Tempat Lahir
                                    </label>

                                    <input
                                        type="text"
                                        placeholder="Masukkan tempat lahir"
                                    >

                                </div>


                                <div class="form-group">

                                    <label>
                                        Tanggal Lahir
                                    </label>

                                    <input
                                        type="date"
                                    >

                                </div>


                                <div class="form-group">

                                    <label>
                                        No. HP
                                    </label>

                                    <input
                                        type="text"
                                        placeholder="Masukkan nomor HP"
                                    >

                                </div>


                                <div class="form-group">

                                    <label>
                                        Email
                                    </label>

                                    <input
                                        type="email"
                                        placeholder="Masukkan email"
                                    >

                                </div>


                                <div class="form-group full">

                                    <label>
                                        Alamat
                                    </label>

                                    <textarea
                                        placeholder="Masukkan alamat lengkap"
                                    ></textarea>

                                </div>


                            </div>


                            <div class="form-actions">

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
                                    Simpan Operator
                                </button>

                            </div>

                        </form>

                    </div>

                </div>


                <!-- TABLE -->

                <div class="data-card">

                    <div class="data-card-header">

                        <h3>
                            Data Operator
                        </h3>

                        <p>
                            Daftar operator yang terdaftar dalam sistem.
                        </p>

                    </div>


                    <div class="table-wrapper">

                        <table class="data-table">

                            <thead>

                                <tr>

                                    <th>
                                        No
                                    </th>

                                    <th>
                                        Nama Lengkap
                                    </th>

                                    <th>
                                        NIK
                                    </th>

                                    <th>
                                        Jenis Kelamin
                                    </th>

                                    <th>
                                        Tempat Lahir
                                    </th>

                                    <th>
                                        Tanggal Lahir
                                    </th>

                                    <th>
                                        No. HP
                                    </th>

                                    <th>
                                        Email
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

                                <tr>

                                    <td
                                        colspan="10"
                                        class="empty-row"
                                    >
                                        Belum ada data operator.
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 VERIFIKATOR
            ================================================== -->

            <div
                class="master-section"
                id="verifikator"
            >


                <!-- FORM -->

                <div class="data-card">

                    <div class="data-card-header">

                        <h3>
                            Tambah Verifikator
                        </h3>

                        <p>
                            Masukkan data lengkap verifikator.
                        </p>

                    </div>


                    <div class="form-body">

                        <form>

                            <div class="form-grid">


                                <div class="form-group">

                                    <label>
                                        Nama Lengkap
                                    </label>

                                    <input
                                        type="text"
                                        placeholder="Masukkan nama lengkap"
                                    >

                                </div>


                                <div class="form-group">

                                    <label>
                                        NIK
                                    </label>

                                    <input
                                        type="text"
                                        placeholder="Masukkan NIK"
                                    >

                                </div>


                                <div class="form-group">

                                    <label>
                                        Jenis Kelamin
                                    </label>

                                    <select>

                                        <option value="">
                                            Pilih jenis kelamin
                                        </option>

                                        <option value="L">
                                            Laki-laki
                                        </option>

                                        <option value="P">
                                            Perempuan
                                        </option>

                                    </select>

                                </div>


                                <div class="form-group">

                                    <label>
                                        Tempat Lahir
                                    </label>

                                    <input
                                        type="text"
                                        placeholder="Masukkan tempat lahir"
                                    >

                                </div>


                                <div class="form-group">

                                    <label>
                                        Tanggal Lahir
                                    </label>

                                    <input
                                        type="date"
                                    >

                                </div>


                                <div class="form-group">

                                    <label>
                                        No. HP
                                    </label>

                                    <input
                                        type="text"
                                        placeholder="Masukkan nomor HP"
                                    >

                                </div>


                                <div class="form-group">

                                    <label>
                                        Email
                                    </label>

                                    <input
                                        type="email"
                                        placeholder="Masukkan email"
                                    >

                                </div>


                                <div class="form-group full">

                                    <label>
                                        Alamat
                                    </label>

                                    <textarea
                                        placeholder="Masukkan alamat lengkap"
                                    ></textarea>

                                </div>


                            </div>


                            <div class="form-actions">

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
                                    Simpan Verifikator
                                </button>

                            </div>

                        </form>

                    </div>

                </div>


                <!-- TABLE -->

                <div class="data-card">

                    <div class="data-card-header">

                        <h3>
                            Data Verifikator
                        </h3>

                        <p>
                            Daftar verifikator yang terdaftar dalam sistem.
                        </p>

                    </div>


                    <div class="table-wrapper">

                        <table class="data-table">

                            <thead>

                                <tr>

                                    <th>
                                        No
                                    </th>

                                    <th>
                                        Nama Lengkap
                                    </th>

                                    <th>
                                        NIK
                                    </th>

                                    <th>
                                        Jenis Kelamin
                                    </th>

                                    <th>
                                        Tempat Lahir
                                    </th>

                                    <th>
                                        Tanggal Lahir
                                    </th>

                                    <th>
                                        No. HP
                                    </th>

                                    <th>
                                        Email
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

                                <tr>

                                    <td
                                        colspan="10"
                                        class="empty-row"
                                    >
                                        Belum ada data verifikator.
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 PETUGAS
            ================================================== -->

            <div
                class="master-section"
                id="petugas"
            >

                <div class="data-card">

                    <div class="data-card-header">

                        <h3>
                            Data Petugas
                        </h3>

                        <p>
                            Data petugas yang tersedia dalam sistem.
                        </p>

                    </div>


                    <div class="table-wrapper">

                        <table class="data-table">

                            <thead>

                                <tr>

                                    <th>
                                        No
                                    </th>

                                    <th>
                                        Nama Lengkap
                                    </th>

                                    <th>
                                        NIK
                                    </th>

                                    <th>
                                        Jenis Kelamin
                                    </th>

                                    <th>
                                        Tempat Lahir
                                    </th>

                                    <th>
                                        Tanggal Lahir
                                    </th>

                                    <th>
                                        No. HP
                                    </th>

                                    <th>
                                        Email
                                    </th>

                                    <th>
                                        Alamat
                                    </th>

                                    <th>
                                        Wilayah
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                <tr>

                                    <td
                                        colspan="10"
                                        class="empty-row"
                                    >
                                        Belum ada data petugas.
                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


        </main>

    </div>


    <!-- =====================================================
         JAVASCRIPT
    ====================================================== -->

    <script>


        /* =====================================================
           TAB MASTER
        ====================================================== */

        function showSection(
            sectionId,
            clickedTab
        ) {

            document
                .querySelectorAll(
                    '.master-section'
                )
                .forEach(function(section) {

                    section.classList.remove(
                        'active'
                    );

                });


            document
                .querySelectorAll(
                    '.master-tab'
                )
                .forEach(function(tab) {

                    tab.classList.remove(
                        'active'
                    );

                });


            document
                .getElementById(sectionId)
                .classList.add('active');


            clickedTab.classList.add(
                'active'
            );

        }


        /* =====================================================
           MOBILE SIDEBAR
        ====================================================== */

        const sidebar =
            document.getElementById(
                'sidebar'
            );


        const sidebarOverlay =
            document.getElementById(
                'sidebarOverlay'
            );


        const mobileMenuBtn =
            document.getElementById(
                'mobileMenuBtn'
            );


        function openSidebar() {

            sidebar.classList.add(
                'active'
            );

            sidebarOverlay.classList.add(
                'active'
            );

            mobileMenuBtn.setAttribute(
                'aria-expanded',
                'true'
            );

        }


        function closeSidebar() {

            sidebar.classList.remove(
                'active'
            );

            sidebarOverlay.classList.remove(
                'active'
            );

            mobileMenuBtn.setAttribute(
                'aria-expanded',
                'false'
            );

        }


        mobileMenuBtn.addEventListener(
            'click',
            function() {

                if (
                    sidebar.classList.contains(
                        'active'
                    )
                ) {

                    closeSidebar();

                } else {

                    openSidebar();

                }

            }
        );


        sidebarOverlay.addEventListener(
            'click',
            function() {

                closeSidebar();

            }
        );


        sidebar
            .querySelectorAll('a')
            .forEach(function(link) {

                link.addEventListener(
                    'click',
                    function() {

                        if (
                            window.innerWidth <= 900
                        ) {

                            closeSidebar();

                        }

                    }
                );

            });


        window.addEventListener(
            'resize',
            function() {

                if (
                    window.innerWidth > 900
                ) {

                    sidebar.classList.remove(
                        'active'
                    );

                    sidebarOverlay.classList.remove(
                        'active'
                    );

                    mobileMenuBtn.setAttribute(
                        'aria-expanded',
                        'false'
                    );

                }

            }
        );


    </script>

</body>

</html>