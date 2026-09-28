@extends('admin.layouts.app')

@section('content')

<style>
    .kuisioner-page {
        background: #f5f6fa;
        min-height: calc(100vh - 80px);
        padding: 36px;
    }

    .kuisioner-container {
        max-width: 1280px;
        margin: 0 auto;
    }

    /* HEADER */
    .page-header {
        background: #ffffff;
        border-radius: 18px;
        padding: 34px 32px;
        margin-bottom: 28px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
    }

    .page-header .eyebrow {
        color: #252a86;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .page-header h1 {
        margin: 0;
        color: #18213d;
        font-size: 36px;
        font-weight: 800;
        line-height: 1.2;
    }

    .page-header p {
        margin: 12px 0 0;
        color: #64748b;
        font-size: 16px;
        line-height: 1.6;
    }

    /* CARD WRAPPER */
    .status-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 24px;
        margin-bottom: 24px;
    }

    /* STATUS CARD */
    .status-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 30px 32px;
        min-height: 300px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        border: 1px solid #edf0f5;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s ease;
    }

    .status-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(0,0,0,0.07);
    }

    .status-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 22px;
    }

    .draft-icon {
        background: #fff5e9;
    }

    .finish-icon {
        background: #e9fbf4;
    }

    .status-card h2 {
        margin: 0 0 10px;
        color: #18213d;
        font-size: 25px;
        font-weight: 800;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .status-card .description {
        margin: 0;
        color: #64748b;
        font-size: 15px;
        line-height: 1.6;
    }

    .status-count {
        margin-top: 30px;
        color: #252a86;
        font-size: 34px;
        font-weight: 800;
        line-height: 1;
    }

    .status-label {
        margin-top: 8px;
        color: #64748b;
        font-size: 14px;
    }

    .status-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 24px;
        color: #252a86;
        text-decoration: none;
        font-size: 15px;
        font-weight: 700;
    }

    .status-link:hover {
        text-decoration: none;
        color: #171b69;
    }

    /* MULAI BARU */
    .new-kuisioner {
        background: #252a86;
        border-radius: 18px;
        padding: 28px 32px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        box-shadow: 0 5px 18px rgba(37,42,134,0.18);
    }

    .new-kuisioner h2 {
        margin: 0;
        color: #ffffff;
        font-size: 24px;
        font-weight: 800;
    }

    .new-kuisioner p {
        margin: 8px 0 0;
        color: rgba(255,255,255,0.82);
        font-size: 14px;
    }

    .btn-start {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        color: #252a86;
        border-radius: 10px;
        padding: 13px 22px;
        min-width: 180px;
        text-decoration: none;
        font-size: 15px;
        font-weight: 800;
        transition: all 0.2s ease;
    }

    .btn-start:hover {
        background: #f1f3ff;
        color: #171b69;
        text-decoration: none;
        transform: translateY(-1px);
    }

    /* FLASH MESSAGE */
    .alert {
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 20px;
        font-size: 14px;
        font-weight: 600;
    }

    .alert-success {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .alert-warning {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    /* RESPONSIVE */
    @media (max-width: 900px) {
        .status-grid {
            grid-template-columns: 1fr;
        }

        .new-kuisioner {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn-start {
            width: 100%;
        }
    }

    @media (max-width: 640px) {
        .kuisioner-page {
            padding: 20px;
        }

        .page-header {
            padding: 25px 22px;
        }

        .page-header h1 {
            font-size: 28px;
        }

        .status-card {
            padding: 25px 22px;
        }

        .new-kuisioner {
            padding: 25px 22px;
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

            <div class="eyebrow">
                Kuisioner Pendataan Keluarga
            </div>

            <h1>
                Form Kuisioner
            </h1>

            <p>
                Kelola pendataan keluarga yang sedang berjalan maupun
                yang telah selesai.
            </p>

        </div>


        {{-- STATUS CARD --}}
        <div class="status-grid">

            {{-- DRAFT --}}
            <div class="status-card">

                <div>

                    <div class="status-icon draft-icon">
                        📋
                    </div>

                    <h2>
                        Draft Kuisioner
                    </h2>

                    <p class="description">
                        Data keluarga yang proses pendataannya
                        masih belum selesai.
                    </p>

                    <div class="status-count">
                        {{ $drafts->count() }}
                    </div>

                    <div class="status-label">
                        Data belum selesai
                    </div>

                </div>

                <div>
                    <a href="{{ route('kuisioner.draft') }}"
                       class="status-link">
                        Lihat Draft
                        <span>→</span>
                    </a>
                </div>

            </div>


            {{-- DATA SELESAI --}}
            <div class="status-card">

                <div>

                    <div class="status-icon finish-icon">
                        ✓
                    </div>

                    <h2>
                        Data Selesai
                    </h2>

                    <p class="description">
                        Data keluarga yang seluruh proses
                        kuisionernya telah selesai.
                    </p>

                    <div class="status-count">
                        {{ $selesaiCount ?? 0 }}
                    </div>

                    <div class="status-label">
                        Data telah selesai
                    </div>

                </div>

                <div>
                    <a href="{{ route('kuisioner.selesai') }}"
                       class="status-link">
                        Lihat Data
                        <span>→</span>
                    </a>
                </div>

            </div>

        </div>


        {{-- MULAI KUISIONER BARU --}}
        <div class="new-kuisioner">

            <div>

                <h2>
                    + Mulai Kuisioner Baru
                </h2>

                <p>
                    Mulai pendataan keluarga baru dari Part 1.
                </p>

            </div>

            <a href="{{ route('kuisioner.part1') }}"
            class="btn-start">
                Mulai Kuisioner
            </a>

        </div>

    </div>

</div>

@endsection