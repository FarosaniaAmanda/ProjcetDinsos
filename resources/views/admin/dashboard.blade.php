<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Sistem Pendataan perlindungan Dinas Sosial</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            min-height: 100%;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f6fa;
            color: #1f2937;
        }

        body {
            overflow-x: hidden;
        }


        /* =====================================================
           SIDEBAR
        ===================================================== */

        .sidebar {

            position: fixed;

            top: 0;
            left: 0;

            width: 260px;
            height: 100vh;

            background: #252A86;

            z-index: 1000;

            overflow-y: auto;

            transition: transform 0.3s ease;
        }


        .sidebar-logo {

            height: 105px;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 15px;
        }


        .sidebar-logo img {

            max-width: 95px;

            max-height: 75px;

            object-fit: contain;
        }


        .sidebar-menu {

            padding: 10px 14px 25px;
        }


        .menu-link {

            display: flex;

            align-items: center;

            gap: 12px;

            width: 100%;

            padding: 12px 13px;

            margin-bottom: 4px;

            border-radius: 7px;

            color: rgba(255, 255, 255, 0.88);

            text-decoration: none;

            font-size: 13px;

            font-weight: 500;

            transition: all 0.2s ease;
        }


        .menu-link:hover {

            background: rgba(255, 255, 255, 0.10);

            color: #ffffff;
        }


        .menu-link.active {

            background: #ffffff;

            color: #252A86;

            font-weight: 700;
        }


        .menu-icon {

            width: 19px;

            min-width: 19px;

            text-align: center;

            font-size: 14px;

            line-height: 1;
        }


        /* =====================================================
           MASTER DROPDOWN
        ===================================================== */

        .master-menu {

            margin-bottom: 4px;
        }


        .master-toggle {

            display: flex;

            align-items: center;

            justify-content: space-between;

            width: 100%;

            padding: 12px 13px;

            border: none;

            border-radius: 7px;

            background: transparent;

            color: rgba(255, 255, 255, 0.88);

            font-family: inherit;

            font-size: 13px;

            font-weight: 500;

            cursor: pointer;

            transition: all 0.2s ease;
        }


        .master-toggle:hover {

            background: rgba(255, 255, 255, 0.10);

            color: #ffffff;
        }


        .master-toggle-left {

            display: flex;

            align-items: center;

            gap: 12px;
        }


        .master-arrow {

            font-size: 10px;

            transition: transform 0.2s ease;
        }


        .master-menu.open .master-arrow {

            transform: rotate(180deg);
        }


        /* =====================================================
           SUBMENU
        ===================================================== */

        .submenu {

            display: none;

            padding: 3px 0 5px 32px;
        }


        .master-menu.open .submenu {

            display: block;
        }


        .submenu a {

            display: flex;

            align-items: center;

            gap: 10px;

            padding: 10px 12px;

            margin-bottom: 2px;

            color: rgba(255, 255, 255, 0.82);

            text-decoration: none;

            font-size: 12px;

            font-weight: 600;

            border-radius: 6px;

            transition: all 0.2s ease;
        }


        .submenu a:hover {

            background: rgba(255, 255, 255, 0.10);

            color: #ffffff;
        }


        .submenu a.active {

            background: rgba(255, 255, 255, 0.16);

            color: #ffffff;

            font-weight: 700;
        }


        .submenu-icon {

            width: 18px;

            min-width: 18px;

            text-align: center;

            font-size: 13px;
        }


        /* =====================================================
           LOGOUT
        ===================================================== */

        .logout-link {

            margin-top: 12px;

            border-top: 1px solid rgba(255, 255, 255, 0.12);

            padding-top: 15px;
        }


        /* =====================================================
           MAIN
        ===================================================== */

        .main {

            margin-left: 260px;

            min-height: 100vh;

            width: calc(100% - 260px);
        }


        /* =====================================================
           HEADER
        ===================================================== */

        .header {

            height: 75px;

            background: #ffffff;

            border-bottom: 1px solid #e5e7eb;

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

            gap: 15px;

            min-width: 0;
        }


        .header-title {

            font-size: 15px;

            font-weight: 700;

            color: #1f2937;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;
        }


        .header-subtitle {

            margin-top: 4px;

            font-size: 11px;

            color: #9ca3af;
        }


        .header-admin {

            display: flex;

            align-items: center;

            gap: 10px;

            flex-shrink: 0;
        }


        .admin-text {

            text-align: right;
        }


        .admin-name {

            font-size: 12px;

            font-weight: 700;

            color: #1f2937;
        }


        .admin-role {

            margin-top: 2px;

            font-size: 10px;

            color: #9ca3af;
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

            font-size: 13px;

            font-weight: 700;
        }


        /* =====================================================
           HAMBURGER
        ===================================================== */

        .hamburger {

            display: none;

            width: 38px;

            height: 38px;

            border: 1px solid #e5e7eb;

            background: #ffffff;

            border-radius: 7px;

            cursor: pointer;

            align-items: center;

            justify-content: center;

            flex-direction: column;

            gap: 4px;

            flex-shrink: 0;
        }


        .hamburger span {

            display: block;

            width: 17px;

            height: 2px;

            background: #252A86;

            border-radius: 2px;
        }


        /* =====================================================
           OVERLAY
        ===================================================== */

        .overlay {

            display: none;

            position: fixed;

            inset: 0;

            background: rgba(0, 0, 0, 0.35);

            z-index: 950;
        }


        .overlay.show {

            display: block;
        }


        /* =====================================================
           CONTENT
        ===================================================== */

        .content {

            padding: 30px;
        }


        .page-kicker {

            font-size: 10px;

            font-weight: 700;

            color: #252A86;

            letter-spacing: 1.2px;

            margin-bottom: 7px;
        }


        .page-title {

            font-size: 26px;

            font-weight: 700;

            color: #111827;

            margin-bottom: 8px;
        }


        .page-description {

            font-size: 13px;

            color: #6b7280;

            line-height: 1.6;

            margin-bottom: 25px;
        }


        /* =====================================================
           WELCOME BANNER
        ===================================================== */

        .welcome-card {

            background: #252A86;

            border-radius: 10px;

            padding: 24px 25px;

            color: #ffffff;

            margin-bottom: 22px;

            position: relative;

            overflow: hidden;
        }


        .welcome-card::after {

            content: "";

            position: absolute;

            width: 180px;

            height: 180px;

            border-radius: 50%;

            background: rgba(255, 255, 255, 0.05);

            right: -55px;

            top: -70px;
        }


        .welcome-title {

            font-size: 18px;

            font-weight: 700;

            margin-bottom: 7px;

            position: relative;

            z-index: 2;
        }


        .welcome-text {

            font-size: 12px;

            color: rgba(255, 255, 255, 0.78);

            line-height: 1.6;

            position: relative;

            z-index: 2;
        }


        /* =====================================================
           STATISTICS
        ===================================================== */

        .stats-grid {

            display: grid;

            grid-template-columns: repeat(4, minmax(0, 1fr));

            gap: 16px;

            margin-bottom: 22px;
        }


        .stat-card {

            background: #ffffff;

            border: 1px solid #e5e7eb;

            border-radius: 9px;

            padding: 18px;

            min-width: 0;
        }


        .stat-label {

            font-size: 11px;

            color: #6b7280;

            margin-bottom: 9px;
        }


        .stat-value {

            font-size: 24px;

            font-weight: 700;

            color: #252A86;

            line-height: 1;
        }


        .stat-description {

            margin-top: 8px;

            font-size: 10px;

            color: #9ca3af;
        }


        /* =====================================================
           INFORMATION CARD
        ===================================================== */

        .info-card {

            background: #ffffff;

            border: 1px solid #e5e7eb;

            border-radius: 9px;

            padding: 20px;
        }


        .info-title {

            font-size: 14px;

            font-weight: 700;

            color: #1f2937;

            margin-bottom: 6px;
        }


        .info-text {

            font-size: 12px;

            color: #6b7280;

            line-height: 1.7;
        }


        /* =====================================================
           TABLET
        ===================================================== */

        @media (max-width: 1100px) {

            .stats-grid {

                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

        }


        /* =====================================================
           MOBILE
        ===================================================== */

        @media (max-width: 900px) {

            .sidebar {

                width: 270px;

                transform: translateX(-100%);
            }


            .sidebar.show {

                transform: translateX(0);
            }


            .main {

                margin-left: 0;

                width: 100%;
            }


            .header {

                height: 70px;

                padding: 0 20px;
            }


            .hamburger {

                display: flex;
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
           SMALL MOBILE
        ===================================================== */

        @media (max-width: 600px) {

            .header {

                height: 64px;

                padding: 0 14px;

                gap: 10px;
            }


            .header-left {

                gap: 10px;

                min-width: 0;
            }


            .hamburger {

                width: 38px;

                height: 38px;
            }


            .header-title {

                font-size: 13px;

                max-width: 190px;
            }


            .header-admin .admin-text {

                display: none;
            }


            .admin-avatar {

                width: 34px;

                height: 34px;

                font-size: 12px;
            }


            .content {

                padding: 16px;
            }


            .page-title {

                font-size: 22px;
            }


            .page-description {

                font-size: 12px;

                margin-bottom: 20px;
            }


            .welcome-card {

                padding: 20px;
            }


            .welcome-title {

                font-size: 16px;
            }


            .stats-grid {

                grid-template-columns: 1fr;

                gap: 12px;
            }


            .stat-card {

                padding: 16px;
            }

        }


        /* =====================================================
           VERY SMALL MOBILE
        ===================================================== */

        @media (max-width: 400px) {

            .sidebar {

                width: 250px;
            }


            .content {

                padding: 12px;
            }


            .page-title {

                font-size: 20px;
            }


            .header-title {

                max-width: 150px;

                font-size: 12px;
            }

        }

    </style>

</head>


<body>


<!-- =====================================================
     SIDEBAR
===================================================== -->

<aside class="sidebar" id="sidebar">

    <div class="sidebar-logo">

        <img
            src="{{ asset('images/dinsos.png') }}"
            alt="Logo Dinas Sosial"
        >

    </div>


    <nav class="sidebar-menu">


        <!-- DASHBOARD -->

        <a
            href="{{ route('dashboard') }}"
            class="menu-link active"
        >

            <span class="menu-icon">
                ▣
            </span>

            <span>
                Dashboard
            </span>

        </a>


        <!-- RESPONDEN -->

        <a
            href="#"
            class="menu-link"
        >

            <span class="menu-icon">
                ♙
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
                ☷
            </span>

            <span>
                Kuisioner
            </span>

        </a>


        <!-- VERIFIKASI -->

        <a
            href="{{ route('verifikasi.index') }}"
            class="menu-link"
        >

            <span class="menu-icon">
                ✓
            </span>

            <span>
                Verifikasi
            </span>

        </a>


        <!-- MONITORING -->

        <a
            href="{{ route('monitoring.index') }}"
            class="menu-link"
        >

            <span class="menu-icon">
                ◉
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
                ▤
            </span>

            <span>
                Laporan
            </span>

        </a>


        <!-- =================================================
             MASTER
        ================================================== -->

        <div
            class="master-menu"
            id="masterMenu"
        >

            <button
                type="button"
                class="master-toggle"
                id="masterToggle"
            >

                <span class="master-toggle-left">

                    <span class="menu-icon">
                        ⚙
                    </span>

                    <span>
                        Master
                    </span>

                </span>

                <span class="master-arrow">
                    ▼
                </span>

            </button>


            <div class="submenu">


                <!-- PERIODE -->

                <a href="{{ url('/periode') }}">

                    <span class="submenu-icon">
                        ◷
                    </span>

                    <span>
                        Periode
                    </span>

                </a>


                <!-- PENGGUNA -->

                <a href="{{ route('master.index') }}">

                    <span class="submenu-icon">
                        ♙
                    </span>

                    <span>
                        Pengguna
                    </span>

                </a>


            </div>

        </div>


        <!-- LOGOUT -->

        <div class="logout-link">

            <a
                href="{{ route('login') }}"
                class="menu-link"
            >

                <span class="menu-icon">
                    ↪
                </span>

                <span>
                    Keluar
                </span>

            </a>

        </div>


    </nav>

</aside>


<!-- =====================================================
     OVERLAY
===================================================== -->

<div
    class="overlay"
    id="overlay"
></div>


<!-- =====================================================
     MAIN
===================================================== -->

<main class="main">


    <!-- =================================================
         HEADER
    ================================================== -->

    <header class="header">


        <div class="header-left">


            <button
                type="button"
                class="hamburger"
                id="hamburger"
                aria-label="Buka menu"
            >

                <span></span>
                <span></span>
                <span></span>

            </button>


            <div>

                <div class="header-title">
                    Sistem Pendataan Perlinsos Kota Pasuruan
                </div>

                <div class="header-subtitle">
                    Panel Administrasi
                </div>

            </div>


        </div>


        <div class="header-admin">


            <div class="admin-text">

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

    <section class="content">


        <div class="page-kicker">
            ADMIN
        </div>


        <h1 class="page-title">
            Dashboard
        </h1>


        <p class="page-description">
            Selamat datang di panel administrasi Sistem Pendataan
            Dinas Sosial Kota Pasuruan.
        </p>


        <!-- =================================================
             WELCOME
        ================================================== -->

        <div class="welcome-card">

            <div class="welcome-title">
                Selamat Datang, Operator
            </div>

            <div class="welcome-text">
                Kelola data pendataan sosial, verifikasi,
                monitoring, dan laporan melalui sistem ini.
            </div>

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
                    128
                </div>

                <div class="stat-description">
                    Data responden terdaftar
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-label">
                    Periode Aktif
                </div>

                <div class="stat-value">
                    1
                </div>

                <div class="stat-description">
                    Periode pendataan berjalan
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-label">
                    Data Masuk Hari Ini
                </div>

                <div class="stat-value">
                    12
                </div>

                <div class="stat-description">
                    Data baru hari ini
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-label">
                    Data Terverifikasi
                </div>

                <div class="stat-value">
                    96
                </div>

                <div class="stat-description">
                    Data telah diverifikasi
                </div>

            </div>


        </div>


        <!-- =================================================
             INFORMATION
        ================================================== -->

        <div class="info-card">

            <div class="info-title">
                Informasi Sistem
            </div>

            <div class="info-text">
                Gunakan menu pada sidebar untuk mengelola
                responden, kuisioner, proses verifikasi,
                monitoring, laporan, serta data master sistem.
            </div>

        </div>


    </section>


</main>


<!-- =====================================================
     JAVASCRIPT
===================================================== -->

<script>

    const sidebar = document.getElementById('sidebar');

    const hamburger = document.getElementById('hamburger');

    const overlay = document.getElementById('overlay');

    const masterMenu = document.getElementById('masterMenu');

    const masterToggle = document.getElementById('masterToggle');


    /* =====================================================
       SIDEBAR MOBILE
    ===================================================== */

    function openSidebar() {

        sidebar.classList.add('show');

        overlay.classList.add('show');

    }


    function closeSidebar() {

        sidebar.classList.remove('show');

        overlay.classList.remove('show');

    }


    hamburger.addEventListener('click', function () {

        if (sidebar.classList.contains('show')) {

            closeSidebar();

        } else {

            openSidebar();

        }

    });


    overlay.addEventListener('click', function () {

        closeSidebar();

    });


    /* =====================================================
       MASTER DROPDOWN
    ===================================================== */

    masterToggle.addEventListener('click', function () {

        masterMenu.classList.toggle('open');

    });


    /* =====================================================
       CLOSE SIDEBAR AFTER CLICK MENU
    ===================================================== */

    const menuLinks = sidebar.querySelectorAll('a');

    menuLinks.forEach(function (link) {

        link.addEventListener('click', function () {

            if (window.innerWidth <= 900) {

                closeSidebar();

            }

        });

    });


    /* =====================================================
       RESET SIDEBAR SAAT DESKTOP
    ===================================================== */

    window.addEventListener('resize', function () {

        if (window.innerWidth > 900) {

            closeSidebar();

        }

    });

</script>


</body>

</html>