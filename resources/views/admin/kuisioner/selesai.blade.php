@extends('admin.layouts.app')

@section('title', 'Kuisioner Diajukan')

@section('content')

<div class="kuisioner-selesai-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="page-header">

        <div class="page-header-text">
            <h1>Kuisioner Diajukan</h1>

            <p>
                Daftar kuisioner yang telah selesai diisi dan siap untuk
                diajukan ke proses verifikasi.
            </p>
        </div>

    </div>


    {{-- =========================================================
         ALERT SUCCESS
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success">

            <div class="alert-icon">
                ✓
            </div>

            <div>
                {{ session('success') }}
            </div>

        </div>

    @endif


    {{-- =========================================================
         ALERT WARNING
    ========================================================== --}}
    @if(session('warning'))

        <div class="alert alert-warning">

            <div class="alert-icon">
                !
            </div>

            <div>
                {{ session('warning') }}
            </div>

        </div>

    @endif


    {{-- =========================================================
         SUMMARY
    ========================================================== --}}
    <div class="summary-card">

        <div class="summary-icon">
            ✓
        </div>

        <div class="summary-content">

            <span class="summary-label">
                Kuisioner Diajukan
            </span>

            <strong class="summary-number">
                {{ $selesai->count() }}
            </strong>

            <span class="summary-description">
                Data telah menyelesaikan seluruh tahapan kuisioner.
            </span>

        </div>

    </div>


    {{-- =========================================================
         MAIN CARD
    ========================================================== --}}
    <div class="data-card">

        <div class="data-card-header">

            <div>

                <h2>
                    Daftar Kuisioner
                </h2>

                <p>
                    Data di bawah merupakan kuisioner yang sudah selesai
                    diisi oleh petugas.
                </p>

            </div>

            <div class="data-count">
                {{ $selesai->count() }} Data
            </div>

        </div>


        {{-- =====================================================
             TABLE
        ====================================================== --}}
        <div class="table-wrapper">

            <table class="data-table">

                <thead>

                    <tr>

                        <th width="60">
                            No
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
                            Kecamatan
                        </th>

                        <th>
                            Kelurahan
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Tanggal Selesai
                        </th>

                        <th width="125">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($selesai as $item)

                        <tr>

                            {{-- =================================================
                                 NO
                            ================================================== --}}
                            <td class="number-column">
                                {{ $loop->iteration }}
                            </td>


                            {{-- =================================================
                                 NO KK
                            ================================================== --}}
                            <td>

                                <div class="primary-text">
                                    {{ $item->no_kk ?? '-' }}
                                </div>

                            </td>


                            {{-- =================================================
                                 NIK
                            ================================================== --}}
                            <td>

                                <div class="primary-text">
                                    {{ $item->nik ?? '-' }}
                                </div>

                            </td>


                            {{-- =================================================
                                 NAMA
                            ================================================== --}}
                            <td>

                                <div class="name-cell">

                                    <div class="name-avatar">
                                        {{ strtoupper(substr($item->nama_kepala_keluarga ?? 'K', 0, 1)) }}
                                    </div>

                                    <div class="name-content">

                                        <strong>
                                            {{ $item->nama_kepala_keluarga ?? '-' }}
                                        </strong>

                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                 KECAMATAN
                            ================================================== --}}
                            <td>
                                {{ $item->kecamatan ?? '-' }}
                            </td>


                            {{-- =================================================
                                 KELURAHAN
                            ================================================== --}}
                            <td>
                                {{ $item->kelurahan ?? '-' }}
                            </td>


                            {{-- =================================================
                                 STATUS
                            ================================================== --}}
                            <td>

                                <span class="status-badge">

                                    <span class="status-dot"></span>

                                    Selesai

                                </span>

                            </td>


                            {{-- =================================================
                                 TANGGAL SELESAI
                            ================================================== --}}
                            <td>

                                <div class="date-cell">

                                    {{ $item->updated_at
                                        ? $item->updated_at->format('d-m-Y')
                                        : '-'
                                    }}

                                    @if($item->updated_at)

                                        <small>
                                            {{ $item->updated_at->format('H:i') }}
                                        </small>

                                    @endif

                                </div>

                            </td>


                            {{-- =================================================
                                 AKSI
                            ================================================== --}}
                            <td>

                                <a
                                    href="{{ route('kuisioner.selesai.detail', $item->id) }}"
                                    class="btn-detail"
                                    title="Lihat Detail Kuisioner"
                                >


                                    <span>
                                        Detail
                                    </span>

                                </a>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="9"
                                class="empty-state"
                            >

                                <div class="empty-icon">
                                    ✓
                                </div>

                                <h3>
                                    Belum Ada Kuisioner Selesai
                                </h3>

                                <p>
                                    Kuisioner yang telah selesai diisi
                                    akan muncul di halaman ini.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


