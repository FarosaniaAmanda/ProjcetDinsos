@extends('admin.layouts.app')

@section('title', 'Rekapitulasi Petugas')

@section('content')
<style>
    .recap-page {
        min-height: calc(100vh - 75px);
        padding: 28px 32px 40px;
        background: #f5f7fb;
    }

    .recap-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .recap-header {
        margin-bottom: 22px;
    }

    .recap-header h1 {
        color: #252a86;
        font-size: 25px;
        line-height: 1.3;
    }

    .recap-header p {
        margin-top: 7px;
        color: #64748b;
        font-size: 14px;
        line-height: 1.6;
    }

    .recap-card {
        overflow: hidden;
        border: 1px solid #e2e6f2;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 4px 18px rgba(37, 42, 134, .06);
    }

    .recap-filter {
        display: grid;
        grid-template-columns: minmax(240px, 1.6fr) repeat(3, minmax(160px, 1fr)) auto;
        align-items: end;
        gap: 14px;
        padding: 20px 24px;
        border-bottom: 1px solid #e8ebf3;
    }

    .recap-filter-field {
        display: flex;
        min-width: 0;
        flex-direction: column;
        gap: 7px;
    }

    .recap-filter-label {
        color: #38415b;
        font-size: 12px;
        font-weight: 700;
    }

    .recap-filter-input,
    .recap-filter-select {
        width: 100%;
        min-height: 42px;
        padding: 9px 11px;
        border: 1px solid #d9deea;
        border-radius: 8px;
        outline: none;
        background: #fff;
        color: #283044;
        font: inherit;
        font-size: 13px;
    }

    .recap-filter-input:focus,
    .recap-filter-select:focus {
        border-color: #252a86;
        box-shadow: 0 0 0 3px rgba(37, 42, 134, .1);
    }

    .recap-filter-actions {
        display: flex;
        gap: 8px;
    }

    .recap-filter-actions .recap-reset {
        white-space: nowrap;
    }

    .recap-filter {
        margin: 0;
    }

    .recap-button,
    .recap-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 0 15px;
        border: 0;
        border-radius: 8px;
        font: inherit;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
    }

    .recap-button {
        background: #252a86;
        color: #fff;
    }

    .recap-button:hover {
        background: #1d226f;
    }

    .recap-reset {
        background: #f1f3f8;
        color: #525d72;
    }

    .recap-table-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding: 20px 24px 14px;
    }

    .recap-period-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .recap-table-heading h2 {
        color: #283044;
        font-size: 16px;
    }

    .recap-period-label {
        color: #64748b;
        font-size: 12px;
        text-align: right;
    }

    .recap-notice {
        margin: 0 24px 15px;
        padding: 11px 13px;
        border: 1px solid #e5e9f4;
        border-radius: 8px;
        background: #f8f9fd;
        color: #59647a;
        font-size: 12px;
        line-height: 1.5;
    }

    .recap-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .recap-table {
        width: 100%;
        min-width: 680px;
        border-collapse: collapse;
        text-align: left;
    }

    .recap-table th,
    .recap-table td {
        padding: 13px 18px;
        border-top: 1px solid #edf0f6;
    }

    .recap-table th {
        background: #f8f9fd;
        color: #59647a;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .03em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .recap-table td {
        color: #364057;
        font-size: 13px;
    }

    .recap-table tbody tr:hover {
        background: #fafbff;
    }

    .recap-table .recap-number {
        width: 54px;
        color: #7b8498;
        text-align: center;
    }

    .recap-table .recap-count {
        width: 120px;
        font-variant-numeric: tabular-nums;
        text-align: center;
    }

    .recap-total {
        color: #252a86 !important;
        font-weight: 700;
    }

    .recap-empty {
        padding: 25px !important;
        color: #7b8498 !important;
        text-align: center;
    }

    @media (max-width: 900px) {
        .recap-page {
            padding: 22px 20px 32px;
        }

        .recap-filter {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .recap-filter-actions {
            grid-column: 1 / -1;
        }

        .recap-reset {
            flex: 1;
        }
    }

    @media (max-width: 560px) {
        .recap-page {
            padding: 18px 12px 26px;
        }

        .recap-header h1 {
            font-size: 21px;
        }

        .recap-filter {
            grid-template-columns: minmax(0, 1fr);
            padding: 16px;
        }

        .recap-filter-actions {
            grid-column: auto;
        }

        .recap-table-heading {
            padding-right: 15px;
            padding-left: 15px;
        }

        .recap-table-heading {
            flex-direction: column;
        }

        .recap-period-actions {
            align-items: flex-start;
            flex-direction: column;
            gap: 8px;
        }

        .recap-period-label {
            text-align: left;
        }

        .recap-notice {
            margin-right: 15px;
            margin-left: 15px;
        }

        .recap-table th,
        .recap-table td {
            padding: 12px;
        }
    }

    @media (max-width: 900px) and (orientation: landscape) {
        .recap-filter {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .recap-filter-actions {
            grid-column: 1 / -1;
        }
    }
</style>

<main class="recap-page">
    <div class="recap-container">
        <header class="recap-header">
            <h1>Rekapitulasi Petugas</h1>
            <p>Ringkasan pendataan responden berdasarkan periode pendataan yang dipilih.</p>
        </header>

        <section class="recap-card" aria-labelledby="recapTableTitle">
            <form
                method="GET"
                action="{{ route('rekapitulasi.index') }}"
                class="recap-filter"
                id="recapFilterForm"
            >
                <div class="recap-filter-field">
                    <label class="recap-filter-label" for="recapSearch">Pencarian Data</label>
                    <input
                        class="recap-filter-input"
                        type="search"
                        id="recapSearch"
                        name="search"
                        value="{{ $filters['search'] }}"
                        placeholder="Cari nama petugas..."
                        autocomplete="off"
                    >
                </div>

                <div class="recap-filter-field">
                    <label class="recap-filter-label" for="recapKecamatan">Kecamatan</label>
                    <select class="recap-filter-select" id="recapKecamatan" name="kecamatan">
                        <option value="">Semua Kecamatan</option>
                        @foreach ($kecamatanOptions as $kecamatan)
                            <option value="{{ $kecamatan }}" @selected($filters['kecamatan'] === $kecamatan)>
                                {{ $kecamatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="recap-filter-field">
                    <label class="recap-filter-label" for="recapKelurahan">Kelurahan</label>
                    <select class="recap-filter-select" id="recapKelurahan" name="kelurahan">
                        <option value="">Semua Kelurahan</option>
                        @foreach ($kelurahanOptions as $kelurahan)
                            <option value="{{ $kelurahan }}" @selected($filters['kelurahan'] === $kelurahan)>
                                {{ $kelurahan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="recap-filter-field">
                    <label class="recap-filter-label" for="recapStatus">Status</label>
                    <select class="recap-filter-select" id="recapStatus" name="status">
                        <option value="">Semua Status</option>
                        @foreach ($statusBuckets as $statusBucket)
                            <option
                                value="{{ $statusBucket['key'] }}"
                                @selected($filters['status'] === $statusBucket['key'])
                            >
                                {{ $statusBucket['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="recap-filter-actions">
                    <a class="recap-reset" href="{{ route('rekapitulasi.index') }}">Reset Filter</a>
                </div>
            </form>

            <div class="recap-table-heading">
                <h2 id="recapTableTitle">Produktivitas Pendataan</h2>
                <div class="recap-period-actions">
                    <span class="recap-period-label">
                        {{ $periode->nama }}
                        ({{ $periode->tgl_awal?->format('d M Y') }} – {{ $periode->tgl_akhir?->format('d M Y') }})
                    </span>
                    <a
                        class="recap-reset"
                        href="{{ route('periode.pilih', ['tujuan' => 'rekapitulasi.index']) }}"
                    >
                        Ganti Periode
                    </a>
                </div>
            </div>

            <p class="recap-notice">
                Total menghitung setiap responden satu kali berdasarkan pendataan pertamanya.
                Jumlah total merupakan penjumlahan seluruh status yang ditampilkan.
            </p>

            @if (! $hasResults)
                <p class="recap-notice" role="status">
                    Tidak ada hasil rekap yang sesuai dengan pencarian dan filter.
                </p>
            @endif

            <div class="recap-table-wrap">
                <table class="recap-table">
                    <thead>
                        <tr>
                            <th class="recap-number">No.</th>
                            <th>Nama Petugas</th>
                            <th class="recap-count">Total Responden</th>
                            @foreach ($statusBuckets as $statusBucket)
                                <th class="recap-count">{{ $statusBucket['label'] }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $index => $row)
                            <tr>
                                <td class="recap-number">{{ $index + 1 }}</td>
                                <td>{{ $row['nama_petugas'] }}</td>
                                <td class="recap-count recap-total">
                                    {{ number_format($row['total_responden'], 0, ',', '.') }}
                                </td>
                                @foreach ($statusBuckets as $statusBucket)
                                    <td class="recap-count">
                                        {{ number_format($row['status_counts'][$statusBucket['key']], 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td class="recap-empty" colspan="{{ 3 + $statusBuckets->count() }}">
                                    Tidak ada petugas yang sesuai dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>
<script>
    (() => {
        const form = document.getElementById('recapFilterForm');
        const search = document.getElementById('recapSearch');
        const kecamatan = document.getElementById('recapKecamatan');
        const kelurahan = document.getElementById('recapKelurahan');
        const searchFocusKey = 'rekapitulasi-search-focus';
        let searchTimer;

        if (window.sessionStorage.getItem(searchFocusKey) === 'true') {
            window.sessionStorage.removeItem(searchFocusKey);
            search.focus();
            search.setSelectionRange(search.value.length, search.value.length);
        }

        search.addEventListener('input', () => {
            window.clearTimeout(searchTimer);
            searchTimer = window.setTimeout(() => {
                window.sessionStorage.setItem(searchFocusKey, 'true');
                form.requestSubmit();
            }, 350);
        });

        [kecamatan, kelurahan, document.getElementById('recapStatus')].forEach((filter) => {
            filter.addEventListener('change', () => {
                if (filter === kecamatan) {
                    kelurahan.value = '';
                }

                form.requestSubmit();
            });
        });
    })();
</script>
@endsection
