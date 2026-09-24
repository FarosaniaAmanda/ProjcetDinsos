@extends('admin.layouts.app')

@section('title', 'Manajemen Responden')

@push('styles')
<style>

/* =========================================================
   RESPONDEN PAGE
========================================================= */

.responden-page {
    width: 100%;
}

.responden-page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 20px;
    margin-bottom: 24px;
}

.responden-page-label {
    font-size: 11px;
    font-weight: 700;
    color: #252A86;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 7px;
}

.responden-page-title {
    font-size: 27px;
    font-weight: 700;
    color: #252A86;
    margin: 0 0 7px;
}

.responden-page-description {
    font-size: 13px;
    color: #777;
    line-height: 1.6;
    margin: 0;
}


/* =========================================================
   BUTTON
========================================================= */

.responden-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    height: 40px;
    padding: 0 16px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all .2s ease;
    box-sizing: border-box;
}

.responden-btn-primary {
    background: #252A86;
    border: 1px solid #252A86;
    color: #fff;
}

.responden-btn-primary:hover {
    background: #1d226f;
    border-color: #1d226f;
    color: #fff;
}

.responden-btn-secondary {
    background: #fff;
    border: 1px solid #dcdfea;
    color: #555;
}

.responden-btn-secondary:hover {
    background: #f7f7fa;
    color: #252A86;
}


/* =========================================================
   TABLE PANEL
========================================================= */

.responden-panel {
    background: #fff;
    border: 1px solid #e7e8ee;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, .03);
}

.responden-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 18px 20px;
    border-bottom: 1px solid #e8e9ef;
}

.responden-panel-title {
    font-size: 15px;
    font-weight: 700;
    color: #252A86;
}

.responden-panel-subtitle {
    margin-top: 4px;
    font-size: 11px;
    color: #888;
}


/* =========================================================
   SEARCH
========================================================= */

.responden-search {
    position: relative;
    width: 280px;
}

.responden-search input {
    width: 100%;
    height: 38px;
    padding: 0 13px 0 38px;
    border: 1px solid #dfe1e8;
    border-radius: 8px;
    outline: none;
    font-size: 12px;
    color: #333;
    box-sizing: border-box;
}

.responden-search input:focus {
    border-color: #252A86;
    box-shadow: 0 0 0 3px rgba(37, 42, 134, .08);
}

.responden-search-icon {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
    font-size: 14px;
    pointer-events: none;
}


/* =========================================================
   TABLE
========================================================= */

.responden-table-wrapper {
    width: 100%;
    overflow-x: auto;
}

.responden-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 750px;
}

.responden-table th {
    background: #fafafd;
    color: #777;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
    padding: 13px 16px;
    border-bottom: 1px solid #e8e9ef;
    text-align: left;
    white-space: nowrap;
}

.responden-table td {
    padding: 14px 16px;
    border-bottom: 1px solid #eeeeF3;
    font-size: 12px;
    color: #555;
    vertical-align: middle;
}

.responden-table tbody tr:hover {
    background: #fafaff;
}

.responden-table tbody tr:last-child td {
    border-bottom: none;
}

.responden-number {
    width: 45px;
    color: #999;
}

.responden-kk {
    font-weight: 700;
    color: #252A86;
}

.responden-code {
    font-size: 10px;
    color: #999;
    margin-top: 3px;
}

.responden-member-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 30px;
    height: 25px;
    padding: 0 8px;
    border-radius: 20px;
    background: #eef0ff;
    color: #252A86;
    font-size: 10px;
    font-weight: 700;
}


/* =========================================================
   ACTION
========================================================= */

.responden-action {
    display: flex;
    gap: 6px;
    align-items: center;
    flex-wrap: wrap;
}

.responden-edit-btn {
    height: 31px;
    padding: 0 10px;
    border: 1px solid #dfe1e8;
    background: #fff;
    color: #252A86;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s ease;
}

.responden-edit-btn:hover {
    background: #252A86;
    border-color: #252A86;
    color: #fff;
}

.responden-delete-btn {
    height: 31px;
    padding: 0 10px;
    border: 1px solid #edd5d5;
    background: #fff7f7;
    color: #b34a4a;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s ease;
}

.responden-delete-btn:hover {
    background: #b34a4a;
    border-color: #b34a4a;
    color: #fff;
}


/* =========================================================
   EMPTY
========================================================= */

.responden-empty {
    text-align: center;
    padding: 55px 20px;
    color: #999;
}

.responden-empty-icon {
    width: 48px;
    height: 48px;
    margin: 0 auto 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #f1f2f8;
    color: #252A86;
    font-size: 20px;
}

.responden-empty-title {
    color: #555;
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 5px;
}

.responden-empty-text {
    font-size: 11px;
}


/* =========================================================
   MODAL
========================================================= */

.responden-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 18, 48, .48);
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
    z-index: 9999;
    backdrop-filter: blur(2px);
}

.responden-modal-overlay.active {
    display: flex;
}

.responden-modal {
    width: 100%;
    max-width: 850px;
    max-height: 90vh;
    background: #fff;
    border-radius: 13px;
    overflow: hidden;
    box-shadow: 0 15px 45px rgba(0, 0, 0, .18);
    display: flex;
    flex-direction: column;
}

.responden-modal form {
    display: flex;
    flex-direction: column;
    min-height: 0;
    height: 100%;
}

.responden-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    padding: 18px 20px;
    border-bottom: 1px solid #e8e9ef;
    flex-shrink: 0;
}

.responden-modal-label {
    font-size: 10px;
    color: #252A86;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .8px;
    margin-bottom: 5px;
}

