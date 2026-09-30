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


        /* =====================================================
           LOGO SIDEBAR
        ====================================================== */

        .sidebar-logo {

            height: 105px;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 16px 20px;

            border-bottom: 1px solid rgba(255, 255, 255, 0.12);

            flex-shrink: 0;
        }


        .sidebar-logo img {

            width: 72px;
            height: 72px;

            object-fit: contain;

            border-radius: 50%;

            background: #ffffff;

            display: block;
        }


        /* =====================================================
           MENU SIDEBAR
        ====================================================== */

        .sidebar-menu {

            padding: 20px 14px;

            flex: 1;
        }


        .menu-title {

            font-size: 11px;
            font-weight: 700;

            color: rgba(255, 255, 255, 0.55);

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

            color: rgba(255, 255, 255, 0.82);

            text-decoration: none;

            font-size: 14px;
            font-weight: 500;

            transition: all .2s ease;
        }


        .menu-link:hover {

            background: rgba(255, 255, 255, 0.10);

            color: #ffffff;
        }


        .menu-link.active {

            background: #ffffff;

            color: #252A86;

            font-weight: 700;

            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.10);
        }


        /* =====================================================
           ICON MENU
        ====================================================== */

        .menu-icon {

            width: 20px;
            height: 20px;

            display: flex;

            align-items: center;
            justify-content: center;

            flex-shrink: 0;
        }


        .menu-icon svg {

            width: 18px;
            height: 18px;

            display: block;
        }


        /* =====================================================
           MASTER DROPDOWN
        ====================================================== */

        .master-menu {
            width: 100%;
        }


        .master-toggle {

            border: none;

            background: transparent;

            cursor: pointer;

            font-family: inherit;

            text-align: left;

            appearance: none;
        }


        /* BAGIAN ICON + TEXT MASTER */

        .master-toggle-left {

            display: flex;

            align-items: center;

            gap: 12px;

            flex: 1;

            min-width: 0;
        }


        .master-toggle-left .menu-icon {

            width: 20px;
            height: 20px;

            display: flex;

            align-items: center;
            justify-content: center;

            flex-shrink: 0;
        }


        .master-toggle-left .menu-icon svg {

            width: 18px;
            height: 18px;

            display: block;
        }


        .master-toggle-left > span:last-child {

            display: flex;

            align-items: center;

            height: 20px;

            line-height: 20px;

            white-space: nowrap;
        }


        /* =====================================================
           PANAH MASTER
        ====================================================== */

        .master-toggle .master-arrow {

            margin-left: auto;

            width: 18px;
            height: 18px;

            display: flex;

            align-items: center;
            justify-content: center;

            flex-shrink: 0;

            transition: transform .25s ease;
        }


        .master-toggle.open .master-arrow {

            transform: rotate(180deg);
        }


        .master-toggle.master-active {

            background: rgba(255, 255, 255, 0.10);

            color: #ffffff;
        }


        .master-toggle.master-active:hover {

            background: rgba(255, 255, 255, 0.14);
        }


        /* =====================================================
           SUBMENU MASTER
        ====================================================== */

        .master-submenu {

            max-height: 0;

            overflow: hidden;

            opacity: 0;

            padding-left: 18px;

            transition:
                max-height .3s ease,
                opacity .2s ease;
        }


        .master-submenu.open {

            max-height: 200px;

            opacity: 1;
        }


        /* =====================================================
           LINK SUBMENU
        ====================================================== */

        .submenu-link {

            display: flex;

            align-items: center;

            gap: 12px;

            width: 100%;

            padding: 10px 14px;

            margin-bottom: 4px;

            border-radius: 8px;

            color: rgba(255, 255, 255, .72);

            text-decoration: none;

            font-size: 13px;

            font-weight: 500;

            transition: all .2s ease;
        }


        .submenu-link:hover {

            background: rgba(255, 255, 255, .10);

            color: #ffffff;
        }


        .submenu-link.active {

            background: #ffffff;

            color: #252A86;

            font-weight: 700;

            box-shadow: 0 3px 8px rgba(0, 0, 0, .08);
        }


        .submenu-icon {

            width: 18px;
            height: 18px;

            display: flex;

            align-items: center;
            justify-content: center;

            flex-shrink: 0;
        }


        .submenu-icon svg {

            width: 18px;
            height: 18px;

            display: block;
        }


        /* =====================================================
           SIDEBAR FOOTER
        ====================================================== */

        .sidebar-footer {

            padding: 15px 14px;

            border-top: 1px solid rgba(255, 255, 255, .12);

            flex-shrink: 0;
        }


        /* =====================================================
           LOGOUT
        ====================================================== */

        .logout-link {

            display: flex;

            align-items: center;

            gap: 12px;

            width: 100%;

            padding: 12px 14px;

            border-radius: 9px;

            color: rgba(255, 255, 255, .85);

            text-decoration: none;

            font-size: 14px;

            font-weight: 500;

            transition: all .2s ease;
        }


        .logout-link:hover {

            background: rgba(255, 255, 255, .10);

            color: #ffffff;
        }


        .logout-link .menu-icon {

            width: 20px;
            height: 20px;

            display: flex;

            align-items: center;
            justify-content: center;

            flex-shrink: 0;
        }


        .logout-link .menu-icon svg {

            width: 18px;
            height: 18px;

            display: block;
        }


        /* =====================================================
           MAIN
        ====================================================== */

        .main {

            margin-left: 260px;

            min-height: 100vh;

            width: calc(100% - 260px);
        }


        /* =====================================================
           HEADER
        ====================================================== */

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

            font-size: 16px;

            font-weight: 700;

            color: #252A86;

            white-space: nowrap;

            overflow: hidden;

            text-overflow: ellipsis;
        }


        .header-subtitle {

            margin-top: 4px;

            font-size: 12px;

            color: #9ca3af;
        }


        /* =====================================================
           PROFIL OPERATOR HEADER
        ====================================================== */

        .header-admin {

            display: flex;

            align-items: center;

            gap: 10px;

            flex-shrink: 0;

            cursor: pointer;

            text-decoration: none;

            color: inherit;

            padding: 6px 9px;

            border-radius: 10px;

            position: relative;

            z-index: 100;

            transition:
                background-color .25s ease,
                transform .25s ease,
                box-shadow .25s ease;
        }


        /* EFEK SAAT MOUSE DIARAHKAN */

        .header-admin:hover {

            background: #f3f4f6;

            transform: translateY(-1px);

            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
        }


        /* EFEK SAAT DITEKAN */

        .header-admin:active {

            transform: scale(.97);

            box-shadow: none;
        }


        .admin-text {

            text-align: right;
        }


        .admin-name {

            font-size: 12px;

            font-weight: 700;

            color: #1f2937;

            transition: color .2s ease;
        }


        .admin-role {

            margin-top: 2px;

            font-size: 10px;

            color: #9ca3af;

            transition: color .2s ease;
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

            flex-shrink: 0;

            transition:
                transform .25s ease,
                box-shadow .25s ease;
        }


        /* ANIMASI AVATAR */

        .header-admin:hover .admin-avatar {

            transform: scale(1.08);

            box-shadow:
                0 4px 10px rgba(37, 42, 134, .25);
        }


        /* WARNA TEKS SAAT HOVER */

        .header-admin:hover .admin-name {

            color: #252A86;
        }


        .header-admin:hover .admin-role {

            color: #6b7280;
        }


        /* =====================================================
           HAMBURGER
        ====================================================== */

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
        ====================================================== */

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
        ====================================================== */

        .content {

            padding: 30px;
        }


        .page-kicker {

            font-size: 11px;

            font-weight: 700;

            color: #252A86;

            letter-spacing: 1.2px;

            margin-bottom: 7px;
        }


        .page-title {

            font-size: 27px;

            font-weight: 700;

            color: #252A86;

            margin-bottom: 8px;
        }


        .page-description {

            font-size: 14px;

            color: #6b7280;

            line-height: 1.6;

            margin-bottom: 25px;
        }


        /* =====================================================
           WELCOME BANNER
        ====================================================== */

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
        ====================================================== */

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
        ====================================================== */

        .info-card {

            background: #ffffff;

            border: 1px solid #e5e7eb;

            border-radius: 9px;

            padding: 20px;
        }


        .info-title {

            font-size: 14px;

            font-weight: 700;

            color: #252A86;

            margin-bottom: 6px;
        }


        .info-text {

            font-size: 12px;

            color: #6b7280;

            line-height: 1.7;
        }


        /* =====================================================
           TABLET
        ====================================================== */

        @media (max-width: 1100px) {

            .stats-grid {

                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

        }


        /* =====================================================
           MOBILE
        ====================================================== */

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
        ====================================================== */

        @media (max-width: 600px) {

            .sidebar-logo {

                height: 100px;
            }


            .sidebar-logo img {

                width: 64px;
                height: 64px;
            }


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


            .header-admin {

                padding: 4px 6px;

                gap: 7px;

                border-radius: 9px;
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


            .master-submenu {

                padding-left: 14px;
            }

        }


        /* =====================================================
           VERY SMALL MOBILE
        ====================================================== */

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


            .master-submenu {

                padding-left: 10px;
            }

        }

    </style>

</head>


<body>


<!-- =====================================================
     SIDEBAR
===================================================== -->

<aside class="sidebar" id="sidebar">


    <!-- =================================================
         LOGO
    ================================================== -->

    <div class="sidebar-logo">

        <img
            src="{{ asset('images/dinsos.png') }}"
            alt="Logo Dinas Sosial"
        >

    </div>


    <!-- =================================================
         MENU
    ================================================== -->

    <nav class="sidebar-menu">


        <!-- MENU UTAMA -->

        <div class="menu-title">
            Menu Utama
        </div>


        <!-- =================================================
             DASHBOARD
        ================================================== -->

        <a
            href="{{ route('dashboard') }}"
            class="menu-link active"
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

            <span>
                Dashboard
            </span>

        </a>


        <!-- =================================================
             RESPONDEN
        ================================================== -->

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

            <span>
                Responden
            </span>

        </a>


        <!-- =================================================
             KUISIONER
        ================================================== -->

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


        <!-- =================================================
             VERIFIKASI
        ================================================== -->

        <a
            href="{{ route('verifikasi.index') }}"
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

                    <path d="M9 11l3 3L22 4"></path>

                    <path
                        d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"
                    ></path>

                </svg>

            </span>

            <span>
                Verifikasi
            </span>

        </a>


        <!-- =================================================
             MONITORING
        ================================================== -->

        <a
            href="{{ route('monitoring.index') }}"
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

                    <polyline
                        points="3 3 3 21 21 21"
                    ></polyline>

                    <polyline
                        points="7 16 11 12 14 15 21 8"
                    ></polyline>

                </svg>

            </span>

            <span>
                Monitoring
            </span>

        </a>


        <!-- =================================================
             LAPORAN
        ================================================== -->

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


        <!-- =================================================
             MASTER
        ================================================== -->

        <div
            class="master-menu"
            id="masterMenu"
        >


            <button
                type="button"
                class="menu-link master-toggle"
                id="masterToggle"
            >

                <span class="master-toggle-left">

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
                                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06-1.42 1.42-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21h-2v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06-1.42-1.42.06-.06A1.65 1.65 0 0 0 8.6 15a1.65 1.65 0 0 0-1.51-1H7v-2h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06 1.42-1.42.06.06A1.65 1.65 0 0 0 12.52 6H12V4h2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06 1.42 1.42-.06.06A1.65 1.65 0 0 0 18.6 9a1.65 1.65 0 0 0 1.51 1H20v2h-.09a1.65 1.65 0 0 0-1.51 1z"
                            ></path>

                        </svg>

                    </span>

                    <span>
                        Master
                    </span>

                </span>


                <span class="master-arrow">

                    <svg
                        width="16"
                        height="16"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >

                        <polyline
                            points="6 9 12 15 18 9"
                        ></polyline>

                    </svg>

                </span>

            </button>


            <!-- =================================================
                 SUBMENU MASTER
            ================================================== -->

            <div
                class="master-submenu"
                id="masterSubmenu"
            >


                <!-- PERIODE -->

                <a
                    href="{{ url('/periode') }}"
                    class="submenu-link"
                >

                    <span class="submenu-icon">
                        ▣
                    </span>

                    <span>
                        Periode
                    </span>

                </a>


                <!-- PENGGUNA -->

                <a
                    href="{{ route('master.index') }}"
                    class="submenu-link"
                >

                    <span class="submenu-icon">

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
                        Pengguna
                    </span>

                </a>


            </div>

        </div>


    </nav>


    <!-- =================================================
         LOGOUT
    ================================================== -->

    <div class="sidebar-footer">

        <a
            href="{{ route('login') }}"
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

                <span>
                    Keluar
                </span>

        </a>

    </div>


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


        <!-- =================================================
             PROFIL OPERATOR
        ================================================== -->

        <a
            href="{{ route('profil.index') }}"
            class="header-admin"
        >

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

        </a>


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
            Perlinsos Kota Pasuruan.
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
                    {{ $periodeAktif }}
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

    const sidebar =
        document.getElementById('sidebar');

    const hamburger =
        document.getElementById('hamburger');

    const overlay =
        document.getElementById('overlay');

    const masterMenu =
        document.getElementById('masterMenu');

    const masterToggle =
        document.getElementById('masterToggle');

    const masterSubmenu =
        document.getElementById('masterSubmenu');


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

        if (
            sidebar.classList.contains('show')
        ) {

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

        masterToggle.classList.toggle('open');

        masterSubmenu.classList.toggle('open');

    });


    /* =====================================================
       CLOSE SIDEBAR AFTER CLICK MENU
    ===================================================== */

    const menuLinks =
        sidebar.querySelectorAll('a');

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