@push('styles')

<style>

    /* =========================================================
       PAGE
    ========================================================== */

    .kuisioner-selesai-page {
        width: 100%;
    }


    /* =========================================================
       PAGE HEADER
    ========================================================== */

    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .page-header-text h1 {
        margin: 0 0 7px;
        color: #252A86;
        font-size: 28px;
        font-weight: 750;
        line-height: 1.25;
    }

    .page-header-text p {
        margin: 0;
        color: #667085;
        font-size: 15px;
        line-height: 1.6;
    }


    /* =========================================================
       ALERT
    ========================================================== */

    .alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 17px;
        margin-bottom: 20px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
    }

    .alert-success {
        background: #ecfdf3;
        color: #027a48;
        border: 1px solid #abefc6;
    }

    .alert-warning {
        background: #fffaeb;
        color: #b54708;
        border: 1px solid #fedf89;
    }

    .alert-icon {
        width: 28px;
        height: 28px;
        flex: 0 0 28px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;
        font-weight: 800;
    }

    .alert-success .alert-icon {
        background: #d1fadf;
    }

    .alert-warning .alert-icon {
        background: #fef0c7;
    }


    /* =========================================================
       SUMMARY CARD
    ========================================================== */

    .summary-card {
        display: flex;
        align-items: center;
        gap: 18px;

        padding: 22px 24px;
        margin-bottom: 22px;

        background: #ffffff;
        border: 1px solid #eaecf0;
        border-radius: 15px;

        box-shadow: 0 4px 18px rgba(16, 24, 40, 0.05);
    }

    .summary-icon {
        width: 58px;
        height: 58px;
        flex: 0 0 58px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 14px;

        background: #ecfdf3;
        color: #299447;

        font-size: 25px;
        font-weight: 800;
    }

    .summary-content {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .summary-label {
        color: #667085;
        font-size: 14px;
        font-weight: 600;
    }

    .summary-number {
        color: #101828;
        font-size: 26px;
        font-weight: 800;
        line-height: 1.2;
    }

    .summary-description {
        color: #667085;
        font-size: 13px;
    }


    /* =========================================================
       MAIN CARD
    ========================================================== */

    .data-card {
        background: #ffffff;
        border: 1px solid #eaecf0;
        border-radius: 15px;

        box-shadow: 0 4px 18px rgba(16, 24, 40, 0.05);

        overflow: hidden;
    }

    .data-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 20px;

        padding: 21px 24px;

        border-bottom: 1px solid #eaecf0;
    }

    .data-card-header h2 {
        margin: 0 0 5px;

        color: #252A86;

        font-size: 19px;
        font-weight: 750;
    }

    .data-card-header p {
        margin: 0;

        color: #667085;

        font-size: 14px;
        line-height: 1.5;
    }

    .data-count {
        flex-shrink: 0;

        padding: 8px 13px;

        border-radius: 9px;

        background: #f2f4f7;
        color: #344054;

        font-size: 13px;
        font-weight: 700;
    }


    /* =========================================================
       TABLE
    ========================================================== */

    .table-wrapper {
        width: 100%;

        overflow-x: auto;

        -webkit-overflow-scrolling: touch;
    }

    .data-table {
        width: 100%;
        min-width: 1180px;

        border-collapse: collapse;
    }

    .data-table thead th {
        padding: 15px 17px;

        background: #f8f9fc;

        color: #475467;

        font-size: 13px;
        font-weight: 750;

        text-align: left;

        white-space: nowrap;

        border-bottom: 1px solid #eaecf0;
    }

    .data-table tbody td {
        padding: 16px 17px;

        color: #344054;

        font-size: 14px;
        line-height: 1.5;

        vertical-align: middle;

        border-bottom: 1px solid #f2f4f7;
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .data-table tbody tr {
        transition: background .2s ease;
    }

    .data-table tbody tr:hover {
        background: #fafbfc;
    }

    .number-column {
        color: #667085 !important;
        font-weight: 600;
    }

    .primary-text {
        color: #344054;

        font-weight: 600;

        white-space: nowrap;
    }


    /* =========================================================
       NAME
    ========================================================== */

    .name-cell {
        display: flex;
        align-items: center;

        gap: 10px;

        min-width: 210px;
    }

    .name-avatar {
        width: 36px;
        height: 36px;
        flex: 0 0 36px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #eef2ff;
        color: #252A86;

        font-size: 14px;
        font-weight: 800;
    }

    .name-content strong {
        display: block;

        color: #344054;

        font-size: 14px;
        font-weight: 700;

        white-space: nowrap;
    }


    /* =========================================================
       STATUS
    ========================================================== */

    .status-badge {
        display: inline-flex;
        align-items: center;

        gap: 7px;

        padding: 7px 11px;

        border-radius: 999px;

        background: #ecfdf3;
        color: #027a48;

        font-size: 13px;
        font-weight: 700;

        white-space: nowrap;
    }

    .status-dot {
        width: 7px;
        height: 7px;

        border-radius: 50%;

        background: #299447;
    }


    /* =========================================================
       DATE
    ========================================================== */

    .date-cell {
        display: flex;
        flex-direction: column;

        gap: 2px;

        white-space: nowrap;
    }

    .date-cell small {
        color: #98a2b3;
        font-size: 12px;
    }


    /* =========================================================
       BUTTON DETAIL
    ========================================================== */

    .btn-detail {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        gap: 7px;

        min-height: 38px;

        padding: 8px 13px;

        border: 1px solid #d0d5dd;
        border-radius: 9px;

        background: #ffffff;
        color: #344054;

        font-size: 13px;
        font-weight: 700;

        text-decoration: none;

        white-space: nowrap;

        transition:
            background .2s ease,
            border-color .2s ease,
            color .2s ease,
            transform .2s ease,
            box-shadow .2s ease;
    }

    .btn-detail:hover {
        background: #f8f9fc;
        border-color: #252A86;
        color: #252A86;

        transform: translateY(-1px);

        box-shadow: 0 3px 8px rgba(16, 24, 40, 0.08);
    }

    .btn-detail:active {
        transform: translateY(0);
    }

    .btn-detail-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        font-size: 14px;
        line-height: 1;
    }


    /* =========================================================
       EMPTY STATE
    ========================================================== */

    .empty-state {
        padding: 60px 25px !important;

        text-align: center !important;
    }

    .empty-icon {
        width: 58px;
        height: 58px;

        margin: 0 auto 15px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #f2f4f7;
        color: #667085;

        font-size: 23px;
        font-weight: 800;
    }

    .empty-state h3 {
        margin: 0 0 7px;

        color: #344054;

        font-size: 17px;
        font-weight: 700;
    }

    .empty-state p {
        margin: 0;

        color: #667085;

        font-size: 14px;
    }


    /* =========================================================
       TABLET
    ========================================================== */

    @media (max-width: 900px) {

        .page-header-text h1 {
            font-size: 24px;
        }

        .data-card-header {
            align-items: flex-start;
        }

        .data-table {
            min-width: 1180px;
        }

    }


    /* =========================================================
       MOBILE
    ========================================================== */

    @media (max-width: 600px) {

        .page-header {
            margin-bottom: 18px;
        }

        .page-header-text h1 {
            font-size: 22px;
        }

        .page-header-text p {
            font-size: 14px;
        }


        .summary-card {
            padding: 18px;
            gap: 13px;
        }

        .summary-icon {
            width: 48px;
            height: 48px;
            flex-basis: 48px;

            font-size: 21px;
        }

        .summary-number {
            font-size: 23px;
        }

        .summary-description {
            font-size: 12px;
            line-height: 1.5;
        }


        .data-card-header {
            padding: 18px;

            flex-direction: column;
            align-items: flex-start;
        }

        .data-card-header h2 {
            font-size: 18px;
        }

        .data-card-header p {
            font-size: 13px;
        }

        .data-count {
            align-self: flex-start;
        }


        /*
         * Tabel tetap horizontal scroll agar
         * data tidak bertumpuk di layar HP.
         */
        .data-table {
            min-width: 1180px;
        }


        .btn-detail {
            min-height: 36px;

            padding: 8px 11px;

            font-size: 12px;
        }

    }


    /* =========================================================
       SMALL MOBILE
    ========================================================== */

    @media (max-width: 400px) {

        .page-header-text h1 {
            font-size: 20px;
        }


        .summary-card {
            align-items: flex-start;
        }

        .summary-icon {
            width: 44px;
            height: 44px;
            flex-basis: 44px;
        }

        .summary-number {
            font-size: 21px;
        }

    }

</style>

@endpush

@endsection