@extends('admin.layouts.app')

@section('content')

<style>
    .page-wrapper {
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        background: white;
        border: 1px solid #e7e8ee;
        border-radius: 18px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .03);
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
        box-shadow: 0 4px 12px rgba(0, 0, 0, .03);
    }

    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1300px;
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
        padding: 15px 18px;
        border-top: 1px solid #eef0f4;
        color: #374151;
        font-size: 13px;
        white-space: nowrap;
    }

    tr:hover td {
        background: #fafbff;
    }

    .status {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 999px;
        background: #fff7ed;
        color: #c2410c;
        font-size: 12px;
        font-weight: 700;
    }

    .progress {
        font-weight: 700;
        color: #252A86;
    }

    .btn-continue {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 9px 14px;
        background: #252A86;
        color: white !important;
        text-decoration: none !important;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 700;
        transition: .2s ease;
    }

    .btn-continue:hover {
        background: #1d216d;
    }

    .empty-state {
        padding: 60px 25px;
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

    .back-button:hover {
        background: #f9fafb;
        border-color: #252A86;
        color: #252A86 !important;
    }
</style>


<div class="page-wrapper">

    {{-- HEADER --}}
    <div class="page-header">

        <h1 class="page-title">
            Draft Kuisioner
        </h1>

        <p class="page-subtitle">
            Data yang sudah diisi tetapi belum menyelesaikan seluruh proses kuisioner.
        </p>

    </div>


    {{-- TABLE --}}
    <div class="table-card">

        @if($drafts->isNotEmpty())

            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>No. KK</th>
                            <th>Jumlah Keluarga</th>
                            <th>Provinsi</th>
                            <th>Daerah</th>
                            <th>Kecamatan</th>
                            <th>Kelurahan</th>
                            <th>Kode Pos</th>
                            <th>RT/RW</th>
                            <th>Alamat Lengkap</th>
                            <th>Status</th>
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
                            @endphp

                            <tr>

                                {{-- NO --}}
                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                {{-- NIK --}}
                                <td>
                                    {{ $draft->nik ?: '-' }}
                                </td>

                                {{-- NO KK --}}
                                <td>
                                    {{ $draft->no_kk ?: '-' }}
                                </td>

                                {{-- JUMLAH KELUARGA --}}
                                <td>
                                    {{ $draft->jml_keluarga ?: '-' }}
                                </td>

                                {{-- PROVINSI --}}
                                <td>
                                    {{ $draft->provinsi ?: '-' }}
                                </td>

                                {{-- DAERAH --}}
                                <td>
                                    {{ $draft->daerah ?: '-' }}
                                </td>

                                {{-- KECAMATAN --}}
                                <td>
                                    {{ $draft->kecamatan ?: '-' }}
                                </td>

                                {{-- KELURAHAN --}}
                                <td>
                                    {{ $draft->kelurahan ?: '-' }}
                                </td>

                                {{-- KODE POS --}}
                                <td>
                                    {{ $draft->kode_pos ?: '-' }}
                                </td>

                                {{-- RT/RW --}}
                                <td>
                                    {{ $draft->rt_rw ?: '-' }}
                                </td>

                                {{-- ALAMAT --}}
                                <td>
                                    {{ $draft->alamat_lengkap ?: '-' }}
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    <span class="status">
                                        Draft
                                    </span>
                                </td>

                                {{-- PROGRESS --}}
                                <td>
                                    <span class="progress">
                                        Part {{ $currentPart }} / 5
                                        ({{ $progress }}%)
                                    </span>
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

        @else

            <div class="empty-state">

                <div class="empty-icon">
                    📋
                </div>

                <div class="empty-title">
                    Belum ada draft kuisioner
                </div>

                <div class="empty-description">
                    Data kuisioner yang sudah diisi tetapi belum selesai akan muncul di sini.
                </div>

            </div>

        @endif

    </div>


    {{-- KEMBALI --}}
    <a
        href="{{ route('kuisioner.index') }}"
        class="back-button"
    >
        ← Kembali ke Kuisioner
    </a>

</div>

@endsection