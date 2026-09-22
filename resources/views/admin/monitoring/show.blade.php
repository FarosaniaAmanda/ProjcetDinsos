@extends('admin.layouts.app')

@section('title', 'Detail Data')

@push('styles')
<style>
    /* =====================================================
       BACK BUTTON
    ====================================================== */

    .back-row {
        margin-bottom: 18px;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        min-height: 36px;
        padding: 8px 12px;
        background: #ffffff;
        border: 1px solid #d9ddef;
        border-radius: 8px;
        color: #252A86;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        transition: all .2s ease;
    }

    .back-button:hover {
        background: #eef0ff;
        border-color: #252A86;
        transform: translateX(-1px);
    }

    .back-icon {
        font-size: 15px;
        line-height: 1;
    }


    /* =====================================================
       PAGE HEADER
    ====================================================== */

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


    /* =====================================================
       SUMMARY
    ====================================================== */

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }

    .summary-card {
        background: #ffffff;
        border: 1px solid #e7e8ee;
        border-radius: 12px;
        padding: 18px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }

    .summary-label {
        font-size: 11px;
        color: #777;
        margin-bottom: 8px;
    }

    .summary-value {
        font-size: 16px;
        font-weight: 700;
        color: #252A86;
        word-break: break-word;
    }


    /* =====================================================
       DETAIL PANEL
    ====================================================== */

    .data-panel {
        background: #ffffff;
        border: 1px solid #e7e8ee;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .data-panel-header {
        padding: 20px 22px;
        border-bottom: 1px solid #ececf1;
    }

    .data-panel-title {
        font-size: 17px;
        font-weight: 700;
        color: #252525;
    }

    .data-panel-description {
        font-size: 12px;
        color: #888;
        margin-top: 4px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        padding: 22px;
    }

    .info-item {
        padding: 14px;
        background: #f8f8fb;
        border: 1px solid #e7e8ee;
        border-radius: 9px;
    }

    .info-label {
        display: block;
        font-size: 11px;
        color: #777;
        margin-bottom: 6px;
    }

    .info-value {
        font-size: 13px;
        font-weight: 600;
        color: #333;
        word-break: break-word;
    }


    /* =====================================================
       STATUS
    ====================================================== */

    .status {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-warning {
        background: #fff7df;
        color: #a87900;
    }

    .status-success {
        background: #e8f7ee;
        color: #21864a;
    }

    .status-info {
        background: #eaf0ff;
        color: #3d5ab8;
    }


    /* =====================================================
       KUISIONER
    ====================================================== */

    .questionnaire-empty {
        margin: 22px;
        padding: 35px 20px;
        text-align: center;
        border: 1px dashed #d7d9e5;
        border-radius: 10px;
        background: #fafaff;
    }

    .questionnaire-icon {
        width: 45px;
        height: 45px;
        margin: 0 auto 12px;
        border-radius: 50%;
        background: #eef0ff;
        color: #252A86;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .questionnaire-empty h3 {
        font-size: 14px;
        color: #333;
        margin-bottom: 6px;
    }

    .questionnaire-empty p {
        font-size: 12px;
        color: #888;
    }


    /* =====================================================
       FOOTER
    ====================================================== */

    .page-footer {
        text-align: center;
        color: #888;
        font-size: 11px;
        padding: 10px 0 20px;
    }


    /* =====================================================
       TABLET
    ====================================================== */

    @media (max-width: 900px) {

        .summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }


    /* =====================================================
       MOBILE
    ====================================================== */

    @media (max-width: 700px) {

        .page-title {
            font-size: 23px;
        }

        .page-description {
            font-size: 13px;
        }

        .summary-grid {
            grid-template-columns: 1fr;
        }

        .info-grid {
            grid-template-columns: 1fr;
            padding: 16px;
        }

        .data-panel-header {
            padding: 17px 16px;
        }

        .data-panel-title {
            font-size: 15px;
        }

        .questionnaire-empty {
            margin: 16px;
        }
    }


    /* =====================================================
       HP KECIL
    ====================================================== */

    @media (max-width: 430px) {

        .page-title {
            font-size: 21px;
        }

        .back-button {
            min-height: 34px;
            padding: 7px 11px;
        }

        .summary-card {
            padding: 15px;
        }

        .data-panel {
            border-radius: 10px;
        }
    }
</style>
@endpush


@section('content')

    <!-- =====================================================
         KEMBALI
    ====================================================== -->

    <div class="back-row">

        <a
            href="{{ route('monitoring.index') }}"
            class="back-button"
        >
            <span class="back-icon">←</span>
            <span>Kembali</span>
        </a>

    </div>


    <!-- =====================================================
         PAGE HEADER
    ====================================================== -->

    <div class="page-header">

        <div class="page-kicker">
            ADMIN
        </div>

        <h1 class="page-title">
            Detail Data Pendataan
        </h1>

        <p class="page-description">
            Informasi lengkap data responden dan hasil pendataan.
        </p>

    </div>


    <!-- =====================================================
         SUMMARY
    ====================================================== -->

    <div class="summary-grid">

        <!-- Nama Responden -->
        <div class="summary-card">

            <div class="summary-label">
                Nama Responden
            </div>

            <div class="summary-value">
                {{ $item['nama'] }}
            </div>

        </div>


        <!-- Nomor KK -->
        <div class="summary-card">

            <div class="summary-label">
                Nomor KK
            </div>

            <div class="summary-value">
                {{ $item['no_kk'] }}
            </div>

        </div>


        <!-- Status Pendataan -->
        <div class="summary-card">

            <div class="summary-label">
                Status Pendataan
            </div>

            <div class="summary-value">

                @if($item['status'] === 'Menunggu')

                    <span class="status status-warning">
                        Menunggu
                    </span>

                @elseif($item['status'] === 'Selesai')

                    <span class="status status-success">
                        Selesai
                    </span>

                @else

                    <span class="status status-info">
                        Diproses
                    </span>

                @endif

            </div>

        </div>

    </div>


    <!-- =====================================================
         INFORMASI RESPONDEN
    ====================================================== -->

    <div class="data-panel">

        <div class="data-panel-header">

            <div class="data-panel-title">
                Informasi Responden
            </div>

            <div class="data-panel-description">
                Data identitas dan informasi pendataan responden.
            </div>

        </div>


        <div class="info-grid">

            <!-- Nomor KK -->
            <div class="info-item">

                <span class="info-label">
                    Nomor KK
                </span>

                <div class="info-value">
                    {{ $item['no_kk'] }}
                </div>

            </div>


            <!-- NIK -->
            <div class="info-item">

                <span class="info-label">
                    NIK
                </span>

                <div class="info-value">
                    {{ $item['nik'] }}
                </div>

            </div>


            <!-- Nama Lengkap -->
            <div class="info-item">

                <span class="info-label">
                    Nama Lengkap
                </span>

                <div class="info-value">
                    {{ $item['nama'] }}
                </div>

            </div>


            <!-- Jumlah Anggota -->
            <div class="info-item">

                <span class="info-label">
                    Jumlah Anggota Keluarga
                </span>

                <div class="info-value">
                    {{ $item['anggota'] }} Orang
                </div>

            </div>


            <!-- Wilayah -->
            <div class="info-item">

                <span class="info-label">
                    Wilayah
                </span>

                <div class="info-value">
                    {{ $item['wilayah'] }}
                </div>

            </div>


            <!-- Petugas -->
            <div class="info-item">

                <span class="info-label">
                    Petugas
                </span>

                <div class="info-value">
                    {{ $item['petugas'] }}
                </div>

            </div>


            <!-- Tanggal Pendataan -->
            <div class="info-item">

                <span class="info-label">
                    Tanggal Pendataan
                </span>

                <div class="info-value">
                    {{ $item['tanggal'] }}
                </div>

            </div>


            <!-- Status -->
            <div class="info-item">

                <span class="info-label">
                    Status
                </span>

                <div class="info-value">

                    @if($item['status'] === 'Menunggu')

                        <span class="status status-warning">
                            Menunggu
                        </span>

                    @elseif($item['status'] === 'Selesai')

                        <span class="status status-success">
                            Selesai
                        </span>

                    @else

                        <span class="status status-info">
                            Diproses
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         HASIL KUISIONER
    ====================================================== -->

    <div class="data-panel">

        <div class="data-panel-header">

            <div class="data-panel-title">
                Hasil Kuisioner
            </div>

            <div class="data-panel-description">
                Riwayat jawaban kuisioner responden.
            </div>

        </div>


        <div class="questionnaire-empty">

            <div class="questionnaire-icon">

                <svg
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>

                    <polyline points="14 2 14 8 20 8"></polyline>

                    <line
                        x1="8"
                        y1="13"
                        x2="16"
                        y2="13"
                    ></line>

                    <line
                        x1="8"
                        y1="17"
                        x2="16"
                        y2="17"
                    ></line>
                </svg>

            </div>


            <h3>
                Belum Ada Hasil Kuisioner
            </h3>

            <p>
                Data hasil kuisioner untuk responden ini belum tersedia.
            </p>

        </div>

    </div>


    <!-- =====================================================
         FOOTER
    ====================================================== -->

    <div class="page-footer">
        Sistem Pendataan Dinas Sosial Kota Pasuruan
    </div>

@endsection