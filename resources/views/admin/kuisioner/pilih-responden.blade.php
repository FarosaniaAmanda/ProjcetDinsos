@extends('admin.layouts.app')

@section('title', 'Pilih Responden')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --primary: #292D8F;
        --primary-dark: #222675;
        --primary-soft: #F0F1FF;
        --border: #E2E4EF;
        --text: #25283A;
        --muted: #777D91;
        --background: #F5F6FB;
        --white: #fff;
    }

    .responden-page,
    .responden-page * {
        box-sizing: border-box;
    }

    .responden-page {
        width: 100%;
        min-height: 100vh;
        padding: 20px 24px 40px;
        background: var(--background);
        color: var(--text);
        font-family: 'Inter', sans-serif;
    }

    .responden-container {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
    }

    /* HEADER */

    .page-header {
        margin-bottom: 20px;
    }

    .page-header h2 {
        margin: 0;
        color: var(--primary-dark);
        font-size: 24px;
        font-weight: 800;
        line-height: 1.4;
    }

    .page-header p {
        margin: 7px 0 0;
        color: var(--muted);
        font-size: 14px;
        line-height: 1.6;
    }

    /* CARD */

    .responden-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 17px;
        box-shadow: 0 5px 20px rgba(35, 40, 80, .05);
        overflow: hidden;
    }

    /* TOP */

    .card-top {
        padding: 23px 26px 20px;
        border-bottom: 1px solid var(--border);
        background: #FBFBFE;
    }

    .card-title {
        margin: 0;
        color: var(--primary-dark);
        font-size: 19px;
        font-weight: 800;
    }

    .card-description {
        margin: 6px 0 0;
        color: var(--muted);
        font-size: 14px;
        line-height: 1.6;
    }

    /* SEARCH */

    .search-area {
        padding: 20px 26px;
    }

    .search-wrapper {
        position: relative;
    }

    .search-input {
        width: 100%;
        height: 48px;
        padding: 0 16px 0 45px;
        border: 1px solid #D9DCE8;
        border-radius: 10px;
        background: #fff;
        color: var(--text);
        font-family: inherit;
        font-size: 14px;
        outline: none;
        transition: .2s ease;
    }

    .search-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(41, 45, 143, .08);
    }

    .search-input::placeholder {
        color: #A2A6B5;
    }

    .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #858A9E;
        font-size: 17px;
        pointer-events: none;
    }

    /* TABLE */

    .table-wrapper {
        padding: 0 26px 26px;
        overflow-x: auto;
    }

    .responden-table {
        width: 100%;
        min-width: 650px;
        border-collapse: collapse;
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
    }

    .responden-table th {
        padding: 14px 16px;
        background: #F7F8FC;
        border-bottom: 1px solid var(--border);
        color: #4C5164;
        font-size: 13px;
        font-weight: 800;
        text-align: left;
        white-space: nowrap;
    }

    .responden-table td {
        padding: 15px 16px;
        border-bottom: 1px solid #ECEEF5;
        color: var(--text);
        font-size: 14px;
        vertical-align: middle;
    }

    .responden-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .responden-table tbody tr:hover {
        background: #FAFAFE;
    }

    /* NUMBER */

    .row-number {
        width: 55px;
        text-align: center !important;
        color: #73788B !important;
        font-weight: 600;
    }

    /* NAME */

    .responden-name {
        font-weight: 700;
        color: var(--text);
        line-height: 1.5;
    }

    .responden-family {
        margin-top: 3px;
        color: var(--muted);
        font-size: 12px;
    }

    /* BUTTON */

    .btn-pilih {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 82px;
        padding: 9px 15px;
        border: 0;
        border-radius: 8px;
        background: var(--primary);
        color: #fff !important;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none !important;
        cursor: pointer;
        transition: .2s ease;
    }

    .btn-pilih:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 5px 12px rgba(41, 45, 143, .18);
    }

    /* EMPTY */

    .empty-state {
        padding: 45px 20px !important;
        text-align: center;
        color: var(--muted) !important;
    }

    .empty-title {
        margin-bottom: 5px;
        color: #505568;
        font-size: 15px;
        font-weight: 700;
    }

    .empty-text {
        font-size: 13px;
    }

    /* FOOTER */

    .card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 18px 26px;
        border-top: 1px solid var(--border);
        background: #FBFBFD;
        flex-wrap: wrap;
    }

    .pagination-info {
        color: #6B7280;
        font-size: 13px;
    }

    .pagination {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .page-btn {
        min-width: 35px;
        height: 35px;
        padding: 0 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #D9DCE8;
        border-radius: 8px;
        background: #fff;
        color: var(--primary);
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: .2s ease;
    }

    .page-btn:hover {
        background: var(--primary-soft);
        border-color: var(--primary);
    }

    .page-btn.active {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }

    .page-btn.disabled {
        color: #B8BBC7;
        background: #F5F6FA;
        cursor: not-allowed;
    }

    /* BACK */

    .back-area {
        margin-top: 16px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 10px 16px;
        border: 1px solid #D9DCE8;
        border-radius: 9px;
        background: #fff;
        color: #656A7C !important;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none !important;
        transition: .2s ease;
    }

    .btn-back:hover {
        background: #F5F6FB;
        border-color: #C8CBDC;
        color: var(--primary) !important;
    }

    /* RESPONSIVE */

    @media (max-width: 700px) {

        .responden-page {
            padding: 14px 14px 30px;
        }

        .page-header h2 {
            font-size: 20px;
        }

        .card-top {
            padding: 19px 18px;
        }

        .search-area {
            padding: 16px 18px;
        }

        .table-wrapper {
            padding: 0 18px 20px;
        }

        .card-footer {
            padding: 16px 18px;
            flex-direction: column;
            align-items: stretch;
        }

        .pagination {
            justify-content: center;
        }

        .back-area {
            margin-top: 13px;
        }
    }
</style>


<div class="responden-page">

    <div class="responden-container">

        {{-- HEADER --}}
        <div class="page-header">

            <h2>
                Pilih Responden
            </h2>

            <p>
                Pilih responden yang belum didata untuk memulai kuisioner.
            </p>

        </div>


        {{-- CARD --}}
        <div class="responden-card">

            {{-- CARD TOP --}}
            <div class="card-top">

                <h3 class="card-title">
                    Daftar Responden Belum Didata
                </h3>

                <p class="card-description">
                    Pilih responden yang belum pernah memulai pendataan kuisioner.
                </p>

            </div>


            {{-- SEARCH --}}
            <div class="search-area">

                <form
                    method="GET"
                    action="{{ route('kuisioner.pilih-responden') }}"
                >

                    <div class="search-wrapper">

                        <span class="search-icon">
                            ⌕
                        </span>

                        <input
                            type="text"
                            name="search"
                            class="search-input"
                            value="{{ $search ?? request('search') }}"
                            placeholder="Cari nama, NIK, atau nomor KK..."
                            autocomplete="off"
                        >

                    </div>

                </form>

            </div>


            {{-- TABLE --}}
            <div class="table-wrapper">

                <table class="responden-table">

                    <thead>

                        <tr>

                            <th class="row-number">
                                No
                            </th>

                            <th>
                                Nama Responden
                            </th>

                            <th>
                                NIK
                            </th>

                            <th>
                                No. KK
                            </th>

                            <th>
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($responden as $index => $item)

                            @php
                                /*
                                 * Nama yang ditampilkan:
                                 * Kepala Keluarga / Anggota Keluarga Pertama
                                 */

                                $kepalaKeluarga = $item->nama_lengkap ?? '-';

                                $anggotaPertama = null;

                                if (
                                    isset($item->anggota) &&
                                    $item->anggota->count() > 0
                                ) {
                                    $anggotaPertama = $item->anggota->first();
                                }

                                $namaAnggota = $anggotaPertama->nama_lengkap ?? null;

                                $namaTampilan = $kepalaKeluarga;

                                if (
                                    $namaAnggota &&
                                    strtolower(trim($namaAnggota)) !==
                                    strtolower(trim($kepalaKeluarga))
                                ) {
                                    $namaTampilan =
                                        $kepalaKeluarga .
                                        ' / ' .
                                        $namaAnggota;
                                }
                            @endphp


                            <tr>

                                <td class="row-number">
                                    {{ $responden->firstItem() + $index }}
                                </td>


                                <td>

                                    <div class="responden-name">
                                        {{ $namaTampilan }}
                                    </div>

                                </td>


                                <td>
                                    {{ $item->nik ?: '-' }}
                                </td>


                                <td>
                                    {{ $item->no_kk ?: '-' }}
                                </td>


                                <td>

                                    <a
                                        href="{{ route('kuisioner.part1', ['keluarga' => $item->kode]) }}"
                                        class="btn-pilih"
                                    >
                                        Pilih
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="empty-state"
                                >

                                    <div class="empty-text">
                                        Hasil Pencarian tidak ditemukan
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}
            @if($responden->hasPages())

                <div class="card-footer">

                    <div class="pagination-info">

                        Menampilkan
                        {{ $responden->firstItem() }}
                        -
                        {{ $responden->lastItem() }}
                        dari
                        {{ $responden->total() }}
                        responden

                    </div>


                    <div class="pagination">

                        @if($responden->onFirstPage())

                            <span class="page-btn disabled">
                                ‹
                            </span>

                        @else

                            <a
                                href="{{ $responden->previousPageUrl() }}"
                                class="page-btn"
                            >
                                ‹
                            </a>

                        @endif


                        @for(
                            $page = 1;
                            $page <= $responden->lastPage();
                            $page++
                        )

                            @if($page == $responden->currentPage())

                                <span class="page-btn active">
                                    {{ $page }}
                                </span>

                            @else

                                <a
                                    href="{{ $responden->url($page) }}"
                                    class="page-btn"
                                >
                                    {{ $page }}
                                </a>

                            @endif

                        @endfor


                        @if($responden->hasMorePages())

                            <a
                                href="{{ $responden->nextPageUrl() }}"
                                class="page-btn"
                            >
                                ›
                            </a>

                        @else

                            <span class="page-btn disabled">
                                ›
                            </span>

                        @endif

                    </div>

                </div>

            @endif

        </div>


        {{-- KEMBALI --}}
        <div class="back-area">

            <a
                href="{{ route('kuisioner.index') }}"
                class="btn-back"
            >
                ← Kembali ke Kuisioner
            </a>

        </div>

    </div>

</div>

@endsection