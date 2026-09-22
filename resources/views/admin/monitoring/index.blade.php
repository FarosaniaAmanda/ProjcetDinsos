@extends('admin.layouts.app')

@section('title', 'Monitoring')

@push('styles')
<style>
    /* =====================================================
       MONITORING PAGE
    ====================================================== */

    .monitoring-page-header {
        margin-bottom: 24px;
    }

    .monitoring-page-label {
        font-size: 11px;
        font-weight: 700;
        color: #252A86;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 7px;
    }

    .monitoring-page-title {
        font-size: 27px;
        font-weight: 700;
        color: #252A86;
        margin-bottom: 7px;
    }

    .monitoring-page-description {
        font-size: 13px;
        color: #777;
        line-height: 1.6;
    }


    /* =====================================================
   SEARCH PANEL
====================================================== */

.monitoring-search-panel {
    background: #ffffff;
    border: 1px solid #e1e3eb;
    border-radius: 12px;
    padding: 18px;
    margin-bottom: 20px;
}

.monitoring-search-form {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
}

.monitoring-search-input-wrapper {
    position: relative;
    flex: 1;
    min-width: 0;
}

.monitoring-search-icon {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    width: 17px;
    height: 17px;
    color: #999;
    pointer-events: none;
}

.monitoring-search-input {
    width: 100%;
    height: 42px;
    border: 1px solid #dfe1e8;
    border-radius: 8px;
    background: #ffffff;
    padding: 0 14px 0 40px;
    font-size: 13px;
    color: #333;
    outline: none;
    transition: all .2s ease;
    box-sizing: border-box;
}

.monitoring-search-input::placeholder {
    color: #999;
}

.monitoring-search-input:focus {
    border-color: #252A86;
    box-shadow: 0 0 0 3px rgba(37, 42, 134, 0.08);
}

.monitoring-search-button {
    height: 42px;
    padding: 0 18px;
    border: none;
    border-radius: 8px;
    background: #252A86;
    color: #ffffff;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    transition: all .2s ease;
}

.monitoring-search-button:hover {
    background: #1d226f;
    transform: translateY(-1px);
}

.monitoring-search-result {
    margin-top: 10px;
    font-size: 12px;
    color: #777;
}

.monitoring-search-result strong {
    color: #252A86;
}


    /* =====================================================
       STATISTICS CARDS
       DESKTOP: 4 CARD = 1 BARIS
    ====================================================== */

    .monitoring-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }

    .monitoring-stat-card {
        background: #ffffff;
        border: 1px solid #e7e8ee;
        border-radius: 12px;
        padding: 18px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .03);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .monitoring-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, .06);
    }

    .monitoring-stat-label {
        font-size: 11px;
        color: #777;
        margin-bottom: 8px;
    }

    .monitoring-stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #252A86;
    }


    /* =====================================================
       DATA PANEL
    ====================================================== */

    .monitoring-data-panel {
        background: #ffffff;
        border: 1px solid #e7e8ee;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .03);
    }

    .monitoring-data-panel-header {
        padding: 18px 20px;
        border-bottom: 1px solid #e8e9ef;
    }

    .monitoring-data-panel-title {
        font-size: 16px;
        font-weight: 700;
        color: #252A86;
    }

    .monitoring-data-panel-subtitle {
        font-size: 12px;
        color: #888;
        margin-top: 4px;
    }


    /* =====================================================
       TABLE
    ====================================================== */

    .monitoring-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .monitoring-data-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .monitoring-data-table th {
        background: #f8f8fb;
        color: #555;
        font-size: 11px;
        font-weight: 700;
        text-align: left;
        padding: 13px 16px;
        border-bottom: 1px solid #e7e8ee;
        white-space: nowrap;
    }

    .monitoring-data-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #eeeeF3;
        font-size: 12px;
        color: #555;
        white-space: nowrap;
    }

    .monitoring-data-table tbody tr {
        transition: background .15s ease;
    }

    .monitoring-data-table tbody tr:hover {
        background: #fafaff;
    }

    .monitoring-data-table tbody tr:last-child td {
        border-bottom: none;
    }


    /* =====================================================
       STATUS
    ====================================================== */

    .monitoring-status {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
    }

    .monitoring-status-menunggu {
        background: #fff4d6;
        color: #9a6b00;
    }

    .monitoring-status-selesai {
        background: #e7f7ed;
        color: #21834b;
    }

    .monitoring-status-diproses {
        background: #e8efff;
        color: #3159a8;
    }


    /* =====================================================
       ACTION BUTTON
    ====================================================== */

    .monitoring-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 11px;
        border-radius: 7px;
        border: 1px solid #dcdfea;
        background: #ffffff;
        color: #252A86;
        text-decoration: none;
        font-size: 11px;
        font-weight: 600;
        transition: all .2s ease;
    }

    .monitoring-action-btn:hover {
        background: #252A86;
        color: #ffffff;
        border-color: #252A86;
    }


    /* =====================================================
       EMPTY SEARCH
    ====================================================== */

    .monitoring-empty-search {
        text-align: center;
        padding: 45px 20px !important;
        color: #777 !important;
    }

    .monitoring-empty-search-icon {
        width: 42px;
        height: 42px;
        margin: 0 auto 12px;
        color: #b5b8c5;
    }

    .monitoring-empty-search-title {
        font-size: 14px;
        font-weight: 700;
        color: #555;
        margin-bottom: 5px;
    }

    .monitoring-empty-search-text {
        font-size: 12px;
        color: #999;
    }


    /* =====================================================
       TABLET
    ====================================================== */

    @media (max-width: 1100px) {

        .monitoring-page-header {
            margin-bottom: 20px;
        }

        .monitoring-search-panel {
            padding: 20px;
        }
    }


    /* =====================================================
       MOBILE
       4 CARD = 2 x 2
    ====================================================== */

    @media (max-width: 700px) {

        .monitoring-page-header {
            margin-bottom: 18px;
        }

        .monitoring-page-title {
            font-size: 23px;
        }

        .monitoring-page-description {
            font-size: 12px;
        }


        /* SEARCH MOBILE */

        .monitoring-search-panel {
            padding: 15px;
            border-radius: 11px;
            margin-bottom: 18px;
        }

        .monitoring-search-form {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }

       .monitoring-search-input {
    height: 42px;
    font-size: 13px;
    padding-left: 40px;
}

.monitoring-search-icon {
    left: 13px;
    width: 17px;
    height: 17px;
}

.monitoring-search-button {
    width: 100%;
    height: 42px;
}

        /* =================================================
           MOBILE STATS
           2 CARD PER BARIS
        ================================================== */

        .monitoring-stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
            margin-bottom: 18px;
        }

        .monitoring-stat-card {
            padding: 14px;
            border-radius: 10px;
        }

        .monitoring-stat-label {
            font-size: 10px;
            margin-bottom: 6px;
        }

        .monitoring-stat-value {
            font-size: 20px;
        }


        /* =================================================
           DATA PANEL MOBILE
        ================================================== */

        .monitoring-data-panel-header {
            padding: 15px;
        }

        .monitoring-data-panel-title {
            font-size: 14px;
        }

        .monitoring-data-panel-subtitle {
            font-size: 11px;
        }
    }


    /* =====================================================
       SMALL MOBILE
    ====================================================== */

    @media (max-width: 430px) {

        .monitoring-page-title {
            font-size: 21px;
        }

        .monitoring-page-description {
            font-size: 11px;
        }

        .monitoring-stats-grid {
            gap: 8px;
        }

        .monitoring-stat-card {
            padding: 12px;
        }

        .monitoring-stat-value {
            font-size: 18px;
        }

        .monitoring-search-input {
            font-size: 13px;
        }
    }
