<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Manajemen Periode</title>


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

        .page {
            min-height: 100vh;
            display: flex;
        }

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

        .logo-area {
            height: 105px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255,255,255,.12);
            flex-shrink: 0;
        }

        .logo {
            width: 72px;
            height: 72px;
            object-fit: contain;
            border-radius: 50%;
            background: #ffffff;
            display: block;
        }

        .menu {
            padding: 20px 14px;
            flex: 1;
            display: block;
        }

        .menu-title {
            font-size: 11px;
            font-weight: 700;
            color: rgba(255,255,255,.55);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 8px 10px 12px;
        }

        .menu a,
        .master-toggle {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 12px 14px;
            margin-bottom: 5px;
            border-radius: 9px;
            color: rgba(255,255,255,.82);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all .2s ease;
        }

        .menu a:hover,
        .master-toggle:hover {
            background: rgba(255,255,255,.10);
            color: #ffffff;
        }

        .menu a.active,
        .submenu-link.active {
            background: #ffffff;
            color: #252A86;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(0,0,0,.10);
        }

        .menu-icon {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 14px;
            line-height: 1;
        }

        .menu-icon svg {
            width: 18px;
            height: 18px;
            display: block;
        }

        .master-toggle {
            border: none;
            background: transparent;
            cursor: pointer;
            font-family: inherit;
            text-align: left;
            appearance: none;
        }

        .master-toggle-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            min-width: 0;
        }

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

        .master-toggle .master-arrow svg {
            width: 18px;
            height: 18px;
        }

        .master-toggle.open .master-arrow {
            transform: rotate(180deg);
        }

        .master-toggle.master-active {
            background: rgba(255,255,255,.10);
            color: #ffffff;
        }

        .master-toggle.master-active:hover {
            background: rgba(255,255,255,.14);
        }

        .master-submenu {
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            padding-left: 18px;
            transition: max-height .3s ease, opacity .2s ease;
        }

        .master-submenu.open {
            max-height: 200px;
            opacity: 1;
        }

        .submenu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 10px 14px;
            margin-bottom: 4px;
            border-radius: 8px;
            color: rgba(255,255,255,.72);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all .2s ease;
        }

        .submenu-link:hover {
            background: rgba(255,255,255,.10);
            color: #ffffff;
        }

        .submenu-link.active {
            box-shadow: 0 3px 8px rgba(0,0,0,.08);
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

        .sidebar-footer {
            padding: 15px 14px;
            border-top: 1px solid rgba(255,255,255,.12);
            flex-shrink: 0;
        }

        .logout-link {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .logout-link button {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border: none;
            border-radius: 9px;
            background: transparent;
            color: rgba(255,255,255,.85);
            font-size: 14px;
            font-family: inherit;
            text-align: left;
            cursor: pointer;
            transition: all .2s ease;
        }

        .logout-link button:hover {
            background: rgba(255,255,255,.10);
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
            width: 20px;
            height: 20px;
        }


        /* =====================================================
           MAIN
        ====================================================== */

        .main {
            flex: 1;
            min-width: 0;
            margin-left: 260px;
            width: calc(100% - 260px);
        }


        /* =====================================================
           HEADER
        ====================================================== */

        .top-header {
            height: 75px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 0;
        }

        .brand-title {
            color: #252A86;
            font-size: 16px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .brand-subtitle {
            margin-top: 4px;
            color: #9ca3af;
            font-size: 11px;
        }

        .user-area {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-size: 12px;
            font-weight: 700;
            color: #1f2937;
        }

        .user-role {
            margin-top: 2px;
            color: #9ca3af;
            font-size: 10px;
        }

        .avatar {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #252A86;
            color: #ffffff;
            border-radius: 50%;
            font-size: 13px;
            font-weight: 700;
        }


        /* =====================================================
           PROFILE
        ====================================================== */

        .profile-link {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: inherit;
            cursor: pointer;
            padding: 5px 8px;
            border-radius: 10px;
            position: relative;
            z-index: 100;
            transition: .2s ease;
        }

        .profile-link:hover {
            background: #f3f4f6;
        }

        .profile-link .user-info {
            text-align: right;
        }


        /* =====================================================
           HAMBURGER MOBILE
        ====================================================== */

        .hamburger {
            display: none;
            width: 38px;
            height: 38px;
            flex: 0 0 38px;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 4px;
            border: 1px solid #e5e7eb;
            background: #ffffff;
            border-radius: 7px;
            cursor: pointer;
        }

        .hamburger span {
            display: block;
            width: 17px;
            height: 2px;
            background: #252A86;
            border-radius: 2px;
        }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.35);
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

        .page-header {
            margin-bottom: 25px;
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
        }


        /* =====================================================
           TOOLBAR
        ====================================================== */

        .toolbar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 12px;
        }

        .btn-tambah {
            min-width: 143px;
            height: 41px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 18px;
            background: #292d8f;
            color: #fff;
            border-radius: 9px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: .2s ease;
        }

        .btn-tambah:hover {
            background: #202475;
            transform: translateY(-1px);
        }


        /* =====================================================
           CARD
        ====================================================== */

        .card {
            width: 100%;
            padding: 20px 22px;
            background: #fff;
            border: 1px solid #e2e3e9;
            border-radius: 12px;
            box-shadow: 0 2px 7px rgba(0,0,0,.03);
        }


        /* =====================================================
           SEARCH
        ====================================================== */

        .search {
            width: 100%;
            height: 44px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 14px;
            margin-bottom: 18px;
            background: #fff;
            border: 1px solid #cfd2dc;
            border-radius: 8px;
            transition: .2s ease;
        }

        .search:focus-within {
            border-color: #292d8f;
            box-shadow: 0 0 0 2px rgba(41,45,143,.10);
        }

        .search-icon {
            color: #777;
            font-size: 18px;
        }

        .search input {
            width: 100%;
            height: 100%;
            border: none;
            outline: none;
            background: transparent;
            font-size: 13px;
        }


        /* =====================================================
           TABLE
        ====================================================== */

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        thead th {
            padding: 13px 14px;
            background: #f7f7fa;
            color: #555;
            border-bottom: 1px solid #e3e4e9;
            font-size: 11px;
            font-weight: 700;
            text-align: left;
        }

        tbody td {
            padding: 14px;
            color: #444;
            border-bottom: 1px solid #eeeef2;
            font-size: 12px;
            vertical-align: middle;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background: #fafaff;
        }


        /* =====================================================
           STATUS
        ====================================================== */

        .status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
        }

        .akan-datang {
            background: #eaf0ff;
            color: #3656b3;
        }

        .aktif {
            background: #e7f7ed;
            color: #21864a;
        }

        .selesai {
            background: #fff6dc;
            color: #a87900;
        }


        /* =====================================================
           AKSI
        ====================================================== */

        .action-group {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .btn-edit,
        .btn-hapus {
            height: 32px;
            padding: 0 11px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s ease;
        }

        .btn-edit {
            background: #fff8e5;
            color: #a47a00;
            border: 1px solid #f0dfaa;
        }

        .btn-edit:hover {
            background: #fff1c7;
        }

        .btn-hapus {
            background: #fdecec;
            color: #c74343;
            border: 1px solid #f5d4d4;
        }

        .btn-hapus:hover {
            background: #fbdada;
        }


        /* =====================================================
           PAGINATION
           10 DATA PER HALAMAN
        ====================================================== */

        .pagination-wrapper {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-top: 18px;
            padding-top: 15px;
            border-top: 1px solid #eeeef2;
        }

        .pagination-info {
            display: block;
            color: #777;
            font-size: 11px;
            white-space: nowrap;
        }

        .pagination {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 5px;
        }

        .pagination button {
            min-width: 28px;
            height: 28px;
            padding: 0 8px;
            border: 1px solid #dfe1e8;
            background: #ffffff;
            color: #555;
            border-radius: 6px;
            font-size: 11px;
            cursor: pointer;
            transition: .2s ease;
        }

        .pagination button:hover:not(:disabled):not(.page-number.active) {
            background: #f1f2fa;
            border-color: #292d8f;
            color: #292d8f;
        }

        .pagination button.arrow-button {
            font-size: 16px;
            font-weight: 400;
        }

        .pagination button.page-number {
            font-weight: 500;
        }

        .pagination button.page-number.active {
            background: #292d8f;
            border-color: #292d8f;
            color: #ffffff;
            font-weight: 700;
            cursor: default;
        }

        .pagination button:disabled {
            opacity: .45;
            cursor: not-allowed;
        }


        /* =====================================================
           MODAL
        ====================================================== */

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(20, 23, 70, 0.42);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 9999;
            backdrop-filter: blur(3px);
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal-box {
            width: 470px;
            max-width: 100%;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 20px 60px rgba(0,0,0,.20);
            animation: modalShow .2s ease;
        }

        @keyframes modalShow {

            from {
                opacity: 0;
                transform: translateY(-15px) scale(.97);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }

        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 22px;
            border-bottom: 1px solid #eee;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 700;
            color: #292d8f;
        }

        .modal-close {
            width: 32px;
            height: 32px;
            border: none;
            background: #f3f3f7;
            color: #555;
            border-radius: 7px;
            font-size: 20px;
            cursor: pointer;
        }

        .modal-close:hover {
            background: #e8e8ef;
            color: #222;
        }

        .modal-body {
            padding: 22px;
        }

        .form-group-modal {
            margin-bottom: 17px;
        }

        .form-group-modal label {
            display: block;
            margin-bottom: 7px;
            color: #333;
            font-size: 12px;
            font-weight: 700;
        }

        .form-group-modal input,
        .form-group-modal select {
            width: 100%;
            height: 43px;
            padding: 0 12px;
            border: 1px solid #d5d7df;
            border-radius: 8px;
            outline: none;
            background: #fff;
            color: #333;
            font-size: 13px;
        }

        .form-group-modal input:focus,
        .form-group-modal select:focus {
            border-color: #292d8f;
            box-shadow: 0 0 0 3px rgba(41,45,143,.08);
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 9px;
            padding: 17px 22px;
            border-top: 1px solid #eee;
            background: #fafafa;
            border-radius: 0 0 14px 14px;
        }

        .btn-modal {
            height: 39px;
            padding: 0 18px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-batal {
            background: #fff;
            color: #555;
            border: 1px solid #d5d7df;
        }

        .btn-simpan {
            background: #292d8f;
            color: #fff;
            border: 1px solid #292d8f;
        }


        /* =====================================================
           PESAN
        ====================================================== */

        .alert {
            padding: 13px 16px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 13px;
        }

        .alert-success {
            background: #e7f7ed;
            color: #21864a;
            border: 1px solid #ccebd8;
        }

        .error-box {
            margin-bottom: 15px;
            padding: 10px 12px;
            background: #fdecec;
            color: #c74343;
            border-radius: 7px;
            font-size: 12px;
        }


        /* =====================================================
           RESPONSIVE
        ====================================================== */

        @media (max-width: 900px) {

            .sidebar {
                width: 270px;
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main {
                margin-left: 0;
                width: 100%;
            }

            .hamburger {
                display: flex;
            }

            .top-header {
                height: 70px;
                padding: 0 20px;
            }

            .content {
                padding: 20px;
            }

        }


        @media (max-width: 768px) {

            .page-title {
                font-size: 24px;
            }

            .toolbar {
                justify-content: stretch;
            }

            .btn-tambah {
                width: 100%;
            }

            .card {
                padding: 16px;
            }

            table {
                min-width: 720px;
            }

            table th:last-child,
            table td:last-child {
                width: 130px !important;
                min-width: 130px;
            }

            .user-info {
                display: none;
            }

            .pagination-wrapper {
                justify-content: space-between;
            }

        }


        @media (max-width: 600px) {

            .top-header {
                height: 64px;
                padding: 0 14px;
            }

            .brand-title {
                font-size: 13px;
                max-width: 190px;
            }

            .brand-subtitle {
                display: none;
            }

            .content {
                padding: 16px;
            }

            .page-title {
                font-size: 22px;
            }

            .modal-box {
                width: 100%;
            }

            .pagination-info {
                font-size: 10px;
            }

        }

    </style>

</head>


<body>

<div class="page">


    <!-- =====================================================
         SIDEBAR
    ===================================================== -->

    <aside class="sidebar" id="sidebar">

        <div class="logo-area">

            <img
                src="{{ asset('images/dinsos.png') }}"
                class="logo"
                alt="Logo Dinsos"
            >

        </div>


        <nav class="menu">

            <div class="menu-title">
                Menu Utama
            </div>


            <!-- DASHBOARD -->

            <a
                href="{{ route('dashboard') }}"
                class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
            >

                <span class="menu-icon">

                    <svg
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


            <!-- RESPONDEN -->

            <a
                href="{{ route('responden.index') }}"
                class="{{ request()->routeIs('responden.*') ? 'active' : '' }}"
            >

                <span class="menu-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >

                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>

                        <circle cx="12" cy="7" r="4"></circle>

                    </svg>

                </span>

                <span>Responden</span>

            </a>


            <!-- KUISIONER -->

            <a
                href="{{ route('periode.pilih', ['tujuan' => 'kuisioner.index']) }}" 
                class="{{ request()->routeIs('kuisioner.*') ? 'active' : '' }}"
            >

                <span class="menu-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >

                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>

                        <polyline points="14 2 14 8 20 8"></polyline>

                        <line x1="8" y1="13" x2="16" y2="13"></line>

                        <line x1="8" y1="17" x2="16" y2="17"></line>

                    </svg>

                </span>

                <span>Kuisioner</span>

            </a>


            <!-- VERIFIKASI -->

            <a
                href="{{ route('periode.pilih', ['tujuan' => 'verifikasi.index']) }}"
                class="{{ request()->routeIs('verifikasi.*') ? 'active' : '' }}"
            >

                <span class="menu-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >

                        <path d="M9 11l3 3L22 4"></path>

                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>

                    </svg>

                </span>

                <span>Verifikasi</span>

            </a>


            <!-- MONITORING -->

            <a
                href="{{ route('periode.pilih', ['tujuan' => 'monitoring.index']) }}"
                class="{{ request()->routeIs('monitoring.*') ? 'active' : '' }}"
            >

                <span class="menu-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >

                        <polyline points="3 3 3 21 21 21"></polyline>

                        <polyline points="7 16 11 12 14 15 21 8"></polyline>

                    </svg>

                </span>

                <span>Monitoring</span>

            </a>


            <!-- LAPORAN -->

            <a
                href="{{ route('periode.pilih', ['tujuan' => 'laporan.index']) }}"
                class="{{ request()->routeIs('laporan.*') ? 'active' : '' }}"
            >

                <span class="menu-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >

                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2-2V8z"></path>

                        <polyline points="14 2 14 8 20 8"></polyline>

                        <line x1="8" y1="13" x2="16" y2="13"></line>

                        <line x1="8" y1="17" x2="16" y2="17"></line>

                    </svg>

                </span>

                <span>Laporan</span>

            </a>


            <!-- MASTER -->

            <button
                type="button"
                class="master-toggle master-active open"
                id="masterToggle"
                aria-expanded="true"
            >

                <span class="master-toggle-left">

                    <span class="menu-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >

                            <circle cx="12" cy="12" r="3"></circle>

                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06-1.42 1.42-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21h-2v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06-1.42-1.42.06-.06A1.65 1.65 0 0 0 8.6 15a1.65 1.65 0 0 0-1.51-1H7v-2h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06 1.42-1.42.06.06A1.65 1.65 0 0 0 12.52 6H12V4h2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06 1.42 1.42-.06.06A1.65 1.65 0 0 0 18.6 9a1.65 1.65 0 0 0 1.51 1H20v2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>

                        </svg>

                    </span>

                    <span>Master</span>

                </span>


                <span class="master-arrow">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >

                        <polyline points="6 9 12 15 18 9"></polyline>

                    </svg>

                </span>

            </button>


            <!-- MASTER SUBMENU -->

            <div
                class="master-submenu open"
                id="masterSubmenu"
            >

                <!-- PERIODE -->

                <a
                    href="{{ route('periode.index') }}"
                    class="submenu-link {{ request()->routeIs('periode.*') ? 'active' : '' }}"
                >

                    <span class="submenu-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >

                            <rect x="3" y="4" width="18" height="17" rx="2"></rect>

                            <line x1="16" y1="2" x2="16" y2="6"></line>

                            <line x1="8" y1="2" x2="8" y2="6"></line>

                            <line x1="3" y1="10" x2="21" y2="10"></line>

                        </svg>

                    </span>

                    <span>Periode</span>

                </a>


                <!-- PENGGUNA -->

                <a
                    href="{{ route('master.index') }}"
                    class="submenu-link {{ request()->routeIs('master.*') ? 'active' : '' }}"
                >

                    <span class="submenu-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >

                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>

                            <circle cx="9" cy="7" r="4"></circle>

                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>

                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>

                        </svg>

                    </span>

                    <span>Pengguna</span>

                </a>

            </div>

        </nav>


        <!-- LOGOUT -->

        <div class="sidebar-footer">

            <form
                action="{{ route('logout') }}"
                method="POST"
                class="logout-link"
            >

                @csrf

                <button type="submit">

                    <span class="menu-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >

                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>

                            <polyline points="16 17 21 12 16 7"></polyline>

                            <line x1="21" y1="12" x2="9" y2="12"></line>

                        </svg>

                    </span>

                    <span>Keluar</span>

                </button>

            </form>

        </div>

    </aside>


    <!-- OVERLAY MOBILE -->

    <div
        class="overlay"
        id="overlay"
    ></div>


    <!-- =====================================================
         MAIN
    ===================================================== -->

    <main class="main">


        <!-- HEADER -->

        <header class="top-header">

            <div class="brand">

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

                    <div class="brand-title">
                        Sistem Pendataan Perlinsos Kota Pasuruan
                    </div>

                    <div class="brand-subtitle">
                        Panel Administrasi
                    </div>

                </div>

            </div>


            <div class="user-area">

                <a
                    href="{{ route('profil.index') }}"
                    class="profile-link"
                >

                    <div class="user-info">

                        <div class="user-name">
                            {{ auth()->user()?->name ?? 'Pengguna' }}
                        </div>

                        <div class="user-role">
                            {{ ucfirst(auth()->user()?->role ?? 'Pengguna') }}
                        </div>

                    </div>


                    <div class="avatar">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'P', 0, 1)) }}
                    </div>

                </a>

            </div>

        </header>


        <!-- CONTENT -->

        <section class="content">


            <!-- PESAN ERROR -->

            @if ($errors->any())

                <div class="error-box">

                    @foreach ($errors->all() as $error)

                        <div>
                            {{ $error }}
                        </div>

                    @endforeach

                </div>

            @endif


            <div class="page-header">

                <div class="page-kicker">
                    ADMIN
                </div>

                <h1 class="page-title">
                    Manajemen Periode
                </h1>

                <p class="page-description">
                    Kelola periode pendataan dan jadwal kegiatan secara terstruktur.
                </p>

            </div>


            <!-- TOMBOL TAMBAH -->

            <div class="toolbar">

                <button
                    type="button"
                    class="btn-tambah"
                    onclick="openAddModal()"
                >
                    + Tambah Periode
                </button>

            </div>


            <!-- CARD -->

            <div class="card">


                <!-- SEARCH -->

                <div class="search">

                    <span class="search-icon">
                        ⌕
                    </span>

                    <input
                        type="text"
                        id="searchPeriode"
                        placeholder="Search..."
                        autocomplete="off"
                    >

                </div>


                <!-- TABLE -->

                <div class="table-wrapper">

                    <table>

                        <thead>

                            <tr>

                                <th style="width: 7%;">
                                    No.
                                </th>

                                <th style="width: 25%;">
                                    Nama Kegiatan
                                </th>

                                <th style="width: 20%;">
                                    Tanggal Mulai
                                </th>

                                <th style="width: 20%;">
                                    Tanggal Selesai
                                </th>

                                <th style="width: 13%;">
                                    Status
                                </th>

                                <th style="width: 15%;">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody id="periodeTable">

                            @forelse ($periodes as $index => $periode)

                                <tr class="periode-row">

                                    <td>
                                        {{ $index + 1 }}
                                    </td>


                                    <td class="nama-kegiatan">

                                        {{ $periode->nama }}

                                    </td>


                                    <td class="tanggal-mulai">

                                        {{ $periode->tgl_awal->format('d-m-Y') }}

                                    </td>


                                    <td class="tanggal-selesai">

                                        {{ $periode->tgl_akhir->format('d-m-Y') }}

                                    </td>


                                    <td>

                                        @if (strtolower($periode->status_periode) == 'aktif')

                                            <span class="status aktif">
                                                Aktif
                                            </span>

                                        @elseif (strtolower($periode->status_periode) == 'selesai')

                                            <span class="status selesai">
                                                Selesai
                                            </span>

                                        @else

                                            <span class="status akan-datang">
                                                {{ $periode->status_periode }}
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        <div class="action-group">


                                            <!-- EDIT -->

                                            <button
                                                type="button"
                                                class="btn-edit edit-button"

                                                data-id="{{ $periode->id }}"
                                                data-nama="{{ $periode->nama }}"
                                                data-mulai="{{ $periode->tgl_awal?->format('Y-m-d') }}"
                                                data-selesai="{{ $periode->tgl_akhir?->format('Y-m-d') }}"
                                                data-status="{{ $periode->status_periode }}"
                                            >
                                                Edit
                                            </button>


                                            <!-- HAPUS -->

                                            <form
                                                action="{{ route('periode.destroy', $periode->id) }}"
                                                method="POST"
                                                style="display:inline;"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus periode ini?')"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn-hapus"
                                                >
                                                    Hapus
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr id="emptyPeriodeRow">

                                    <td
                                        colspan="6"
                                        style="text-align:center; padding:30px; color:#888;"
                                    >
                                        Belum ada data periode.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                <!-- =================================================
                     PAGINATION
                ================================================== -->

                @if ($periodes->count() > 0)

                    <div class="pagination-wrapper">

                        <div
                            class="pagination-info"
                            id="paginationInfo"
                        ></div>

                        <div
                            class="pagination"
                            id="paginationButtons"
                        ></div>

                    </div>

                @endif


            </div>

        </section>

    </main>

