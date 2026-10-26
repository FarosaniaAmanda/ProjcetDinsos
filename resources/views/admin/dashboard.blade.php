@php
    $authenticatedUser = auth()->user();
    $authenticatedName = trim((string) ($authenticatedUser?->name ?? ''))
        ?: 'Nama tidak tersedia';
    $authenticatedRole = ucfirst($authenticatedUser?->role ?: 'pengguna');
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Sistem Pendataan Perlinsos Kota Pasuruan</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f6fa;
            color: #1f2937;
            overflow-x: hidden;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            height: 100vh;
            background: #252A86;
            color: #fff;
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
            padding: 16px;
            border-bottom: 1px solid rgba(255,255,255,.12);
        }

        .sidebar-logo img {
            width: 72px;
            height: 72px;
            object-fit: contain;
            border-radius: 50%;
            background: #fff;
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

        .menu-link,
        .submenu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 12px 14px;
            margin-bottom: 5px;
            border: 0;
            border-radius: 9px;
            background: transparent;
            color: rgba(255,255,255,.85);
            text-decoration: none;
            text-align: left;
            font-family: inherit;
            font-size: 14px;
            cursor: pointer;
            transition: .2s ease;
        }

        .menu-link:hover,
        .submenu-link:hover {
            background: rgba(255,255,255,.12);
            color: #fff;
        }

        .menu-link.active {
            background: #fff;
            color: #252A86;
            font-weight: 700;
        }

        .menu-icon {
            width: 20px;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .menu-icon svg,
        .submenu-icon svg {
            width: 18px;
            height: 18px;
        }

        .master-menu {
            width: 100%;
        }

        .master-toggle {
            appearance: none;
        }

        .master-toggle-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .master-arrow {
            margin-left: auto;
            transition: transform .25s ease;
        }

        .master-arrow svg {
            width: 18px;
            height: 18px;
        }

        .master-toggle.open .master-arrow {
            transform: rotate(180deg);
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
            font-size: 13px;
            padding: 10px 14px;
        }

        .submenu-icon {
            width: 18px;
            min-width: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-footer {
            padding: 15px 14px;
            border-top: 1px solid rgba(255,255,255,.12);
        }

        .logout-form {
            width: 100%;
        }

        .logout-link {
            width: 100%;
            border: 0;
            background: transparent;
            color: rgba(255,255,255,.85);
            text-align: left;
            cursor: pointer;
        }

        /* MAIN */
        .main {
            margin-left: 260px;
            width: calc(100% - 260px);
            min-height: 100vh;
        }

        .header {
            height: 75px;
            position: sticky;
            top: 0;
            z-index: 900;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 0 30px;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 0;
        }

        .header-title {
            color: #252A86;
            font-size: 16px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .header-subtitle {
            margin-top: 4px;
            color: #9ca3af;
            font-size: 12px;
        }

        .header-admin {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 9px;
            border-radius: 10px;
            color: inherit;
            text-decoration: none;
            flex-shrink: 0;
        }

        .header-admin:hover {
            background: #f3f4f6;
        }

        .admin-text {
            text-align: right;
        }

        .admin-name {
            font-size: 13px;
            font-weight: 700;
        }

        .admin-role {
            margin-top: 3px;
            color: #9ca3af;
            font-size: 11px;
        }

        .admin-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #252A86;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
        }

        .hamburger {
            display: none;
            width: 38px;
            height: 38px;
            border: 1px solid #e5e7eb;
            background: #fff;
            border-radius: 7px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 4px;
        }

        .hamburger span {
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

        /* CONTENT */
        .content {
            padding: 30px;
        }

        .page-kicker {
            margin-bottom: 7px;
            color: #252A86;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.2px;
        }

        .page-title {
            margin-bottom: 8px;
            color: #252A86;
            font-size: 27px;
        }

        .page-description {
            margin-bottom: 25px;
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
        }

        .welcome-card {
            position: relative;
            overflow: hidden;
            padding: 24px 25px;
            margin-bottom: 22px;
            border-radius: 10px;
            background: #252A86;
            color: #fff;
        }

        .welcome-card::after {
            content: "";
            position: absolute;
            width: 180px;
            height: 180px;
            right: -55px;
            top: -70px;
            border-radius: 50%;
            background: rgba(255,255,255,.05);
        }

        .welcome-title {
            position: relative;
            z-index: 1;
            margin-bottom: 7px;
            font-size: 18px;
            font-weight: 700;
        }

        .welcome-text {
            position: relative;
            z-index: 1;
            color: rgba(255,255,255,.8);
            font-size: 12px;
            line-height: 1.6;
        }

        /* KARTU STATISTIK */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0,1fr));
            gap: 12px;
            margin-bottom: 22px;
        }

        .stat-card {
            position: relative;
            min-height: 125px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 18px 10px;
            overflow: hidden;
            text-align: center;
            background: #fff;
            border: 1px solid #e6e8f1;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(41,45,143,.045);
        }

        .stat-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--stat-color);
        }

        .stat-card-respondent { --stat-color: #3155c6; }
        .stat-card-rejected { --stat-color: #d9534f; }
        .stat-card-pending { --stat-color: #d99a00; }
        .stat-card-approved { --stat-color: #15916d; }

        .stat-label {
            color: #27305e;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.35;
        }

        .stat-value {
            margin-top: 9px;
            color: var(--stat-color);
            font-size: 29px;
            font-weight: 800;
            line-height: 1;
        }

        .stat-description {
            margin-top: 8px;
            color: #8a92a5;
            font-size: 10px;
            line-height: 1.4;
        }

        /* GRAFIK */
        .dashboard-chart-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin-top: 22px;
            align-items: stretch;
        }

        .chart-card {
            width: 100%;
            min-width: 0;
            height: 350px;
            padding: 18px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .chart-title {
            margin-bottom: 5px;
            color: #252A86;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.4;
        }

        .chart-description {
            color: #9ca3af;
            font-size: 11px;
            line-height: 1.5;
        }

        .chart-header {
            margin-bottom: 12px;
        }

        .chart-container {
            position: relative;
            width: 100%;
            height: 245px;
        }

        .chart-legend {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 17px;
            margin-top: 12px;
            color: #555;
            font-size: 11px;
        }

        .chart-legend span {
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }

        .legend-blue {
            width: 11px;
            height: 11px;
            border-radius: 3px;
            background: #3978f6;
        }

        .legend-green {
            width: 3px;
            height: 16px;
            border-radius: 2px;
            background: #19a974;
        }

        .verification-chart-container {
            height: 245px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 0 8px;
            overflow: hidden;
        }

        .verification-chart {
            position: relative;
            width: 42%;
            max-width: 155px;
            height: 155px;
            flex: 0 1 42%;
            margin-left: 0;
        }

        .verification-legend {
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 54%;
            min-width: 0;
            margin-left: auto;
            padding-left: 4px;
        }

        .verification-legend-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            font-size: 11px;
            line-height: 1.3;
            color: #555;
            font-weight: 700;
        }

        .verification-legend-left {
            display: flex;
            align-items: center;
            gap: 6px;
            min-width: 0;
            flex: 1 1 auto;
        }

        .verification-legend-left span:last-child {
            display: block;
            min-width: 0;
            overflow-wrap: anywhere;
        }

        .verification-legend-item strong {
            color: #252A86;
            font-size: 11px;
            white-space: nowrap;
            flex: 0 0 auto;
            margin-right: 8px; 
            font-weight: 600;
        }

        .verification-dot {
            width: 9px;
            height: 9px;
            min-width: 9px;
            border-radius: 50%;
        }

        .verification-approved { background: #21874b; }
        .verification-pending { background: #f2c94c; }
        .verification-rejected { background: #d9534f; }

        /* TABEL */
        .questionnaire-card,
        .period-card {
            margin-top: 20px;
            padding: 22px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
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
            margin-bottom: 5px;
            color: #252A86;
            font-size: 15px;
            font-weight: 700;
        }

        .questionnaire-description,
        .period-description {
            color: #9ca3af;
            font-size: 11px;
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

        .period-table {
            table-layout: fixed;
        }

        .period-table th:nth-child(1),
        .period-table td:nth-child(1) { width: 5%; text-align: center; }

        .period-table th:nth-child(2),
        .period-table td:nth-child(2) { width: 17%; }

        .period-table th:nth-child(3),
        .period-table td:nth-child(3) { width: 14%; }

        .period-table th:nth-child(4),
        .period-table td:nth-child(4) {
            width: 13%;
            text-align: center;
            padding-left: 16px;
            padding-right: 16px;
        }

        .period-table th:nth-child(5),
        .period-table td:nth-child(5) {
            width: 12%;
            text-align: center;
            padding-left: 16px;
            padding-right: 16px;
        }

        .period-table th:nth-child(6),
        .period-table td:nth-child(6) {
            width: 15%;
            text-align: center;
            padding-left: 16px;
            padding-right: 16px;
        }

        .period-table th:nth-child(7),
        .period-table td:nth-child(7) {
            width: 11%;
            text-align: center;
            padding-left: 16px;
            padding-right: 16px;
        }

        .period-table th:nth-child(8),
        .period-table td:nth-child(8) { width: 13%; text-align: center; }

        .questionnaire-table thead th,
        .period-table thead th {
            padding: 12px 14px;
            background: #f7f8fc;
            border-bottom: 1px solid #e5e7eb;
            color: #252A86;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .questionnaire-table tbody td,
        .period-table tbody td {
            padding: 13px 14px;
            border-bottom: 1px solid #f0f1f5;
            color: #4b5563;
            vertical-align: middle;
            font-size: 11px;
        }

        .questionnaire-table tbody tr:hover,
        .period-table tbody tr:hover {
            background: #fafbff;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            white-space: nowrap;
        }

        .period-table .status-selesai {
            background: #fff3d6;
            color: #b77900;
        }

        .status-disetujui {
            background: #eaf7ef;
            color: #21874b;
        }

        .status-proses,
        .status-diajukan {
            background: #fff5dc;
            color: #a36b00;
        }

        .status-belum {
            background: #f1f2f5;
            color: #6b7280;
        }

        .period-table .status-aktif {
            background: #e4f6ec;
            color: #16834a;
        }

        .period-table .status-akan-datang {
            background: #eaf0ff;
            color: #3159d9;
        }

        .status-ditolak {
            background: #fdecec;
            color: #c0392b;
        }

        .pagination-area {
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

        .pagination-buttons {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 5px;
        }

        .pagination-button {
            min-width: 28px;
            height: 28px;
            padding: 0 8px;
            border: 1px solid #dfe1e8;
            background: #fff;
            color: #555;
            border-radius: 6px;
            font-size: 11px;
            cursor: pointer;
            transition: .2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .pagination-button:hover:not(:disabled):not(.active) {
            background: #f1f2fa;
            border-color: #292d8f;
            color: #292d8f;
        }

        .pagination-button.arrow-button {
            font-size: 16px;
            font-weight: 400;
        }

        .pagination-button.page-number {
            font-weight: 500;
        }

        .pagination-button.page-number.active {
            background: #292d8f;
            border-color: #292d8f;
            color: #fff;
            font-weight: 700;
            cursor: default;
        }

        .pagination-button:disabled {
            opacity: .45;
            cursor: not-allowed;
        }

        .pagination-button[aria-disabled="true"] {
            opacity: .45;
            cursor: not-allowed;
            text-decoration: none;
        }

        .empty-table {
            padding: 25px !important;
            text-align: center !important;
            color: #9ca3af !important;
        }

        /* RESPONSIVE */
        @media (max-width: 1100px) {
            .stats-grid {
                grid-template-columns: repeat(2,minmax(0,1fr));
            }

            .dashboard-chart-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .chart-card {
                padding: 14px;
            }

            .verification-chart-container {
                gap: 12px;
                padding: 0 4px;
            }

            .verification-chart {
                width: 40%;
                max-width: 140px;
                height: 140px;
                flex-basis: 40%;
            }

            .verification-legend {
                width: 56%;
                padding-left: 3px;
            }

            .verification-legend-item {
                font-size: 9px;
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
            }

            .dashboard-chart-grid {
                grid-template-columns: 1fr;
            }

            .chart-card {
                height: 370px;
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
                max-width: 170px;
                font-size: 12px;
            }

            .header-admin .admin-text {
                display: none;
            }

            .admin-avatar {
                width: 34px;
                height: 34px;
            }

            .content {
                padding: 16px 12px 28px;
            }

            .page-title {
                font-size: 22px;
            }

            .page-description {
                font-size: 11px;
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
            }

            .stats-grid {
                gap: 9px;
                margin-bottom: 14px;
            }

            .stat-card {
                min-height: 115px;
                padding: 13px 8px;
            }

            .stat-label {
                font-size: 11px;
            }

            .stat-value {
                font-size: 25px;
            }

            .stat-description {
                font-size: 9px;
            }

            .dashboard-chart-grid {
                gap: 14px;
                margin-top: 14px;
            }

            .chart-card {
                height: auto;
                min-height: 350px;
                padding: 14px;
            }

            .chart-title {
                font-size: 13px;
            }

            .chart-description {
                font-size: 10px;
            }

            .chart-container {
                height: 245px;
            }

            .verification-chart-container {
                height: auto;
                min-height: 245px;
                flex-direction: column;
                gap: 10px;
                padding: 6px 0;
            }

            .verification-chart {
                width: 150px;
                max-width: 150px;
                height: 150px;
                flex: 0 0 150px;
            }

            .verification-legend {
                width: 100%;
                max-width: 270px;
                gap: 10px;
            }

            .questionnaire-card,
            .period-card {
                padding: 14px;
                margin-top: 14px;
            }

            .questionnaire-title,
            .period-title {
                font-size: 13px;
            }

            .questionnaire-description,
            .period-description {
                font-size: 10px;
            }

            .questionnaire-table thead th,
            .period-table thead th,
            .questionnaire-table tbody td,
            .period-table tbody td {
                padding: 10px;
                font-size: 9px;
            }

            .status-badge {
                padding: 4px 8px;
                font-size: 8px;
            }

            .pagination-area {
                flex-direction: column;
                align-items: flex-start;
            }

            .pagination-buttons {
                width: 100%;
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

            .stat-value {
                font-size: 21px;
            }

            .chart-container {
                height: 225px;
            }
        }

        /* FILTER PERIODE DASHBOARD */
        .period-filter-panel {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            margin: 22px 0;
            padding: 16px 18px;
            background: #fff;
            border: 1px solid #e7eaf2;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(20, 35, 90, .04);
        }

        .period-filter-copy {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .period-filter-title {
            color: #252A86;
            font-size: 14px;
            font-weight: 700;
        }

        .period-filter-description {
            color: #737b8c;
            font-size: 12px;
        }

        .period-filter-controls {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .period-filter-select {
            min-width: 220px;
            max-width: 100%;
            padding: 10px 12px;
            border: 1px solid #d9deea;
            border-radius: 8px;
            background: #fff;
            color: #283044;
            font: inherit;
            font-size: 13px;
        }

        .period-filter-button {
            padding: 10px 15px;
            border: 0;
            border-radius: 8px;
            background: #252A86;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .period-filter-reset {
            padding: 10px 12px;
            border-radius: 8px;
            color: #525d72;
            background: #f1f3f8;
            text-decoration: none;
            font-size: 13px;
        }

        @media (max-width: 600px) {
            .period-filter-panel {
                align-items: stretch;
            }

            .period-filter-controls {
                width: 100%;
            }

            .period-filter-select {
                flex: 1;
                min-width: 0;
                width: 100%;
            }
        }

    </style>
</head>

<body>
    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('images/dinsos.png') }}" alt="Logo Dinas Sosial">
        </div>

        <nav class="sidebar-menu">
            <div class="menu-title">Menu Utama</div>

            <a href="{{ route('dashboard') }}" class="menu-link active">
                <span class="menu-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                    </svg>
                </span>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('periode.pilih', ['tujuan' => 'responden.index']) }}" class="menu-link">
                <span class="menu-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </span>
                <span>Responden</span>
            </a>

            <a href="{{ route('kuisioner.index') }}" class="menu-link">
                <span class="menu-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="8" y1="13" x2="16" y2="13"/>
                        <line x1="8" y1="17" x2="16" y2="17"/>
                    </svg>
                </span>
                <span>Kuisioner</span>
            </a>

            <a href="{{ route('verifikasi.index') }}" class="menu-link">
                <span class="menu-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 11l3 3L22 4"/>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                    </svg>
                </span>
                <span>Verifikasi</span>
            </a>

            <a href="{{ route('monitoring.index') }}" class="menu-link">
                <span class="menu-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 3 3 21 21 21"/>
                        <polyline points="7 16 11 12 14 15 21 8"/>
                    </svg>
                </span>
                <span>Monitoring</span>
            </a>

            <a href="{{ route('laporan.index') }}" class="menu-link">
                <span class="menu-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="8" y1="13" x2="16" y2="13"/>
                        <line x1="8" y1="17" x2="16" y2="17"/>
                    </svg>
                </span>
                <span>Laporan</span>
            </a>

            <div class="master-menu" id="masterMenu">
                <button type="button" class="menu-link master-toggle" id="masterToggle">
                    <span class="master-toggle-left">
                        <span class="menu-icon">⚙</span>
                        <span>Master</span>
                    </span>
                    <span class="master-arrow">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </span>
                </button>

                <div class="master-submenu" id="masterSubmenu">
                    <a href="{{ route('periode.index') }}" class="submenu-link">
                        <span class="submenu-icon">▣</span>
                        <span>Periode</span>
                    </a>
                    <a href="{{ route('master.index') }}" class="submenu-link">
                        <span class="submenu-icon">♙</span>
                        <span>Pengguna</span>
                    </a>
                </div>
            </div>
        </nav>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="menu-link logout-link">
                    <span class="menu-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                    </span>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="overlay" id="overlay"></div>

    {{-- MAIN --}}
    <main class="main">
        <header class="header">
            <div class="header-left">
                <button type="button" class="hamburger" id="hamburger" aria-label="Buka menu">
                    <span></span><span></span><span></span>
                </button>
                <div>
                    <div class="header-title">Sistem Pendataan Perlinsos Kota Pasuruan</div>
                    <div class="header-subtitle">Panel Administrasi</div>
                </div>
            </div>

            <a href="{{ route('profil.index') }}" class="header-admin">
                <div class="admin-text">
                    <div class="admin-name">{{ $authenticatedName }}</div>
                    <div class="admin-role">{{ $authenticatedRole }}</div>
                </div>
                <div class="admin-avatar">
                    {{ strtoupper(substr($authenticatedName, 0, 1)) }}
                </div>
            </a>
        </header>

        <section class="content">
            <div class="page-kicker">{{ strtoupper(auth()->user()->role ?? 'PENGGUNA') }}</div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-description">
                Selamat datang di panel administrasi Sistem Pendataan Perlinsos Kota Pasuruan.
                Pantau data responden, kuisioner, dan proses verifikasi melalui dashboard ini.
            </p>



            <div class="welcome-card">
                <div class="welcome-title">Selamat Datang, {{ $authenticatedName }}</div>
                <div class="welcome-text">
                    Anda masuk sebagai {{ strtolower($authenticatedRole) }}.
                    Kelola data pendataan sosial, kuisioner, verifikasi, monitoring, dan laporan
                    melalui sistem ini.
                </div>
            </div>

            {{-- STATISTIK --}}
            <div class="stats-grid">
                <div class="stat-card stat-card-respondent">
                    <div class="stat-label">Total Responden</div>
                    <div class="stat-value">{{ $totalResponden ?? 0 }}</div>
                    <div class="stat-description">Data responden terdaftar</div>
                </div>

                <div class="stat-card stat-card-rejected">
                    <div class="stat-label">Ditolak</div>
                    <div class="stat-value">{{ $jumlahDitolak ?? 0 }}</div>
                    <div class="stat-description">Jumlah data yang ditolak saat verifikasi</div>
                </div>

                <div class="stat-card stat-card-pending">
                    <div class="stat-label">Menunggu Verifikasi</div>
                    <div class="stat-value">{{ $jumlahMenungguVerifikasi ?? 0 }}</div>
                    <div class="stat-description">Data kuisioner menunggu verifikasi</div>
                </div>

                <div class="stat-card stat-card-approved">
                    <div class="stat-label">Disetujui</div>
                    <div class="stat-value">{{ $jumlahDisetujui ?? 0 }}</div>
                    <div class="stat-description">Data telah disetujui</div>
                </div>
            </div>

            {{-- FILTER PERIODE DASHBOARD --}}
            <form method="GET" action="{{ route('dashboard') }}" class="period-filter-panel">
                <div class="period-filter-copy">
                    <div class="period-filter-title">Filter Periode Pendataan</div>
                    <div class="period-filter-description">
                        Pilih periode aktif maupun selesai untuk melihat ringkasan data.
                    </div>
                </div>

                <div class="period-filter-controls">
                    <select name="periode_kode" class="period-filter-select" aria-label="Pilih periode pendataan">
                        <option value="">Semua Periode</option>
                        @foreach(($periodeOptions ?? collect()) as $opsiPeriode)
                            <option value="{{ $opsiPeriode->kode }}"
                                {{ (string) ($selectedPeriodKode ?? '') === (string) $opsiPeriode->kode ? 'selected' : '' }}>
                                {{ $opsiPeriode->nama }}
                                ({{ ucfirst(strtolower($opsiPeriode->status_periode ?? '')) }})
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="period-filter-button">Terapkan</button>
                    @if(!empty($selectedPeriodKode))
                        <a href="{{ route('dashboard') }}" class="period-filter-reset">Reset</a>
                    @endif
                </div>
            </form>

            {{-- GRAFIK --}}
            <div class="dashboard-chart-grid">
                <div class="chart-card">
                    <div class="chart-header">
                        <div class="chart-title">Persebaran Responden per Kecamatan</div>
                        <div class="chart-description">
                            Jumlah responden pada setiap kecamatan.
                        </div>
                    </div>

                    <div class="chart-container">
                        <canvas id="kecamatanChart"></canvas>
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-header">
                        <div class="chart-title">Status Verifikasi</div>
                        <div class="chart-description">
                            Ringkasan status verifikasi responden.
                        </div>
                    </div>

                    <div class="verification-chart-container">
                        <div class="verification-chart">
                            <canvas id="verificationChart"></canvas>
                        </div>

                        <div class="verification-legend">
                            <div class="verification-legend-item">
                                <div class="verification-legend-left">
                                    <span class="verification-dot verification-approved"></span>
                                    <span>Disetujui</span>
                                </div>
                                <strong id="approvedValue">{{ $jumlahDisetujui ?? 0 }}</strong>
                            </div>

                            <div class="verification-legend-item">
                                <div class="verification-legend-left">
                                    <span class="verification-dot verification-pending"></span>
                                    <span>Menunggu Verifikasi</span>
                                </div>
                                <strong id="pendingValue">{{ $jumlahMenungguVerifikasi ?? 0 }}</strong>
                            </div>

                            <div class="verification-legend-item">
                                <div class="verification-legend-left">
                                    <span class="verification-dot verification-rejected"></span>
                                    <span>Ditolak</span>
                                </div>
                                <strong id="rejectedValue">{{ $jumlahDitolak ?? 0 }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABEL KUISIONER --}}
            <div class="questionnaire-card">
                <div class="questionnaire-header">
                    <div>
                        <div class="questionnaire-title">Data yang Menunggu Verifikasi</div>
                        <div class="questionnaire-description">
                            Daftar responden yang masih menunggu pemeriksaan dan verifikasi.
                        </div>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="questionnaire-table" id="questionnaireTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Responden</th>
                                <th>Kecamatan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($kuisionerTerakhir) && count($kuisionerTerakhir) > 0)
                                @foreach($kuisionerTerakhir as $index => $kuisioner)
                                    @php
                                        $status = strtolower(trim($kuisioner->status_verifikasi ?? 'pending'));
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $kuisionerTerakhir instanceof \Illuminate\Pagination\LengthAwarePaginator
                                                ? (($kuisionerTerakhir->firstItem() ?? 1) + $loop->index)
                                                : $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ $kuisioner->nama_responden
                                                ?? $kuisioner->nama_kepala_keluarga
                                                ?? $kuisioner->nama
                                                ?? '-' }}
                                        </td>
                                        <td>{{ $kuisioner->kecamatan ?? '-' }}</td>
                                        <td>
                                            {{ isset($kuisioner->created_at)
                                                ? \Carbon\Carbon::parse($kuisioner->updated_at ?? $kuisioner->created_at)->format('d/m/Y')
                                                : '-' }}
                                        </td>
                                        <td>
                                            @if(in_array($status, ['disetujui', 'approved', 'setuju']))
                                                <span class="status-badge status-disetujui">Disetujui</span>
                                            @elseif(in_array($status, ['ditolak', 'rejected', 'tolak']))
                                                <span class="status-badge status-ditolak">Ditolak</span>
                                            @elseif(in_array($status, ['pending', 'diajukan', 'menunggu', 'diperiksa']))
                                                <span class="status-badge status-diajukan">Menunggu Verifikasi</span>
                                            @elseif(in_array($status, ['not_processed', 'belum', 'belum_didata']))
                                                <span class="status-badge status-belum">Belum Didata</span>
                                            @else
                                                <span class="status-badge status-belum">
                                                    {{ $status === 'draft' ? 'Draft' : ucfirst($status) }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="empty-table">
                                        Belum ada data kuisioner yang menunggu verifikasi.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if($kuisionerTerakhir instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="pagination-area">
                        <div class="pagination-info">
                            Menampilkan {{ $kuisionerTerakhir->firstItem() ?? 0 }}
                            –{{ $kuisionerTerakhir->lastItem() ?? 0 }}
                            dari {{ $kuisionerTerakhir->total() }} data
                        </div>

                        @php
                            $lastPage = max(1, $kuisionerTerakhir->lastPage());
                            $currentPage = min($kuisionerTerakhir->currentPage(), $lastPage);
                        @endphp

                        <div class="pagination-buttons">
                            @if($kuisionerTerakhir->onFirstPage())
                                <span class="pagination-button arrow-button" aria-disabled="true">‹</span>
                            @else
                                <a class="pagination-button arrow-button" href="{{ $kuisionerTerakhir->previousPageUrl() }}" title="Halaman sebelumnya">‹</a>
                            @endif

                            @foreach($kuisionerTerakhir->getUrlRange(1, $lastPage) as $page => $url)
                                @if($page === $currentPage)
                                    <span class="pagination-button page-number active" aria-current="page">{{ $page }}</span>
                                @else
                                    <a class="pagination-button page-number" href="{{ $url }}">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if($kuisionerTerakhir->hasMorePages())
                                <a class="pagination-button arrow-button" href="{{ $kuisionerTerakhir->nextPageUrl() }}" title="Halaman selanjutnya">›</a>
                            @else
                                <span class="pagination-button arrow-button" aria-disabled="true">›</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- REKAP PERIODE --}}
            <div class="period-card">
                <div class="period-header">
                    <div>
                        <div class="period-title">Rekap Data Tiap Periode</div>
                        <div class="period-description">
                            Ringkasan jumlah responden dan hasil verifikasi berdasarkan periode pendataan.
                        </div>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="period-table" id="periodTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Periode</th>
                                <th>Waktu Periode</th>
                                <th>Total Responden</th>
                                <th>Disetujui</th>
                                <th>Menunggu Verifikasi</th>
                                <th>Ditolak</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($rekapPeriode) && count($rekapPeriode) > 0)
                                @foreach($rekapPeriode as $index => $periode)
                                    @php
                                        $statusAsli = trim((string) ($periode->status_periode ?? $periode->status ?? ''));
                                        $statusPeriode = mb_strtolower($statusAsli);

                                        if (in_array($statusPeriode, ['aktif', 'active'], true)) {
                                            $statusClass = 'status-aktif';
                                            $statusLabel = 'Aktif';
                                        } elseif (in_array($statusPeriode, ['selesai', 'completed', 'complete'], true)) {
                                            $statusClass = 'status-selesai';
                                            $statusLabel = 'Selesai';
                                        } elseif (in_array($statusPeriode, ['akan datang', 'akan_datang', 'upcoming', 'belum dimulai', 'belum_dimulai'], true)) {
                                            $statusClass = 'status-akan-datang';
                                            $statusLabel = 'Akan Datang';
                                        } else {
                                            $statusClass = 'status-akan-datang';
                                            $statusLabel = $statusAsli !== '' ? ucfirst($statusAsli) : '-';
                                        }
                                    @endphp
                                    <tr class="period-row">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $periode->nama_periode ?? $periode->nama ?? $periode->periode ?? '-' }}</td>
                                        <td>
                                            @if(!empty($periode->tgl_awal) && !empty($periode->tgl_akhir))
                                                {{ \Carbon\Carbon::parse($periode->tgl_awal)->format('d/m/Y') }}
                                                -
                                                {{ \Carbon\Carbon::parse($periode->tgl_akhir)->format('d/m/Y') }}
                                            @elseif(!empty($periode->tgl_awal))
                                                Mulai {{ \Carbon\Carbon::parse($periode->tgl_awal)->format('d/m/Y') }}
                                            @elseif(!empty($periode->tgl_akhir))
                                                Selesai {{ \Carbon\Carbon::parse($periode->tgl_akhir)->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $periode->total_responden ?? 0 }}</td>
                                        <td>{{ $periode->jumlah_disetujui ?? 0 }}</td>
                                        <td>{{ $periode->jumlah_menunggu_verifikasi ?? 0 }}</td>
                                        <td>{{ $periode->jumlah_ditolak ?? 0 }}</td>
                                        <td>
                                            <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" class="empty-table">Belum ada data rekap periode.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="pagination-area" id="periodPagination">
                    <div class="pagination-info" id="periodInfo"></div>
                    <nav class="pagination-buttons" id="periodButtons" aria-label="Navigasi halaman rekap periode"></nav>
                </div>
            </div>
        </section>
    </main>

    <script>
        /* SIDEBAR RESPONSIF */
        const sidebar = document.getElementById('sidebar');
        const hamburger = document.getElementById('hamburger');
        const overlay = document.getElementById('overlay');

        function openSidebar() {
            sidebar.classList.add('show');
            overlay.classList.add('show');
        }

        function closeSidebar() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        }

        hamburger.addEventListener('click', function () {
            sidebar.classList.contains('show') ? closeSidebar() : openSidebar();
        });

        overlay.addEventListener('click', closeSidebar);

        window.addEventListener('resize', function () {
            if (window.innerWidth > 900) closeSidebar();
        });

        sidebar.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth <= 900) closeSidebar();
            });
        });

        /* DROPDOWN MASTER */
        const masterMenu = document.getElementById('masterMenu');
        const masterToggle = document.getElementById('masterToggle');
        const masterSubmenu = document.getElementById('masterSubmenu');

        masterToggle.addEventListener('click', function () {
            masterMenu.classList.toggle('open');
            masterToggle.classList.toggle('open');
            masterSubmenu.classList.toggle('open');
        });


        /* PAGINATION TABEL PERIODE */
        function setupPeriodPagination() {
            const table = document.getElementById('periodTable');
            const pagination = document.getElementById('periodPagination');
            const info = document.getElementById('periodInfo');
            const buttons = document.getElementById('periodButtons');

            if (!table || !pagination || !info || !buttons) return;

            const rows = Array.from(
                table.querySelectorAll('tbody tr.period-row')
            );

            const perPage = 5;
            let currentPage = 1;

            const totalData = rows.length;
            const totalPages = Math.ceil(totalData / perPage);

            function addButton(label, page, disabled = false, active = false) {
                const button = document.createElement('button');

                button.type = 'button';
                button.className = 'pagination-button ' +
                    (label === '‹' || label === '›' ? 'arrow-button' : 'page-number') +
                    (active ? ' active' : '');
                button.textContent = label;
                button.disabled = disabled;

                if (!disabled && !active) {
                    button.addEventListener('click', function () {
                        currentPage = page;
                        render();
                    });
                }

                buttons.appendChild(button);
            }

            function addPageButton(page) {
                addButton(
                    String(page),
                    page,
                    false,
                    page === currentPage
                );
            }

            function render() {
                const start = (currentPage - 1) * perPage;
                const end = Math.min(start + perPage, totalData);

                rows.forEach(function (row, index) {
                    row.style.display =
                        index >= start && index < end ? '' : 'none';
                });

                buttons.innerHTML = '';

                if (totalData === 0) {
                    pagination.style.display = 'none';
                    return;
                }

                pagination.style.display = 'flex';

                info.textContent =
                    `Menampilkan ${start + 1}–${end} dari ${totalData} periode`;

                // Tombol halaman sebelumnya
                addButton(
                    '‹',
                    currentPage - 1,
                    currentPage === 1
                );

                for (let page = 1; page <= totalPages; page++) {
                    addPageButton(page);
                }

                // Tombol halaman berikutnya
                addButton(
                    '›',
                    currentPage + 1,
                    currentPage === totalPages
                );
            }

            render();
        }

        setupPeriodPagination();