</style>
@endpush


@section('content')

    @php
        /*
        |--------------------------------------------------------------------------
        | FITUR PENCARIAN MONITORING
        |--------------------------------------------------------------------------
        |
        | Pencarian dilakukan berdasarkan:
        | - No. KK
        | - NIK
        | - Nama Kepala Keluarga
        | - Wilayah
        |
        | NIK tetap bisa dicari walaupun kolom NIK belum ditampilkan
        | pada tabel Monitoring.
        |
        */

        $search = trim(request('search', ''));

        $filteredData = collect($data ?? [])
            ->filter(function ($item) use ($search) {

                // Jika tidak ada kata pencarian,
                // tampilkan seluruh data.
                if ($search === '') {
                    return true;
                }

                $noKk = data_get($item, 'no_kk', '');
                $nik = data_get($item, 'nik', '');
                $nama = data_get($item, 'nama', '');
                $namaKepalaKeluarga = data_get($item, 'nama_kepala_keluarga', '');
                $wilayah = data_get($item, 'wilayah', '');

                $kataPencarian = strtolower($search);

                return
                    str_contains(strtolower((string) $noKk), $kataPencarian) ||
                    str_contains(strtolower((string) $nik), $kataPencarian) ||
                    str_contains(strtolower((string) $nama), $kataPencarian) ||
                    str_contains(strtolower((string) $namaKepalaKeluarga), $kataPencarian) ||
                    str_contains(strtolower((string) $wilayah), $kataPencarian);
            })
            ->values();
    @endphp


    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="monitoring-page-header">

        <div class="monitoring-page-label">
            ADMIN
        </div>

        <h1 class="monitoring-page-title">
            Monitoring
        </h1>

        <p class="monitoring-page-description">
            Pantau progres pendataan dan status verifikasi data secara real-time.
        </p>

    </div>


    {{-- =====================================================
         SEARCH
    ====================================================== --}}

    <div class="monitoring-search-panel">

        <form
            action="{{ route('monitoring.index') }}"
            method="GET"
            class="monitoring-search-form"
        >

            {{-- INPUT PENCARIAN --}}
            <div class="monitoring-search-input-wrapper">

                {{-- Icon Search --}}
                <svg
                    class="monitoring-search-icon"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <circle cx="11" cy="11" r="7"></circle>
                    <line x1="16.5" y1="16.5" x2="21" y2="21"></line>
                </svg>

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    class="monitoring-search-input"
                    placeholder="Cari No. KK, NIK, atau nama kepala keluarga..."
                    autocomplete="off"
                >

            </div>


            {{-- TOMBOL CARI --}}
            <button
                type="submit"
                class="monitoring-search-button"
            >
                Cari Data
            </button>


           

        </form>


        {{-- HASIL PENCARIAN --}}
        @if ($search !== '')

            <div class="monitoring-search-result">

                Hasil pencarian untuk
                <strong>"{{ $search }}"</strong> :

                <strong>
                    {{ $filteredData->count() }} data
                </strong>

            </div>

        @endif

    </div>


    {{-- =====================================================
         STATISTICS
    ====================================================== --}}

    <div class="monitoring-stats-grid">

        {{-- Total Responden --}}
        <div class="monitoring-stat-card">

            <div class="monitoring-stat-label">
                Total Responden
            </div>

            <div class="monitoring-stat-value">
                1.245
            </div>

        </div>


        {{-- Sudah Didata --}}
        <div class="monitoring-stat-card">

            <div class="monitoring-stat-label">
                Sudah Didata
            </div>

            <div class="monitoring-stat-value">
                980
            </div>

        </div>


        {{-- Menunggu Verifikasi --}}
        <div class="monitoring-stat-card">

            <div class="monitoring-stat-label">
                Menunggu Verifikasi
            </div>

            <div class="monitoring-stat-value">
                118
            </div>

        </div>


        {{-- Disetujui --}}
        <div class="monitoring-stat-card">

            <div class="monitoring-stat-label">
                Disetujui
            </div>

            <div class="monitoring-stat-value">
                742
            </div>

        </div>

    </div>


    {{-- =====================================================
         DATA TABLE
    ====================================================== --}}

    <div class="monitoring-data-panel">

        <div class="monitoring-data-panel-header">

            <div class="monitoring-data-panel-title">
                Progress Data Pendataan
            </div>

            <div class="monitoring-data-panel-subtitle">

                @if ($search !== '')

                    Menampilkan hasil pencarian data responden

                @else

                    Daftar data responden dan status pendataan

                @endif

            </div>

        </div>


        <div class="monitoring-table-wrapper">

            <table class="monitoring-data-table">

                <thead>

                    <tr>

                        <th>
                            No.
                        </th>

                        <th>
                            No. KK
                        </th>

                        <th>
                            Nama Kepala Keluarga
                        </th>

                        <th>
                            Wilayah
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Petugas
                        </th>

                        <th>
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($filteredData as $item)

                        <tr>

                            {{-- NO --}}
                            <td>
                                {{ $loop->iteration }}
                            </td>


                            {{-- NO KK --}}
                            <td>
                                {{ data_get($item, 'no_kk', '-') }}
                            </td>


                            {{-- NAMA --}}
                            <td>
                                {{ data_get($item, 'nama', data_get($item, 'nama_kepala_keluarga', '-')) }}
                            </td>


                            {{-- WILAYAH --}}
                            <td>
                                {{ data_get($item, 'wilayah', '-') }}
                            </td>


                            {{-- STATUS --}}
                            <td>

                                @php
                                    $status = data_get($item, 'status', '-');
                                @endphp

                                @if ($status === 'Menunggu')

                                    <span class="monitoring-status monitoring-status-menunggu">
                                        Menunggu
                                    </span>

                                @elseif ($status === 'Selesai')

                                    <span class="monitoring-status monitoring-status-selesai">
                                        Selesai
                                    </span>

                                @elseif ($status === 'Diproses')

                                    <span class="monitoring-status monitoring-status-diproses">
                                        Diproses
                                    </span>

                                @else

                                    <span class="monitoring-status monitoring-status-menunggu">
                                        {{ $status }}
                                    </span>

                                @endif

                            </td>


                            {{-- PETUGAS --}}
                            <td>
                                {{ data_get($item, 'petugas', '-') }}
                            </td>


                            {{-- AKSI --}}
                            <td>

                                <a
                                    href="{{ route('monitoring.detail', data_get($item, 'id', 0)) }}"
                                    class="monitoring-action-btn"
                                >
                                    Detail
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="monitoring-empty-search"
                            >

                                @if ($search !== '')

                                    {{-- ICON --}}
                                    <svg
                                        class="monitoring-empty-search-icon"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >
                                        <circle cx="11" cy="11" r="7"></circle>
                                        <line x1="16.5" y1="16.5" x2="21" y2="21"></line>
                                    </svg>

                                    <div class="monitoring-empty-search-title">
                                        Data Tidak Ditemukan
                                    </div>

                                    <div class="monitoring-empty-search-text">
                                        Tidak ada data yang sesuai dengan pencarian
                                        "{{ $search }}".
                                    </div>

                                @else

                                    <div class="monitoring-empty-search-title">
                                        Belum Ada Data Monitoring
                                    </div>

                                    <div class="monitoring-empty-search-text">
                                        Belum terdapat data responden yang dapat ditampilkan.
                                    </div>

                                @endif

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection