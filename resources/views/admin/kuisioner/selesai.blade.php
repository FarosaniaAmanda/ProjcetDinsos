
@extends('admin.layouts.app')

@section('title', 'Kuisioner Menunggu Verifikasi')

@section('content')

<div class="kuisioner-selesai-page">

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <div class="page-header-text">
            <h1>Kuisioner Menunggu Verifikasi</h1>
            <p>Daftar kuisioner yang telah diajukan dan sedang menunggu proses verifikasi.</p>
        </div>

        <a href="{{ route('kuisioner.index') }}" class="btn-kembali">
            &larr; Kembali ke Kuisioner
        </a>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success">
            <div class="alert-icon">✓</div>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    {{-- ALERT WARNING --}}
    @if(session('warning'))
        <div class="alert alert-warning">
            <div class="alert-icon">!</div>
            <div>{{ session('warning') }}</div>
        </div>
    @endif

    {{-- SUMMARY --}}
    <div class="summary-card">
        <div class="summary-icon">!</div>

        <div class="summary-content">
            <span class="summary-label">
                Total Kuisioner Menunggu Verifikasi
            </span>

            <strong class="summary-number">
                {{ $selesai->total() }}
            </strong>

            <span class="summary-description">
                Kuisioner yang telah diajukan dan menunggu pemeriksaan verifikator.
            </span>
        </div>
    </div>

    {{-- MAIN CARD --}}
    <div class="data-card">

        <div class="data-card-header">
            <div>
                <h2>Daftar Kuisioner Menunggu Verifikasi</h2>
                <p>Data di bawah merupakan kuisioner yang menunggu proses verifikasi.</p>
            </div>

            <div class="data-count">
                {{ $selesai->total() }} Data
            </div>
        </div>

        {{-- TABLE --}}
        <div class="table-wrapper">
            <table class="data-table">

                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>No. KK</th>
                        <th>NIK</th>
                        <th>Nama Kepala Keluarga</th>
                        <th>Kecamatan</th>
                        <th>Kelurahan</th>
                        <th>Status</th>
                        <th>Tanggal Pengajuan</th>
                        <th width="125">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($selesai as $item)
                        <tr>
                            {{-- NO --}}
                            <td class="number-column">
                                {{ $selesai->firstItem() + $loop->index }}
                            </td>


                            {{-- NO KK --}}
                            <td>
                                <div class="primary-text">
                                    {{ $item->no_kk ?? '-' }}
                                </div>
                            </td>

                            {{-- NIK --}}
                            <td>
                                <div class="primary-text">
                                    {{ $item->nik ?? '-' }}
                                </div>
                            </td>

                            {{-- NAMA --}}
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

                            {{-- KECAMATAN --}}
                            <td>
                                {{ $item->kecamatan ?? '-' }}
                            </td>

                            {{-- KELURAHAN --}}
                            <td>
                                {{ $item->kelurahan ?? '-' }}
                            </td>

                            {{-- STATUS --}}
                            <td>
                                <span class="status-badge status-menunggu">
                                    <span class="status-dot"></span>
                                    Menunggu Verifikasi
                                </span>
                            </td>
                            
                            {{-- TANGGAL PENGAJUAN --}}
                            <td>
                                <div class="date-cell">
                                    {{ $item->updated_at
                                        ? $item->updated_at->format('d-m-Y')
                                        : '-'
                                    }}
                                </div>
                            </td>


                            {{-- AKSI --}}
                            <td>
                                <a
                                    href="{{ route('kuisioner.selesai.detail', $item->id) }}"
                                    class="btn-detail"
                                    title="Lihat Detail Kuisioner"
                                >
                                    <span>Detail</span>
                                </a>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="9" class="empty-state">
                                <div class="empty-icon">!</div>

                                <h3>
                                    Belum Ada Kuisioner Menunggu Verifikasi
                                </h3>

                                <p>
                                    Kuisioner yang telah diajukan akan muncul di halaman ini
                                    dan menunggu proses verifikasi.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
        {{-- PAGINATION --}}
        <div class="pagination-wrapper">
            <div class="pagination-info">
                Menampilkan
                <strong>{{ $selesai->firstItem() ?? 0 }}</strong>
                sampai
                <strong>{{ $selesai->lastItem() ?? 0 }}</strong>
                dari <strong>{{ $selesai->total() }}</strong> data
            </div>

            <nav class="pagination-buttons" aria-label="Navigasi halaman kuisioner">
                @if ($selesai->onFirstPage())
                    <span class="page-btn disabled" aria-disabled="true">&lsaquo;</span>
                @else
                    <a href="{{ $selesai->previousPageUrl() }}" class="page-btn" aria-label="Halaman sebelumnya">
                        &lsaquo;
                    </a>
                @endif

                @for ($page = 1; $page <= $selesai->lastPage(); $page++)
                    @if ($selesai->currentPage() === $page)
                        <span class="page-btn active" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $selesai->url($page) }}" class="page-btn">{{ $page }}</a>
                    @endif
                @endfor

                @if ($selesai->hasMorePages())
                    <a href="{{ $selesai->nextPageUrl() }}" class="page-btn" aria-label="Halaman berikutnya">
                        &rsaquo;
                    </a>
                @else
                    <span class="page-btn disabled" aria-disabled="true">&rsaquo;</span>
                @endif
            </nav>
        </div>
    </div>