.responden-modal-title {
    font-size: 17px;
    color: #252A86;
    font-weight: 700;
    margin: 0;
}

.responden-modal-close {
    width: 34px;
    height: 34px;
    border: none;
    background: #f4f4f8;
    border-radius: 7px;
    color: #777;
    font-size: 19px;
    cursor: pointer;
    flex-shrink: 0;
}

.responden-modal-close:hover {
    background: #252A86;
    color: #fff;
}


/* =========================================================
   MODAL BODY
========================================================= */

.responden-modal-body {
    padding: 20px;
    overflow-y: auto;
    flex: 1;
    min-height: 0;
}


/* =========================================================
   MODAL FOOTER
========================================================= */

.responden-modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    padding: 14px 20px;
    background: #fafafd;
    border-top: 1px solid #e8e9ef;
    flex-shrink: 0;
}

.responden-modal-footer .responden-btn {
    width: auto;
    min-width: 110px;
}


/* =========================================================
   MODAL FORM
========================================================= */

.responden-section {
    background: #f8f8fb;
    border: 1px solid #e5e6ed;
    border-radius: 10px;
    padding: 17px;
    margin-bottom: 18px;
}

.responden-section:last-child {
    margin-bottom: 0;
}

.responden-section-heading {
    font-size: 13px;
    font-weight: 700;
    color: #252A86;
    margin-bottom: 4px;
}

.responden-section-description {
    font-size: 11px;
    color: #888;
    line-height: 1.5;
    margin-bottom: 17px;
}

.responden-form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 15px;
}

.responden-form-group {
    margin-bottom: 0;
}

.responden-form-group.full {
    grid-column: 1 / -1;
}

.responden-form-label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #555;
    margin-bottom: 6px;
}

.required {
    color: #d64545;
}

.responden-form-control {
    width: 100%;
    height: 40px;
    padding: 0 12px;
    border: 1px solid #dfe1e8;
    border-radius: 7px;
    background: #fff;
    color: #333;
    font-size: 12px;
    outline: none;
    box-sizing: border-box;
}

textarea.responden-form-control {
    height: auto;
    min-height: 90px;
    padding: 11px 12px;
    resize: vertical;
}

.responden-form-control:focus {
    border-color: #252A86;
    box-shadow: 0 0 0 3px rgba(37, 42, 134, .08);
}

.responden-form-control::placeholder {
    color: #aaa;
}

.responden-form-control[readonly] {
    background: #f3f4f8;
    color: #555;
    cursor: not-allowed;
}


/* =========================================================
   ANGGOTA
========================================================= */

.anggota-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
}

.anggota-header-text {
    font-size: 12px;
    font-weight: 700;
    color: #252A86;
}

.btn-tambah-anggota {
    height: 34px;
    padding: 0 11px;
    border: 1px solid #252A86;
    background: #fff;
    color: #252A86;
    border-radius: 7px;
    font-size: 10px;
    font-weight: 600;
    cursor: pointer;
}

.btn-tambah-anggota:hover {
    background: #252A86;
    color: #fff;
}

.anggota-card {
    background: #fff;
    border: 1px solid #e1e3eb;
    border-radius: 9px;
    padding: 15px;
    margin-bottom: 12px;
}

.anggota-card:last-child {
    margin-bottom: 0;
}

.anggota-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 14px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eeeeF3;
}

.anggota-card-title {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #252A86;
    font-size: 11px;
    font-weight: 700;
}

.anggota-number {
    width: 25px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #eef0ff;
    color: #252A86;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 700;
}

.btn-hapus-anggota {
    height: 29px;
    padding: 0 9px;
    border: 1px solid #edd5d5;
    background: #fff7f7;
    color: #b34a4a;
    border-radius: 6px;
    font-size: 9px;
    cursor: pointer;
}

.btn-hapus-anggota:hover {
    background: #b34a4a;
    color: #fff;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 750px) {

    .responden-page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .responden-page-header .responden-btn {
        width: 100%;
    }

    .responden-panel-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .responden-search {
        width: 100%;
    }

    .responden-form-grid {
        grid-template-columns: 1fr;
    }

    .responden-form-group.full {
        grid-column: auto;
    }

    .responden-modal {
        max-height: 94vh;
    }

}

@media (max-width: 500px) {

    .responden-modal-overlay {
        padding: 10px;
    }

    .responden-modal-header {
        padding: 15px;
    }

    .responden-modal-body {
        padding: 14px;
    }

    .responden-modal-footer {
        padding: 12px 15px;
    }

    .responden-section {
        padding: 13px;
    }

    .anggota-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .btn-tambah-anggota {
        width: 100%;
    }

    .responden-modal-footer {
        justify-content: flex-end;
    }

    .responden-modal-footer .responden-btn {
        flex: 1;
        min-width: 0;
    }

}

</style>
@endpush


@section('content')

