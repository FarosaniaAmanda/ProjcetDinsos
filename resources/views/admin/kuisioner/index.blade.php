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

    /* =========================================================
       ALERT
    ========================================================= */

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

    /* =========================================================
       HEADER
    ========================================================= */

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

    /* =========================================================
       STATUS GRID
    ========================================================= */

    .status-grid {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 12px;
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
        padding: 18px 10px;
        overflow: hidden;

        box-shadow: 0 6px 20px rgba(41, 45, 143, .045);

        text-decoration: none !important;

        transition:
            transform .2s ease,
            box-shadow .2s ease;
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

    .status-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(41, 45, 143, .08);
        text-decoration: none !important;
    }

    /* =========================================================
       WARNA CARD
    ========================================================= */

    .respondent-card,
    .respondent-card:hover,
    .respondent-card:visited,
    .respondent-card:focus,
    .respondent-card:active {
        color: #3155C6 !important;
    }

    .belum-card,
    .belum-card:hover,
    .belum-card:visited,
    .belum-card:focus,
    .belum-card:active {
        color: #7C3AED !important;
    }

    .draft-card,
    .draft-card:hover,
    .draft-card:visited,
    .draft-card:focus,
    .draft-card:active {
        color: #6B7280 !important;
    }

    .submit-card,
    .submit-card:hover,
    .submit-card:visited,
    .submit-card:focus,
    .submit-card:active {
        color: #D99A00 !important;
        background: #FFFFFF !important;
        text-decoration: none !important;
    }

    .reject-card,
    .reject-card:hover,
    .reject-card:visited,
    .reject-card:focus,
    .reject-card:active {
        color: #D14B4B !important;
        background: #FFFFFF !important;
        text-decoration: none !important;
    }

    .accept-card,
    .accept-card:hover,
    .accept-card:visited,
    .accept-card:focus,
    .accept-card:active {
        color: #15916D !important;
        background: #FFFFFF !important;
        text-decoration: none !important;
    }

    /* =========================================================
       JUDUL CARD
    ========================================================= */

    .status-title {
        margin: 0;
        color: #27305E !important;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.35;
    }

    /* =========================================================
       ANGKA CARD
    ========================================================= */

    .status-count {
        margin-top: 9px;
        font-size: 29px;
        font-weight: 800;
        letter-spacing: -1px;
        line-height: 1;
    }

    .respondent-card .status-count {
        color: #3155C6 !important;
    }

    .belum-card .status-count {
        color: #7C3AED !important;
    }

    .draft-card .status-count {
        color: #4B5563 !important;
    }

    .submit-card .status-count,
    .submit-card:hover .status-count {
        color: #D99A00 !important;
    }

    .reject-card .status-count,
    .reject-card:hover .status-count {
        color: #C43E3E !important;
    }

    .accept-card .status-count,
    .accept-card:hover .status-count {
        color: #138360 !important;
    }

    /* =========================================================
       DRAFT SECTION
    ========================================================= */

    .draft-section {
        margin-top: 28px;
        margin-bottom: 24px;
    }

    .draft-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 14px;
    }

    .draft-heading h2 {
        margin: 0 0 5px;
        color: #252A86;
        font-size: 21px;
        font-weight: 800;
        letter-spacing: -.3px;
    }

    .draft-heading p {
        margin: 0;
        color: #68738A;
        font-size: 13px;
        line-height: 1.5;
    }

    /* =========================================================
       TABLE CARD
    ========================================================= */

    .draft-table-card {
        background: #FFFFFF;
        border: 1px solid #E5E8F0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 18px rgba(25, 35, 70, 0.05);
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .draft-table {
        width: 100%;
        min-width: 1050px;
        border-collapse: collapse;
    }

    .draft-table thead {
        background: #F7F8FC;
    }

    .draft-table th {
        padding: 15px 16px;
        text-align: left;
        font-size: 13px;
        font-weight: 800;
        color: #39415C;
        border-bottom: 1px solid #E5E8F0;
        white-space: nowrap;
    }

    .draft-table td {
        padding: 16px;
        border-bottom: 1px solid #EDF0F5;
        color: #3F465A;
        font-size: 14px;
        vertical-align: middle;
    }

    .draft-table tbody tr:last-child td {
        border-bottom: none;
    }

    .draft-table tbody tr:hover {
        background: #FAFBFE;
    }

    /* =========================================================
       DATA
    ========================================================= */

    .row-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #F1F3F8;
        color: #4B5368;
        font-weight: 700;
    }

    .data-primary {
        color: #3F465A;
        font-weight: 600;
        white-space: nowrap;
    }

    .family-name {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .family-name strong {
        color: #30384D;
        font-size: 14px;
        font-weight: 700;
    }

    .family-name small {
        color: #8A92A5;
        font-size: 12px;
    }

    /* =========================================================
       PROGRESS
    ========================================================= */

    .progress-wrapper {
        width: 145px;
    }

    .progress-label {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
        color: #596277;
        font-size: 12px;
    }

    .progress-label strong {
        color: #252A86;
        font-size: 12px;
    }

    .progress-track {
        width: 100%;
        height: 7px;
        background: #E9EDF4;
        border-radius: 20px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: #55B5D5;
        border-radius: 20px;
        transition: width .25s ease;
    }

    /* =========================================================
       DATE
    ========================================================= */

    .date-info {
        display: flex;
        flex-direction: column;
        gap: 3px;
        white-space: nowrap;
    }

    .date-info strong {
        color: #4A5266;
        font-size: 13px;
    }

    .date-info small {
        color: #8B93A5;
        font-size: 12px;
    }

    /* =========================================================
       BUTTON LANJUTKAN
    ========================================================= */

    .btn-lanjutkan {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 13px;
        border-radius: 8px;
        background: #252A86;
        color: #FFFFFF !important;
        text-decoration: none !important;
        font-size: 13px;
        font-weight: 700;
        transition:
            transform .2s ease,
            opacity .2s ease,
            box-shadow .2s ease;
    }

    .btn-lanjutkan:hover {
        opacity: .92;
        transform: translateY(-1px);
        box-shadow: 0 5px 12px rgba(37, 42, 134, .18);
        color: #FFFFFF !important;
        text-decoration: none !important;
    }

    .btn-icon {
        font-size: 15px;
    }

    /* =========================================================
       PAGINATION
    ========================================================= */

    .draft-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 16px 18px;
        border-top: 1px solid #EDF0F5;
    }

    .pagination-info {
        color: #737C91;
        font-size: 13px;
    }

    .pagination-info strong {
        color: #424A5F;
    }

    .pagination-links {
        display: flex;
        align-items: center;
    }

    /* =========================================================
       EMPTY
    ========================================================= */

    .empty-draft {
        background: #FFFFFF;
        border: 1px solid #E5E8F0;
        border-radius: 16px;
        padding: 50px 20px;
        text-align: center;
        box-shadow: 0 5px 18px rgba(25, 35, 70, 0.04);
    }

    .empty-draft-icon {
        width: 52px;
        height: 52px;
        margin: 0 auto 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #EDF8F1;
        color: #299447;
        font-size: 22px;
        font-weight: 800;
    }

    .empty-draft h4 {
        margin: 0 0 7px;
        color: #39415A;
        font-size: 17px;
        font-weight: 800;
    }

    .empty-draft p {
        margin: 0;
        color: #7D8598;
        font-size: 14px;
    }

    /* =========================================================
       MULAI KUISIONER BARU
    ========================================================= */

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
        color: var(--primary) !important;
        border: 1px solid rgba(255,255,255,.8);
        border-radius: 10px;
        text-decoration: none !important;
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
        color: var(--primary-dark) !important;
        transform: translateY(-2px);
        text-decoration: none !important;
    }

    .btn-start:hover::after {
        transform: translateX(3px);
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1150px) {

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

        .draft-pagination {
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

        {{-- =====================================================
             ALERT
        ====================================================== --}}

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


        {{-- =====================================================
             HEADER
        ====================================================== --}}

        <div class="page-header">

            <h1>
                Form Kuisioner
            </h1>

            <p>
                Kelola pendataan keluarga berdasarkan status proses pendataan.
            </p>

        </div>


        {{-- =====================================================
             STATUS CARD
        ====================================================== --}}

        <div class="status-grid">

            {{-- TOTAL RESPONDEN --}}
            <div class="status-card respondent-card">
                <div>

                    <h2 class="status-title">
                        Total Responden
                    </h2>

                    <div class="status-count">
                        {{ $respondenCount ?? 0 }}
                    </div>

                </div>
            </div>


            {{-- BELUM DIDATA --}}
            <div class="status-card belum-card">
                <div>

                    <h2 class="status-title">
                        Belum Didata
                    </h2>

                    <div class="status-count">
                        {{ $belumDidataCount ?? 0 }}
                    </div>

                </div>
            </div>


            {{-- DRAFT --}}
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


            {{-- MENUNGGU VERIFIKASI --}}
            <a
                href="{{ route('kuisioner.selesai') }}"
                class="status-card submit-card"
            >
                <div>

                    <h2 class="status-title">
                        Menunggu Verifikasi
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


            {{-- DISETUJUI --}}
            <a
                href="{{ route('verifikasi.index', ['status' => 'disetujui']) }}"
                class="status-card accept-card"
            >
                <div>

                    <h2 class="status-title">
                        Disetujui
                    </h2>

                    <div class="status-count">
                        {{ $diterimaCount ?? 0 }}
                    </div>

                </div>
            </a>

        </div>


        {{-- =====================================================
             DRAFT KUISIONER
        ====================================================== --}}

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

            </div>


            @if(isset($drafts) && $drafts->count() > 0)

                <div class="draft-table-card">

                    <div class="table-responsive">

                        <table class="draft-table">

                            <thead>

                                <tr>

                                    <th width="60">
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
                                        Progress
                                    </th>

                                    <th>
                                        Terakhir Diperbarui
                                    </th>

                                    <th width="150">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($drafts as $index => $item)

                                    @php

                                        $currentPart = (int) (
                                            $item->current_part ?? 1
                                        );

                                        $completedPart = max(
                                            0,
                                            min(
                                                5,
                                                $currentPart - 1
                                            )
                                        );

                                        $progressPercent = (
                                            $completedPart / 5
                                        ) * 100;

                                    @endphp


                                    <tr>

                                        {{-- NOMOR --}}
                                        <td>

                                            <span class="row-number">
                                                {{ $drafts->firstItem() + $index }}
                                            </span>

                                        </td>


                                        {{-- NO KK --}}
                                        <td>

                                            <span class="data-primary">
                                                {{ $item->no_kk ?? '-' }}
                                            </span>

                                        </td>


                                        {{-- NIK --}}
                                        <td>

                                            <span class="data-primary">
                                                {{ $item->nik ?? '-' }}
                                            </span>

                                        </td>


                                        {{-- NAMA KEPALA KELUARGA --}}
                                        <td>

                                            <div class="family-name">

                                                <strong>
                                                    {{ $item->nama_kepala_keluarga ?? '-' }}
                                                </strong>

                                                <small>
                                                    Data kuisioner keluarga
                                                </small>

                                            </div>

                                        </td>


                                        {{-- PROGRESS --}}
                                        <td>

                                            <div class="progress-wrapper">

                                                <div class="progress-label">

                                                    <span>
                                                        Part {{ $completedPart }}/5
                                                    </span>

                                                    <strong>
                                                        {{ number_format($progressPercent, 0) }}%
                                                    </strong>

                                                </div>


                                                <div class="progress-track">

                                                    <div
                                                        class="progress-fill"
                                                        style="width: {{ $progressPercent }}%;"
                                                    ></div>

                                                </div>

                                            </div>

                                        </td>


                                        {{-- TERAKHIR DIPERBARUI --}}
                                        <td>

                                            @if($item->updated_at)

                                                <div class="date-info">

                                                    <strong>
                                                        {{ $item->updated_at->format('d/m/Y') }}
                                                    </strong>

                                                    <small>
                                                        {{ $item->updated_at->format('H:i') }}
                                                    </small>

                                                </div>

                                            @else

                                                -

                                            @endif

                                        </td>


                                        {{-- AKSI --}}
                                        <td>

                                            <a
                                                href="{{ route('kuisioner.draft.resume', $item->id) }}"
                                                class="btn-lanjutkan"
                                            >

                                                <span class="btn-icon">
                                                    ↻
                                                </span>

                                                <span>
                                                    Lanjutkan
                                                </span>

                                            </a>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- =================================================
                         PAGINATION
                    ================================================== --}}

                    @if($drafts->hasPages())

                        <div class="draft-pagination">

                            <div class="pagination-info">

                                Menampilkan

                                <strong>
                                    {{ $drafts->firstItem() }}
                                </strong>

                                sampai

                                <strong>
                                    {{ $drafts->lastItem() }}
                                </strong>

                                dari

                                <strong>
                                    {{ $drafts->total() }}
                                </strong>

                                data draft

                            </div>


                            <div class="pagination-links">

                                {{ $drafts->onEachSide(1)->links() }}

                            </div>

                        </div>

                    @endif

                </div>


            @else

                {{-- =================================================
                     EMPTY STATE
                ================================================== --}}

                <div class="empty-draft">

                    <div class="empty-draft-icon">
                        ✓
                    </div>

                    <h4>
                        Belum ada draft kuisioner
                    </h4>

                    <p>
                        Data kuisioner yang belum selesai akan muncul di sini.
                    </p>

                </div>

            @endif

        </div>


        {{-- =====================================================
             MULAI KUISIONER BARU
        ====================================================== --}}

        <div class="new-kuisioner">

            <div class="new-kuisioner-content">

                <h2>
                    Mulai Kuisioner Baru
                </h2>

                <p>
                    Mari mulai pendataan keluarga dengan data yang akurat dan lengkap.
                </p>

            </div>


            <a
                href="{{ route('kuisioner.pilih-responden') }}"
                class="btn-start"
            >
                Mulai Kuisioner
            </a>

        </div>

    </div>

</div>

@endsection