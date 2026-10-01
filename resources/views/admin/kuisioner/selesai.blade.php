@extends('admin.layouts.app')

@section('content')

<style>

/* =========================================================
   PAGE
   ========================================================= */

.page-wrapper {
    width: 100%;
    max-width: 1100px;
    margin: 0 auto;
}


/* =========================================================
   HEADER
   ========================================================= */

.page-header {
    background: #FFFFFF;
    border: 1px solid #E2E4EF;
    border-radius: 17px;

    padding: 25px 28px;
    margin-bottom: 20px;

    box-shadow: 0 5px 20px rgba(41, 45, 143, 0.05);
}

.page-title {
    margin: 0;

    color: #292D8F;
    font-size: 22px;
    font-weight: 800;

    line-height: 1.3;
}

.page-subtitle {
    margin: 7px 0 0;

    color: #777D91;
    font-size: 14px;
    font-weight: 400;

    line-height: 1.5;
}


/* =========================================================
   TABLE CARD
   ========================================================= */

.table-card {
    width: 100%;

    background: #FFFFFF;

    border: 1px solid #E2E4EF;
    border-radius: 17px;

    overflow: hidden;

    box-shadow: 0 5px 20px rgba(41, 45, 143, 0.05);
}

.table-wrapper {
    width: 100%;
    overflow-x: auto;
}


/* =========================================================
   TABLE
   ========================================================= */

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #F8F9FF;

    color: #6B7082;

    font-size: 13px;
    font-weight: 800;

    padding: 15px 18px;

    text-align: left;

    white-space: nowrap;
}

td {
    padding: 16px 18px;

    border-top: 1px solid #E9EAF2;

    color: #374151;

    font-size: 14px;
    font-weight: 400;

    vertical-align: middle;
}

tbody tr {
    transition: 0.2s ease;
}

tbody tr:hover td {
    background: #FAFBFF;
}


/* =========================================================
   NO KK
   ========================================================= */

td strong {
    color: #292D8F;
    font-weight: 700;
}


/* =========================================================
   STATUS
   ========================================================= */

.status {
    display: inline-flex;
    align-items: center;
    gap: 6px;

    padding: 7px 11px;

    border-radius: 999px;

    background: #ECFDF5;
    color: #047857;

    font-size: 12px;
    font-weight: 700;

    white-space: nowrap;
}


/* =========================================================
   DETAIL BUTTON
   ========================================================= */

.detail-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-height: 36px;

    padding: 0 14px;

    background: #292D8F;

    border: 1px solid #292D8F;
    border-radius: 8px;

    color: #FFFFFF !important;

    text-decoration: none !important;

    font-size: 12px;
    font-weight: 700;

    transition: 0.2s ease;
}

.detail-button:hover {
    background: #20236F;
    border-color: #20236F;

    color: #FFFFFF !important;

    transform: translateY(-1px);
}


/* =========================================================
   EMPTY STATE
   ========================================================= */

.empty-state {
    padding: 55px 25px;

    text-align: center;
}

.empty-icon {
    width: 58px;
    height: 58px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin: 0 auto 15px;

    border-radius: 50%;

    background: #F0F1FF;
    color: #292D8F;

    font-size: 28px;
    font-weight: 800;
}

.empty-title {
    color: #374151;

    font-size: 16px;
    font-weight: 800;
}

.empty-description {
    margin-top: 6px;

    color: #777D91;

    font-size: 14px;
    line-height: 1.5;
}


/* =========================================================
   PAGINATION
   ========================================================= */

.pagination-wrapper {
    padding: 16px 20px;

    border-top: 1px solid #E9EAF2;

    display: flex;
    justify-content: flex-end;
}


/* =========================================================
   BACK BUTTON
   ========================================================= */

.back-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;

    margin-top: 18px;

    min-height: 43px;

    padding: 0 18px;

    background: #FFFFFF;

    border: 1px solid #D9DCE8;
    border-radius: 9px;

    color: #656A7C !important;

    text-decoration: none !important;

    font-family: inherit;
    font-size: 13px;
    font-weight: 700;

    transition: 0.2s ease;
}

.back-button:hover {
    background: #F5F6FB;
    border-color: #C8CBF7;
    color: #292D8F !important;
}


/* =========================================================
   RESPONSIVE
   ========================================================= */

@media (max-width: 750px) {

    .page-header {
        padding: 22px 20px;
    }

    .page-title {
        font-size: 20px;
    }

    .table-card {
        border-radius: 14px;
    }

    th {
        font-size: 12px;
        padding: 13px 15px;
    }

    td {
        font-size: 13px;
        padding: 14px 15px;
    }

}

@media (max-width: 560px) {

    .page-header {
        border-radius: 13px;
        padding: 20px;
    }

    .page-title {
        font-size: 18px;
    }

    .page-subtitle {
        font-size: 13px;
    }

    .empty-state {
        padding: 45px 20px;
    }

    .empty-title {
        font-size: 15px;
    }

    .empty-description {
        font-size: 13px;
    }

    .back-button {
        width: 100%;
    }

}

</style>


<div class="page-wrapper">

    {{-- =====================================================
         HEADER
         ===================================================== --}}

    <div class="page-header">

        <h1 class="page-title">
            Data Kuisioner Selesai
        </h1>

        <p class="page-subtitle">
            Data keluarga yang seluruh proses pendataannya telah selesai.
        </p>

    </div>


    {{-- =====================================================
         TABLE
         ===================================================== --}}

    <div class="table-card">

        @if($selesai->count() > 0)

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>
                            <th>No</th>
                            <th>No. KK</th>
                            <th>NIK</th>
                            <th>Kecamatan</th>
                            <th>Kelurahan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($selesai as $data)

                            <tr>

                                <td>
                                    {{ $selesai->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $data->no_kk ?: '-' }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $data->nik ?: '-' }}
                                </td>

                                <td>
                                    {{ $data->kecamatan ?: '-' }}
                                </td>

                                <td>
                                    {{ $data->kelurahan ?: '-' }}
                                </td>

                                <td>
                                    <span class="status">
                                        ✓ Selesai
                                    </span>
                                </td>

                                <td>

                                    <a
                                        href="{{ route('kuisioner.selesai.detail', $data->id) }}"
                                        class="detail-button"
                                    >
                                        Detail
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}

            @if($selesai->hasPages())

                <div class="pagination-wrapper">
                    {{ $selesai->links() }}
                </div>

            @endif


        @else

            <div class="empty-state">

                <div class="empty-icon">
                    ✓
                </div>

                <div class="empty-title">
                    Belum ada data yang selesai
                </div>

                <div class="empty-description">
                    Data yang telah menyelesaikan seluruh Part 1 sampai Part 5
                    akan muncul di sini.
                </div>

            </div>

        @endif

    </div>


    {{-- =====================================================
         BACK
         ===================================================== --}}

    <a
        href="{{ route('kuisioner.index') }}"
        class="back-button"
    >
        ← Kembali ke Kuisioner
    </a>

</div>

@endsection