</div>


<!-- =====================================================
     MODAL TAMBAH / EDIT
===================================================== -->

<div
    class="modal-overlay"
    id="periodeModal"
    onclick="closeModalOutside(event)"
>


    <div
        class="modal-box"
        onclick="event.stopPropagation()"
    >


        <div class="modal-header">

            <h2
                class="modal-title"
                id="modalTitle"
            >
                Tambah Periode
            </h2>


            <button
                type="button"
                class="modal-close"
                onclick="closeModal()"
            >
                ×
            </button>

        </div>


        <!-- FORM -->

        <form
            id="periodeForm"
            action="{{ route('periode.store') }}"
            method="POST"
        >

            @csrf


            <!-- METHOD EDIT -->

            <input
                type="hidden"
                name="_method"
                id="methodField"
                value="POST"
            >


            <div class="modal-body">


                <!-- NAMA -->

                <div class="form-group-modal">

                    <label for="nama_periode">
                        Nama Kegiatan
                    </label>

                    <input
                        type="text"
                        id="nama_periode"
                        name="nama_periode"
                        placeholder="Contoh: Pendataan Awal 2026"
                        required
                    >

                </div>


                <!-- TANGGAL MULAI -->

                <div class="form-group-modal">

                    <label for="tanggal_mulai">
                        Tanggal Mulai
                    </label>

                    <input
                        type="date"
                        id="tanggal_mulai"
                        name="tanggal_mulai"
                        required
                    >

                </div>


                <!-- TANGGAL SELESAI -->

                <div class="form-group-modal">

                    <label for="tanggal_selesai">
                        Tanggal Selesai
                    </label>

                    <input
                        type="date"
                        id="tanggal_selesai"
                        name="tanggal_selesai"
                        required
                    >

                </div>


                <!-- STATUS -->

                <div class="form-group-modal">

                    <label for="status">
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        required
                    >

                        <option value="Akan Datang">
                            Akan Datang
                        </option>

                        <option value="Aktif">
                            Aktif
                        </option>

                        <option value="Selesai">
                            Selesai
                        </option>

                    </select>

                </div>

            </div>


            <!-- FOOTER -->

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn-modal btn-batal"
                    onclick="closeModal()"
                >
                    Batal
                </button>


                <button
                    type="submit"
                    class="btn-modal btn-simpan"
                >
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>


