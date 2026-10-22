@extends('admin.kuisioner.layout')

@section('kuisioner-content')

<style>
    .part5-list-wrapper {
        width: 100%;
    }

    .part5-info {
        background: #f5f7ff;
        border: 1px solid #dfe3f5;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 18px;
    }

    .part5-info-title {
        font-size: 15px;
        font-weight: 700;
        color: #252A86;
        margin-bottom: 5px;
    }

    .part5-info-text {
        font-size: 13px;
        color: #666;
        margin: 0;
        line-height: 1.5;
    }

    .part5-progress {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 18px;
    }

    .part5-progress-label {
        font-size: 13px;
        color: #555;
    }

    .part5-progress-number {
        font-size: 15px;
        font-weight: 700;
        color: #252A86;
    }

    .part5-table-wrapper {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #e5e5e5;
        border-radius: 10px;
        background: #fff;
    }

    .part5-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 650px;
    }

    .part5-table th {
        background: #252A86;
        color: #fff;
        font-size: 11px;
        font-weight: 600;
        padding: 11px 12px;
        text-align: left;
        white-space: nowrap;
    }

    .part5-table td {
        padding: 12px;
        border-bottom: 1px solid #eeeeee;
        font-size: 13px;
        color: #444;
        vertical-align: middle;
    }

    .part5-table tbody tr:last-child td {
        border-bottom: none;
    }

    .part5-table tbody tr:hover {
        background: #f8f9ff;
    }

    .part5-number {
        width: 50px;
        text-align: center !important;
    }

    .part5-nik {
        white-space: nowrap;
        font-size: 12px !important;
    }

    .part5-name {
        font-weight: 600;
        color: #333;
    }

    .part5-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    .part5-status-selesai {
        background: #e8f7ef;
        color: #087443;
    }

    .part5-status-belum {
        background: #fff4df;
        color: #a35b00;
    }

    .part5-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 90px;
        padding: 7px 12px;
        border: none;
        border-radius: 6px;
        background: #252A86;
        color: #fff;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        transition: .2s;
    }

    .part5-action:hover {
        background: #1d216d;
        color: #fff;
    }

    .part5-empty {
        text-align: center;
        padding: 30px 20px !important;
        color: #777 !important;
        font-size: 13px !important;
    }

    /* Footer navigasi */
    .part5-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-top: 14px;
        padding: 10px 14px;
        background: #fff;
        border: 1px solid #e2e4ef;
        border-radius: 10px;
        box-sizing: border-box;
    }

    .part5-footer > a,
    .part5-footer > div {
        min-width: 0;
    }

    .part5-footer > div {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    /* Ukuran semua tombol */
    .part5-back-btn,
    .part5-draft-btn,
    .part5-next-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 36px;
        padding: 0 16px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.2;
        text-decoration: none !important;
        white-space: nowrap;
        box-sizing: border-box;
        cursor: pointer;
        transition: background-color .2s ease, border-color .2s ease;
    }

    /* Tombol kembali */
    .part5-back-btn {
        background: #fff;
        color: #5b6478 !important;
        border-color: #d7dced;
    }

    .part5-back-btn:hover {
        background: #f7f8fc;
        border-color: #c5cbe0;
        color: #252A86 !important;
    }

    /* Tombol simpan draft */
    .part5-draft-btn {
        background: #e8eaff;
        color: #252A86 !important;
        border-color: #e8eaff;
    }

    .part5-draft-btn:hover {
        background: #d9ddff;
        border-color: #d9ddff;
        color: #1d216d !important;
    }

    /* Tombol lanjut */
    .part5-next-btn {
        background: #252A86;
        color: #fff !important;
        border-color: #252A86;
    }

    .part5-next-btn:hover:not(:disabled) {
        background: #1d216d;
        border-color: #1d216d;
        color: #fff !important;
    }

    .part5-next-btn:disabled {
        opacity: .5;
        cursor: not-allowed;
    }

    .part5-warning {
        margin-top: 12px;
        padding: 12px 16px;
        border: 1px solid #f1d9a8;
        border-radius: 8px;
        background: #fff8e9;
        color: #946200;
        font-size: 13px;
        line-height: 1.5;
    }

    @media (max-width: 768px) {
        .part5-footer {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
            padding: 12px;
        }

        .part5-footer > a {
            width: 100%;
        }

        .part5-footer > div {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 8px;
        }

        .part5-footer form {
            width: 100%;
        }

        .part5-back-btn,
        .part5-draft-btn,
        .part5-next-btn {
            width: 100%;
            min-height: 36px;
        }
    }
