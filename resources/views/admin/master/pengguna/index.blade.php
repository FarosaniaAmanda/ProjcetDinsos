<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Master | Sistem Pendataan Dinas Sosial Kota Pasuruan</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            min-height: 100%;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f6fa;
            color: #1f2937;
        }

        body {
            overflow-x: hidden;
        }

        /* =========================
           SIDEBAR
        ========================= */

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
            height: 110px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px;
            color: #ffffff;
        }

        .sidebar-logo img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .sidebar-logo-text {
            font-size: 13px;
            font-weight: 700;
            line-height: 1.4;
        }

        .sidebar-menu {
            padding: 10px 14px 25px;
        }

        .menu-title {
            color: rgba(255, 255, 255, 0.55);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px 12px 8px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 13px;
            margin-bottom: 4px;
            border-radius: 7px;
            color: rgba(255, 255, 255, 0.88);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .sidebar-menu a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .sidebar-menu a.active {
            background: #ffffff;
            color: #252A86;
            font-weight: 700;
        }

        .menu-icon {
            width: 20px;
            min-width: 20px;
            text-align: center;
            font-size: 15px;
        }

        /* =========================
           MAIN
        ========================= */

        .main {
            margin-left: 260px;
            width: calc(100% - 260px);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* =========================
           HEADER
        ========================= */

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
            gap: 12px;
            min-width: 0;
        }

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
            transition: all 0.25s ease;
        }

        .header-title {
            min-width: 0;
        }

        .header-title h1 {
            font-size: 16px;
            font-weight: 700;
            color: #252A86;
            line-height: 1.3;
        }

        .header-title p {
            margin-top: 2px;
            font-size: 11px;
            color: #8b93a7;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .admin-info {
            text-align: right;
        }

        .admin-info strong {
            display: block;
            font-size: 12px;
            color: #252A86;
        }

        .admin-info span {
            display: block;
            margin-top: 2px;
            font-size: 10px;
            color: #8b93a7;
        }

        .avatar {
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

        /* =========================
           CONTENT
        ========================= */

        .content {
            padding: 30px;
        }

        .page-kicker {
            color: #252A86;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .page-title {
            font-size: 26px;
            font-weight: 700;
            color: #20233b;
            margin-bottom: 6px;
        }

        .page-description {
            font-size: 13px;
            color: #7b8498;
            margin-bottom: 24px;
        }

        /* =========================
           MASTER TABS
        ========================= */

        .master-tabs {
            display: flex;
            align-items: center;
            gap: 5px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 9px;
            padding: 5px;
            width: fit-content;
            margin-bottom: 20px;
        }

        .master-tab {
            border: none;
            background: transparent;
            color: #6b7280;
            padding: 10px 18px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .master-tab:hover {
            color: #252A86;
            background: #f1f2f8;
        }

        .master-tab.active {
            background: #252A86;
            color: #ffffff;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* =========================
           FORM CARD
        ========================= */

        .form-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 24px;
            margin-bottom: 20px;
        }

        .form-card-title {
            margin-bottom: 20px;
        }

        .form-card-title h3 {
            font-size: 15px;
            color: #252A86;
            margin-bottom: 5px;
        }

        .form-card-title p {
            font-size: 12px;
            color: #8b93a7;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 17px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 7px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            border: 1px solid #dfe2ea;
            border-radius: 7px;
            padding: 10px 12px;
            font-family: inherit;
            font-size: 12px;
            color: #374151;
            outline: none;
            background: #ffffff;
            transition: border 0.2s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #252A86;
        }

        .form-group textarea {
            min-height: 85px;
            resize: vertical;
        }

        /* =========================
           TABLE CARD
        ========================= */

        .table-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .table-card-header {
            padding: 18px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .table-card-title {
            min-width: 0;
        }

        .table-card-title h3 {
            font-size: 14px;
            color: #252A86;
            margin-bottom: 4px;
        }

        .table-card-title p {
            font-size: 11px;
            color: #8b93a7;
        }

        .table-header-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            flex-shrink: 0;
        }

        /* =========================
           SEARCH
        ========================= */

        .table-search {
            position: relative;
            width: 210px;
            flex-shrink: 0;
        }

        .table-search input {
            width: 100%;
            height: 38px;
            padding: 0 12px 0 34px;
            border: 1px solid #dfe2ea;
            border-radius: 7px;
            outline: none;
            font-size: 12px;
            color: #374151;
            background: #ffffff;
        }

        .table-search input:focus {
            border-color: #252A86;
        }

        .table-search-icon {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #8b93a7;
            font-size: 15px;
            pointer-events: none;
        }

        /* =========================
           BUTTON TAMBAH
        ========================= */

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            height: 38px;
            padding: 0 15px;
            background: #252A86;
            color: #ffffff;
            border: 1px solid #252A86;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: #1d216d;
            border-color: #1d216d;
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(37, 42, 134, 0.18);
        }

        .btn-plus {
            font-size: 16px;
            line-height: 1;
            font-weight: 400;
        }

        /* =========================
           TABLE
        ========================= */

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 1250px;
            border-collapse: collapse;
        }

        thead th {
            background: #f8f9fc;
            color: #5d6578;
            font-size: 11px;
            font-weight: 700;
            text-align: left;
            padding: 13px 15px;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }

        tbody td {
            padding: 13px 15px;
            border-bottom: 1px solid #eef0f4;
            color: #4b5563;
            font-size: 11px;
            vertical-align: middle;
            white-space: nowrap;
        }

        tbody tr:hover {
            background: #fafbfe;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .empty-row {
            text-align: center;
            color: #9ca3af;
            padding: 30px 15px !important;
        }

        /* =========================
           ACTION BUTTON
        ========================= */

        .action-buttons {
            display: flex;
            align-items: center;
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
            white-space: nowrap;
        }

        .btn-edit {
            background: #eef0ff;
            color: #252A86;
        }

        .btn-edit:hover {
            background: #e0e3ff;
        }

        .btn-delete {
            background: #fff0f0;
            color: #dc2626;
        }

        .btn-delete:hover {
            background: #ffe0e0;
        }

        /* =========================
           OVERLAY
        ========================= */

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 999;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* =========================
           RESPONSIVE 900
        ========================= */

        @media (max-width: 900px) {

            .sidebar {
                width: 270px;
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main {
                margin-left: 0;
                width: 100%;
            }

            .mobile-menu-btn {
                display: flex;
            }

            .header {
                height: 70px;
                padding: 0 20px;
            }

            .content {
                padding: 20px;
            }

            .header-title h1 {
                font-size: 15px;
            }

            .header-title p {
                display: none;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: auto;
            }
        }

        /* =========================
           RESPONSIVE 600
        ========================= */

        @media (max-width: 600px) {

            .header {
                height: 64px;
                padding: 0 14px;
            }

            .mobile-menu-btn {
                width: 38px;
                height: 38px;
            }

            .mobile-menu-btn span {
                width: 18px;
            }

            .header-title h1 {
                font-size: 13px;
            }

            .header-right {
                gap: 7px;
            }

            .admin-info {
                display: none;
            }

            .avatar {
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
                line-height: 1.5;
            }

            .master-tabs {
                width: 100%;
                overflow-x: auto;
            }

            .master-tab {
                flex: 1;
                min-width: 100px;
                padding: 9px 12px;
                font-size: 11px;
            }

            .form-card {
                padding: 16px;
            }

            .table-card-header {
                flex-direction: column;
                align-items: stretch;
                gap: 14px;
                padding: 16px;
            }

            .table-header-actions {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
            }

            .table-search {
                width: 100%;
                min-width: 0;
            }

            .btn-primary {
                width: 100%;
            }
        }

        /* =========================
           RESPONSIVE 400
        ========================= */

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

            .header-title h1 {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>

    <!-- =========================
         SIDEBAR
    ========================= -->

    <aside class="sidebar" id="sidebar">

        <div class="sidebar-logo">
            <img src="{{ asset('images/dinsos.png') }}" alt="Logo Dinsos">

            <div class="sidebar-logo-text">
                Sistem Pendataan<br>
                Dinas Sosial Kota Pasuruan
            </div>
        </div>

        <nav class="sidebar-menu">

            <div class="menu-title">Menu Utama</div>

            <a href="{{ url('/dashboard') }}">
                <span class="menu-icon">⌂</span>
                Dashboard
            </a>

            <a href="{{ url('/periode') }}">
                <span class="menu-icon">▣</span>
                Periode
            </a>

            <a href="#">
                <span class="menu-icon">♙</span>
                Petugas
            </a>

            <a href="#">
                <span class="menu-icon">☷</span>
                Responden
            </a>

            <a href="#">
                <span class="menu-icon">☑</span>
                Kuisioner
            </a>

            <a href="#">
                <span class="menu-icon">✓</span>
                Verifikasi
            </a>

            <a href="#">
                <span class="menu-icon">◫</span>
                Monitoring
            </a>

            <a href="#">
                <span class="menu-icon">▤</span>
                Laporan
            </a>

            <a href="{{ route('master.index') }}" class="active">
                <span class="menu-icon">⚙</span>
                Master
            </a>

            <div class="menu-title">Akun</div>

            <a href="#">
                <span class="menu-icon">↪</span>
                Logout
            </a>

        </nav>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- =========================
         MAIN
    ========================= -->

    <main class="main">

        <!-- HEADER -->

        <header class="header">

            <div class="header-left">

                <button
                    type="button"
                    class="mobile-menu-btn"
                    id="mobileMenuBtn"
                    aria-label="Buka menu"
                    aria-expanded="false"
                >
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <div class="header-title">
                    <h1>Sistem Pendataan Dinas Sosial Kota Pasuruan</h1>
                    <p>Panel Administrasi</p>
                </div>

            </div>

            <div class="header-right">

                <div class="admin-info">
                    <strong>Operator</strong>
                    <span>Admin</span>
                </div>

                <div class="avatar">
                    A
                </div>

            </div>

        </header>

        <!-- CONTENT -->

        <section class="content">

            <div class="page-kicker">
                ADMIN
            </div>

            <h2 class="page-title">
                Master
            </h2>

            <p class="page-description">
                Kelola data operator, verifikator, dan petugas dalam sistem pendataan.
            </p>

            <!-- =========================
                 TABS
            ========================= -->

            <div class="master-tabs">

                <button
                    type="button"
                    class="master-tab active"
                    onclick="showTab('operator', this)"
                >
                    Operator
                </button>

                <button
                    type="button"
                    class="master-tab"
                    onclick="showTab('verifikator', this)"
                >
                    Verifikator
                </button>

                <button
                    type="button"
                    class="master-tab"
                    onclick="showTab('petugas', this)"
                >
                    Petugas
                </button>

            </div>

            <!-- ==================================================
                 OPERATOR
            ================================================== -->

            <div class="tab-content active" id="operator">

                <div class="table-card">

                    <div class="table-card-header">

                        <div class="table-card-title">
                            <h3>Data Operator</h3>
                            <p>
                                Daftar operator yang terdaftar dalam sistem.
                            </p>
                        </div>

                        <div class="table-header-actions">

                            <div class="table-search">

                                <span class="table-search-icon">
                                    ⌕
                                </span>

                                <input
                                    type="text"
                                    id="searchOperator"
                                    placeholder="Cari operator..."
                                    onkeyup="searchTable('searchOperator', 'operatorTable')"
                                >

                            </div>

                            <a
                                href="{{ route('master.operator.create') }}"
                                class="btn-primary"
                            >
                                <span class="btn-plus">+</span>
                                Tambah Operator
                            </a>

                        </div>

                    </div>

                    <div class="table-wrapper">

                        <table id="operatorTable">

                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>NIK</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tempat Lahir</th>
                                    <th>Tanggal Lahir</th>
                                    <th>No. HP</th>
                                    <th>Email</th>
                                    <th>Alamat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                            <tr>
                                <td>2</td>
                                <td>Siti Aminah</td>
                                <td>3575010202020002</td>
                                <td>Perempuan</td>
                                <td>Pasuruan</td>
                                <td>22 Mei 1999</td>
                                <td>082234567891</td>
                                <td>siti.aminah@gmail.com</td>
                                <td>Jl. Soekarno Hatta, Pasuruan</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('master.operator.edit', 1) }}" class="btn-edit">
                                            Edit
                                        </a>

                                        <button type="button" class="btn-delete">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>

                        </tbody>

                        </table>

                    </div>

                </div>

            </div>

            <!-- ==================================================
                 VERIFIKATOR
            ================================================== -->

            <div class="tab-content" id="verifikator">

                <div class="table-card">

                    <div class="table-card-header">

                        <div class="table-card-title">
                            <h3>Data Verifikator</h3>
                            <p>
                                Daftar verifikator yang terdaftar dalam sistem.
                            </p>
                        </div>

                        <div class="table-header-actions">

                            <div class="table-search">

                                <span class="table-search-icon">
                                    ⌕
                                </span>

                                <input
                                    type="text"
                                    id="searchVerifikator"
                                    placeholder="Cari verifikator..."
                                    onkeyup="searchTable('searchVerifikator', 'verifikatorTable')"
                                >

                            </div>

                            <a
                                href="{{ route('master.verifikator.create') }}"
                                class="btn-primary"
                            >
                                <span class="btn-plus">+</span>
                                Tambah Verifikator
                            </a>

                        </div>

                    </div>

                    <div class="table-wrapper">

                        <table id="verifikatorTable">

                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>NIK</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tempat Lahir</th>
                                    <th>Tanggal Lahir</th>
                                    <th>No. HP</th>
                                    <th>Email</th>
                                    <th>Alamat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr>
                                    <td>1</td>
                                    <td>Budi Santoso</td>
                                    <td>3575030303030003</td>
                                    <td>Laki-laki</td>
                                    <td>Pasuruan</td>
                                    <td>15 Maret 1997</td>
                                    <td>083345678901</td>
                                    <td>budi.santoso@gmail.com</td>
                                    <td>Jl. Hayam Wuruk, Pasuruan</td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('master.verifikator.edit', 1) }}" class="btn-edit">
                                                Edit
                                            </a>

                                            <button type="button" class="btn-delete">
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

            <!-- ==================================================
                 PETUGAS
            ================================================== -->

            <div class="tab-content" id="petugas">

                <div class="table-card">

                    <div class="table-card-header">

                        <div class="table-card-title">
                            <h3>Data Petugas</h3>
                            <p>
                                Daftar petugas yang terdaftar dalam sistem.
                            </p>
                        </div>

                        <div class="table-header-actions">

                            <div class="table-search">

                                <span class="table-search-icon">
                                    ⌕
                                </span>

                                <input
                                    type="text"
                                    id="searchPetugas"
                                    placeholder="Cari petugas..."
                                    onkeyup="searchTable('searchPetugas', 'petugasTable')"
                                >

                            </div>

                        </div>

                    </div>

                    <div class="table-wrapper">

                        <table id="petugasTable">

                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Lengkap</th>
                                    <th>NIK</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tempat Lahir</th>
                                    <th>Tanggal Lahir</th>
                                    <th>No. HP</th>
                                    <th>Email</th>
                                    <th>Alamat</th>
                                    <th>Wilayah</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr>
                                    <td colspan="10" class="empty-row">
                                        Belum ada data petugas.
                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </section>

    </main>

    <!-- =========================
         JAVASCRIPT
    ========================= -->

    <script>

        /* =========================
           TAB MASTER
        ========================= */

        function showTab(tabName, button) {

            const tabs = document.querySelectorAll('.tab-content');

            tabs.forEach(function(tab) {
                tab.classList.remove('active');
            });

            const buttons = document.querySelectorAll('.master-tab');

            buttons.forEach(function(btn) {
                btn.classList.remove('active');
            });

            const selectedTab = document.getElementById(tabName);

            if (selectedTab) {
                selectedTab.classList.add('active');
            }

            if (button) {
                button.classList.add('active');
            }
        }


        /* =========================
           SEARCH TABLE
        ========================= */

        function searchTable(inputId, tableId) {

            const input = document.getElementById(inputId);

            if (!input) {
                return;
            }

            const filter = input.value.toLowerCase();

            const table = document.getElementById(tableId);

            if (!table) {
                return;
            }

            const tbody = table.querySelector('tbody');

            if (!tbody) {
                return;
            }

            const rows = tbody.getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {

                const rowText = rows[i].textContent.toLowerCase();

                if (rowText.indexOf(filter) > -1) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }

            }
        }


        /* =========================
           MOBILE SIDEBAR
        ========================= */

        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');


        function openSidebar() {

            if (!sidebar || !sidebarOverlay || !mobileMenuBtn) {
                return;
            }

            sidebar.classList.add('active');
            sidebarOverlay.classList.add('active');

            mobileMenuBtn.setAttribute('aria-expanded', 'true');
        }


        function closeSidebar() {

            if (!sidebar || !sidebarOverlay || !mobileMenuBtn) {
                return;
            }

            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');

            mobileMenuBtn.setAttribute('aria-expanded', 'false');
        }


        if (mobileMenuBtn) {

            mobileMenuBtn.addEventListener('click', function() {

                if (sidebar.classList.contains('active')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }

            });

        }


        if (sidebarOverlay) {

            sidebarOverlay.addEventListener('click', function() {
                closeSidebar();
            });

        }


        const sidebarLinks = document.querySelectorAll('.sidebar-menu a');

        sidebarLinks.forEach(function(link) {

            link.addEventListener('click', function() {

                if (window.innerWidth <= 900) {
                    closeSidebar();
                }

            });

        });


        window.addEventListener('resize', function() {

            if (window.innerWidth > 900) {
                closeSidebar();
            }

        });

    </script>

</body>

</html>