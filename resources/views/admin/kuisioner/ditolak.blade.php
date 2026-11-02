@extends('admin.layouts.app')

@section('title', 'Kuisioner Ditolak')

@section('content')
<div class="status-questionnaire-page">
    <header class="status-page-header">
        <div>
            <h1>Kuisioner Ditolak</h1>
            <p>Pilih Edit untuk memperbaiki kuisioner yang ditolak.</p>
        </div>
        <div class="status-header-actions">
            <a class="status-back-link" href="{{ route('kuisioner.index') }}">Kembali ke Kuisioner</a>
        </div>
    </header>

    @if (session('success'))
        <div class="status-alert status-alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="status-alert status-alert-error">{{ session('error') }}</div>
    @endif

    <section class="status-summary">
        <span>Jumlah Kuisioner Ditolak</span>
        <strong>{{ $kuisioner->total() }}</strong>
    </section>

    <section class="status-list-card">
        <div class="status-list-header">
            <div>
                <h2>Daftar Responden Ditolak</h2>
                <p></p>
            </div>
            <span>{{ $kuisioner->total() }} Data</span>
        </div>

        <div class="status-table-wrapper">
            <table class="status-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>No. KK</th>
                        <th>NIK</th>
                        <th>Nama Kepala Keluarga</th>
                        <th>Alamat</th>
                        <th>Kecamatan</th>
                        <th>Kelurahan</th>
                        <th>Alasan Penolakan</th>
                        <th>Tanggal Pembaruan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kuisioner as $item)
                        <tr>
                            <td>{{ $kuisioner->firstItem() + $loop->index }}</td>
                            <td>{{ $item->no_kk ?: ($item->keluarga_data?->no_kk ?: '-') }}</td>
                            <td>{{ $item->nik ?: ($item->keluarga_data?->nik ?: '-') }}</td>
                            <td>{{ $item->nama_kepala_keluarga ?: ($item->keluarga_data?->nama_lengkap ?: '-') }}</td>
                            <td>{{ $item->alamat_lengkap ?: ($item->keluarga_data?->alamat_lengkap ?: '-') }}</td>
                            <td>{{ $item->kecamatan ?: '-' }}</td>
                            <td>{{ $item->kelurahan ?: '-' }}</td>
                            <td>{{ $item->alasan_penolakan ?: 'Alasan penolakan belum tersedia.' }}</td>
                            <td>{{ $item->updated_at?->format('d-m-Y') ?: '-' }}</td>
                            <td>
                                <form method="POST" action="{{ route('kuisioner.rejected.edit', ['id' => $item->id]) }}">
                                    @csrf
                                    <button class="status-edit-button" type="submit">Edit Kuisioner</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="status-empty" colspan="10">
                                <strong>Belum Ada Kuisioner Ditolak</strong>
                                <span>Kuisioner yang berstatus ditolak akan muncul di halaman ini.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- PAGINATION --}}
        <div class="status-pagination">
            <div class="pagination-info">
                Menampilkan
                <strong>{{ $kuisioner->firstItem() ?? 0 }}</strong>
                sampai
                <strong>{{ $kuisioner->lastItem() ?? 0 }}</strong>
                dari <strong>{{ $kuisioner->total() }}</strong> data
            </div>

            <div class="pagination-buttons">
                {{-- Tombol sebelumnya --}}
                @if ($kuisioner->onFirstPage())
                    <span class="page-btn disabled">‹</span>
                @else
                    <a href="{{ $kuisioner->previousPageUrl() }}"
                    class="page-btn">‹</a>
                @endif

                {{-- Nomor halaman --}}
                @for ($page = 1; $page <= $kuisioner->lastPage(); $page++)
                    <a href="{{ $kuisioner->url($page) }}"
                    class="page-btn {{ $kuisioner->currentPage() == $page ? 'active' : '' }}">
                        {{ $page }}
                    </a>
                @endfor

                {{-- Tombol berikutnya --}}
                @if ($kuisioner->hasMorePages())
                    <a href="{{ $kuisioner->nextPageUrl() }}"
                    class="page-btn">›</a>
                @else
                    <span class="page-btn disabled">›</span>
                @endif
            </div>
        </div>
    </section>
</div>

