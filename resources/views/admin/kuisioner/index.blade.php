@extends('admin.layouts.app')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    :root {
        --primary: #292D8F;
        --primary-dark: #222675;
        --background: #F5F6FB;
        --text: #1D2754;
        --muted: #73809B;
        --border: #E6E8F1;
    }

    * {
        box-sizing: border-box;
    }

    .kuisioner-page {
        min-height: calc(100vh - 80px);
        background: var(--background);
        padding: 32px 34px 45px;
        font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
        color: var(--text);
    }

    .kuisioner-container {
        width: 100%;
        max-width: 1240px;
        margin: 0 auto;
    }

    /* =========================
       ALERT
    ========================= */

    .kuisioner-page .alert {
        display: flex;
        align-items: center;
        min-height: 48px;
        padding: 13px 17px;
        margin-bottom: 20px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
    }

    .kuisioner-page .alert-success {
        color: #08785D;
        background: #ECFDF7;
        border: 1px solid #C9F2E3;
    }

    .kuisioner-page .alert-warning {
        color: #A66A00;
        background: #FFF9E8;
        border: 1px solid #F6DEA0;
    }

    /* =========================
       HEADER
    ========================= */

    .page-header {
        position: relative;
        overflow: hidden;
        background: #FFFFFF;
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 30px 34px;
        margin-bottom: 22px;
        box-shadow: 0 7px 25px rgba(41, 45, 143, .055);
    }

    .page-header::before {
        content: '';
        position: absolute;
        width: 250px;
        height: 250px;
        right: -120px;
        top: -155px;
        border-radius: 50%;
        background: rgba(41, 45, 143, .045);
    }

    .page-header::after {
        content: '';
        position: absolute;
        width: 120px;
        height: 120px;
        right: 90px;
        bottom: -90px;
        border-radius: 50%;
        border: 1px solid rgba(41, 45, 143, .05);
    }

    .page-header h1 {
        position: relative;
        z-index: 1;
        margin: 0;
        color: var(--primary-dark);
        font-size: 30px;
        font-weight: 800;
        letter-spacing: -.8px;
        line-height: 1.2;
    }

    .page-header p {
        position: relative;
        z-index: 1;
        margin: 9px 0 0;
        color: var(--muted);
        font-size: 13px;
        line-height: 1.6;
    }

    /* =========================
       STATUS GRID
    ========================= */

    .status-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }

    .status-card {
        position: relative;
        min-height: 125px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        background: #FFFFFF;
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 20px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(41, 45, 143, .045);
    }

    .status-card::before {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        height: 4px;
        background: currentColor;
    }

    .status-card > div {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    /* WARNA CARD */

    .draft-card {
        color: #6B7280;
    }

    .respondent-card {
        color: #3155C6;
        text-decoration: none;
    }

    .submit-card {
        color: #D99A00;
        text-decoration: none;
    }

    .reject-card {
        color: #D14B4B;
        text-decoration: none;
    }

    .accept-card {
        color: #15916D;
        text-decoration: none;
    }

    .status-title {
        margin: 0;
        color: #27305E;
        font-size: 14px;
        font-weight: 700;
        line-height: 1.3;
    }

    .status-count {
        margin-top: 8px;
        font-size: 30px;
        font-weight: 800;
        letter-spacing: -1px;
        line-height: 1;
    }

    .draft-card .status-count {
        color: #4B5563;
    }

    .respondent-card .status-count {
        color: #3155C6;
    }

    .submit-card .status-count {
        color: #D99A00;
    }

    .reject-card .status-count {
        color: #C43E3E;
    }

    .accept-card .status-count {
        color: #138360;
    }

    /* =========================
       DRAFT SECTION
    ========================= */

    .draft-section {
        margin-bottom: 24px;
    }

    .draft-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 13px;
    }

    .draft-heading h2 {
        margin: 0;
        color: var(--primary-dark);
        font-size: 19px;
        font-weight: 800;
        letter-spacing: -.3px;
    }

    .draft-heading p {
        margin: 5px 0 0;
        color: var(--muted);
        font-size: 12px;
    }

    .draft-total {
        color: #68738C;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    /* =========================
       TABLE
    ========================= */

    .table-card {
        background: #FFFFFF;
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(41, 45, 143, .045);
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .draft-table {
        width: 100%;
        border-collapse: collapse;
    }

    .draft-table th {
        background: #F8F9FD;
        color: #68738C;
        padding: 14px 17px;
        border-bottom: 1px solid #E9EBF2;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .35px;
        text-align: left;
        white-space: nowrap;
    }

    .draft-table td {
        padding: 15px 17px;
        border-bottom: 1px solid #EEF0F5;
        color: #374151;
        font-size: 13px;
        vertical-align: middle;
    }

    .draft-table tbody tr:last-child td {
        border-bottom: none;
    }

    .draft-table tbody tr:hover td {
        background: #FAFBFF;
    }

    .number-cell {
        width: 55px;
        color: #7B8498 !important;
        font-weight: 600;
        text-align: center;
    }

    .kk-cell {
        color: #252A86 !important;
        font-weight: 700;
        white-space: nowrap;
    }

    .nik-cell {
        white-space: nowrap;
    }

    .name-cell {
        min-width: 190px;
        color: #27305E !important;
        font-weight: 700;
    }

    .address-cell {
        min-width: 270px;
        max-width: 360px;
        color: #68738C !important;
        line-height: 1.5;
    }

    .progress-cell {
        min-width: 120px;
        white-space: nowrap;
    }

    .progress-text {
        color: #292D8F;
        font-size: 12px;
        font-weight: 700;
    }

    .progress-bar {
        width: 90px;
        height: 5px;
        margin-top: 6px;
        overflow: hidden;
        background: #E9EBF3;
        border-radius: 99px;
    }

    .progress-fill {
        height: 100%;
        background: #292D8F;
        border-radius: 99px;
    }

    .btn-continue {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-width: 95px;
        padding: 9px 13px;
        background: #292D8F;
        color: #FFFFFF !important;
        border-radius: 9px;
        text-decoration: none !important;
        font-size: 12px;
        font-weight: 700;
        transition: .2s ease;
        white-space: nowrap;
    }

    .btn-continue:hover {
        background: #222675;
        transform: translateY(-1px);
    }

    /* =========================
       EMPTY
    ========================= */

    .empty-state {
        padding: 50px 25px;
        text-align: center;
    }

    .empty-title {
        color: #374151;
        font-size: 14px;
        font-weight: 800;
    }

    .empty-description {
        margin-top: 6px;
        color: #7B8498;
        font-size: 12px;
    }

    /* =========================
       PAGINATION
    ========================= */

    .pagination-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 15px 17px;
        border-top: 1px solid #EEF0F5;
        background: #FFFFFF;
    }

    .pagination-info {
        color: #7B8498;
        font-size: 12px;
    }

    .pagination {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .pagination a,
    .pagination span {
        min-width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 9px;
        border: 1px solid #E2E5ED;
        border-radius: 8px;
        background: #FFFFFF;
        color: #4B5563;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
    }

    .pagination a:hover {
        border-color: #292D8F;
        color: #292D8F;
    }

    .pagination .active {
        background: #292D8F;
        border-color: #292D8F;
        color: #FFFFFF;
    }

    .pagination .disabled {
        color: #B5BAC5;
        background: #F7F8FA;
        cursor: not-allowed;
    }

    /* =========================
       MULAI KUISIONER
    ========================= */

    .new-kuisioner {
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 25px;
        min-height: 105px;
        padding: 23px 28px;
        background: var(--primary);
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(41, 45, 143, .20);
    }

    .new-kuisioner::before {
        content: '';
        position: absolute;
        width: 270px;
        height: 270px;
        right: 80px;
        top: -190px;
        border: 1px solid rgba(255,255,255,.08);
        border-radius: 50%;
    }

    .new-kuisioner::after {
        content: '';
        position: absolute;
        width: 190px;
        height: 190px;
        right: -30px;
        bottom: -150px;
        border: 1px solid rgba(255,255,255,.08);
        border-radius: 50%;
    }

    .new-kuisioner-content {
        position: relative;
        z-index: 1;
    }

    .new-kuisioner h2 {
        margin: 0;
        color: #FFFFFF;
        font-size: 19px;
        font-weight: 800;
        letter-spacing: -.25px;
        line-height: 1.3;
    }

    .new-kuisioner p {
        margin: 6px 0 0;
        color: rgba(255,255,255,.72);
        font-size: 13px;
        line-height: 1.5;
    }

    .btn-start {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-width: 175px;
        padding: 12px 21px;
        background: #FFFFFF;
        color: var(--primary);
        border: 1px solid rgba(255,255,255,.8);
        border-radius: 10px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 800;
        box-shadow: 0 5px 14px rgba(20, 23, 75, .14);
        transition: .2s ease;
    }

    .btn-start::after {
        content: '→';
        font-size: 16px;
        line-height: 1;
        transition: transform .2s ease;
    }

    .btn-start:hover {
        background: #F5F6FF;
        color: var(--primary-dark);
        transform: translateY(-2px);
        text-decoration: none;
    }

    .btn-start:hover::after {
        transform: translateX(3px);
    }

    /* =========================
       RESPONSIVE
    ========================= */

    @media (max-width: 1050px) {
        .status-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 750px) {
        .kuisioner-page {
            padding: 22px;
        }

        .status-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .draft-heading {
            align-items: flex-start;
            flex-direction: column;
            gap: 5px;
        }

        .pagination-wrapper {
            align-items: flex-start;
            flex-direction: column;
        }
    }

    @media (max-width: 520px) {
        .kuisioner-page {
            padding: 16px;
        }

        .page-header {
            padding: 24px 21px;
        }

        .page-header h1 {
            font-size: 25px;
        }

        .status-grid {
            grid-template-columns: 1fr;
        }

        .new-kuisioner {
            align-items: flex-start;
            flex-direction: column;
            padding: 22px;
        }

        .btn-start {
            width: 100%;
        }
    }
</style>


<div class="kuisioner-page">

    <div class="kuisioner-container">

        {{-- ALERT --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif


        {{-- HEADER --}}
        <div class="page-header">

            <h1>
                Form Kuisioner
            </h1>

            <p>
                Kelola pendataan keluarga berdasarkan status proses pendataan.
            </p>

        </div>


        {{-- =========================
             STATUS CARD
        ========================== --}}
        <div class="status-grid">

            {{-- DRAFT --}}
            {{-- SENGAJA DIV, BUKAN A --}}
            <div class="status-card draft-card">

                <div>

                    <h2 class="status-title">
                        Draft
                    </h2>

                    <div class="status-count">
                        {{ $draftCount ?? 0 }}
                    </div>

                </div>

            </div>


            {{-- JUMLAH RESPONDEN --}}
            <a
                href="{{ route('verifikasi.index') }}"
                class="status-card respondent-card"
            >

                <div>

                    <h2 class="status-title">
                        Jumlah Responden
                    </h2>

                    <div class="status-count">
                        {{ $respondenCount ?? 0 }}
                    </div>

                </div>

            </a>


            {{-- DIAJUKAN --}}
            <a
                href="{{ route('verifikasi.index', ['status' => 'menunggu']) }}"
                class="status-card submit-card"
            >

                <div>

                    <h2 class="status-title">
                        Diajukan
                    </h2>

                    <div class="status-count">
                        {{ $submitCount ?? 0 }}
                    </div>

                </div>

            </a>


            {{-- DITOLAK --}}
            <a
                href="{{ route('verifikasi.index', ['status' => 'ditolak']) }}"
                class="status-card reject-card"
            >

                <div>

                    <h2 class="status-title">
                        Ditolak
                    </h2>

                    <div class="status-count">
                        {{ $ditolakCount ?? 0 }}
                    </div>

                </div>

            </a>


            {{-- DITERIMA --}}
            <a
                href="{{ route('verifikasi.index', ['status' => 'disetujui']) }}"
                class="status-card accept-card"
            >

                <div>

                    <h2 class="status-title">
                        Diterima
                    </h2>

                    <div class="status-count">
                        {{ $diterimaCount ?? 0 }}
                    </div>

                </div>

            </a>

        </div>


        {{-- =========================
             TABEL DRAFT
        ========================== --}}
        <div class="draft-section">

            <div class="draft-heading">

                <div>
                    <h2>
                        Draft Kuisioner
                    </h2>

                    <p>
                        Data keluarga yang sudah mulai didata tetapi belum menyelesaikan seluruh kuisioner.
                    </p>
                </div>

                <div class="draft-total">
                    Total {{ $draftCount ?? 0 }} draft
                </div>

            </div>


            <div class="table-card">

                @if(isset($drafts) && $drafts->count() > 0)

                    <div class="table-wrapper">

                        <table class="draft-table">

                            <thead>

                                <tr>
                                    <th>No</th>
                                    <th>No. KK</th>
                                    <th>NIK</th>
                                    <th>Nama Kepala Keluarga</th>
                                    <th>Alamat</th>
                                    <th>Progress</th>
                                    <th>Aksi</th>
                                </tr>

                            </thead>


                            <tbody>

                                @foreach($drafts as $draft)

                                    @php

                                        $currentPart = (int) ($draft->current_part ?? 1);

                                        $progress = match ($currentPart) {
                                            1 => 20,
                                            2 => 40,
                                            3 => 60,
                                            4 => 80,
                                            5 => 100,
                                            default => 20,
                                        };

                                        /*
                                         * Alamat dibuat dari data yang tersedia.
                                         * Jika alamat_lengkap tersedia, gunakan langsung.
                                         */
                                        $alamat = $draft->alamat_lengkap ?? null;

                                        if (!$alamat) {

                                            $alamatParts = [];

                                            if (!empty($draft->alamat)) {
                                                $alamatParts[] = $draft->alamat;
                                            }

                                            if (!empty($draft->rt_rw)) {
                                                $alamatParts[] = 'RT/RW ' . $draft->rt_rw;
                                            }

                                            if (!empty($draft->kelurahan)) {
                                                $alamatParts[] = 'Kel. ' . $draft->kelurahan;
                                            }

                                            if (!empty($draft->kecamatan)) {
                                                $alamatParts[] = 'Kec. ' . $draft->kecamatan;
                                            }

                                            $alamat = implode(', ', $alamatParts);
                                        }

                                    @endphp


                                    <tr>

                                        {{-- NO --}}
                                        <td class="number-cell">
                                            {{ $drafts->firstItem() + $loop->index }}
                                        </td>


                                        {{-- NO KK --}}
                                        <td class="kk-cell">
                                            {{ $draft->no_kk ?: '-' }}
                                        </td>


                                        {{-- NIK --}}
                                        <td class="nik-cell">
                                            {{ $draft->nik ?: '-' }}
                                        </td>


                                        {{-- NAMA KEPALA KELUARGA --}}
                                        <td class="name-cell">
                                            {{ $draft->nama_kepala_keluarga ?: '-' }}
                                        </td>


                                        {{-- ALAMAT --}}
                                        <td class="address-cell">
                                            {{ $alamat ?: '-' }}
                                        </td>


                                        {{-- PROGRESS --}}
                                        <td class="progress-cell">

                                            <div class="progress-text">
                                                Part {{ $currentPart }} / 5
                                                · {{ $progress }}%
                                            </div>

                                            <div class="progress-bar">

                                                <div
                                                    class="progress-fill"
                                                    style="width: {{ $progress }}%;"
                                                ></div>

                                            </div>

                                        </td>


                                        {{-- AKSI --}}
                                        <td>

                                            <a
                                                href="{{ route('kuisioner.draft.resume', ['id' => $draft->id]) }}"
                                                class="btn-continue"
                                            >
                                                Lanjutkan →
                                            </a>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- PAGINATION --}}
                    <div class="pagination-wrapper">

                        <div class="pagination-info">

                            Menampilkan
                            {{ $drafts->firstItem() }}
                            –
                            {{ $drafts->lastItem() }}
                            dari
                            {{ $drafts->total() }}
                            draft

                        </div>


                        <div class="pagination">

                            {{-- PREVIOUS --}}
                            @if($drafts->onFirstPage())

                                <span class="disabled">
                                    ‹
                                </span>

                            @else

                                <a href="{{ $drafts->previousPageUrl() }}">
                                    ‹
                                </a>

                            @endif


                            {{-- NOMOR HALAMAN --}}
                            @foreach($drafts->getUrlRange(1, $drafts->lastPage()) as $page => $url)

                                @if($page == $drafts->currentPage())

                                    <span class="active">
                                        {{ $page }}
                                    </span>

                                @else

                                    <a href="{{ $url }}">
                                        {{ $page }}
                                    </a>

                                @endif

                            @endforeach


                            {{-- NEXT --}}
                            @if($drafts->hasMorePages())

                                <a href="{{ $drafts->nextPageUrl() }}">
                                    ›
                                </a>

                            @else

                                <span class="disabled">
                                    ›
                                </span>

                            @endif

                        </div>

                    </div>

                @else

                    <div class="empty-state">

                        <div class="empty-title">
                            Belum ada draft kuisioner
                        </div>

                        <div class="empty-description">
                            Data kuisioner yang sudah mulai diisi tetapi belum selesai akan muncul di sini.
                        </div>

                    </div>

                @endif

            </div>

        </div>


        {{-- =========================
             MULAI KUISIONER BARU
        ========================== --}}
        <div class="new-kuisioner">

            <div class="new-kuisioner-content">

                <h2>
                    Mulai Kuisioner Baru
                </h2>

                <p>
                    Mulai pendataan keluarga baru dari Part 1.
                </p>

            </div>


            <a
                href="{{ route('kuisioner.part1') }}"
                class="btn-start"
            >
                Mulai Kuisioner
            </a>

        </div>

    </div>

</div>

@endsection