<script>


    /* =====================================================
       TAMBAH
    ===================================================== */

    function openAddModal()
    {

        document.getElementById(
            "modalTitle"
        ).textContent = "Tambah Periode";


        document.getElementById(
            "periodeForm"
        ).action =
            "{{ route('periode.store') }}";


        document.getElementById(
            "methodField"
        ).value = "POST";


        document.getElementById(
            "nama_periode"
        ).value = "";


        document.getElementById(
            "tanggal_mulai"
        ).value = "";


        document.getElementById(
            "tanggal_selesai"
        ).value = "";


        document.getElementById(
            "status"
        ).value = "Akan Datang";


        document.getElementById(
            "periodeModal"
        ).classList.add("show");


        setTimeout(function () {

            document.getElementById(
                "nama_periode"
            ).focus();

        }, 100);

    }



    /* =====================================================
       EDIT
    ===================================================== */

    document
        .querySelectorAll(".edit-button")
        .forEach(function(button)
        {

            button.addEventListener(
                "click",
                function()
                {

                    const id =
                        this.dataset.id;

                    const nama =
                        this.dataset.nama;

                    const mulai =
                        this.dataset.mulai;

                    const selesai =
                        this.dataset.selesai;

                    const status =
                        this.dataset.status;


                    document.getElementById(
                        "modalTitle"
                    ).textContent =
                        "Edit Periode";


                    document.getElementById(
                        "nama_periode"
                    ).value =
                        nama;


                    document.getElementById(
                        "tanggal_mulai"
                    ).value =
                        mulai;


                    document.getElementById(
                        "tanggal_selesai"
                    ).value =
                        selesai;


                    document.getElementById(
                        "status"
                    ).value =
                        status;


                    document.getElementById(
                        "periodeForm"
                    ).action =
                        "{{ url('/periode') }}/" + id;


                    document.getElementById(
                        "methodField"
                    ).value =
                        "PUT";


                    document.getElementById(
                        "periodeModal"
                    ).classList.add("show");

                }
            );

        });



    /* =====================================================
       CLOSE MODAL
    ===================================================== */

    function closeModal()
    {

        document.getElementById(
            "periodeModal"
        ).classList.remove("show");

    }


    function closeModalOutside(event)
    {

        if (
            event.target.id ===
            "periodeModal"
        )
        {

            closeModal();

        }

    }



    /* =====================================================
       PAGINATION PERIODE

       10 DATA PER HALAMAN

       Contoh:

       14 DATA

       HALAMAN 1
       Menampilkan 1–10 dari 14 periode
       ←  1  2  →

       HALAMAN 2
       Menampilkan 11–14 dari 14 periode
       ←  1  2  →
    ===================================================== */

    const rowsPerPage = 10;

    let currentPage = 1;



    /* =====================================================
       AMBIL SEMUA BARIS DATA
    ===================================================== */

    function getPeriodeRows()
    {

        return Array.from(
            document.querySelectorAll(
                "#periodeTable .periode-row"
            )
        );

    }



    /* =====================================================
       RENDER PAGINATION
    ===================================================== */

    function renderPagination()
    {

        const allRows =
            getPeriodeRows();


        const searchInput =
            document.getElementById(
                "searchPeriode"
            );


        const keyword =
            searchInput
                ? searchInput.value
                    .toLowerCase()
                    .trim()
                : "";


        /* =================================================
           FILTER SEARCH
        ================================================= */

        const filteredRows =
            allRows.filter(function(row)
            {

                const text =
                    row.textContent
                        .toLowerCase();

                return text.includes(keyword);

            });



        /* =================================================
           HITUNG TOTAL HALAMAN
        ================================================= */

        const totalPages =
            Math.max(
                1,
                Math.ceil(
                    filteredRows.length /
                    rowsPerPage
                )
            );



        /* =================================================
           PASTIKAN CURRENT PAGE VALID
        ================================================= */

        if (currentPage > totalPages)
        {

            currentPage = totalPages;

        }


        if (currentPage < 1)
        {

            currentPage = 1;

        }



        /* =================================================
           SEMBUNYIKAN SEMUA BARIS
        ================================================= */

        allRows.forEach(function(row)
        {

            row.style.display = "none";

        });



        /* =================================================
           TENTUKAN DATA HALAMAN SEKARANG
        ================================================= */

        const start =
            (currentPage - 1) *
            rowsPerPage;


        const end =
            start +
            rowsPerPage;


        const pageRows =
            filteredRows.slice(
                start,
                end
            );



        /* =================================================
           TAMPILKAN DATA HALAMAN SEKARANG
        ================================================= */

        pageRows.forEach(function(row)
        {

            row.style.display = "";

        });



        /* =================================================
           PAGINATION CONTAINER
        ================================================= */

        const paginationButtons =
            document.getElementById(
                "paginationButtons"
            );


        const paginationInfo =
            document.getElementById(
                "paginationInfo"
            );


        if (!paginationButtons)
        {

            return;

        }



        /* =================================================
           HAPUS PAGINATION LAMA
        ================================================= */

        paginationButtons.innerHTML = "";



        /* =================================================
           INFORMASI DATA
        ================================================= */

        if (paginationInfo)
        {

            if (filteredRows.length === 0)
            {

                paginationInfo.textContent =
                    "Tidak ada data";

            }
            else
            {

                const displayStart =
                    start + 1;


                const displayEnd =
                    Math.min(
                        end,
                        filteredRows.length
                    );


                paginationInfo.textContent =
                    "Menampilkan " +
                    displayStart +
                    "–" +
                    displayEnd +
                    " dari " +
                    filteredRows.length +
                    " periode";

            }

        }



        /* =================================================
           KALAU TIDAK ADA DATA HASIL SEARCH
        ================================================= */

        if (filteredRows.length === 0)
        {

            return;

        }



        /* =================================================
           TOMBOL SEBELUMNYA
        ================================================= */

        const previousButton =
            document.createElement(
                "button"
            );


        previousButton.type =
            "button";


        previousButton.className =
            "arrow-button";


        previousButton.innerHTML =
            "‹";


        previousButton.title =
            "Halaman sebelumnya";


        previousButton.disabled =
            currentPage === 1;


        previousButton.addEventListener(
            "click",
            function()
            {

                if (currentPage > 1)
                {

                    currentPage--;

                    renderPagination();

                }

            }
        );


        paginationButtons.appendChild(
            previousButton
        );



        /* =================================================
           NOMOR HALAMAN
        ================================================= */

        for (
            let page = 1;
            page <= totalPages;
            page++
        )
        {

            const pageButton =
                document.createElement(
                    "button"
                );


            pageButton.type =
                "button";


            pageButton.className =
                "page-number";


            pageButton.textContent =
                page;


            if (page === currentPage)
            {

                pageButton.classList.add(
                    "active"
                );

            }


            pageButton.addEventListener(
                "click",
                function()
                {

                    currentPage = page;

                    renderPagination();

                }
            );


            paginationButtons.appendChild(
                pageButton
            );

        }



        /* =================================================
           TOMBOL BERIKUTNYA
        ================================================= */

        const nextButton =
            document.createElement(
                "button"
            );


        nextButton.type =
            "button";


        nextButton.className =
            "arrow-button";


        nextButton.innerHTML =
            "›";


        nextButton.title =
            "Halaman selanjutnya";


        nextButton.disabled =
            currentPage === totalPages;


        nextButton.addEventListener(
            "click",
            function()
            {

                if (
                    currentPage <
                    totalPages
                )
                {

                    currentPage++;

                    renderPagination();

                }

            }
        );


        paginationButtons.appendChild(
            nextButton
        );

    }



    /* =====================================================
       SEARCH

       Search tetap bekerja bersama pagination.
    ===================================================== */

    const searchInput =
        document.getElementById(
            "searchPeriode"
        );


    if (searchInput)
    {

        searchInput.addEventListener(
            "input",
            function()
            {

                currentPage = 1;

                renderPagination();

            }
        );

    }



    /* =====================================================
       JALANKAN PAGINATION
    ===================================================== */

    renderPagination();



    /* =====================================================
       ESC CLOSE MODAL
    ===================================================== */

    document.addEventListener(
        "keydown",
        function(event)
        {

            if (
                event.key ===
                "Escape"
            )
            {

                closeModal();

            }

        }
    );



    /* =====================================================
       SIDEBAR MASTER
    ===================================================== */

    const masterToggle =
        document.getElementById(
            "masterToggle"
        );


    const masterSubmenu =
        document.getElementById(
            "masterSubmenu"
        );


    if (
        masterToggle &&
        masterSubmenu
    )
    {

        masterToggle.addEventListener(
            "click",
            function()
            {

                const isOpen =
                    masterSubmenu.classList.toggle(
                        "open"
                    );


                masterToggle.classList.toggle(
                    "open",
                    isOpen
                );


                masterToggle.classList.toggle(
                    "master-active",
                    isOpen
                );


                masterToggle.setAttribute(
                    "aria-expanded",
                    isOpen
                        ? "true"
                        : "false"
                );

            }
        );

    }



    /* =====================================================
       MOBILE SIDEBAR
    ===================================================== */

    const sidebar =
        document.getElementById(
            "sidebar"
        );


    const hamburger =
        document.getElementById(
            "hamburger"
        );


    const overlay =
        document.getElementById(
            "overlay"
        );


    function openSidebar()
    {

        if (sidebar)
        {

            sidebar.classList.add(
                "show"
            );

        }


        if (overlay)
        {

            overlay.classList.add(
                "show"
            );

        }

    }


    function closeSidebar()
    {

        if (sidebar)
        {

            sidebar.classList.remove(
                "show"
            );

        }


        if (overlay)
        {

            overlay.classList.remove(
                "show"
            );

        }

    }


    if (hamburger)
    {

        hamburger.addEventListener(
            "click",
            function()
            {

                if (
                    sidebar &&
                    sidebar.classList.contains(
                        "show"
                    )
                )
                {

                    closeSidebar();

                }
                else
                {

                    openSidebar();

                }

            }
        );

    }


    if (overlay)
    {

        overlay.addEventListener(
            "click",
            closeSidebar
        );

    }


    if (sidebar)
    {

        sidebar
            .querySelectorAll("a")
            .forEach(
                function(link)
                {

                    link.addEventListener(
                        "click",
                        function()
                        {

                            if (
                                window.innerWidth <=
                                900
                            )
                            {

                                sidebar.classList.remove(
                                    "show"
                                );

                            }

                        }
                    );

                }
            );

    }


</script>


</body>

</html>