</style>

<div class="part5-list-wrapper">

    {{-- INFORMASI --}}
    <div class="part5-info">
        <div class="part5-info-title">
            Part 5 — Data Anggota Keluarga
        </div>

        <p class="part5-info-text">
            Silakan pilih setiap anggota keluarga untuk mengisi data individu
            pada pertanyaan nomor 52–68.
        </p>
    </div>

    {{-- PROGRESS --}}
    <div class="part5-progress">
        <div class="part5-progress-label">
            Progress pengisian data anggota
        </div>

        <div class="part5-progress-number">
            {{ $jumlahSelesai }} / {{ $jumlahAnggota }} anggota selesai
        </div>
    </div>

    {{-- DAFTAR ANGGOTA --}}
    <div class="part5-table-wrapper">
        <table class="part5-table">
            <thead>
                <tr>
                    <th class="part5-number">No</th>
                    <th>NIK</th>
                    <th>Nama Lengkap</th>
                    <th>Status Keluarga</th>
                    <th>Status Pengisian</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($anggota as $index => $item)

                @php
                    $sudahDiisi = (bool) ($statusPart5[$item->kode] ?? false);
                @endphp


                    <tr>
                        <td class="part5-number">
                            {{ $index + 1 }}
                        </td>

                        <td class="part5-nik">
                            {{ $item->nik }}
                        </td>

                        <td class="part5-name">
                            {{ $item->nama_lengkap }}
                        </td>

                        <td>
                            {{ $item->status_keluarga }}
                        </td>

                        <td>
                            @if ($sudahDiisi)
                                <span class="part5-status part5-status-selesai">
                                    ✓ Sudah Diisi
                                </span>
                            @else
                                <span class="part5-status part5-status-belum">
                                    Belum Diisi
                                </span>
                            @endif
                        </td>

                        <td>
                            <a
                                href="{{ route('kuisioner.part5.anggota', $item->kode) }}"
                                class="part5-action"
                            >
                                {{ $sudahDiisi ? 'Edit Data' : 'Isi Data' }}
                            </a>
                        </td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="part5-empty">
                            Tidak Memiliki Anggota Keluarga yang Terdaftar.
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>
    </div>


    {{-- FOOTER --}}
    <div class="part5-footer">

        <a
            href="{{ route('kuisioner.part4') }}"
            class="part5-back-btn"
        >
            ← Kembali ke Part 4
        </a>

        <div class="part5-footer-actions">

            {{-- SIMPAN DRAFT --}}
            <form
                action="{{ route('kuisioner.part5.draft') }}"
                method="POST"
                style="margin: 0;"
            >
                @csrf

                <button
                    type="submit"
                    class="part5-draft-btn kuisioner-draft-button"
                >
                    Draft
                </button>
            </form>

            {{-- LANJUT KE FOTO RUMAH --}}
            <a
                href="{{ route('kuisioner.part5.foto') }}"
                class="part5-next-btn"
            >
                Lanjut →
            </a>

        </div>
    </div>

    {{-- INFORMASI: DATA ANGGOTA MASIH BELUM LENGKAP --}}
    @if ($jumlahAnggota > 0 && $jumlahSelesai < $jumlahAnggota)
        <div class="part5-warning">
            Masih ada anggota keluarga yang belum diisi.
            Kamu tetap bisa melanjutkan ke tahap foto rumah.
            Pastikan seluruh data anggota dilengkapi sebelum menyelesaikan kuesioner.
        </div>
    @endif

@endsection