<style>
    
    
    .status-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        padding: 20px 22px;
        border-top: 1px solid #e8ebf3;
        background: #fff;
    }

    .pagination-info {
        color: #737b8c;
        font-size: 15px;
    }

    .pagination-info strong {
        font-weight: 600;
    }

    .pagination-buttons {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        height: 42px;
        padding: 0 12px;
        border: 1px solid #d5dced;
        border-radius: 12px;
        background: #fff;
        color: #292f91;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s, color 0.2s;
    }

    .page-btn:hover {
        background: #eef0ff;
        color: #292f91;
    }

    .page-btn.active {
        background: #292f91;
        border-color: #292f91;
        color: #fff;
    }

    .page-btn.disabled {
        background: #f4f5f9;
        color: #b7bdcc;
        cursor: not-allowed;
    }

    @media (max-width: 600px) {
        .status-pagination {
            align-items: flex-start;
            flex-direction: column;
        }

        .pagination-buttons {
            gap: 5px;
        }

        .page-btn {
            min-width: 36px;
            height: 36px;
            padding: 0 9px;
        }
    }
    .status-questionnaire-page { min-height: calc(100vh - 70px); padding: 24px 28px 40px; background: #f5f7fb; color: #27305e; }
    .status-page-header, .status-list-header { display: flex; align-items: center; justify-content: space-between; gap: 16px; }
    .status-header-actions { display: flex; flex-shrink: 0; flex-wrap: wrap; gap: 8px; }
    .status-page-header { margin-bottom: 20px; }
    .status-page-header h1 { margin: 0; color: #252a86; font-size: 25px; }
    .status-page-header p, .status-list-header p { margin: 7px 0 0; color: #667085; font-size: 14px; }
    .status-back-link, .status-report-link { padding: 10px 14px; border: 1px solid #d7daf2; border-radius: 8px; color: #252a86; font-size: 13px; font-weight: 700; text-decoration: none; background: #fff; }
    .status-report-link { border-color: #252a86; background: #252a86; color: #fff; }
    .status-summary, .status-list-card { margin-bottom: 18px; border: 1px solid #e2e6f2; border-radius: 14px; background: #fff; box-shadow: 0 4px 18px rgba(37, 42, 134, .05); }
    .status-summary { display: flex; align-items: center; justify-content: space-between; padding: 20px 24px; color: #b93838; font-weight: 700; }
    .status-summary strong { font-size: 28px; }
    .status-list-header { padding: 20px 22px; border-bottom: 1px solid #e8ebf3; }
    .status-list-header h2 { margin: 0; color: #252a86; font-size: 18px; }
    .status-list-header > span { flex-shrink: 0; padding: 8px 12px; border-radius: 8px; background: #f2f4f7; color: #344054; font-size: 13px; font-weight: 700; }
    .status-table-wrapper { overflow-x: auto; }
    .status-table { width: 100%; min-width: 1100px; border-collapse: collapse; }
    .status-table th, .status-table td { padding: 13px 15px; border-bottom: 1px solid #eef0f4; text-align: left; font-size: 13px; vertical-align: middle; }
    .status-table th { background: #f8f9fc; color: #475467; font-weight: 700; white-space: nowrap; }
    .status-table td { color: #344054; }
    .status-edit-button { padding: 8px 12px; border: 0; border-radius: 7px; background: #252a86; color: #fff; font-size: 12px; font-weight: 700; cursor: pointer; white-space: nowrap; }
    .status-alert { margin-bottom: 16px; padding: 12px 15px; border-radius: 8px; font-size: 13px; }
    .status-alert-success { border: 1px solid #bce5c9; background: #eaf8ef; color: #24723c; }
    .status-alert-error { border: 1px solid #f1bcbc; background: #fff0f0; color: #a83232; }
    .status-empty { height: 170px; text-align: center !important; }
    .status-empty strong, .status-empty span { display: block; }
    .status-empty span { margin-top: 7px; color: #667085; }
    @media (max-width: 700px) { .status-questionnaire-page { padding: 18px 12px 28px; } .status-page-header { align-items: flex-start; flex-direction: column; } .status-header-actions { width: 100%; } .status-page-header h1 { font-size: 22px; } }
</style>
@endsection
