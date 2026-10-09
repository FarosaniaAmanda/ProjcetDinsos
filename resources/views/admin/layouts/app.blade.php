<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'Sistem Pendataan Dinas Sosial Kota Pasuruan')
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

        html {
            width: 100%;
            min-height: 100%;
            scroll-behavior: smooth;
        }

        body {
            width: 100%;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f6fa;
            color: #252525;
            overflow-x: hidden;
        }

        button,
        input,
        select,
        textarea {
            font-family: inherit;
        }

        button {
            -webkit-tap-highlight-color: transparent;
        }

        a {
            -webkit-tap-highlight-color: transparent;
        }


        /* =====================================================
           SIDEBAR
        ====================================================== */

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;

            width: 260px;
            min-width: 260px;
            max-width: 260px;

            height: 100vh;
            height: 100dvh;

            background: #252A86;
            color: #ffffff;

            display: flex;
            flex-direction: column;

            z-index: 1100;

            overflow-x: hidden;
            overflow-y: auto;

            scrollbar-width: thin;

            transform: translateX(0);

            transition:
                transform .3s ease,
                box-shadow .3s ease;
        }


        /* =====================================================
           SIDEBAR LOGO
        ====================================================== */

        .sidebar-logo {
            height: 105px;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 16px 20px;

            border-bottom: 1px solid rgba(255,255,255,.12);

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
           SIDEBAR MENU
        ====================================================== */

        .sidebar-menu {
            padding: 20px 14px;

            flex: 1 1 auto;

            width: 100%;
            min-width: 0;

            overflow: visible;
        }

        .menu-title {
            font-size: 11px;
            font-weight: 700;

            color: rgba(255,255,255,.55);

            text-transform: uppercase;

            letter-spacing: 1px;

            margin: 8px 10px 12px;
        }


        /* =====================================================
           MENU UTAMA
        ====================================================== */

        .menu-link {
            display: flex;
            align-items: center;

            gap: 12px;

            width: 100%;
            min-width: 0;
            height: 48px;

            padding: 0 14px;

            margin-bottom: 5px;

            border-radius: 9px;

            color: rgba(255,255,255,.82);

            text-decoration: none;

            font-size: 14px;
            font-weight: 500;

            transition:
                background .2s ease,
                color .2s ease,
                box-shadow .2s ease,
                transform .15s ease;
        }

        .menu-link:hover {
            background: rgba(255,255,255,.10);
            color: #ffffff;
        }

        .menu-link:active {
            transform: scale(.985);
        }

        .menu-link.active {
            background: #ffffff;
            color: #252A86;

            font-weight: 700;

            box-shadow:
                0 4px 12px rgba(0,0,0,.10);
        }

        .menu-link.active:hover {
            background: #ffffff;
            color: #252A86;
        }


        /* =====================================================
           ICON MENU
        ====================================================== */

        .menu-icon {
            width: 20px;
            min-width: 20px;
            height: 20px;

            display: flex;
            align-items: center;
            justify-content: center;

            flex-shrink: 0;
        }

        .menu-link > span:last-child {
            min-width: 0;
            overflow: hidden;

            white-space: nowrap;
            text-overflow: ellipsis;
        }


        /* =====================================================
           MASTER BUTTON
        ====================================================== */

        .master-toggle {
            border: none;
            outline: none;

            background: transparent;

            cursor: pointer;

            font-family: inherit;

            text-align: left;

            appearance: none;

            user-select: none;
        }


        /* =====================================================
           MASTER ARROW
        ====================================================== */

        .master-arrow {
            margin-left: auto;

            width: 18px;
            min-width: 18px;
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


        /* =====================================================
           MASTER ACTIVE
        ====================================================== */

        .master-toggle.active {
            background: #ffffff;
            color: #252A86;

            font-weight: 700;

            box-shadow:
                0 4px 12px rgba(0,0,0,.10);
        }

        .master-toggle.active:hover {
            background: #ffffff;
            color: #252A86;
        }


        /* =====================================================
           MASTER SUBMENU
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
           SUBMENU
        ====================================================== */

        .submenu-link {
            display: flex;
            align-items: center;

            gap: 12px;

            width: 100%;
            height: 42px;

            padding: 0 14px;

            margin-bottom: 4px;

            border-radius: 8px;

            color: rgba(255,255,255,.72);

            text-decoration: none;

            font-size: 13px;
            font-weight: 500;

            transition:
                background .2s ease,
                color .2s ease,
                box-shadow .2s ease,
                transform .15s ease;
        }

        .submenu-link:hover {
            background: rgba(255,255,255,.10);
            color: #ffffff;
        }

        .submenu-link:active {
            transform: scale(.985);
        }

        .submenu-link.active {
            background: #ffffff;
            color: #252A86;

            font-weight: 700;

            box-shadow:
                0 3px 8px rgba(0,0,0,.08);
        }

        .submenu-link.active:hover {
            background: #ffffff;
            color: #252A86;
        }

        .submenu-icon {
            width: 18px;
            min-width: 18px;
            height: 18px;

            display: flex;
            align-items: center;
            justify-content: center;

            flex-shrink: 0;
        }


        /* =====================================================
        SIDEBAR FOOTER / LOGOUT
        ====================================================== */

        .sidebar-footer {
            padding: 15px 14px;
            border-top: 1px solid rgba(255,255,255,.12);
            flex-shrink: 0;
        }

        .logout-form {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .logout-link {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            min-width: 0;
            height: 48px;
            padding: 0 14px;
            margin: 0;
            border: none;
            outline: none;
            border-radius: 9px;

            background: transparent;
            color: rgba(255,255,255,.82);

            text-decoration: none;
            font-family: inherit;
            font-size: 14px;
            font-weight: 500;

            text-align: left;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;

            transition:
                background .2s ease,
                color .2s ease,
                box-shadow .2s ease,
                transform .15s ease;
        }

        .logout-link:hover {
            background: rgba(255,255,255,.10);
            color: #ffffff;
        }

        .logout-link:active {
            transform: scale(.985);
        }

        .logout-link:focus {
            outline: none;
            box-shadow: none;
        }

        .logout-link .menu-icon {
            width: 20px;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .logout-link > span:last-child {
            min-width: 0;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }


        /* =====================================================
           MAIN
        ====================================================== */

        .main {
            margin-left: 260px;

            min-height: 100vh;
            min-height: 100dvh;

            width: calc(100% - 260px);

            display: flex;
            flex-direction: column;

            min-width: 0;
        }


        /* =====================================================
           HEADER / TOP BAR
        ====================================================== */

        .header {
            position: sticky;

            top: 0;

            z-index: 900;

            width: 100%;

            height: 75px;
            min-height: 75px;

            background: #ffffff;

            border-bottom: 1px solid #e8e9ef;

            display: flex;
            align-items: center;

            gap: 16px;

            padding: 0 30px;

            box-shadow:
                0 1px 4px rgba(0,0,0,.03);
        }


        /* =====================================================
           HEADER LEFT
        ====================================================== */

        .header-left {
            display: flex;
            align-items: center;

            gap: 14px;

            flex: 1 1 auto;

            min-width: 0;

            overflow: hidden;
        }

        .header-left > div {
            min-width: 0;
            max-width: 100%;
        }

        .header-title {
            font-size: 16px;
            font-weight: 700;

            line-height: 1.3;

            color: #252A86;

            white-space: nowrap;

            overflow: hidden;
            text-overflow: ellipsis;
        }

        .header-subtitle {
            font-size: 12px;

            color: #777;

            margin-top: 3px;

            white-space: nowrap;

            overflow: hidden;
            text-overflow: ellipsis;
        }


        /* =====================================================
           ADMIN PROFILE
        ====================================================== */

        .admin-profile {
            display: flex;
            align-items: center;

            gap: 10px;

            flex: 0 0 auto;

            min-width: max-content;

            position: relative;

            z-index: 2;
        }

        .admin-info {
            text-align: right;

            min-width: 0;

            white-space: nowrap;
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
            width: 40px;
            min-width: 40px;

            height: 40px;
            min-height: 40px;

            border-radius: 50%;

            background: #252A86;

            color: #ffffff;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 14px;

            font-weight: 700;

            flex-shrink: 0;
        }


        /* =====================================================
           MOBILE MENU BUTTON
        ====================================================== */

        .mobile-menu-btn {
            display: none;

            width: 42px;
            min-width: 42px;

            height: 42px;
            min-height: 42px;

            border: none;

            border-radius: 8px;

            background: #f1f2f8;

            cursor: pointer;

            align-items: center;
            justify-content: center;

            flex-direction: column;

            gap: 5px;

            flex-shrink: 0;

            transition:
                background .2s ease,
                transform .15s ease;
        }

        .mobile-menu-btn:hover {
            background: #e7e8f2;
        }

        .mobile-menu-btn:active {
            transform: scale(.95);
        }

        .mobile-menu-btn span {
            display: block;

            width: 20px;
            height: 2px;

            background: #252A86;

            border-radius: 2px;

            transition:
                transform .25s ease,
                opacity .2s ease;
        }

        .mobile-menu-btn.active span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .mobile-menu-btn.active span:nth-child(2) {
            opacity: 0;
        }

        .mobile-menu-btn.active span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }


        /* =====================================================
           SIDEBAR OVERLAY
        ====================================================== */

        .sidebar-overlay {
            display: none;

            position: fixed;

            inset: 0;

            background: rgba(0,0,0,.40);

            opacity: 0;

            visibility: hidden;

            transition:
                opacity .3s ease,
                visibility .3s ease;

            z-index: 1050;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }


        /* =====================================================
           CONTENT
        ====================================================== */

        .content {
            width: 100%;

            padding: 30px;

            flex: 1 1 auto;

            min-width: 0;

            overflow: visible;
        }


        /* =====================================================
           TABLET
        ====================================================== */

        @media (max-width: 1100px) {

            .header {
                padding-left: 24px;
                padding-right: 24px;
            }

            .content {
                padding: 24px;
            }

            .header-title {
                font-size: 15px;
            }
        }


        /* =====================================================
           TABLET / MOBILE
        ====================================================== */

        @media (max-width: 900px) {

            .sidebar {
                width: 270px;
                min-width: 270px;
                max-width: 270px;

                transform: translateX(-100%);

                box-shadow:
                    8px 0 30px rgba(0,0,0,.18);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar-overlay {
                display: block;
            }

            .main {
                margin-left: 0;

                width: 100%;
            }

            .header {
                height: 70px;
                min-height: 70px;

                padding: 0 20px;

                gap: 12px;
            }

            .mobile-menu-btn {
                display: flex;
            }

            .header-left {
                gap: 10px;
            }

            .header-title {
                font-size: 15px;
            }

            .header-subtitle {
                display: none;
            }

            .admin-info {
                display: block;
            }

            .admin-avatar {
                width: 38px;
                min-width: 38px;

                height: 38px;
                min-height: 38px;
            }

            .content {
                padding: 20px;
            }
        }


        /* =====================================================
           MOBILE
        ====================================================== */

        @media (max-width: 600px) {

            .sidebar {
                width: 270px;
                min-width: 270px;
                max-width: 270px;
            }

            .sidebar-logo {
                height: 100px;
            }

            .sidebar-logo img {
                width: 64px;
                height: 64px;
            }

            .header {
                height: 64px;
                min-height: 64px;

                padding: 0 14px;

                gap: 9px;
            }

            .mobile-menu-btn {
                width: 38px;
                min-width: 38px;

                height: 38px;
                min-height: 38px;
            }

            .mobile-menu-btn span {
                width: 18px;
            }

            .header-left {
                flex: 1 1 auto;

                min-width: 0;

                overflow: hidden;
            }

            .header-title {
                width: 100%;

                font-size: 13px;

                line-height: 1.3;

                white-space: nowrap;

                overflow: hidden;
                text-overflow: ellipsis;
            }

            .header-subtitle {
                display: none;
            }

            .admin-profile {
                gap: 0;

                flex: 0 0 auto;
            }

            .admin-info {
                display: none;
            }

            .admin-avatar {
                width: 34px;
                min-width: 34px;

                height: 34px;
                min-height: 34px;

                font-size: 13px;
            }

            .content {
                padding: 16px;

                width: 100%;
            }

            .master-submenu {
                padding-left: 14px;
            }
        }


        /* =====================================================
           SMALL MOBILE
        ====================================================== */

        @media (max-width: 400px) {

            .sidebar {
                width: 250px;
                min-width: 250px;
                max-width: 250px;
            }

            .sidebar-logo {
                height: 95px;
            }

            .sidebar-logo img {
                width: 60px;
                height: 60px;
            }

            .header {
                height: 60px;
                min-height: 60px;

                padding: 0 10px;

                gap: 7px;
            }

            .mobile-menu-btn {
                width: 36px;
                min-width: 36px;

                height: 36px;
                min-height: 36px;
            }

            .mobile-menu-btn span {
                width: 17px;
            }

            .header-title {
                font-size: 12px;

                max-width: 100%;
            }

            .admin-avatar {
                width: 32px;
                min-width: 32px;

                height: 32px;
                min-height: 32px;

                font-size: 12px;
            }

            .content {
                padding: 12px;
            }

            .master-submenu {
                padding-left: 10px;
            }
        }


        /* =====================================================
           EXTRA SMALL MOBILE
        ====================================================== */

        @media (max-width: 360px) {

            .header {
                padding: 0 8px;

                gap: 6px;
            }

            .mobile-menu-btn {
                width: 34px;
                min-width: 34px;

                height: 34px;
                min-height: 34px;
            }

            .mobile-menu-btn span {
                width: 16px;
            }

            .header-title {
                font-size: 11.5px;
            }

            .admin-avatar {
                width: 30px;
                min-width: 30px;

                height: 30px;
                min-height: 30px;

                font-size: 11px;
            }

            .content {
                padding: 10px;
            }
        }


        /* =====================================================
           REDUCE MOTION
        ====================================================== */

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;

                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
            }
        }

    </style>

    @stack('styles')

</head>


<body>


    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside
        class="sidebar"
        id="sidebar"
    >

        <!-- LOGO -->

        <div class="sidebar-logo">

            <img
                src="{{ asset('images/dinsos.png') }}"
                alt="Logo Dinas Sosial"
            >

        </div>


        <!-- MENU -->

        <nav class="sidebar-menu">

            <div class="menu-title">
                Menu Utama
            </div>


            <!-- =================================================
                 DASHBOARD
            ================================================== -->

            <a
                href="{{ route('dashboard') }}"
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


            <!-- =================================================
                 RESPONDEN
            ================================================== -->

            <a
                href="{{ route('responden.index') }}"
                class="menu-link {{ request()->is('responden*') ? 'active' : '' }}"
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
                href="{{ route('periode.pilih', ['tujuan' => 'kuisioner.index']) }}"
                class="menu-link {{ request()->is('kuisioner*') ? 'active' : '' }}"
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
                href="{{ route('periode.pilih', ['tujuan' => 'verifikasi.index']) }}"
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

                <span>
                    Verifikasi
                </span>

            </a>


            <!-- =================================================
                 MONITORING
            ================================================== -->

            <a
                href="{{ route('periode.pilih', ['tujuan' => 'monitoring.index']) }}"
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
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >

                        <rect x="2.5" y="3" width="15" height="11" rx="1.5"></rect>

                        <path d="M7 18h6"></path>

                        <path d="M10 14v4"></path>

                        <circle cx="17" cy="16" r="3.5"></circle>

                        <path d="m19.5 18.5 2 2"></path>

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
                href="{{ route('periode.pilih', ['tujuan' => 'laporan.index']) }}"
                class="menu-link {{ request()->is('laporan*') ? 'active' : '' }}"
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

            @php

                $isMasterPage =
                    request()->is('master*');

                $isPeriodePage =
                    request()->is('periode*');

                $masterOpen =
                    $isMasterPage ||
                    $isPeriodePage;

            @endphp


            <button
                type="button"
                class="menu-link master-toggle {{ $masterOpen ? 'open' : '' }}"
                id="masterToggle"
                aria-expanded="{{ $masterOpen ? 'true' : 'false' }}"
                aria-controls="masterSubmenu"
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
                            d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06-1.42 1.42-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21h-2v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06-1.42-1.42.06-.06A1.65 1.65 0 0 0 8.6 15a1.65 1.65 0 0 0-1.51-1H7v-2h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06 1.42-1.42.06.06A1.65 1.65 0 0 0 12.52 6H12V4h2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06 1.42 1.42-.06.06A1.65 1.65 0 0 0 18.6 9a1.65 1.65 0 0 0 1.51 1H20v2h-.09a1.65 1.65 0 0 0-1.51 1z"
                        ></path>

                    </svg>

                </span>


                <span>
                    Master
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
                class="master-submenu {{ $masterOpen ? 'open' : '' }}"
                id="masterSubmenu"
            >

                <!-- PERIODE -->

                <a
                    href="{{ route('periode.index') }}"
                    class="submenu-link {{ $isPeriodePage ? 'active' : '' }}"
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


                <!-- PENGGUNA -->

                <a
                    href="{{ route('master.index') }}"
                    class="submenu-link {{ $isMasterPage ? 'active' : '' }}"
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

        </nav>


        <!-- =================================================
             LOGOUT
        ================================================== -->

        <div class="sidebar-footer">

            <form
                action="{{ route('logout') }}"
                method="POST"
                class="logout-form"
            >
                @csrf

                <button
                    type="submit"
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

                </button>

            </form>

        </div>

    </aside>


    <!-- =====================================================
         MOBILE OVERLAY
    ====================================================== -->

    <div
        class="sidebar-overlay"
        id="sidebarOverlay"
    ></div>


    <!-- =====================================================
         MAIN
    ====================================================== -->

    <div class="main">


        <!-- =================================================
             HEADER / TOP BAR
        ================================================== -->

        <header class="header">


            <!-- HAMBURGER -->

            <button
                class="mobile-menu-btn"
                id="mobileMenuBtn"
                type="button"
                aria-label="Buka menu"
                aria-expanded="false"
                aria-controls="sidebar"
            >

                <span></span>
                <span></span>
                <span></span>

            </button>


            <!-- HEADER LEFT -->

            <div class="header-left">

                <div>

                    <div class="header-title">
                        Sistem Pendataan Perlindungan Dinas Sosial Kota Pasuruan
                    </div>

                    <div class="header-subtitle">
                        Panel Administrasi
                    </div>

                </div>

            </div>


            <!-- ADMIN PROFILE -->

            <div class="admin-profile">

                <div class="admin-info">

                    <div class="admin-name">
                        Operator
                    </div>

                    <div class="admin-role">
                        Admin
                    </div>

                </div>


                <div
                    class="admin-avatar"
                    title="Operator - Admin"
                >
                    A
                </div>

            </div>

        </header>


        <!-- =================================================
             CONTENT
        ================================================== -->

        <main class="content">

            @yield('content')

        </main>

    </div>


    <!-- =====================================================
         JAVASCRIPT
    ====================================================== -->

    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const mobileMenuBtn =
                    document.getElementById(
                        'mobileMenuBtn'
                    );

                const sidebar =
                    document.getElementById(
                        'sidebar'
                    );

                const sidebarOverlay =
                    document.getElementById(
                        'sidebarOverlay'
                    );

                const masterToggle =
                    document.getElementById(
                        'masterToggle'
                    );

                const masterSubmenu =
                    document.getElementById(
                        'masterSubmenu'
                    );


                /* =================================================
                   SIDEBAR MOBILE
                ================================================== */

                function openSidebar() {

                    if (!sidebar) return;

                    sidebar.classList.add('active');

                    if (sidebarOverlay) {
                        sidebarOverlay.classList.add('active');
                    }

                    if (mobileMenuBtn) {

                        mobileMenuBtn.classList.add('active');

                        mobileMenuBtn.setAttribute(
                            'aria-expanded',
                            'true'
                        );

                        mobileMenuBtn.setAttribute(
                            'aria-label',
                            'Tutup menu'
                        );

                    }

                    document.body.style.overflow = 'hidden';
                }


                function closeSidebar() {

                    if (!sidebar) return;

                    sidebar.classList.remove('active');

                    if (sidebarOverlay) {
                        sidebarOverlay.classList.remove('active');
                    }

                    if (mobileMenuBtn) {

                        mobileMenuBtn.classList.remove('active');

                        mobileMenuBtn.setAttribute(
                            'aria-expanded',
                            'false'
                        );

                        mobileMenuBtn.setAttribute(
                            'aria-label',
                            'Buka menu'
                        );

                    }

                    document.body.style.overflow = '';
                }


                function toggleSidebar() {

                    if (!sidebar) return;

                    if (
                        sidebar.classList.contains('active')
                    ) {

                        closeSidebar();

                    } else {

                        openSidebar();

                    }
                }


                if (mobileMenuBtn) {

                    mobileMenuBtn.addEventListener(
                        'click',
                        toggleSidebar
                    );

                }


                if (sidebarOverlay) {

                    sidebarOverlay.addEventListener(
                        'click',
                        closeSidebar
                    );

                }


                /* =================================================
                   MASTER DROPDOWN
                ================================================== */

                if (
                    masterToggle &&
                    masterSubmenu
                ) {

                    masterToggle.addEventListener(
                        'click',
                        function (event) {

                            event.preventDefault();

                            const isOpen =
                                masterSubmenu.classList.contains(
                                    'open'
                                );


                            if (isOpen) {

                                masterSubmenu.classList.remove(
                                    'open'
                                );

                                masterToggle.classList.remove(
                                    'open'
                                );

                                masterToggle.classList.remove(
                                    'active'
                                );

                                masterToggle.setAttribute(
                                    'aria-expanded',
                                    'false'
                                );

                            } else {

                                document
                                    .querySelectorAll(
                                        '.sidebar .menu-link.active'
                                    )
                                    .forEach(
                                        function (item) {

                                            item.classList.remove(
                                                'active'
                                            );

                                        }
                                    );


                                document
                                    .querySelectorAll(
                                        '.sidebar .submenu-link.active'
                                    )
                                    .forEach(
                                        function (item) {

                                            item.classList.remove(
                                                'active'
                                            );

                                        }
                                    );


                                masterToggle.classList.add(
                                    'active'
                                );

                                masterToggle.classList.add(
                                    'open'
                                );

                                masterSubmenu.classList.add(
                                    'open'
                                );

                                masterToggle.setAttribute(
                                    'aria-expanded',
                                    'true'
                                );

                            }

                        }
                    );

                }


                /* =================================================
                   MENU UTAMA
                ================================================== */

                document
                    .querySelectorAll(
                        '.sidebar .menu-link:not(.master-toggle)'
                    )
                    .forEach(
                        function (link) {

                            link.addEventListener(
                                'click',
                                function () {

                                    document
                                        .querySelectorAll(
                                            '.sidebar .menu-link'
                                        )
                                        .forEach(
                                            function (item) {

                                                item.classList.remove(
                                                    'active'
                                                );

                                            }
                                        );


                                    document
                                        .querySelectorAll(
                                            '.sidebar .submenu-link'
                                        )
                                        .forEach(
                                            function (item) {

                                                item.classList.remove(
                                                    'active'
                                                );

                                            }
                                        );


                                    this.classList.add(
                                        'active'
                                    );


                                    if (masterToggle) {

                                        masterToggle.classList.remove(
                                            'active'
                                        );

                                        masterToggle.classList.remove(
                                            'open'
                                        );

                                        masterToggle.setAttribute(
                                            'aria-expanded',
                                            'false'
                                        );

                                    }


                                    if (masterSubmenu) {

                                        masterSubmenu.classList.remove(
                                            'open'
                                        );

                                    }


                                    if (
                                        window.innerWidth <= 900
                                    ) {

                                        closeSidebar();

                                    }

                                }
                            );

                        }
                    );


                /* =================================================
                   SUBMENU MASTER
                ================================================== */

                document
                    .querySelectorAll(
                        '.sidebar .submenu-link'
                    )
                    .forEach(
                        function (link) {

                            link.addEventListener(
                                'click',
                                function () {

                                    document
                                        .querySelectorAll(
                                            '.sidebar .menu-link'
                                        )
                                        .forEach(
                                            function (item) {

                                                item.classList.remove(
                                                    'active'
                                                );

                                            }
                                        );


                                    document
                                        .querySelectorAll(
                                            '.sidebar .submenu-link'
                                        )
                                        .forEach(
                                            function (item) {

                                                item.classList.remove(
                                                    'active'
                                                );

                                            }
                                        );


                                    this.classList.add(
                                        'active'
                                    );


                                    if (masterToggle) {

                                        masterToggle.classList.remove(
                                            'active'
                                        );

                                        masterToggle.classList.add(
                                            'open'
                                        );

                                        masterToggle.setAttribute(
                                            'aria-expanded',
                                            'true'
                                        );

                                    }


                                    if (masterSubmenu) {

                                        masterSubmenu.classList.add(
                                            'open'
                                        );

                                    }


                                    if (
                                        window.innerWidth <= 900
                                    ) {

                                        closeSidebar();

                                    }

                                }
                            );

                        }
                    );


                /* =================================================
                    LOGOUT
                    ================================================== */

                    const logoutForm = document.querySelector('.logout-form');

                    if (logoutForm) {

                        logoutForm.addEventListener('submit', function () {

                            if (window.innerWidth <= 900) {
                                closeSidebar();
                            }

                        });

                    }


                /* =================================================
                   ESC
                ================================================== */

                document.addEventListener(
                    'keydown',
                    function (event) {

                        if (
                            event.key === 'Escape' ||
                            event.key === 'Esc'
                        ) {

                            if (
                                sidebar &&
                                sidebar.classList.contains(
                                    'active'
                                )
                            ) {

                                closeSidebar();

                            }

                        }

                    }
                );


                /* =================================================
                   RESIZE
                ================================================== */

                let resizeTimer = null;

                window.addEventListener(
                    'resize',
                    function () {

                        clearTimeout(resizeTimer);

                        resizeTimer =
                            setTimeout(
                                function () {

                                    if (
                                        window.innerWidth > 900
                                    ) {

                                        closeSidebar();

                                    }

                                },
                                100
                            );

                    }
                );


                /* =================================================
                   PREVENT BODY LOCK WHEN DESKTOP
                ================================================== */

                window.addEventListener(
                    'pageshow',
                    function () {

                        if (
                            window.innerWidth > 900
                        ) {

                            document.body.style.overflow = '';

                        }

                    }
                );

            }
        );

    </script>


    @stack('scripts')


</body>

</html>