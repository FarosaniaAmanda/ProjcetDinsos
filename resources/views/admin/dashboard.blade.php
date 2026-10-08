<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Sistem Pendataan Perlindungan Dinas Sosial</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

        .sidebar-menu {
            padding: 20px 14px;
            flex: 1;
        }

        .menu-title {
            font-size: 11px;
            font-weight: 700;
            color: rgba(255,255,255,.55);
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
            color: rgba(255,255,255,.82);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all .2s ease;
        }

        .menu-link:hover {
            background: rgba(255,255,255,.10);
            color: #ffffff;
        }

        .menu-link.active {
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
        }

        .menu-icon svg {
            width: 18px;
            height: 18px;
            display: block;
        }


        /* =====================================================
           MASTER
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
           FOOTER
        ====================================================== */

        .sidebar-footer {
            padding: 15px 14px;
            border-top: 1px solid rgba(255,255,255,.12);
            flex-shrink: 0;
        }

        .logout-form {
            width: 100%;
        }

        .logout-link {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 12px 14px;
            border: none;
            border-radius: 9px;
            background: transparent;
            color: rgba(255,255,255,.85);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            font-family: inherit;
            text-align: left;
            cursor: pointer;
            transition: all .2s ease;
        }

        .logout-link:hover {
            background: rgba(255,255,255,.10);
            color: #ffffff;
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
            transition:
                background-color .25s ease,
                transform .25s ease,
                box-shadow .25s ease;
        }

        .header-admin:hover {
            background: #f3f4f6;
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(0,0,0,.06);
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
            flex-shrink: 0;
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
           WELCOME
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
            background: rgba(255,255,255,.05);
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
            color: rgba(255,255,255,.78);
            line-height: 1.6;
            position: relative;
            z-index: 2;
        }


        /* =====================================================
           STATISTICS
        ====================================================== */

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0,1fr));
            gap: 16px;
            margin-bottom: 22px;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 9px;
            padding: 18px;
            min-width: 0;
            transition: .2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 18px rgba(37,42,134,.08);
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
           GRAFIK
        ====================================================== */

        .dashboard-chart-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.10fr) minmax(430px, .90fr);
            gap: 20px;
            margin-top: 24px;
            align-items: start;
        }

        .chart-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 22px;
            margin-top: 0;
            height: 350px;
            box-sizing: border-box;
            overflow: hidden;
        }

        .chart-header {
            margin-bottom: 20px;
        }

        .chart-title {
            font-size: 15px;
            font-weight: 700;
            color: #252A86;
            margin-bottom: 5px;
        }

        .chart-description {
            font-size: 11px;
            color: #9ca3af;
            line-height: 1.5;
        }

        .chart-container {
            position: relative;
            width: 100%;
            height: 245px;
            padding: 0 6px;
        }


        /* =====================================================
           VERIFIKASI
        ====================================================== */

        .verification-chart-container {
            width: 100%;
            height: 250px;
            position: relative;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 18px;
            padding: 0 8px;
            box-sizing: border-box;
            overflow: visible;
        }

        .verification-chart {
            width: 175px;
            height: 175px;
            position: relative;
            flex: 0 0 175px;
        }

        .verification-legend {
            display: flex;
            flex-direction: column;
            gap: 14px;
            width: 175px;
            min-width: 175px;
            box-sizing: border-box;
        }

        .verification-legend-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            width: 100%;
            font-size: 11px;
            line-height: 1.3;
            color: #555;
            white-space: nowrap;
        }

        .verification-legend-left {
            display: flex;
            align-items: center;
            gap: 7px;
            min-width: 0;
        }

        .verification-dot {
            width: 11px;
            height: 11px;
            min-width: 11px;
            border-radius: 50%;
            display: inline-block;
        }

        .verification-approved {
            background: #252A86;
        }

        .verification-submitted {
            background: #8A96E8;
        }

        .verification-rejected {
            background: #d9534f;
        }

        .verification-legend-item strong {
            font-size: 11px;
            font-weight: 500;
            color: #252A86;
        }


        /* =====================================================
           CARD TABEL
        ====================================================== */

        .questionnaire-card,
        .period-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 22px;
            margin-top: 20px;
            overflow: hidden;
        }

        .questionnaire-header,
        .period-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 18px;
        }

        .questionnaire-title,
        .period-title {
            font-size: 15px;
            font-weight: 700;
            color: #252A86;
            margin-bottom: 5px;
        }

        .questionnaire-description,
        .period-description {
            font-size: 11px;
            color: #9ca3af;
            line-height: 1.5;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .questionnaire-table,
        .period-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        }


        /* =====================================================
           POSISI KOLOM REKAP PERIODE
        ====================================================== */

        .period-table {
            table-layout: fixed;
        }

        .period-table th:nth-child(1),
        .period-table td:nth-child(1) {
            width: 5%;
        }

        .period-table th:nth-child(2),
        .period-table td:nth-child(2) {
            width: 18%;
        }

        .period-table th:nth-child(3),
        .period-table td:nth-child(3) {
            width: 13%;
        }

        .period-table th:nth-child(4),
        .period-table td:nth-child(4) {
            width: 13%;
        }

        .period-table th:nth-child(5),
        .period-table td:nth-child(5) {
            width: 13%;
        }

        .period-table th:nth-child(6),
        .period-table td:nth-child(6) {
            width: 13%;
        }

        .period-table th:nth-child(7),
        .period-table td:nth-child(7) {
            width: 13%;
        }

        .period-table th:nth-child(8),
        .period-table td:nth-child(8) {
            width: 12%;
        }


        .questionnaire-table thead th,
        .period-table thead th {
            background: #f7f8fc;
            color: #252A86;
            font-size: 11px;
            font-weight: 700;
            text-align: left;
            padding: 12px 14px;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }

        .questionnaire-table tbody td,
        .period-table tbody td {
            padding: 13px 14px;
            font-size: 11px;
            color: #4b5563;
            border-bottom: 1px solid #f0f1f5;
            vertical-align: middle;
        }

        .questionnaire-table tbody tr:last-child td,
        .period-table tbody tr:last-child td {
            border-bottom: none;
        }

        .questionnaire-table tbody tr:hover,
        .period-table tbody tr:hover {
            background: #fafbff;
        }


        /* =====================================================
           STATUS
        ====================================================== */

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-selesai {
            background: #eaf7ef;
            color: #21874b;
        }

        .status-proses {
            background: #fff5dc;
            color: #a36b00;
        }

        .status-belum {
            background: #f1f2f5;
            color: #6b7280;
        }

        .status-aktif {
            background: #e8edff;
            color: #252A86;
        }

        .status-disetujui {
            background: #eaf7ef;
            color: #21874b;
        }

        .status-diajukan {
            background: #fff5dc;
            color: #a36b00;
        }

        .status-ditolak {
            background: #fdecec;
            color: #c0392b;
        }


        /* =====================================================
           PAGINATION
        ====================================================== */

        .pagination-area {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px solid #f0f1f5;
        }

        .pagination-info {
            font-size: 10px;
            color: #9ca3af;
        }

        .pagination-buttons {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 5px;
            flex-wrap: wrap;
        }

        .pagination-button {
            min-width: 31px;
            height: 31px;
            padding: 0 9px;
            border: 1px solid #e1e4ec;
            background: #ffffff;
            color: #252A86;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s ease;
        }

        .pagination-button:hover:not(:disabled) {
            background: #f1f3ff;
            border-color: #252A86;
        }

        .pagination-button.active {
            background: #252A86;
            border-color: #252A86;
            color: #ffffff;
        }

        .pagination-button:disabled {
            opacity: .45;
            cursor: not-allowed;
        }

        .empty-table {
            text-align: center !important;
            padding: 25px !important;
            color: #9ca3af !important;
        }


        /* =====================================================
           RESPONSIVE
        ====================================================== */

        @media (max-width: 1100px) {

            .stats-grid {
                grid-template-columns: repeat(2,minmax(0,1fr));
            }

            .dashboard-chart-grid {
                grid-template-columns: minmax(0,1fr) minmax(390px,1fr);
                gap: 18px;
            }

            .verification-chart {
                width: 165px;
                height: 165px;
                flex-basis: 165px;
            }

            .verification-legend {
                width: 150px;
                min-width: 150px;
            }
        }


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
                width: 100%;
                overflow-x: hidden;
            }

            .dashboard-chart-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .chart-card {
                width: 100%;
                height: auto;
                min-height: 350px;
            }

            .verification-chart-container {
                width: 100%;
                max-width: 100%;
            }

            .questionnaire-card,
            .period-card {
                width: 100%;
            }
        }


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
            }

            .header-title {
                font-size: 12px;
                max-width: 170px;
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
                padding: 16px 12px 28px;
            }

            .page-kicker {
                font-size: 10px;
                margin-bottom: 5px;
            }

            .page-title {
                font-size: 22px;
                margin-bottom: 6px;
            }

            .page-description {
                font-size: 11px;
                line-height: 1.55;
                margin-bottom: 16px;
            }

            .welcome-card {
                padding: 16px;
                margin-bottom: 14px;
            }

            .welcome-title {
                font-size: 14px;
            }

            .welcome-text {
                font-size: 10px;
                line-height: 1.5;
            }

            .stats-grid {
                grid-template-columns: repeat(2, minmax(0,1fr));
                gap: 9px;
                margin-bottom: 14px;
            }

            .stat-card {
                padding: 13px;
            }

            .stat-label {
                font-size: 9px;
                line-height: 1.35;
                margin-bottom: 7px;
            }

            .stat-value {
                font-size: 21px;
            }

            .stat-description {
                font-size: 8px;
                line-height: 1.4;
                margin-top: 6px;
            }

            .dashboard-chart-grid {
                gap: 14px;
                margin-top: 14px;
            }

            .chart-card {
                padding: 14px;
                min-height: 0;
                border-radius: 10px;
            }

            .chart-header {
                margin-bottom: 12px;
            }

            .chart-title {
                font-size: 13px;
                line-height: 1.4;
            }

            .chart-description {
                font-size: 9px;
                line-height: 1.5;
            }

            .chart-container {
                height: 240px;
            }

            .verification-chart-container {
                height: auto;
                min-height: 250px;
                flex-direction: column;
                gap: 12px;
                padding: 8px 0 14px;
            }

            .verification-chart {
                width: 175px;
                height: 175px;
                flex-basis: 175px;
            }

            .verification-legend {
                width: 100%;
                max-width: 270px;
                min-width: 0;
                gap: 10px;
            }

            .questionnaire-card,
            .period-card {
                padding: 14px;
                margin-top: 14px;
            }

            .questionnaire-header,
            .period-header {
                margin-bottom: 12px;
            }

            .questionnaire-title,
            .period-title {
                font-size: 13px;
            }

            .questionnaire-description,
            .period-description {
                font-size: 9px;
            }

            .questionnaire-table thead th,
            .period-table thead th {
                font-size: 9px;
                padding: 10px;
            }

            .questionnaire-table tbody td,
            .period-table tbody td {
                font-size: 9px;
                padding: 10px;
            }

            .status-badge {
                font-size: 8px;
                padding: 4px 8px;
            }

            .pagination-area {
                flex-direction: column;
                align-items: flex-start;
            }

            .pagination-buttons {
                width: 100%;
                justify-content: flex-end;
            }
        }


        @media (max-width: 400px) {

            .sidebar {
                width: 250px;
            }

            .content {
                padding: 14px 10px 24px;
            }

            .header-title {
                max-width: 145px;
                font-size: 11px;
            }

            .stats-grid {
                gap: 8px;
            }

            .stat-card {
                padding: 11px;
            }

            .stat-value {
                font-size: 19px;
            }

            .chart-container {
                height: 220px;
            }

            .verification-chart-container {
                min-height: 250px;
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

        <div class="menu-title">
            Menu Utama
        </div>


        <!-- DASHBOARD -->

        <a
            href="{{ route('dashboard') }}"
            class="menu-link active"
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
            class="menu-link"
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
            href="{{ route('kuisioner.index') }}"
            class="menu-link"
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
            href="{{ route('verifikasi.index') }}"
            class="menu-link"
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
            href="{{ route('monitoring.index') }}"
            class="menu-link"
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
            href="{{ route('laporan.index') }}"
            class="menu-link"
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

            <span>Laporan</span>

        </a>


        <!-- MASTER -->

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


            <div
                class="master-submenu"
                id="masterSubmenu"
            >

                <a
                    href="{{ route('periode.index') }}"
                    class="submenu-link"
                >

                    <span class="submenu-icon">
                        ▣
                    </span>

                    <span>
                        Periode
                    </span>

                </a>


                <a
                    href="{{ route('master.index') }}"
                    class="submenu-link"
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

                    <span>
                        Pengguna
                    </span>

                </a>

            </div>

        </div>

    </nav>


    <!-- SIDEBAR FOOTER -->

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

                <span>
                    Keluar
                </span>

            </button>

        </form>

    </div>

</aside>


<div
    class="overlay"
    id="overlay"
></div>


<!-- =====================================================
     MAIN
===================================================== -->

<main class="main">


    <!-- HEADER -->

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


        <a
            href="{{ route('profil.index') }}"
            class="header-admin"
        >

            <div class="admin-text">

                <div class="admin-name">
                    {{ auth()->user()->name ?? 'Pengguna' }}
                </div>

                <div class="admin-role">
                    {{ ucfirst(auth()->user()->role ?? 'Pengguna') }}
                </div>

            </div>


            <div class="admin-avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'P', 0, 1)) }}
            </div>

        </a>

    </header>


    <!-- CONTENT -->

    <section class="content">


        <div class="page-kicker">
            {{ strtoupper(auth()->user()->role ?? 'PENGGUNA') }}
        </div>


        <h1 class="page-title">
            Dashboard
        </h1>


        <p class="page-description">
            Selamat datang di panel administrasi Sistem Pendataan
            Perlinsos Kota Pasuruan. Pantau data responden,
            kuisioner, dan proses verifikasi melalui dashboard ini.
        </p>


        <!-- WELCOME -->

        <div class="welcome-card">

            <div class="welcome-title">
                Selamat Datang,
                {{ ucfirst(auth()->user()->role ?? 'Pengguna') }}
            </div>

            <div class="welcome-text">
                Kelola data pendataan sosial, kuisioner,
                verifikasi alamat, monitoring, dan laporan
                melalui sistem ini.
            </div>

        </div>


        <!-- STATISTICS -->

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
                    {{ $periodeAktif ?? 0 }}
                </div>

                <div class="stat-description">
                    Periode pendataan berjalan
                </div>

            </div>


            <div class="stat-card">

                <div class="stat-label">
                    Data Berjalan Saat Ini
                </div>

                <div class="stat-value">
                    12
                </div>

                <div class="stat-description">
                    Data pendataan yang sedang berjalan
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
                    Data alamat telah diverifikasi
                </div>

            </div>

        </div>


        <!-- GRAFIK -->

        <div class="dashboard-chart-grid">


            <!-- GRAFIK KECAMATAN -->

            <div class="chart-card">

                <div class="chart-header">

                    <div class="chart-title">
                        Persebaran Data Responden Berdasarkan Kecamatan
                    </div>

                    <div class="chart-description">
                        Jumlah data responden berdasarkan kecamatan
                        pada pendataan yang sedang berjalan.
                    </div>

                </div>


                <div class="chart-container">

                    <canvas id="kecamatanChart"></canvas>

                </div>

            </div>


            <!-- GRAFIK VERIFIKASI -->

            <div class="chart-card">

                <div class="chart-header">

                    <div class="chart-title">
                        Status Verifikasi Alamat
                    </div>

                    <div class="chart-description">
                        Perbandingan status verifikasi alamat
                        responden berdasarkan data kuisioner.
                    </div>

                </div>


                <div class="verification-chart-container">


                    <div class="verification-chart">

                        <canvas id="verificationChart"></canvas>

                    </div>


                    <div class="verification-legend">


                        <!-- DISETUJUI -->

                        <div class="verification-legend-item">

                            <div class="verification-legend-left">

                                <span class="verification-dot verification-approved"></span>

                                <span>
                                    Disetujui
                                </span>

                            </div>

                            <strong id="approvedValue">
                                96 (75%)
                            </strong>

                        </div>


                        <!-- DIAJUKAN -->

                        <div class="verification-legend-item">

                            <div class="verification-legend-left">

                                <span class="verification-dot verification-submitted"></span>

                                <span>
                                    Diajukan
                                </span>

                            </div>

                            <strong id="submittedValue">
                                20 (16%)
                            </strong>

                        </div>


                        <!-- DITOLAK -->

                        <div class="verification-legend-item">

                            <div class="verification-legend-left">

                                <span class="verification-dot verification-rejected"></span>

                                <span>
                                    Ditolak
                                </span>

                            </div>

                            <strong id="rejectedValue">
                                12 (9%)
                            </strong>

                        </div>


                    </div>

                </div>

            </div>

        </div>


        <!-- KUISIONER TERAKHIR -->

        <div class="questionnaire-card">


            <div class="questionnaire-header">

                <div>

                    <div class="questionnaire-title">
                        Kuisioner Terakhir
                    </div>

                    <div class="questionnaire-description">
                        Data kuisioner yang dilakukan atau diperbarui.
                    </div>

                </div>

            </div>


            <div class="table-wrapper">


                <table
                    class="questionnaire-table"
                    id="questionnaireTable"
                >


                    <thead>

                        <tr>

                            <th>
                                No
                            </th>

                            <th>
                                Nama Responden
                            </th>

                            <th>
                                Kecamatan
                            </th>

                            <th>
                                Tanggal
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        @if(isset($kuisionerTerakhir) && count($kuisionerTerakhir) > 0)


                            @foreach($kuisionerTerakhir as $index => $kuisioner)


                                <tr class="questionnaire-row">


                                    <td>
                                        {{ $index + 1 }}
                                    </td>


                                    <td>
                                        {{ $kuisioner->nama_responden
                                            ?? $kuisioner->nama
                                            ?? '-' }}
                                    </td>


                                    <td>
                                        {{ $kuisioner->kecamatan ?? '-' }}
                                    </td>


                                    <td>

                                        {{ isset($kuisioner->created_at)
                                            ? \Carbon\Carbon::parse(
                                                $kuisioner->created_at
                                            )->format('d/m/Y')
                                            : '-' }}

                                    </td>


                                    <td>

                                        @php

                                            $status = strtolower(trim(
                                                $kuisioner->status_verifikasi
                                                ?? $kuisioner->status
                                                ?? 'diajukan'
                                            ));

                                        @endphp


                                        @if(
                                            $status === 'disetujui'
                                            || $status === 'approved'
                                            || $status === 'setuju'
                                        )

                                            <span class="status-badge status-disetujui">
                                                Disetujui
                                            </span>


                                        @elseif(
                                            $status === 'ditolak'
                                            || $status === 'rejected'
                                            || $status === 'tolak'
                                        )

                                            <span class="status-badge status-ditolak">
                                                Ditolak
                                            </span>


                                        @elseif(
                                            $status === 'diajukan'
                                            || $status === 'pending'
                                            || $status === 'menunggu'
                                            || $status === 'diperiksa'
                                        )

                                            <span class="status-badge status-diajukan">
                                                Diajukan
                                            </span>


                                        @else

                                            <span class="status-badge status-belum">
                                                {{ ucfirst($status) }}
                                            </span>

                                        @endif

                                    </td>


                                </tr>


                            @endforeach


                        @else


                            <tr>

                                <td
                                    colspan="5"
                                    class="empty-table"
                                >
                                    Belum ada data kuisioner terakhir.
                                </td>

                            </tr>


                        @endif


                    </tbody>

                </table>

            </div>


            <!-- PAGINATION KUISIONER -->

            <div
                class="pagination-area"
                id="questionnairePagination"
            >

                <div
                    class="pagination-info"
                    id="questionnaireInfo"
                ></div>


                <div
                    class="pagination-buttons"
                    id="questionnaireButtons"
                ></div>

            </div>

        </div>


        <!-- REKAP DATA TIAP PERIODE -->

        <div class="period-card">


            <div class="period-header">

                <div>

                    <div class="period-title">
                        Rekap Data Tiap Periode
                    </div>

                    <div class="period-description">
                        Ringkasan jumlah responden dan hasil verifikasi
                        berdasarkan periode pendataan.
                    </div>

                </div>

            </div>


            <div class="table-wrapper">


                <table
                    class="period-table"
                    id="periodTable"
                >


                    <thead>

                        <tr>

                            <th>
                                No
                            </th>

                            <th>
                                Nama Periode
                            </th>

                            <th>
                                Tanggal Mulai
                            </th>

                            <th>
                                Tanggal Selesai
                            </th>

                            <th>
                                Total Responden
                            </th>

                            <th>
                                Terverifikasi
                            </th>

                            <th>
                                Belum Verifikasi
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        @if(isset($rekapPeriode) && count($rekapPeriode) > 0)


                            @foreach($rekapPeriode as $index => $periode)


                                <tr class="period-row">


                                    <td>
                                        {{ $index + 1 }}
                                    </td>


                                    <td>

                                        {{ $periode->nama_periode
                                            ?? $periode->nama
                                            ?? $periode->periode
                                            ?? '-' }}

                                    </td>


                                    <td>

                                        @if(!empty($periode->tgl_awal))

                                            {{ \Carbon\Carbon::parse(
                                                $periode->tgl_awal
                                            )->format('d/m/Y') }}

                                        @elseif(!empty($periode->tanggal_mulai))

                                            {{ \Carbon\Carbon::parse(
                                                $periode->tanggal_mulai
                                            )->format('d/m/Y') }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    <td>

                                        @if(!empty($periode->tgl_akhir))

                                            {{ \Carbon\Carbon::parse(
                                                $periode->tgl_akhir
                                            )->format('d/m/Y') }}

                                        @elseif(!empty($periode->tanggal_selesai))

                                            {{ \Carbon\Carbon::parse(
                                                $periode->tanggal_selesai
                                            )->format('d/m/Y') }}

                                        @else

                                            -

                                        @endif

                                    </td>


                                    <td>

                                        {{ $periode->total_responden
                                            ?? $periode->jumlah_responden
                                            ?? 0 }}

                                    </td>


                                    <td>

                                        {{ $periode->terverifikasi
                                            ?? $periode->jumlah_terverifikasi
                                            ?? 0 }}

                                    </td>


                                    <td>

                                        {{ $periode->belum_verifikasi
                                            ?? $periode->belum_terverifikasi
                                            ?? 0 }}

                                    </td>


                                    <td>

                                        @php

                                            $statusPeriode = strtolower(
                                                $periode->status_periode
                                                ?? $periode->status
                                                ?? 'selesai'
                                            );

                                        @endphp


                                        @if($statusPeriode === 'aktif')

                                            <span class="status-badge status-aktif">
                                                Aktif
                                            </span>

                                        @elseif($statusPeriode === 'selesai')

                                            <span class="status-badge status-selesai">
                                                Selesai
                                            </span>

                                        @else

                                            <span class="status-badge status-belum">
                                                {{ ucfirst($statusPeriode) }}
                                            </span>

                                        @endif

                                    </td>


                                </tr>


                            @endforeach


                        @else


                            <tr>

                                <td
                                    colspan="8"
                                    class="empty-table"
                                >
                                    Belum ada data rekap periode.
                                </td>

                            </tr>


                        @endif


                    </tbody>

                </table>

            </div>


            <!-- PAGINATION REKAP PERIODE -->

            <div
                class="pagination-area"
                id="periodPagination"
            >

                <div
                    class="pagination-info"
                    id="periodInfo"
                ></div>


                <div
                    class="pagination-buttons"
                    id="periodButtons"
                ></div>

            </div>

        </div>

    </section>

</main>


<!-- =====================================================
     JAVASCRIPT
===================================================== -->

<script>


    /* =====================================================
       SIDEBAR
    ===================================================== */

    const sidebar =
        document.getElementById('sidebar');


    const hamburger =
        document.getElementById('hamburger');


    const overlay =
        document.getElementById('overlay');


    function openSidebar() {

        sidebar.classList.add('show');

        overlay.classList.add('show');

    }


    function closeSidebar() {

        sidebar.classList.remove('show');

        overlay.classList.remove('show');

    }


    hamburger.addEventListener(
        'click',
        function () {

            if (
                sidebar.classList.contains('show')
            ) {

                closeSidebar();

            } else {

                openSidebar();

            }

        }
    );


    overlay.addEventListener(
        'click',
        function () {

            closeSidebar();

        }
    );


    /* =====================================================
       MASTER DROPDOWN
    ===================================================== */

    const masterMenu =
        document.getElementById('masterMenu');


    const masterToggle =
        document.getElementById('masterToggle');


    const masterSubmenu =
        document.getElementById('masterSubmenu');


    masterToggle.addEventListener(
        'click',
        function () {

            masterMenu.classList.toggle(
                'open'
            );

            masterToggle.classList.toggle(
                'open'
            );

            masterSubmenu.classList.toggle(
                'open'
            );

        }
    );


    /* =====================================================
       CLOSE SIDEBAR MOBILE
    ===================================================== */

    const menuLinks =
        sidebar.querySelectorAll('a');


    menuLinks.forEach(
        function (link) {

            link.addEventListener(
                'click',
                function () {

                    if (
                        window.innerWidth <= 900
                    ) {

                        closeSidebar();

                    }

                }
            );

        }
    );


    /* =====================================================
       RESET SIDEBAR DESKTOP
    ===================================================== */

    window.addEventListener(
        'resize',
        function () {

            if (
                window.innerWidth > 900
            ) {

                closeSidebar();

            }

        }
    );


    /* =====================================================
       PAGINATION
       
       5 DATA PER HALAMAN
       JUMLAH DATA TIDAK DIBATASI
    ===================================================== */

    function setupPagination(
        tableId,
        rowClass,
        infoId,
        buttonsId
    ) {


        const table =
            document.getElementById(tableId);


        if (!table) {
            return;
        }


        const rows =
            Array.from(
                table.querySelectorAll(
                    'tbody .' + rowClass
                )
            );


        const info =
            document.getElementById(infoId);


        const buttons =
            document.getElementById(buttonsId);


        const perPage = 5;


        const dataRows = rows;


        let currentPage = 1;


        const totalData =
            dataRows.length;


        const totalPages =
            Math.max(
                1,
                Math.ceil(
                    totalData / perPage
                )
            );


        function showPage(page) {


            currentPage = page;


            const start =
                (page - 1) * perPage;


            const end =
                start + perPage;


            dataRows.forEach(
                function (row, index) {


                    if (
                        index >= start &&
                        index < end
                    ) {

                        row.style.display = '';

                    } else {

                        row.style.display = 'none';

                    }

                }
            );


            if (totalData > 0) {


                const startNumber =
                    start + 1;


                const endNumber =
                    Math.min(
                        end,
                        totalData
                    );


                info.textContent =
                    'Menampilkan ' +
                    startNumber +
                    '–' +
                    endNumber +
                    ' dari ' +
                    totalData +
                    ' data';


            } else {


                info.textContent =
                    'Belum ada data';

            }


            renderButtons();

        }


        function renderButtons() {


            buttons.innerHTML = '';


            if (
                totalPages <= 1
            ) {

                return;

            }


            const previous =
                document.createElement(
                    'button'
                );


            previous.type =
                'button';


            previous.className =
                'pagination-button';


            previous.innerHTML =
                '‹';


            previous.title =
                'Sebelumnya';


            previous.disabled =
                currentPage === 1;


            previous.addEventListener(
                'click',
                function () {


                    if (
                        currentPage > 1
                    ) {

                        showPage(
                            currentPage - 1
                        );

                    }

                }
            );


            buttons.appendChild(
                previous
            );


            for (
                let page = 1;
                page <= totalPages;
                page++
            ) {


                const button =
                    document.createElement(
                        'button'
                    );


                button.type =
                    'button';


                button.className =
                    'pagination-button';


                button.textContent =
                    page;


                if (
                    page === currentPage
                ) {

                    button.classList.add(
                        'active'
                    );

                }


                button.addEventListener(
                    'click',
                    function () {

                        showPage(page);

                    }
                );


                buttons.appendChild(
                    button
                );

            }


            const next =
                document.createElement(
                    'button'
                );


            next.type =
                'button';


            next.className =
                'pagination-button';


            next.innerHTML =
                '›';


            next.title =
                'Berikutnya';


            next.disabled =
                currentPage === totalPages;


            next.addEventListener(
                'click',
                function () {


                    if (
                        currentPage <
                        totalPages
                    ) {

                        showPage(
                            currentPage + 1
                        );

                    }

                }
            );


            buttons.appendChild(
                next
            );

        }


        showPage(1);

    }


    /* =====================================================
       PAGINATION KUISIONER
       
       5 DATA PER HALAMAN
       TANPA BATAS JUMLAH DATA
    ====================================================== */

    setupPagination(
        'questionnaireTable',
        'questionnaire-row',
        'questionnaireInfo',
        'questionnaireButtons'
    );


    /* =====================================================
       PAGINATION REKAP PERIODE

       5 DATA PER HALAMAN

       FORMAT:
       1/2
       2/2

       ATAU:

       1/3
       2/3
       3/3

       TANPA BATAS JUMLAH DATA
    ====================================================== */

    function setupPeriodPagination() {


        const table =
            document.getElementById(
                'periodTable'
            );


        if (!table) {
            return;
        }


        const rows =
            Array.from(
                table.querySelectorAll(
                    'tbody .period-row'
                )
            );


        const info =
            document.getElementById(
                'periodInfo'
            );


        const buttons =
            document.getElementById(
                'periodButtons'
            );


        if (
            !info ||
            !buttons
        ) {

            return;

        }


        const perPage = 5;


        let currentPage = 1;


        const totalData =
            rows.length;


        const totalPages =
            Math.max(
                1,
                Math.ceil(
                    totalData / perPage
                )
            );


        function showPage(page) {


            currentPage = page;


            const start =
                (currentPage - 1) *
                perPage;


            const end =
                start + perPage;


            rows.forEach(
                function(row, index) {


                    row.style.display =
                        index >= start &&
                        index < end
                            ? ''
                            : 'none';

                }
            );


            if (
                totalData > 0
            ) {


                const startNumber =
                    start + 1;


                const endNumber =
                    Math.min(
                        end,
                        totalData
                    );


                info.textContent =
                    'Menampilkan ' +
                    startNumber +
                    '–' +
                    endNumber +
                    ' dari ' +
                    totalData +
                    ' data';


            } else {


                info.textContent =
                    'Belum ada data';

            }


            renderPeriodButtons();

        }


        function renderPeriodButtons() {


            buttons.innerHTML = '';


            if (
                totalPages <= 1
            ) {

                return;

            }


            const previous =
                document.createElement(
                    'button'
                );


            previous.type =
                'button';


            previous.className =
                'pagination-button';


            previous.innerHTML =
                '‹';


            previous.title =
                'Sebelumnya';


            previous.disabled =
                currentPage === 1;


            previous.addEventListener(
                'click',
                function() {


                    if (
                        currentPage > 1
                    ) {

                        showPage(
                            currentPage - 1
                        );

                    }

                }
            );


            buttons.appendChild(
                previous
            );


            const indicator =
                document.createElement(
                    'button'
                );


            indicator.type =
                'button';


            indicator.className =
                'pagination-button active';


            indicator.textContent =
                currentPage +
                '/' +
                totalPages;


            indicator.style.cursor =
                'default';


            buttons.appendChild(
                indicator
            );


            const next =
                document.createElement(
                    'button'
                );


            next.type =
                'button';


            next.className =
                'pagination-button';


            next.innerHTML =
                '›';


            next.title =
                'Berikutnya';


            next.disabled =
                currentPage === totalPages;


            next.addEventListener(
                'click',
                function() {


                    if (
                        currentPage <
                        totalPages
                    ) {

                        showPage(
                            currentPage + 1
                        );

                    }

                }
            );


            buttons.appendChild(
                next
            );

        }


        showPage(1);

    }


    setupPeriodPagination();


    /* =====================================================
       GRAFIK KECAMATAN
    ====================================================== */

    const kecamatanCtx =
        document.getElementById(
            'kecamatanChart'
        );


    new Chart(
        kecamatanCtx,
        {

            type: 'bar',


            data: {

                labels: [

                    'Bugul Kidul',
                    'Gadingrejo',
                    'Panggungrejo',
                    'Purworejo'

                ],


                datasets: [

                    {

                        label:
                            'Jumlah Responden',


                        data: [

                            35,
                            25,
                            30,
                            38

                        ],


                        backgroundColor: [

                            '#252A86',
                            '#3B4CCA',
                            '#6675D9',
                            '#8A96E8'

                        ],


                        borderRadius: 6,


                        borderWidth: 0,


                        categoryPercentage:
                            0.65,


                        barPercentage:
                            0.78

                    }

                ]

            },


            options: {

                responsive: true,


                maintainAspectRatio:
                    false,


                plugins: {

                    legend: {

                        display:
                            false

                    }

                },


                scales: {

                    y: {

                        beginAtZero:
                            true,


                        ticks: {

                            precision:
                                0

                        },


                        grid: {

                            color:
                                '#eef0f5'

                        },


                        title: {

                            display:
                                true,


                            text:
                                'Jumlah Responden'

                        }

                    },


                    x: {

                        grid: {

                            display:
                                false

                        },


                        title: {

                            display:
                                true,


                            text:
                                'Kecamatan'

                        }

                    }

                }

            }

        }
    );


    /* =====================================================
       GRAFIK STATUS VERIFIKASI
       
       3 STATUS:
       1. DISETUJUI
       2. DIAJUKAN
       3. DITOLAK
    ====================================================== */


    const verificationCtx =
        document.getElementById(
            'verificationChart'
        );


    /* =====================================================
       DATA VERIFIKASI

       TOTAL:
       96 + 20 + 12 = 128
    ====================================================== */

    const verificationData = {

        disetujui: 96,

        diajukan: 20,

        ditolak: 12

    };


    /* =====================================================
       HITUNG TOTAL
    ====================================================== */

    const verificationTotal =
        verificationData.disetujui +
        verificationData.diajukan +
        verificationData.ditolak;


    /* =====================================================
       HITUNG PERSENTASE
    ====================================================== */

    const approvedPercentage =
        Math.round(
            (
                verificationData.disetujui /
                verificationTotal
            ) * 100
        );


    const submittedPercentage =
        Math.round(
            (
                verificationData.diajukan /
                verificationTotal
            ) * 100
        );


    const rejectedPercentage =
        Math.round(
            (
                verificationData.ditolak /
                verificationTotal
            ) * 100
        );


    /* =====================================================
       MASUKKAN NILAI KE LEGEND
    ====================================================== */

    document.getElementById(
        'approvedValue'
    ).textContent =
        verificationData.disetujui +
        ' (' +
        approvedPercentage +
        '%)';


    document.getElementById(
        'submittedValue'
    ).textContent =
        verificationData.diajukan +
        ' (' +
        submittedPercentage +
        '%)';


    document.getElementById(
        'rejectedValue'
    ).textContent =
        verificationData.ditolak +
        ' (' +
        rejectedPercentage +
        '%)';


    /* =====================================================
       TEXT DI TENGAH DONUT
    ====================================================== */

    const centerTextPlugin = {

        id: 'centerText',


        afterDraw(chart) {


            const { ctx } =
                chart;


            const meta =
                chart.getDatasetMeta(0);


            if (
                !meta.data.length
            ) {

                return;

            }


            const x =
                meta.data[0].x;


            const y =
                meta.data[0].y;


            ctx.save();


            ctx.textAlign =
                'center';


            ctx.textBaseline =
                'middle';


            /* TOTAL */

            ctx.font =
                '12px Arial';


            ctx.fillStyle =
                '#777';


            ctx.fillText(
                'Total',
                x,
                y - 18
            );


            /* ANGKA TOTAL */

            ctx.font =
                'bold 20px Arial';


            ctx.fillStyle =
                '#252A86';


            ctx.fillText(
                verificationTotal,
                x,
                y + 3
            );


            /* LABEL */

            ctx.font =
                '11px Arial';


            ctx.fillStyle =
                '#777';


            ctx.fillText(
                'Responden',
                x,
                y + 22
            );


            ctx.restore();

        }

    };


    /* =====================================================
       CHART DONUT VERIFIKASI
    ====================================================== */

    new Chart(
        verificationCtx,
        {

            type:
                'doughnut',


            data: {

                labels: [

                    'Disetujui',

                    'Diajukan',

                    'Ditolak'

                ],


                datasets: [

                    {

                        data: [

                            verificationData.disetujui,

                            verificationData.diajukan,

                            verificationData.ditolak

                        ],


                        backgroundColor: [

                            '#252A86',

                            '#8A96E8',

                            '#d9534f'

                        ],


                        borderWidth:
                            0,


                        hoverOffset:
                            4

                    }

                ]

            },


            options: {

                responsive:
                    true,


                maintainAspectRatio:
                    false,


                cutout:
                    '62%',


                plugins: {

                    legend: {

                        display:
                            false

                    },


                    tooltip: {

                        callbacks: {

                            label:
                                function(context) {


                                    const value =
                                        context.raw;


                                    const percentage =
                                        Math.round(
                                            (
                                                value /
                                                verificationTotal
                                            ) * 100
                                        );


                                    return (

                                        context.label +

                                        ': ' +

                                        value +

                                        ' (' +

                                        percentage +

                                        '%)'

                                    );

                                }

                        }

                    }

                }

            },


            plugins: [

                centerTextPlugin

            ]

        }
    );


</script>


</body>

</html>