<div class="responden-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="responden-page-header">

        <div>

            <div class="responden-page-label">
                ADMIN
            </div>

            <h1 class="responden-page-title">
                Data Responden
            </h1>

            <p class="responden-page-description">
                Kelola data Kartu Keluarga dan anggota keluarga yang akan didata.
            </p>

        </div>

        <button
            type="button"
            class="responden-btn responden-btn-primary"
            onclick="bukaModalTambah()"
        >
            <span style="font-size: 16px;">+</span>
            Tambah Responden
        </button>

    </div>


    {{-- =====================================================
         TABLE
    ====================================================== --}}

    <div class="responden-panel">

        <div class="responden-panel-header">

            <div>

                <div class="responden-panel-title">
                    Daftar Responden
                </div>

                <div class="responden-panel-subtitle">
                    Data KK yang telah terdaftar dalam sistem.
                </div>

            </div>

            <div class="responden-search">

                <span class="responden-search-icon">
                    ⌕
                </span>

                <input
                    type="text"
                    id="searchResponden"
                    placeholder="Cari No. KK atau nama..."
                    autocomplete="off"
                >

            </div>

        </div>


        <div class="responden-table-wrapper">

            <table
                class="responden-table"
                id="tabelResponden"
            >

                <thead>

                    <tr>

                        <th>No</th>

                        <th>No. KK</th>

                        <th>Nama Kepala Keluarga</th>

                        <th>Jumlah Anggota</th>

                        <th>Aksi</th>

                    </tr>

                </thead>

                <tbody id="respondenTableBody">

                    @forelse ($keluargas as $index => $keluarga)

                        <tr class="responden-row">

                            <td class="responden-number">
                                {{ $index + 1 }}
                            </td>

                            <td>

                                <div class="responden-kk">
                                    {{ $keluarga->no_kk ?? '-' }}
                                </div>

                                @if ($keluarga->kode)

                                    <div class="responden-code">
                                        {{ $keluarga->kode }}
                                    </div>

                                @endif

                            </td>

                            <td>
                                {{ $keluarga->nama_lengkap ?? '-' }}
                            </td>

                            <td>

                                <span class="responden-member-count">

                                    {{ $keluarga->anggota->count() }}

                                    orang

                                </span>

                            </td>

                            <td>

                                <div class="responden-action">

                                    <button
                                        type="button"
                                        class="responden-edit-btn"
                                        onclick="editResponden({{ $keluarga->id }})"
                                    >
                                        Edit
                                    </button>

                                    <button
                                        type="button"
                                        class="responden-delete-btn"
                                        onclick="hapusResponden({{ $keluarga->id }})"
                                    >
                                        Hapus
                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="responden-empty"
                            >

                                <div class="responden-empty-icon">
                                    👥
                                </div>

                                <div class="responden-empty-title">
                                    Belum ada data responden
                                </div>

                                <div class="responden-empty-text">
                                    Data responden yang ditambahkan akan muncul di sini.
                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- =========================================================
     MODAL TAMBAH
========================================================= --}}

<div
    class="responden-modal-overlay"
    id="modalTambahResponden"
