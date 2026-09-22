<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Operator | Sistem Pendataan Dinas Sosial Kota Pasuruan</title>

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
           FORM CARD
        ========================= */

        .form-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 24px;
            max-width: 100%;
        }

        .form-card-title {
            margin-bottom: 22px;
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
            gap: 18px;
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
            min-height: 90px;
            resize: vertical;
        }

        /* =========================
           BUTTON
        ========================= */

        .form-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eef0f4;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 40px;
            padding: 0 18px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-secondary {
            background: #f1f2f6;
            color: #5f6678;
            border: 1px solid #e1e4eb;
        }

        .btn-secondary:hover {
            background: #e7e9ef;
        }

        .btn-primary {
            background: #252A86;
            color: #ffffff;
            border: 1px solid #252A86;
        }

        .btn-primary:hover {
            background: #1d216d;
            border-color: #1d216d;
            box-shadow: 0 3px 8px rgba(37, 42, 134, 0.18);
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

            .form-card {
                padding: 16px;
            }

            .form-actions {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .btn {
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

            <img
                src="{{ asset('images/dinsos.png') }}"
                alt="Logo Dinsos"
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

            <div class="menu-title">
                Akun
            </div>

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

                    <h1>
                        Sistem Pendataan Dinas Sosial Kota Pasuruan
                    </h1>

                    <p>
                        Panel Administrasi
                    </p>

                </div>

            </div>

            <div class="header-right">

                <div class="admin-info">

                    <strong>
                        Operator
                    </strong>

                    <span>
                        Admin
                    </span>

                </div>

                <div class="avatar">
                    A
                </div>

            </div>

        </header>

        <!-- =========================
             CONTENT
        ========================= -->

        <section class="content">

            <div class="page-kicker">
                MASTER
            </div>

            <h2 class="page-title">
                Edit Operator
            </h2>

            <p class="page-description">
                Perbarui data operator yang terdaftar dalam sistem.
            </p>

            <!-- =========================
                 FORM EDIT
            ========================= -->

            <div class="form-card">

                <div class="form-card-title">

                    <h3>
                        Form Edit Operator
                    </h3>

                    <p>
                        Silakan perbarui informasi operator pada formulir berikut.
                    </p>

                </div>

                <form
                    onsubmit="simpanPerubahan(event)"
                >

                    <div class="form-grid">

                        <!-- NAMA -->

                        <div class="form-group">

                            <label for="nama_lengkap">
                                Nama Lengkap
                            </label>

                            <input
                                type="text"
                                id="nama_lengkap"
                                name="nama_lengkap"
                                value="Ahmad Fauzi"
                                required
                            >

                        </div>

                        <!-- NIK -->

                        <div class="form-group">

                            <label for="nik">
                                NIK
                            </label>

                            <input
                                type="text"
                                id="nik"
                                name="nik"
                                value="3575010101010001"
                                maxlength="16"
                                required
                            >

                        </div>

                        <!-- JENIS KELAMIN -->

                        <div class="form-group">

                            <label for="jenis_kelamin">
                                Jenis Kelamin
                            </label>

                            <select
                                id="jenis_kelamin"
                                name="jenis_kelamin"
                                required
                            >

                                <option value="">
                                    Pilih Jenis Kelamin
                                </option>

                                <option value="Laki-laki" selected>
                                    Laki-laki
                                </option>

                                <option value="Perempuan">
                                    Perempuan
                                </option>

                            </select>

                        </div>

                        <!-- TEMPAT LAHIR -->

                        <div class="form-group">

                            <label for="tempat_lahir">
                                Tempat Lahir
                            </label>

                            <input
                                type="text"
                                id="tempat_lahir"
                                name="tempat_lahir"
                                value="Pasuruan"
                                required
                            >

                        </div>

                        <!-- TANGGAL LAHIR -->

                        <div class="form-group">

                            <label for="tanggal_lahir">
                                Tanggal Lahir
                            </label>

                            <input
                                type="date"
                                id="tanggal_lahir"
                                name="tanggal_lahir"
                                value="1998-01-10"
                                required
                            >

                        </div>

                        <!-- NO HP -->

                        <div class="form-group">

                            <label for="no_hp">
                                No. HP
                            </label>

                            <input
                                type="tel"
                                id="no_hp"
                                name="no_hp"
                                value="081234567890"
                                required
                            >

                        </div>

                        <!-- EMAIL -->

                        <div class="form-group">

                            <label for="email">
                                Email
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="ahmad.fauzi@gmail.com"
                                required
                            >

                        </div>

                        <!-- ALAMAT -->

                        <div class="form-group full">

                            <label for="alamat">
                                Alamat
                            </label>

                            <textarea
                                id="alamat"
                                name="alamat"
                                required
                            >Jl. Panglima Sudirman, Pasuruan</textarea>

                        </div>

                    </div>

                    <!-- BUTTON -->

                    <div class="form-actions">

                        <a
                            href="{{ route('master.index') }}"
                            class="btn btn-secondary"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            Simpan Perubahan
                        </button>

                    </div>

                </form>

            </div>

        </section>

    </main>

    <!-- =========================
         JAVASCRIPT
    ========================= -->

    <script>

        /* =========================
           SIMPAN PERUBAHAN
        ========================= */

        function simpanPerubahan(event) {

            event.preventDefault();

            alert('Perubahan data operator berhasil disimpan.');

            window.location.href = "{{ route('master.index') }}?tab=operator";
        }


        /* =========================
           MOBILE SIDEBAR
        ========================= */

        const sidebar =
            document.getElementById('sidebar');

        const sidebarOverlay =
            document.getElementById('sidebarOverlay');

        const mobileMenuBtn =
            document.getElementById('mobileMenuBtn');


        function openSidebar() {

            sidebar.classList.add('active');

            sidebarOverlay.classList.add('active');

            mobileMenuBtn.setAttribute(
                'aria-expanded',
                'true'
            );
        }


        function closeSidebar() {

            sidebar.classList.remove('active');

            sidebarOverlay.classList.remove('active');

            mobileMenuBtn.setAttribute(
                'aria-expanded',
                'false'
            );
        }


        mobileMenuBtn.addEventListener(
            'click',
            function() {

                if (
                    sidebar.classList.contains('active')
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


        const sidebarLinks =
            document.querySelectorAll(
                '.sidebar-menu a'
            );


        sidebarLinks.forEach(function(link) {

            link.addEventListener(
                'click',
                function() {

                    if (window.innerWidth <= 900) {
                        closeSidebar();
                    }

                }
            );

        });


        window.addEventListener(
            'resize',
            function() {

                if (window.innerWidth > 900) {
                    closeSidebar();
                }

            }
        );

    </script>

</body>

</html>