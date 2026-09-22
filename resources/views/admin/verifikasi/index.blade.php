@extends('admin.layouts.app')

@section('title', 'Verifikasi Data')


@push('styles')

<style>

    /* =====================================================
       VERIFIKASI - PAGE HEADER
    ====================================================== */

    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .page-kicker {
        font-size: 11px;
        font-weight: 700;
        color: #252A86;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 6px;
    }

    .page-title {
        font-size: 26px;
        font-weight: 700;
        color: #252A86;
        line-height: 1.25;
        margin-bottom: 8px;
    }

    .page-description {
        font-size: 14px;
        color: #777;
        line-height: 1.6;
        max-width: 700px;
    }


    /* =====================================================
       ALERT SUCCESS
    ====================================================== */

    .alert-success {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 13px 16px;
        margin-bottom: 20px;
        border-radius: 9px;
        background: #eaf8ef;
        border: 1px solid #bce5c9;
        color: #24723c;
        font-size: 13px;
        animation: alertFade .3s ease;
    }

    .alert-success svg {
        flex-shrink: 0;
    }

    @keyframes alertFade {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }


    /* =====================================================
       SEARCH
    ====================================================== */

    .search-section {
        background: #ffffff;
        border: 1px solid #e8e9ef;
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 20px;
    }

    .search-form {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .search-box {
        position: relative;
        flex: 1;
    }

    .search-box svg {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        pointer-events: none;
    }

    .search-box input {
        width: 100%;
        height: 42px;
        padding: 0 14px 0 40px;
        border: 1px solid #dfe1e8;
        border-radius: 8px;
        outline: none;
        font-size: 13px;
        color: #333;
        background: #ffffff;
        transition: all .2s ease;
    }

    .search-box input:focus {
        border-color: #252A86;
        box-shadow: 0 0 0 3px rgba(37, 42, 134, 0.08);
    }

    .btn-search {
        height: 42px;
        padding: 0 18px;
        border: none;
        border-radius: 8px;
        background: #252A86;
        color: #ffffff;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s ease;
    }

    .btn-search:hover {
        background: #1d226f;
        transform: translateY(-1px);
    }

    .search-result {
    margin-top: 10px;
    font-size: 12px;
    color: #777;
}

.search-result strong {
    color: #252A86;
    font-weight: 700;
}


    /* =====================================================
       STATISTICS
    ====================================================== */

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid #e8e9ef;
        border-radius: 12px;
        padding: 18px;
        transition: all .2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }

    .stat-label {
        font-size: 12px;
        color: #777;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 25px;
        font-weight: 700;
        color: #252A86;
        line-height: 1;
    }


    /* =====================================================
       DATA PANEL
    ====================================================== */

    .data-panel {
        background: #ffffff;
        border: 1px solid #e8e9ef;
        border-radius: 12px;
        overflow: hidden;
    }

    .data-panel-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        padding: 20px;
        border-bottom: 1px solid #e8e9ef;
    }

    .data-panel-title {
        font-size: 17px;
        font-weight: 700;
        color: #252A86;
        margin-bottom: 5px;
    }

    .data-panel-description {
        font-size: 12px;
        color: #888;
        line-height: 1.5;
    }


    /* =====================================================
       FILTER
    ====================================================== */

    .filter-wrapper {
        flex-shrink: 0;
    }

    .filter-select {
        height: 38px;
        min-width: 170px;
        padding: 0 12px;
        border: 1px solid #dfe1e8;
        border-radius: 8px;
        background: #ffffff;
        color: #444;
        font-size: 12px;
        outline: none;
        cursor: pointer;
        transition: all .2s ease;
    }

    .filter-select:focus {
        border-color: #252A86;
        box-shadow: 0 0 0 3px rgba(37, 42, 134, 0.08);
    }


    /* =====================================================
       TABLE
    ====================================================== */

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .data-table {
        width: 100%;
        min-width: 1050px;
        border-collapse: collapse;
    }

    .data-table th {
        padding: 13px 14px;
        background: #f8f8fb;
        border-bottom: 1px solid #e8e9ef;
        color: #666;
        font-size: 11px;
        font-weight: 700;
        text-align: left;
        white-space: nowrap;
    }

    .data-table td {
        padding: 14px;
        border-bottom: 1px solid #eeeeF2;
        color: #444;
        font-size: 12px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .data-table tbody tr {
        transition: background .15s ease;
    }

    .data-table tbody tr:hover {
        background: #fafaff;
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }


    /* =====================================================
       STATUS BADGE
    ====================================================== */

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-pending {
        background: #fff4db;
        color: #9a6a00;
    }

    .status-draft {
        background: #f0f0f2;
        color: #666;
    }

    .status-not-processed {
        background: #f1f2f8;
        color: #5f6380;
    }

    .status-approved {
        background: #eaf8ef;
        color: #24723c;
    }

    .status-rejected {
        background: #fdecec;
        color: #a53636;
    }


    /* =====================================================
       ACTION BUTTON
    ====================================================== */

    .action-wrapper {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-detail,
    .btn-edit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 32px;
        padding: 0 10px;
        border-radius: 7px;
        text-decoration: none;
        font-size: 11px;
        font-weight: 600;
        transition: all .2s ease;
        white-space: nowrap;
    }

    .btn-detail {
        background: #eef0ff;
        color: #252A86;
    }

    .btn-detail:hover {
        background: #252A86;
        color: #ffffff;
    }

    .btn-edit {
        background: #fff5dc;
        color: #8b6800;
    }

    .btn-edit:hover {
        background: #c99b18;
        color: #ffffff;
    }


    /* =====================================================
       EMPTY STATE
    ====================================================== */

    .empty-state {
        padding: 50px 20px;
        text-align: center;
    }

    .empty-state-icon {
        width: 52px;
        height: 52px;
        margin: 0 auto 14px;
        border-radius: 50%;
        background: #f1f2f8;
        color: #8b8fa8;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-state-title {
        font-size: 14px;
        font-weight: 700;
        color: #555;
        margin-bottom: 5px;
    }

    .empty-state-description {
        font-size: 12px;
        color: #999;
    }


    /* =====================================================
       RESPONSIVE
    ====================================================== */

    @media (max-width: 1200px) {

        .stats-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

    }


    @media (max-width: 900px) {

        .page-title {
            font-size: 23px;
        }

        .page-header {
            margin-bottom: 20px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .data-panel-header {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-wrapper {
            width: 100%;
        }

        .filter-select {
            width: 100%;
        }

    }


    @media (max-width: 600px) {

        .page-title {
            font-size: 21px;
        }

        .page-description {
            font-size: 13px;
        }

        .search-section {
            padding: 14px;
        }

        .search-form {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-search {
            width: 100%;
        }

        .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .stat-card {
            padding: 14px;
        }

        .stat-value {
            font-size: 21px;
        }

        .data-panel-header {
            padding: 16px;
        }

    }


    @media (max-width: 400px) {

        .stats-grid {
            gap: 8px;
        }

        .stat-card {
            padding: 12px;
        }

        .stat-label {
            font-size: 11px;
        }

        .stat-value {
            font-size: 19px;
        }

    }

</style>

@endpush


@section('content')

    <!-- =====================================================
         PAGE HEADER
    ====================================================== -->

    <div class="page-header">

        <div>

            <div class="page-kicker">
                ADMIN
            </div>

            <h1 class="page-title">
                Sistem Verifikasi
            </h1>

            <p class="page-description">
                Kelola, periksa, dan perbarui data hasil pendataan responden.
            </p>

        </div>

    </div>


    <!-- =====================================================
         SUCCESS ALERT
    ====================================================== -->

    @if (session('success'))

        <div
            class="alert-success"
            id="successAlert"
        >

            <svg
                width="18"
                height="18"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <path d="M20 6L9 17l-5-5"></path>
            </svg>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    <!-- =====================================================
         SEARCH
    ====================================================== -->

    <div class="search-section"><div class="search-section">
    <form
        action="{{ route('verifikasi.index') }}"
        method="GET"
        class="search-form"
    >
        <div class="search-box">
            <svg
                width="17"
                height="17"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <circle
                    cx="11"
                    cy="11"
                    r="7"
                ></circle>
                <line
                    x1="16.65"
                    y1="16.65"
                    x2="21"
                    y2="21"
                ></line>
            </svg>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari No. KK, NIK, atau nama kepala keluarga..."
            >
        </div>

        <button
            type="submit"
            class="btn-search"
        >
            Cari Data
        </button>
    </form>

    {{-- =====================================================
         HASIL PENCARIAN
    ====================================================== --}}
    @if (request('search'))
        <div class="search-result">
            Hasil pencarian untuk
            <strong>"{{ request('search') }}"</strong> :
            <strong>{{ count($data) }} data</strong>
        </div>
    @endif
</div>

    <!-- =====================================================
         STATISTICS
    ====================================================== -->

    <div class="stats-grid">

        <!-- Total Responden -->

        <div class="stat-card">

            <div class="stat-label">
                Total Responden
            </div>

            <div class="stat-value">
                100
            </div>

        </div>


        <!-- Sudah Didata -->

        <div class="stat-card">

            <div class="stat-label">
                Sudah Didata
            </div>

            <div class="stat-value">
                10
            </div>

        </div>


        <!-- Belum Didata -->

        <div class="stat-card">

            <div class="stat-label">
                Belum Didata
            </div>

            <div class="stat-value">
                265
            </div>

        </div>


        <!-- Menunggu Verifikasi -->

        <div class="stat-card">

            <div class="stat-label">
                Menunggu Verifikasi
            </div>

            <div class="stat-value">
                118
            </div>

        </div>


        <!-- Disetujui -->

        <div class="stat-card">

            <div class="stat-label">
                Disetujui
            </div>

            <div class="stat-value">
                742
            </div>

        </div>


        <!-- Ditolak -->

        <div class="stat-card">

            <div class="stat-label">
                Ditolak
            </div>

            <div class="stat-value">
                83
            </div>

        </div>

    </div>


    <!-- =====================================================
         DATA PANEL
    ====================================================== -->

    <div class="data-panel">

        <!-- HEADER PANEL -->

        <div class="data-panel-header">

            <div>

                <div class="data-panel-title">
                    Data Hasil Pendataan
                </div>

                <div class="data-panel-description">
                    Daftar data responden yang telah masuk ke sistem.
                </div>

            </div>


            <!-- FILTER STATUS -->

            <div class="filter-wrapper">

                <form
                    action="{{ route('verifikasi.index') }}"
                    method="GET"
                >

                    @if (request('search'))

                        <input
                            type="hidden"
                            name="search"
                            value="{{ request('search') }}"
                        >

                    @endif

                    <select
                        name="status"
                        class="filter-select"
                        onchange="this.form.submit()"
                    >

                        <option
                            value="all"
                            {{ request('status', 'all') == 'all' ? 'selected' : '' }}
                        >
                            Semua Status
                        </option>

                        <option
                            value="pending"
                            {{ request('status') == 'pending' ? 'selected' : '' }}
                        >
                            Menunggu Verifikasi
                        </option>

                        <option
                            value="draft"
                            {{ request('status') == 'draft' ? 'selected' : '' }}
                        >
                            Draft
                        </option>

                        <option
                            value="not_processed"
                            {{ request('status') == 'not_processed' ? 'selected' : '' }}
                        >
                            Belum Didata
                        </option>

                        <option
                            value="approved"
                            {{ request('status') == 'approved' ? 'selected' : '' }}
                        >
                            Disetujui
                        </option>

                        <option
                            value="rejected"
                            {{ request('status') == 'rejected' ? 'selected' : '' }}
                        >
                            Ditolak
                        </option>

                    </select>

                </form>

            </div>

        </div>


        <!-- =================================================
             TABLE
        ================================================== -->

        <div class="table-wrapper">

            <table class="data-table">

                <thead>

                    <tr>

                        <th>
                            No.
                        </th>

                        <th>
                            No. KK
                        </th>

                        <th>
                            NIK
                        </th>

                        <th>
                            Nama Kepala Keluarga
                        </th>

                        <th>
                            Jumlah Anggota
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Wilayah Pendataan
                        </th>

                        <th>
                            Petugas
                        </th>

                        <th>
                            Tanggal Pendataan
                        </th>

                        <th>
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($data as $item)

                        <tr>

                            <!-- NO -->

                            <td>
                                {{ $loop->iteration }}
                            </td>


                            <!-- NO KK -->

                            <td>
                                {{ $item['no_kk'] ?? '-' }}
                            </td>


                            <!-- NIK -->

                            <td>
                                {{ $item['nik'] ?? '-' }}
                            </td>


                            <!-- NAMA -->

                            <td>
                                {{ $item['nama'] ?? '-' }}
                            </td>


                            <!-- ANGGOTA -->

                            <td>
                                {{ $item['anggota'] ?? 0 }} Orang
                            </td>


                            <!-- STATUS -->

                            <td>

                                @php
                                    $status = strtolower(
                                        str_replace(
                                            [' ', '-'],
                                            '_',
                                            $item['status'] ?? ''
                                        )
                                    );
                                @endphp

                                @if (
                                    $status === 'pending' ||
                                    $status === 'menunggu' ||
                                    $status === 'menunggu_verifikasi'
                                )

                                    <span class="status-badge status-pending">
                                        Menunggu Verifikasi
                                    </span>

                                @elseif ($status === 'draft')

                                    <span class="status-badge status-draft">
                                        Draft
                                    </span>

                                @elseif (
                                    $status === 'not_processed' ||
                                    $status === 'belum_didata' ||
                                    $status === 'belum_didata'
                                )

                                    <span class="status-badge status-not-processed">
                                        Belum Didata
                                    </span>

                                @elseif (
                                    $status === 'approved' ||
                                    $status === 'disetujui'
                                )

                                    <span class="status-badge status-approved">
                                        Disetujui
                                    </span>

                                @elseif (
                                    $status === 'rejected' ||
                                    $status === 'ditolak'
                                )

                                    <span class="status-badge status-rejected">
                                        Ditolak
                                    </span>

                                @else

                                    <span class="status-badge status-draft">
                                        {{ $item['status'] ?? '-' }}
                                    </span>

                                @endif

                            </td>


                            <!-- WILAYAH -->

                            <td>
                                {{ $item['wilayah'] ?? '-' }}
                            </td>


                            <!-- PETUGAS -->

                            <td>
                                {{ $item['petugas'] ?? '-' }}
                            </td>


                            <!-- TANGGAL -->

                            <td>
                                {{ $item['tanggal'] ?? '-' }}
                            </td>


                            <!-- AKSI -->

                            <td>

                                <div class="action-wrapper">

                                    <a
                                        href="{{ route('verifikasi.show', $item['id'] ?? 0) }}"
                                        class="btn-detail"
                                    >
                                        Detail
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="10">

                                <div class="empty-state">

                                    <div class="empty-state-icon">

                                        <svg
                                            width="23"
                                            height="23"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <circle
                                                cx="11"
                                                cy="11"
                                                r="7"
                                            ></circle>

                                            <line
                                                x1="16.65"
                                                y1="16.65"
                                                x2="21"
                                                y2="21"
                                            ></line>
                                        </svg>

                                    </div>

                                    <div class="empty-state-title">
                                        Data tidak ditemukan
                                    </div>

                                    <div class="empty-state-description">

                                        @if (request('search'))

                                            Tidak ada data yang sesuai dengan pencarian
                                            "{{ request('search') }}".

                                        @else

                                            Belum terdapat data responden yang masuk ke sistem.

                                        @endif

                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection


@push('scripts')

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const successAlert =
            document.getElementById('successAlert');

        if (successAlert) {

            setTimeout(function () {

                successAlert.style.opacity = '0';

                successAlert.style.transform =
                    'translateY(-5px)';

                successAlert.style.transition =
                    'all .3s ease';

                setTimeout(function () {

                    successAlert.remove();

                }, 300);

            }, 3000);

        }

    });

</script>

@endpush