>

    <div class="responden-modal">

        <form
            action="{{ route('responden.store') }}"
            method="POST"
        >

            @csrf

            <div class="responden-modal-header">

                <div>

                    <div class="responden-modal-label">
                        DATA RESPONDEN
                    </div>

                    <h2 class="responden-modal-title">
                        Tambah Responden
                    </h2>

                </div>

                <button
                    type="button"
                    class="responden-modal-close"
                    onclick="tutupModalTambah()"
                >
                    ×
                </button>

            </div>


            <div class="responden-modal-body">


                {{-- =================================================
                     WILAYAH
                ================================================== --}}

                <div class="responden-section">

                    <div class="responden-section-heading">
                        Wilayah / Alamat Keluarga
                    </div>

                    <div class="responden-section-description">
                        Masukkan wilayah dan alamat tempat tinggal keluarga yang akan didata.
                    </div>


                    <div class="responden-form-grid">


                        {{-- PROVINSI --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Provinsi
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="provinsi"
                                class="responden-form-control"
                                value="Jawa Timur"
                                readonly
                                required
                            >

                        </div>


                        {{-- KOTA --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Daerah/Kota
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="daerah"
                                class="responden-form-control"
                                value="Kota Pasuruan"
                                readonly
                                required
                            >

                        </div>


                        {{-- KECAMATAN --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Kecamatan
                                <span class="required">*</span>
                            </label>

                            <select
                                name="kecamatan_id"
                                id="kecamatan"
                                class="responden-form-control"
                                required
                            >

                                <option value="">
                                    Pilih Kecamatan
                                </option>

                                @foreach ($kecamatans as $kecamatan)

                                    <option
                                        value="{{ $kecamatan->kecamatan_id }}"
                                    >
                                        {{ $kecamatan->deskripsi }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- KELURAHAN --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Kelurahan/Desa
                                <span class="required">*</span>
                            </label>

                            <select
                                name="kelurahan_id"
                                id="kelurahan"
                                class="responden-form-control"
                                required
                            >

                                <option value="">
                                    Pilih Kecamatan terlebih dahulu
                                </option>

                            </select>

                        </div>


                        {{-- KODE POS --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Kode Pos
                            </label>

                            <input
                                type="text"
                                name="kode_pos"
                                class="responden-form-control"
                                placeholder="Masukkan kode pos"
                                maxlength="10"
                                inputmode="numeric"
                            >

                        </div>


                        {{-- RT RW --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                RT/RW
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="rt_rw"
                                class="responden-form-control"
                                placeholder="Contoh: 001/002"
                                maxlength="20"
                                required
                            >

                        </div>


                        {{-- ALAMAT --}}

                        <div class="responden-form-group full">

                            <label class="responden-form-label">
                                Alamat Lengkap
                                <span class="required">*</span>
                            </label>

                            <textarea
                                name="alamat_lengkap"
                                class="responden-form-control"
                                placeholder="Masukkan alamat lengkap"
                                rows="3"
                                required
                            ></textarea>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     DATA KK
                ================================================== --}}

                <div class="responden-section">

                    <div class="responden-section-heading">
                        Data Kartu Keluarga
                    </div>

                    <div class="responden-section-description">
                        Masukkan nomor KK dan nama kepala keluarga.
                    </div>

                    <div class="responden-form-grid">


                        {{-- NOMOR KK --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                No. KK
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="nomor_kk"
                                class="responden-form-control nomor-16-digit"
                                maxlength="16"
                                minlength="16"
                                pattern="[0-9]{16}"
                                inputmode="numeric"
                                placeholder="Masukkan 16 digit nomor KK"
                                required
                            >

                            <div style="
                                font-size:10px;
                                color:#888;
                                margin-top:5px;
                            ">
                                Harus tepat 16 digit angka.
                            </div>

                        </div>


                        {{-- NAMA KEPALA KELUARGA --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Nama Kepala Keluarga
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="nama_kepala_keluarga"
                                class="responden-form-control"
                                maxlength="255"
                                placeholder="Masukkan nama kepala keluarga"
                                required
                            >

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     ANGGOTA KELUARGA
                ================================================== --}}

                <div class="responden-section">

                    <div class="anggota-header">

                        <div>

                            <div class="anggota-header-text">
                                Anggota Keluarga
                            </div>

                            <div
                                class="responden-section-description"
                                style="margin-bottom:0;margin-top:4px;"
                            >
                                Tambahkan anggota keluarga yang akan didata.
                            </div>

                        </div>

                        <button
                            type="button"
                            class="btn-tambah-anggota"
                            onclick="tambahAnggota()"
                        >
                            + Tambah Anggota
                        </button>

                    </div>


                    <div id="anggotaContainer">

                        {{-- ANGGOTA PERTAMA --}}

                        <div
                            class="anggota-card"
                            data-index="0"
                        >

                            <div class="anggota-card-header">

                                <div class="anggota-card-title">

                                    <div class="anggota-number">
                                        1
                                    </div>

                                    <span>
                                        Anggota Keluarga 1
                                    </span>

                                </div>

                            </div>


                            <div class="responden-form-grid">


                                {{-- NIK --}}

                                <div class="responden-form-group">

                                    <label class="responden-form-label">
                                        NIK
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="anggota[0][nik]"
                                        class="responden-form-control nomor-16-digit"
                                        maxlength="16"
                                        minlength="16"
                                        pattern="[0-9]{16}"
                                        inputmode="numeric"
                                        placeholder="Masukkan 16 digit NIK"
                                        required
                                    >

                                </div>


                                {{-- NAMA --}}

                                <div class="responden-form-group">

                                    <label class="responden-form-label">
                                        Nama Lengkap
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        name="anggota[0][nama_lengkap]"
                                        class="responden-form-control"
                                        maxlength="255"
                                        placeholder="Masukkan nama lengkap"
                                        required
                                    >

                                </div>


                                {{-- STATUS --}}

                                <div class="responden-form-group full">

                                    <label class="responden-form-label">
                                        Status Keluarga
                                        <span class="required">*</span>
                                    </label>

                                    <select
                                        name="anggota[0][status_keluarga]"
                                        class="responden-form-control status-keluarga"
                                        onchange="toggleStatusLainnya(this)"
                                        required
                                    >

                                        <option value="">
                                            Pilih Status Keluarga
                                        </option>

                                        <option value="Kepala Keluarga">
                                            Kepala Keluarga
                                        </option>

                                        <option value="Istri">
                                            Istri
                                        </option>

                                        <option value="Suami">
                                            Suami
                                        </option>

                                        <option value="Anak">
                                            Anak
                                        </option>

                                        <option value="Orang Tua">
                                            Orang Tua
                                        </option>

                                        <option value="Saudara">
                                            Saudara
                                        </option>

                                        <option value="Famili">
                                            Famili
                                        </option>

                                        <option value="Lainnya">
                                            Lainnya
                                        </option>

                                    </select>

                                    <input
                                        type="text"
                                        name="anggota[0][status_keluarga_lainnya]"
                                        class="responden-form-control status-lainnya"
                                        placeholder="Ketik status keluarga"
                                        style="display:none;margin-top:8px;"
                                    >

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <div class="responden-modal-footer">

                <button
                    type="button"
                    class="responden-btn responden-btn-secondary"
                    onclick="tutupModalTambah()"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="responden-btn responden-btn-primary"
                >
                    Simpan Responden
                </button>

            </div>

        </form>

    </div>

</div>


{{-- =========================================================
     MODAL EDIT
========================================================= --}}

<div
    class="responden-modal-overlay"
    id="modalEditResponden"
>

    <div class="responden-modal">

        <form
            id="formEditResponden"
            method="POST"
        >

            @csrf
            @method('PUT')


            <div class="responden-modal-header">

                <div>

                    <div class="responden-modal-label">
                        DATA RESPONDEN
                    </div>

                    <h2 class="responden-modal-title">
                        Edit Responden
                    </h2>

                </div>

                <button
                    type="button"
                    class="responden-modal-close"
                    onclick="tutupModalEdit()"
                >
                    ×
                </button>

            </div>


            <div class="responden-modal-body">


                {{-- =================================================
                     WILAYAH EDIT
                ================================================== --}}

                <div class="responden-section">

                    <div class="responden-section-heading">
                        Wilayah / Alamat Keluarga
                    </div>

                    <div class="responden-section-description">
                        Ubah wilayah dan alamat tempat tinggal keluarga.
                    </div>


                    <div class="responden-form-grid">


                        {{-- PROVINSI --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Provinsi
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="provinsi"
                                id="edit_provinsi"
                                class="responden-form-control"
                                value="Jawa Timur"
                                readonly
                                required
                            >

                        </div>


                        {{-- KOTA --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Daerah/Kota
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="daerah"
                                id="edit_daerah"
                                class="responden-form-control"
                                value="Kota Pasuruan"
                                readonly
                                required
                            >

                        </div>


                        {{-- KECAMATAN --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Kecamatan
                                <span class="required">*</span>
                            </label>

                            <select
                                name="kecamatan_id"
                                id="edit_kecamatan"
                                class="responden-form-control"
                                required
                            >

                                <option value="">
                                    Pilih Kecamatan
                                </option>

                                @foreach ($kecamatans as $kecamatan)

                                    <option
                                        value="{{ $kecamatan->kecamatan_id }}"
                                    >
                                        {{ $kecamatan->deskripsi }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- KELURAHAN --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Kelurahan/Desa
                                <span class="required">*</span>
                            </label>

                            <select
                                name="kelurahan_id"
                                id="edit_kelurahan"
                                class="responden-form-control"
                                required
                            >

                                <option value="">
                                    Pilih Kecamatan terlebih dahulu
                                </option>

                            </select>

                        </div>


                        {{-- KODE POS --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Kode Pos
                            </label>

                            <input
                                type="text"
                                name="kode_pos"
                                id="edit_kode_pos"
                                class="responden-form-control"
                                maxlength="10"
                                inputmode="numeric"
                                placeholder="Masukkan kode pos"
                            >

                        </div>


                        {{-- RT RW --}}

                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                RT/RW
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="rt_rw"
                                id="edit_rt_rw"
                                class="responden-form-control"
                                maxlength="20"
                                placeholder="Contoh: 001/002"
                                required
                            >

                        </div>


                        {{-- ALAMAT --}}

                        <div class="responden-form-group full">

                            <label class="responden-form-label">
                                Alamat Lengkap
                                <span class="required">*</span>
                            </label>

                            <textarea
                                name="alamat_lengkap"
                                id="edit_alamat_lengkap"
                                class="responden-form-control"
                                rows="3"
                                placeholder="Masukkan alamat lengkap"
                                required
                            ></textarea>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     DATA KK EDIT
                ================================================== --}}

                <div class="responden-section">

                    <div class="responden-section-heading">
                        Data Kartu Keluarga
                    </div>

                    <div class="responden-section-description">
                        Ubah nomor KK dan nama kepala keluarga.
                    </div>

                    <div class="responden-form-grid">


                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                No. KK
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="nomor_kk"
                                id="edit_nomor_kk"
                                class="responden-form-control nomor-16-digit"
                                maxlength="16"
                                minlength="16"
                                pattern="[0-9]{16}"
                                inputmode="numeric"
                                placeholder="Masukkan 16 digit nomor KK"
                                required
                            >

                            <div style="
                                font-size:10px;
                                color:#888;
                                margin-top:5px;
                            ">
                                Harus tepat 16 digit angka.
                            </div>

                        </div>


                        <div class="responden-form-group">

                            <label class="responden-form-label">
                                Nama Kepala Keluarga
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="nama_kepala_keluarga"
                                id="edit_nama_kepala_keluarga"
                                class="responden-form-control"
                                maxlength="255"
                                required
                            >

                        </div>

                    </div>

                </div>


                {{-- =================================================
                     ANGGOTA EDIT
                ================================================== --}}

                <div class="responden-section">

                    <div class="anggota-header">

                        <div>

                            <div class="anggota-header-text">
                                Anggota Keluarga
                            </div>

                            <div
                                class="responden-section-description"
                                style="margin-bottom:0;margin-top:4px;"
                            >
                                Ubah data anggota keluarga yang telah terdaftar.
                            </div>

                        </div>

                        <button
                            type="button"
                            class="btn-tambah-anggota"
                            onclick="tambahAnggotaEdit()"
                        >
                            + Tambah Anggota
                        </button>

                    </div>


                    <div id="anggotaEditContainer"></div>

                </div>

            </div>


            <div class="responden-modal-footer">

                <button
                    type="button"
                    class="responden-btn responden-btn-secondary"
                    onclick="tutupModalEdit()"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="responden-btn responden-btn-primary"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection


@push('scripts')

<script>

/* =========================================================
   MODAL TAMBAH
========================================================= */

function bukaModalTambah() {

    const modal = document.getElementById(
        'modalTambahResponden'
    );

    if (modal) {
        modal.classList.add('active');
    }

    document.body.style.overflow = 'hidden';

}


function tutupModalTambah() {

    const modal = document.getElementById(
        'modalTambahResponden'
    );

    if (modal) {
        modal.classList.remove('active');
    }

    document.body.style.overflow = '';

}


/* =========================================================
   MODAL EDIT
========================================================= */

function tutupModalEdit() {

    const modal = document.getElementById(
        'modalEditResponden'
    );

    if (modal) {
        modal.classList.remove('active');
    }

    document.body.style.overflow = '';

}


/* =========================================================
   STATUS LAINNYA
========================================================= */

function toggleStatusLainnya(select) {

    const parent = select.closest(
        '.responden-form-group'
    );

    if (!parent) {
        return;
    }

    const input = parent.querySelector(
        '.status-lainnya'
    );

    if (!input) {
        return;
    }

    if (select.value === 'Lainnya') {

        input.style.display = 'block';
        input.required = true;

    } else {

        input.style.display = 'none';
        input.required = false;
        input.value = '';

    }

}


/* =========================================================
   NOMOR HANYA ANGKA
========================================================= */

document.addEventListener(
    'input',
    function(event) {

        if (
            event.target.classList.contains(
                'nomor-16-digit'
            )
        ) {

            event.target.value =
                event.target.value
                    .replace(/\D/g, '')
                    .slice(0, 16);

        }

    }
);


/* =========================================================
   TAMBAH ANGGOTA
========================================================= */

let anggotaIndex = 1;


function tambahAnggota() {

    const container =
        document.getElementById(
            'anggotaContainer'
        );

    if (!container) {
        return;
    }

    const index = anggotaIndex;

    const nomor = index + 1;


    const html = `

        <div
            class="anggota-card"
            data-index="${index}"
        >

            <div class="anggota-card-header">

                <div class="anggota-card-title">

                    <div class="anggota-number">
                        ${nomor}
                    </div>

                    <span>
                        Anggota Keluarga ${nomor}
                    </span>

                </div>

                <button
                    type="button"
                    class="btn-hapus-anggota"
                    onclick="hapusAnggota(this)"
                >
                    Hapus
                </button>

            </div>


            <div class="responden-form-grid">


                <div class="responden-form-group">

                    <label class="responden-form-label">
                        NIK
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="anggota[${index}][nik]"
                        class="responden-form-control nomor-16-digit"
                        maxlength="16"
                        minlength="16"
                        pattern="[0-9]{16}"
                        inputmode="numeric"
                        placeholder="Masukkan 16 digit NIK"
                        required
                    >

                </div>


                <div class="responden-form-group">

                    <label class="responden-form-label">
                        Nama Lengkap
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="anggota[${index}][nama_lengkap]"
                        class="responden-form-control"
                        maxlength="255"
                        placeholder="Masukkan nama lengkap"
                        required
                    >

                </div>


                <div class="responden-form-group full">

                    <label class="responden-form-label">
                        Status Keluarga
                        <span class="required">*</span>
                    </label>

                    <select
                        name="anggota[${index}][status_keluarga]"
                        class="responden-form-control status-keluarga"
                        onchange="toggleStatusLainnya(this)"
                        required
                    >

                        <option value="">
                            Pilih Status Keluarga
                        </option>

                        <option value="Kepala Keluarga">
                            Kepala Keluarga
                        </option>

                        <option value="Istri">
                            Istri
                        </option>

                        <option value="Suami">
                            Suami
                        </option>

                        <option value="Anak">
                            Anak
                        </option>

                        <option value="Orang Tua">
                            Orang Tua
                        </option>

                        <option value="Saudara">
                            Saudara
                        </option>

                        <option value="Famili">
                            Famili
                        </option>

                        <option value="Lainnya">
                            Lainnya
                        </option>

                    </select>


                    <input
                        type="text"
                        name="anggota[${index}][status_keluarga_lainnya]"
                        class="responden-form-control status-lainnya"
                        placeholder="Ketik status keluarga"
                        style="display:none;margin-top:8px;"
                    >

                </div>

            </div>

        </div>

    `;


    container.insertAdjacentHTML(
        'beforeend',
        html
    );


    anggotaIndex++;

    updateNomorAnggota();

}


function hapusAnggota(button) {

    const card =
        button.closest('.anggota-card');

    if (card) {
        card.remove();
    }

    updateNomorAnggota();

}


function updateNomorAnggota() {

    const cards =
        document.querySelectorAll(
            '#anggotaContainer .anggota-card'
        );

    cards.forEach(
        function(card, index) {

            const number =
                card.querySelector(
                    '.anggota-number'
                );

            const title =
                card.querySelector(
                    '.anggota-card-title span'
                );

            if (number) {
                number.textContent =
                    index + 1;
            }

            if (title) {
                title.textContent =
                    'Anggota Keluarga ' +
                    (index + 1);
            }

        }
    );

}


/* =========================================================
   EDIT RESPONDEN
========================================================= */

let anggotaEditIndex = 0;


function editResponden(id) {

    fetch(
        '/responden/' +
        id +
        '/edit-data'
    )

        .then(
            response => {

                if (!response.ok) {

                    throw new Error(
                        'Gagal mengambil data.'
                    );

                }

                return response.json();

            }
        )

        .then(
            data => {

                const modal =
                    document.getElementById(
                        'modalEditResponden'
                    );

                const form =
                    document.getElementById(
                        'formEditResponden'
                    );


                form.action =
                    '/responden/update/' +
                    id;


                document.getElementById(
                    'edit_provinsi'
                ).value =
                    'Jawa Timur';


                document.getElementById(
                    'edit_daerah'
                ).value =
                    'Kota Pasuruan';


                document.getElementById(
                    'edit_kode_pos'
                ).value =
                    data.kode_pos ?? '';


                document.getElementById(
                    'edit_rt_rw'
                ).value =
                    data.rt_rw ?? '';


                document.getElementById(
                    'edit_alamat_lengkap'
                ).value =
                    data.alamat_lengkap ?? '';


                document.getElementById(
                    'edit_nomor_kk'
                ).value =
                    data.no_kk ?? '';


                document.getElementById(
                    'edit_nama_kepala_keluarga'
                ).value =
                    data.nama_lengkap ?? '';


                const kecamatan =
                    document.getElementById(
                        'edit_kecamatan'
                    );


                kecamatan.value =
                    data.kecamatan_id ?? '';


                if (data.kecamatan_id) {

                    loadKelurahan(
                        data.kecamatan_id,
                        'edit_kelurahan',
                        data.kelurahan_id
                    );

                } else {

                    const kelurahan =
                        document.getElementById(
                            'edit_kelurahan'
                        );

                    kelurahan.innerHTML = `
                        <option value="">
                            Pilih Kecamatan terlebih dahulu
                        </option>
                    `;

                }


                const container =
                    document.getElementById(
                        'anggotaEditContainer'
                    );


                container.innerHTML = '';

                anggotaEditIndex = 0;


                if (
                    data.anggota &&
                    data.anggota.length > 0
                ) {

                    data.anggota.forEach(
                        function(anggota) {

                            tambahAnggotaEdit(
                                anggota
                            );

                        }
                    );

                } else {

                    tambahAnggotaEdit();

                }


                modal.classList.add(
                    'active'
                );

                document.body.style.overflow =
                    'hidden';

            }
        )

        .catch(
            error => {

                console.error(error);

                alert(
                    'Data responden gagal dimuat.'
                );

            }
        );

}


/* =========================================================
   TAMBAH ANGGOTA EDIT
========================================================= */

function tambahAnggotaEdit(
    data = null
) {

    const container =
        document.getElementById(
            'anggotaEditContainer'
        );

    if (!container) {
        return;
    }

    const index =
        anggotaEditIndex;

    const nomor =
        index + 1;

    const nik =
        data?.nik ?? '';

    const nama =
        data?.nama_lengkap ?? '';

    const status =
        data?.status_keluarga ?? '';


    const statusNormal = [
        'Kepala Keluarga',
        'Istri',
        'Suami',
        'Anak',
        'Orang Tua',
        'Saudara',
        'Famili'
    ];


    const statusLainnya =
        status !== '' &&
        !statusNormal.includes(status)
            ? status
            : '';


    const statusValue =
        statusLainnya !== ''
            ? 'Lainnya'
            : status;


    const html = `

        <div
            class="anggota-card"
            data-edit-index="${index}"
        >

            <div class="anggota-card-header">

                <div class="anggota-card-title">

                    <div class="anggota-number">
                        ${nomor}
                    </div>

                    <span>
                        Anggota Keluarga ${nomor}
                    </span>

                </div>


                <button
                    type="button"
                    class="btn-hapus-anggota"
                    onclick="hapusAnggotaEdit(this)"
                >
                    Hapus
                </button>

            </div>


            <div class="responden-form-grid">


                <div class="responden-form-group">

                    <label class="responden-form-label">
                        NIK
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="anggota[${index}][nik]"
                        class="responden-form-control nomor-16-digit"
                        maxlength="16"
                        minlength="16"
                        pattern="[0-9]{16}"
                        inputmode="numeric"
                        value="${escapeHtml(nik)}"
                        required
                    >

                </div>


                <div class="responden-form-group">

                    <label class="responden-form-label">
                        Nama Lengkap
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="anggota[${index}][nama_lengkap]"
                        class="responden-form-control"
                        maxlength="255"
                        value="${escapeHtml(nama)}"
                        required
                    >

                </div>


                <div class="responden-form-group full">

                    <label class="responden-form-label">
                        Status Keluarga
                        <span class="required">*</span>
                    </label>

                    <select
                        name="anggota[${index}][status_keluarga]"
                        class="responden-form-control status-keluarga"
                        onchange="toggleStatusLainnya(this)"
                        required
                    >

                        <option value="">
                            Pilih Status Keluarga
                        </option>

                        <option
                            value="Kepala Keluarga"
                            ${statusValue === 'Kepala Keluarga' ? 'selected' : ''}
                        >
                            Kepala Keluarga
                        </option>

                        <option
                            value="Istri"
                            ${statusValue === 'Istri' ? 'selected' : ''}
                        >
                            Istri
                        </option>

                        <option
                            value="Suami"
                            ${statusValue === 'Suami' ? 'selected' : ''}
                        >
                            Suami
                        </option>

                        <option
                            value="Anak"
                            ${statusValue === 'Anak' ? 'selected' : ''}
                        >
                            Anak
                        </option>

                        <option
                            value="Orang Tua"
                            ${statusValue === 'Orang Tua' ? 'selected' : ''}
                        >
                            Orang Tua
                        </option>

                        <option
                            value="Saudara"
                            ${statusValue === 'Saudara' ? 'selected' : ''}
                        >
                            Saudara
                        </option>

                        <option
                            value="Famili"
                            ${statusValue === 'Famili' ? 'selected' : ''}
                        >
                            Famili
                        </option>

                        <option
                            value="Lainnya"
                            ${statusValue === 'Lainnya' ? 'selected' : ''}
                        >
                            Lainnya
                        </option>

                    </select>


                    <input
                        type="text"
                        name="anggota[${index}][status_keluarga_lainnya]"
                        class="responden-form-control status-lainnya"
                        placeholder="Ketik status keluarga"
                        value="${escapeHtml(statusLainnya)}"
                        style="
                            display:${statusLainnya !== '' ? 'block' : 'none'};
                            margin-top:8px;
                        "
                        ${statusLainnya !== '' ? 'required' : ''}
                    >

                </div>

            </div>

        </div>

    `;


    container.insertAdjacentHTML(
        'beforeend',
        html
    );


    anggotaEditIndex++;

    updateNomorAnggotaEdit();

}


function hapusAnggotaEdit(button) {

    const card =
        button.closest('.anggota-card');

    if (card) {
        card.remove();
    }

    updateNomorAnggotaEdit();

}


function updateNomorAnggotaEdit() {

    const cards =
        document.querySelectorAll(
            '#anggotaEditContainer .anggota-card'
        );

    cards.forEach(
        function(card, index) {

            const number =
                card.querySelector(
                    '.anggota-number'
                );

            const title =
                card.querySelector(
                    '.anggota-card-title span'
                );

            if (number) {
                number.textContent =
                    index + 1;
            }

            if (title) {
                title.textContent =
                    'Anggota Keluarga ' +
                    (index + 1);
            }

        }
    );

}


/* =========================================================
   HAPUS RESPONDEN
========================================================= */

function hapusResponden(id) {

    const konfirmasi =
        confirm(
            'Apakah Anda yakin ingin menghapus data responden ini?'
        );

    if (!konfirmasi) {
        return;
    }


    const form =
        document.createElement('form');

    form.method = 'POST';

    form.action =
        '/responden/hapus/' +
        id;


    const csrf =
        document.createElement('input');

    csrf.type = 'hidden';

    csrf.name = '_token';

    csrf.value =
        '{{ csrf_token() }}';


    const method =
        document.createElement('input');

    method.type = 'hidden';

    method.name = '_method';

    method.value = 'DELETE';


    form.appendChild(csrf);

    form.appendChild(method);

    document.body.appendChild(form);

    form.submit();

}


/* =========================================================
   KELURAHAN BERDASARKAN KECAMATAN
========================================================= */

function loadKelurahan(
    kecamatanId,
    targetId,
    selectedId = null
) {

    const select =
        document.getElementById(
            targetId
        );

    if (!select) {
        return;
    }


    select.innerHTML = `
        <option value="">
            Memuat Kelurahan/Desa...
        </option>
    `;


    if (!kecamatanId) {

        select.innerHTML = `
            <option value="">
                Pilih Kecamatan terlebih dahulu
            </option>
        `;

        return;

    }


    fetch(
        '/responden/kelurahan/' +
        kecamatanId
    )

        .then(
            response => {

                if (!response.ok) {

                    throw new Error(
                        'Gagal mengambil data kelurahan.'
                    );

                }

                return response.json();

            }
        )

        .then(
            result => {

                /*
                |--------------------------------------------------------------------------
                | CONTROLLER MENGIRIM:
                |
                | {
                |     success: true,
                |     data: [...]
                | }
                |
                | Jadi array kelurahan berada di result.data
                |--------------------------------------------------------------------------
                */

                console.log(
                    'Response Kelurahan:',
                    result
                );


                const data =
                    result.data ?? [];


                select.innerHTML = `
                    <option value="">
                        Pilih Kelurahan/Desa
                    </option>
                `;


                if (data.length === 0) {

                    select.innerHTML = `
                        <option value="">
                            Kelurahan/Desa tidak ditemukan
                        </option>
                    `;

                    return;

                }


                data.forEach(
                    function(item) {

                        const option =
                            document.createElement(
                                'option'
                            );


                        option.value =
                            item.kelurahan_id;


                        option.textContent =
                            item.deskripsi;


                        if (
                            selectedId !== null &&
                            String(item.kelurahan_id) ===
                            String(selectedId)
                        ) {

                            option.selected =
                                true;

                        }


                        select.appendChild(
                            option
                        );

                    }
                );

            }
        )

        .catch(
            error => {

                console.error(
                    'Error Kelurahan:',
                    error
                );


                select.innerHTML = `
                    <option value="">
                        Kelurahan gagal dimuat
                    </option>
                `;

            }
        );

}


/* =========================================================
   KECAMATAN TAMBAH & EDIT
========================================================= */

document.addEventListener(
    'DOMContentLoaded',
    function() {

        const kecamatan =
            document.getElementById(
                'kecamatan'
            );


        if (kecamatan) {

            kecamatan.addEventListener(
                'change',
                function() {

                    loadKelurahan(
                        this.value,
                        'kelurahan'
                    );

                }
            );

        }


        const editKecamatan =
            document.getElementById(
                'edit_kecamatan'
            );


        if (editKecamatan) {

            editKecamatan.addEventListener(
                'change',
                function() {

                    loadKelurahan(
                        this.value,
                        'edit_kelurahan'
                    );

                }
            );

        }

    }
);


/* =========================================================
   SEARCH
========================================================= */

document.addEventListener(
    'DOMContentLoaded',
    function() {

        const search =
            document.getElementById(
                'searchResponden'
            );


        if (!search) {
            return;
        }


        search.addEventListener(
            'input',
            function() {

                const keyword =
                    this.value
                        .toLowerCase()
                        .trim();


                const rows =
                    document.querySelectorAll(
                        '#respondenTableBody .responden-row'
                    );


                rows.forEach(
                    function(row) {

                        const text =
                            row.textContent
                                .toLowerCase();


                        row.style.display =
                            text.includes(keyword)
                                ? ''
                                : 'none';

                    }
                );

            }
        );

    }
);


/* =========================================================
   ESCAPE HTML
========================================================= */

function escapeHtml(value) {

    const div =
        document.createElement(
            'div'
        );

    div.textContent =
        value ?? '';

    return div.innerHTML;

}


/* =========================================================
   CLICK OUTSIDE MODAL
========================================================= */

document.addEventListener(
    'DOMContentLoaded',
    function() {

        const modalTambah =
            document.getElementById(
                'modalTambahResponden'
            );


        const modalEdit =
            document.getElementById(
                'modalEditResponden'
            );


        if (modalTambah) {

            modalTambah.addEventListener(
                'click',
                function(event) {

                    if (
                        event.target ===
                        this
                    ) {

                        tutupModalTambah();

                    }

                }
            );

        }


        if (modalEdit) {

            modalEdit.addEventListener(
                'click',
                function(event) {

                    if (
                        event.target ===
                        this
                    ) {

                        tutupModalEdit();

                    }

                }
            );

        }

    }
);


/* =========================================================
   ESC KEY
========================================================= */

document.addEventListener(
    'keydown',
    function(event) {

        if (
            event.key ===
            'Escape'
        ) {

            tutupModalTambah();

            tutupModalEdit();

        }

    }
);

</script>

@endpush