</div>

@push('styles')
<style>
    
    .btn-kembali {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 18px;
        background: #252A86;
        color: #fff;
        text-decoration: none;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
        transition: background 0.2s ease;
    }

    .btn-kembali:hover {
        background: #1b1f6b;
        color: #fff;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-kembali {
            align-self: flex-start;
        }

        .pagination-wrapper {
            align-items: flex-start;
            flex-direction: column;
        }

        .pagination-buttons {
            gap: 5px;
        }

        .page-btn {
            min-width: 36px;
            height: 36px;
            padding: 0 9px;
        }
    }

    .kuisioner-selesai-page {
        width: 100%;
    }

    /* PAGE HEADER */
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

    /* ALERT */
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

    /* SUMMARY CARD */
    .summary-card {
        position: relative;
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 25px 28px;
        margin-bottom: 24px;
        overflow: hidden;
        background: linear-gradient(115deg, #ffffff 0%, #fffbeb 100%);
        border: 1px solid #fde68a;
        border-left: 5px solid #eab308;
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(146, 64, 14, 0.07);
    }

    .summary-card::after {
        content: "";
        position: absolute;
        width: 150px;
        height: 150px;
        right: -48px;
        top: -68px;
        border-radius: 50%;
        background: rgba(234, 179, 8, 0.08);
        pointer-events: none;
    }

    .summary-icon {
        width: 64px;
        height: 64px;
        flex: 0 0 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #fde68a;
        border-radius: 18px;
        background: #fef3c7;
        color: #b45309;
        font-size: 30px;
        font-weight: 800;
        box-shadow: 0 4px 10px rgba(180, 83, 9, 0.08);
    }

    .summary-content {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .summary-label {
        color: #92400e;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.2px;
    }

    .summary-number {
        color: #78350f;
        font-size: 36px;
        font-weight: 800;
        line-height: 1.15;
    }

    .summary-description {
        color: #78716c;
        font-size: 13px;
        line-height: 1.6;
    }
    
    .pagination-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        padding: 20px 22px;
        border-top: 1px solid #e8ebf3;
        background: #fff;
    }

    .pagination-info {
        color: #737b8c;
        font-size: 15px;
    }

    .pagination-info strong {
        font-weight: 600;
    }

    .pagination-buttons {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        height: 42px;
        padding: 0 12px;
        border: 1px solid #d5dced;
        border-radius: 12px;
        background: #fff;
        color: #292f91;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s, color 0.2s;
    }

    .page-btn:hover {
        background: #eef0ff;
        color: #292f91;
    }

    .page-btn.active {
        background: #292f91;
        border-color: #292f91;
        color: #fff;
    }

    .page-btn.disabled {
        background: #f4f5f9;
        color: #b7bdcc;
        cursor: not-allowed;
    }

    /* MAIN CARD */
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
        background: #fef3c7;
        color: #92400e;
        font-size: 13px;
        font-weight: 700;
    }

    /* TABLE */
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

    /* NAME */
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

    /* STATUS MENUNGGU VERIFIKASI */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 11px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-badge.status-menunggu {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .status-dot {
        width: 7px;
        height: 7px;
        flex: 0 0 7px;
        border-radius: 50%;
    }

    .status-badge.status-menunggu .status-dot {
        background: #d97706;
    }

    /* DATE */
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

    /* BUTTON DETAIL */
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
        transition: background .2s ease, border-color .2s ease,
            color .2s ease, transform .2s ease, box-shadow .2s ease;
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

    /* EMPTY STATE */
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
        background: #fef3c7;
        color: #b45309;
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

    /* TABLET */
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

    /* MOBILE */
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

        .summary-label {
            font-size: 12px;
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

        .data-table {
            min-width: 1180px;
        }

        .btn-detail {
            min-height: 36px;
            padding: 8px 11px;
            font-size: 12px;
        }
    }

    /* SMALL MOBILE */
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
