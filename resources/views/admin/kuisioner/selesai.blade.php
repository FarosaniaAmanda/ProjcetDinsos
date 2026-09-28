@extends('admin.layouts.app')

@section('content')

<style>
    .page-wrapper {
        max-width: 1100px;
        margin: 0 auto;
    }

    .page-header {
        background: white;
        border: 1px solid #e7e8ee;
        border-radius: 18px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 4px 12px rgba(0,0,0,.03);
    }

    .page-title {
        margin: 0;
        font-size: 26px;
        font-weight: 800;
        color: #1f2937;
    }

    .page-subtitle {
        margin-top: 7px;
        color: #6b7280;
        font-size: 14px;
    }

    .table-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,.03);
    }

    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        background: #f8f9ff;
        color: #6b7280;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        padding: 15px 18px;
        text-align: left;
        white-space: nowrap;
    }

    td {
        padding: 16px 18px;
        border-top: 1px solid #eef0f4;
        color: #374151;
        font-size: 14px;
    }

    tr:hover td {
        background: #fafbff;
    }

    .status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 10px;
        border-radius: 999px;
        background: #ecfdf5;
        color: #047857;
        font-size: 12px;
        font-weight: 700;
    }

    .empty-state {
        padding: 55px 25px;
        text-align: center;
    }

    .empty-icon {
        font-size: 42px;
        margin-bottom: 12px;
    }

    .empty-title {
        font-weight: 800;
        color: #374151;
    }

    .empty-description {
        margin-top: 5px;
        font-size: 14px;
        color: #6b7280;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 18px;
        padding: 10px 16px;
        background: white;
        border: 1px solid #d1d5db;
        color: #374151 !important;
        text-decoration: none !important;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
    }
</style>


<div class="page-wrapper">

    <div class="page-header">

        <h1 class="page-title">
            Data Kuisioner Selesai
        </h1>

        <p class="page-subtitle">
            Data keluarga yang seluruh proses pendataannya telah selesai.
        </p>

    </div>


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
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($selesai as $data)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
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

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="empty-state">

                <div class="empty-icon">
                    ✓
                </div>

                <div class="empty-title">
                    Belum ada data yang selesai
                </div>

                <div class="empty-description">
                    Data yang telah menyelesaikan seluruh Part 1 sampai Part 5 akan muncul di sini.
                </div>

            </div>

        @endif

    </div>


    <a
        href="{{ route('kuisioner.index') }}"
        class="back-button"
    >
        ← Kembali ke Kuisioner
    </a>

</div>

@endsection