/* DATA GRAFIK KECAMATAN DARI CONTROLLER */
const dataKecamatan = @json($persebaranKecamatan ?? []);

const labelsKecamatan = dataKecamatan.map(item =>
    String(item.nama_kecamatan ?? '').trim()
);
const jumlahKecamatan = dataKecamatan.map(item =>
    Number(item.total_responden) || 0
);

/* GRAFIK BATANG: HANYA JUMLAH RESPONDEN PER KECAMATAN */
const kecamatanCanvas = document.getElementById('kecamatanChart');

if (kecamatanCanvas && typeof Chart !== 'undefined') {
    const chartLama = Chart.getChart(kecamatanCanvas);
    if (chartLama) chartLama.destroy();

    new Chart(kecamatanCanvas, {
        type: 'bar',
        data: {
            labels: labelsKecamatan,
            datasets: [{
                label: 'Jumlah Responden per Kecamatan',
                data: jumlahKecamatan,
                backgroundColor: 'rgba(57,120,246,.85)',
                borderColor: '#3978f6',
                borderWidth: 1,
                borderRadius: 6,
                maxBarThickness: 55,
                categoryPercentage: 0.65,
                barPercentage: 0.78
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label(context) {
                            return `Jumlah Responden: ${context.parsed.y}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#555',
                        maxRotation: 35,
                        minRotation: 0
                    },
                    title: {
                        display: true,
                        text: 'Kecamatan'
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        color: '#777'
                    },
                    grid: {
                        color: '#eef0f5'
                    },
                    title: {
                        display: true,
                        text: 'Jumlah Responden'
                    }
                }
            }
        }
    });
}


        /* GRAFIK DONUT STATUS VERIFIKASI */
        const verificationCanvas = document.getElementById('verificationChart');

               const verificationData = {
            disetujui: Number(@json($jumlahDisetujui ?? 0)),
            menungguVerifikasi: Number(@json($jumlahMenungguVerifikasi ?? 0)),
            ditolak: Number(@json($jumlahDitolak ?? 0))
        };

        const verificationTotal =
            Number(verificationData.disetujui) +
            Number(verificationData.menungguVerifikasi) +
            Number(verificationData.ditolak);

        const verificationHasData = verificationTotal > 0;

        function percentageFor(value) {
            return verificationTotal === 0
                ? 0
                : Math.round((Number(value) / verificationTotal) * 100);
        }

        document.getElementById('approvedValue').textContent =
            `${verificationData.disetujui} (${percentageFor(verificationData.disetujui)}%)`;

        document.getElementById('pendingValue').textContent =
            `${verificationData.menungguVerifikasi} (${percentageFor(verificationData.menungguVerifikasi)}%)`;

        document.getElementById('rejectedValue').textContent =
            `${verificationData.ditolak} (${percentageFor(verificationData.ditolak)}%)`;

        const centerTextPlugin = {
            id: 'centerText',

            afterDraw(chart) {
                const meta = chart.getDatasetMeta(0);
                if (!meta.data.length) return;

                const { ctx } = chart;
                const x = meta.data[0].x;
                const y = meta.data[0].y;

                ctx.save();
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';

                ctx.font = '12px Arial';
                ctx.fillStyle = '#777';
                ctx.fillText('Total', x, y - 18);

                ctx.font = 'bold 20px Arial';
                ctx.fillStyle = '#252A86';
                ctx.fillText(String(verificationTotal), x, y + 3);

                ctx.font = '11px Arial';
                ctx.fillStyle = '#777';
                ctx.fillText('verifikasi', x, y + 22);

                ctx.restore();
            }
        };

        if (verificationCanvas && typeof Chart !== 'undefined') {
            new Chart(verificationCanvas, {
                type: 'doughnut',

                data: {
                    labels: ['Disetujui', 'Menunggu Verifikasi', 'Ditolak'],
                    datasets: [{
                        data: verificationHasData
                            ? [
                                Number(verificationData.disetujui),
                                Number(verificationData.menungguVerifikasi),
                                Number(verificationData.ditolak)
                            ]
                            : [1],

                        backgroundColor: verificationHasData
                            ? ['#21874b', '#f2c94c', '#d9534f']
                            : ['#e5e7eb'],

                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '62%',

                    plugins: {
                        legend: {
                            display: false
                        },

                        tooltip: {
                            filter: function () {
                                return verificationHasData;
                            },

                            callbacks: {
                                label(context) {
                                    const value = Number(context.raw);

                                    return `${context.label}: ${value} (${percentageFor(value)}%)`;
                                }
                            }
                        }
                    }
                },

                plugins: [centerTextPlugin]
            });
        }
    </script>
</body>
</html>