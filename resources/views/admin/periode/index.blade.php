<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Periode | Sistem Pendataan Dinas Sosial Kota Pasuruan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        body {
            background: #f5f6fa;
            color: #252525;
            min-height: 100vh;
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
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #6670d8;
            border-radius: 10px;
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

        .main {
            margin-left: 260px;
            min-height: 100vh;
            width: calc(100% - 260px);
            display: flex;
            flex-direction: column;
        }

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

        .content {
            padding: 30px;
        }

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

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 22px;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 18px;
            border-radius: 10px;
            background: #252A86;
            color: #ffffff;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: .2s ease;
        }

        .btn-primary:hover {
            background: #1e236f;
        }

        .card {
            background: #ffffff;
            border: 1px solid #e7e8ee;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            padding: 22px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 12px;
            font-weight: 700;
            color: #495057;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            height: 42px;
            border: 1px solid #dfe1e8;
            border-radius: 8px;
            padding: 0 12px;
            font-size: 13px;
            outline: none;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #252A86;
            box-shadow: 0 0 0 3px rgba(37,42,134,0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background: #f8f8fb;
            color: #666;
            font-size: 11px;
            font-weight: 700;
            text-align: left;
            padding: 13px 14px;
            border-bottom: 1px solid #e7e8ee;
        }

        td {
            padding: 14px;
            font-size: 12px;
            color: #444;
            border-bottom: 1px solid #eeeeF3;
            vertical-align: middle;
        }

        .status {
            display: inline-flex;
            align-items: center;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-aktif {
            background: #e8f7ee;
            color: #21864a;
        }

        .status-selesai {
            background: #fff7df;
            color: #a87900;
        }

        .action-group {
            display: flex;
            gap: 8px;
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
        }

        .btn-edit {
            background: #fff8e5;
            color: #a47a00;
            border-color: #f3e4b5;
        }

        .btn-delete {
            background: #fdecec;
            color: #c74343;
            border-color: #f7dada;
        }

        @media (max-width: 900px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main {
                margin-left: 0;
                width: 100%;
            }

            .header {
                padding: 0 20px;
            }

            .sidebar-menu {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                padding: 12px;
            }

            .menu-link {
                flex: 1 1 calc(50% - 8px);
                min-width: 150px;
                margin-bottom: 0;
            }

            .filter-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 700px) {
            .sidebar-logo {
                height: auto;
                padding: 16px 18px;
            }

            .sidebar-logo-text {
                font-size: 13px;
                line-height: 1.4;
            }

            .menu-link {
                flex: 1 1 100%;
                min-width: 0;
            }

            .header {
                position: relative;
                min-height: 70px;
                height: auto;
                padding: 12px 16px;
            }

            .header-left {
                width: 100%;
            }

            .header-title {
                font-size: 15px;
                line-height: 1.4;
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

            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-primary {
                width: 100%;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('images/dinsos.png') }}" alt="Logo Dinas Sosial">
            <div class="sidebar-logo-text">
                Sistem Pendataan<br>
                Dinas Sosial Kota Pasuruan
            </div>
        </div>

        <nav class="sidebar-menu">
            <div class="menu-title">Menu Utama</div>

            <a href="/dashboard" class="menu-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <span class="menu-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect></svg>
                </span>
                <span>Dashboard</span>
            </a>

            <a href="/periode" class="menu-link {{ request()->is('periode*') ? 'active' : '' }}">
                <span class="menu-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="17" rx="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </span>
                <span>Periode</span>
            </a>

            <a href="#" class="menu-link">
                <span class="menu-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </span>
                <span>Petugas</span>
            </a>

            <a href="#" class="menu-link">
                <span class="menu-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </span>
                <span>Responden</span>
            </a>

            <a href="#" class="menu-link">
                <span class="menu-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="8" y1="13" x2="16" y2="13"></line><line x1="8" y1="17" x2="16" y2="17"></line></svg>
                </span>
                <span>Kuisioner</span>
            </a>

            <a href="{{ route('verifikasi.index') }}" class="menu-link {{ request()->is('verifikasi*') ? 'active' : '' }}">
                <span class="menu-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                </span>
                <span>Verifikasi</span>
            </a>

            <a href="/monitoring" class="menu-link {{ request()->is('monitoring*') ? 'active' : '' }}">
                <span class="menu-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 3 3 21 21 21"></polyline><polyline points="7 16 11 12 14 15 21 8"></polyline></svg>
                </span>
                <span>Monitoring</span>
            </a>

            <a href="#" class="menu-link">
                <span class="menu-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="8" y1="13" x2="16" y2="13"></line><line x1="8" y1="17" x2="16" y2="17"></line></svg>
                </span>
                <span>Laporan</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="/logout" class="logout-link">
                <span class="menu-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                </span>
                <span>Keluar</span>
            </a>
        </div>
    </aside>

    <div class="main">
        <header class="header">
            <div class="header-left">
                <img src="{{ asset('images/logopemerinta.jpg') }}" alt="Logo Pemerintah" class="header-logo">
                <div>
                    <div class="header-title">Sistem Pendataan Dinas Sosial Kota Pasuruan</div>
                    <div class="header-subtitle">Panel Administrasi</div>
                </div>
            </div>

            <div class="admin-profile">
                <div class="admin-info">
                    <div class="admin-name">Administrator</div>
                    <div class="admin-role">Admin</div>
                </div>
                <div class="admin-avatar">A</div>
            </div>
        </header>

        <main class="content">
            <div class="page-header">
                <div class="page-kicker">ADMIN</div>
                <h1 class="page-title">Manajemen Periode</h1>
                <p class="page-description">Kelola periode pendataan dan jadwal kegiatan secara terstruktur.</p>
            </div>

            <div class="toolbar">
                <div></div>
                <a href="/periode/tambah" class="btn-primary">+ Tambah Periode</a>
            </div>

            <div class="card">
                <div class="filter-grid">
                    <div class="form-group">
                        <label>Nama Kegiatan</label>
                        <input type="text" placeholder="Cari nama kegiatan">
                    </div>
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date">
                    </div>
                    <div class="form-group">
                        <label>Bulan</label>
                        <select>
                            <option>Semua Bulan</option>
                            <option>Januari</option>
                            <option>Februari</option>
                            <option>Maret</option>
                            <option>April</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select>
                            <option>Semua Status</option>
                            <option>Aktif</option>
                            <option>Selesai</option>
                        </select>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Kegiatan</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Pendataan Awal 2026</td>
                            <td>01 Januari 2026</td>
                            <td>31 Januari 2026</td>
                            <td><span class="status status-aktif">Aktif</span></td>
                            <td>
                                <div class="action-group">
                                    <a href="#" class="btn-action btn-edit">Edit</a>
                                    <a href="#" class="btn-action btn-delete">Hapus</a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Verifikasi Data</td>
                            <td>10 Februari 2026</td>
                            <td>25 Februari 2026</td>
                            <td><span class="status status-selesai">Selesai</span></td>
                            <td>
                                <div class="action-group">
                                    <a href="#" class="btn-action btn-edit">Edit</a>
                                    <a href="#" class="btn-action btn-delete">Hapus</a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>

}

</style>

</head>


<body>



<!-- SIDEBAR -->

<div class="sidebar">


<div class="logo">


<div class="logo-icon">
🏛️
</div>


Sistem Pendataan

<br>

Sensus


</div>



<a href="/dashboard">
Dashboard
</a>


<a href="/periode">
Periode
</a>


<a>
Petugas
</a>


<a>
Responden
</a>


<a>
Kuisioner
</a>


<a>
Verifikasi
</a>


<a>
Monitoring
</a>


<a>
Laporan
</a>


<a>
Master
</a>


</div>




<!-- MAIN -->

<div class="main">


<div class="header">

👤 Admin

</div>



<div class="content">



<h1 class="title">

Manajemen Periode

</h1>


<a href="/periode/tambah" class="btn-tambah">

+ Tambah Periode

</a>




<div class="card">


<h3>
Filter Periode
</h3>


<br>


<div class="filter">


<div>

<label>
Nama Kegiatan
</label>

<input type="text" placeholder="Cari nama kegiatan">

</div>



<div>

<label>
Tanggal
</label>

<input type="date">

</div>



<div>

<label>
Bulan
</label>


<select>

<option>Pilih Bulan</option>
<option>Januari</option>
<option>Februari</option>
<option>Maret</option>
<option>September</option>

</select>

</div>




<div>

<label>
Tahun
</label>

<select>

<option>2026</option>
<option>2027</option>

</select>


</div>


</div>


</div>





<div class="card">


<h3>
Daftar Periode
</h3>



<table>


<thead>

<tr>

<th>No</th>
<th>Nama Kegiatan</th>
<th>Tanggal Mulai</th>
<th>Tanggal Selesai</th>
<th>Status</th>
<th>Aksi</th>

</tr>

</thead>



<tbody>


<tr>


<td>1</td>

<td>
Pendataan Penduduk September 2026
</td>


<td>
01 September 2026
</td>


<td>
07 September 2026
</td>


<td>

<span class="status-aktif">
Aktif
</span>

</td>


<td>

<a href="/periode/edit" class="btn-edit">
Edit
</a>


<a href="#" class="btn-hapus">
Hapus
</a>

</td>


</tr>



<tr>


<td>2</td>


<td>
Pendataan Penduduk Agustus 2026
</td>


<td>
01 Agustus 2026
</td>


<td>
07 Agustus 2026
</td>


<td>

<span class="status-selesai">
Selesai
</span>

</td>


<td>

<a href="/periode/edit" class="btn-edit">
Edit
</a>


<a href="#" class="btn-hapus">
Hapus
</a>


</td>


</tr>


</tbody>


</table>


</div>



</div>


</div>


</body>

</html>