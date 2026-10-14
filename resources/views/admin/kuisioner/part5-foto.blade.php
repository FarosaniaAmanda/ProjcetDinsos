
@extends('admin.kuisioner.layout')

@section('kuisioner-content')
<style>
    /* =========================
       DASAR HALAMAN
    ========================= */
    .part5-foto-wrapper {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        padding-bottom: 24px;
        color: #30364b;
    }

    .part5-foto-header {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 24px;
    }

    .part5-foto-title {
        margin: 0 0 6px;
        color: #252A86;
        font-size: 21px;
        font-weight: 750;
        line-height: 1.4;
    }

    .part5-foto-subtitle {
        margin: 0;
        color: #737b8c;
        font-size: 13px;
        line-height: 1.7;
    }

    /* =========================
       DAFTAR KARTU FOTO
    ========================= */
    .part5-foto-list {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .part5-foto-card {
        display: grid;
        grid-template-columns: minmax(250px, 340px) minmax(0, 1fr);
        gap: 26px;
        align-items: start;
        padding: 22px;
        background: #fff;
        border: 1px solid #e7eaf1;
        border-radius: 13px;
        box-shadow: 0 2px 7px rgba(28, 39, 75, .035);
        transition: border-color .2s, box-shadow .2s;
    }

    .part5-foto-card:hover {
        border-color: #d4daf2;
        box-shadow: 0 5px 15px rgba(28, 39, 75, .055);
    }

    .part5-foto-left {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        grid-template-rows: auto 190px auto;
        align-content: start;
        min-width: 0;
    }

    /* Judul pertanyaan 69, 70, 71 */
    .part5-foto-number {
        display: inline-flex;
        justify-self: start;
        align-items: center;
        min-height: 36px;
        height: auto;
        margin: 0 0 12px;
        padding: 7px 13px;
        color: #303d88;
        background: #eef0ff;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 750;
        line-height: 1.4;
    }

    /* Pratinjau foto */
    .part5-foto-thumbnail {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 190px;
        min-height: 190px;
        overflow: hidden;
        background: #f5f7fb;
        border: 1px solid #e1e6f0;
        border-radius: 11px;
    }

    .part5-foto-thumbnail img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: zoom-in;
    }

    .part5-foto-thumbnail-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 12px;
        color: #8994b2;
        text-align: center;
    }

    .part5-foto-thumbnail-empty-icon {
        font-size: 35px;
        line-height: 1;
    }

    .part5-foto-thumbnail-empty-text {
        font-size: 12px;
        font-weight: 500;
    }

    .part5-foto-filename {
        margin-top: 7px;
        color: #17804b;
        font-size: 12px;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    /* =========================
       AREA TAMBAH FOTO
    ========================= */
    .part5-foto-input {
        display: none !important;
    }

    .part5-foto-card > div:last-child {
        min-width: 0;
        padding-top: 48px;
    }

    .part5-foto-upload {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 190px;
        min-height: 190px;
        padding: 18px;
        color: #555f78;
        background: #f9faff;
        border: 1.5px dashed #b9c5e3;
        border-radius: 11px;
        text-align: center;
        cursor: pointer;
        transition: background .2s, border-color .2s;
    }

    .part5-foto-upload:hover {
        background: #f1f4ff;
        border-color: #6477c2;
    }

    .part5-foto-upload:focus-visible {
        outline: 3px solid rgba(37, 42, 134, .2);
        outline-offset: 3px;
    }

    .part5-foto-upload-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .part5-foto-upload-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 52px;
        color: #fff;
        background: #303d88;
        border-radius: 15px;
        font-size: 23px;
        box-shadow: 0 4px 10px rgba(48, 61, 136, .16);
    }

    .part5-foto-upload-title {
        color: #303d80;
        font-size: 15px;
        font-weight: 750;
    }

    .part5-foto-upload-description {
        color: #737e97;
        font-size: 12px;
        line-height: 1.7;
    }

    .part5-foto-upload-hint {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 0;
        padding: 7px 12px;
        color: #303d88;
        background: #edf0ff;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 700;
    }

    .part5-foto-error {
        margin-top: 8px;
        color: #c93646;
        font-size: 12px;
        line-height: 1.5;
    }

    /* =========================
       CATATAN FORMAT FOTO
    ========================= */
    .part5-foto-note {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-top: 21px;
        padding: 14px 16px;
        background: #fffaf0;
        border: 1px solid #f1e6c9;
        border-radius: 9px;
    }

    .part5-foto-note-icon {
        color: #b17b19;
        font-size: 17px;
    }

    .part5-foto-note-content {
        color: #866a36;
        font-size: 11px;
        line-height: 1.8;
    }

    .part5-foto-note-content strong {
        display: block;
        margin-bottom: 2px;
        color: #795c25;
        font-size: 12px;
    }

    /* =========================
       TOMBOL BAWAH
    ========================= */
    .part5-foto-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 25px;
        padding-top: 18px;
        border-top: 1px solid #eceef4;
    }

    .part5-foto-footer-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
    }

    .part5-foto-back,
    .part5-foto-draft,
    .part5-foto-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 41px;
        padding: 10px 16px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: background .2s, border-color .2s;
    }

    .part5-foto-back {
        color: #626b7c;
        background: #fff;
        border-color: #dfe3eb;
    }

    .part5-foto-back:hover {
        color: #30364b;
        background: #f7f8fb;
    }

    .part5-foto-draft {
        color: #303d80;
        background: #fff;
        border-color: #cfd5ed;
    }

    .part5-foto-draft:hover {
        color: #252A86;
        background: #f3f5ff;
        border-color: #aeb8e6;
    }

    .part5-foto-submit {
        color: #fff;
        background: #252A86;
        border-color: #252A86;
    }

    .part5-foto-submit:hover {
        color: #fff;
        background: #1d216d;
    }

    /* =========================
       MODAL PILIH SUMBER FOTO
    ========================= */
    .foto-choice-overlay {
        position: fixed;
        inset: 0;
        z-index: 999998;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 18px;
        background: rgba(23, 29, 52, .55);
        backdrop-filter: blur(3px);
    }

    .foto-choice-overlay.active {
        display: flex;
    }

    .foto-choice-dialog {
        position: relative;
        width: 100%;
        max-width: 390px;
        padding: 25px;
        background: #fff;
        border: 1px solid #e8eaf2;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(14, 23, 55, .2);
        animation: fotoModalIn .18s ease-out;
    }

    @keyframes fotoModalIn {
        from {
            opacity: 0;
            transform: translateY(7px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .foto-choice-close {
        position: absolute;
        top: 13px;
        right: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        color: #70778a;
        background: #f2f4f8;
        border: none;
        border-radius: 8px;
        font-size: 20px;
        cursor: pointer;
    }

    .foto-choice-heading {
        margin: 0 32px 6px 0;
        color: #252A86;
        font-size: 17px;
        font-weight: 750;
    }

    .foto-choice-description {
        margin: 0 0 20px;
        color: #7b8292;
        font-size: 12px;
        line-height: 1.7;
    }

    .foto-choice-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .foto-choice-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 120px;
        padding: 15px 10px;
        color: #30364b;
        background: #fafbff;
        border: 1px solid #e3e7f2;
        border-radius: 11px;
        cursor: pointer;
        transition: background .2s, border-color .2s;
    }

    .foto-choice-option:hover {
        color: #252A86;
        background: #f1f3ff;
        border-color: #bfc8eb;
    }

    .foto-choice-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 46px;
        height: 46px;
        color: #394b9a;
        background: #e9edff;
        border-radius: 13px;
        font-size: 25px;
    }

    .foto-choice-option-label {
        font-size: 12px;
        font-weight: 750;
    }

    .foto-choice-cancel {
        width: 100%;
        margin-top: 12px;
        padding: 10px;
        color: #737b8c;
        background: transparent;
        border: none;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .foto-choice-cancel:hover {
        background: #f4f5f8;
    }

    /* =========================
       MODAL KAMERA
    ========================= */
    .kamera-overlay {
        position: fixed;
        inset: 0;
        z-index: 999999;
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 15px;
        padding: 18px;
        background: rgba(13, 16, 27, .97);
    }

    .kamera-overlay.active {
        display: flex;
    }

    .kamera-title {
        color: #fff;
        font-size: 17px;
        font-weight: 750;
        text-align: center;
    }

    .kamera-video {
        display: block;
        width: 100%;
        max-width: 700px;
        max-height: 62vh;
        background: #08090c;
        border-radius: 12px;
        object-fit: contain;
    }

    .kamera-status {
        max-width: 500px;
        color: #d9deec;
        font-size: 12px;
        line-height: 1.7;
        text-align: center;
    }

    .kamera-controls {
        display: flex;
        justify-content: center;
        gap: 9px;
        flex-wrap: wrap;
    }

    .kamera-btn {
        min-height: 40px;
        padding: 10px 15px;
        border: none;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .kamera-ambil {
        color: #fff;
        background: #198754;
    }

    .kamera-switch {
        color: #303d80;
        background: #fff;
    }

    .kamera-tutup {
        color: #fff;
        background: #dc3545;
    }

    .kamera-btn:disabled {
        opacity: .6;
        cursor: not-allowed;
    }

    /* =========================
       MODAL PREVIEW FOTO
    ========================= */
    .foto-viewer-overlay {
        position: fixed;
        inset: 0;
        z-index: 1000000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(13, 16, 27, .88);
    }

    .foto-viewer-overlay.active {
        display: flex;
    }

    .foto-viewer-content {
        position: relative;
        max-width: 92vw;
        max-height: 88vh;
        padding: 18px;
        background: #fff;
        border-radius: 12px;
    }

    .foto-viewer-title {
        margin: 0 38px 13px 0;
        color: #252A86;
        font-size: 13px;
        font-weight: 750;
    }

    .foto-viewer-close {
        position: absolute;
        top: 10px;
        right: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 29px;
        height: 29px;
        color: #fff;
        background: #252A86;
        border: none;
        border-radius: 7px;
        font-size: 18px;
        cursor: pointer;
    }

    .foto-viewer-content img {
        display: block;
        max-width: 85vw;
        max-height: 72vh;
        border-radius: 7px;
        object-fit: contain;
    }

    /* =========================
       RESPONSIVE TABLET DAN HP
    ========================= */
    @media (max-width: 700px) {
        .part5-foto-title {
            font-size: 19px;
        }

        .part5-foto-card {
            grid-template-columns: minmax(0, 1fr);
            gap: 15px;
            padding: 16px;
        }

        .part5-foto-left {
            grid-template-columns: minmax(0, 1fr);
            grid-template-rows: auto 180px auto;
        }

        .part5-foto-number {
            grid-column: 1;
            grid-row: 1;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .part5-foto-thumbnail {
            grid-column: 1;
            grid-row: 2;
            height: 180px;
            min-height: 180px;
        }

        .part5-foto-filename {
            grid-column: 1;
        }

        .part5-foto-card > div:last-child {
            padding-top: 0;
        }

        .part5-foto-upload {
            height: 170px;
            min-height: 170px;
        }

        .part5-foto-upload-icon {
            width: 46px;
            height: 46px;
            font-size: 21px;
        }

        .part5-foto-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .part5-foto-footer-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .part5-foto-back,
        .part5-foto-draft,
        .part5-foto-submit {
            width: 100%;
        }
    }

    @media (max-width: 380px) {
        .part5-foto-card {
            padding: 13px;
        }

        .part5-foto-number {
            font-size: 13px;
        }

        .part5-foto-thumbnail {
            height: 155px;
            min-height: 155px;
        }

        .part5-foto-left {
            grid-template-rows: auto 155px auto;
        }

        .part5-foto-upload {
            height: 155px;
            min-height: 155px;
            padding: 12px;
        }

        .foto-choice-dialog {
            padding: 20px;
        }
    }
</style>

<div class="part5-foto-wrapper">

    {{-- HEADER --}}
    <div class="part5-foto-header">
        <div>
            <h2 class="part5-foto-title">Part 5 - Dokumentasi Foto Rumah</h2>
            <p class="part5-foto-subtitle">
                Lengkapi dokumentasi kondisi rumah responden
                sebagai bagian dari proses pendataan.
            </p>
        </div>
    </div>

    <form
        id="form-foto-rumah"
        action="{{ route('kuisioner.part5.foto.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        <div class="part5-foto-list">

            {{-- 69. TAMPAK DEPAN RUMAH --}}
            <div class="part5-foto-card" data-foto="tampak_depan">
                <div class="part5-foto-left">
                    <span class="part5-foto-number">
                        69. Tampak Depan Rumah
                    </span>

                    <div class="part5-foto-thumbnail" data-thumbnail="tampak_depan">
                        @if (isset($fotoRumah['tampak_depan']))
                            <img
                                src="{{ asset('storage/' . $fotoRumah['tampak_depan']->path_file) }}"
                                alt="Foto tampak depan rumah"
                                data-view-title="Tampak Depan Rumah"
                            >
                        @else
                            <div class="part5-foto-thumbnail-empty">
                                <span class="part5-foto-thumbnail-empty-icon">
                                    <i class="far fa-image"></i>
                                </span>
                                <span class="part5-foto-thumbnail-empty-text">
                                    Belum ada foto
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="part5-foto-filename" data-filename="tampak_depan"></div>
                </div>

                <div>
                    <input
                        type="file"
                        name="tampak_depan"
                        class="part5-foto-input"
                        accept="image/jpeg,image/png,image/jpg"
                        required
                    >

                    <button
                        type="button"
                        class="part5-foto-upload"
                        data-foto-choice="tampak_depan"
                        aria-label="Tambah foto tampak depan rumah"
                    >
                        <span class="part5-foto-upload-content">

                            <span class="part5-foto-upload-title">
                                Tambahkan Foto
                            </span>

                            <span class="part5-foto-upload-description">
                                Ambil foto menggunakan kamera<br>
                                atau pilih dari galeri/file
                            </span>
                        </span>
                    </button>

                    @error('tampak_depan')
                        <div class="part5-foto-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- 70. RUANG TAMU --}}
            <div class="part5-foto-card" data-foto="ruang_tamu">
                <div class="part5-foto-left">
                    <span class="part5-foto-number">
                        70. Ruang Tamu
                    </span>

                    <div class="part5-foto-thumbnail" data-thumbnail="ruang_tamu">
                        @if (isset($fotoRumah['ruang_tamu']))
                            <img
                                src="{{ asset('storage/' . $fotoRumah['ruang_tamu']->path_file) }}"
                                alt="Foto ruang tamu"
                                data-view-title="Ruang Tamu"
                            >
                        @else
                            <div class="part5-foto-thumbnail-empty">
                                <span class="part5-foto-thumbnail-empty-icon">
                                    <i class="far fa-image"></i>
                                </span>
                                <span class="part5-foto-thumbnail-empty-text">
                                    Belum ada foto
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="part5-foto-filename" data-filename="ruang_tamu"></div>
                </div>

                <div>
                    <input
                        type="file"
                        name="ruang_tamu"
                        class="part5-foto-input"
                        accept="image/jpeg,image/png,image/jpg"
                        required
                    >

                    <button
                        type="button"
                        class="part5-foto-upload"
                        data-foto-choice="ruang_tamu"
                        aria-label="Tambah foto ruang tamu"
                    >
                        <span class="part5-foto-upload-content">

                            <span class="part5-foto-upload-title">
                                Tambahkan Foto
                            </span>

                            <span class="part5-foto-upload-description">
                                Ambil foto menggunakan kamera<br>
                                atau pilih dari galeri/file
                            </span>
                        </span>
                    </button>

                    @error('ruang_tamu')
                        <div class="part5-foto-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- 71. KAMAR MANDI --}}
            <div class="part5-foto-card" data-foto="kamar_mandi">
                <div class="part5-foto-left">
                    <span class="part5-foto-number">
                        71. Kamar Mandi
                    </span>

                    <div class="part5-foto-thumbnail" data-thumbnail="kamar_mandi">
                        @if (isset($fotoRumah['kamar_mandi']))
                            <img
                                src="{{ asset('storage/' . $fotoRumah['kamar_mandi']->path_file) }}"
                                alt="Foto kamar mandi"
                                data-view-title="Kamar Mandi"
                            >
                        @else
                            <div class="part5-foto-thumbnail-empty">
                                <span class="part5-foto-thumbnail-empty-icon">
                                    <i class="far fa-image"></i>
                                </span>
                                <span class="part5-foto-thumbnail-empty-text">
                                    Belum ada foto
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="part5-foto-filename" data-filename="kamar_mandi"></div>
                </div>

                <div>
                    <input
                        type="file"
                        name="kamar_mandi"
                        class="part5-foto-input"
                        accept="image/jpeg,image/png,image/jpg"
                        required
                    >

                    <button
                        type="button"
                        class="part5-foto-upload"
                        data-foto-choice="kamar_mandi"
                        aria-label="Tambah foto kamar mandi"
                    >
                        <span class="part5-foto-upload-content">

                            <span class="part5-foto-upload-title">
                                Tambahkan Foto
                            </span>

                            <span class="part5-foto-upload-description">
                                Ambil foto menggunakan kamera<br>
                                atau pilih dari galeri/file
                            </span>
                        </span>
                    </button>

                    @error('kamar_mandi')
                        <div class="part5-foto-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        </div>

        {{-- CATATAN --}}
        <div class="part5-foto-note">
            <div class="part5-foto-note-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>

            <div class="part5-foto-note-content">
                <strong>Ketentuan berkas foto</strong>
                Gunakan format JPG, JPEG, atau PNG. Ukuran maksimal yang
                diperbolehkan adalah 5 MB untuk setiap foto.
                Pastikan gambar dapat dibuka dan tidak buram.
            </div>
        </div>

        {{-- TOMBOL AKSI --}}
        <div class="part5-foto-footer">
            <a href="{{ route('kuisioner.part5') }}" class="part5-foto-back">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Daftar Anggota
            </a>

            <div class="part5-foto-footer-actions">
                <button
                    type="submit"
                    name="aksi"
                    value="draft"
                    class="part5-foto-draft"
                    formnovalidate
                >
                    <i class="far fa-save"></i>
                    Draft
                </button>

                <button
                    type="submit"
                    name="aksi"
                    value="selesai"
                    class="part5-foto-submit"
                >
                    <i class="fas fa-check-circle"></i>
                    Simpan &amp; Selesaikan Kuisioner
                </button>
            </div>
        </div>
    </form>
</div>

{{-- MODAL PILIH SUMBER FOTO --}}
<div
    id="foto-choice-overlay"
    class="foto-choice-overlay"
    role="dialog"
    aria-modal="true"
    aria-labelledby="foto-choice-heading"
>
    <div class="foto-choice-dialog">
        <button
            type="button"
            id="foto-choice-close"
            class="foto-choice-close"
            aria-label="Tutup"
        >&times;</button>

        <h3 id="foto-choice-heading" class="foto-choice-heading">
            Tambahkan Foto
        </h3>

        <p class="foto-choice-description">
            Pilih cara untuk menambahkan dokumentasi foto rumah.
        </p>

        <div class="foto-choice-options">
            <button type="button" id="foto-choice-camera" class="foto-choice-option">
                
            <span class="foto-choice-icon">
                <svg viewBox="0 0 24 24" width="30" height="30"
                    fill="none" stroke="currentColor" stroke-width="1.8"
                    stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true">
                    <path d="M14 4H10L8 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-4l-2-3Z"/>
                    <circle cx="12" cy="13" r="4"/>
                </svg>
            </span>

                <span class="foto-choice-option-label">Gunakan Kamera</span>
            </button>

            <button type="button" id="foto-choice-gallery" class="foto-choice-option">
                
                <span class="foto-choice-icon">
                    <svg viewBox="0 0 24 24" width="30" height="30"
                        fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <rect x="3" y="3" width="18" height="18" rx="3"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <path d="m21 15-5-5L5 21"/>
                    </svg>
                </span>

                <span class="foto-choice-option-label">Galeri / File</span>
            </button>
        </div>
    </div>
</div>

{{-- MODAL KAMERA --}}
<div
    id="kamera-overlay"
    class="kamera-overlay"
    role="dialog"
    aria-modal="true"
    aria-label="Kamera foto rumah"
>
    <div class="kamera-title" id="kamera-title">Ambil Foto Rumah</div>

    <video
        id="kamera-video"
        class="kamera-video"
        autoplay
        playsinline
        muted
    ></video>

    <canvas id="kamera-canvas" style="display:none;"></canvas>

    <div class="kamera-status" id="kamera-status">
        Arahkan kamera ke bagian rumah yang ingin difoto.
    </div>

    <div class="kamera-controls">
        <button type="button" id="kamera-ambil" class="kamera-btn kamera-ambil">
            <i class="fas fa-camera"></i>
            Ambil Foto
        </button>

        <button type="button" id="kamera-switch" class="kamera-btn kamera-switch">
            <i class="fas fa-sync-alt"></i>
            Ganti Kamera
        </button>

        <button type="button" id="kamera-tutup" class="kamera-btn kamera-tutup">
            Tutup
        </button>
    </div>
</div>

{{-- MODAL LIHAT FOTO --}}
<div
    id="foto-viewer-overlay"
    class="foto-viewer-overlay"
    role="dialog"
    aria-modal="true"
    aria-label="Pratinjau foto"
>
    <div class="foto-viewer-content">
        <button
            type="button"
            id="foto-viewer-close"
            class="foto-viewer-close"
            aria-label="Tutup"
        >&times;</button>

        <div id="foto-viewer-title" class="foto-viewer-title">
            Pratinjau Foto
        </div>

        <img id="foto-viewer-image" src="" alt="Pratinjau foto rumah">
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const choiceOverlay = document.getElementById('foto-choice-overlay');
    const choiceHeading = document.getElementById('foto-choice-heading');
    const choiceCamera = document.getElementById('foto-choice-camera');
    const choiceGallery = document.getElementById('foto-choice-gallery');
    const choiceClose = document.getElementById('foto-choice-close');

    const cameraOverlay = document.getElementById('kamera-overlay');
    const video = document.getElementById('kamera-video');
    const canvas = document.getElementById('kamera-canvas');
    const cameraTitle = document.getElementById('kamera-title');
    const cameraStatus = document.getElementById('kamera-status');
    const captureButton = document.getElementById('kamera-ambil');
    const switchButton = document.getElementById('kamera-switch');
    const closeCameraButton = document.getElementById('kamera-tutup');

    const viewerOverlay = document.getElementById('foto-viewer-overlay');
    const viewerTitle = document.getElementById('foto-viewer-title');
    const viewerImage = document.getElementById('foto-viewer-image');
    const viewerClose = document.getElementById('foto-viewer-close');

    const labels = {
        tampak_depan: 'Tampak Depan Rumah',
        ruang_tamu: 'Ruang Tamu',
        kamar_mandi: 'Kamar Mandi'
    };

    let activeStream = null;
    let activeInput = null;
    let activeThumbnail = null;
    let activeFilename = null;
    let selectedField = null;
    let cameraFacing = 'environment';
    let cameraBusy = false;
    let previousBodyOverflow = '';

    function lockPage() {
        if (
            !choiceOverlay.classList.contains('active') &&
            !cameraOverlay.classList.contains('active') &&
            !viewerOverlay.classList.contains('active')
        ) {
            previousBodyOverflow = document.body.style.overflow;
        }

        document.body.style.overflow = 'hidden';
    }

    function restorePageScroll() {
        if (
            !choiceOverlay.classList.contains('active') &&
            !cameraOverlay.classList.contains('active') &&
            !viewerOverlay.classList.contains('active')
        ) {
            document.body.style.overflow = previousBodyOverflow;
        }
    }

    function setActiveField(fieldName) {
        const card = document.querySelector(
            '.part5-foto-card[data-foto="' + fieldName + '"]'
        );

        if (!card) {
            return false;
        }

        selectedField = fieldName;
        activeInput = card.querySelector('input[name="' + fieldName + '"]');
        activeThumbnail = card.querySelector('[data-thumbnail="' + fieldName + '"]');
        activeFilename = card.querySelector('[data-filename="' + fieldName + '"]');

        return true;
    }

    function openChoice(fieldName) {
        if (!setActiveField(fieldName)) {
            return;
        }

        choiceHeading.textContent =
            'Tambah Foto — ' + (labels[fieldName] || 'Rumah');

        choiceOverlay.classList.add('active');
        lockPage();
    }

    function closeChoice() {
        choiceOverlay.classList.remove('active');
        restorePageScroll();
    }

    function stopCamera() {
        if (activeStream) {
            activeStream.getTracks().forEach(function (track) {
                track.stop();
            });

            activeStream = null;
        }

        video.srcObject = null;
    }

    function closeCamera() {
        stopCamera();
        cameraOverlay.classList.remove('active');
        cameraBusy = false;
        captureButton.disabled = false;
        restorePageScroll();
    }

    async function startCamera(facingMode) {
        if (
            !navigator.mediaDevices ||
            !navigator.mediaDevices.getUserMedia
        ) {
            const message =
                'Kamera tidak tersedia. Gunakan HTTPS atau localhost, lalu coba kembali.';

            cameraStatus.textContent = message;
            alert(message);
            return;
        }

        stopCamera();
        cameraStatus.textContent = 'Menghubungkan kamera...';
        captureButton.disabled = true;

        try {
            activeStream = await navigator.mediaDevices.getUserMedia({
                audio: false,
                video: {
                    facingMode: { ideal: facingMode },
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                }
            });

            video.srcObject = activeStream;
            cameraFacing = facingMode;

            await video.play();

            cameraStatus.textContent =
                'Pastikan objek terlihat jelas, kemudian tekan Ambil Foto.';

            captureButton.disabled = false;
        } catch (error) {
            stopCamera();

            let message =
                'Kamera gagal dibuka. Periksa izin kamera pada browser.';

            if (error.name === 'NotAllowedError') {
                message =
                    'Akses kamera ditolak. Izinkan penggunaan kamera melalui pengaturan browser.';
            } else if (error.name === 'NotFoundError') {
                message = 'Kamera tidak ditemukan pada perangkat ini.';
            } else if (error.name === 'NotReadableError') {
                message =
                    'Kamera sedang digunakan aplikasi lain. Tutup aplikasi tersebut lalu coba kembali.';
            }

            cameraStatus.textContent = message;
            alert(message);
            captureButton.disabled = true;
        }
    }

    async function openCamera() {
        closeChoice();

        if (!activeInput || !activeThumbnail) {
            return;
        }

        cameraTitle.textContent =
            'Ambil Foto — ' + (labels[selectedField] || 'Rumah');

        cameraFacing = 'environment';
        cameraOverlay.classList.add('active');
        lockPage();

        await startCamera(cameraFacing);
    }

    function displayThumbnail(file, fieldName) {
        if (!activeThumbnail || !file) {
            return;
        }

        const image = document.createElement('img');
        image.alt = labels[fieldName] || fieldName;
        image.src = URL.createObjectURL(file);
        image.dataset.viewTitle = labels[fieldName] || 'Foto Rumah';

        activeThumbnail.replaceChildren(image);

        if (activeFilename) {
            activeFilename.textContent = file.name;
        }
    }

    /* Buka modal pilihan sumber foto */
    document.querySelectorAll('[data-foto-choice]').forEach(function (button) {
        button.addEventListener('click', function () {
            openChoice(button.dataset.fotoChoice);
        });
    });

    /* Pilih kamera */
    choiceCamera.addEventListener('click', openCamera);

    /* Pilih galeri atau file */
    choiceGallery.addEventListener('click', function () {
        if (!activeInput) {
            return;
        }

        const input = activeInput;

        closeChoice();
        input.click();
    });

    choiceClose.addEventListener('click', closeChoice);

    choiceOverlay.addEventListener('click', function (event) {
        if (event.target === choiceOverlay) {
            closeChoice();
        }
    });

    /* Ambil foto dari kamera */
    captureButton.addEventListener('click', function () {
        if (
            cameraBusy ||
            !activeStream ||
            !video.videoWidth ||
            !video.videoHeight ||
            !activeInput ||
            !activeThumbnail
        ) {
            return;
        }

        cameraBusy = true;
        captureButton.disabled = true;

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        const context = canvas.getContext('2d');

        if (!context) {
            cameraBusy = false;
            captureButton.disabled = false;
            alert('Gagal memproses foto. Silakan coba kembali.');
            return;
        }

        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        canvas.toBlob(function (blob) {
            if (!blob) {
                cameraBusy = false;
                captureButton.disabled = false;
                alert('Gagal mengambil foto. Silakan coba kembali.');
                return;
            }

            const fieldName = activeInput.name;

            const file = new File(
                [blob],
                fieldName + '-' + Date.now() + '.jpg',
                {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                }
            );

            const transfer = new DataTransfer();
            transfer.items.add(file);
            activeInput.files = transfer.files;

            displayThumbnail(file, fieldName);
            closeCamera();
        }, 'image/jpeg', 0.9);
    });

    /* Beralih kamera depan dan belakang */
    switchButton.addEventListener('click', async function () {
        const nextFacing =
            cameraFacing === 'environment' ? 'user' : 'environment';

        await startCamera(nextFacing);
    });

    closeCameraButton.addEventListener('click', closeCamera);

    cameraOverlay.addEventListener('click', function (event) {
        if (event.target === cameraOverlay) {
            closeCamera();
        }
    });

    /* Pratinjau gambar yang dipilih dari galeri/file */
    document.querySelectorAll('.part5-foto-input').forEach(function (input) {
        input.addEventListener('change', function () {
            if (!input.files || !input.files[0]) {
                return;
            }

            const file = input.files[0];

            if (!file.type.startsWith('image/')) {
                alert('File harus berupa gambar.');
                input.value = '';
                return;
            }

            setActiveField(input.name);
            displayThumbnail(file, input.name);
        });
    });

    /* Klik foto untuk melihat ukuran lebih besar */
    document.querySelectorAll('.part5-foto-thumbnail').forEach(function (thumbnail) {
        thumbnail.addEventListener('click', function () {
            const image = thumbnail.querySelector('img');

            if (!image) {
                return;
            }

            viewerImage.src = image.src;
            viewerTitle.textContent =
                image.dataset.viewTitle || 'Pratinjau Foto';

            viewerOverlay.classList.add('active');
            lockPage();
        });
    });

    function closeViewer() {
        viewerOverlay.classList.remove('active');
        viewerImage.src = '';
        restorePageScroll();
    }

    viewerClose.addEventListener('click', closeViewer);

    viewerOverlay.addEventListener('click', function (event) {
        if (event.target === viewerOverlay) {
            closeViewer();
        }
    });

    /* Escape untuk menutup modal aktif */
    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') {
            return;
        }

        if (cameraOverlay.classList.contains('active')) {
            closeCamera();
        } else if (choiceOverlay.classList.contains('active')) {
            closeChoice();
        } else if (viewerOverlay.classList.contains('active')) {
            closeViewer();
        }
    });

    /* Hentikan kamera saat meninggalkan halaman */
    window.addEventListener('pagehide', stopCamera);
});
</script>
@endsection
