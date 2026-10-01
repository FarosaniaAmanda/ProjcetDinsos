@extends('admin.kuisioner.layout')

@section('content')

<style>
    .part5-foto-wrapper {
        width: 100%;
    }

    .part5-foto-info {
        background: #f5f7ff;
        border: 1px solid #dfe3f5;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 18px;
    }

    .part5-foto-title {
        font-size: 15px;
        font-weight: 700;
        color: #252A86;
        margin-bottom: 5px;
    }

    .part5-foto-description {
        font-size: 13px;
        color: #666;
        margin: 0;
        line-height: 1.5;
    }

    .part5-foto-card {
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 14px;
    }

    .part5-foto-question {
        display: flex;
        gap: 8px;
        font-size: 15px;
        font-weight: 600;
        color: #333;
        margin-bottom: 12px;
    }

    .part5-foto-number {
        min-width: 25px;
        font-size: 11px;
        font-weight: 700;
        color: #252A86;
    }

    .part5-foto-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #444;
        margin-bottom: 8px;
    }

    .part5-foto-input {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #d8d8d8;
        border-radius: 6px;
        font-size: 14px;
        background: #fff;
    }

    .part5-foto-input:focus {
        outline: none;
        border-color: #252A86;
        box-shadow: 0 0 0 2px rgba(37, 42, 134, .08);
    }

    .part5-foto-preview {
        margin-top: 12px;
    }

    .part5-foto-preview img {
        display: block;
        width: 180px;
        height: 120px;
        object-fit: cover;
        border-radius: 7px;
        border: 1px solid #ddd;
    }

    .part5-foto-existing {
        font-size: 11px;
        color: #087443;
        margin-top: 6px;
    }

    .part5-foto-error {
        margin-top: 6px;
        font-size: 11px;
        color: #dc3545;
    }

    .part5-foto-note {
        background: #fff8e8;
        border: 1px solid #f1dfad;
        border-radius: 7px;
        padding: 9px 11px;
        font-size: 11px;
        color: #8a6200;
        margin-top: 15px;
        line-height: 1.5;
    }

    .part5-foto-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-top: 20px;
        padding-bottom: 20px;
    }

    .part5-foto-back,
    .part5-foto-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 15px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }

    .part5-foto-back {
        background: #eeeeee;
        color: #444;
    }

    .part5-foto-back:hover {
        background: #e2e2e2;
        color: #333;
    }

    .part5-foto-submit {
        background: #252A86;
        color: #fff;
    }

    .part5-foto-submit:hover {
        background: #1d216d;
    }

    @media (max-width: 768px) {
        .part5-foto-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .part5-foto-back,
        .part5-foto-submit {
            width: 100%;
        }
    }
</style>

<div class="part5-foto-wrapper">

    {{-- INFORMASI --}}
    <div class="part5-foto-info">

        <div class="part5-foto-title">
            Part 5 — Foto Rumah
        </div>

        <p class="part5-foto-description">
            Silakan upload foto kondisi rumah sesuai dengan bagian yang diminta.
        </p>

    </div>


    <form
        action="{{ route('kuisioner.part5.foto.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf


        {{-- 69. FOTO TAMPAK DEPAN --}}
        <div class="part5-foto-card">

            <div class="part5-foto-question">
                <span class="part5-foto-number">69.</span>
                <span>Foto Rumah — Tampak Depan</span>
            </div>

            <label class="part5-foto-label">
                Upload Foto Tampak Depan
            </label>

            <input
                type="file"
                name="tampak_depan"
                class="part5-foto-input"
                accept="image/jpeg,image/png"
                required
            >

            @if (isset($fotoRumah['tampak_depan']))
                <div class="part5-foto-existing">
                    ✓ Foto sebelumnya sudah tersimpan.
                </div>

                <div class="part5-foto-preview">
                    <img
                        src="{{ asset('storage/' . $fotoRumah['tampak_depan']->path_file) }}"
                        alt="Foto Tampak Depan"
                    >
                </div>
            @endif

            @error('tampak_depan')
                <div class="part5-foto-error">
                    {{ $message }}
                </div>
            @enderror

        </div>


        {{-- FOTO RUANG TAMU --}}
        <div class="part5-foto-card">

            <div class="part5-foto-question">
                <span class="part5-foto-number">69.</span>
                <span>Foto Rumah — Ruang Tamu</span>
            </div>

            <label class="part5-foto-label">
                Upload Foto Ruang Tamu
            </label>

            <input
                type="file"
                name="ruang_tamu"
                class="part5-foto-input"
                accept="image/jpeg,image/png"
                required
            >

            @if (isset($fotoRumah['ruang_tamu']))
                <div class="part5-foto-existing">
                    ✓ Foto sebelumnya sudah tersimpan.
                </div>

                <div class="part5-foto-preview">
                    <img
                        src="{{ asset('storage/' . $fotoRumah['ruang_tamu']->path_file) }}"
                        alt="Foto Ruang Tamu"
                    >
                </div>
            @endif

            @error('ruang_tamu')
                <div class="part5-foto-error">
                    {{ $message }}
                </div>
            @enderror

        </div>


        {{-- FOTO KAMAR MANDI --}}
        <div class="part5-foto-card">

            <div class="part5-foto-question">
                <span class="part5-foto-number">69.</span>
                <span>Foto Rumah — Kamar Mandi</span>
            </div>

            <label class="part5-foto-label">
                Upload Foto Kamar Mandi
            </label>

            <input
                type="file"
                name="kamar_mandi"
                class="part5-foto-input"
                accept="image/jpeg,image/png"
                required
            >

            @if (isset($fotoRumah['kamar_mandi']))
                <div class="part5-foto-existing">
                    ✓ Foto sebelumnya sudah tersimpan.
                </div>

                <div class="part5-foto-preview">
                    <img
                        src="{{ asset('storage/' . $fotoRumah['kamar_mandi']->path_file) }}"
                        alt="Foto Kamar Mandi"
                    >
                </div>
            @endif

            @error('kamar_mandi')
                <div class="part5-foto-error">
                    {{ $message }}
                </div>
            @enderror

        </div>


        {{-- CATATAN --}}
        <div class="part5-foto-note">
            Format foto yang diperbolehkan: JPG, JPEG, atau PNG.
            Ukuran maksimal setiap foto adalah 5 MB.
        </div>


        {{-- FOOTER --}}
        <div class="part5-foto-footer">

            <a
                href="{{ route('kuisioner.part5') }}"
                class="part5-foto-back"
            >
                ← Kembali ke Daftar Anggota
            </a>

            <button
                type="submit"
                class="part5-foto-submit"
            >
                Simpan & Selesaikan Kuisioner
            </button>

        </div>

    </form>

</div>

@endsection